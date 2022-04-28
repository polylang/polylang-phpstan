<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

class DynamicReturnTypeExtensionTest extends \PHPStan\Testing\TypeInferenceTestCase
{
	/**
	 * @return iterable<mixed>
	 */
	public function dataFileAsserts(): iterable
	{
		// Path to a file with actual asserts of expected types:
		yield from $this->gatherAssertTypes(__DIR__ . '/data/the_languages.php');
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param array<string> ...$args
	 */
	public function testFileAsserts(string $assertType, string $file, ...$args): void
	{
		$this->assertFileAsserts($assertType, $file, ...$args);
	}

	public static function getAdditionalConfigFiles(): array
	{
		// path to your project's phpstan.neon, or extension.neon in case of custom extension packages
		return [__DIR__ . '/../extension.neon'];
	}
}