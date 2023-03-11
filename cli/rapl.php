#!/usr/bin/env php
<?php
if ( 'cli' !== PHP_SAPI ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'RAPL_CLI_ROOT', __DIR__ );
define( 'RAPL_ROOT', dirname( __DIR__ ) );
define( 'THE_GULS', 'cpbn' );
define( 'THE_SLUG', strrev( THE_GULS ) );

if ( ! defined( 'RAPL_CLI_TEST' ) ) {
	$app = new RAPL_CLI_App();
	$app->run();
}
