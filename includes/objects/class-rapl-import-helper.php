<?php
/**
 * RAPL: Import helper
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Import_Helper' ) ) {
	class RAPL_Import_Helper {
		protected static array $object_data = [];

		public static function import( array|object $item, string $class_name ): mixed {
			$instance = new $class_name();

			foreach ( static::extract_data( $class_name ) as $prop => $type ) {
				$instance->$prop = static::from_item( $item, $prop, static::get_type_default( $type, $instance->$prop ) );
			}

			return $instance;
		}

		/**
		 * @param array|object $item
		 * @param string       $key
		 * @param mixed        $default
		 *
		 * @return mixed
		 */
		public static function from_item( array|object $item, string $key, mixed $default = '' ): mixed {
			if ( is_array( $item ) ) {
				return $item[ $key ] ?? $default;
			} else {
				return $item->$key ?? $default;
			}
		}

		/**
		 * @param string $type
		 * @param mixed  $default
		 *
		 * @return mixed
		 */
		public static function get_type_default( string $type, mixed $default ): mixed {
			return match ( $type ) {
				'int'    => 0,
				'string' => '',
				default  => $default,
			};
		}

		public static function extract_data( string $class_name ): array {
			if ( ! isset( static::$object_data[ $class_name ] ) ) {
				try {
					$item_data = [];
					foreach ( ( new ReflectionClass( $class_name ) )->getProperties( ReflectionProperty::IS_PUBLIC ) as $property ) {
						$item_data[ $property->getName() ] = $property->getType()->getName();
					}
					static::$object_data[ $class_name ] = $item_data;
				} catch ( ReflectionException $e ) {
					die( $e->getMessage() );
				}
			}

			return static::$object_data[ $class_name ];
		}
	}
}
