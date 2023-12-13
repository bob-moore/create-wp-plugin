<?php
/**
 * Route controller
 *
 * PHP Version 8.0.28
 *
 * @package theme
 * @author  Bob Moore <bob.moore@midwestfamilymadison.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/wp-theme-boilerplate
 * @since   1.0.0
 */

namespace Mwf\Theme\Controllers;

use Mwf\Theme\Routes;

use Mwf\Theme\Deps\Mwf\WPCore,
	Mwf\Theme\Deps\Mwf\WPCore\DI\ContainerBuilder;

/**
 * Route controller class
 *
 * Defines routes to be loaded
 *
 * @subpackage Controllers
 */
class Router extends WPCore\Controllers\Routes
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Routes\Archive::class  => 	ContainerBuilder::autowire(),
			Routes\Search::class   => 	ContainerBuilder::autowire(),
			Routes\Blog::class     => 	ContainerBuilder::autowire(),
			Routes\Single::class   => 	ContainerBuilder::autowire(),
			Routes\Admin::class    => 	ContainerBuilder::autowire(),
			Routes\Frontend::class => 	ContainerBuilder::autowire(),
			'route.archive'        => 	ContainerBuilder::get( Routes\Archive::class ),
			'route.search'         => 	ContainerBuilder::get( Routes\Search::class ),
			'route.blog'           => 	ContainerBuilder::get( Routes\Blog::class ),
			'route.single'         => 	ContainerBuilder::get( Routes\Single::class ),
			'route.admin'          => 	ContainerBuilder::get( Routes\Admin::class ),
			'route.frontend'       => 	ContainerBuilder::get( Routes\Frontend::class ),
		];
	}
}
