<?php
/**
 * RAPL: Menu register.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Menu' ) ) {
	class RAPL_Register_Menu extends RAPL_Register_Base_Menu {
		public function get_items(): Generator {
			yield;
			// yield new RAPL_Reg_Menu();
			// yield new RAPL_Reg_Submenu();
		}
	}
}
