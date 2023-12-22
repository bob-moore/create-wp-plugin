<?php
/**
 * Entities Controller
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Controllers;

use PLUGIN_NAMESPACE\Entities as Entity;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount,
	PLUGIN_NAMESPACE\Deps\DI\Attribute\Inject,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

use Psr\Container\ContainerInterface;

/**
 * Entities controller class
 *
 * Controls and orchestrates the execution of entities (post types, taxonomies, etc).
 *
 * @subpackage Controllers
 */
class Entities extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Controller
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Entity\CustomPostType::class => ContainerBuilder::autowire(),
			Entity\CustomTaxonomy::class => ContainerBuilder::autowire(),
			'post_types'                 => static::getPostTypeServiceAliases(),
			'taxonomies'                 => static::getTaxonomyServiceAliases(),
			'blocks'                     => static::getBlockServiceAliases(),
		];
	}
	/**
	 * Get service aliases for post types
	 *
	 * @return array<
	 */
	/**
	 * Get service aliases for post types
	 *
	 * @return WPCore\DI\ArrayDefinitionHelper
	 */
	protected static function getPostTypeServiceAliases(): WPCore\DI\ArrayDefinitionHelper
	{
		return ContainerBuilder::array(
			[
				ContainerBuilder::get( Entity\CustomPostType::class ),
			]
		);
	}
	/**
	 * Get service aliases for taxonomies
	 *
	 * @return WPCore\DI\ArrayDefinitionHelper
	 */
	protected static function getTaxonomyServiceAliases(): WPCore\DI\ArrayDefinitionHelper
	{
		return ContainerBuilder::array(
			[
				ContainerBuilder::get( Entity\CustomTaxonomy::class ),
			]
		);
	}
	/**
	 * Get service aliases for blocks
	 *
	 * Should be a list of paths to block.json files
	 *
	 * @return WPCore\DI\ArrayDefinitionHelper
	 */
	protected static function getBlockServiceAliases(): WPCore\DI\ArrayDefinitionHelper
	{
		return ContainerBuilder::array(
			[
				'sample' => ContainerBuilder::string( '{config.dir}/{config.assets.dir}/blocks/sample/block.json' ),
			]
		);
	}
}
