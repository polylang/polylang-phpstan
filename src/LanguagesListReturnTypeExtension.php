<?php

namespace WPSyntex\Polylang\PHPStan;

use PHPStan\Reflection\FunctionReflection;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\ObjectType;

class LanguagesListReturnTypeExtension implements \PHPStan\Type\DynamicFunctionReturnTypeExtension {
	public function isFunctionSupported( FunctionReflection $functionReflection ) : bool {
		return 'pll_languages_list' === $functionReflection->getName();
	}

	public function getTypeFromFunctionCall( FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope ) : Type {
		if ( is_array( $functionCall->args[0]->value ) && isset( $functionCall->args[0]->value['fields'] ) && empty( $functionCall->args[0]->value['fields'] ) ) {
			return new ArrayType( new IntegerType(), new ObjectType( 'PLL_Language' ) );
		}

		return new ArrayType( new IntegerType(), new StringType() );
	}
}
