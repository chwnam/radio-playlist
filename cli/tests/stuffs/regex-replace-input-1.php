<?php
/**
 * File for slug change testing.
 *
 * Upper slug: RAPL, for class, interface, trait names.
 * Lower slug: rapl, for variable, method names.
 *
 * Sluges will be replaced only if they are used as whole words.
 * e.g. Replaced: RAPL, rapl, RAPL-Foo, RAPL_Foo, Foo-RAPL, Foo_RAPL, rapl-foo, rapl_foo, get_rapl, get_rapl_some
 *      Ignored:  NBPCFoo, FooNBPC, nbpcfoo, foonbpc
 */

if ( ! interface_exists( 'RAPL_ID_Interface' ) ) {
	interface RAPL_ID_Interface {
		public function get_id(): int;
	}
}

if ( ! interface_exists( 'RAPL_Minor_Interface' ) ) {
	interface RAPL_Minor_Interface {
		public function do_minor_thing();
	}
}

if ( ! interface_exists( 'RAPL_Side_Interface' ) ) {
	interface RAPL_Side_Interface {
		public function do_side_thing();
	}
}

if ( ! interface_exists( 'RAPL_Major_Interface' ) ) {
	interface RAPL_Major_Interface extends RAPL_Minor_Interface, RAPL_ID_Interface {
		public function do_major_thing();
	}
}

if ( ! class_exists( 'RAPL_Major_Impl' ) ) {
	class RAPL_Major_Impl implements RAPL_Major_Interface {
		public function __construct( public int $id ) {
		}

		public function do_minor_thing() {
			echo "Minor\n";
		}

		public function do_major_thing() {
			echo "Major: {$this->get_id()}\n";
		}

		public function get_id(): int {
			return $this->id;
		}
	}
}

if ( ! class_exists( 'RAPL_Side_Impl' ) ) {
	class RAPL_Side_Impl implements RAPL_Side_Interface, RAPL_ID_Interface {
		public function __construct( public int $id ) {
		}

		public function do_side_thing() {
			echo "Side: {$this->get_id()}\n";
		}

		public function get_id(): int {
			return $this->id;
		}
	}
}

if ( ! trait_exists( 'RAPL_Extraction_Trait' ) ) {
	trait RAPL_Extraction_Trait {
		protected string $trait_value;

		public function rapl_get_trait_value(): string {
			return $this->trait_value;
		}
	}
}

if ( ! class_exists( 'RAPL_Extraction_Base ' ) ) {
	abstract class RAPL_Extraction_Base {
		abstract protected function get_extractions(): array;
	}
}

if ( ! class_exists( 'RAPL_Extraction_Input_1' ) ) {
	/**
	 * RAPL_Extraction_Input_1
	 *
	 * Lower slug: rapl
	 * Upper slug: RAPL
	 */
	class RAPL_Extraction_Input_1 extends RAPL_Extraction_Base implements RAPL_Major_Interface, RAPL_Side_Interface {
		use RAPL_Extraction_Trait;

		public const EXT = 'rapl_ext';

		private array $rapl_doubled;

		private array $rapl_tripled;

		private array $rapl_quadrupled;

		private RAPL_Major_Interface $major;

		private RAPL_Side_Interface $side;

		public function __construct( RAPL_Major_Interface $major, RAPL_Side_Interface $side ) {
			$this->major = $major;
			$this->side  = $side;

			$this->rapl_doubled = array_map(
				[ $this, 'rapl_make_double' ],
				[ __CLASS__, 'rapl_get_basic_array' ]
			);

			$this->rapl_tripled = array_map(
				function ( $value ) { return rapl_get_three() * $value; },
				[ __CLASS__, 'rapl_get_basic_array' ]
			);

			$this->rapl_quadrupled = array_map(
				fn( $value ) => rapl_get_four() * $value,
				[ $this, 'rapl_get_basic_array' ]
			);

			$this->trait_value = static::EXT;
		}

		public function do_minor_thing() {
			$this->major->do_minor_thing();
		}

		public function do_major_thing() {
			$this->major->do_major_thing();
		}

		public function do_side_thing() {
			$this->side->do_side_thing();
		}

		public function get_extractions(): array {
			return [
				RAPL_Extraction_Input_1::class,
				RAPL_Minor_Interface::class,
				RAPL_Side_Interface::class,
			];
		}

		public function get_rapl_items(): string {
			return implode( ', ', [ ...$this->rapl_doubled, ...$this->rapl_tripled, ...$this->rapl_quadrupled ] );
		}

		public function rapl_make_double( int $value ): int {
			return rapl_get_two() * $value;
		}

		public function create_another(): RAPL_Extraction_Input_1 {
			return new RAPL_Extraction_Input_1(
				new RAPL_Major_Impl( $this->get_major()->get_id() + 1 ),
				new RAPL_Side_Impl( $this->get_side()->get_id() + 1 )
			);
		}

		public function get_major(): RAPL_Major_Interface {
			return $this->major;
		}

		public function set_major( RAPL_Major_Interface $major ) {
			$this->major = $major;
		}

		public function get_side(): RAPL_Side_Interface {
			return $this->side;
		}

		public function set_side( RAPL_Side_Interface $side ) {
			$this->side = $side;
		}

		public function get_id(): int {
			return $this->major->get_id();
		}

		public static function rapl_get_basic_array(): array {
			return [ 1, 2, 3 ];
		}
	}
}

if ( ! function_exists( 'rapl_get_two' ) ) {
	function rapl_get_two(): int {
		return 2;
	}
}

if ( ! function_exists( 'rapl_get_three' ) ) {
	function rapl_get_three(): int {
		return 3;
	}
}

if ( ! function_exists( 'rapl_get_four' ) ) {
	function rapl_get_four(): int {
		return 4;
	}
}

if ( ! function_exists( 'rapl_double_major' ) ) {
	function rapl_double_major( RAPL_Major_Interface $major ): RAPL_Major_Impl {
		return new RAPL_Major_Impl( $major->get_id() * rapl_get_two() );
	}
}

if ( ! function_exists( 'rapl_create_side' ) ) {
	function rapl_create_side( int $id ): RAPL_Side_Impl {
		return new RAPL_Side_Impl( $id );
	}
}

if ( ! function_exists( 'nbpcfoo' ) ) {
	// Intentionally testing wrong slug.
	function nbpcfoo(): void {
	}
}

// Commnet and rapl.
esc_html_e( RAPL_Extraction_Input_1::EXT, 'rapl' );

// Do silly things, just for testing.
$rapl_major = new RAPL_Major_Impl( 1 );
$rapl_side  = new RAPL_Side_Impl( 1 );

// Comment and RAPL.
$ex1 = new RAPL_Extraction_Input_1( $rapl_major, $rapl_side );
echo $ex1->get_rapl_items();

# Command and RAPL.
$ex2 = $ex1->create_another();

$ex1->set_major( rapl_double_major( $ex2->get_major() ) );
$ex1->set_side( rapl_create_side( 10 ) );

printf(
	'<a href="%s" title="%s">%s</a">',
	esc_url( get_home_url() ),
	esc_attr( __( 'Link for test', 'rapl' ) ),
	esc_html( _n( 'A Link', 'Links', 2, 'rapl' ) . ' ' )
);
?>

    <p><?php echo esc_html( $ex1->rapl_get_trait_value() ); ?><p>

<?php

$arr_rapl = RAPL_Extraction_Input_1::rapl_get_basic_array();
echo esc_html( $arr_rapl[1] );
