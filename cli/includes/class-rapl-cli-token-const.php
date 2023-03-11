<?php

if ( ! class_exists( 'RAPL_CLI_Token_Const' ) ) {
	class RAPL_CLI_Token_Const {
		public function __construct(
			public RAPL_CLI_Token $name,
			public RAPL_CLI_Token $value,
		) {
		}
	}
}