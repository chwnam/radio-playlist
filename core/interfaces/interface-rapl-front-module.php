<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-front-module.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Front_Module' ) ) {
	interface RAPL_Front_Module extends RAPL_Module {
		public function display(): void;
	}
}
