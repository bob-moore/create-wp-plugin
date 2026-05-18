<?php
/**
 * Router Service Definition
 *
 * PHP Version 8.2
 *
 * @package placeholder_plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://example.com
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Processors;

use Placeholder\Plugin\Abstracts;

/**
 * Example block processor class.
 *
 * @subpackage Processors
 */
class Blocks extends Abstracts\Module
{
	/**
	 * Example block processor method.
	 *
	 * @param string               $block_content : html output of the block.
	 * @param array<string, mixed> $block : parsed block.
	 *
	 * @return string
	 */
	public function processBlock( string $block_content, array $block ): string
	{
		$supported_blocks = [
			'core/paragraph',
			'core/heading',
		];

		if ( ! in_array( $block['blockName'], $supported_blocks ) ) {
			return $block_content;
		}

		// Do something to the block content, for example, wrap it in a div with a custom class.

		return $block_content;
	}
}
