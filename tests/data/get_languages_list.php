<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \PLL_Model */
$model = $model;

/** @var array */
$array = $array;

$args = ['fields' => 'slug'];

assertType('array<int, string>', $model->get_languages_list(['fields' => 'slug']));
assertType('array<int, string>', $model->get_languages_list($args));
assertType('array<int, mixed>', $model->get_languages_list($array));
assertType('array<int, string>', $model->get_languages_list(array_merge($array, ['fields' => 'slug'])));