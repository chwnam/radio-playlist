<?php
/**
 * RAPL: Shortcode register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Shortcode' ) ) {
	class RAPL_Register_Shortcode extends RAPL_Register_Base_Shortcode {
		public function get_items(): Generator {
			/**
			 * @uses RAPL_Shortcode_Handlers::handlde_playlist()
			 */
			yield new RAPL_Reg_Shortcode( 'rapl_playlist', 'shortcode_handlers@handlde_playlist' );
		}
	}
}
