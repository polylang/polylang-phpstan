<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use PHPStan\Type\NullType;
use WPSyntex\Polylang\PHPStan\OptionTypes;

class OptionTypesRegistrySyncTest extends \PHPUnit\Framework\TestCase {
	/**
	 * Keys from polylang/src/Options/Registry::OPTIONS (via Business\*::key()).
	 *
	 * @var list<string>
	 */
	private const FREE_REGISTRY_KEYS = [
		'force_lang',
		'domains',
		'hide_default',
		'rewrite',
		'redirect_lang',
		'browser',
		'media_support',
		'post_types',
		'taxonomies',
		'sync',
		'default_lang',
		'nav_menus',
		'first_activation',
		'previous_version',
		'version',
	];

	/**
	 * Keys from polylang-pro/src/Options/Registry::OPTIONS (via Business\*::key()).
	 *
	 * @var list<string>
	 */
	private const PRO_REGISTRY_KEYS = [
		'media',
		'machine_translation_enabled',
		'machine_translation_service',
		'machine_translation_services',
	];

	/**
	 * Legacy DB option kept for backward compatibility; not in Registry::OPTIONS.
	 *
	 * @var list<string>
	 */
	private const LEGACY_KEYS = [
		'language_taxonomies',
	];

	public function testFreeRegistryKeysAreTyped(): void {
		$optionTypes = new OptionTypes( false );

		foreach ( self::FREE_REGISTRY_KEYS as $key ) {
			$this->assertNotNull( $optionTypes->getTypeForKey( $key ), "Missing type for free registry key '{$key}'." );
		}
	}

	public function testLegacyKeysAreTyped(): void {
		$optionTypes = new OptionTypes( false );

		foreach ( self::LEGACY_KEYS as $key ) {
			$this->assertNotNull( $optionTypes->getTypeForKey( $key ), "Missing type for legacy key '{$key}'." );
		}
	}

	public function testProRegistryKeysAreTypedOnlyWhenProEnabled(): void {
		$proOptionTypes = new OptionTypes( true );

		foreach ( self::PRO_REGISTRY_KEYS as $key ) {
			$this->assertNotNull( $proOptionTypes->getTypeForKey( $key ), "Missing type for pro registry key '{$key}'." );
		}

		if ( defined( 'POLYLANG_PRO_PHPSTAN' ) && POLYLANG_PRO_PHPSTAN ) {
			$this->markTestSkipped( 'POLYLANG_PRO_PHPSTAN is already defined in this PHP process.' );
		}

		$freeOptionTypes = new OptionTypes( false );

		foreach ( self::PRO_REGISTRY_KEYS as $key ) {
			$this->assertNull( $freeOptionTypes->getTypeForKey( $key ), "Pro key '{$key}' should be null in free mode." );
		}
	}

	public function testUnknownKeysInferNull(): void {
		$optionTypes = new OptionTypes( true );

		$this->assertInstanceOf( NullType::class, $optionTypes->getTypeForKeyOrNull( 'unknown_option_key' ) );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testPolylangProPhpstanConstantEnablesProKeys(): void {
		require __DIR__ . '/bootstrap-pro-phpstan.php';

		$optionTypes = new OptionTypes( false );

		foreach ( self::PRO_REGISTRY_KEYS as $key ) {
			$this->assertNotNull( $optionTypes->getTypeForKey( $key ), "POLYLANG_PRO_PHPSTAN should enable type for '{$key}'." );
		}
	}
}
