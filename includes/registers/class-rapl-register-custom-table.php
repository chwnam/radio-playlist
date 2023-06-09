<?php
/**
 * RAPL: Custom table register
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_Custom_Table' ) ) {
	class RAPL_Register_Custom_Table extends RAPL_Register_Base_Custom_Table {
		use RAPL_Hook_Impl;

		const DB_VERSION = '20230519v1'; // Set DB version here.

		/**
		 * Constructor
		 *
		 * You may need to use activation callback to create table and insert initial data.
		 * You may need to use 'plugins_loaded' callback to check db version and to update table.
		 */
		public function __construct() {
			$this
				->add_action( 'rapl_activation', 'initial_setup' )
				->add_action( 'plugins_loaded', 'update_table' )
			;
		}

		/**
		 * Return custom table items
		 *
		 * @return Generator
		 * @see    RAPL_Reg_Custom_Table::create_table()
		 */
		public function get_items(): Generator {
			global $wpdb;

			yield new RAPL_Reg_Custom_Table(
				"{$wpdb->prefix}rapl_artists",
				[
					"id bigint(20) unsigned NOT NULL AUTO_INCREMENT",
					"name varchar(100) NOT NULL",
					"count bigint(20) unsigned NOT NULL",
				],
				[
					"PRIMARY KEY  (id)",
					"UNIQUE KEY unique_name (name)",
					"FULLTEXT INDEX idx_name (name)",
					"KEY idx_count (count)",
				]
			);

			yield new RAPL_Reg_Custom_Table(
				"{$wpdb->prefix}rapl_tracks",
				[
					"id bigint(20) unsigned NOT NULL AUTO_INCREMENT",
					"artist_id bigint(20) unsigned NOT NULL",
					"title varchar(255) NOT NULL",
					"length int unsigned NOT NULL",
					"art_url varchar(255) NOT NULL",
					"count bigint(20) unsigned NOT NULL",
				],
				[
					"PRIMARY KEY  (id)",
					"KEY idx_artist_id (artist_id)",
					"FULLTEXT INDEX idx_title (title)",
					"KEY idx_count (count)",
				]
			);

			yield new RAPL_Reg_Custom_Table(
				"{$wpdb->prefix}rapl_history",
				[
					"id bigint(20) unsigned NOT NULL AUTO_INCREMENT",
					"network_id bigint(20) unsigned NOT NULL",
					"channel_id bigint(20) unsigned NOT NULL",
					"track_id bigint(20) unsigned NOT NULL",
					"started bigint(20) unsigned NOT NULL DEFAULT 0",
				],
				[
					"PRIMARY KEY  (id)",
					"KEY idx_network_id (network_id)",
					"KEY idx_channel_id (channel_id)",
					"KEY idx_track_id (track_id)",
					"UNIQUE KEY unique_history (track_id, started)",
				]
			);
		}

		/**
		 * Return initial table data.
		 *
		 * @return array Key: table name
		 *               Val: Array of key-value pair.
		 */
		public function get_initial_data(): array {
			global $wpdb;

			return [
//				"{$wpdb->prefix}my_table" => [
//					[
//						'title' => 'My Blog',
//						'url'   => 'https://my.blog.io/',
//					],
//					[
//						...
//					]
//				],
			];
		}

		/**
		 * Update count fields.
		 */
		public function update_counts(): void {
			global $wpdb;

			$query_for_artists =
				"UPDATE {$wpdb->prefix}rapl_artists AS a, (" .
				"SELECT a.id, COUNT(a.id) AS playback_count FROM {$wpdb->prefix}rapl_artists AS a" .
				" INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id=a.id" .
				" INNER JOIN {$wpdb->prefix}rapl_history h on h.track_id = t.id" .
				" GROUP BY a.id) AS c" .
				" SET a.count=c.playback_count WHERE a.id=c.id";

			$query_for_tracks =
				"UPDATE {$wpdb->prefix}rapl_tracks AS t, (" .
				"SELECT t.id, COUNT(t.id) AS playback_count FROM {$wpdb->prefix}rapl_tracks AS t" .
				" INNER JOIN {$wpdb->prefix}rapl_history h on h.track_id = t.id" .
				" GROUP BY t.id) AS c" .
				" SET t.count=c.playback_count WHERE t.id=c.id";

			$wpdb->query( $query_for_artists );
			$wpdb->query( $query_for_tracks );
		}
	}
}
