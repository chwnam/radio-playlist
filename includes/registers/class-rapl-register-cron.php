<?php
/**
 * RAPL: Cron register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Cron' ) ) {
	class RAPL_Register_Cron extends RAPL_Register_Base_Cron {
		public function get_items(): Generator {
			yield new RAPL_Reg_Cron( time(), 'every-10-min', 'rapl_playlist' );
		}
	}
}
