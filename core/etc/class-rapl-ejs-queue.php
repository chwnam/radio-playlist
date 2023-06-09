<?php
/**
 * Naran Boilerplate Core
 *
 * etc/class-rapl-ejs-queue.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_EJS_Queue' ) ) {
	class RAPL_EJS_Queue implements RAPL_Module {
		use RAPL_Template_Impl;

		/**
		 * EJS data store.
		 *
		 * - Key: relative template path.
		 * - Value: array. 'context', and 'variant' keys are stored.
		 *
		 * @var array{string: array{string: mixed}}
		 */
		private array $queue = [];

		/**
		 * Constructor method.
		 */
		public function __construct() {
			if ( is_admin() ) {
				if ( ! has_action( 'admin_print_footer_scripts', [ $this, 'do_template' ] ) ) {
					add_action( 'admin_print_footer_scripts', [ $this, 'do_template' ], rapl_priority() );
				}
			} elseif ( ! has_action( 'wp_print_footer_scripts', [ $this, 'do_template' ] ) ) {
				add_action( 'wp_print_footer_scripts', [ $this, 'do_template' ], rapl_priority() );
			}
		}

		/**
		 * Enqueue EJS template.
		 *
		 * @param string $relpath Relative path to EJS template.
		 * @param array  $data    Context and variant info.
		 *
		 * @return void
		 * @see    RAPL_Template_Impl::enqueue_ejs()
		 */
		public function enqueue( string $relpath, array $data = [] ): void {
			$this->queue[ $relpath ] = $data;
		}

		/**
		 * Create EJS script tags.
		 *
		 * @return void
		 */
		public function do_template(): void {
			// Output a EJS template. Escaping is too much here.
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

			foreach ( $this->queue as $relpath => $data ) {
				$tmpl_id = 'tmpl-' . pathinfo( wp_basename( $relpath ), PATHINFO_FILENAME );
				$content = $this->render_file(
					$this->locate_file( 'ejs', $relpath, $data['variant'] ),
					$data['context'],
					false
				);
				$content = preg_replace( '/\s+/', ' ', $content );
				$content = trim( str_replace( '> <', '><', $content ) );

				if ( ! empty( $content ) ) {
					echo wp_kses(
						"\n<script id='" . esc_attr( $tmpl_id ) . "' type='text/template'>\n" .
						$content .
						"\n</script>\n",
						[
							'script' => [
								'id'   => true,
								'type' => true,
							],
						]
					);
				}
			}

			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
