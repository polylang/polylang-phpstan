<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \PLL_Links */
$link = $link;

/** @var array */
$array = $array;

$attributes = ['foo' => 'bar'];

// Raw attribute set to true.
assertType('array<string, mixed>', pll_the_languages($link, ['raw' => true]));

// Raw attribute set tot true with array_merge.
assertType('array<string, mixed>', pll_the_languages($link, array_merge($attributes, ['raw' => true])));

// Raw attribute set to false.
assertType('string', pll_the_languages($link, ['raw' => false]));

// Raw attribute set tot false with array_merge.
assertType('string', pll_the_languages($link, array_merge($attributes, ['raw' => false])));

// Without raw set.
assertType('string', pll_the_languages($link, $attributes));

// With empty array.
assertType('string', pll_the_languages($link, []));

// Default attributes.
assertType('string', pll_the_languages($link));

// Unkown attributes.
assertType('array<string, mixed>|string', pll_the_languages($link, $array));

// With unkown variable merged.
$args = array_merge( array( 'raw' => 1 ), $options );
assertType('array<string, mixed>|string', pll_the_languages($link, $args));
