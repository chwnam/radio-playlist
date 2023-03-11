<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-deactivation.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Deactivation' ) ) {
	abstract class RAPL_Register_Base_Deactivation implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this->add_action( 'rapl_deactivation', 'register' );
		}

		/**
		 * Method name can mislead, but it does deactivation callback jobs.
		 *
		 * @return void
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Deactivation ) {
					$item->register();
				}
			}
		}
	}
}
