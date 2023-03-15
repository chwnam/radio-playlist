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
			/** @uses RAPL_YouTube::submit_get_youtube_video() */
			yield new RAPL_Reg_Submit( 'rapl_get_youtube_video', 'youtube@submit_get_youtube_video', true );

			/** @uses RAPL_YouTube::submit_get_youtube_music() */
			yield new RAPL_Reg_Submit( 'rapl_get_youtube_music', 'youtube@submit_get_youtube_music', true );
		}
	}
}
