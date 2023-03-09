<?php

/**
 * Tools for argument types
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\CallLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;

trait ArgumentsTrait {

	/**
	 * Returns the `Type` object related to the argument at index `$argIndex`.
	 * Fallback to the default value if no argument is passed to the method/function.
	 * Doesn't work for named parameters.
	 *
	 * @see https://github.com/phpstan/phpstan/issues/3016
	 *
	 * @param int                                 $argIndex
	 * @param MethodReflection|FunctionReflection $functionReflection
	 * @param CallLike                            $functionCall
	 * @param Scope                               $scope
	 * @return Type
	 */
	private function getArgumentTypeOrDefault( int $argIndex, $functionReflection, CallLike $functionCall, Scope $scope ): Type {
		$args = $functionCall->getArgs();

		if ( isset( $args[ $argIndex ] ) ) {
			return $scope->getType( $args[ $argIndex ]->value );
		}

		// Called without arguments.
		$args = ParametersAcceptorSelector::selectSingle( $functionReflection->getVariants() )->getParameters();

		if ( isset( $args[ $argIndex ] ) ) {
			return $args[ $argIndex ]->getDefaultValue();
		}

		// No default value.
		return new NullType();
	}

	/**
	 * Returns the value of an argument.
	 *
	 * @param Type $argumentType
	 * @return mixed
	 */
	private function getArgumentValue( Type $argumentType ) {
		if ( $argumentType instanceof ArrayType ) {
			// ArrayType.
			$argumentKeys = $argumentType->getKeysArray();

			if ( ! $argumentKeys instanceof ConstantArrayType ) {
				return [];
			}

			$array              = [];
			$argumentValueTypes = $argumentType->getValuesArray()->getValueTypes();

			foreach ( $argumentKeys->getValueTypes() as $index => $key ) {
				$array[ $key->getValue() ] = $this->getArgumentValue( $argumentValueTypes[ $index ] );
			}

			return $array;
		}

		if ( method_exists( $argumentType, 'getValue' ) ) {
			// Any other Constant*Type.
			return $argumentType->getValue();
		}

		// UnionType.
		return $argumentType->getTypes()[0]->getValue();
	}
}
