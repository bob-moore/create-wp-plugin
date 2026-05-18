<?php
/**
 * Main app file
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://example.com
 * @since   1.0.0
 */

namespace Placeholder\Plugin;

use Placeholder\Plugin\Services\ServiceLocator;
/**
 * Main App Class
 *
 * Defines the service container and mounts the plugin.
 */
class Main extends Abstracts\Controller
{
	/**
	 * The name of the plugin.
	 *
	 * @var string
	 */
	public const PACKAGE = 'placeholder_plugin';
	/**
	 * Service Locator, used to set/retrieve services from the DI container.
	 *
	 * @var ServiceLocator|null
	 */
	protected static ?ServiceLocator $service_locator;
	/**
	 * Public constructor
	 *
	 * @param array<string, mixed> $config : optional configuration array.
	 * @param bool                 $should_compile : enable PHP-DI container compilation.
	 */
	public function __construct( protected array $config = [], bool $should_compile = false )
	{
		if ( ! isset( self::$service_locator ) ) {
			self::$service_locator = new ServiceLocator( $should_compile );
		}
	}
	/**
	 * Merge additional values into the configuration array
	 *
	 * @param array<string, mixed> $config Configuration values to merge.
	 */
	public function setConfig( array $config = [] ): void
	{
		$this->config = wp_parse_args( $config, $this->config );
	}
	/**
	 * Register static configuration into the service container
	 *
	 * config.dir and config.url are intentionally excluded here — they are
	 * dynamic (path changes per environment) and must not be baked into the
	 * compiled container. They are injected after build() via setService().
	 *
	 * @return void
	 */
	protected function registerConfig(): void
	{
		if ( empty( $this->config['config.package'] ) ) {
			$this->config['config.package'] = static::PACKAGE;
		}

		$static_config = array_diff_key(
			$this->config,
			[ 'config.dir' => '', 'config.url' => '' ]
		);

		self::$service_locator->addDefinitions( definitions: $static_config );
	}
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Controllers\ProcessorController::class => ServiceLocator::autowire(),
			Controllers\ContextController::class   => ServiceLocator::autowire(),
			Controllers\ProviderController::class  => ServiceLocator::autowire(),
			Controllers\ServiceController::class   => ServiceLocator::autowire(),
		];
	}
	/**
	 * Mount the plugin
	 *
	 * @return void
	 */
	public function mount(): void
	{
		$this->registerConfig();
		
		self::$service_locator->addDefinitions(
			definitions: static::getServiceDefinitions()
		);

		self::$service_locator->build();

		// Inject dynamic path values after build so they are never compiled/cached.
		// Container::set() overrides at runtime even in a compiled container.
		self::$service_locator->setService( 'config.dir', $this->config['config.dir'] ?? '' );
		self::$service_locator->setService( 'config.url', $this->config['config.url'] ?? '' );

		$defs = self::$service_locator->getDefinitions();

		foreach ( $defs as $service => $definition ) {
			if ( is_object( $definition ) && Helpers::implements( $service, Interfaces\Controller::class ) ) {
				self::$service_locator->mountService( service: $service );
			}
		}
	}
	/**
	 * Locate a specific service
	 *
	 * @param string $service_name : name of service to locate.
	 *
	 * @return mixed
	 */
	public static function locateService( string $service_name ): mixed
	{
		if ( ! isset( self::$service_locator ) ) {
			return null;
		}
		$services = [
			trim( $service_name ),
			trim( __NAMESPACE__ . '\\' . $service_name ),
		];
		foreach ( $services as $service ) {
			$resolved = self::$service_locator->getService( service: $service );
			if ( is_wp_error( thing: $resolved ) ) {
				continue;
			}
			return $resolved;
		}
		return null;
	}
}
