<?php
/**
 * RAPL: External track history object.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Ext_Track_History' ) ) {
	/**
	 * External track history.
	 *
	 * @sample
	 * [
	 *   {
	 *     "network_id": 13,
	 *     "channel_id": 192,
	 *     "artist": "Soul Collector",
	 *     "display_artist": "Soul Collector",
	 *     "title": "Thrashmageddon",
	 *     "display_title": "Thrashmageddon",
	 *     "track": "Soul Collector - Thrashmageddon",
	 *     "length": 368,
	 *     "duration": 368,
	 *     "started": 1684158077,
	 *     "type": "track",
	 *     "track_id": 2271879,
	 *     "votes": { ... },
	 *     "release": null,
	 *     "art_url": "//cdn-images.audioaddict.com/0/6/a/d/e/c/06adec2aaaefa7e20957ac1758c446b6.jpg",
	 *     "images": {
	 *       "default": "//cdn-images.audioaddict.com/0/6/a/d/e/c/06adec2aaaefa7e20957ac1758c446b6.jpg{?size,height,width,quality,pad}"
	 *     }
	 *   },
	 *   ....
	 * ]
	 */
	class RAPL_Object_Ext_Track_History implements RAPL_Object {
		public int $network_id = 0;

		public int $channel_id = 0;

		public string $artist = '';

		public string $title = '';

		public string $track = '';

		public int $length = 0;

		public int $duration = 0;

		public int $started = 0;

		public string $type = '';

		public int $track_id = 0;

		public string $art_url = '';

		public static function import( array|object $item ): static {
			return RAPL_Import_Helper::import( $item, RAPL_Object_Ext_Track_History::class );
		}
	}
}
