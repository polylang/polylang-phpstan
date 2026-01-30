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
  stubFiles:
    - vendor/wpsyntex/polylang-stubs/polylang-stubs.php
```

### Opt in to WordPress stubs overrides

The `stubs/wordpress-override.php` file provides corrected type definitions for specific WordPress functions that have imprecise or incorrect type hints in the standard WordPress stubs (currently `sanitize_key()`, `maybe_serialize()`, and `sanitize_text_field()`).

```yaml
  stubFiles:
    - vendor/wpsyntex/polylang-phpstan/stubs/wordpress-override.php
```
