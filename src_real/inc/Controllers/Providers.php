<?php
/**
 * Providers Controller
 *
 * PHP Version 8.0.28
 *
 * @package %package%
 * @author  %author_name% <%author_email%>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    %plugin_uri%
 * @since   1.0.0
 */

namespace %namespace%\Controllers;

use %namespace%\Providers as Provider;

use %namespace%\Deps\Devkit\WPCore,
	%namespace%\Deps\Devkit\WPCore\DI\OnMount,
	%namespace%\Deps\Devkit\WPCore\DI\ContainerBuilder;

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
			Provider\Astra::class   => ContainerBuilder::autowire(),
			Provider\Kadence::class => ContainerBuilder::autowire(),
		];
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Provider\Astra $provider : astra provider instance.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountAstra( Provider\Astra $provider ): void
	{
		add_filter( "{$this->package}_frontend_style_dependencies", [ $provider, 'useStyles' ] );
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Provider\Kadence $provider : kadence provider instance.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountKadence( Provider\Kadence $provider ): void
	{
		add_filter( "{$this->package}_frontend_style_dependencies", [ $provider, 'useStyles' ] );
	}
}
