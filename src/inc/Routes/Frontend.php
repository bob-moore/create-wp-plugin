<?php
/**
 * Frontend Route Definition
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/Devkit-Plugin-Boilerplate
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Routes;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount;

/**
 * Frontend router class
 *
 * @subpackage Route
 */
class Frontend extends WPCore\Abstracts\Mountable implements
	WPCore\Interfaces\Uses\Scripts,
	WPCore\Interfaces\Uses\Styles
{
	use WPCore\Traits\Uses\Scripts;
	use WPCore\Traits\Uses\Styles;
	/**
	 * Load actions and filters, and other setup requirements
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ] );
	}
	/**
	 * Enqueue admin styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript(
			'frontend',
			'frontend/bundle.js'
		);
		$this->enqueueStyle(
			'frontend',
			'frontend/bundle.css'
		);
	}
}
