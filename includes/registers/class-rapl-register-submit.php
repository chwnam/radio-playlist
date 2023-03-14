<?php
/**
 * RAPL: Submit (admin-post.php) register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Submit' ) ) {
	class RAPL_Register_Submit extends RAPL_Register_Base_Submit {
		// Disable 'admin-post.php' autobind.
		// protected bool $autobind = false;

		public function get_items(): Generator {
			/** @uses RAPL_YouTube_Video::submit_get_video() */
			yield new RAPL_Reg_Submit( 'rapl_get_video', 'youtube_video@submit_get_video', true );
		}
	}
}
