<?php
/**
 * Naran Boilerplate Core
 *
 * interfaces/interface-rapl-reg.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Reg' ) ) {
	interface RAPL_Reg {
		public function register( $dispatch = null );
	}
}
