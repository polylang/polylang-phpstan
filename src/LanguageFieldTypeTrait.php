<?php

/**
 * PLL_Language property types
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PHPStan\Analyser\Scope;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\IntegerRangeType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

trait LanguageFieldTypeTrait {

	use ArgumentsTrait;

	private function getLanguageFieldType( Type $argumentType, Scope $scope ): Type {
		if ( ! $argumentType->isString()->yes() ) {
			// Called with an argument that is not a string: explosion.
			return new NullType();
		}

		/**
		 * When using `foreach`, `$argumentType` is a `UnionType` regrouping several fields that are expected to have
		 * the same return type. So testing the 1st one is enough.
		 */
		$field = $this->getArgumentValue( $argumentType );

		switch ( $field ) {
			case 'name':
			case 'slug':
			case 'locale':
			case 'w3c':
			case 'flag_code':
			case 'host':
			case 'flag_url':
			case 'flag':
			case 'home_url':
			case 'search_url':
				// non-empty-string
				return new AccessoryNonEmptyStringType();

			case 'facebook':
			case 'custom_flag_url':
			case 'custom_flag':
				// string
				return new StringType();

			case 'mo_id':
			case 'page_on_front':
			case 'page_for_posts':
			case 'term_id':
			case 'term_taxonomy_id':
			case 'count':
			case 'tl_term_id':
			case 'tl_term_taxonomy_id':
			case 'tl_count':
				// int<0, max>
				return IntegerRangeType::fromInterval( 0, null );

			case 'term_group':
				// int
				return new IntegerType();

			case 'is_rtl':
				// int<0, 1>
				return IntegerRangeType::fromInterval( 0, 1 );

			case 'active':
				// bool
				return new BooleanType();

			case 'fallbacks':
				// list<non-empty-string>
				AccessoryArrayListType::setListTypeEnabled( true );
				return AccessoryArrayListType::intersectWith( new ArrayType( new IntegerType(), new AccessoryNonEmptyStringType() ) );

			default:
				if ( preg_match( '/^(.{1,32}):(term_id|term_taxonomy_id|count)$/', $field ) ) {
					// int<0, max>
					return IntegerRangeType::fromInterval( 0, null );
				}

				// false
				return new ConstantBooleanType( false );
		}
	}
}
