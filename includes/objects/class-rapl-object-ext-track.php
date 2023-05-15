<?php
/**
 * RAPL: Raw playlist
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Ext_Track' ) ) {
	/**
	 * More accurate track info to extract an artist info.
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
	 *   "votes": { ... },
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
	class RAPL_Object_Ext_Track implements RAPL_Object {
		public int $artist_id = 0; // Artist ID. This is the key data.

		public static function import( array|object $item ): static {
			/** @var array<array|stdClass> $artists */
			$artist = RAPL_Import_Helper::from_item( $item, 'artist', [] );

			$obj            = new static();
			$obj->artist_id = RAPL_Import_Helper::from_item( $artist, 'id', 0 );

			return $obj;
		}
	}
}
