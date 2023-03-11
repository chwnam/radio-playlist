<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-capability.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Capability' ) ) {
	class RAPL_Reg_Capability implements RAPL_Reg {
		/**
		 * Constructor method
		 *
		 * @param string $role
		 * @param array  $capabilities
		 */
		public function __construct(
			public string $role,
			public array $capabilities
		) {
		}

		public function register( $dispatch = null ): void {
			$role = get_role( $this->role );

			if ( $role ) {
				foreach ( $this->capabilities as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

		public function unregister(): void {
			$role = get_role( $this->role );

			if ( $role ) {
				foreach ( $this->capabilities as $capability ) {
					$role->remove_cap( $capability );
				}
			}
		}
	}
}
