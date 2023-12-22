<?php
/**
 * Custom post type definition
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    PLUGIN_URI
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Entities;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore;

/**
 * Sample post type definition
 *
 * @subpackage Entities
 */
class CustomPostType extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Entities\PostType
{
	/**
	 * Getter for post type name
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return 'custom-post-type';
	}
	/**
	 * Getter for post type definition
	 *
	 * @see https://generatewp.com/post-type/ to generate new types
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 * @return array<string, mixed>
	 */
	public function getDefinition(): array
	{
		$labels = [
			'name'                  => _x( 'Custom Post Type', 'Post Type General Name', 'PLUGIN_SLUG' ),
			'singular_name'         => _x( 'Custom Post Type', 'Post Type Singular Name', 'PLUGIN_SLUG' ),
			'menu_name'             => __( 'Custom Post Types', 'PLUGIN_SLUG' ),
			'name_admin_bar'        => __( 'Custom Post Type', 'PLUGIN_SLUG' ),
			'archives'              => __( 'Item Archives', 'PLUGIN_SLUG' ),
			'attributes'            => __( 'Item Attributes', 'PLUGIN_SLUG' ),
			'parent_item_colon'     => __( 'Parent Item:', 'PLUGIN_SLUG' ),
			'all_items'             => __( 'All Items', 'PLUGIN_SLUG' ),
			'add_new_item'          => __( 'Add New Item', 'PLUGIN_SLUG' ),
			'add_new'               => __( 'Add New', 'PLUGIN_SLUG' ),
			'new_item'              => __( 'New Item', 'PLUGIN_SLUG' ),
			'edit_item'             => __( 'Edit Item', 'PLUGIN_SLUG' ),
			'update_item'           => __( 'Update Item', 'PLUGIN_SLUG' ),
			'view_item'             => __( 'View Item', 'PLUGIN_SLUG' ),
			'view_items'            => __( 'View Items', 'PLUGIN_SLUG' ),
			'search_items'          => __( 'Search Item', 'PLUGIN_SLUG' ),
			'not_found'             => __( 'Not found', 'PLUGIN_SLUG' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'PLUGIN_SLUG' ),
			'featured_image'        => __( 'Featured Image', 'PLUGIN_SLUG' ),
			'set_featured_image'    => __( 'Set featured image', 'PLUGIN_SLUG' ),
			'remove_featured_image' => __( 'Remove featured image', 'PLUGIN_SLUG' ),
			'use_featured_image'    => __( 'Use as featured image', 'PLUGIN_SLUG' ),
			'insert_into_item'      => __( 'Insert into item', 'PLUGIN_SLUG' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'PLUGIN_SLUG' ),
			'items_list'            => __( 'Items list', 'PLUGIN_SLUG' ),
			'items_list_navigation' => __( 'Items list navigation', 'PLUGIN_SLUG' ),
			'filter_items_list'     => __( 'Filter items list', 'PLUGIN_SLUG' ),
		];

		$rewrite = [
			'slug'       => 'custom-post-type',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
		];

		return [
			'label'               => __( 'Custom Post Type', 'PLUGIN_SLUG' ),
			'description'         => __( 'Custom Post Type', 'PLUGIN_SLUG' ),
			'labels'              => $labels,
			'supports'            => [
				'title',
				'editor',
				'revisions',
				'custom-fields',
				'page-attributes',
				'thumbnail',
			],
			'taxonomies'          => [],
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-document',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rewrite'             => $rewrite,
		];
	}
}
