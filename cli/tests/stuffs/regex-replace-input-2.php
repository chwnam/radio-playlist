<?php
namespace Naran\RAPL\CLI;

use Naran\RAPL\Core as RAPL_Core;
use function RAPL_Foo\Bar\func as rapl_func;

function rapl_foo_x( int|string|array $foo ): int|string|array|RAPL_Foo {
	return $foo;
}
