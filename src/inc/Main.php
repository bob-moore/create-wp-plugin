<?php
/**
 * Main theme handler
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/Devkit-Plugin-Boilerplate
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
			Controllers\Handlers::class => ContainerBuilder::autowire(),
			Controllers\Routes::class   => ContainerBuilder::autowire(),
			Controllers\Services::class => ContainerBuilder::autowire(),
		];
	}
	/**
	 * Mount the Actions
	 *
	 * @return void
	 */
	protected function mountActions(): void
	{
		parent::mountActions();
	}
	/**
	 * Mount the controller classes
	 *
	 * @return void
	 */
	protected function mountControllers(): void
	{
		parent::mountControllers();
	}
}
