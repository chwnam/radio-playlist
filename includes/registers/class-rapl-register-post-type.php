<?php
/**
 * RAPL: Custom post type register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Post_Type' ) ) {
	class RAPL_Register_Post_Type extends RAPL_Register_Base_Post_Type {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Post_Type();
		}
	}
}
