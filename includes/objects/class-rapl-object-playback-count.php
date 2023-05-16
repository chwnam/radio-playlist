<?php
/**
 * RAPL: Track count.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Playback_Count' ) ) {
	class RAPL_Object_Playback_Count implements RAPL_Object {
		use RAPL_YouTube_Prop_Trait;

		public int $track_id = 0; // Track ID.

		public string $title = '';

		public int $length = 0;

		public string $art_url = '';

		public int $playback_count = 0;

		public static function import( array|object $item ): static {
			return RAPL_Import_Helper::import( $item, static::class );
		}
	}
}
