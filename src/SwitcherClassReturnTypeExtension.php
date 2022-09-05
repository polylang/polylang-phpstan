<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Scalar;
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
		$args = [];
		if ($methodCall instanceof MethodCall && isset($methodCall->getArgs()[1]) ) {
			$args = $methodCall->getArgs()[1]->value;
		}
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
					} elseif ($argPart->value instanceof Array_)  {
						foreach ($argPart->value->items as $arrayItem) {
							$key = $arrayItem->key->value;
							$value = $arrayItem->value->name->parts[0];
							if ($key === 'raw' && $value === 'true') {
								$isRawSwitcher = true;
							}
						}
					} elseif ($argPart instanceof Scalar) {
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
