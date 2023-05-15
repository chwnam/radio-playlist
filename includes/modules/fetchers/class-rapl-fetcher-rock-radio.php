<?php
/**
 * RAPL: Rock radio fetcher
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Fetcher_Rock_Radio' ) ) {
	/**
	 * 플레이리스트를 수집기 모듈
	 */
	class RAPL_Fetcher_Rock_Radio implements RAPL_Fetcher {
		/**
		 * 원격 서버로부터 채널의 방송 목록을 가져온다.
		 *
		 * @param int $channel 채널 ID.
		 *
		 * @return array<RAPL_Object_Ext_Track_History>
		 */
		public function get_track_history( int $channel ): array {
			$url      = "https://api.audioaddict.com/v1/rockradio/track_history/channel/$channel";
			$response = wp_remote_get( $url );

			$code   = wp_remote_retrieve_response_code( $response );
			$body   = json_decode( wp_remote_retrieve_body( $response ) );
			$result = [];

			if ( 200 === $code && is_array( $body ) ) {
				$result = array_filter(
					array_map( [ RAPL_Object_Ext_Track_History::class, 'import' ], $body ),
					fn ( RAPL_Object_Ext_Track_History $item ) => $item->type === "track"
				);
			}

			return $result;
		}

		/**
		 * 원격 서버로부터 한 트랙에 대한 아티스트 정보를 가져 온다.
		 *
		 * @param int $track_id
		 *
		 * @return RAPL_Object_Ext_Track|null
		 */
		public function get_track_info( int $track_id ): ?RAPL_Object_Ext_Track {
			$url      = "https://api.audioaddict.com/v1/rockradio/tracks/$track_id";
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 200 === $code && $body ) {
				return RAPL_Object_Ext_Track::import( $body );
			}

			return null;
		}

		/**
		 * 스래치 메탈 채널 ID 리턴.
		 *
		 * @return int
		 */
		public function get_channel_thrash_metal(): int {
			return 192;
		}

		/**
		 * 파워 메탈 채널 ID 리턴.
		 *
		 * @return int
		 */
		public function get_channel_power_metal(): int {
			return 163;
		}

		/**
		 * 헤비 메탈 채널 ID 리턴.
		 */
		public function get_channel_heavy_metal(): int {
			return 149;
		}
	}
}
