<?php
/**
 * RAPL: REST route
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_REST_Routes' ) ) {
	class RAPL_REST_Routes implements RAPL_Module {
		const NAMESPACE = 'rapl/v1';
		const MODULE    = 'rest_routes';

		/**
		 * @return Generator
		 */
		public static function get_regs(): Generator {
			$module = self::MODULE;

			/**
			 * 목록
			 */
			yield new RAPL_Reg_REST_Route(
				self::NAMESPACE,
				'playlist',
				[
					'method'              => 'GET',
					'callback'            => "$module@list",
					'permission_callback' => '__return_true',
					'args'                => [
						'page'     => [
							'required'          => false,
							'description'       => '가져올 페이지 번호',
							'default'           => 1,
							'sanitize_callback' => fn( $v ) => max( 1, absint( $v ) ),
						],
						'per_page' => [
							'required'          => false,
							'description'       => '페이지당 항목 수',
							'default'           => 10,
							'sanitize_callback' => fn( $v ) => min( 100, max( 1, absint( $v ) ) ),
						],
					],
				]
			);
		}

		public function list( WP_REST_Request $request ): WP_REST_Response {
			$page     = $request->get_param( 'page' );
			$per_page = $request->get_param( 'per_page' );

			$result = rapl()->playlist->query(
				[
					'page'     => $page,
					'per_page' => $per_page,
				]
			);

			$headers = [
				'X-WP-Total'       => $result->total,
				'X-WP-TotalPages'  => $result->total_pages,
				'X-Rapl-TimeSpent' => $result->time_spent,
			];

			return new WP_REST_Response( $result->items, 200, $headers );
		}
	}
}
