<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-register.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Register' ) ) {
	interface RAPL_Register {
		/**
		 * Get list of regs.
		 */
		public function get_items(): Generator;

		/**
		 * Register all regs.
		 */
		public function register();
	}
}
