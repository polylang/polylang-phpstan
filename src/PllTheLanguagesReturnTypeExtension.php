<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;

class PllTheLanguagesReturnTypeExtension implements DynamicFunctionReturnTypeExtension {
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
		$args = [];
		if (isset($funcCall->getArgs()[1]) ) {
			$args = $funcCall->getArgs()[1]->value;
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
