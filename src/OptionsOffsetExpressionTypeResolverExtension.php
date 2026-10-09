<?php
/**
 * Dynamic return type for `WP_Syntex\Polylang\Options\Options['key']`.
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ExpressionTypeResolverExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use WP_Syntex\Polylang\Options\Options;

class OptionsOffsetExpressionTypeResolverExtension implements ExpressionTypeResolverExtension {

	public function __construct(
		private OptionTypes $optionTypes,
	) {
	}

	public function getType( Expr $expr, Scope $scope ): ?Type {
		if ( ! $expr instanceof ArrayDimFetch || $expr->dim === null ) {
			return null;
		}

		$varType = $scope->getType( $expr->var );
		if ( ! ( new ObjectType( Options::class ) )->isSuperTypeOf( $varType )->yes() ) {
			return null;
		}

		$dimType = $scope->getType( $expr->dim );
		if ( count( $dimType->getConstantStrings() ) === 0 ) {
			return null;
		}

		$returnTypes = [];

		foreach ( $dimType->getConstantStrings() as $constantString ) {
			$returnTypes[] = $this->optionTypes->getTypeForKeyOrNull( $constantString->getValue() );
		}

		return TypeCombinator::union( ...$returnTypes );
	}
}
