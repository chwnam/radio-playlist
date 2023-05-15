<?php
/**
 * RAPL: Store interface
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RAPL_Store' ) ) {
	interface RAPL_Store {
		public function get( int $id, array|string $args = [] );

		public function insert( array $data );
	}
}
