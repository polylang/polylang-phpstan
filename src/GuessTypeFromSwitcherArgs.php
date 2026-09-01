<?php

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Arg;
use PHPStan\Analyser\Scope;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\VoidType;

/**
 * Infers pll_the_languages() return types from switcher arguments.
 *
 * Mirrors the runtime branches in polylang/src/api.php:
 * raw truthy → array, echo false → string, otherwise → void.
 */
trait GuessTypeFromSwitcherArgs {
	private function guessPllTheLanguagesReturnType( Arg $arg, Scope $scope ): Type {
		$isRaw = $this->guessBooleanArg( $arg, $scope, 'raw', TrinaryLogic::createNo() );

		if ( $isRaw->yes() ) {
			return $this->getRawReturnType();
		}

		if ( $isRaw->maybe() ) {
			return TypeCombinator::union(
				$this->getRawReturnType(),
				$this->guessNonRawReturnType( $arg, $scope )
			);
		}

		return $this->guessNonRawReturnType( $arg, $scope );
	}

	private function guessNonRawReturnType( Arg $arg, Scope $scope ): Type {
		// `echo` defaults to true: the markup is printed and nothing is returned.
		$isEcho = $this->guessBooleanArg( $arg, $scope, 'echo', TrinaryLogic::createYes() );

		if ( $isEcho->yes() ) {
			return new VoidType();
		}

		if ( $isEcho->no() ) {
			return new StringType();
		}

		return TypeCombinator::union( new VoidType(), new StringType() );
	}

	private function getRawReturnType(): Type {
		return new ArrayType( new StringType(), new MixedType() );
	}

	private function guessBooleanArg( Arg $arg, Scope $scope, string $key, TrinaryLogic $whenAbsent ): TrinaryLogic {
		$argsType = $scope->getType( $arg->value );
		$keyType  = new ConstantStringType( $key );

		$hasKey = $argsType->hasOffsetValueType( $keyType );

		if ( $hasKey->yes() ) {
			return $argsType->getOffsetValueType( $keyType )->toBoolean()->isTrue();
		}

		if ( $hasKey->no() ) {
			return $whenAbsent;
		}

		return $this->guessBooleanArgFromConstantArrays( $argsType, $key );
	}

	private function guessBooleanArgFromConstantArrays( Type $argsType, string $key ): TrinaryLogic {
		$constantArrays = $argsType->getConstantArrays();

		if ( [] === $constantArrays ) {
			return TrinaryLogic::createMaybe();
		}

		$knownResult = null;

		foreach ( $constantArrays as $constantArray ) {
			$result = $this->guessBooleanArgFromConstantArray( $constantArray, $key );

			if ( $result->maybe() ) {
				continue;
			}

			if ( null === $knownResult ) {
				$knownResult = $result;
				continue;
			}

			if ( $knownResult->yes() !== $result->yes() ) {
				return TrinaryLogic::createMaybe();
			}
		}

		return $knownResult ?? TrinaryLogic::createMaybe();
	}

	private function guessBooleanArgFromConstantArray( ConstantArrayType $constantArray, string $key ): TrinaryLogic {
		foreach ( $constantArray->getKeyTypes() as $index => $argKey ) {
			if ( ! $this->isConstantStringKey( $argKey, $key ) ) {
				continue;
			}

			return $constantArray->getValueTypes()[ $index ]->toBoolean()->isTrue();
		}

		return TrinaryLogic::createMaybe();
	}

	private function isConstantStringKey( Type $keyType, string $key ): bool {
		foreach ( $keyType->getConstantStrings() as $constantString ) {
			if ( $constantString->getValue() === $key ) {
				return true;
			}
		}

		return false;
	}
}
