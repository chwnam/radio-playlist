<?php
/**
 * RAPL: AJAX (admin-ajax.php, or wc-ajax) register.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_AJAX' ) ) {
	class RAPL_Register_AJAX extends RAPL_Register_Base_AJAX {
		// Disable AJAX autobind.
		// protected bool $autobind = false;

		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_AJAX();
		}
	}
}
