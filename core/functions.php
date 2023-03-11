<?php
/**
 * Naran Boilerplate Core
 *
 * functions.php
 */

// PHP_SAPI check make you run unit tests safely.
if ( ! defined( 'ABSPATH' ) && 'cli' !== PHP_SAPI ) {
	exit;
}

if ( ! function_exists( 'rapl_main_file' ) ) {
	function rapl_main_file(): string {
		return defined( 'RAPL_MAIN_FILE' ) ? RAPL_MAIN_FILE : dirname( __DIR__ ) . '/index.php';
	}
}


if ( ! function_exists( 'rapl_priority' ) ) {
	function rapl_priority(): int {
		return defined( 'RAPL_PRIORITY' ) ? RAPL_PRIORITY : 10;
	}
}


if ( ! function_exists( 'rapl_version' ) ) {
	function rapl_version(): string {
		return defined( 'RAPL_VERSION' ) ? RAPL_VERSION : '';
	}
}


if ( ! function_exists( 'rapl_is_theme' ) ) {
	/**
	 * Check if it is used as theme.
	 */
	function rapl_is_theme(): bool {
		return defined( 'RAPL_THEME' ) && RAPL_THEME;
	}
}


if ( ! function_exists( 'rapl_is_plugin' ) ) {
	/**
	 * Check if it is used as plugin. (default)
	 */
	function rapl_is_plugin(): bool {
		return ! rapl_is_theme();
	}
}


if ( ! function_exists( 'rapl_script_debug' ) ) {
	/**
	 * Return SCRIPT_DEBUG.
	 */
	function rapl_script_debug(): bool {
		return apply_filters( 'rapl_script_debug', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
	}
}


if ( ! function_exists( 'rapl_format_callable' ) ) {
	/**
	 * Format callback method or function.
	 *
	 * This method does not care about $callable is actually callable.
	 */
	function rapl_format_callable( mixed $callable ): string {
		if ( is_string( $callable ) ) {
			return $callable;
		}

		if (
			( is_array( $callable ) && 2 === count( $callable ) ) &&
			( is_object( $callable[0] ) || is_string( $callable[0] ) ) &&
			is_string( $callable[1] )
		) {
			if ( method_exists( $callable[0], $callable[1] ) ) {
				try {
					$ref = new ReflectionClass( $callable[0] );
					if ( $ref->isAnonymous() ) {
						return "{AnonymousClass}::$callable[1]";
					}
				} catch ( ReflectionException $e ) {
					return "Error while reflecting $callable[0].";
				}
			}
			if ( is_string( $callable[0] ) ) {
				return "$callable[0]::$callable[1]";
			} else {
				$class = get_class( (object) $callable[0] );
				if ( $class ) {
					return "$class::$callable[1]";
				}
			}
		} elseif ( $callable instanceof Closure ) {
			return '{Closure}';
		}

		return '{Unknown}';
	}
}


if ( ! function_exists( 'rapl_react_refresh_runtime' ) ) {
	/**
	 * Helper function for properly enqueueing 'wp-react-refresh-runtime'.
	 *
	 * Gutenberg plugin must be installed, but its activation is optional.
	 */
	function rapl_react_refresh_runtime(): Generator {
		if ( ! wp_script_is( 'wp-react-refresh-runtime', 'registered' ) ) {
			$path = WP_PLUGIN_DIR . '/gutenberg/build/react-refresh-runtime/index.min.asset.php';
			if ( file_exists( $path ) && is_readable( $path ) ) {
				$asset = include $path;
				if ( is_array( $asset ) && isset( $asset['dependencies'], $asset['version'] ) ) {
					yield new RAPL_Reg_Script(
						'wp-react-refresh-runtime',
						WP_PLUGIN_URL . '/gutenberg/build/react-refresh-runtime/index.min.js',
						$asset['dependencies'],
						$asset['version'],
						true
					);
				}
			}
		}
		yield;
	}
}
