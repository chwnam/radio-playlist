<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'RAPL_YouTube_Prop_Trait' ) ) {
	trait RAPL_YouTube_Prop_Trait {
		/**
		 * @var stdClass{
		 *     video: stdClass{
		 *         direct: string,
		 *         search: string,
		 *     },
		 *     music: stdClass{
		 *         direct: string,
		 *         search:string
		 *     }
		 * }
		 */
		public stdClass $youtube;

		public function __construct() {
			$this->init_youtube();
		}

		public function init_youtube(): static {
			$this->youtube = (object) [
				'video' => (object) [
					'direct' => '',
					'search' => '',
				],
				'music' => (object) [
					'direct' => '',
					'search' => '',
				],
			];

			return $this;
		}

		public function set_youtube( int $track_id, string $artist_name, string $title ): static {
			$this->init_youtube();

			if ( $this->track_id ) {
				$this->youtube->music->direct = RAPL_YouTube::get_direct_url( $track_id, 'music' );
				$this->youtube->video->direct = RAPL_YouTube::get_direct_url( $track_id, 'video' );
			}

			if ( $artist_name && $title ) {
				$this->youtube->music->search = RAPL_YouTube::get_search_query_url( $artist_name, $title, 'music' );
				$this->youtube->video->search = RAPL_YouTube::get_search_query_url( $artist_name, $title, 'video' );
			}

			return $this;
		}
	}
}