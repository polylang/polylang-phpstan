<?php

namespace WPSyntex\Polylang\PHPStan;

use WPSyntex\Polylang\PHPStan\GuessTypeFromSwitcherAttributes;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;

class TheLanguagesFunctionReturnTypeExtension implements DynamicFunctionReturnTypeExtension {
	use GuessTypeFromSwitcherAttributes;

	public function isFunctionSupported(FunctionReflection $functionReflection): bool
	{
		return $functionReflection->getName() === 'pll_the_languages';
	}

	public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $funcCall, Scope $scope): Type
	{
		$args = $funcCall->getArgs();

		if (count($args) === 0) {
			// No attributes provided to the switcher, default type 'string'.
			return new StringType();
		}

		$switcherAttributes = reset($args);

		return $this->guessType($switcherAttributes, $scope);
	}
}
