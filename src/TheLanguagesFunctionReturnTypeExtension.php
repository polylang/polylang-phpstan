<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;

class TheLanguagesFunctionReturnTypeExtension implements DynamicFunctionReturnTypeExtension {
	use GuessTypeFromSwitcherArgs;

	public function isFunctionSupported( FunctionReflection $functionReflection ): bool {
		return 'pll_the_languages' === $functionReflection->getName();
	}

	public function getTypeFromFunctionCall( FunctionReflection $functionReflection, FuncCall $funcCall, Scope $scope ): Type {
		$args = $funcCall->getArgs();

		if ( 0 === count( $args ) ) {
			return new VoidType();
		}

		return $this->guessPllTheLanguagesReturnType( reset( $args ), $scope );
	}
}
