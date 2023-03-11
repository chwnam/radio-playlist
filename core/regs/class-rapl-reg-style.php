<?php
/**
 * Naran Boilerplate Core
 *
 * regs/class-rapl-reg-style.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Reg_Style' ) ) {
	class RAPL_Reg_Style implements RAPL_Reg {
		public string $handle;

		public string $src;

		public array $deps;

		/** @var string|bool */
		public string|bool $ver;

		public string $media;

		/**
		 * Constructor method
		 */
		public function __construct(
			string $handle,
			string $src,
			array $deps = [],
			string|bool|null $ver = null,
			string $media = 'all'
		) {
			$this->handle = $handle;
			$this->src    = $src;
			$this->deps   = $deps;
			$this->ver    = is_null( $ver ) ? rapl_version() : $ver;
			$this->media  = $media;
		}

		public function register( $dispatch = null ): void {
			if ( $this->handle && $this->src && ! wp_style_is( $this->handle, 'registered' ) ) {
				wp_register_style(
					$this->handle,
					$this->src,
					$this->deps,
					// Three cases.
					// 1. string:     As-is.
					// 2. true:       Use WordPress version string.
					// 3. null/false: Converted to null. An empty version string.
					$this->ver ?: null,
					$this->media
				);
			}
		}
	}
}
