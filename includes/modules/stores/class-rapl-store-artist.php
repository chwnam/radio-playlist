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

		public function insert( array $data ): int {
			global $wpdb;

			$wpdb->insert(
				"{$wpdb->prefix}rapl_artists",
				[
					'id'    => $data['id'],
					'name'  => $data['name'],
					'count' => $data['count'],
				],
				[
					'id'    => '%d',
					'name'  => '%s',
					'count' => '%d',
				]
			);

			return $wpdb->insert_id;
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

		public function total_playbacks( int $artist_id ): int {
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

		public function add_count( int $artist_id, int $amount = 1 ): void {
			global $wpdb;

			$query = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}rapl_artists SET count=count+%d WHERE id=%d",
				$amount,
				$artist_id
			);

			$wpdb->query( $query );
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
