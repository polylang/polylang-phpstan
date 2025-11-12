# Polylang PHPStan

This package provides a [PHPStan](https://phpstan.org/) extension for [Polylang](https://wordpress.org/plugins/polylang/) and [Polylang Pro](https://polylang.pro).
It's should be used in combination with [Polylang Stubs](https://github.com/polylang/polylang-stubs/).

### Requirements

- PHP >=7.1

### Installation

Require this package as a development dependency with Composer.

```bash
composer require --dev wpsyntex/polylang-phpstan
composer require --dev wpsyntex/polylang-stubs
```

### Configuration

Include the extension and stubs in the PHPStan configuration file.

```yaml
includes:
  - vendor/wpsyntex/polylang-phpstan/extension.neon
parameters:
  bootstrapFiles:
    - vendor/wpsyntex/polylang-stubs/polylang-stubs.php
```

Opt-in for WordPress stubs override.

```yaml
  stubFiles:
    - vendor/wpsyntex/polylang-phpstan/stubs/wordpress-override.php
```
