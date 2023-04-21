<?php
/**
 * RAPL: Playlist module
 */

use Monolog\Logger;

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Playlist' ) ) {
	/**
	 * 플레이리스트를 위한 여러 작업들
	 */
	class RAPL_Playlist implements RAPL_Module {
		private Logger $logger;

		public function __construct() {
			$this->logger = rapl_get_logger();
		}

		/**
		 * 원격 서버로부터 채널의 방송 목록을 가져온다.
		 *
		 * @param int $channel 채널 ID.
		 *
		 * @return array
		 */
		public function fetch( int $channel ): array {
			$this->logger->info( 'Getting channel track history.' );

			$url      = "https://api.audioaddict.com/v1/rockradio/track_history/channel/$channel";
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			return 200 === $code && is_array( $body ) ? $body : [];
		}

		/**
		 * 원격 서버로부터 한 트랙에 대한 아티스트 정보를 가져 온다.
		 *
		 * @param int $track_id
		 *
		 * @return RAPL_Object_Artist|null
		 */
		public function fetch_artist( int $track_id ): ?RAPL_Object_Artist {
			$this->logger->info( "Getring track infomation of $track_id." );

			$url      = "https://api.audioaddict.com/v1/rockradio/tracks/$track_id";
			$response = wp_remote_get( $url );

			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 200 === $code && $body ) {
				return RAPL_Object_Artist::from_object( $body->artist );
			}

			$this->logger->error( "Error. No infomation found for track $track_id." );

			return null;
		}

		/**
		 * 원격 서버로부터 수신한 방송 목록을 데이터베이스에 저장한다.
		 *
		 * @param int   $channel
		 * @param array $items
		 *
		 * @return void
		 */
		public function collect( int $channel,  array $items ): void {
			/** @var RAPL_Object_Track[] $tracks */
			$tracks = array_map( [ 'RAPL_Object_Track', 'from_object' ], $items );

			$total       = count( $tracks );
			$new_artists = 0;
			$new_tracks  = 0;
			$new_history = 0;
			$skipped     = 0;

			$this->logger->info( sprintf( "Fetched %d tracks.", $total ) );

			foreach ( $tracks as $track ) {
				if ( 'track' !== $track->type ) {
					$this->logger->info( "Skipping track $track->track_id because it is not a track type." );
					$skipped += 1;
					continue;
				}

				// Add track if not exists.
				$has_track = $this->has_track( $track->track_id );
				if ( ! $has_track ) {
					// Fetch artist infomation from the remote server.
					sleep( 2 );
					$artist = $this->fetch_artist( $track->track_id );
					if ( ! $artist ) {
						continue;
					}

					// Add artist infomation.
					if ( ! $this->has_artist_id( $artist->id ) ) {
						$this->insert_artist( $artist );
						$new_artists += 1;
					}

					// Add track infomation.
					$this->insert_track( $track, $artist->id );
					$new_tracks += 1;
				}

				// Add track history.
				if ( ! $this->has_track_history_id( $channel, $track->track_id, $track->started ) ) {
					$this->insert_track_history( $track );
					$new_history += 1;
				}
			}

			$this->logger->info(
				sprintf(
					'Collecting completed. %d new artsts, %d new tracks, %d new history, and %d skipped.',
					$new_artists,
					$new_tracks,
					$new_history,
					$skipped
				)
			);
		}

		/**
		 * 데이터 덤프.
		 *
		 * @param array  $items   서버로부터 받은 내용 그대로.
		 * @param string $path    덤프할 경로. 생략시 약속한 기본 장소에 덤프.
		 * @param string $postfix 파일 이름에 붙일 접미. 경로 생략시에만 유효합니다.
		 *
		 * @return void
		 */
		public function dump( array $items, string $path = '', string $postfix = '' ): void {
			if ( ! $path ) {
				$basedir = rapl_get_upload_private_directory( 'dump' );
				$date    = wp_date( 'Ymd-His' );
				$path    = "$basedir/rapl-$postfix-$date.json";
			}

			$encoded = wp_json_encode( $items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

			file_put_contents( $path, $encoded );
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
		 * 데이터베이스에 저장된 트랙 정보를 쿼리.
		 *
		 * @param array $args
		 *
		 * @return RAPL_Object_Track_Query
		 */
		public function query( array $args = [] ): RAPL_Object_Track_Query {
			global $wpdb;

			$defaults = [
				'page'     => 1,
				'per_page' => 10,
				'search'   => '',
			];

			$args     = wp_parse_args( $args, $defaults );
			$page     = max( 1, $args['page'] );
			$per_page = min( 100, max( 1, $args['per_page'] ) );

			$fields = [
				"h.network_id",
				"h.channel_id",
				"t.id AS track_id",
				"t.title",
				"a.id AS artist_id",
				"a.name AS artist_name",
				"t.length",
				"h.started",
				"t.art_url",
			];

			$f      = implode( ', ', $fields );
			$offset = ( $page - 1 ) * $per_page;
			$limit  = $wpdb->prepare( "LIMIT %d, %d", $offset, $per_page );
			$where  = "WHERE 1=1";

			if ( $args['search'] ) {
				$like  = esc_sql( '%' . $wpdb->esc_like( $args['search'] ) . '%' );
				$where .= $wpdb->prepare(
					" AND ((a.name LIKE %s) OR (t.title LIKE %s))",
					$like,
					$like
				);
			}

			$query = "SELECT SQL_CALC_FOUND_ROWS $f FROM {$wpdb->prefix}rapl_artists AS a" .
			         " INNER JOIN {$wpdb->prefix}rapl_tracks AS t ON t.artist_id = a.id" .
			         " INNER JOIN {$wpdb->prefix}rapl_history AS h ON h.track_id=t.id" .
			         " $where ORDER BY h.started DESC $limit";

			$wpdb->timer_start();
			$rows       = $wpdb->get_results( $query );
			$time       = $wpdb->timer_stop();
			$found_rows = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );
			$records    = array_map( [ RAPL_Object_Track_History::class, 'from_object' ], $rows );

			$result              = new RAPL_Object_Track_Query();
			$result->items       = $records;
			$result->per_page    = $per_page;
			$result->page        = $page;
			$result->total       = $found_rows;
			$result->total_pages = (int) ceil( (float) $found_rows / (float) $per_page );
			$result->time_spent  = $time;

			return $result;
		}

		/**
		 * 아티스트가 저장되었는지 확인.
		 *
		 * @param int $artist_id
		 *
		 * @return bool
		 */
		public function has_artist_id( int $artist_id ): bool {
			global $wpdb;

			return (bool) $wpdb->get_var(
				$wpdb->prepare( "SELECT id FROM {$wpdb->prefix}rapl_artists WHERE id=%d", $artist_id )
			);
		}

		/**
		 * 트랙이 저장되었는지 확인.
		 *
		 * @param int $track_id
		 *
		 * @return bool
		 */
		public function has_track( int $track_id ): bool {
			global $wpdb;

			return (bool) $wpdb->get_var(
				$wpdb->prepare( "SELECT id FROM {$wpdb->prefix}rapl_tracks WHERE id=%d", $track_id )
			);
		}

		/**
		 * 트랙의 방송 내역이 저장되었는지 확인.
		 *
		 * @param int $channel
		 * @param int $track_id
		 * @param int $started
		 *
		 * @return bool
		 */
		public function has_track_history_id( int $channel, int $track_id, int $started ): bool {
			global $wpdb;

			return (bool) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}rapl_history WHERE network_id=13 AND channel_id=%d AND track_id=%d AND started=%d",
					$channel,
					$track_id,
					$started
				)
			);
		}

		/**
		 * 아티스트 저장.
		 *
		 * @param RAPL_Object_Artist $artist
		 *
		 * @return void
		 */
		protected function insert_artist( RAPL_Object_Artist $artist ): void {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"INSERT IGNORE INTO {$wpdb->prefix}rapl_artists(id, name) VALUES(%d, %s)",
					$artist->id,
					$artist->name
				)
			);
		}

		/**
		 * 트랙 저장.
		 *
		 * @param RAPL_Object_Track $track
		 * @param int               $artist_id
		 *
		 * @return void
		 */
		protected function insert_track( RAPL_Object_Track $track, int $artist_id ): void {
			global $wpdb;

			$query = "INSERT IGNORE INTO {$wpdb->prefix}rapl_tracks(id, artist_id, title, length, art_url)" .
			         " VALUES(%d, %d, %s, %d, %s)";

			$wpdb->query(
				$wpdb->prepare(
					$query,
					$track->track_id,
					$artist_id,
					$track->title,
					$track->length,
					$track->art_url
				)
			);
		}

		/**
		 * 트랙 방송 내역 저장.
		 *
		 * @param RAPL_Object_Track $track
		 *
		 * @return void
		 */
		protected function insert_track_history( RAPL_Object_Track $track ): void {
			global $wpdb;

			$query = "INSERT IGNORE INTO {$wpdb->prefix}rapl_history(network_id, channel_id, track_id, started)" .
			         " VALUES(%d, %d, %d, %d)";

			$wpdb->query(
				$wpdb->prepare(
					$query,
					$track->network_id,
					$track->channel_id,
					$track->track_id,
					$track->started
				)
			);
		}
	}
}
