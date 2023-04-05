<?php
/**
 * RAPL: Track local object
 *
 * NOTE: Used for REST APIs.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Track_History' ) ) {
	class RAPL_Object_Track_History {
		public int $network_id = 0;

		public int $channel_id = 0;

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

		public static function from_object( stdClass $item ): RAPL_Object_Track_History {
			$obj = new static();

			$obj->network_id  = $item->network_id ?? 0;
			$obj->channel_id  = $item->channel_id ?? 0;
			$obj->track_id    = $item->track_id ?? 0;
			$obj->title       = $item->title ?? '';
			$obj->artist_id   = $item->artist_id ?? '';
			$obj->artist_name = $item->artist_name ?? '';
			$obj->length      = $item->length ?? 0;
			$obj->started     = $item->started ?? 0;
			$obj->art_url     = $item->art_url ?? '';

			if ( $obj->track_id ) {
				$obj->youtube->music->direct = RAPL_YouTube::get_direct_url( $obj->track_id, 'music' );
				$obj->youtube->video->direct = RAPL_YouTube::get_direct_url( $obj->track_id, 'video' );

				$obj->youtube->video->search = RAPL_YouTube::get_search_query_url(
					$obj->artist_name,
					$obj->title,
					'video'
				);
				$obj->youtube->music->search = RAPL_YouTube::get_search_query_url(
					$obj->artist_name,
					$obj->title,
					'music'
				);
			}

			return $obj;
		}

		public function __construct() {
			$this->youtube = (object) [
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
	}
}
