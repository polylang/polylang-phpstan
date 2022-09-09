<?php

namespace WPSyntex\Polylang\PHPStan;

use WPSyntex\Polylang\PHPStan\GuessTypeFromSwitcherAttributes;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;

class TheLanguagesFunctionReturnTypeExtension implements DynamicFunctionReturnTypeExtension {
	use GuessTypeFromSwitcherAttributes;

	public function isFunctionSupported(FunctionReflection $functionReflection): bool
	{
		return $functionReflection->getName() === 'pll_the_languages';
	}

	public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $funcCall, Scope $scope): Type
	{
		if (count($funcCall->getArgs()) === 0) {
			return ParametersAcceptorSelector::selectFromArgs(
				$scope,
				$funcCall->getArgs(),
				$functionReflection->getVariants()
			)->getReturnType();
		}

		return $this->guessType($funcCall, $scope);
	}
}
