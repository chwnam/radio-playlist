<?php
/**
 * RAPL: Cron schedule register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Cron_Schedule' ) ) {
	class RAPL_Register_Cron_Schedule extends RAPL_Register_Base_Cron_Schedule {
		public function get_items(): Generator {
			yield new RAPL_Reg_Cron_Schedule( 'every-3-min', 3 * MINUTE_IN_SECONDS, '매5분' );
		}
	}
}
