<?php
/**
 * RAPL: Activation register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Activation' ) ) {
	class RAPL_Register_Activation extends RAPL_Register_Base_Activation {
		public function get_items(): Generator {
			yield; // new RAPL_Reg_Activation( 'registers.role@register' );
		}
	}
}
