<?php
/**
 * RAPL: Script register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Script' ) ) {
	class RAPL_Register_Script extends RAPL_Register_Base_Script {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Script();
		}
	}
}
