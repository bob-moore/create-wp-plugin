<?php
/**
 * Editor Handler
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Handlers;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount;

/**
 * Adds custom editor support
 *
 * @subpackage Handlers
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
