<?php

/**
 * Set return type of Model\Languages->get_list().
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use WP_Syntex\Polylang\Model\Languages;
use PHPStan\Reflection\MethodReflection;

class PLLModelLanguagesGetListDynamicMethodReturnTypeExtension extends PLLModelGetLanguagesListDynamicMethodReturnTypeExtension {
	public function getClass(): string {
		return Languages::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return $methodReflection->getName() === 'get_list';
	}
}
