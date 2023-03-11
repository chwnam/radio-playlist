<?php
/**
 * RAPL: Admin page module.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Admin_Page' ) ) {
	class RAPL_Admin_Page implements RAPL_Admin_Module {
		use RAPL_Hook_Impl;
		use RAPL_Template_Impl;

		public function __construct() {
		}
	}
}
