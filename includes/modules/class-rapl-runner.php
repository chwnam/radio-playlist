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
			$this->add_action( 'rapl_playlist' );
		}

		/**
		 * 라디오 플레이리스트 수집 스케쥴을 시작한다.
		 *
		 * @return void
		 */
		public function rapl_playlist(): void {
			$logger = rapl_get_logger();
			$logger->info( 'Starting \'rapl_playlist\' schedule.' );

			$module = rapl()->playlist;

			$items = $module->fetch();

			if ( $this->is_debug() ) {
				$module->dump( $items );
			}

			$module->collect( $items );
		}

		/**
		 * wp-config.php WP_ENVIRONMENT_TYPE 상수를 'local', 'development' 값으로 잡으면 디버깅을 위한 객체 덤프.
		 *
		 * @return bool
		 */
		protected function is_debug(): bool {
			return in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
		}
	}
}
