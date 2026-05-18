<?php
/**
 * Provider Controller
 *
 * Manages and coordinates various WordPress functionality providers,
 * including blocks, context handling, images, patterns, editor settings,
 * and template parts.
 *
 * PHP Version 8.2
 *
 * @package    Placeholder_Plugin
 * @subpackage Controllers
 * @author     Plugin Author <author@example.com>
 * @license    GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link       https://example.com
 * @since      1.0.0
 */

namespace Placeholder\Plugin\Controllers;

use Placeholder\Plugin\ {
	Providers,
	Services\ServiceLocator,
	Abstracts
};
use DI\Attribute\Inject;

/**
 * Provider Controller Class
 *
 * Controls the registration and execution of WordPress functionality providers.
 *
 * @subpackage Controllers
 */
class ProviderController extends Abstracts\Controller
{
	/**
	 * Get service container definitions
	 *
	 * @return array<string, mixed> Array of service definitions.
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Providers\Context::class    => ServiceLocator::autowire(),
			Providers\Blocks::class     => ServiceLocator::autowire(),
			Providers\Shortcodes::class => ServiceLocator::autowire(),
			Providers\Taxonomies::class => ServiceLocator::autowire(),
		];
	}
	/**
	 * Mount Context Provider
	 *
	 * @param Providers\Context $provider Instance of Context provider.
	 *
	 * @return void
	 */
	#[Inject]
	public function mountContext( Providers\Context $provider ): void
	{
		add_action( 'wp', [ $provider, 'dispatch' ], 4 );
		add_action( 'current_screen', [ $provider, 'dispatch' ], 4 );
		add_action( 'login_init', [ $provider, 'dispatch' ], 4 );
	}

	/**
	 * Mount Blocks Provider
	 *
	 * @param Providers\Blocks $provider Instance of Blocks provider.
	 *
	 * @return void
	 */
	#[Inject]
	public function mountBlocks( Providers\Blocks $provider ): void
	{
		add_action( 'init', [ $provider, 'registerBlocks' ] );
	}

	/**
	 * Mount Shortcodes Provider
	 *
	 * @param Providers\Shortcodes $provider Instance of Shortcodes provider.
	 *
	 * @return void
	 */
	#[Inject]
	public function mountShortcodes( Providers\Shortcodes $provider ): void
	{
		add_shortcode( 'hello_world', [ $provider, 'renderHelloWorld' ] );
	}

	/**
	 * Mount Taxonomies Provider
	 *
	 * @param Providers\Taxonomies $provider Instance of Taxonomies provider.
	 *
	 * @return void
	 */
	#[Inject]
	public function mountTaxonomies( Providers\Taxonomies $provider ): void
	{
		add_action( 'init', [ $provider, 'registerTaxonomies' ] );
	}
}
