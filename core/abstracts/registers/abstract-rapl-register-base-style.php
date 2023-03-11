<?php
/**
 * Naran Boilerplate Core
 *
 * abstracts/registers/abstract-rapl-register-base-style.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Base_Style' ) ) {
	abstract class RAPL_Register_Base_Style implements RAPL_Register {
		use RAPL_Hook_Impl;

		/**
		 * Constructor method.
		 */
		public function __construct() {
			$this->add_action( 'init', 'register' );
		}

		/**
		 * @callback
		 * @action       init
		 *
		 * @return void
		 */
		public function register(): void {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof RAPL_Reg_Style ) {
					$item->register();
				}
			}
		}

		/**
		 * 'src' location helper.
		 *
		 * @param string $rel_path
		 * @param bool   $replace_min
		 *
		 * @return string
		 */
		protected function src_helper( string $rel_path, bool $replace_min = true ): string {
			$rel_path = trim( $rel_path, '\\/' );

			if ( $replace_min && rapl_script_debug() && str_ends_with( $rel_path, '.min.css' ) ) {
				$rel_path = substr( $rel_path, 0, - 8 ) . '.css';
			}

			if ( rapl_is_theme() ) {
				return get_stylesheet_directory_uri() . "/assets/css/$rel_path";
			} else {
				return plugin_dir_url( rapl_main_file() ) . 'assets/css/' . $rel_path;
			}
		}
	}
}
