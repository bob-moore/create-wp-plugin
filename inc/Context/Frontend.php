<?php
/**
 * Frontend Context Handler Definition
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://www.bobmoore.dev
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Context;

use Placeholder\Plugin\Abstracts;
/**
 * Frontend context handler
 *
 * @subpackage Context
 */
class Frontend extends Abstracts\ContextHandler
{
	/**
	 * Enqueue frontend styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript( handle: "{$this->package}-frontend", path: 'build/frontend.js' );
		$this->enqueueStyle( handle: "{$this->package}-frontend", path: 'build/frontend.css' );
	}
}
