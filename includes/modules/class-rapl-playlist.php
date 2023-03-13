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

		public function collect( array $items ): void {
			global $wpdb;

			if ( ! $items ) {
				return;
			}

			foreach ( $items as $item ) {
				$track = RAPL_Object_Track::from_array( $item );

				if ( 'track' !== $track->type ) {
					continue;
				}

				// Add artist data and get artist id.
				$artist_id = (int) $wpdb->get_var(
					$wpdb->prepare(
						"SELECT id FROM {$wpdb->prefix}rapl_artists WHERE name=%s",
						$track->artist
					)
				);
				if ( ! $artist_id ) {
					$wpdb->insert( "{$wpdb->prefix}rapl_artists", [ 'name' => $track->artist ] );
					$artist_id = $wpdb->insert_id;
				}

				// Get track id.
				$track_id = (int) $wpdb->get_var(
					$wpdb->prepare(
						"SELECT id FROM {$wpdb->prefix}rapl_tracks WHERE id=%d",
						$track->track_id
					)
				);
				if ( ! $track_id ) {
					$wpdb->insert(
						"{$wpdb->prefix}rapl_tracks",
						[
							'id'        => $track->track_id,
							'artist_id' => $artist_id,
							'title'     => $track->title,
							'length'    => $track->length,
							'art_url'   => $track->art_url,
						],
						[
							'id'        => '%d',
							'artist_id' => '%d',
							'title'     => '%s',
							'length'    => '%d',
							'art_url'   => '%s',
						]
					);
					$track_id = $track->track_id;
				}

				// Get history id.
				$history_id = (int) $wpdb->get_var(
					$wpdb->prepare(
						"SELECT id FROM {$wpdb->prefix}rapl_history WHERE track_id=%d AND started=%d",
						$track_id,
						$track->started
					)
				);
				if ( ! $history_id ) {
					$wpdb->insert(
						"{$wpdb->prefix}rapl_history",
						[
							'network_id' => $track->network_id,
							'channel_id' => $track->channel_id,
							'track_id'   => $track_id,
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
				$up      = wp_get_upload_dir();
				$basedir = untrailingslashit( $up['basedir'] );
				$date    = wp_date( 'Ymd-His' );

				$path = "$basedir/rapl-$date.json";
			}

			file_put_contents(
				$path,
				wp_json_encode( $items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
			);
		}

		public function get_channel(): int {
			return 192; // Thrash metal.
		}
	}
}