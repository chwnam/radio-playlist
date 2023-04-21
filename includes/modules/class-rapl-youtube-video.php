<?php
/**
 * RAPL: Youtube video extract module
 */

use JetBrains\PhpStorm\NoReturn;
use Monolog\Logger;

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_YouTube' ) ) {
	class RAPL_YouTube implements RAPL_Module {
		private Logger $logger;

		public function __construct() {
			$this->logger = rapl_get_logger();
		}

		#[NoReturn] public function submit_get_youtube_video() {
			$track_id = (int) ( $_GET['track_id'] ?? '0' );

			check_admin_referer( 'rapl_get_youtube_video_' . $track_id, 'nonce' );

			$video_id = $this->extract_video_id( $track_id );

			if ( ! $video_id ) {
				wp_die( 'Video ID not found!' );
			}

			wp_redirect( "https://www.youtube.com/watch?v=$video_id" );
			exit;
		}

		#[NoReturn] public function submit_get_youtube_music() {
			$track_id = (int) ( $_GET['track_id'] ?? '0' );

			check_admin_referer( 'rapl_get_youtube_music_' . $track_id, 'nonce' );

			$video_id = $this->extract_video_id( $track_id );

			if ( ! $video_id ) {
				wp_die( 'Video ID not found!' );
			}

			$url = add_query_arg(
				[
					'feafure' => 'share',
					'v'       => $video_id,
				],
				'https://music.youtube.com/watch'
			);

			wp_redirect( $url );
			exit;
		}

		public function extract_video_id( int $track_id ): string {
			$artist_title = $this->get_artist_title( $track_id );

			if ( ! $artist_title ) {
				$this->logger->error( "Track id $track_id: empty artist and title." );
				return '';
			}

			$url  = self::get_search_query_url( $artist_title[0], $artist_title[1], 'video' );
			$res  = wp_remote_get( $url );
			$body = wp_remote_retrieve_body( $res );

			if ( ! preg_match( '/<script nonce="[A-Za-z0-9_\-]+">var ytInitialData = (.+);<\\/script>/', $body, $matches ) ) {
				$this->logger->error( "Track id $track_id: no regex match." );
				return '';
			}

			$data = trim( $matches[1] );
			$obj  = json_decode( $data );

//			$video_id = $obj
//				?->contents
//				?->twoColumnSearchResultsRenderer
//				?->primaryContents
//				?->sectionListRenderer
//				?->contents[0]
//				?->itemSectionRenderer
//				?->contents[0]
//				?->videoRenderer
//				?->videoId;

			$contents = $obj
				?->contents
				?->twoColumnSearchResultsRenderer
				?->primaryContents
				?->sectionListRenderer
				?->contents[0]
				?->itemSectionRenderer
				?->contents;

			$video_id = ( $contents[0]->videoRenderer ?? null )?->videoId;

			if ( ! $video_id ) {
				for ( $i = 1; $i < count( $contents ); ++ $i ) {
					$video_id = ( $contents[ $i ]->videoRenderer ?? null )?->videoId;
					if ( $video_id ) {
						break;
					}
				}
			}

			if ( ! $video_id ) {
				$this->logger->error( "Track ID $track_id, video ID not found!" );
				return '';
			}

			$this->logger->debug( "Track ID $track_id's video ID: $video_id" );
			return $video_id;
		}

		protected function get_artist_title( int $track_id ): array {
			global $wpdb;

			$query = $wpdb->prepare(
				"SELECT a.name, t.title FROM {$wpdb->prefix}rapl_tracks AS t" .
				" INNER JOIN {$wpdb->prefix}rapl_artists AS a ON a.id=t.artist_id" .
				" WHERE t.id=%d LIMIT 0, 1",
				$track_id
			);

			$record = $wpdb->get_row( $query );

			return $record ? [ $record->name, $record->title ] : [];
		}

		public static function get_search_query_format(): string {
			return '"%1$s" "%2$s"';
		}

		public static function get_search_query_url( string $artist_name, string $title, string $type ): string {
			if ( ! $artist_name || ! $title || ! $type ) {
				return '';
			}

			$query = urlencode( sprintf( static::get_search_query_format(), $artist_name, $title ) );

			return match ( $type ) {
				'video' => add_query_arg( 'search_query', $query, 'https://www.youtube.com/results' ),
				'music' => add_query_arg( 'q', $query, 'https://music.youtube.com/search' ),
			};
		}

		public static function get_direct_url( int $track_id, string $type ): string {
			return match ( $type ) {
				'video' => add_query_arg(
					[
						'action'   => 'rapl_get_youtube_video',
						'nonce'    => wp_create_nonce( 'rapl_get_youtube_video_' . $track_id ),
						'track_id' => $track_id,
					],
					admin_url( 'admin-post.php' )
				),
				'music' => add_query_arg(
					[
						'action'   => 'rapl_get_youtube_music',
						'nonce'    => wp_create_nonce( 'rapl_get_youtube_music_' . $track_id ),
						'track_id' => $track_id,
					],
					admin_url( 'admin-post.php' )
				),
			};
		}
	}
}
