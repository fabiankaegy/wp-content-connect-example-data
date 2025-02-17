<?php
/**
 * Person post type registration.
 *
 * @package WPContentConnectExample
 */

declare(strict_types=1);

namespace WPContentConnectExample\PostTypes;

/**
 * Person post type class.
 */
class Person {
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	public const POST_TYPE = 'person';

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'              => [
					'name'                  => __( 'People', 'wp-content-connect-example' ),
					'singular_name'         => __( 'Person', 'wp-content-connect-example' ),
					'add_new'               => __( 'Add New', 'wp-content-connect-example' ),
					'add_new_item'          => __( 'Add New Person', 'wp-content-connect-example' ),
					'edit_item'             => __( 'Edit Person', 'wp-content-connect-example' ),
					'new_item'              => __( 'New Person', 'wp-content-connect-example' ),
					'view_item'             => __( 'View Person', 'wp-content-connect-example' ),
					'search_items'          => __( 'Search People', 'wp-content-connect-example' ),
					'not_found'             => __( 'No people found', 'wp-content-connect-example' ),
					'not_found_in_trash'    => __( 'No people found in Trash', 'wp-content-connect-example' ),
					'parent_item_colon'     => __( 'Parent Person:', 'wp-content-connect-example' ),
					'all_items'             => __( 'All People', 'wp-content-connect-example' ),
					'archives'              => __( 'Person Archives', 'wp-content-connect-example' ),
					'attributes'            => __( 'Person Attributes', 'wp-content-connect-example' ),
					'insert_into_item'      => __( 'Insert into person', 'wp-content-connect-example' ),
					'uploaded_to_this_item' => __( 'Uploaded to this person', 'wp-content-connect-example' ),
					'featured_image'        => __( 'Featured Image', 'wp-content-connect-example' ),
					'set_featured_image'    => __( 'Set featured image', 'wp-content-connect-example' ),
					'remove_featured_image' => __( 'Remove featured image', 'wp-content-connect-example' ),
					'use_featured_image'    => __( 'Use as featured image', 'wp-content-connect-example' ),
					'menu_name'             => __( 'People', 'wp-content-connect-example' ),
				],
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 22,
				'menu_icon'           => 'dashicons-admin-users',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'show_in_rest'        => true,
				'supports'            => [
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'custom-fields',
				],
				'rewrite'             => [
					'slug' => 'person',
				],
			]
		);
	}
}
