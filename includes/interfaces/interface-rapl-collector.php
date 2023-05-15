<?php
/**
 * RAPL: Collector interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Collector' ) ) {
	interface RAPL_Collector {
		public function collect(): void;
	}
}
