<?php
/**
 * Context Type Enum
 *
 * Defines the available context types for the application
 *
 * @package Placeholder_Plugin
 * @author  Plugin Author <author@example.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://example.com
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Context;

/**
 * Context Type Enumeration
 *
 * Defines all available context handlers for different WordPress page types
 *
 * @subpackage Context
 */
enum Handlers: string
{
	/** EDITOR page handler */
	case EDITOR = Editor::class;
	/** No specific handler */
	case NONE = '';
}
