<?php

if ( ! class_exists( 'RAPL_CLI_Textdomain_Replace' ) ) {
	class RAPL_CLI_Textdomain_Replace implements RAPL_CLI_Replace {
		private RAPL_CLI_Token_Extract $extractor;

		public function __construct(
			private string $old_textdomain,
			private string $new_textdomain
		) {
			$this->extractor = new RAPL_CLI_Token_Extract( new RAPL_CLI_Node_Visitor_Node() );
		}

		public function replace( string $content, string $dump_path = '' ): string {
			$tokens = $this->extractor->extract( $content, $dump_path );

			foreach ( array_reverse( $tokens ) as $token ) {
				if ( RAPL_CLI_Token::TYPE_TEXTDOMAIN === $token->type && $this->old_textdomain === $token->value ) {
					$content = substr_replace(
						$content,
						$this->new_textdomain,
						$token->start_pos,
						$token->end_pos - $token->start_pos
					);
				}
			}

			return $content;
		}
	}
}
