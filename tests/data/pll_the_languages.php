<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var array */
$array = $array;

/** @var array<string, mixed> */
$options = $options;

$attributes = [ 'foo' => 'bar' ];

// raw => true
assertType( 'array<string, mixed>', pll_the_languages( [ 'raw' => true ] ) );
assertType( 'array<string, mixed>', pll_the_languages( array_merge( $attributes, [ 'raw' => true ] ) ) );

// echo => false
assertType( 'string', pll_the_languages( [ 'echo' => false ] ) );
assertType( 'string', pll_the_languages( [ 'raw' => false, 'echo' => false ] ) );
assertType( 'string', pll_the_languages( array_merge( $attributes, [ 'echo' => false ] ) ) );

// Default: echo is true, nothing is returned.
assertType( 'void', pll_the_languages() );
assertType( 'void', pll_the_languages( [] ) );
assertType( 'void', pll_the_languages( $attributes ) );
assertType( 'void', pll_the_languages( [ 'raw' => false ] ) );
assertType( 'void', pll_the_languages( array_merge( $attributes, [ 'raw' => false ] ) ) );

// Unknown attributes.
assertType( 'array<string, mixed>|string|void', pll_the_languages( $array ) );

// With unknown variable merged.
$args = array_merge( [ 'raw' => 1 ], $options );
assertType( 'array<string, mixed>|string|void', pll_the_languages( $args ) );

// With raw attribute set to true outside.
$array['raw'] = 1;
assertType( 'array<string, mixed>', pll_the_languages( $array ) );

// With raw attribute set to false outside a previously unknown array.
$array['raw'] = false;
assertType( 'string|void', pll_the_languages( $array ) );

// With echo attribute set to false outside.
$array['echo'] = false;
assertType( 'string', pll_the_languages( $array ) );
