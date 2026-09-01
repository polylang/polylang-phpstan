<?php

namespace WPSyntex\Polylang\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * Reports usage of PLL_Switcher, removed in Polylang 3.9.
 *
 * @implements Rule<Expr>
 */
class DeprecatedPllSwitcherRule implements Rule {
	private const CLASS_NAME = 'PLL_Switcher';

	private const CLASS_MESSAGE = 'Class PLL_Switcher was removed in Polylang 3.9. Use pll_the_languages() or WP_Syntex\Polylang\Switcher\Switcher instead.';

	private const METHOD_MESSAGE = 'Method PLL_Switcher::the_languages() was removed in Polylang 3.9. Use pll_the_languages() instead.';

	public function getNodeType(): string {
		return Expr::class;
	}

	public function processNode( Node $node, Scope $scope ): array {
		if ( ! $node instanceof Expr ) {
			return [];
		}

		if ( $node instanceof New_ ) {
			return $this->processInstantiation( $node );
		}

		if ( $node instanceof MethodCall ) {
			return $this->processMethodCall( $node, $scope );
		}

		return [];
	}

	/**
	 * @return list<RuleError>
	 */
	private function processInstantiation( New_ $node ): array {
		if ( ! $node->class instanceof Name ) {
			return [];
		}

		if ( self::CLASS_NAME !== $node->class->toString() ) {
			return [];
		}

		return [
			RuleErrorBuilder::message( self::CLASS_MESSAGE )
				->line( $node->getStartLine() )
				->identifier( 'polylang.deprecatedSwitcher' )
				->build(),
		];
	}

	/**
	 * @return list<RuleError>
	 */
	private function processMethodCall( MethodCall $node, Scope $scope ): array {
		if ( ! $node->name instanceof Node\Identifier ) {
			return [];
		}

		if ( 'the_languages' !== $node->name->toString() ) {
			return [];
		}

		$calledOnType = $scope->getType( $node->var );

		if ( ! ( new ObjectType( self::CLASS_NAME ) )->isSuperTypeOf( $calledOnType )->yes() ) {
			return [];
		}

		return [
			RuleErrorBuilder::message( self::METHOD_MESSAGE )
				->line( $node->getStartLine() )
				->identifier( 'polylang.deprecatedSwitcherMethod' )
				->build(),
		];
	}
}
