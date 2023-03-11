<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-wp-cli.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_WP_CLI' ) ) {
	class RAPL_Reg_WP_CLI implements RAPL_Reg {
		/**
		 * Constructor method
		 *
		 * @link https://make.wordpress.org/cli/handbook/references/internal-api/wp-cli-add-command/
		 */
		public function __construct(
			public string $name,
			public Closure|array|string $callback,
			public array $args = []
		) {
		}

		/**
		 * @param null $dispatch
		 *
		 * @return void
		 * @throws Exception Thrown from WP_CLI.
		 */
		public function register( $dispatch = null ): void {
			WP_CLI::add_command( $this->name, $this->callback, $this->args );
		}
	}
}
