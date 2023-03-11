<?php
if ( ! class_exists( 'RAPL_CLI_App' ) ) {
	final class RAPL_CLI_App {
		public function run(): void {
			try {
				$parsed = $this->build_parser()->parse();
			} catch ( Exception $e ) {
				die( 'ERROR: ' . $e->getMessage() . PHP_EOL );
			}

			foreach ( $this->get_commands() as $command ) {
				if ( $parsed->command_name === call_user_func( [ $command, 'get_command_name' ] ) ) {
					$instance = new $command;
					if ( $instance instanceof RAPL_CLI_Command ) {
						$instance->run( $parsed->command );
					}
					break;
				}
			}
		}

		private function build_parser(): Console_CommandLine {
			$parser                       = new Console_CommandLine();
			$parser->description          = 'Naran Boilerplate CLI';
			$parser->renderer->line_width = $this->get_console_width();

			foreach ( $this->get_commands() as $command ) {
				$implements = class_implements( $command );
				if ( isset( $implements[ RAPL_CLI_Command::class ] ) ) {
					call_user_func( [ $command, 'add_command' ], $parser );
				}
			}

			return $parser;
		}

		/**
		 * @return string[]
		 */
		private function get_commands(): array {
			return [
				RAPL_CLI_Command_Make_Zip::class,
				RAPL_CLI_Command_Remove_Hot_Update::class,
				RAPL_CLI_Command_Slug_Change::class,
				RAPL_CLI_Command_Sync_Version::class,
			];
		}

		private function get_console_width(): int {
			try {
				if ( 'Windows' === PHP_OS_FAMILY ) {
					$a1 = shell_exec( 'MODE' );
					/*
					 * Status for device CON:
					 * ----------------------
					 * Lines:          300
					 * Columns:        80
					 * Keyboard rate:  31
					 * Keyboard delay: 1
					 * Code page:      437
					 */
					$arr = explode( "\n", $a1 );
					$col = trim( explode( ':', $arr[4] )[1] );
				} else {
					$col = exec( 'tput cols' );
				}
			} catch ( Exception $ex ) {
				$col = 80;
			}

			return (int) $col;
		}
	}
}
