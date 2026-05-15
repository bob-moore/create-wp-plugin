# Create WP Plugin

A WordPress plugin starter for building a namespaced plugin with a small framework layer, Composer dependency scoping, WordPress block tooling, and PHP test/lint coverage.

The starter is designed to be bootstrapped into a real plugin with:

```bash
composer run create-plugin
```

After bootstrapping, the placeholder package name, PHP namespace, plugin metadata, block namespace, text domain, and main plugin file are rewritten for your plugin.

## What Is Included

- A WordPress plugin bootstrap file: `Plugin.php`.
- First-party plugin code under `inc/`.
- A scoped runtime framework dependency generated into `vendor/scoped`.
- A separate runtime dependency manifest: `composer-deps.json`.
- A root Composer setup for development tools, tests, and scoping.
- WordPress block/editor asset tooling through `@wordpress/scripts`.
- Example block source under `src/example-block`.
- PHPUnit tests focused on framework conformance, plus one example provider test.
- PHPStan and PHPCS configuration under `tests/`.

## Requirements

- PHP 8.2 or newer for the plugin runtime.
- Composer.
- Node and npm for block and asset builds.
- WordPress 6.0 or newer.

The Composer platform is currently pinned to PHP 8.1 for dependency resolution, while the plugin header requires PHP 8.2.

## Create A Plugin

Install dependencies first:

```bash
composer install
npm install
```

Then run the bootstrap command interactively:

```bash
composer run create-plugin
```

For a non-interactive setup, pass options after `--`:

```bash
composer run create-plugin -- \
	--name="My Plugin" \
	--slug="my-plugin" \
	--namespace="Acme\\MyPlugin" \
	--package="acme/my-plugin" \
	--description="A custom WordPress plugin." \
	--author="Acme Team" \
	--author-email="dev@example.com" \
	--plugin-uri="https://example.com/my-plugin" \
	--author-uri="https://example.com" \
	--block-namespace="my-plugin" \
	--main-file="my-plugin.php"
```

Useful options:

- `--dry-run`: show what would change without writing files.
- `--force`: allow overwriting an existing target main plugin file.
- `--help`: print all supported options.

The bootstrap script rewrites placeholders across PHP, JSON, XML, NEON, SCSS, TS, TSX, JS, Markdown, and related project files. It skips generated and local-only directories such as `vendor`, `node_modules`, `build`, `.git`, and `.phpunit.cache`.

After bootstrapping, refresh autoloads and build assets:

```bash
composer dump-autoload
composer install
npm run compile
```

`composer install` is important because WPify Scoper is configured with `autorun: true`, so it builds scoped runtime dependencies into `vendor/scoped`.

## Bootstrapping Flow

The plugin starts in `Plugin.php`.

At runtime, the bootstrap file:

1. Exits if WordPress has not loaded `ABSPATH`.
2. Loads `vendor/scoped/autoload.php`.
3. Loads `vendor/scoped/scoper-autoload.php`.
4. Loads the root development autoloader from `vendor/autoload.php`.
5. Builds a config array with:
   - `config.package`
   - `config.dir`
   - `config.url`
6. Instantiates `Placeholder\Plugin\Main`.
7. Calls `$plugin->mount()`.

`inc/Main.php` extends the scoped framework `Main` class and declares the plugin controllers that should be registered with the framework service locator.

## Project Layout

```text
Plugin.php                 Main WordPress plugin bootstrap.
composer.json              Development dependencies, scripts, autoloading, scoper config.
composer-deps.json         Runtime dependencies that should be scoped and bundled.
inc/                       First-party PHP plugin code.
inc/Context/               Plugin-specific context handlers.
inc/Controllers/           Controller classes that register services and hooks.
inc/Processors/            Content/data processors mounted by controllers.
inc/Providers/             WordPress feature providers.
scoper/                    Custom scoper support classes.
scripts/create-plugin.php  Bootstrap command implementation.
src/                       Block and asset source files.
tests/                     PHPStan, PHPCS, and PHPUnit configuration/tests.
vendor/scoped/             Generated scoped runtime dependencies.
```

## Architecture

The package follows the class roles provided by `bmd/wp-framework`.

### Main

`inc/Main.php` defines the plugin package and registers controller classes with the framework.

Keep this class focused on wiring. Feature behavior belongs in controllers, providers, processors, services, or context handlers.

### Controllers

Controllers live in `inc/Controllers`.

Controllers coordinate registration. They can:

- Add service definitions through `getServiceDefinitions()`.
- Receive dependencies through `#[Inject]`.
- Register WordPress hooks, filters, and shortcodes.
- Delegate work to providers, processors, and context handlers.

Current controllers:

- `Controllers\ServiceController`
- `Controllers\ContextController`
- `Controllers\ProcessorController`
- `Controllers\ProviderController`

### Providers

Providers live in `inc/Providers`.

Providers register WordPress features. The starter includes examples for:

- Blocks: `Providers\Blocks::registerBlocks()`
- Shortcodes: `Providers\Shortcodes`
- Taxonomies: `Providers\Taxonomies`

The only behavior-specific PHPUnit example currently kept is the blocks provider registration test. The other tests intentionally check framework conformance so boilerplate examples can be removed without breaking the suite.

### Processors

Processors live in `inc/Processors`.

Processors transform content or data. They should not mount themselves to WordPress. A controller owns the hook registration.

### Context Handlers

Context handlers live in `inc/Context`.

`Context\Handlers` is an enum of plugin-specific context handlers. `Context\Editor` extends the framework admin context handler and shows where editor-only assets can be registered.

## Scoped Runtime Dependencies

Runtime dependencies belong in `composer-deps.json`, not in the root `composer.json`.

The root `composer.json` is for development tooling:

- PHPUnit
- PHPStan
- PHPCS/WPCS
- WPify Scoper
- WP Mock

The scoped dependency manifest currently requires:

```json
{
	"require": {
		"bmd/wp-framework": "*"
	}
}
```

WPify Scoper reads `composer-deps.json` and writes scoped runtime code to `vendor/scoped`.

The scoper configuration lives in `composer.json`:

```json
{
	"extra": {
		"wpify-scoper": {
			"prefix": "Placeholder\\Plugin",
			"slug": "placeholder_plugin",
			"folder": "./vendor/scoped",
			"globals": [
				"wordpress",
				"woocommerce"
			],
			"composerjson": "composer-deps.json",
			"composerlock": "composer-deps.lock",
			"autorun": true
		}
	}
}
```

After you bootstrap a plugin, `prefix` and `slug` are rewritten to your namespace and package slug.

## Adding Scoped Dependencies

Add third-party runtime packages to `composer-deps.json`:

```json
{
	"require": {
		"bmd/wp-framework": "*",
		"vendor/package": "^1.0"
	}
}
```

Then run:

```bash
composer install
```

Because `wpify-scoper` has `autorun` enabled, Composer will install and scope the runtime dependencies into `vendor/scoped`.

Use scoped class names in plugin code. For example, after bootstrapping with the namespace `Acme\MyPlugin`, a dependency class originally named:

```php
Vendor\Package\Client
```

is referenced from plugin code as:

```php
Acme\MyPlugin\Vendor\Package\Client
```

Avoid adding runtime libraries to the root `composer.json` unless they are development-only tools. Code that ships with the plugin should go through `composer-deps.json` so it is scoped and bundled.

## Blocks And Assets

Source files live in `src/`.

The example block lives in:

```text
src/example-block
```

Build output is generated by `@wordpress/scripts`. `Providers\Blocks` registers built block metadata by globbing:

```text
build/blocks/**/block.json
```

Compile assets:

```bash
npm run compile
```

During development, run:

```bash
npm run start
```

## Testing And Quality Checks

Run PHPStan:

```bash
composer run phpstan
```

Run PHPCS:

```bash
composer run phpsniff
```

Auto-fix PHPCS issues where possible:

```bash
composer run phpsniff:fix
```

Run PHPUnit:

```bash
composer run phpunit
```

Run the full JS/CSS build pipeline:

```bash
npm run build
```

## Packaging Notes

The plugin must include:

- The main plugin file generated from `Plugin.php`.
- `inc/`.
- `vendor/`, including `vendor/scoped/`.
- Built assets under `build/` if the plugin uses blocks or compiled scripts/styles.

Do not rely on unscoped runtime packages being available in another plugin or theme. Dependencies that ship with this plugin should be declared in `composer-deps.json` and scoped into `vendor/scoped`.
