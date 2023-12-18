<?php
/**
 * Providers Controller
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/Devkit-Plugin-Boilerplate
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Controllers;

use PLUGIN_NAMESPACE\Providers as Provider;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

use Psr\Container\ContainerInterface;

/**
 * Providers controller class
 *
 * Controls and orchestrates the execution of any 3rd party providers.
 *
 * @subpackage Controllers
 */
class Providers extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Controller
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Provider\ACF::class => ContainerBuilder::autowire(),
			static::class       => Lib\DI\ContainerBuilder::decorate(
				[
					static::class,
					'decorateInstance',
				]
			),
		];
	}
	/**
	 * Class decorator
	 *
	 * Mounts active providers
	 *
	 * @param self               $instance : instance of this class.
	 * @param ContainerInterface $container : container instance.
	 *
	 * @return self
	 */
	public static function decorateInstance( self $instance, ContainerInterface $container ): self
	{
		if ( 
			WPCore\Helpers::isPluginActive( 'advanced-custom-fields-pro/acf.php')
			|| WPCore\Helpers::isPluginActive( 'advanced-custom-fields/acf.php' )
		) {
			$container->call( [ $instance, 'mountAcf' ] );
		}

		return $instance;
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Provider\ACF $provider : acf provider instance.
	 *
	 * @return void
	 */
	public function mountAcf( Provider\ACF $provider ): void
	{
		// add actions & filters for ACF
	}
}
