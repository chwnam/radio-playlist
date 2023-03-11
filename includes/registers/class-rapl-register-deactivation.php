<?php
/**
 * RAPL: Deactivation register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Deactivation' ) ) {
	class RAPL_Register_Deactivation extends RAPL_Register_Base_Deactivation {
		public function get_items(): Generator {
			// Remove defined roles
			yield new RAPL_Reg_Activation( 'registers.role@unregister' );

			// Remove defined caps
			yield new RAPL_Reg_Activation( 'registers.cap@unregister' );
		}
	}
}
