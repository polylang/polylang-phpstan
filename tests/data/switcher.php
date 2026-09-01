<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use WP_Syntex\Polylang\Switcher\Switcher;
use WP_Syntex\Polylang\Switcher\Settings\Settings;
use function PHPStan\Testing\assertType;

/** @var Settings $settings */
$settings = $settings;

/** @var \PLL_Links $links */
$links = $links;

$switcher = new Switcher( $settings, $links );

assertType( 'string', $switcher->get() );
assertType( 'array<WP_Syntex\Polylang\Switcher\Element\Abstract_Element>', $switcher->get_elements() );
