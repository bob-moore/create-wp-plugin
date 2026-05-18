<?php
/**
 * Service Controller
 *
 * Manages and coordinates core service components of the application,
 * including script loading, style loading, and path resolution services.
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
	Services\ServiceLocator,
	Services,
	Abstracts
};
/**
 * Service Controller Class
 *
 * Controls the registration and execution of core application services.
 *
 * @subpackage Controllers
 */
class ServiceController extends Abstracts\Controller
{
	/**
	 * Get service container definitions
	 *
	 * @return array<string, mixed> Array of service definitions.
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Services\ScriptLoader::class    => ServiceLocator::autowire(),
			Services\StyleLoader::class     => ServiceLocator::autowire(),
			Services\FilePathResolver::class => ServiceLocator::autowire(),
			Services\UrlResolver::class     => ServiceLocator::autowire(),
		];
	}
}
