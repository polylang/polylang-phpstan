<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\ArrayItem;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;

class SwitcherClassReturnTypeExtension implements DynamicMethodReturnTypeExtension {
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
		if (count($methodCall->getArgs()) === 0) {
			return ParametersAcceptorSelector::selectFromArgs(
				$scope,
				$methodCall->getArgs(),
				$methodReflection->getVariants()
			)->getReturnType();
		}
		$args = $methodCall->getArgs()[1]->value;
		$isRawSwitcher = false;
		foreach ($args as $arg) {
			if (is_array($arg)) {
				foreach ($arg as $argPart) {
					if ($argPart instanceof ArrayItem) {
						$key = $argPart->key->value;
						$value = $argPart->value->name->parts[0];
						if ($key === 'raw' && $value === 'true') {
							$isRawSwitcher = true;
						}
					} else {
						$argvalue = $argPart->value;
						$items = $argvalue->items;
						if (! empty($items) && is_array($items)) {
							foreach ($items as $item) {
								$key = $item->key->value;
								$value = $item->value->name->parts[0];
								if ($key === 'raw' && $value === 'true') {
									$isRawSwitcher = true;
									break;
								}
							}
						}
					}
				}
			}
		}
		if($isRawSwitcher) {
			return new ArrayType(new StringType(), new MixedType());
		}

		return new StringType();
	}
}
