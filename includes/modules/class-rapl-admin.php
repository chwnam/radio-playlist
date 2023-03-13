<?php
/**
 * RAPL: Admin modules group
 *
 * Manage all admin modules
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Admin' ) ) {
	/**
	 * @property-read RAPL_Admin_Playlist $playlist
	 */
	class RAPL_Admin implements RAPL_Module {
		use RAPL_Hook_Impl;
		use RAPL_Submodule_Impl;

		/**
		 * Constructor method
		 *
		 * @uses instantiate_admin_module()
		 */
		public function __construct() {
			$this
				->add_action( 'current_screen', 'instantiate_admin_module' )
				->assign_modules(
					[
						'playlist' => RAPL_Admin_Playlist::class,
					],
					true // Automatic wrapping. Do not instantiate modules unless they are explicitly invoked.
				)
			;
		}

		/**
		 * Instantiate admin module by screen condition.
		 *
		 * @callback
		 * @action    current_screen
		 *
		 * @param WP_Screen $screen
		 *
		 * @return void
		 */
		public function instantiate_admin_module( WP_Screen $screen ): void {
			if ( 'toplevel_page_rapl-playlist' === $screen->id ) {
				$this->touch( 'playlist' );
			}
		}
	}
}
