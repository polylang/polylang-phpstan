<?php

/**
 * Dynamic return type for `PLL_Model::get_languages_list()`.
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PLL_Model;
use PHPStan\Reflection\MethodReflection;

class PLLModelGetLanguagesListDynamicMethodReturnTypeExtension extends PLLModelLanguagesGetListDynamicMethodReturnTypeExtension {
	public function getClass(): string {
		return PLL_Model::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return $methodReflection->getName() === 'get_languages_list';
	}
}
