<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \WP_Syntex\Polylang\Options\Options */
$options = $options;

$postTypes = $options['post_types'];
assertType('array<int, non-falsy-string>', $postTypes);

$combined = array_combine( $postTypes, $postTypes );
assertType('array<non-falsy-string, non-falsy-string>', $combined);

foreach ( $options['domains'] as $lang => $domain ) {
	assertType('non-falsy-string', $lang);
	assertType('string', $domain);
}
