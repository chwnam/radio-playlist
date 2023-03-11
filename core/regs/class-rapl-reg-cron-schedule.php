<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-cron-schedule.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Cron_Schedule' ) ) {
	class RAPL_Reg_Cron_Schedule implements RAPL_Reg {
		/**
		 * Constructor method
		 */
		public function __construct(
			public string $name,
			public int $interval,
			public string $display
		) {
		}

		public function register( $dispatch = null ): void {
			// Do nothing.
		}
	}
}
