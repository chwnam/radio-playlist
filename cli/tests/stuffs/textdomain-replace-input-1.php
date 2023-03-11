<?php

class RAPL_Textdomain_Test {
	public function test(): string {
		return __( 'RAPL Textdomain Test', 'rapl' );
	}
}

$rapl_textdomain_test = new RAPL_Textdomain_Test();
?>

<div title="<?php esc_attr_e( 'Div title', 'rapl' ); ?>">
	<?php _e( 'String', 'rapl' ); ?>
	<?php
	$string1 = _n( 'single', 'plural', 2, 'rapl' );
	$string2 = _nx( 'single', 'plural', 2, 'context', 'rapl' );
	$string3 = _x( 'string', 'context', 'rapl' );
	$string4 = __( 'rapl' ); // This is textdomain test. This one should not be replaced by the test.
	?>
</div>
