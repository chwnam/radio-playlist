<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-admin-module.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Admin_Module' ) ) {
	interface RAPL_Admin_Module extends RAPL_Module {
	}
}
