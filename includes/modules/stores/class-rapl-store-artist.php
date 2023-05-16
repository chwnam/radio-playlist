<?php
/**
 * RAPL: Artist store
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Store_Artist' ) ) {
	class RAPL_Store_Artist implements RAPL_Store {
		public function get( int $id, array|string $args = [] ): ?RAPL_Object_Artist {
			global $wpdb;

			$record = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT id AS artist_id, name AS artist_name FROM {$wpdb->prefix}rapl_artists WHERE id=%d LIMIT 0, 1",
					$id
				)
			);

			return $record ? RAPL_Object_Artist::import( $record ) : null;
		}

		public function find( int $id ): int {
			global $wpdb;

			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_artists WHERE id=%d LIMIT 0, 1",
					$id
				)
			);
		}

		public function insert( array $data ): RAPL_Object_Artist {
			global $wpdb;

			$wpdb->insert(
				"{$wpdb->prefix}rapl_artists",
				[
					'id'   => $data['id'],
					'name' => $data['name'],
				],
				[
					'id'   => '%d',
					'name' => '%s',
				]
			);

			return $this->get( $wpdb->insert_id );
		}

		/**
		 * @param array|string $args
		 *
		 * @return RAPL_Object_Query_Results
		 */
		public function playback_counts( array|string $args = [] ): RAPL_Object_Query_Results {
			global $wpdb;

			$defaults = [
				'artist_id' => 0,
				'page'      => 1,
				'per_page'  => 10,
			];

			$args      = wp_parse_args( $args, $defaults );
			$artist_id = absint( $args['artist_id'] );
			$page      = max( 1, $args['page'] );
			$per_page  = min( 100, max( 1, $args['per_page'] ) );

			$fields = [
				't.id AS track_id',
				't.title',
				't.length',
				't.art_url',
				'COUNT(h.track_id) AS playback_count',
			];

			$f      = implode( ', ', $fields );
			$offset = ( $page - 1 ) * $per_page;
			$limit  = $wpdb->prepare( "LIMIT %d, %d", $offset, $per_page );

			$query = $wpdb->prepare(
				"SELECT SQL_CALC_FOUND_ROWS $f FROM {$wpdb->prefix}rapl_artists AS a" .
				" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id=a.id" .
				" INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
				" WHERE a.id=%d GROUP BY h.track_id, t.title ORDER BY t.title $limit",
				$artist_id
			);

			$wpdb->timer_start();
			$results    = $wpdb->get_results( $query );
			$time       = $wpdb->timer_stop();
			$found_rows = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );
			$records    = array_map( [ RAPL_Object_Playback_Count::class, 'import' ], $results );

			if ( $artist_id ) {
				$artist = rapl()->stores->artist->get( $artist_id );
				foreach ( $records as $record ) {
					/** @var RAPL_Object_Playback_Count $record */
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

		public function total_tracks( int $artist_id ): int {
			global $wpdb;

			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}rapl_artists AS a" .
					" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id=a.id" .
					" WHERE a.id=%d",
					$artist_id
				)
			);
		}

		public function total_playbacks( int $artist_id ) : int {
			global $wpdb;

			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}rapl_artists AS a" .
					" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id=a.id" .
					" INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
					" WHERE a.id=%d",
					$artist_id
				)
			);
		}

		public function first_fetch( int $artist_id ): int {
			return (int) $this->aggregate_query( $artist_id, 'MIN' );

		}

		public function last_fetch( int $artist_id ): int {
			return (int) $this->aggregate_query( $artist_id, 'MAX' );
		}

		private function aggregate_query( int $artist_id, string $func ): string {
			global $wpdb;

			$date = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT $func(h.started) FROM {$wpdb->prefix}rapl_artists AS a" .
					" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id=a.id" .
					" INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
					" WHERE a.id=%d",
					$artist_id
				)
			);

			return $date ?: '';
		}
	}
}
