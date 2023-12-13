<?php
/**
 * Admin Route Definition
 *
 * PHP Version 8.0.28
 *
 * @package %package%
 * @author  %author_name% <%author_email%>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    %plugin_uri%
 * @since   1.0.0
 */

namespace %namespace%\Routes;

use %namespace%\Deps\Devkit\WPCore,
	%namespace%\Deps\Devkit\WPCore\DI\OnMount;

/**
 * Admin router class
 *
 * @subpackage Route
 */
class Admin extends WPCore\Abstracts\Mountable implements
	WPCore\Interfaces\Uses\ScriptDispatcher,
	WPCore\Interfaces\Uses\StyleDispatcher
{
	use WPCore\Traits\Uses\ScriptDispatcher;
	use WPCore\Traits\Uses\StyleDispatcher;

	/**
	 * Load actions and filters, and other setup requirements
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
	}
	/**
	 * Enqueue admin styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript(
			'admin',
			'admin/bundle.js'
		);
		$this->enqueueStyle(
			'admin',
			'admin/bundle.css'
		);
	}
}
