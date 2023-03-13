<?php
/**
 * RAPL: WP-CLI register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_WP_CLI' ) ) {
	class RAPL_Register_WP_CLI extends RAPL_Register_Base_WP_CLI {
		public function get_items(): Generator {
			yield new RAPL_Reg_WP_CLI( 'rapl', RAPL_CLI::class );
		}
	}
}
