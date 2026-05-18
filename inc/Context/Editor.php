<?php
/**
 * Editor Context Handler Definition
 *
 * PHP Version 8.2
 *
 * @package placeholder_plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://example.com
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Context;

use Bmd\WPFramework\Context\Admin;

/**
 * Editor context handler
 *
 * @subpackage Context
 * @see https://github.com/bob-moore/WP-Framework/tree/main/inc/Context
 */
class Editor extends Admin
{
	/**
	 * Do something specific for editor page context,
	 * for example, enqueue editor-specific assets or set up data for the block editor.
	 */
	public function enqueueEditorAssets(): void
	{
		$this->enqueueScript(
			handle: "{$this->package}-editor",
			path: 'build/editor.js'
		);

		$this->enqueueStyle(
			handle: "{$this->package}-editor",
			path: 'build/editor.css'
		);
	}
}
