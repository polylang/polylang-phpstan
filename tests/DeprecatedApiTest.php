<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use PHPStan\Rules\Rule;
use WPSyntex\Polylang\PHPStan\Rules\DeprecatedPllSwitcherRule;

class DeprecatedApiTest extends \PHPStan\Testing\RuleTestCase {
	protected function getRule(): Rule {
		return new DeprecatedPllSwitcherRule();
	}

	public function testPllSwitcherUsageIsReportedAsDeprecated(): void {
		$this->analyse(
			[ __DIR__ . '/data/deprecated_pll_switcher.php' ],
			[
				[
					'Class PLL_Switcher was removed in Polylang 3.9. Use pll_the_languages() or WP_Syntex\Polylang\Switcher\Switcher instead.',
					11,
				],
				[
					'Method PLL_Switcher::the_languages() was removed in Polylang 3.9. Use pll_the_languages() instead.',
					13,
				],
			]
		);
	}

	public static function getAdditionalConfigFiles(): array {
		return [
			__DIR__ . '/phpstan.neon',
			__DIR__ . '/test-extension.neon',
		];
	}
}
