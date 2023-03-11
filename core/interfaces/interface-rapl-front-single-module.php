<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-front-single-module.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Front_Single_Module' ) ) {
	interface RAPL_Front_Single_Module extends RAPL_Front_Module {
		public function pre_get_posts( WP_Query $query );
	}
}
