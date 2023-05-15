<?php
/**
 * RAPL: Playlist admin module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Admin_Playlist' ) ) {
	class RAPL_Admin_Playlist implements RAPL_Admin_Module {
		/**
		 * @return void
		 *
		 * @see RAPL_Register_Menu::get_items()
		 */
		public function output_menu_page(): void {
			do_action( 'rapl_playlist' );
		}
	}
}
