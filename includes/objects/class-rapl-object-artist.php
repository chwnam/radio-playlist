<?php
/**
 * RAPL: Track detailed object
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Artist' ) ) {
	class RAPL_Object_Artist implements RAPL_Object {
		public int $artist_id = 0; // Artist ID.

		public string $artist_name = '';

		public static function import( array|object $item ): static {
			return RAPL_Import_Helper::import( $item, static::class );
		}
	}
}
