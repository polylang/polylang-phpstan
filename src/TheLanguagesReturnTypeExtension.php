<?php

namespace WPSyntex\Polylang\PHPStan;

use PHPStan\Reflection\FunctionReflection;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use PHPStan\Type\ArrayType;
use PHPStan\Type\StringType;
use PHPStan\Type\MixedType;

class TheLanguagesReturnTypeExtension implements \PHPStan\Type\DynamicFunctionReturnTypeExtension {
	public function isFunctionSupported( FunctionReflection $functionReflection ) : bool {
		return 'pll_the_languages' === $functionReflection->getName();
	}

	public function getTypeFromFunctionCall( FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope ) : Type {
		$argsCount = count( $functionCall->args );
		if ( $argsCount === 1 && ! empty( $functionCall->args[0]->value['raw'] ) ) {
			return new ArrayType( new StringType(), new MixedType() );
		}

		return new StringType();
	}
}
