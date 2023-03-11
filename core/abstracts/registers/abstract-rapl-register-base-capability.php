<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-capability.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Capability' ) ) {
	abstract class RAPL_Register_Base_Capability implements RAPL_Register {
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Capability ) {
					$item->register();
				}
			}
		}

		public function unregister(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Capability ) {
					$item->unregister();
				}
			}
		}
	}
}
