<?php
/**
 * RAPL: Fetchers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Fetchers' ) ) {
	/**
	 * @property-read RAPL_Fetcher_Rock_Radio $rock_radio
	 */
	class RAPL_Fetchers implements RAPL_Module {
		use RAPL_Submodule_Impl;

		public function __construct() {
			$this->assign_modules(
				[
					'rock_radio' => fn() => $this->new_instance( RAPL_Fetcher_Rock_Radio::class ),
				]
			);
		}
	}
}
