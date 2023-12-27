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
	 * Array of entities to register
	 *
	 * @var array<string, array<string>>
	 */
	protected const ENTITIES = [
		'blocks'     => [
			'blocks/sample/block.json'
		],
		'taxonomies' => [
			Entity\CustomTaxonomy::class,
		],
		'post_types' => [
			Entity\CustomPostType::class,
		],
	];
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		$definitions = [];
		/**
		 * Loop over and register block definitions, and block factory.
		 */
		foreach ( self::ENTITIES['blocks'] as $definition ) {
			$definitions[ $definition ] = ContainerBuilder::string( '{config.dir}/{config.assets.dir}/' . $definition );
		}
		$definitions['blocks'] = ContainerBuilder::factory( function( ContainerInterface $container ) {
			return array_map( fn( $definition ) => $container->get( $definition ), self::ENTITIES['blocks'] );
		} );
		/**
		 * Loop over and register taxonomy definitions, and taxonomy factory.
		 */
		foreach ( self::ENTITIES['taxonomies'] as $definition ) {
			$definitions[ $definition ] = ContainerBuilder::autowire();
		}
		$definitions['taxonomies'] = ContainerBuilder::factory( function( ContainerInterface $container ) {
			return array_map( fn( $definition ) => $container->get( $definition ), self::ENTITIES['taxonomies'] );
		} );
		/**
		 * Loop over and register post type definitions, and post type factory.
		 */
		foreach ( self::ENTITIES['post_types'] as $definition ) {
			$definitions[ $definition ] = ContainerBuilder::autowire();
		}
		$definitions['post_types'] = ContainerBuilder::factory( function( ContainerInterface $container ) {
			return array_map( fn( $definition ) => $container->get( $definition ), self::ENTITIES['post_types'] );
		} );

		return $definitions;
	}
}
