<?php
/**
 * RAPL: Rest reoute register
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Register_REST_Route' ) ) {
	class RAPL_Register_REST_Route extends RAPL_Register_Base_REST_Route {
		/**
		 * Define your custom API endpoint.
		 *
		 * @return Generator
		 *
		 * @link   https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
		 * @sample yield new RAPL_Reg_REST_Route(
		 *           'rapl/v1',
		 *           'author/(?P<id>\d+)',
		 *           [
		 *             'methods'  => 'GET',
		 *             'callback' => 'module_v1@author'
		 *             'args'     => [
		 *               'id' => [
		 *                 'sanitize_callback' => 'module_v1@sanitize_id',
		 *                 'validate_callback' => 'module_v1@validate_id',
		 *                 'required'          => true,
		 *                 'default'           => '',
		 *               ],
		 *             ],
		 *           ]
		 *         );
		 */
		public function get_items(): Generator {
			yield from RAPL_REST_Routes::get_regs();
		}
	}
}
