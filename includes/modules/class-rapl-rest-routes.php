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
						'artist_id'  => [
							'required'          => false,
							'default'           => 0,
							'description'       => '아티스트 ID',
							'sanitize_callback' => fn( $v ) => absint( $v ),
						],
						'channel_id' => [
							'required'          => false,
							'default'           => 0,
							'description'       => '채널 ID',
							'sanitize_callback' => fn( $v ) => absint( $v ),
						],
						'page'       => [
							'required'          => false,
							'description'       => '가져올 페이지 번호',
							'default'           => 1,
							'sanitize_callback' => fn( $v ) => max( 1, absint( $v ) ),
						],
						'per_page'   => [
							'required'          => false,
							'description'       => '페이지당 항목 수',
							'default'           => 10,
							'sanitize_callback' => fn( $v ) => min( 100, max( 1, absint( $v ) ) ),
						],
						'search'     => [
							'required'          => false,
							'description'       => '곡 제목이나 아티스트 이름을 검색',
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						],
						'track_id'   => [
							'required'          => false,
							'default'           => 0,
							'description'       => '트랙 ID',
							'sanitize_callback' => fn( $v ) => absint( $v ),
						],
					],
				]
			);

			/**
			 * 한 아티스트의 재생 이력
			 *
			 * @uses RAPL_REST_Routes::artist()
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
						'orderby'   => [
							'required'          => false,
							'description'       => '정렬 방식',
							'default'           => 'title:asc',
							'sanitize_callback' => fn( $v ) => sanitize_text_field( $v ),
						],
					],
				]
			);

			/**
			 * 한 노래의 재생 이력
			 *
			 * @uses RAPL_REST_Routes::track()
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

			/**
			 * 랭킹 산출
			 *
			 * @uses RAPL_REST_Routes::ranking()
			 */
			yield new RAPL_Reg_REST_Route(
				self::NAMESPACE,
				'ranking/?',
				[
					'method'              => 'GET',
					'callback'            => "$module@ranking",
					'permission_callback' => '__return_true',
					'args'                => [
						'criteria' => [
							'default'           => 'artist',
							'description'       => '랭킹 분야. artist (default), track, or shuffle.',
							'required'          => false,
							'sanitize_callback' => [ RAPL_Store_Ranking::class, 'sanitize_criteria' ],
						],
						'duration' => [
							'default'           => '1w',
							'description'       => '조회기간. 1w, 2w, 3w, 1m, 2m, 3m.',
							'required'          => false,
							'sanitize_callback' =>  [ RAPL_Store_Ranking::class, 'sanitize_duration' ],
						],
						'exclude' => [
							'default'           => 'yes',
							'description'       => '아티스트 제외 적용 여부.',
							'required'          => false,
							'sanitize_callback' =>  [ RAPL_Store_Ranking::class, 'sanitize_exclude' ],
						],
						'number'   => [
							'default'           => 10,
							'description'       => '조회 개수.',
							'required'          => false,
							'sanitize_callback' => [ RAPL_Store_Ranking::class, 'sanitize_number' ],
						],
						'order'    => [
							'default'           => 'desc',
							'description'       => '랭킹 순서, asc: 오름차순, desc 내림차순.',
							'required'          => false,
							'sanitize_callback' => [ RAPL_Store_Ranking::class, 'sanitize_order' ],
						],
					],
				]
			);
		}

		public function playlist( WP_REST_Request $request ): WP_REST_Response {
			$artist_id  = $request->get_param( 'artist_id' );
			$channle_id = $request->get_param( 'channel_id' );
			$page       = $request->get_param( 'page' );
			$per_page   = $request->get_param( 'per_page' );
			$search     = $request->get_param( 'search' );
			$track_id   = $request->get_param( 'track_id' );

			$result = rapl()->stores->history->query(
				[
					'artist_id'  => $artist_id,
					'channel_id' => $channle_id,
					'page'       => $page,
					'per_page'   => $per_page,
					'search'     => $search,
					'track_id'   => $track_id,
				]
			);

			$headers = [
				'X-RAPL-Total'      => $result->total,
				'X-RAPL-TotalPages' => $result->total_pages,
				'X-RAPL-TimeSpent'  => $result->time_spent,
			];

			return new WP_REST_Response( $result->items, 200, $headers );
		}

		public function artist( WP_REST_Request $request ): WP_REST_Response {
			// Modules.
			$artist_store = rapl()->stores->artist;
			$track_store  = rapl()->stores->track;

			// Params
			$artist_id = $request->get_param( 'artist_id' );
			$page      = $request->get_param( 'page' );
			$per_page  = $request->get_param( 'per_page' );
			$orderby   = $request->get_param( 'orderby' );

			// Queries
			$artist = $artist_store->get( $artist_id );
			if ( ! $artist ) {
				return new WP_REST_Response( null, 404 );
			}

			$total_tracks   = $artist_store->total_tracks( $artist_id );
			$total_playback = $artist_store->total_playbacks( $artist_id );
			$first_fetched  = $artist_store->first_fetch( $artist_id );
			$last_fetched   = $artist_store->last_fetch( $artist_id );
			$tracks         = $track_store->query( "artist_id=$artist_id&page=$page&per_page=$per_page&orderby=$orderby" );

			// Response organizing.
			$result = [
				'artist'         => $artist,
				'total_tracks'   => $total_tracks,
				'total_playback' => $total_playback,
				'first_fetched'  => $first_fetched,
				'last_fetched'   => $last_fetched,
				'tracks'         => $tracks->items,
			];

			// Headers organizing.
			$headers = [
				'X-RAPL-Total'      => $tracks->total,
				'X-RAPL-TotalPages' => $tracks->total_pages,
				'X-RAPL-TimeSpent'  => $tracks->time_spent,
			];

			return new WP_REST_Response( $result, 200, $headers );
		}

		public function track( WP_REST_Request $request ): WP_REST_Response {
			// Modules.
			$track_store   = rapl()->stores->track;
			$history_store = rapl()->stores->history;

			// Params.
			$track_id = $request->get_param( 'track_id' );
			$page     = $request->get_param( 'page' );
			$per_page = $request->get_param( 'per_page' );

			// Queries.
			$track = $track_store->get( $track_id );
			if ( ! $track ) {
				return new WP_REST_Response( null, 404 );
			}

			$first_fetched = $track_store->first_fetch( $track_id );
			$last_fetched  = $track_store->last_fetch( $track_id );
			$history       = $history_store->query( "track_id=$track_id&page=$page&per_page=$per_page" );

			// Response organizing.
			$result = [
				'track'         => $track,
				'first_fetched' => $first_fetched,
				'last_fethed'   => $last_fetched,
				'history'       => array_map(
					function ( RAPL_Object_History $item ) {
						return [
							'network_id' => $item->network_id,
							'channel_id' => $item->channel_id,
							'history_id' => $item->history_id,
							'started'    => $item->started,
						];
					},
					$history->items
				),
			];

			// Headers organizing.
			$headers = [
				'X-RAPL-Total'      => $history->total,
				'X-RAPL-TotalPages' => $history->total_pages,
				'X-RAPL-TimeSpent'  => $history->time_spent,
			];

			return new WP_REST_Response( $result, 200, $headers );
		}

		public function ranking( WP_REST_Request $request ): WP_REST_Response {
			// Modules.
			$ranking_store = rapl()->stores->ranking;

			// Extract all params.
			$criteria = $request->get_param( 'criteria' );
			$duration = $request->get_param( 'duration' );
			$exclude  = $request->get_param( 'exclude' );
			$number   = $request->get_param( 'number' );
			$order    = $request->get_param( 'order' );

			return new WP_REST_Response( $ranking_store->get_results( $criteria, $duration, $exclude, $number, $order ) );
		}
	}
}
