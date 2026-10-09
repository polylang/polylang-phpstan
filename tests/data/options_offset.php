<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \WP_Syntex\Polylang\Options\Options */
$options = $options;

// With an unknown option.
assertType('null', $options['foo']);

// With a Pro option in PLL Free.
assertType('null', $options['media']);

// With a boolean type option.
foreach ( [ 'browser', 'hide_default', 'media_support', 'redirect_lang', 'rewrite' ] as $option_name ) {
	assertType('bool', $options[$option_name]);
}

// With a string type option.
foreach ( [ 'default_lang', 'previous_version', 'version' ] as $option_name ) {
	assertType('string', $options[$option_name]);
}

// Domains.
assertType('array<non-falsy-string, string>', $options['domains']);

// With a list type option.
foreach ( [ 'language_taxonomies', 'post_types', 'sync', 'taxonomies' ] as $option_name ) {
	assertType('array<int, non-falsy-string>', $options[$option_name]);
}

// With the nav menus option.
assertType('array<non-falsy-string, array<non-falsy-string, array<non-falsy-string, int<0, 9223372036854775807>>>>', $options['nav_menus']);

// With the first activation option.
assertType('int<0, 9223372036854775807>', $options['first_activation']);

// With the force lang option.
assertType('int<0, 3>', $options['force_lang']);

// Bracket and method access parity.
assertType('string', $options['version']);
assertType('string', $options->get('version'));
