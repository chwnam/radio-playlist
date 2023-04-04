<?php
/**
 * RAPL: Playlist module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Playlist' ) ) {
	class RAPL_Playlist implements RAPL_Module {
		public function fetch(): array {
			$url      = 'https://api.audioaddict.com/v1/rockradio/track_history/channel/' . $this->get_channel();
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			return 200 === $code && is_array( $body ) ? $body : [];
		}

		public function get_artist( int $track_id ): ?RAPL_Object_Artist {
			$url      = "https://api.audioaddict.com/v1/rockradio/tracks/$track_id";
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 200 === $code && $body ) {
				return RAPL_Object_Artist::from_object( $body->artist );
			}

			return null;
		}

		public function collect( array $items ): void {
			global $wpdb;

			if ( ! $items ) {
				return;
			}

			foreach ( $items as $item ) {
				$track = RAPL_Object_Track::from_object( $item );
				if ( 'track' !== $track->type ) {
					continue;
				}

				$artist = $this->get_artist( $track->track_id );
				sleep( 2 );

				// Insert artist data, if not exists.
				$wpdb->query(
					$wpdb->prepare(
						"INSERT IGNORE INTO {$wpdb->prefix}rapl_artists VALUES(%d, %s)",
						$artist->id,
						$artist->name
					)
				);

				// Insert track data, if not exists.
				$wpdb->query(
					$wpdb->prepare(
						"INSERT IGNORE INTO {$wpdb->prefix}rapl_tracks(id, artist_id, title, length, art_url) VALUES(%d, %d, %s, %d, %s)",
						$track->track_id,
						$artist->id,
						$track->title,
						$track->length,
						$track->art_url
					)
				);

				// Get history id.
				$history_id = (int) $wpdb->get_var(
					$wpdb->prepare(
						"SELECT id FROM {$wpdb->prefix}rapl_history WHERE track_id=%d AND started=%d",
						$track->track_id,
						$track->started
					)
				);
				if ( ! $history_id ) {
					$wpdb->insert(
						"{$wpdb->prefix}rapl_history",
						[
							'network_id' => $track->network_id,
							'channel_id' => $track->channel_id,
							'track_id'   => $track->track_id,
							'started'    => $track->started,
						],
						[
							'network_id' => '%d',
							'channel_id' => '%d',
							'track_id'   => '%d',
							'started'    => '%d',
						]
					);
				}
			}
		}

		public function dump( array $items, string $path = '' ): void {
			if ( ! $path ) {
				$basedir = rapl_get_upload_private_directory( 'dump' );
				$date    = wp_date( 'Ymd-His' );
				$path    = "$basedir/rapl-$date.json";
			}

			$encoded = wp_json_encode( $items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

			file_put_contents( $path, $encoded );
		}

		public function get_channel(): int {
			return 192; // Thrash metal.
		}

		public function query( array $args = [] ): RAPL_Object_Track_Query {
			global $wpdb;

			$defaults = [
				'page'     => 1,
				'per_page' => 10,
				'search'   => '',
			];

			$args     = wp_parse_args( $args, $defaults );
			$page     = max( 1, $args['page'] );
			$per_page = min( 100, max( 1, $args['per_page'] ) );

			$fields = [
				"h.network_id",
				"h.channel_id",
				"t.id AS track_id",
				"t.title",
				"a.id AS artist_id",
				"a.name AS artist_name",
				"t.length",
				"h.started",
				"t.art_url",
			];

			$f      = implode( ', ', $fields );
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

			$query = "SELECT SQL_CALC_FOUND_ROWS $f FROM {$wpdb->prefix}rapl_artists AS a" .
			         " INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
			         " INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
			         " $where ORDER BY h.started DESC $limit";

			$wpdb->timer_start();
			$rows       = $wpdb->get_results( $query );
			$time       = $wpdb->timer_stop();
			$found_rows = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );
			$records    = array_map( [ RAPL_Object_Track_History::class, 'from_object' ], $rows );

			$result              = new RAPL_Object_Track_Query();
			$result->items       = $records;
			$result->per_page    = $per_page;
			$result->page        = $page;
			$result->total       = $found_rows;
			$result->total_pages = (int) ceil( (float) $found_rows / (float) $per_page );
			$result->time_spent  = $time;

			return $result;
		}
	}
}