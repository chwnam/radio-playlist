<?php
/**
 * RAPL: Role register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Role' ) ) {
	class RAPL_Register_Role extends RAPL_Register_Base_Role {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Role();
		}
	}
}
