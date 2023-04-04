<?php
/**
 * RAPL: Track object
 *
 * NOTE: Only for fetch from the remote server.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Track' ) ) {
	class RAPL_Object_Track {
		public int $network_id = 0;

		public int $channel_id = 0;

		public string $type = '';

		public int $track_id = 0;

		public string $artist = '';

		public string $title = '';

		public int $length = 0;

		public int $started = 0;

		public string $art_url = '';

		public static function from_object( stdClass $item ): RAPL_Object_Track {
			$obj = new static();

			$obj->network_id = $item->network_id ?? 0;
			$obj->channel_id = $item->channel_id ?? 0;
			$obj->track_id   = $item->track_id ?? 0;
			$obj->artist     = $item->artist ?? '';
			$obj->title      = $item->title ?? '';
			$obj->length     = $item->length ?? 0;
			$obj->started    = $item->started ?? 0;
			$obj->art_url    = $item->art_url ?? '';
			$obj->type       = $item->type ?? '';

			return $obj;
		}
	}
}
