<?php
if ( ! interface_exists( 'RAPL_CLI_Replace' ) ) {
	interface RAPL_CLI_Replace {
		public function replace( string $content ): string;
	}
}
