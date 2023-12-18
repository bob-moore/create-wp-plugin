<?php
/**
 * Handler Controller
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

 namespace PLUGIN_NAMESPACE\Handlers;

 use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	 PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Terms extends WPCore\Handlers\Terms
{
	/**
	 * Array of custom taxonomies
	 *
	 * @var array<Interfaces\Entities\Taxonomy>
	 */
	protected array $taxonomies = [];
}
