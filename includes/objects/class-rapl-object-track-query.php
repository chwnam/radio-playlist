<?php
/**
 * RAPL: Track object query
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Track_Query' ) ) {
	class RAPL_Object_Track_Query {
		/** @var RAPL_Object_Track_History[] $items */
		public array $items = [];

		public int $total = 0;

		public int $total_pages = 0;

		public int $per_page = 0;

		public int $page = 0;

		public float $time_spent = 0.0;
	}
}
