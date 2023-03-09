<?php

/**
 * Set return type of PLL_Language->get_prop().
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;

class PLLLanguageGetPropDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension {

	use LanguageFieldTypeTrait;

	public function getClass(): string {
		return \PLL_Language::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return $methodReflection->getName() === 'get_prop';
	}

	public function getTypeFromMethodCall( MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope ): Type {
		$args = $methodCall->getArgs();

		if ( count( $args ) === 0 ) {
			// Called without arguments: explosion.
			return new NullType();
		}

		$argumentType = $scope->getType( $args[0]->value );

		return $this->getLanguageFieldType( $argumentType, $scope );
	}
}
