<?php
/**
 * RAPL: Sidebar register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Sidebar' ) ) {
	class RAPL_Register_Sidebar extends RAPL_Register_Base_Sidebar {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Sidebar();
		}
	}
}
