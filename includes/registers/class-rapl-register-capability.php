<?php
/**
 * RAPL: Role register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class_alias( RAPL_Reg_Capability::class, 'RAPL_Reg_Cap' );

if ( ! class_exists( 'RAPL_Register_Capability' ) ) {
	class RAPL_Register_Capability extends RAPL_Register_Base_Capability {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Cap();
		}
	}
}
