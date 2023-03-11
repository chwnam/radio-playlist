<?php
/**
 * Naran Boilerplate Core
 *
 * etc/class-rapl-style-helper.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Style_Helper' ) ) {
	class RAPL_Style_Helper {
		/**
		 * Parent module object
		 *
		 * @var RAPL_Template_Impl|RAPL_Module
		 */
		private $parent;

		/**
		 * Script handle
		 *
		 * @var string
		 */
		private string $handle;

		/**
		 * Constructor method
		 *
		 * @param RAPL_Template_Impl|RAPL_Module $parent Parent module object.
		 * @param string                         $handle Script handle.
		 */
		public function __construct( $parent, string $handle ) {
			$this->parent = $parent;
			$this->handle = $handle;
		}

		/**
		 * Return another script helper.
		 *
		 * @param string $handle Handle string.
		 *
		 * @return RAPL_Script_Helper
		 */
		public function script( string $handle ): RAPL_Script_Helper {
			return new RAPL_Script_Helper( $this->parent, $handle );
		}

		/**
		 * Return another style helper.
		 *
		 * @param string $handle Handle string.
		 *
		 * @return RAPL_Style_Helper
		 */
		public function style( string $handle ): RAPL_Style_Helper {
			return new RAPL_Style_Helper( $this->parent, $handle );
		}

		/**
		 * Enqueue the style.
		 *
		 * @return self
		 */
		public function enqueue(): self {
			wp_enqueue_style( $this->handle );
			return $this;
		}

		/**
		 * Finish call chain
		 *
		 * @return RAPL_Module|RAPL_Template_Impl
		 */
		public function then() {
			return $this->parent;
		}
	}
}
