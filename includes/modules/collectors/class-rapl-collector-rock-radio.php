<?php
/**
 * RAPL: Rock radio collector
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Collector_Rock_Radio' ) ) {
	class RAPL_Collector_Rock_Radio implements RAPL_Collector {
		public function collect(): void {
			$logger = rapl_get_logger();
			$logger->info( 'Rock radio collector started.' );

			$fetcher  = rapl()->fetchers->rock_radio;
			$channels = [
				$fetcher->get_channel_thrash_metal(),
				$fetcher->get_channel_power_metal(),
				$fetcher->get_channel_heavy_metal(),
			];


			foreach ( $channels as $channel_id ) {
				sleep( 2 );
				$track_histories = $fetcher->get_track_history( $channel_id );

				foreach ( $track_histories as $track_history ) {
					sleep( 2 );
					$track_info = $fetcher->get_track_info( $track_history->track_id );
					if ( $track_info ) {
						$this->store( $track_history, $track_info );
					}
				}
			}

			$logger->info( 'Rock radio collector finished.' );
		}

		public function store( RAPL_Object_Ext_Track_History $track_history, RAPL_Object_Ext_Track $track_info ): void {
			$artist_store  = rapl()->stores->artist;
			$track_store   = rapl()->stores->track;
			$history_store = rapl()->stores->history;

			$artist_id = $artist_store->find( $track_info->artist_id );
			if ( ! $artist_id ) {
				$artist_id = $artist_store->insert(
					[
						'id'    => $track_info->artist_id,
						'name'  => $track_history->artist,
						'count' => 0,
					]
				);
			}

			$track_id = $track_store->find( $track_history->track_id );
			if ( ! $track_id ) {
				$track_id = $track_store->insert(
					[
						'id'        => $track_history->track_id,
						'artist_id' => $track_info->artist_id,
						'title'     => $track_history->title,
						'length'    => $track_history->length,
						'art_url'   => $track_history->art_url,
						'count'     => 0,
					]
				);
			}

			$history_id = $history_store->find( $track_history->track_id, $track_history->started );
			if ( ! $history_id ) {
				$history_store->insert(
					[
						'network_id' => $track_history->network_id,
						'channel_id' => $track_history->channel_id,
						'track_id'   => $track_history->track_id,
						'started'    => $track_history->started,
					]
				);
				// Increment artist and track count.
				$artist_store->add_count( $artist_id );
				$track_store->add_count( $track_id );
			}
		}
	}
}
