<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

/** @var \PLL_Links $links */
$links = $links;

/** @var \PLL_Switcher $switcher */
$switcher = new \PLL_Switcher();

$switcher->the_languages( $links, [ 'raw' => true ] );
