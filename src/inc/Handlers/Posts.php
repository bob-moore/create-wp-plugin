<?php
/**
 * Posts handler
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
 * Controls the functions related to posts
 *
 * @subpackage Handlers
 */
class Posts extends WPCore\Handlers\Posts
{
    /**
	 * Array of custom post types
	 *
	 * @var array<Interfaces\Entities\PostType>
	 */
	protected array $post_types = [];
}
