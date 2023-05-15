<?php
/**
 * RAPL: History object
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_History' ) ) {
	class RAPL_Object_History implements RAPL_Object {
		public int $network_id = 0;

		public int $channel_id = 0;

		public int $history_id = 0;

		public int $track_id = 0;

		public string $title = '';

		public int $artist_id = 0;

		public string $artist_name = '';

		public int $length = 0;

		public int $started = 0;

		public string $art_url = '';

		/**
		 * @var stdClass{
		 *     video: stdClass{
		 *         direct: string,
		 *         search: string,
		 *     },
		 *     music: stdClass{
		 *         direct: string,
		 *         search:string
		 *     }
		 * }
		 */
		public stdClass $youtube;

		public static function import( array|object $item ): static {
			$instance = RAPL_Import_Helper::import( $item, static::class );

			if ( $instance->track_id ) {
				$instance->youtube->music->direct = RAPL_YouTube::get_direct_url( $instance->track_id, 'music' );
				$instance->youtube->music->search = RAPL_YouTube::get_search_query_url(
					$instance->artist_name,
					$instance->title,
					'music'
				);

				$instance->youtube->video->direct = RAPL_YouTube::get_direct_url( $instance->track_id, 'video' );
				$instance->youtube->video->search = RAPL_YouTube::get_search_query_url(
					$instance->artist_name,
					$instance->title,
					'video'
				);
			}

			return $instance;
		}

		private static function init_youtube( self $instance ): void {
			$instance->youtube = (object) [
				'video' => (object) [
					'direct' => '',
					'search' => '',
				],
				'music' => (object) [
					'direct' => '',
					'search' => '',
				],
			];
		}

		public function __construct() {
			static::init_youtube( $this );
		}
	}
}
