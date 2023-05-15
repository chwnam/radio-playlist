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
	class RAPL_Object_Track implements RAPL_Object{
		public int $track_id = 0;

		public int $artist_id = 0;

		public string $artist_name = '';

		public string $title = '';

		public int $length = 0;

		public string $art_url = '';

		public static function import( array|object $item ): static {
			return RAPL_Import_Helper::import( $item, static::class );
		}
	}
}
