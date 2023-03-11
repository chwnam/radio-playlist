<?php
/**
 * RAPL: Uninstall register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Uninstall' ) ) {
	class RAPL_Register_Uninstall extends RAPL_Register_Base_Uninstall {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Uninstall();
		}
	}
}
