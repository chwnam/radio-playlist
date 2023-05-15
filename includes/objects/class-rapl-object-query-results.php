<?php
/**
 * RAPL: Playlist object query
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Object_Query_Results' ) ) {
	class RAPL_Object_Query_Results {
		/** @var RAPL_Object_History[]|RAPL_Object_Playback_Count[] $items */
		public array $items = [];

		public int $total = 0;

		public int $total_pages = 0;

		public int $per_page = 0;

		public int $page = 0;

		public float $time_spent = 0.0;

		public static function create(
			array $items,
			int $per_page,
			int $page,
			int $total,
			float $time_spent
		): static {
			$instnce = new static();

			$instnce->items    = $items;
			$instnce->per_page = $per_page;
			$instnce->page     = $page;
			$instnce->total    = $total;
			$instnce->total_pages = (int) ceil( (float) $total / (float) $per_page );
			$instnce->time_spent  = $time_spent;

			return $instnce;
		}
	}
}
