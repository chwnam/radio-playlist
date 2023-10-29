<?php
/**
 * RAPL: Stores
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Stores' ) ) {
	/**
	 * @property-read RAPL_Store_Artist  $artist
	 * @property-read RAPL_Store_History $history
	 * @property-read RAPL_Store_Ranking $ranking
	 * @property-read RAPL_Store_Track   $track
	 */
	class RAPL_Stores implements RAPL_Module {
		use RAPL_Submodule_Impl;

		public function __construct() {
			$this->assign_modules(
				[
					'artist'  => fn() => $this->new_instance( RAPL_Store_Artist::class ),
					'history' => fn() => $this->new_instance( RAPL_Store_History::class ),
					'ranking' => fn() => $this->new_instance( RAPL_Store_Ranking::class ),
					'track'   => fn() => $this->new_instance( RAPL_Store_Track::class ),
				]
			);
		}
	}
}
