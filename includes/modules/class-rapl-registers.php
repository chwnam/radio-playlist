<?php
/**
 * RAPL: Registers module
 *
 * Manage all registers
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Registers' ) ) {
	/**
	 * You can remove unused registers.
	 *
	 * @property-read RAPL_Register_Activation    $activation
	 * @property-read RAPL_Register_Ajax          $ajax
	 * @property-read RAPL_Register_Block         $block
	 * @property-read RAPL_Register_Capability    $cap
	 * @property-read RAPL_Register_Comment_Meta  $comment_meta
	 * @property-read RAPL_Register_Cron          $cron
	 * @property-read RAPL_Register_Cron_Schedule $cron_schedule
	 * @property-read RAPL_Register_Custom_Table  $custom_table
	 * @property-read RAPL_Register_Deactivation  $deactivation
	 * @property-read RAPL_Register_Menu          $menu
	 * @property-read RAPL_Register_Option        $option
	 * @property-read RAPL_Register_Post_Meta     $post_meta
	 * @property-read RAPL_Register_Post_Type     $post_type
	 * @property-read RAPL_Register_Rest_Route    $rest_route
	 * @property-read RAPL_Register_Rewrite_Rule  $rewrite_rule
	 * @property-read RAPL_Register_Role          $role
	 * @property-read RAPL_Register_Script        $script
	 * @property-read RAPL_Register_Shortcode     $shortcode
	 * @property-read RAPL_Register_Sidebar       $sidebar
	 * @property-read RAPL_Register_Style         $style
	 * @property-read RAPL_Register_Submit        $submit
	 * @property-read RAPL_Register_Taxonomy      $taxonomy
	 * @property-read RAPL_Register_Theme_Support $theme_support
	 * @property-read RAPL_Register_Term_Meta     $term_meta
	 * @property-read RAPL_Register_Uninstall     $uninstall
	 * @property-read RAPL_Register_User_Meta     $user_meta
	 * @property-read RAPL_Register_Widget        $widget
	 * @property-read RAPL_Register_WP_CLI        $wp_cli
	 */
	class RAPL_Registers implements RAPL_Module {
		use RAPL_Submodule_Impl;

		/**
		 * Constructor method
		 */
		public function __construct() {
			/**
			 * You can remove unused registers.
			 */
			$this->assign_modules(
				[
//					'activation'    => RAPL_Register_Activation::class,
//					'ajax'          => RAPL_Register_AJAX::class,
//					'block'         => RAPL_Register_Block::class,
//					'cap'           => function () { return new RAPL_Register_Capability(); },
//					'comment_meta'  => RAPL_Register_Comment_Meta::class,
					'cron'          => RAPL_Register_Cron::class,
					'cron_schedule' => RAPL_Register_Cron_Schedule::class,
					'custom_table'  => RAPL_Register_Custom_Table::class,
//					'deactivation'  => RAPL_Register_Deactivation::class,
					'menu'          => RAPL_Register_Menu::class,
//					'option'        => RAPL_Register_Option::class,
//					'post_meta'     => RAPL_Register_Post_Meta::class,
//					'post_type'     => RAPL_Register_Post_Type::class,
//					'rest_route'    => RAPL_Register_REST_Route::class,
//					'rewrite_rule'  => RAPL_Register_Rewrite_Rule::class,
//					'role'          => function () { return new RAPL_Register_Role(); },
//					'script'        => RAPL_Register_Script::class,
//					'shortcode'     => RAPL_Register_Shortcode::class,
//					'sidebar'       => RAPL_Register_Sidebar::class,
//					'style'         => RAPL_Register_Style::class,
//					'submit'        => RAPL_Register_Submit::class,
//					'taxonomy'      => RAPL_Register_Taxonomy::class,
//					'term_meta'     => RAPL_Register_Term_Meta::class,
					// 'theme_support' => RAPL_Register_Theme_Support::class, // Only for themes.
//					'uninstall'     => function () { return new RAPL_Register_Uninstall(); },
//					'user_meta'     => RAPL_Register_User_Meta::class,
//					'widget'        => RAPL_Register_Widget::class,
					'wp_cli'        => RAPL_Register_WP_CLI::class,
				]
			);
		}
	}
}
