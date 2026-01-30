<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan;

error_reporting(E_ALL);

// extension.neon contains relative links.
$helperDirectory = dirname(__DIR__) . '/vendor/wpsyntex/polylang-phpstan';
if (! is_dir($helperDirectory)) {
	mkdir($helperDirectory, 0777, true);
}

// Copy package to a fake vendor directory.
copy(dirname(__DIR__) . '/extension.neon', $helperDirectory . '/extension.neon');
copy(dirname(__DIR__) . '/test-extension.neon', $helperDirectory . '/test-extension.neon');
copy(dirname(__DIR__) . '/bootstrap.php', $helperDirectory . '/bootstrap.php');

require_once dirname(__DIR__) . '/vendor/autoload.php';
