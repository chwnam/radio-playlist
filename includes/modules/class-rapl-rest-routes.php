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
		const MODULE = 'rest_routes';

		/**
		 * @return Generator
		 */
		public static function get_regs(): Generator {
			$module = self::MODULE;

			/**
			 * 목록
			 *
			 * @uses RAPL_REST_Routes::playlist()
			 */
			yield new RAPL_Reg_REST_Route(
				self::NAMESPACE,
				'playlist',
				[
					'method'              => 'GET',
					'callback'            => "$module@playlist",
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
						'search'   => [
							'required'          => false,
							'description'       => '곡 제목이나 아티스트 이름을 검색',
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						],
					],
				]
			);

			/**
			 * 한 아티스트의 재생 이력
			 *
			 * uses RAPL_REST_Routes::artist()
			 */
			yield new RAPL_Reg_REST_Route(
				self::NAMESPACE,
				'artist/(?P<artist_id>\d+)/?',
				[
					'method'              => 'GET',
					'callback'            => "$module@artist",
					'permission_callback' => '__return_true',
					'args'                => [
						'artist_id' => [
							'required'          => true,
							'description'       => '아티스트 ID',
							'sanitize_callback' => fn( $v ) => absint( $v ),
						],
						'page'      => [
							'required'          => false,
							'description'       => '가져올 페이지 번호',
							'default'           => 1,
							'sanitize_callback' => fn( $v ) => max( 1, absint( $v ) ),
						],
						'per_page'  => [
							'required'          => false,
							'description'       => '페이지당 항목 수',
							'default'           => 10,
							'sanitize_callback' => fn( $v ) => min( 100, max( 1, absint( $v ) ) ),
						],
					],
				]
			);

			/**
			 * 한 노래의 재생 이력
			 *
			 * uses RAPL_REST_Routes::track()
			 */
			yield new RAPL_Reg_REST_Route(
				self::NAMESPACE,
				'track/(?P<track_id>\d+)/?',
				[
					'method'              => 'GET',
					'callback'            => "$module@track",
					'permission_callback' => '__return_true',
					'args'                => [
						'track_id' => [
							'required'          => true,
							'description'       => '트랙 ID',
							'sanitize_callback' => fn( $v ) => absint( $v ),
						],
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

		public function playlist( WP_REST_Request $request ): WP_REST_Response {
			$page     = $request->get_param( 'page' );
			$per_page = $request->get_param( 'per_page' );
			$search   = $request->get_param( 'search' );

			$result = rapl()->playlist->query(
				[
					'page'     => $page,
					'per_page' => $per_page,
					'search'   => $search,
				]
			);

			$headers = [
				'X-WP-Total'       => $result->total,
				'X-WP-TotalPages'  => $result->total_pages,
				'X-Rapl-TimeSpent' => $result->time_spent,
			];

			return new WP_REST_Response( $result->items, 200, $headers );
		}

		public function artist( WP_REST_Request $request ): WP_REST_Response {
			$artist_id = $request->get_param( 'artist_id' );
			$page      = $request->get_param( 'page' );
			$per_page  = $request->get_param( 'per_page' );

			$result = rapl()->playlist->query(
				[
					'artist_id' => $artist_id,
					'page'      => $page,
					'per_page'  => $per_page,
				]
			);

			$headers = [
				'X-WP-Total'       => $result->total,
				'X-WP-TotalPages'  => $result->total_pages,
				'X-Rapl-TimeSpent' => $result->time_spent,
			];

			return new WP_REST_Response( $result->items, 200, $headers );
		}

		public function track( WP_REST_Request $request ): WP_REST_Response {
			$track_id = $request->get_param( 'track_id' );
			$page     = $request->get_param( 'page' );
			$per_page = $request->get_param( 'per_page' );

			$result = rapl()->playlist->query(
				[
					'track_id' => $track_id,
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
