<?php
/**
 * RAPL: Menu register.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Menu' ) ) {
	class RAPL_Register_Menu extends RAPL_Register_Base_Menu {
		public function get_items(): Generator {
			/**
			 * @see RAPL_Admin_Playlist::output_menu_page()
			 */
			yield new RAPL_Reg_Menu(
				'플레이리스트',
				'플레이리스트',
				'administrator',
				'rapl-playlist',
				'admin.playlist@output_menu_page',
			);
		}
	}
}
