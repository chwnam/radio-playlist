<?php
/**
 * RAPL: Collectors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Collectors' ) ) {
	/**
	 * @property-read RAPL_Collector_Rock_Radio $rock_radio
	 */
	class RAPL_Collectors implements RAPL_Module {
		use RAPL_Submodule_Impl;

		public function __construct() {
			$this->assign_modules(
				[
					'rock_radio' => fn() => $this->new_instance( RAPL_Collector_Rock_Radio::class ),
				]
			);
		}
	}
}
