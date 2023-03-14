<?php
/**
 * RAPL: Shortcode handler module
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Shortcode_Handlers' ) ) {
	class RAPL_Shortcode_Handlers implements RAPL_Module {
		use RAPL_Template_Impl;

		public function handlde_playlist(): string {
			$module = rapl()->playlist;

			$srch   = wp_unslash( $_GET['srch'] ?? '' );
			$page   = (int) ( $_GET['pg'] ?? '1' );
			$result = $module->query(
				[
					'page'     => $page,
					'per_page' => 20,
					'search'   => $srch,
				]
			);

			return $this->render(
				'radio-playlist',
				[
					'page'   => $page,
					'result' => &$result,
					'search' => $srch,
				],
				'',
				false
			);
		}
	}
}
