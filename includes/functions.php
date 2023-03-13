<?php
/**
 * RAPL
 *
 * functions.php
 */

// PHP_SAPI check make you run unit tests safely.
if ( ! defined( 'ABSPATH' ) && 'cli' !== PHP_SAPI ) {
	exit;
}

if ( ! function_exists( 'rapl' ) ) {
	/**
	 * RAPL_Main alias.
	 */
	function rapl(): RAPL_Main {
		return RAPL_Main::get_instance();
	}
}


if ( ! function_exists( 'rapl_parse_module' ) ) {
	/**
	 * Retrieve submodule by given string notation.
	 */
	function rapl_parse_module( string $module_notation ): object|false {
		return rapl()->get_module_by_notation( $module_notation );
	}
}


if ( ! function_exists( 'rapl_parse_callback' ) ) {
	/**
	 * Return submodule's callback method by given string notation.
	 *
	 * @throws RAPL_Callback_Exception Thrown if callback is invalid.
	 *
	 * @example foo.bar@baz ---> array( rapl()->foo->bar, 'baz' )
	 */
	function rapl_parse_callback( callable|array|string $maybe_callback ): callable|array|string {
		return rapl()->parse_callback( $maybe_callback );
	}
}


if ( ! function_exists( 'rapl_option' ) ) {
	/**
	 * Alias function for option.
	 */
	function rapl_option(): ?RAPL_Register_Option {
		return rapl()->registers->option;
	}
}


if ( ! function_exists( 'rapl_comment_meta' ) ) {
	/**
	 * Alias function for comment meta.
	 */
	function rapl_comment_meta(): ?RAPL_Register_Comment_Meta {
		return rapl()->registers->comment_meta;
	}
}


if ( ! function_exists( 'rapl_post_meta' ) ) {
	/**
	 * Alias function for post meta.
	 */
	function rapl_post_meta(): ?RAPL_Register_Post_Meta {
		return rapl()->registers->post_meta;
	}
}


if ( ! function_exists( 'rapl_term_meta' ) ) {
	/**
	 * Alias function for term meta.
	 */
	function rapl_term_meta(): ?RAPL_Register_Term_Meta {
		return rapl()->registers->term_meta;
	}
}


if ( ! function_exists( 'rapl_user_meta' ) ) {
	/**
	 * Alias function for user meta.
	 */
	function rapl_user_meta(): ?RAPL_Register_User_Meta {
		return rapl()->registers->user_meta;
	}
}


if ( ! function_exists( 'rapl_get_front_module' ) ) {
	/**
	 * Get front module.
	 *
	 * The module is chosen in RAPL_Register_Theme_Support::map_front_modules().
	 *
	 * @see RAPL_Register_Theme_Support::map_front_modules()
	 */
	function rapl_get_front_module(): RAPL_Front_Module {
		$hierarchy    = RAPL_Theme_Hierarchy::get_instance();
		$front_module = $hierarchy->get_front_module();

		if ( ! $front_module ) {
			$front_module = $hierarchy->get_fallback();
		}

		if ( ! $front_module instanceof RAPL_Front_Module ) {
			throw new RuntimeException( __( '$instance should be a front module instance.', 'rapl' ) );
		}

		return $front_module;
	}
}


if ( ! function_exists( 'rapl_doing_submit' ) ) {
	/**
	 * Chekc if request is from 'admin-post.php'
	 *
	 * @return bool
	 */
	function rapl_doing_submit(): bool {
		return apply_filters( 'rapl_doing_submit', is_admin() && str_ends_with( $_SERVER['SCRIPT_NAME'] ?? '', '/wp-admin/admin-post.php' ) );
	}
}


if ( ! function_exists( 'rapl_format_runtime' ) ) {
	function rapl_format_runtime( int $length ): string {
		$minute = (int) ( $length / 60 );
		$second = $length % 60;

		return sprintf( '%02d:%02d', $minute, $second );
	}
}


if ( ! function_exists( 'rapl_format_timestamp' ) ) {
	function rapl_format_timestamp( int $timestamp ): string {
		return wp_date( 'Y-m-d H:i:s', $timestamp );
	}
}
