<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-cron.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Cron' ) ) {
	class RAPL_Reg_Cron implements RAPL_Reg {
		/**
		 * Constructor method
		 */
		public function __construct(
			public int $timestamp,
			public string $schedule,
			public string $hook,
			public array $args = [],
			public bool $wp_error = false,
			public bool $is_single_event = false
		) {
		}

		public function register( $dispatch = null ): void {
			if ( $this->is_single_event ) {
				wp_schedule_single_event( $this->timestamp, $this->hook, $this->args, $this->wp_error );
			} else {
				wp_schedule_event( $this->timestamp, $this->schedule, $this->hook, $this->args, $this->wp_error );
			}
		}

		public function unregister(): void {
			if ( wp_next_scheduled( $this->hook, $this->args ) ) {
				wp_clear_scheduled_hook( $this->hook, $this->args );
			}
		}
	}
}
