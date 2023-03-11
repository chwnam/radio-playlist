<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-cron-schedule.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Cron_Schedule' ) ) {
	abstract class RAPL_Register_Base_Cron_Schedule implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this->add_filter( 'cron_schedules', 'register' );
		}

		/**
		 * Register cron schedule regs.
		 *
		 * @callback
		 * @filter   cron_schedules
		 *
		 * @return  array
		 * @see      wp_get_schedules()
		 */
		public function register(): array {
			$schedules = func_get_arg( 0 );

			foreach ( $this->get_items() as $item ) {
				if (
					$item instanceof RAPL_Reg_Cron_Schedule &&
					$item->interval > 0 &&
					! isset( $schedules[ $item->name ] )
				) {
					$schedules[ $item->name ] = [
						'interval' => $item->interval,
						'display'  => $item->display,
					];
				}
			}

			return $schedules;
		}
	}
}
