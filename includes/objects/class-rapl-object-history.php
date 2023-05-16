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
		use RAPL_YouTube_Prop_Trait;

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

		public static function import( array|object $item ): static {
			$instance = RAPL_Import_Helper::import( $item, static::class );

			return $instance->set_youtube( $instance, $instance->track_id, $instance->artist_name, $instance->title );
		}

		public function __construct() {
			static::init_youtube( $this );
		}
	}
}
