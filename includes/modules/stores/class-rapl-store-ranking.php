<?php
/**
 * RAPL: Ranking store
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Store_Ranking' ) ) {
	class RAPL_Store_Ranking implements RAPL_Store {
		public function get_results( string $criteria, string $duration, bool $exclude, int $number, string $order ): array {
			return match ( self::sanitize_criteria( $criteria ) ) {
				'artist'  => $this->ranking_artist( $duration, $exclude, $number, $order ),
				'track'   => $this->ranking_track( $duration, $exclude, $number, $order ),
				'shuffle' => $this->ranking_shuffle( $exclude, $number ),
			};
		}

		protected function ranking_artist( string $duration, bool $exclude, int $number, string $order ): array {
			global $wpdb;

			[ $now, $then ] = self::parse_duration( $duration );
			$order = self::sanitize_order( $order );

			$fields = implode(
				', ',
				[
					'a.id AS artist_id',
					'a.name as artist_name',
					'COUNT(a.id) AS `count`',
					"RANK() OVER (ORDER BY COUNT(a.id) $order) AS `ranking`",
				]
			);

			$query = $wpdb->prepare(
				"SELECT {$fields} FROM {$wpdb->prefix}rapl_artists AS a" .
				" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
				" INNER JOIN  {$wpdb->prefix}rapl_history AS h ON h.track_id = t.id" .
				" WHERE 1=1" .
				( $exclude ? " AND a.id NOT IN (SELECT artist_id FROM {$wpdb->prefix}rapl_excluded_artists)" : '' ) .
				" AND %d < h.started AND h.started < %d" .
				" GROUP BY a.id" .
				" ORDER BY COUNT(a.id) $order" .
				" LIMIT 0, %d",
				$then,
				$now,
				$number
			);

			return array_map(
				fn( $r ) => [
					'artist_id'   => (int) $r->artist_id,
					'artist_name' => (string) $r->artist_name,
					'count'       => (int) $r->count,
					'ranking'     => (int) $r->ranking,
				],
				$wpdb->get_results( $query ) ?: []
			);
		}

		protected function ranking_track( string $duration, bool $exclude, int $number, string $order ): array {
			global $wpdb;

			[ $now, $then ] = self::parse_duration( $duration );
			$order = self::sanitize_order( $order );

			$fields = implode(
				', ',
				[
					'a.id AS artist_id',
					'a.name as artist_name',
					't.id AS track_id',
					't.title',
					'COUNT(h.track_id) AS `count`',
					"RANK() OVER (ORDER BY COUNT(h.track_id) $order) AS `ranking`",
				]
			);

			$query = $wpdb->prepare(
				"SELECT {$fields} FROM {$wpdb->prefix}rapl_artists AS a" .
				" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
				" INNER JOIN  {$wpdb->prefix}rapl_history AS h ON h.track_id = t.id" .
				" WHERE 1=1" .
				( $exclude ? " AND a.id NOT IN (SELECT artist_id FROM {$wpdb->prefix}rapl_excluded_artists)" : '' ) .
				" AND %d < h.started AND h.started < %d" .
				" GROUP BY h.track_id" .
				" ORDER BY COUNT(h.track_id) {$order}" .
				" LIMIT 0, %d",
				$then,
				$now,
				$number,
			);

			return array_map(
				fn( $r ) => [
					'artist_id'   => (int) $r->artist_id,
					'artist_name' => (string) $r->artist_name,
					'track_id'    => (int) $r->track_id,
					'title'       => (string) $r->title,
					'count'       => (int) $r->count,
					'ranking'     => (int) $r->ranking,
				],
				$wpdb->get_results( $query ) ?: []
			);
		}

		protected function ranking_shuffle( bool $exclude, int $number ): array {
			global $wpdb;

			$fields = implode(
				', ',
				[
					'a.id AS artist_id',
					'a.name as artist_name',
					't.id AS track_id',
					't.title',
				]
			);

			$query = $wpdb->prepare(
				"SELECT {$fields} FROM {$wpdb->prefix}rapl_artists AS a" .
				" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
				" INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id = t.id" .
				" WHERE 1=1" .
				( $exclude ? " AND a.id NOT IN (SELECT artist_id FROM {$wpdb->prefix}rapl_excluded_artists)" : '' ) .
				" ORDER BY RAND()" .
				" LIMIT 0, %d",
				$number
			);

			return array_map(
				fn( $r ) => [
					'artist_id'   => (int) $r->artist_id,
					'artist_name' => (string) $r->artist_name,
					'track_id'    => (int) $r->track_id,
					'title'       => (string) $r->title,
				],
				$wpdb->get_results( $query ) ?: []
			);
		}

		protected static function parse_duration( string $duration ): array {
			preg_match( '/(\d+)([dmwy])/', $duration, $matches );

			$value = min( 3, max( 1, absint( $matches[1] ) ) );
			$unit  = $matches[2];

			$now = time();

			$then = match ( $unit ) {
				'd' => $now - ( $value * DAY_IN_SECONDS ),
				'm' => $now - ( $value * MONTH_IN_SECONDS ),
				'w' => $now - ( $value * WEEK_IN_SECONDS ),
				'y' => $now - ( $value * YEAR_IN_SECONDS ),
			};

			return [ $now, $then ];
		}

		/**
		 * @throws Exception
		 */
		public function get( int $id, array|string $args = [] ) {
			throw new Exception( 'Not implemented.' );
		}

		/**
		 * @throws Exception
		 */
		public function insert( array $data ) {
			throw new Exception( 'Not implemented.' );
		}

		public static function sanitize_criteria( $v ): string {
			return in_array( $v, [ 'artist', 'track', 'shuffle' ], true ) ? $v : 'artist';
		}

		public static function sanitize_duration( $v ): string {
			return preg_match( '/\d+[dmwy]/', $v ) ? $v : '1w';
		}

		public static function sanitize_exclude( $v ): bool {
			return filter_var( $v, FILTER_VALIDATE_BOOL );
		}

		public static function sanitize_number( $v ): string|int {
			return filter_var( $v, FILTER_VALIDATE_INT, [ 'min_range' => 1, 'max_range' => 100 ] ) ?: 10;
		}

		public static function sanitize_order( $v ): string {
			return 'asc' === strtolower( $v ) ? 'asc' : 'desc';
		}
	}
}
