<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-wp-cli.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_WP_CLI' ) ) {
	abstract class RAPL_Register_Base_WP_CLI implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				$this->add_action( 'plugins_loaded', 'register' );
			}
		}

		/**
		 * @return void
		 *
		 * @throws Exception Thrown from WP_CLI.
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_WP_CLI ) {
					$item->register();
				}
			}
		}
	}
}
