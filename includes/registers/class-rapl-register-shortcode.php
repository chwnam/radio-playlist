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
			yield; // yield new RAPL_Reg_Shortcode();
		}
	}
}
