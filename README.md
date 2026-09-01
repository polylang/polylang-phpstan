# Polylang PHPStan

This package provides a [PHPStan](https://phpstan.org/) extension for [Polylang](https://wordpress.org/plugins/polylang/) and [Polylang Pro](https://polylang.pro).
It should be used in combination with [Polylang Stubs](https://github.com/polylang/polylang-stubs/).

## Requirements

- PHP 8+

## Installation

Require this package as a development dependency with Composer.

> [!TIP]
> `polylang/polylang-stubs` is optional but strongly recommended.

```bash
composer require --dev wpsyntex/polylang-phpstan
composer require --dev wpsyntex/polylang-stubs
```

## Configuration

### Adding the extension

Include the extension and stubs in the PHPStan configuration file.

> [!IMPORTANT]
> Prior to version 2.1 (included), `polylang/polylang-stubs` is automatically loaded.
> Starting from version 2.2, it must be configured manually.

```yaml
includes:
  - vendor/wpsyntex/polylang-phpstan/extension.neon
parameters:
  scanFiles:
    - vendor/wpsyntex/polylang-stubs/polylang-stubs.php
```

### Opt in to WordPress stubs overrides

The `stubs/wordpress-override.php` file provides corrected type definitions for specific WordPress functions that have imprecise or incorrect type hints in the standard WordPress stubs (currently `sanitize_key()`, `maybe_serialize()`, and `sanitize_text_field()`).

```yaml
  stubFiles:
    - vendor/wpsyntex/polylang-phpstan/stubs/wordpress-override.php
```

## Language switcher typing (Polylang 3.9+)

Polylang 3.9 replaced `PLL_Switcher::the_languages()` with `pll_the_languages()` and the internal `WP_Syntex\Polylang\Switcher\Switcher` class.

This extension provides dynamic return types for `pll_the_languages()`:

| `$args` | Return type |
|---|---|
| `raw => true` | `array<string, mixed>` |
| `echo => false` | `string` |
| default (`echo => true`) | `void` |

Direct usage of `WP_Syntex\Polylang\Switcher\Switcher` is typed from Polylang stubs or from Polylang source when you analyze the plugin itself.

### Deprecated `PLL_Switcher`

`PLL_Switcher` was removed in Polylang 3.9. When this extension is enabled, PHPStan reports:

- `polylang.deprecatedSwitcher` on `new PLL_Switcher()`
- `polylang.deprecatedSwitcherMethod` on `PLL_Switcher::the_languages()`

Migrate to `pll_the_languages()` or to `WP_Syntex\Polylang\Switcher\Switcher`.
