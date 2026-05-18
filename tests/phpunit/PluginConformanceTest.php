<?php
/**
 * Plugin class conformance tests.
 *
 * @package Placeholder_Plugin
 */

namespace Placeholder\Plugin\PHPUnit;

use Bmd\WPFramework\Abstracts;
use Bmd\WPFramework\Interfaces;
use Bmd\WPFramework\Main as FrameworkMain;
use Placeholder\Plugin\Context;
use Placeholder\Plugin\Controllers;
use Placeholder\Plugin\Main;
use Placeholder\Plugin\Processors;
use Placeholder\Plugin\Providers;
use WP_Mock\Tools\TestCase;

/**
 * @coversNothing
 */
final class PluginConformanceTest extends TestCase
{
	/**
	 * @dataProvider classConformanceProvider
	 *
	 * @param class-string        $class Class under test.
	 * @param class-string        $parent Expected parent class or abstract.
	 * @param array<class-string> $interfaces Expected framework interfaces.
	 */
	public function testPluginClassesConformToFrameworkContracts( string $class, string $parent, array $interfaces ): void
	{
		$this->assertTrue( class_exists( $class ), "{$class} does not exist." );
		$this->assertTrue( is_subclass_of( $class, $parent ), "{$class} does not extend {$parent}." );

		foreach ( $interfaces as $interface ) {
			$this->assertTrue( is_subclass_of( $class, $interface ), "{$class} does not implement {$interface}." );
		}
	}

	/**
	 * @dataProvider moduleClassProvider
	 *
	 * @param class-string $class Module class under test.
	 */
	public function testPluginModulesExposeNormalizedPackageName( string $class ): void
	{
		$reflection = new \ReflectionClass( $class );
		$instance   = $reflection->newInstanceWithoutConstructor();

		$this->assertInstanceOf( Abstracts\Module::class, $instance );

		$instance->setPackage( 'Placeholder/Plugin-Test' );

		$this->assertSame( 'placeholder_plugin_test', $instance->getPackage() );
	}

	/**
	 * @dataProvider controllerClassProvider
	 *
	 * @param class-string<Interfaces\Controller> $class Controller class under test.
	 */
	public function testPluginControllersExposeServiceDefinitions( string $class ): void
	{
		$definitions = $class::getServiceDefinitions();

		$this->assertIsArray( $definitions );

		foreach ( array_keys( $definitions ) as $service ) {
			$this->assertIsString( $service );
		}
	}

	public function testContextHandlersResolveToFrameworkContextHandlers(): void
	{
		foreach ( Context\Handlers::cases() as $handler ) {
			if ( '' === $handler->value ) {
				continue;
			}

			$this->assertTrue( class_exists( $handler->value ) );
			$this->assertTrue( is_subclass_of( $handler->value, Interfaces\ContextHandler::class ) );
		}
	}

	/**
	 * @return array<string, array{class-string, class-string, array<class-string>}>
	 */
	public static function classConformanceProvider(): array
	{
		return [
			'main'                 => [
				Main::class,
				FrameworkMain::class,
				[
					Interfaces\Module::class,
					Interfaces\Controller::class,
					Interfaces\Mountable::class,
				],
			],
			'service-controller'   => [
				Controllers\ServiceController::class,
				Abstracts\Controller::class,
				[
					Interfaces\Module::class,
					Interfaces\Controller::class,
					Interfaces\Mountable::class,
				],
			],
			'context-controller'   => [
				Controllers\ContextController::class,
				Abstracts\Controller::class,
				[
					Interfaces\Module::class,
					Interfaces\Controller::class,
					Interfaces\Mountable::class,
				],
			],
			'processor-controller' => [
				Controllers\ProcessorController::class,
				Abstracts\Controller::class,
				[
					Interfaces\Module::class,
					Interfaces\Controller::class,
					Interfaces\Mountable::class,
				],
			],
			'provider-controller'  => [
				Controllers\ProviderController::class,
				Abstracts\Controller::class,
				[
					Interfaces\Module::class,
					Interfaces\Controller::class,
					Interfaces\Mountable::class,
				],
			],
			'editor-context'       => [
				Context\Editor::class,
				Abstracts\ContextHandler::class,
				[
					Interfaces\Module::class,
					Interfaces\ContextHandler::class,
					Interfaces\Mountable::class,
				],
			],
			'blocks-provider'      => [
				Providers\Blocks::class,
				Abstracts\Module::class,
				[
					Interfaces\Module::class,
				],
			],
			'shortcodes-provider'  => [
				Providers\Shortcodes::class,
				Abstracts\Module::class,
				[
					Interfaces\Module::class,
				],
			],
			'taxonomies-provider'  => [
				Providers\Taxonomies::class,
				Abstracts\Module::class,
				[
					Interfaces\Module::class,
				],
			],
			'blocks-processor'     => [
				Processors\Blocks::class,
				Abstracts\Module::class,
				[
					Interfaces\Module::class,
				],
			],
		];
	}

	/**
	 * @return array<string, array{class-string}>
	 */
	public static function moduleClassProvider(): array
	{
		return array_map(
			static fn ( array $definition ): array => [ $definition[0] ],
			self::classConformanceProvider()
		);
	}

	/**
	 * @return array<string, array{class-string<Interfaces\Controller>}>
	 */
	public static function controllerClassProvider(): array
	{
		return [
			'main'                 => [ Main::class ],
			'service-controller'   => [ Controllers\ServiceController::class ],
			'context-controller'   => [ Controllers\ContextController::class ],
			'processor-controller' => [ Controllers\ProcessorController::class ],
			'provider-controller'  => [ Controllers\ProviderController::class ],
		];
	}
}
