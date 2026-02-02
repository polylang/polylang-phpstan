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
assertType('array<string, mixed>', pll_the_languages(['raw' => true]));

// Raw attribute set to true with array_merge.
assertType('array<string, mixed>', pll_the_languages(array_merge($attributes, ['raw' => true])));

// Raw attribute set to false.
assertType('string', pll_the_languages(['raw' => false]));

// Raw attribute set to false with array_merge and variable with known values.
assertType('string', pll_the_languages(array_merge($attributes, ['raw' => false])));

// Without raw set.
assertType('string', pll_the_languages($attributes));

// With empty array.
assertType('string', pll_the_languages([]));

// Default attributes.
assertType('string', pll_the_languages());

// Unknown attributes.
assertType('array<string, mixed>|string', pll_the_languages($array));

// With unknown variable merged.
$args = array_merge( [ 'raw' => 1 ], $options );
assertType('array<string, mixed>', pll_the_languages($args));

// With raw attribute set to true outside.
$array['raw'] = 1;
assertType('array<string, mixed>', pll_the_languages($array));
