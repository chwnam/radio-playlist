<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-block.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Block' ) ) {
	abstract class RAPL_Register_Base_Block implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this->add_action( 'init', 'register' );
		}

		/**
		 * Register reg items.
		 *
		 * @return void
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Block ) {
					$item->register();
				}
			}
		}
	}
}
