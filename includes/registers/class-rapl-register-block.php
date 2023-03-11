<?php
/**
 * RAPL: Block register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Block' ) ) {
	class RAPL_Register_Block extends RAPL_Register_Base_Block {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Block();
		}
	}
}
