<?php
/**
 * Dynamic return type for `WP_Syntex\Polylang\Options\Options->get()`.
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\Accessory\AccessoryNonFalsyStringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerRangeType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class OptionsGetDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension {
	public function getClass(): string {
		return \WP_Syntex\Polylang\Options\Options::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return in_array( $methodReflection->getName(), [ 'get', 'reset', 'offsetGet' ], true );
	}

	public function getTypeFromMethodCall( MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope ): ?Type {
		if ( count( $methodCall->getArgs() ) === 0 ) {
			return null;
		}

		$argumentType = $scope->getType( $methodCall->getArgs()[0]->value );

		// When called with a type that isn't a constant string, return default return type.
		if ( count( $argumentType->getConstantStrings() ) === 0 ) {
			return null;
		}

		// Called with a constant string type.
		$returnType = [];

		foreach ( $argumentType->getConstantStrings() as $constantString ) {
			switch ( $constantString->getValue() ) {
				case 'browser':
				case 'hide_default':
				case 'media_support':
				case 'redirect_lang':
				case 'rewrite':
					$returnType[] = new BooleanType();
					break;

				case 'default_lang':
				case 'previous_version':
				case 'version':
					$returnType[] = new StringType();
					break;

				case 'domains':
					$returnType[] = new ArrayType(
						$this->getNonFalsyStringType(),
						new StringType()
					);
					break;

				case 'language_taxonomies':
				case 'post_types':
				case 'sync':
				case 'taxonomies':
					$returnType[] = AccessoryArrayListType::intersectWith(
						new ArrayType(
							new IntegerType(),
							$this->getNonFalsyStringType()
						)
					);
					break;

				case 'nav_menus':
					$returnType[] = new ArrayType(
						$this->getNonFalsyStringType(),
						new ArrayType(
							$this->getNonFalsyStringType(),
							new ArrayType(
								$this->getNonFalsyStringType(),
								IntegerRangeType::fromInterval( 0, \PHP_INT_MAX )
							)
						)
					);
					break;

				case 'first_activation':
					$returnType[] = IntegerRangeType::fromInterval( 0, \PHP_INT_MAX );
					break;

				case 'force_lang':
					$returnType[] = IntegerRangeType::fromInterval( 0, 3 );
					break;

				default:
					$returnType[] = $this->getDefaultReturnType( $constantString->getValue() );
			}
		}

		return TypeCombinator::union( ...$returnType );
	}

	protected function getNonFalsyStringType(): Type {
		return new IntersectionType(
			[
				new StringType(),
				new AccessoryNonFalsyStringType(),
			]
		);
	}

	/**
	 * Returns the type to return (!) when the option name is unknown.
	 * Currently handles Polylang Pro's options.
	 * Can be overwritten to handle more option names.
	 *
	 * @param string $option_name Option name.
	 * @return Type
	 */
	protected function getDefaultReturnType( string $option_name ): Type {
		if ( ! defined( 'POLYLANG_PRO_PHPSTAN' ) || ! POLYLANG_PRO_PHPSTAN ) { // Constant specific to PHPStan in Polylang Pro.
			return new NullType();
		}

		switch ( $option_name ) {
			case 'media':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new BooleanType()
				);

			case 'machine_translation_enabled':
				return new BooleanType();

			case 'machine_translation_services':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new ArrayType(
						$this->getNonFalsyStringType(),
						new StringType()
					)
				);

			default:
				return new NullType();
		}
	}
}
