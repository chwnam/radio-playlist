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
			rapl()->collectors->rock_radio->collect();
		}
	}
}
