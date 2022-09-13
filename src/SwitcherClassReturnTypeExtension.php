<?php

namespace WPSyntex\Polylang\PHPStan;

use WPSyntex\Polylang\PHPStan\GuessTypeFromSwitcherAttributes;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;

class SwitcherClassReturnTypeExtension implements DynamicMethodReturnTypeExtension {
	use GuessTypeFromSwitcherAttributes;

	public function getClass(): string
	{
		return \PLL_Switcher::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'the_languages';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$args = $methodCall->getArgs();

		if (count($args) === 0) {
			return ParametersAcceptorSelector::selectFromArgs(
				$scope,
				$methodCall->getArgs(),
				$methodReflection->getVariants()
			)->getReturnType();
		}

		if(isset($args[1])) {
			return $this->guessType($args[1], $scope);
		}

		// No attributes provided to the switcher, default type 'string'.
		return new StringType();
	}
}
