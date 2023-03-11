<?php
/**
 * RAPL: Admin post module.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Admin_Post' ) ) {
	class RAPL_Admin_Post implements RAPL_Admin_Module {
		use RAPL_Hook_Impl;
		use RAPL_Template_Impl;

		public function __construct() {
		}
	}
}
