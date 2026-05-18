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
	 */
	public function __construct( protected array $config = [] )
	{
		if ( ! isset( self::$service_locator ) ) {
			self::$service_locator = new ServiceLocator();
		}
	}
	/**
	 * Set the configuration array
	 *
	 * @param array<string, mixed> $config Configuration array to merge with existing config.
	 */
	public function setConfig( array $config = [] ): void
	{
		$this->config = wp_parse_args(
			args: $config,
			defaults: $this->config ?? []
		);
	}
	/**
	 * Register the configuration array
	 *
	 * @return void
	 */
	protected function registerConfig(): void
	{
		if ( empty( $this->config['config.package'] ) ) {
			$this->config['config.package'] = static::PACKAGE;
		}

		$this->config = apply_filters(
			"{$this->config['config.package']}_config",
			$this->config
		);

		self::$service_locator->addDefinitions(
			definitions: wp_parse_args(
				args: $this->config,
				defaults: [
					'config.dir' => untrailingslashit( plugin_dir_path( __DIR__ ) ),
					'config.url' => untrailingslashit( plugin_dir_url( __DIR__ ) ),
				]
			)
		);
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
	 * Register controller definitions in the service container
	 *
	 * @return void
	 */
	public function registerControllers(): void
	{
		self::$service_locator->addDefinitions(
			definitions: static::getServiceDefinitions()
		);
	}
	/**
	 * Mount the plugin
	 *
	 * @return void
	 */
	public function mount(): void
	{
		$this->registerConfig();
		$this->registerControllers();

		self::$service_locator->build();

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
