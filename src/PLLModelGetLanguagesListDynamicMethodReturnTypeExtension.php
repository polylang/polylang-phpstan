<?php

/**
 * Set return type of PLL_Model->get_languages_list().
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PLL_Language;
use PLL_Model;

class PLLModelGetLanguagesListDynamicMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension {
	public function getClass(): string {
		return PLL_Model::class;
	}

	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return $methodReflection->getName() === 'get_languages_list';
	}

	public function getTypeFromMethodCall( MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope ): Type {
		$args = $methodCall->getArgs();

		// Called without arguments.
		if ( count( $args ) === 0 ) {
			return new ArrayType( new IntegerType(), new ObjectType( PLL_Language::class ) );
		}

		$argumentType = $scope->getType( $args[0]->value );

		// Called with an argument that is not an array.
		if ( ! $argumentType instanceof ConstantArrayType ) {
			return new ArrayType( new IntegerType(), new ObjectType( PLL_Language::class ) );
		}

		foreach ( $argumentType->getKeyTypes() as $index => $key ) {
			if ( ! $key instanceof ConstantStringType || $key->getValue() !== 'fields' ) {
				continue;
			}

			$fieldsType = $argumentType->getValueTypes()[ $index ];

			if ( ! $fieldsType instanceof ConstantStringType ) {
				return new ArrayType( new IntegerType(), new ObjectType( PLL_Language::class ) );
			}

			$fields = $fieldsType->getValue();
			break;
		}

		// Without 'fields' argument, or empty value.
		if ( empty( $fields ) ) {
			return new ArrayType( new IntegerType(), new ObjectType( PLL_Language::class ) );
		}

		switch ( $fields ) {
			case 'term_id':
			case 'term_group':
			case 'term_taxonomy_id':
			case 'count':
			case 'tl_term_id':
			case 'tl_term_taxonomy_id':
			case 'tl_count':
			case 'is_rtl':
			case 'mo_id':
				return new ArrayType( new IntegerType(), new IntegerType() );
			case 'name':
			case 'slug':
			case 'locale':
			case 'w3c':
			case 'flag_code':
				return new ArrayType( new IntegerType(), new StringType() );
			case 'page_on_front':
			case 'page_for_posts':
				return new ArrayType( new IntegerType(), new UnionType( [ new IntegerType(), new NullType() ] ) );
			case 'facebook':
			case 'home_ur':
			case 'search_url':
			case 'host':
			case 'flag_url':
			case 'flag':
			case 'custom_flag_url':
			case 'custom_flag':
				return new ArrayType( new IntegerType(), new UnionType( [ new StringType(), new NullType() ] ) );
			default:
				// Everything exploded.
				return new NullType();
		}
	}
}
