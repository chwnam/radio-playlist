<?php
/**
 * RAPL: Widget register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Widget' ) ) {
	class RAPL_Register_Widget extends RAPL_Register_Base_Widget {
		public function get_items(): Generator {
			yield; // yield new RAPL_Reg_Widget();
		}
	}
}
