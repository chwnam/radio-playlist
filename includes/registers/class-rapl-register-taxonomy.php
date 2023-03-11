<?php
/**
 * RAPL: Custom taxonomy register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Taxonomy' ) ) {
	class RAPL_Register_Taxonomy extends RAPL_Register_Base_Taxonomy {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Taxonomy();
		}
	}
}
