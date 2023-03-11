<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-shortcode.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Shortcode' ) ) {
	class RAPL_Reg_Shortcode implements RAPL_Reg {
		/**
		 * Constructor method
		 */
		public function __construct(
			public string $tag,
			public Closure|array|string $callback,
			public Closure|array|string|null $heading_action = null
		) {
		}

		public function register( $dispatch = null ): void {
			add_shortcode( $this->tag, $dispatch );
		}
	}
}
