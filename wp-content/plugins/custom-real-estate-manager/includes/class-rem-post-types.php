<?php
/**
 * Register Custom Post Types
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Post_Types {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'disable_gutenberg_for_property' ), 10, 2 );
	}

	/**
	 * Register CPTs.
	 */
	public function register_post_types() {
		// 1. Property Custom Post Type
		$property_labels = array(
			'name'               => _x( 'Properties', 'post type general name', 'custom-real-estate-manager' ),
			'singular_name'      => _x( 'Property', 'post type singular name', 'custom-real-estate-manager' ),
			'menu_name'          => _x( 'Properties (Custom)', 'admin menu', 'custom-real-estate-manager' ),
			'name_admin_bar'     => _x( 'Property', 'add new on admin bar', 'custom-real-estate-manager' ),
			'add_new'            => _x( 'Add New', 'property', 'custom-real-estate-manager' ),
			'add_new_item'       => __( 'Add New Property', 'custom-real-estate-manager' ),
			'new_item'           => __( 'New Property', 'custom-real-estate-manager' ),
			'edit_item'          => __( 'Edit Property', 'custom-real-estate-manager' ),
			'view_item'          => __( 'View Property', 'custom-real-estate-manager' ),
			'all_items'          => __( 'All Properties', 'custom-real-estate-manager' ),
			'search_items'       => __( 'Search Properties', 'custom-real-estate-manager' ),
			'parent_item_colon'  => __( 'Parent Properties:', 'custom-real-estate-manager' ),
			'not_found'          => __( 'No properties found.', 'custom-real-estate-manager' ),
			'not_found_in_trash' => __( 'No properties found in Trash.', 'custom-real-estate-manager' ),
		);

		$property_args = array(
			'labels'             => $property_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'properties', 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-admin-home',
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions' ),
		);

		register_post_type( 'property', $property_args );

		// Location Structure - State CPT
		$state_labels = array(
			'name'               => _x( 'States', 'post type general name', 'custom-real-estate-manager' ),
			'singular_name'      => _x( 'State', 'post type singular name', 'custom-real-estate-manager' ),
			'menu_name'          => _x( 'States', 'admin menu', 'custom-real-estate-manager' ),
			'add_new_item'       => __( 'Add New State', 'custom-real-estate-manager' ),
			'edit_item'          => __( 'Edit State', 'custom-real-estate-manager' ),
			'all_items'          => __( 'States', 'custom-real-estate-manager' ),
			'not_found'          => __( 'No states found.', 'custom-real-estate-manager' ),
		);

		$state_args = array(
			'labels'             => $state_labels,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=property', // Nest under Properties menu
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'state' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'read',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'thumbnail' ),
		);

		register_post_type( 'state', $state_args );

		// Location Structure - District CPT
		$district_labels = array(
			'name'               => _x( 'Districts', 'post type general name', 'custom-real-estate-manager' ),
			'singular_name'      => _x( 'District', 'post type singular name', 'custom-real-estate-manager' ),
			'menu_name'          => _x( 'Districts', 'admin menu', 'custom-real-estate-manager' ),
			'add_new_item'       => __( 'Add New District', 'custom-real-estate-manager' ),
			'edit_item'          => __( 'Edit District', 'custom-real-estate-manager' ),
			'all_items'          => __( 'Districts', 'custom-real-estate-manager' ),
			'not_found'          => __( 'No districts found.', 'custom-real-estate-manager' ),
		);

		$district_args = array(
			'labels'             => $district_labels,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=property',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'district' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'read',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'district', $district_args );

		// Location Structure - Taluk CPT
		$taluk_labels = array(
			'name'               => _x( 'Taluks', 'post type general name', 'custom-real-estate-manager' ),
			'singular_name'      => _x( 'Taluk', 'post type singular name', 'custom-real-estate-manager' ),
			'menu_name'          => _x( 'Taluks', 'admin menu', 'custom-real-estate-manager' ),
			'add_new_item'       => __( 'Add New Taluk', 'custom-real-estate-manager' ),
			'edit_item'          => __( 'Edit Taluk', 'custom-real-estate-manager' ),
			'all_items'          => __( 'Taluks', 'custom-real-estate-manager' ),
			'not_found'          => __( 'No taluks found.', 'custom-real-estate-manager' ),
		);

		$taluk_args = array(
			'labels'             => $taluk_labels,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=property',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'taluk' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'read',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'taluk', $taluk_args );

		// 5. Notification Custom Post Type (Private)
		register_post_type( 'rem_notification', array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'author' ),
		) );

		// Location Structure - Location Place CPT
		$place_labels = array(
			'name'               => _x( 'Places', 'post type general name', 'custom-real-estate-manager' ),
			'singular_name'      => _x( 'Place', 'post type singular name', 'custom-real-estate-manager' ),
			'menu_name'          => _x( 'Places', 'admin menu', 'custom-real-estate-manager' ),
			'add_new_item'       => __( 'Add New Place', 'custom-real-estate-manager' ),
			'edit_item'          => __( 'Edit Place', 'custom-real-estate-manager' ),
			'all_items'          => __( 'Places', 'custom-real-estate-manager' ),
			'not_found'          => __( 'No places found.', 'custom-real-estate-manager' ),
		);

		$place_args = array(
			'labels'             => $place_labels,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=property',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'place' ),
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'read',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'location_place', $place_args );
	}

	/**
	 * Disable Gutenberg block editor for the Property CPT.
	 *
	 * @param bool   $use_block_editor Whether to use the block editor.
	 * @param string $post_type        Post type name.
	 * @return bool
	 */
	public function disable_gutenberg_for_property( $use_block_editor, $post_type ) {
		if ( 'property' === $post_type ) {
			return false;
		}
		return $use_block_editor;
	}
}
