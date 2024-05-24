<?php

namespace WPSyntex\Polylang\PHPStan;

use PHPStan\Reflection\FunctionReflection;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use PHPStan\Type\StringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\TypeCombinator;

class LanguageReturnTypeExtension implements \PHPStan\Type\DynamicFunctionReturnTypeExtension {
	public function isFunctionSupported( FunctionReflection $functionReflection ) : bool {
		return in_array( $functionReflection->getName(), [ 'pll_current_language', 'pll_default_language' ], true );
	}

	public function getTypeFromFunctionCall( FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope ) : Type {
		if ( [] === $functionCall->args || 'OBJECT' !== $functionCall->args[0]->value ) {
			return TypeCombinator::union( new StringType(), new ConstantBooleanType( false ) );
		}

		return TypeCombinator::union( new ObjectType( 'PLL_Language' ), new ConstantBooleanType( false ) );
	}
}
