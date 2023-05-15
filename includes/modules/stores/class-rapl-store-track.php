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

			return $record ? RAPL_Object_Track::import( $record ) : null;
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

		public function insert( array $data ): RAPL_Object_Track {
			global $wpdb;

			$wpdb->insert(
				"{$wpdb->prefix}rapl_tracks",
				[
					'id'        => $data['id'],
					'artist_id' => $data['artist_id'],
					'title'     => $data['title'],
					'length'    => $data['length'],
					'art_url'   => $data['art_url'],
				],
				[
					'id'        => '%d',
					'artist_id' => '%d',
					'title'     => '%s',
					'length'    => '%d',
					'art_url'   => '%s',
				]
			);

			return $this->get( $wpdb->insert_id );
		}

		public function first_fetch( int $track_id ): string {
			return $this->aggrigate_query( $track_id, 'MIN' );
		}

		public function last_fetch( int $track_id ): string {
			return $this->aggrigate_query( $track_id, 'MAX' );
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
