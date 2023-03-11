<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-uninstall.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Uninstall' ) ) {
	abstract class RAPL_Register_Base_Uninstall implements RAPL_Register {
		/**
		 * Method name can mislead, but it does uninstall callback jobs.
		 *
		 * @return void
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Uninstall ) {
					$item->register();
				}
			}
		}
	}
}
