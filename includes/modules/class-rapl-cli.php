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
		 * Restore from dump files.
		 *
		 * ## OPTIONS
		 *
		 * <file_name>
		 * : .json file to load.
		 *
		 * @param array $args
		 *
		 * @return void
		 * @throws \WP_CLI\ExitException
		 */
		public function import( array $args ): void {
			$path = realpath( $args[0] );

			if ( ! file_exists( $path ) ) {
				WP_CLI::error( 'File not found.' );
			}

			if ( ! is_file( $path ) || ! is_readable( $path ) ) {
				WP_CLI::error( 'File cannot be read.' );
			}

			$items = json_decode( file_get_contents( $path ) );

			if ( ! $items ) {
				WP_CLI::error( 'Invalid JSON file.' );
			}

			$start = microtime( true );
			$module = rapl()->playlist;
			$module->collect( $items );
			$finish = microtime( true );

			WP_CLI::success( sprintf( "'%s' successfully imported in %.2fs.", $path, ( $finish - $start ) ) );
		}
	}
}
