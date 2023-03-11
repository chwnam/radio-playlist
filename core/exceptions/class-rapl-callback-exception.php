<?php
/**
 * Naran Boilerplate Core
 *
 * exceptions/class-rapl-callback-exception.php
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Callback_Exception' ) ) {
	class RAPL_Callback_Exception extends Exception {
	}
}
