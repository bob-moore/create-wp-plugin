<?php
/**
 * Main theme handler
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

/**
 * Main file
 *
 * @subpackage Main
 */
class Main extends WPCore\Main
{
	/**
	 * The optional package name.
	 *
	 * @var string
	 */
	protected const PACKAGE = 'PLUGIN_SLUG';
	/**
	 * Get service definitions to add to service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Controllers\Handlers::class  => ContainerBuilder::autowire(),
			Controllers\Router::class    => ContainerBuilder::autowire(),
			Controllers\Providers::class => ContainerBuilder::autowire(),
			Controllers\Entities::class  => ContainerBuilder::autowire(),
			Controllers\Compiler::class  => ContainerBuilder::autowire(),
		];
	}
}
