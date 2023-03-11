<?php
/**
 * RAPL: rewrite rule register
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Rewrite_Rule' ) ) {
	class RAPL_Register_Rewrite_Rule extends RAPL_Register_Base_Rewrite_Rule {
		/**
		 * Get rewrite rule regs.
		 *
		 * @return Generator
		 */
		public function get_items(): Generator {
			yield; // yield RAPL_Reg_Rewrite_Rule();
		}
	}
}
