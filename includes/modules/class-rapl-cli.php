<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_CLI' ) ) {
	class RAPL_CLI implements RAPL_Module {
		/**
		 * Clear all RAPL tables.
		 *
		 * @return void
		 */
		public function clear(): void {
			global $wpdb;

			WP_CLI::confirm( WP_CLI::colorize( '%RThis is dangerous! Are you sure?%N' ) );

			$wpdb->query( "TRUNCATE {$wpdb->prefix}rapl_history" );
			$wpdb->query( "TRUNCATE {$wpdb->prefix}rapl_tracks" );
			$wpdb->query( "TRUNCATE {$wpdb->prefix}rapl_artists" );

			WP_CLI::success( 'All RAPL tables truncated!' );
		}

		/**
		 * Run now.
		 *
		 * @return void
		 */
		public function run(): void {
			WP_CLI::line( WP_CLI::colorize( "%GRunning now...%N" ) );

			rapl()->runner->rapl_playlist();

			WP_CLI::success( "Done!" );
		}

		/**
		 * Update count.
		 *
		 * @return void
		 */
		public function update_count(): void {
			rapl_update_count();
			WP_CLI::success( "Count updated." );
		}
	}
}
