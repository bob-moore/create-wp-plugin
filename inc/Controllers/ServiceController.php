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

use Placeholder\Plugin\Bmd\WPFramework\Services\ServiceLocator;
use Placeholder\Plugin\Bmd\WPFramework;

use Placeholder\Plugin\DI\Attribute\Inject;

/**
 * Service Controller Class
 *
 * Controls the registration and execution of core application services.
 * Extends the framework's base service controller with plugin-specific services.
 *
 * @subpackage Controllers
 * @since      1.0.0
 */
class ServiceController extends WPFramework\Controllers\ServiceController
{
	/**
	 * Get service container definitions
	 *
	 * Adds plugin-specific services (Compiler, PostMeta) on top of the
	 * base framework services registered by the parent.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array<string, mixed> Array of service definitions.
	 */
	public static function getServiceDefinitions(): array
	{
		return array_merge(
			parent::getServiceDefinitions(),
			[]
		);
	}
}
