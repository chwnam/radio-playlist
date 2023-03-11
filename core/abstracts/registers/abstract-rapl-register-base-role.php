<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-role.php
 */
/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Role' ) ) {
	abstract class RAPL_Register_Base_Role implements RAPL_Register {
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Role ) {
					$item->register();
				}
			}
		}

		public function unregister(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Role ) {
					$item->unregister();
				}
			}
		}
	}
}
