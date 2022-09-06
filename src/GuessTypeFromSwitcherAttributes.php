<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\CallLike;
use PHPStan\Analyser\Scope;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeCombinator;

trait GuessTypeFromSwitcherAttributes {
	private function guessType(CallLike $call, Scope $scope) : Type
	{
		$args = [];

		if (isset($call->getArgs()[1]) ) {
			$args = $call->getArgs()[1]->value;
		}

		if (empty($args)) {
			// No arguments provided to the switcher, default type 'string'.
			return new StringType();
		}

		$isRaw = TrinaryLogic::createMaybe();

		if ($args instanceof Expr) {
			$argsType   = $scope->getType($args);
			$argsKeys   = [];
			$argsValues = [];

			if ($argsType instanceof ArrayType) {
				$argsKeys   = $argsType->getKeysArray();
				$argsValues = $argsType->getValuesArray();
			}

			if ($argsKeys instanceof ConstantArrayType) {
				foreach ($argsKeys->getValueTypes() as $index => $key) {
					if ($key->getValue() !== 'raw') {
						// Current argument is not 'raw' parameter.
						continue;
					}
					if ($argsValues->getValueTypes()[$index]->getValue()) {
						// Current argument set 'raw' to 'true'.
						$isRaw = TrinaryLogic::createYes();
						break;
					}
				}
				// If none 'raw' parameter set to 'true' is found, consider it's 'false'.
				$isRaw = $isRaw->yes() ? $isRaw : TrinaryLogic::createNo();
			}
		}

		if ($isRaw->maybe()) {
			// Can't guess type precisely, return 'array<string, mixed>|string'.
			return TypeCombinator::union(new ArrayType(new StringType(), new MixedType()), new StringType());
		}

		if ($isRaw->yes()) {
			// Switcher output is raw, return 'array<string, mixed>'.
			return new ArrayType(new StringType(), new MixedType());
		}

		// Raw is considered false, return 'string'.
		return new StringType();
	}
}
