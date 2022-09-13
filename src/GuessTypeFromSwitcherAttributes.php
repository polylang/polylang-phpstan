<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Arg;
use PHPStan\Analyser\Scope;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeCombinator;

trait GuessTypeFromSwitcherAttributes {
	private function guessType(Arg $args, Scope $scope) : Type
	{
		$args = $args->value;

		$isRaw = TrinaryLogic::createMaybe();

		if ($args instanceof Expr) {
			$argsType   = $scope->getType($args);
			$argsKeys   = [];
			$argsValues = [];

			if ($argsType instanceof ArrayType) {
				$argsKeys   = $argsType->getKeysArray();
				$argsValues = $argsType->getValuesArray();
			}

			if ($argsType instanceof IntersectionType && $argsType->isIterable()) {
				// Let's look into each types to see if it contains 'raw' key.
				foreach($argsType->getTypes() as $type) {
					if ($type->hasOffsetValueType(new ConstantStringType('raw'))->yes()) {
						$isRaw = $type->getOffsetValueType(new ConstantStringType('raw'))->getValue() ? TrinaryLogic::createYes() : TrinaryLogic::createNo();
					}
				}
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
