<?php
/**
 * Mountable interface definition
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://www.bobmoore.dev
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Interfaces;

/**
 * Action Module interface requirements
 *
 * @subpackage Interfaces
 */
interface Mountable
{
	/**
	 * Check if loading action has already fired
	 *
	 * @return int
	 */
	public function hasMounted(): int;
	/**
	 * Fire Mounted action on mount
	 *
	 * @return void
	 */
	public function onMount(): void;
}
