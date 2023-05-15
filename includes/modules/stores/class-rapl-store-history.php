<?php
/**
 * RAPL: Playlist module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Store_History' ) ) {
	class RAPL_Store_History implements RAPL_Store {
		public function get( int $id, array|string $args = [] ): ?RAPL_Object_History {
			global $wpdb;

			$fields = static::fields();
			$record = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT $fields FROM {$wpdb->prefix}rapl_history AS h" .
					" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.id=h.track_id" .
					" INNER JOIN {$wpdb->prefix}rapl_artists AS a ON a.id=t.artist_id" .
					" WHERE h.id=%d LIMIT 0, 1",
					$id
				)
			);

			return $record ? RAPL_Object_History::import( $record ) : null;
		}

		public function find( int $track_id, int $started ): int {
			global $wpdb;

			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_history WHERE track_id=%d AND started=%d LIMIT 0, 1",
					$track_id,
					$started
				)
			);
		}

		public function insert( array $data ): RAPL_Object_History {
			global $wpdb;

			$wpdb->insert(
				"{$wpdb->prefix}rapl_history",
				[
					'network_id' => $data['network_id'],
					'channel_id' => $data['channel_id'],
					'track_id'   => $data['track_id'],
					'started'    => $data['started'],
				],
				[
					'network_id' => '%d',
					'channel_id' => '%d',
					'track_id'   => '%d',
					'started'    => '%d',
				]
			);

			return $this->get( $wpdb->insert_id );
		}

		/**
		 * 데이터베이스에 저장된 트랙 정보를 쿼리.
		 *
		 * @param array|string $args
		 *
		 * @return RAPL_Object_Query_Results
		 */
		public function query( array|string $args = [] ): RAPL_Object_Query_Results {
			global $wpdb;

			$defaults = [
				'artist_id'  => 0,
				'channel_id' => 0,
				'page'       => 1,
				'per_page'   => 10,
				'search'     => '',
				'track_id'   => 0,
			];

			$args     = wp_parse_args( $args, $defaults );
			$page     = max( 1, $args['page'] );
			$per_page = min( 100, max( 1, $args['per_page'] ) );

			$fields = static::fields();;
			$offset = ( $page - 1 ) * $per_page;
			$limit  = $wpdb->prepare( "LIMIT %d, %d", $offset, $per_page );
			$where  = "WHERE 1=1";

			if ( $args['search'] ) {
				$like  = esc_sql( '%' . $wpdb->esc_like( $args['search'] ) . '%' );
				$where .= $wpdb->prepare(
					" AND ((a.name LIKE %s) OR (t.title LIKE %s))",
					$like,
					$like
				);
			}

			if ( $args['artist_id'] ) {
				$where .= $wpdb->prepare( " AND a.id=%d", $args['artist_id'] );
			}

			if ( $args['channel_id'] ) {
				$where .= $wpdb->prepare( " AND h.channel_id=%d", $args['channel_id'] );
			}

			if ( $args['track_id'] ) {
				$where .= $wpdb->prepare( " AND t.id=%d", $args['track_id'] );
			}

			$query = "SELECT SQL_CALC_FOUND_ROWS $fields FROM {$wpdb->prefix}rapl_artists AS a" .
			         " INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
			         " INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
			         " $where ORDER BY h.started DESC $limit";

			$wpdb->timer_start();
			$rows       = $wpdb->get_results( $query );
			$time       = $wpdb->timer_stop();
			$found_rows = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );
			$records    = array_map( [ RAPL_Object_History::class, 'import' ], $rows );

			return RAPL_Object_Query_Results::create(
				items: $records,
				per_page: $per_page,
				page: $page,
				total: $found_rows,
				time_spent: $time
			);
		}

		protected static function fields(): string {
			return implode(
				', ',
				[
					"h.network_id",
					"h.channel_id",
					'h.id AS history_id',
					"t.id AS track_id",
					"t.title",
					"a.id AS artist_id",
					"a.name AS artist_name",
					"t.length",
					"h.started",
					"t.art_url",
				]
			);
		}
	}
}
