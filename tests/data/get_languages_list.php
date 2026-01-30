<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \PLL_Model */
$model = $model;

/** @var array */
$array = $array;

$args = ['fields' => 'slug'];

// Without any argument.
assertType('array<int, PLL_Language>', $model->get_languages_list());

// With empty array.
assertType('array<int, PLL_Language>', $model->get_languages_list([]));

// With 'fields' key set explicitly.
assertType('array<int, string>', $model->get_languages_list(['fields' => 'slug']));

// With 'fields' key set in a variable.
assertType('array<int, string>', $model->get_languages_list($args));

// With a variable containing unknown data.
assertType('array<int, mixed>', $model->get_languages_list($array));

// With array_merge() result passed as parameter.
assertType('array<int, string>', $model->get_languages_list(array_merge($array, ['fields' => 'slug'])));

// With 'fields' key set on top of variable containing unknown data.
$array['fields'] = 'slug';
assertType('array<int, string>', $model->get_languages_list($array));
