<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \WP_Syntex\Polylang\Options\Options */
$options = $options;

assertType('array<non-falsy-string, bool>', $options['media']);
assertType('bool', $options['machine_translation_enabled']);
assertType('array<non-falsy-string, array<non-falsy-string, string>>', $options['machine_translation_services']);
