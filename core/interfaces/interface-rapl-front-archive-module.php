<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-front-archive-module.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Front_Archive_Module' ) ) {
	interface RAPL_Front_Archive_Module extends RAPL_Front_Module {
		public function pre_get_posts( WP_Query $query );

		public function get_posts_per_page(): int;
	}
}
