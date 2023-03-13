<?php
/**
 * RAPL: Runnner module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Runner' ) ) {
	class RAPL_Runner implements RAPL_Module {
		use RAPL_Hook_Impl;

		public function __construct() {
			/** @uses rapl_playlist() */
//			$this->add_action( 'rapl_playlist' );
		}

		public function rapl_playlist() {
			$module = rapl()->playlist;

			$items = $module->fetch();

			if ( $this->is_debug() ) {
				$module->dump( $items );
			}

			$module->collect( $items );
		}

		protected function is_debug(): bool {
			return in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
		}
	}
}
