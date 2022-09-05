<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \PLL_Links */
$link = $link;

$attritubtes = [];

// Raw attribute set to true.
assertType('array<string, mixed>', pll_the_languages($link, ['raw' => true]));

// Raw attribute set tot true with array_merge.
assertType('array<string, mixed>', pll_the_languages($link, array_merge($attritubtes, ['raw' => true])));

// Raw attribute set to false.
assertType('string', pll_the_languages($link, ['raw' => false]));

// Raw attribute set tot false with array_merge.
assertType('string', pll_the_languages($link, array_merge($attritubtes, ['raw' => false])));

// Without raw set.
assertType('string', pll_the_languages($link, $attritubtes));

// Default attributes.
assertType('string', pll_the_languages($link));


