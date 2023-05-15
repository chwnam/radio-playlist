<?php
/**
 * RAPL: Object interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Object' ) ) {
	interface RAPL_Object {
		public static function import( array|object $item ): static;
	}
}
