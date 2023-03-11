<?php
/**
 * RAPL: Style register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Style' ) ) {
	class RAPL_Register_Style extends RAPL_Register_Base_Style {
		/**
		 * Return Style regs.
		 *
		 * @return Generator
		 */
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Style();
		}
	}
}
