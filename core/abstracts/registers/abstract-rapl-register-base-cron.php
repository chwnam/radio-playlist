<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-cron.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Cron' ) ) {
	abstract class RAPL_Register_Base_Cron implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this
				->add_action( 'rapl_activation', 'register' )
				->add_action( 'rapl_deactivation', 'unregister' )
			;
		}

		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Cron ) {
					$item->register();
				}
			}
		}

		public function unregister(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Cron ) {
					$item->unregister();
				}
			}
		}
	}
}
