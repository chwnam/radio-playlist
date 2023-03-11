<?php
/**
 * RAPL: uninstall script.
 */

if ( ! ( defined( 'WP_UNINSTALL_PLUGIN' ) && WP_UNINSTALL_PLUGIN ) ) {
	exit;
}

require_once __DIR__ . '/index.php';
require_once __DIR__ . '/core/uninstall-functions.php';

$rapl_uninstall = rapl()->registers->uninstall;
if ( $rapl_uninstall ) {
	$rapl_uninstall->register();
}

// You may use these functions to purge data.
// rapl_cleanup_option();
// rapl_cleanup_meta();
// rapl_cleanup_terms();
// rapl_cleanup_posts();
