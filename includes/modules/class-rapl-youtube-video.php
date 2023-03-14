<?php
/**
 * RAPL: Youtube video extract module
 */

/* ABSPATH check */

use JetBrains\PhpStorm\NoReturn;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_YouTube_Video' ) ) {
	class RAPL_YouTube_Video implements RAPL_Module {
		#[NoReturn]
		public function submit_get_video(): void {
			$track_id = (int) ( $_GET['track_id'] ?? '0' );

			check_admin_referer( 'rapl_get_video_' . $track_id, 'nonce' );

			$video_id = $this->extract_video_id( $track_id );

			if ( ! $video_id ) {
				wp_die( 'Video ID not found!' );
			}

			wp_redirect( "https://www.youtube.com/watch?v=$video_id" );
			exit;
		}

		public function extract_video_id( int $track_id ): string {
			$artist_title = $this->get_artist_title( $track_id );

			if ( ! $artist_title ) {
				return '';
			}

			$url  = add_query_arg( 'search_query', urlencode( $artist_title ), 'https://www.youtube.com/results' );
			$res  = wp_remote_get( $url );
			$body = wp_remote_retrieve_body( $res );

			if ( ! preg_match( '/<script nonce="[A-Za-z0-9\-]+">var ytInitialData = (.+);<\\/script>/', $body, $matches ) ) {
				return '';
			}

			$data = trim( $matches[1] );
			$obj  = json_decode( $data );

			$video_id = $obj
				?->contents
				?->twoColumnSearchResultsRenderer
				?->primaryContents
				?->sectionListRenderer
				?->contents[0]
				?->itemSectionRenderer
				?->contents[0]
				?->videoRenderer
				?->videoId;

			return $video_id ?? '';
		}

		protected function get_artist_title( int $track_id ): string {
			global $wpdb;

			$query = $wpdb->prepare(
				"SELECT a.name, t.title FROM {$wpdb->prefix}rapl_tracks AS t" .
				" INNER JOIN {$wpdb->prefix}rapl_artists AS a ON a.id=t.artist_id" .
				" WHERE t.id=%d LIMIT 0, 1",
				$track_id
			);

			$record = $wpdb->get_row( $query );

			return $record ? "$record->name $record->title" : "";
		}
	}
}
