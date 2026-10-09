<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

class OptionsReturnTypeExtensionTest extends \PHPStan\Testing\TypeInferenceTestCase {
	/**
	 * @return iterable<mixed>
	 */
	public function dataFileAsserts(): iterable {
		yield from $this->gatherAssertTypes( __DIR__ . '/data/options_get.php' );
		yield from $this->gatherAssertTypes( __DIR__ . '/data/options_offset.php' );
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param array<string> ...$args
	 */
	public function testFileAsserts( string $assertType, string $file, ...$args ): void {
		$this->assertFileAsserts( $assertType, $file, ...$args );
	}

	public static function getAdditionalConfigFiles(): array {
		return [
			__DIR__ . '/options-phpstan.neon',
		];
	}
}
