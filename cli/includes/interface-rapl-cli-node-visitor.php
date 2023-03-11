<?php
use PhpParser\NodeVisitor;

if ( ! interface_exists( 'RAPL_CLI_Node_Visitor' ) ) {
	interface RAPL_CLI_Node_Visitor extends NodeVisitor {
		public function get_tokens(): array;
	}
}
