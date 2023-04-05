<?php

class PlaylistTest extends WP_UnitTestCase {
	/**
	 * @throws ReflectionException
	 */
	public function test_methods() {
		$module               = rapl()->playlist;
		$insert_artist        = $this->make_method_accessible( 'insert_artist' );
		$insert_track         = $this->make_method_accessible( 'insert_track' );
		$insert_track_history = $this->make_method_accessible( 'insert_track_history' );

		// Test artist.
		$artist       = new RAPL_Object_Artist();
		$artist->id   = 45;
		$artist->name = 'The test artist';

		// Test track.
		$track             = new RAPL_Object_Track();
		$track->network_id = 13;
		$track->channel_id = 192;
		$track->track_id   = 772;
		$track->artist     = 'The test artist';
		$track->type       = 'track';
		$track->title      = 'The test song';
		$track->length     = 120;
		$track->started    = 1680587068;

		// Insert.
		$insert_artist->invoke( $module, $artist );
		$insert_track->invoke( $module, $track, $artist->id );
		$insert_track_history->invoke( $module, $track );

		// Get
		$this->assertFalse( $module->has_artist_id( 44 ) );
		$this->assertTrue( $module->has_artist_id( 45 ) );

		$this->assertFalse( $module->has_track( 771 ) );
		$this->assertTrue( $module->has_track( 772 ) );
		$this->assertTrue( $module->has_track_history_id( $track->track_id, $track->started ) > 0 );
	}

	private function make_method_accessible( string $method_name ): ReflectionMethod {
		if ( method_exists( RAPL_Playlist::class, $method_name ) ) {
			try {
				$reflection = new ReflectionClass( RAPL_Playlist::class );
				$method     = $reflection->getMethod( $method_name );
				$method->setAccessible( true );
			} catch ( ReflectionException $e ) {
				die( $e->getMessage() );
			}
		} else {
			throw new RuntimeException( 'Class or method not found.' );
		}

		return $method;
	}
}