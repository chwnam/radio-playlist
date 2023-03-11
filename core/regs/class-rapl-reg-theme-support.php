<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-theme-support.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Theme_Support' ) ) {
	class RAPL_Reg_Theme_Support implements RAPL_Reg {
		private string $feature;

		private array $args;

		public function __construct( string $feature, ...$args ) {
			$this->feature = $feature;
			$this->args    = $args;
		}

		public function register( $dispatch = null ) {
			add_theme_support( $this->feature, ...$this->args );
		}
	}
}
