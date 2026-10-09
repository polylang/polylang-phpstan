<?php
/**
 * Dynamic return type for `WP_Syntex\Polylang\Options\Options->get()`.
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class OptionsGetDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension {

	public function __construct(
		private OptionTypes $optionTypes,
	) {
	}

	public function getClass(): string {
		return \WP_Syntex\Polylang\Options\Options::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return in_array( $methodReflection->getName(), [ 'get', 'reset', 'offsetGet' ], true );
	}

	public function getTypeFromMethodCall( MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope ): ?Type {
		if ( count( $methodCall->getArgs() ) === 0 ) {
			return null;
		}

		$argumentType = $scope->getType( $methodCall->getArgs()[0]->value );

		// When called with a type that isn't a constant string, return default return type.
		if ( count( $argumentType->getConstantStrings() ) === 0 ) {
			return null;
		}

		// Called with a constant string type.
		$returnType = [];

		foreach ( $argumentType->getConstantStrings() as $constantString ) {
			$returnType[] = $this->optionTypes->getTypeForKeyOrNull( $constantString->getValue() );
		}

		return TypeCombinator::union( ...$returnType );
	}
}
