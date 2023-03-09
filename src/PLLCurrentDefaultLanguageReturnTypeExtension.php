<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class PLLCurrentDefaultLanguageReturnTypeExtension implements \PHPStan\Type\DynamicFunctionReturnTypeExtension {

	use LanguageFieldTypeTrait;

	public function isFunctionSupported( FunctionReflection $functionReflection ) : bool {
		return in_array( $functionReflection->getName(), array( 'pll_current_language', 'pll_default_language' ), true );
	}

	public function getTypeFromFunctionCall( FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope ): Type {
		$argumentType = $this->getArgumentTypeOrDefault( 0, $functionReflection, $functionCall, $scope );

		if ( \OBJECT === $this->getArgumentValue( $argumentType ) ) {
			// Called with `OBJECT`.
			return TypeCombinator::union( new ObjectType( 'PLL_Language' ), new ConstantBooleanType( false ) );
		}

		return $this->getLanguageFieldType( $argumentType, $scope );
	}
}
