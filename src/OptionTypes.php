<?php
/**
 * PHPStan types for Polylang Options keys.
 *
 * Mirrors polylang/src/Options/Registry.php (and Pro registry when Pro mode is enabled).
 */

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

use PHPStan\Type\Accessory\AccessoryNonFalsyStringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerRangeType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

class OptionTypes {

	private bool $proEnabled;

	public function __construct( bool $proEnabled = false ) {
		$this->proEnabled = $proEnabled;
	}

	private function isProEnabled(): bool {
		return $this->proEnabled || ( defined( 'POLYLANG_PRO_PHPSTAN' ) && POLYLANG_PRO_PHPSTAN );
	}

	/**
	 * Returns the PHPStan type for a known option key, or null for unknown keys.
	 */
	public function getTypeForKey( string $key ): ?Type {
		switch ( $key ) {
			case 'browser':
			case 'hide_default':
			case 'media_support':
			case 'redirect_lang':
			case 'rewrite':
				return new BooleanType();

			case 'default_lang':
			case 'previous_version':
			case 'version':
				return new StringType();

			case 'domains':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new StringType()
				);

			case 'language_taxonomies':
			case 'post_types':
			case 'sync':
			case 'taxonomies':
				return new ArrayType(
					new IntegerType(),
					$this->getNonFalsyStringType()
				);

			case 'nav_menus':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new ArrayType(
						$this->getNonFalsyStringType(),
						new ArrayType(
							$this->getNonFalsyStringType(),
							IntegerRangeType::fromInterval( 0, \PHP_INT_MAX )
						)
					)
				);

			case 'first_activation':
				return IntegerRangeType::fromInterval( 0, \PHP_INT_MAX );

			case 'force_lang':
				return IntegerRangeType::fromInterval( 0, 3 );

			default:
				return $this->getProTypeForKey( $key );
		}
	}

	/**
	 * Returns the PHPStan type for an option key (known keys or null for unknown).
	 */
	public function getTypeForKeyOrNull( string $key ): Type {
		return $this->getTypeForKey( $key ) ?? new NullType();
	}

	private function getProTypeForKey( string $key ): ?Type {
		if ( ! $this->isProEnabled() ) {
			return null;
		}

		switch ( $key ) {
			case 'media':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new BooleanType()
				);

			case 'machine_translation_enabled':
				return new BooleanType();

			case 'machine_translation_service':
				return $this->getNonFalsyStringType();

			case 'machine_translation_services':
				return new ArrayType(
					$this->getNonFalsyStringType(),
					new ArrayType(
						$this->getNonFalsyStringType(),
						new StringType()
					)
				);

			default:
				return null;
		}
	}

	private function getNonFalsyStringType(): Type {
		return new IntersectionType(
			[
				new StringType(),
				new AccessoryNonFalsyStringType(),
			]
		);
	}
}
