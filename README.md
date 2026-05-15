# Placeholder Plugin Framework

This repository is a starter WordPress plugin framework built around [`bmd/wp-framework`](https://github.com/bob-moore/WP-Framework). It is intentionally small: the goal is to show the architecture, dependency scoping, and extension patterns without pretending to be a finished product.

Use it as a starting point for a plugin, an MU plugin, or a theme-adjacent package that wants a clean service container, predictable WordPress hook registration, and scoped Composer dependencies.

## What This Demonstrates

- A root plugin bootstrap in [`Plugin.php`](Plugin.php).
- A framework entry point in [`includes/Main.php`](includes/Main.php).
- Controller classes that orchestrate dependency registration and WordPress hooks.
- Provider classes that register WordPress features such as blocks, shortcodes, and taxonomies.
- Processor classes that transform data or markup.
- Context handlers for view-specific behavior.
- A scoped Timber/Twig compiler using [`wpify/scoper`](https://github.com/wpify/scoper), [`timber/timber`](https://github.com/timber/timber), and [`twig/twig`](https://github.com/twigphp/Twig).
- A minimal static block in [`src/example-block`](src/example-block).
- Basic PHPUnit examples using [`10up/wp_mock`](https://github.com/10up/wp_mock).

## Key Dependencies

Runtime dependencies that are bundled and scoped are listed in [`composer-deps.json`](composer-deps.json):

- [`bmd/wp-framework`](https://github.com/bob-moore/WP-Framework): the underlying controller/module/service-container framework.
- [`timber/timber`](https://github.com/timber/timber): WordPress-friendly Twig rendering.
- [`twig/twig`](https://github.com/twigphp/Twig): template engine used by Timber.

Development dependencies are listed in [`composer.json`](composer.json) and [`package.json`](package.json):

- [`wpify/scoper`](https://github.com/wpify/scoper): prefixes runtime Composer dependencies into this plugin namespace.
- [`PHP-DI`](https://php-di.org/): dependency injection container used by WPFramework.
- [`@wordpress/scripts`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/): build tooling for block and asset compilation.
- [`@wordpress/blocks`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-blocks/) and [`@wordpress/block-editor`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/): block registration and editor helpers.

## Boot Flow

The plugin starts in [`Plugin.php`](Plugin.php):

1. WordPress loads the plugin file.
2. The plugin loads scoped Composer autoloaders from `vendor/scoped`.
3. The plugin loads the development/root autoloader from `vendor/autoload.php`.
4. A config array is passed into `Placeholder\Plugin\Main`.
5. `Main::mount()` builds the service container and mounts registered controllers.

The important config keys are:

- `config.package`: the package slug used to build hook names, currently `placeholder_plugin`.
- `config.dir`: plugin root path.
- `config.url`: plugin root URL.

## Architecture

This project uses a small set of class roles. Keeping these roles distinct is the point of the framework.

### Main

[`includes/Main.php`](includes/Main.php) extends `Bmd\WPFramework\Main`.

`Main` answers two questions:

- Which controllers are part of this package?
- Which controller classes should be autowired into the service container?

It should stay boring. Avoid business logic here.

### Controllers

Controllers live in [`includes/Controllers`](includes/Controllers).

Controllers do not do the actual work. They orchestrate registration.

A controller typically:

- Adds service definitions via `getServiceDefinitions()`.
- Receives dependencies through `#[Inject]`.
- Registers WordPress actions, filters, shortcodes, or dispatch hooks.
- Delegates actual behavior to a provider, processor, service, or context handler.

Examples:

- [`ProviderController`](includes/Controllers/ProviderController.php) wires blocks, shortcodes, and taxonomies.
- [`ServiceController`](includes/Controllers/ServiceController.php) wires the Timber/Twig compiler to package-specific filters and actions.
- [`ProcessorController`](includes/Controllers/ProcessorController.php) wires block rendering through a processor.
- [`ContextController`](includes/Controllers/ContextController.php) wires view-specific context handlers.

If a controller starts parsing data, rendering templates, querying posts, or making decisions specific to a feature, move that behavior into a module class.

### Providers

Providers live in [`includes/Providers`](includes/Providers).

Providers register WordPress features. They are the place for feature-level registration methods that a controller can mount.

Current examples:

- [`Providers\Blocks`](includes/Providers/Blocks.php): finds built block metadata in `build/blocks/**/block.json` and calls `register_block_type()`.
- [`Providers\Shortcodes`](includes/Providers/Shortcodes.php): renders `[timber]...[/timber]` shortcode content through the compiler filter.
- [`Providers\Taxonomies`](includes/Providers/Taxonomies.php): registers example page taxonomies.

Providers can contain feature configuration, but keep them focused. A provider should register a thing; a processor/service should do deeper work.

### Services

Services live in [`includes/Services`](includes/Services).

Services are reusable capabilities that other modules can call through hooks or dependency injection.

Current example:

- [`Services\Compiler`](includes/Services/Compiler.php): wraps scoped Timber/Twig rendering and exposes it through package-specific filters and actions.

The compiler is deliberately kept because it demonstrates the scoped dependency pattern:

```php
use Placeholder\Plugin\Timber\Timber;
use Placeholder\Plugin\Twig\Error\SyntaxError;
```

Those classes come from dependencies prefixed by WPify Scoper into the plugin namespace.

### Processors

Processors live in [`includes/Processors`](includes/Processors).

Processors transform content or data. They should not register themselves with WordPress. A controller decides when a processor is mounted.

Current example:

- [`Processors\Blocks`](includes/Processors/Blocks.php): looks for inline Twig syntax in selected core block output and sends it through the compiler filter.

The pattern is:

```php
return apply_filters( "{$this->package}_compile_string", $block_content, $block );
```

That keeps the block processor decoupled from Timber. It only knows that the package has a compile-string extension point.

### Context Handlers

Context handlers live in [`includes/Context`](includes/Context).

Context handlers are for specific WordPress views or execution contexts: frontend, admin, login, editor, REST, CLI, and so on. They are the right place for context-specific assets and behavior.

The current example keeps only a login handler:

- [`Context\Handlers`](includes/Context/Handlers.php): enum of package-specific context handlers.
- [`Context\Login`](includes/Context/Login.php): extends the WPFramework login context handler.

The framework `ContextController` dispatches context handlers and mounts their assets. This package extends that behavior only where an example is useful.

## Dependency Injection

WPFramework uses [`PHP-DI`](https://php-di.org/) behind its `ServiceLocator`.

The common pattern is:

```php
public static function getServiceDefinitions(): array
{
	return [
		SomeClass::class => ServiceLocator::autowire(),
	];
}
```

When a controller is added to the container, WPFramework also asks that controller for its service definitions. This lets controller registration cascade into providers, processors, context handlers, and services without manually instantiating them.

Dependencies can be injected with PHP attributes:

```php
#[Inject]
public function mountBlocks( Providers\Blocks $provider ): void
{
	add_action( 'init', [ $provider, 'registerBlocks' ] );
}
```

The controller owns the hook. The provider owns the behavior.

## Scoped Dependencies

This boilerplate uses [`wpify/scoper`](https://github.com/wpify/scoper) to prefix runtime dependencies into the plugin namespace.

Configuration lives in [`composer.json`](composer.json):

```json
"wpify-scoper": {
	"prefix": "Placeholder\\Plugin",
	"slug": "placeholder_plugin",
	"folder": "./vendor/scoped",
	"composerjson": "composer-deps.json",
	"composerlock": "composer-deps.lock",
	"autorun": true
}
```

Runtime dependencies are declared separately in [`composer-deps.json`](composer-deps.json). This keeps development tooling separate from code that should be shipped with the plugin.

After `composer install`, scoped dependencies are written to `vendor/scoped`.

## Hooks Exposed By The Compiler

[`ServiceController`](includes/Controllers/ServiceController.php) exposes the compiler through package-specific hooks:

- `placeholder_plugin_timber/locations`
- `placeholder_plugin_compile_template`
- `placeholder_plugin_compile_string`
- `placeholder_plugin_render_template`
- `placeholder_plugin_render_string`

Examples of consumers:

- [`Providers\Shortcodes`](includes/Providers/Shortcodes.php) uses `placeholder_plugin_compile_string` for `[timber]`.
- [`Processors\Blocks`](includes/Processors/Blocks.php) uses `placeholder_plugin_compile_string` for supported block content containing Twig delimiters.

This is the preferred pattern for cross-module collaboration in this starter: expose a package hook, then let another module consume it.

## Blocks

Source files live in [`src/example-block`](src/example-block).

The block is intentionally simple:

- `block.json` declares `placeholder/example-block`.
- `index.tsx` renders `Hello World` in both `edit` and `save`.
- There is no PHP render file.

This is not meant to teach block development. It exists to show where block source lives and how built block metadata is auto-registered by `Providers\Blocks`.

Build output goes to `build/blocks`, and `Providers\Blocks` registers blocks by globbing:

```php
build/blocks/**/block.json
```

## Commands

Install PHP dependencies:

```bash
composer install
```

Install JS dependencies:

```bash
npm install
```

Build assets and blocks:

```bash
npm run compile
```

Run PHP unit tests:

```bash
composer run phpunit
```

Run PHPStan:

```bash
composer run phpstan
```

Run PHPCS:

```bash
composer run phpsniff
```

## Files Intentionally Ignored

This repository is boilerplate, not a distributed package. Generated artifacts are ignored:

- `build/`
- `vendor/`
- `node_modules/`
- `composer.lock`
- `composer-deps.lock`
- `package-lock.json`

Projects created from this starter can choose whether to commit lockfiles and build output based on their own deployment process.

## Adapting This Starter

At minimum, replace:

- Composer package name: `placeholder/plugin`
- PHP namespace: `Placeholder\Plugin`
- Package slug/text domain: `placeholder_plugin`
- Plugin header values in `Plugin.php`
- Block namespace: `placeholder/example-block`

Then add real modules by role:

- Need to register a WordPress feature? Add a provider and mount it in `ProviderController`.
- Need to transform content or data? Add a processor and mount it in `ProcessorController`.
- Need a reusable capability? Add a service and expose it through `ServiceController`.
- Need behavior for a specific view? Add a context handler and list it in `Context\Handlers`.
- Need a new controller? Add it to `Main::getServiceDefinitions()`.

Keep controllers thin. Let modules do the work. That one rule keeps the project easy to scan and easy to adapt.
