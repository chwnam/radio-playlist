<?php
/**
 * RAPL: Track detailed object
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Artist' ) ) {
	/**
	 * Artist info, extracting from track info API.
	 *
	 * @sample
	 * {
	 *   "id": 2890847,
	 *   "length": 188,
	 *   "title": "Terminal Possesion",
	 *   "version": null,
	 *   "display_title": "Terminal Possesion",
	 *   "display_artist": "Perpetrator",
	 *   "track": "Perpetrator - Terminal Possesion",
	 *   "mix": false,
	 *   "artists": [
	 *     {
	 *       "id": 722830,
	 *       "name": "Perpetrator",
	 *       "slug": "perpetrator",
	 *       "images": {},
	 *       "type": "artist"
	 *     }
	 *   ],
	 *   "release": null,
	 *   "content_accessibility": 0,
	 *   "preview_accessibility": 0,
	 *   "retail_accessibility": 0,
	 *   "retail": {},
	 *   "release_date": null,
	 *   "waveform_url": "//waveform.audioaddict.com/prd/9/2/f/6/8/ae0835a472d69775a1b99ddb41e1c952bab.json",
	 *   "track_container_id": null,
	 *   "isrc": null,
	 *   "parental_advisory": null,
	 *   "details_url": "https://www.rockradio.com/tracks/2890847",
	 *   "images": {
	 *     "default": "//cdn-images.audioaddict.com/3/c/b/d/1/5/3cbd15a8a2b7b03e124138e268fc743a.png{?size,height,width,quality,pad}"
	 *   },
	 *   "votes": {
	 *     "up": 37,
	 *     "down": 4,
	 *     "who_upvoted": {
	 *       "size": 1640,
	 *       "hashes": 30,
	 *       "seed": 1680536920,
	 *       "bits": [
	 *         3775998366,
	 *         411181413,
	 *         ...
	 *         43
	 *       ],
	 *       "items": null
	 *     },
	 *     "who_downvoted": {
	 *       "size": 216,
	 *       "hashes": 30,
	 *       "seed": 1680536920,
	 *       "bits": [
	 *         3421270232,
	 *         ...
	 *         9105714
	 *       ],
	 *       "items": null
	 *     }
	 *   },
	 *   "content": {},
	 *   "preview": null,
	 *   "is_show_asset": false,
	 *   "artist": {
	 *     "id": 722830,
	 *     "name": "Perpetrator",
	 *     "asset_url": null,
	 *     "images": {}
	 *   },
	 *   "asset_url": "//cdn-images.audioaddict.com/3/c/b/d/1/5/3cbd15a8a2b7b03e124138e268fc743a.png"
	 * }
	 */
	class RAPL_Object_Artist {
		public int $id = 0; // Artist ID.

		public string $name = '';

		public static function from_object( stdClass $item ): RAPL_Object_Artist {
			$obj = new static();

			$obj->id = $item->channel_id ?? 0;
			$obj->name = $item->name ?? '';

			return $obj;
		}
	}
}
