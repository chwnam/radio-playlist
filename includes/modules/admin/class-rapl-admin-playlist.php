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
			$module  = rapl()->playlist;
			$up      = wp_get_upload_dir();
			$basedir = untrailingslashit( $up['basedir'] );

			$start = microtime( true );

			echo '<p>Started!</p>';

			foreach ( glob( "$basedir/rapl-*.json" ) as $file ) {
				if ( file_exists( $file ) && is_readable( $file ) ) {
					$object = json_decode( file_get_contents( $file ) );
					$module->collect( $object );
				}
			}

			$finish = microtime( true );

			echo '<p>Finished! ' . ( $finish - $start ) . '</p>';
		}
	}
}
