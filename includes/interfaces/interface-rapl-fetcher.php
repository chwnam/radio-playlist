<?php
/**
 * RAPL: Fetcher interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Fetcher' ) ) {
	interface RAPL_Fetcher extends RAPL_Module {
		public function get_track_history( int $channel );

		public function get_track_info( int $track_id );
	}
}
