<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \WP_Syntex\Polylang\Options\Options */
$options = $options;

// With an unknown option.
assertType('null', $options->get('foo'));

// With a Pro option in PLL Free.
assertType('null', $options->get('media'));

// With a boolean type option.
foreach ( [ 'browser', 'hide_default', 'media_support', 'redirect_lang', 'rewrite' ] as $option_name ) {
	assertType('bool', $options->get($option_name));
}

// With a string type option.
foreach ( [ 'default_lang', 'previous_version', 'version' ] as $option_name ) {
	assertType('string', $options->get($option_name));
}

// Domains.
assertType('array<non-falsy-string, string>', $options->get('domains'));

// With a list type option.
foreach ( [ 'language_taxonomies', 'post_types', 'sync', 'taxonomies' ] as $option_name ) {
	assertType('array<int, non-falsy-string>', $options->get($option_name));
}

// With the nav menus option.
assertType('array<non-falsy-string, array<non-falsy-string, array<non-falsy-string, int<0, 9223372036854775807>>>>', $options->get('nav_menus'));

// With the first activation option.
assertType('int<0, 9223372036854775807>', $options->get('first_activation'));

// With the force lang option.
assertType('int<0, 3>', $options->get('force_lang'));

//define( 'POLYLANG_PRO', true );

// With a Pro option in PLL Pro.
//assertType('array<non-falsy-string, bool>', $options->get('media'));
