<?php
/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Store_Track' ) ) {
	class RAPL_Store_Track implements RAPL_Store {
		public function get( int $id, array|string $args = [] ): ?RAPL_Object_Track {
			global $wpdb;

			$fields = [
				"t.id AS track_id",
				"a.id AS artist_id",
				"a.name AS artist_name",
				"t.title",
				"t.length",
				"t.art_url",
				"t.count AS playback_count",
			];

			$fields = implode( ', ', $fields );

			$record = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT $fields FROM {$wpdb->prefix}rapl_tracks AS t" .
					" INNER JOIN {$wpdb->prefix}rapl_artists AS a ON a.id=t.artist_id" .
					" WHERE t.id=%d LIMIT 0, 1",
					$id
				)
			);

			if ( ! $record ) {
				return null;
			}

			$object = RAPL_Object_Track::import( $record );
			$artist = rapl()->stores->artist->get( $object->artist_id );
			if ( $artist ) {
				$object->set_youtube( $object->track_id, $artist->artist_name, $object->title );
			}

			return $object;
		}

		public function find( int $id ): int {
			global $wpdb;

			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_tracks WHERE id=%d LIMIT 0, 1",
					$id
				)
			);
		}

		public function insert( array $data ): int {
			global $wpdb;

			$wpdb->insert(
				"{$wpdb->prefix}rapl_tracks",
				[
					'id'        => $data['id'],
					'artist_id' => $data['artist_id'],
					'title'     => $data['title'],
					'length'    => $data['length'],
					'art_url'   => $data['art_url'],
					'count'     => $data['count'],
				],
				[
					'id'        => '%d',
					'artist_id' => '%d',
					'title'     => '%s',
					'length'    => '%d',
					'art_url'   => '%s',
					'count'     => '%d',
				]
			);

			return $wpdb->insert_id;
		}

		public function query( array|string $args = [] ): RAPL_Object_Query_Results {
			global $wpdb;

			$defaults = [
				'artist_id' => 0,
				'page'      => 1,
				'per_page'  => 10,
				'orderby'   => 'title:asc',
			];

			$args      = wp_parse_args( $args, $defaults );
			$artist_id = absint( $args['artist_id'] );
			$page      = max( 1, $args['page'] );
			$per_page  = min( 100, max( 1, $args['per_page'] ) );

			$fields = [
				't.id AS track_id',
				't.artist_id',
				't.title',
				't.length',
				't.art_url',
				't.count AS playback_count',
			];

			$f      = implode( ', ', $fields );
			$offset = ( $page - 1 ) * $per_page;
			$limit  = $wpdb->prepare( "LIMIT %d, %d", $offset, $per_page );
			$order  = self::parse_orderby( $args['orderby'] );

			$where = 'WHERE 1=1';
			if ( $artist_id ) {
				$where .= $wpdb->prepare( ' AND t.artist_id=%d', $artist_id );
			}

			$orderby = match ( $order[0] ) {
				           'title'          => 't.title',
				           'playback_count' => 't.count',
			           } . " $order[1]";

			$query = $wpdb->prepare(
				"SELECT SQL_CALC_FOUND_ROWS $f FROM {$wpdb->prefix}rapl_tracks AS t" .
				" $where ORDER BY $orderby $limit",
				$artist_id
			);

			$wpdb->timer_start();
			$results    = $wpdb->get_results( $query );
			$time       = $wpdb->timer_stop();
			$found_rows = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );
			$records    = array_map( [ RAPL_Object_Track::class, 'import' ], $results );

			if ( $artist_id ) {
				$artist = rapl()->stores->artist->get( $artist_id );
				foreach ( $records as $record ) {
					/** @var RAPL_Object_Track $record */
					$record->set_youtube( $record->track_id, $artist->artist_name, $record->title );
				}
			}

			return RAPL_Object_Query_Results::create(
				items: $records,
				per_page: $per_page,
				page: $page,
				total: $found_rows,
				time_spent: $time
			);
		}

		public function first_fetch( int $track_id ): int {
			return (int) $this->aggrigate_query( $track_id, 'MIN' );
		}

		public function last_fetch( int $track_id ): int {
			return (int) $this->aggrigate_query( $track_id, 'MAX' );
		}

		public function add_count( int $track_id, int $amount = 1 ): void {
			global $wpdb;

			$query = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}rapl_tracks SET count=count+%d WHERE id=%d",
				$amount,
				$track_id
			);

			$wpdb->query( $query );
		}

		private static function parse_orderby( string $orderby ): array {
			$exploded = explode( ':', sanitize_text_field( $orderby ), 2 );

			if ( 0 === count( $exploded ) ) {
				return [ 'title', 'asc' ];
			} elseif ( 1 === count( $exploded ) ) {
				$order = 'asc';
			} else {
				$order = 'desc' === strtolower( $exploded[1] ) ? 'desc' : 'asc';
			}

			$field = strtolower( $exploded[0] );

			if ( ! in_array( $field, [ 'title', 'playback_count' ] ) ) {
				$field = 'title';
			}

			return [ $field, $order ];
		}

		private function aggrigate_query( int $track_id, string $func ): string {
			global $wpdb;

			$date = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT $func(h.started) FROM {$wpdb->prefix}rapl_tracks AS t" .
					" INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
					" WHERE h.track_id=%d",
					$track_id
				)
			);

			return $date ?: '';
		}
	}
}
