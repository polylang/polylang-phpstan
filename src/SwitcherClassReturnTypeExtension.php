<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeCombinator;

class SwitcherClassReturnTypeExtension implements DynamicMethodReturnTypeExtension {
	public function getClass(): string
	{
		return \PLL_Switcher::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'the_languages';
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
	{
		if (count($methodCall->getArgs()) === 0) {
			return ParametersAcceptorSelector::selectFromArgs(
				$scope,
				$methodCall->getArgs(),
				$methodReflection->getVariants()
			)->getReturnType();
		}
		$args = [];
		if (isset($methodCall->getArgs()[1]) ) {
			$args = $methodCall->getArgs()[1]->value;
		}
		$isRaw      = TrinaryLogic::createMaybe();
		if ($args instanceof Expr) {
			$argsKeys   = $scope->getType($args)->getKeysArray();
			$argsValues = $scope->getType($args)->getValuesArray();
			if ($argsKeys instanceof ConstantArrayType) {
				foreach ($argsKeys->getValueTypes() as $index => $key) {
					if ($key->getValue() !== 'raw') {
						continue;
					}
					if ($argsValues->getValueTypes()[$index]->getValue()) {
						$isRaw = TrinaryLogic::createYes();
						break;
					}
				}
				$isRaw = $isRaw->yes() ? $isRaw : TrinaryLogic::createNo();
			}
		}
		if (empty($args)) {
			$isRaw = TrinaryLogic::createNo();
		}

		if ($isRaw->maybe()) {
			return TypeCombinator::union(new ArrayType(new StringType(), new MixedType()), new StringType());
		}
		if ($isRaw->yes()) {
			return new ArrayType(new StringType(), new MixedType());
		}
		if ($isRaw->no()) {
			return new StringType();
		}

	}

	private function isRawAttribute(ArrayItem $arrayItem): TrinaryLogic
	{
		$key = $arrayItem->key->value;
		$value = $arrayItem->value->name->parts[0];
		if ($key !== 'raw') {
			return TrinaryLogic::createMaybe();
		}
		if ($value === 'true') {
			return TrinaryLogic::createYes();
		}
		if ($value === 'false') {
			return TrinaryLogic::createNo();
		}
	}
}
