<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-theme-support.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Theme_Support' ) ) {
	abstract class RAPL_Register_Base_Theme_Support implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this->add_action( 'after_setup_theme', 'register' );
		}

		/**
		 * @callback
		 * @actin       init
		 *
		 * @return void
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Theme_Support ) {
					$item->register();
				}
			}
			$this->extra_register();
		}
	}
}
