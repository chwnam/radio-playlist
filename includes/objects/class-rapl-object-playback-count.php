<?php
/**
 * RAPL: Track count.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Playback_Count' ) ) {
	class RAPL_Object_Playback_Count {
		public int $track_id = 0; // Track ID.

		public string $title = '';

		public int $length = 0;

		public string $art_url = '';

		public int $playback_count = 0;

		public static function from_object( stdClass $item ): RAPL_Object_Playback_Count {
			$obj = new static();

			$obj->track_id       = $item->track_id ?? 0;
			$obj->title          = $item->title ?? '';
			$obj->length         = $item->length ?? '';
			$obj->art_url        = $item->art_url ?? '';
			$obj->playback_count = $item->playback_count ?? 0;

			return $obj;
		}
	}
}
