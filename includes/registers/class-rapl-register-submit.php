<?php
/**
 * RAPL: Submit (admin-post.php) register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Submit' ) ) {
	class RAPL_Register_Submit extends RAPL_Register_Base_Submit {
		// Disable 'admin-post.php' autobind.
		// protected bool $autobind = false;

		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Submit();
		}
	}
}
