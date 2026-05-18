<?php
/**
 * Login Context Handler Definition
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
 * Login context handler
 *
 * @subpackage Context
 */
class Login extends Abstracts\ContextHandler
{
	/**
	 * Enqueue styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript( handle: "{$this->package}-login", path: 'build/login.js' );
		$this->enqueueStyle( handle: "{$this->package}-login", path: 'build/login.css' );
	}
}
