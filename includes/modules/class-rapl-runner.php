<?php
/**
 * RAPL: Runnner module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Runner' ) ) {
	class RAPL_Runner implements RAPL_Module {
		use RAPL_Hook_Impl;

		public function __construct() {
			/** @uses rapl_playlist() */
			$this->add_action( 'rapl_playlist' );
		}

		public function rapl_playlist() {
			$playlist = $this->get_playlist();

			$this->dump_playlist( $playlist );
			$this->collect_playlist( $playlist );
		}

		public function get_playlist(): array {
			$url      = 'https://api.audioaddict.com/v1/rockradio/track_history/channel/' . $this->get_channel();
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			return 200 === $code && is_array( $body ) ? $body : [];
		}

		public function get_channel(): int {
			return 192; // Thrash metal.
		}

		protected function dump_playlist( array $playlist ): void {
			if ( 'local' === wp_get_environment_type() || 'development' === wp_get_environment_type() ) {
				$up   = wp_get_upload_dir();
				$path = untrailingslashit( $up['basedir'] );
				$date = wp_date( 'Ymd-His' );

				if ( $playlist ) {
					file_put_contents(
						"$path/rapl-$date.json",
						wp_json_encode( $playlist, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
					);
				}
			}
		}

		protected function collect_playlist( array $playlist ): void {
			if ( ! $playlist ) {
				return;
			}

			$tracks = [];

			foreach ( $playlist as $item ) {
				if ( 'track' === ( $item->type ?? '' ) ) {
					$tracks[] = RAPL_Object_Track::from_array( $item );
				}
			}

			// Add artists.
			$artist_map = $this->add_artists( $tracks );

			// Add tracks.
			$this->add_tracks( $tracks, $artist_map );

			// Add track history.
			$this->add_history( $tracks );
		}

		/**
		 * @param RAPL_Object_Track[] $tracks
		 *
		 * @return array<string, int>
		 */
		protected function add_artists( array $tracks ): array {
			global $wpdb;

			$artist_map = [];

			if ( $tracks ) {
				// Get artists.
				$artists     = wp_list_pluck( $tracks, 'artist' );
				$placeholder = implode( ', ', array_pad( [], count( $artists ), '%s' ) );

				$query = $wpdb->prepare(
					"SELECT name, id FROM {$wpdb->prefix}rapl_artists WHERE name IN ($placeholder)",
					$artists
				);

				$artist_map = $wpdb->get_results( $query, OBJECT_K );

				foreach ( $artists as $artist ) {
					if ( ! isset( $artist_map[ $artist ] ) ) {
						$wpdb->insert( "{$wpdb->prefix}rapl_artists", [ 'name' => $artist ] );
						$artist_map[ $artist ] = $wpdb->insert_id;
					}
				}
			}

			return $artist_map;
		}

		/**
		 * @param RAPL_Object_Track[] $tracks
		 * @param array<string, int>  $artist_map
		 *
		 * @return void
		 */
		protected function add_tracks( array $tracks, array $artist_map ): void {
			global $wpdb;

			if ( $tracks ) {
				// Get track ids.
				$track_ids   = wp_list_pluck( $tracks, 'track_id' );
				$placeholder = implode( ', ', array_pad( [], count( $track_ids ), '%d' ) );

				$query = $wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_tracks WHERE id IN ($placeholder)",
					$track_ids
				);

				$records = $wpdb->get_results( $query, OBJECT_K );

				foreach ( $tracks as $track ) {
					if ( ! isset( $records[ $track->track_id ] ) && isset( $artist_map[ $track->artist ] ) ) {
						$wpdb->insert(
							"{$wpdb->prefix}rapl_tracks",
							[
								'id'        => $track->track_id,
								'artist_id' => $artist_map[ $track->artist ],
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
					}
				}

			}
		}

		/**
		 * @param RAPL_Object_Track[] $tracks
		 *
		 * @return void
		 */
		protected function add_history( array $tracks ): void {
			global $wpdb;

			foreach ( $tracks as $track ) {
				$query = $wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_history WHERE track_id=%d AND started=%d LIMIT 0, 1",
					$track->track_id,
					$track->started
				);

				$inserted = (bool) $wpdb->get_var( $query );

				if ( ! $inserted ) {
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
	}
}
