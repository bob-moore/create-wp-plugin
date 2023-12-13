<?php
/**
 * Editor Handler
 *
 * PHP Version 8.0.28
 *
 * @package theme
 * @author  Bob Moore <bob.moore@midwestfamilymadison.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/wp-theme-boilerplate
 * @since   1.0.0
 */

namespace Mwf\Theme\Handlers;

use Mwf\Theme\Deps\Mwf\WPCore,
	Mwf\Theme\Deps\Mwf\WPCore\DI\OnMount;

/**
 * Adds custom editor support
 *
 * @subpackage Services
 */
class Editor extends WPCore\Abstracts\Mountable
{
	/**
	 * Add theme support to the editor
	 *
	 * @return void
	 */
	public function themeSupport(): void
	{
		add_theme_support( 'editor-styles' );
	}
	/**
	 * Enqueue the editor stylesheet for the block editor
	 *
	 * @return void
	 */
	public function editorStylesheet()
	{
		add_editor_style( 'dist/editor/bundle.css' );
	}
}
