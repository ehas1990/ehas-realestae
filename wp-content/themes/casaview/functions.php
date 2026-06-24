<?php
/**
 * PRWorks Real Estate Theme functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include Theme Settings and Custom Metaboxes
require_once get_template_directory() . '/inc/theme-settings.php';
require_once get_template_directory() . '/inc/property-metaboxes.php';
require_once get_template_directory() . '/inc/district-metaboxes.php';
require_once get_template_directory() . '/inc/header-menu-settings.php';
require_once get_template_directory() . '/inc/backup-settings.php';


// 1. Theme Setup
function casaview_theme_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Enable support for Document Title tag.
	add_theme_support( 'title-tag' );

	// Register Navigation Menus.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'casaview' ),
		'footer'  => esc_html__( 'Footer Menu', 'casaview' ),
	) );

	// Support HTML5 markup.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
}
add_action( 'after_setup_theme', 'casaview_theme_setup' );
function prworks_enqueue_bootstrap() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        array(),
        '5.3.3'
    );

    // Bootstrap Bundle JS (includes Popper)
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.3',
        true
    );
}
add_action('wp_enqueue_scripts', 'prworks_enqueue_bootstrap');
// 2. Enqueue Scripts and Styles
function casaview_enqueue_scripts() {
	// Enqueue Google Fonts
	$font_type = get_option( 'casaview_font_type', 'google' );
	if ( $font_type === 'google' ) {
		$selected_font = get_option( 'casaview_theme_font', 'Manrope' );
		if ( $selected_font !== 'Manrope' && $selected_font !== 'Tajawal' ) {
			$font_query = urlencode( $selected_font ) . ':wght@300;400;500;600;700;800';
			wp_enqueue_style( 'casaview-custom-google-font', "https://fonts.googleapis.com/css2?family={$font_query}&display=swap", array(), null );
		}
	}
	wp_enqueue_style( 'casaview-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap', array(), null );

	// Enqueue FontAwesome
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	// Enqueue Leaflet Maps CSS & JS
	wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
	wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
	wp_enqueue_style( 'leaflet-markercluster-css', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css', array('leaflet-css'), '1.4.1' );
	wp_enqueue_style( 'leaflet-markercluster-default-css', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css', array('leaflet-markercluster-css'), '1.4.1' );
	wp_enqueue_script( 'leaflet-markercluster-js', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js', array('leaflet-js'), '1.4.1', true );

	// Enqueue Main Stylesheet
	wp_enqueue_style( 'casaview-style', get_stylesheet_uri(), array(), '1.0.2' );

	// Enqueue Custom App Stylesheet
	wp_enqueue_style( 'casaview-theme-styles', get_template_directory_uri() . '/assets/css/theme.css', array(), time() );

	// Enqueue Swiper CSS & JS
	wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.1.4' );
	wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.1.4', true );
}
add_action( 'wp_enqueue_scripts', 'casaview_enqueue_scripts' );

// 3. Register Custom Post Types and Taxonomies
function casaview_register_post_types_and_taxonomies() {
	// 3.1 Properties CPT
	$property_labels = array(
		'name'               => _x( 'Properties', 'post type general name', 'casaview' ),
		'singular_name'      => _x( 'Property', 'post type singular name', 'casaview' ),
		'menu_name'          => _x( 'Properties', 'admin menu', 'casaview' ),
		'name_admin_bar'     => _x( 'Property', 'add new on admin bar', 'casaview' ),
		'add_new'            => _x( 'Add New', 'property', 'casaview' ),
		'add_new_item'       => __( 'Add New Property', 'casaview' ),
		'edit_item'          => __( 'Edit Property', 'casaview' ),
		'view_item'          => __( 'View Property', 'casaview' ),
		'all_items'          => __( 'All Properties', 'casaview' ),
		'search_items'       => __( 'Search Properties', 'casaview' ),
		'not_found'          => __( 'No properties found.', 'casaview' ),
		'not_found_in_trash' => __( 'No properties found in Trash.', 'casaview' ),
	);
	$property_args = array(
		'labels'             => $property_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'property' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-admin-home',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
	);
	register_post_type( 'property', $property_args );

	// 3.2 Agents CPT
	$agent_labels = array(
		'name'               => _x( 'Agents', 'post type general name', 'casaview' ),
		'singular_name'      => _x( 'Agent', 'post type singular name', 'casaview' ),
		'menu_name'          => _x( 'Agents', 'admin menu', 'casaview' ),
		'add_new'            => _x( 'Add New', 'agent', 'casaview' ),
		'add_new_item'       => __( 'Add New Agent', 'casaview' ),
		'edit_item'          => __( 'Edit Agent', 'casaview' ),
		'view_item'          => __( 'View Agent', 'casaview' ),
		'all_items'          => __( 'All Agents', 'casaview' ),
		'search_items'       => __( 'Search Agents', 'casaview' ),
	);
	$agent_args = array(
		'labels'             => $agent_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'agent' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-businessman',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
	);
	register_post_type( 'agent', $agent_args );

	// 3.3 Leads CPT (Inquiries)
	$lead_labels = array(
		'name'               => _x( 'Leads', 'post type general name', 'casaview' ),
		'singular_name'      => _x( 'Lead', 'post type singular name', 'casaview' ),
		'menu_name'          => _x( 'Leads', 'admin menu', 'casaview' ),
		'all_items'          => __( 'All Leads', 'casaview' ),
		'view_item'          => __( 'View Lead', 'casaview' ),
		'search_items'       => __( 'Search Leads', 'casaview' ),
	);
	$lead_args = array(
		'labels'             => $lead_labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-email-alt',
		'show_in_rest'       => false,
		'supports'           => array( 'title' ),
	);
	register_post_type( 'lead', $lead_args );

	// 3.5 Districts CPT
	$district_labels = array(
		'name'               => _x( 'Districts', 'post type general name', 'casaview' ),
		'singular_name'      => _x( 'District', 'post type singular name', 'casaview' ),
		'menu_name'          => _x( 'Districts', 'admin menu', 'casaview' ),
		'add_new'            => _x( 'Add New', 'district', 'casaview' ),
		'add_new_item'       => __( 'Add New District', 'casaview' ),
		'edit_item'          => __( 'Edit District', 'casaview' ),
		'view_item'          => __( 'View District', 'casaview' ),
		'all_items'          => __( 'All Districts', 'casaview' ),
		'search_items'       => __( 'Search Districts', 'casaview' ),
	);
	$district_args = array(
		'labels'             => $district_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'district' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 8,
		'menu_icon'          => 'dashicons-location-alt',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
	);
	register_post_type( 'district', $district_args );

	// 3.4 Custom Taxonomies
	// Property Type
	register_taxonomy( 'property_type', 'property', array(
		'labels' => array(
			'name'              => __( 'Property Types', 'casaview' ),
			'singular_name'     => __( 'Property Type', 'casaview' ),
			'search_items'      => __( 'Search Property Types', 'casaview' ),
			'all_items'         => __( 'All Property Types', 'casaview' ),
			'edit_item'         => __( 'Edit Property Type', 'casaview' ),
			'update_item'       => __( 'Update Property Type', 'casaview' ),
			'add_new_item'      => __( 'Add New Property Type', 'casaview' ),
			'menu_name'         => __( 'Property Types', 'casaview' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'property-type' ),
		'show_in_rest'      => true,
	) );

	// Property Category
	register_taxonomy( 'property_category', 'property', array(
		'labels' => array(
			'name'              => __( 'Property Categories', 'casaview' ),
			'singular_name'     => __( 'Property Category', 'casaview' ),
			'search_items'      => __( 'Search Property Categories', 'casaview' ),
			'all_items'         => __( 'All Property Categories', 'casaview' ),
			'edit_item'         => __( 'Edit Property Category', 'casaview' ),
			'update_item'       => __( 'Update Property Category', 'casaview' ),
			'add_new_item'      => __( 'Add New Property Category', 'casaview' ),
			'menu_name'         => __( 'Property Categories', 'casaview' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'property-category' ),
		'show_in_rest'      => true,
	) );

	// Amenities
	register_taxonomy( 'amenity', 'property', array(
		'labels' => array(
			'name'              => __( 'Amenities', 'casaview' ),
			'singular_name'     => __( 'Amenity', 'casaview' ),
			'search_items'      => __( 'Search Amenities', 'casaview' ),
			'all_items'         => __( 'All Amenities', 'casaview' ),
			'edit_item'         => __( 'Edit Amenity', 'casaview' ),
			'update_item'       => __( 'Update Amenity', 'casaview' ),
			'add_new_item'      => __( 'Add New Amenity', 'casaview' ),
			'menu_name'         => __( 'Amenities', 'casaview' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'amenity' ),
		'show_in_rest'      => true,
	) );

	// Property Location (India Location Database)
	register_taxonomy( 'property_location', 'property', array(
		'labels' => array(
			'name'              => __( 'Locations', 'casaview' ),
			'singular_name'     => __( 'Location', 'casaview' ),
			'search_items'      => __( 'Search Locations', 'casaview' ),
			'all_items'         => __( 'All Locations', 'casaview' ),
			'parent_item'       => __( 'Parent Location', 'casaview' ),
			'parent_item_colon' => __( 'Parent Location:', 'casaview' ),
			'edit_item'         => __( 'Edit Location', 'casaview' ),
			'update_item'       => __( 'Update Location', 'casaview' ),
			'add_new_item'      => __( 'Add New Location', 'casaview' ),
			'menu_name'         => __( 'Locations', 'casaview' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'property-location' ),
		'show_in_rest'      => true,
	) );
}
add_action( 'init', 'casaview_register_post_types_and_taxonomies' );

// Flush rewrite rules on theme activation
function casaview_flush_rewrites() {
	casaview_register_post_types_and_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'casaview_flush_rewrites' );

// ACF Slug auto-generation from Property Title when post is saved
function casaview_sync_property_slug( $post_id, $post, $update ) {
	if ( ! $post || $post->post_type !== 'property' ) {
		return;
	}

	// If a custom slug is being set via POST, do not overwrite it!
	if ( isset( $_POST['seo_property_slug'] ) || isset( $_POST['post_name'] ) ) {
		return;
	}

	// Prevent infinite loop
	remove_action( 'save_post_property', 'casaview_sync_property_slug', 10, 3 );
	
	if ( ! empty( $post->post_title ) ) {
		$slug = sanitize_title( $post->post_title );
		// Only sync the slug on creation or if it is currently empty
		if ( empty( $post->post_name ) || ! $update ) {
			if ( $post->post_name !== $slug ) {
				wp_update_post( array(
					'ID'        => $post_id,
					'post_name' => $slug,
				) );
			}
		}
	}
	add_action( 'save_post_property', 'casaview_sync_property_slug', 10, 3 );
}
add_action( 'save_post_property', 'casaview_sync_property_slug', 10, 3 );

// 4. Register Custom Fields using Advanced Custom Fields (ACF) PHP API
if ( function_exists('acf_add_local_field_group') ) {

	// Helper function for common section design settings
	if ( ! function_exists('casaview_get_general_section_fields') ) {
		function casaview_get_general_section_fields($prefix, $include_bg_image = true) {
			$fields = array(
				array(
					'key' => 'field_tab_' . $prefix . '_general',
					'label' => 'Design Settings',
					'type' => 'tab',
				),
				array(
					'key' => 'field_' . $prefix . '_top_padding',
					'label' => 'Top Padding (px)',
					'name' => $prefix . '_top_padding',
					'type' => 'number',
					'default_value' => 100,
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_' . $prefix . '_bottom_padding',
					'label' => 'Bottom Padding (px)',
					'name' => $prefix . '_bottom_padding',
					'type' => 'number',
					'default_value' => 100,
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_' . $prefix . '_bg_color',
					'label' => 'Background Color',
					'name' => $prefix . '_bg_color',
					'type' => 'color_picker',
					'wrapper' => array('width' => '50%'),
				),
			);
			if ($include_bg_image) {
				$fields[] = array(
					'key' => 'field_' . $prefix . '_bg_image',
					'label' => 'Background Image',
					'name' => $prefix . '_bg_image',
					'type' => 'image',
					'return_format' => 'url',
					'wrapper' => array('width' => '50%'),
				);
			}
			return $fields;
		}
	}

	$location_front_page = array(
		array(
			array(
				'param' => 'page_type',
				'operator' => '==',
				'value' => 'front_page',
			),
		),
	);

	// Unified Homepage Settings field group
	acf_add_local_field_group( array(
		'key' => 'group_homepage',
		'title' => 'Homepage Settings',
		'fields' => array(
			// ==========================================
			// 1. Hero Section Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_hero',
				'label' => '1. Hero Section',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_hero_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_hero_enable',
				'label' => 'Enable Hero Section',
				'name' => 'hero_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Toggle to show or hide the Hero Banner section on the homepage.',
			),
			array(
				'key' => 'field_hero_bg_image',
				'label' => 'Desktop Background Image',
				'name' => 'hero_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Upload the background image for desktop screen viewports.',
			),
			array(
				'key' => 'field_hero_mobile_bg_image',
				'label' => 'Mobile Background Image',
				'name' => 'hero_mobile_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Upload the background image for mobile screen viewports.',
			),
			array(
				'key' => 'field_hero_small_title',
				'label' => 'Small Title',
				'name' => 'hero_small_title',
				'type' => 'text',
				'default_value' => 'Boutique Real Estate Agency',
				'placeholder' => 'Enter section small title...',
				'instructions' => 'Small sub-heading shown above the main heading.',
			),
			array(
				'key' => 'field_hero_main_heading',
				'label' => 'Main Heading',
				'name' => 'hero_main_heading',
				'type' => 'text',
				'default_value' => 'Find Your Signature Address',
				'placeholder' => 'Enter section title...',
				'instructions' => 'The main bold headline of the Hero section.',
			),
			array(
				'key' => 'field_hero_description',
				'label' => 'Description',
				'name' => 'hero_description',
				'type' => 'textarea',
				'rows' => 3,
				'default_value' => 'Elite boutique properties, penthouses, and signature villas in the most exclusive postcodes.',
				'placeholder' => 'Enter short description...',
				'instructions' => 'Paragraph text block shown below the main heading.',
			),
			array(
				'key' => 'field_hero_search_enable',
				'label' => 'Enable Search Form',
				'name' => 'hero_search_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '33%'),
				'instructions' => 'Toggle to display the properties filter search bar on the Hero banner.',
			),
			array(
				'key' => 'field_hero_search_btn_text',
				'label' => 'Search Button Text',
				'name' => 'hero_search_btn_text',
				'type' => 'text',
				'default_value' => 'Search Properties',
				'placeholder' => 'Search Properties',
				'wrapper' => array('width' => '33%'),
				'instructions' => 'Text overlay on the submit search button.',
			),
			array(
				'key' => 'field_hero_cta_text',
				'label' => 'CTA Button Text',
				'name' => 'hero_cta_text',
				'type' => 'text',
				'default_value' => 'Explore Projects',
				'placeholder' => 'Explore Projects',
				'wrapper' => array('width' => '33%'),
				'instructions' => 'Label for the alternative Call To Action button.',
			),
			array(
				'key' => 'field_hero_cta_link',
				'label' => 'CTA Button Link',
				'name' => 'hero_cta_link',
				'type' => 'text',
				'default_value' => '#properties',
				'placeholder' => '#properties',
				'instructions' => 'URL link destination when CTA button is clicked (e.g. /properties/ or page anchor).',
			),
			// Hero Design Settings Tab (uses helper fields)
			array(
				'key' => 'field_tab_hero_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_hero_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'hero_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Section top padding in pixels.',
			),
			array(
				'key' => 'field_hero_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'hero_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Section bottom padding in pixels.',
			),
			array(
				'key' => 'field_hero_bg_color',
				'label' => 'Background Color',
				'name' => 'hero_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Optional section background color.',
			),
			array(
				'key' => 'field_hero_text_align',
				'label' => 'Text Alignment',
				'name' => 'hero_text_align',
				'type' => 'select',
				'choices' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				),
				'default_value' => 'left',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_hero_container_width',
				'label' => 'Container Width (px)',
				'name' => 'hero_container_width',
				'type' => 'number',
				'default_value' => 1200,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_hero_image_radius',
				'label' => 'Image Radius (px)',
				'name' => 'hero_image_radius',
				'type' => 'number',
				'default_value' => 0,
				'wrapper' => array('width' => '50%'),
			),
			// Hero Advanced Settings Tab
			array(
				'key' => 'field_tab_hero_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_hero_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'hero_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_hero_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'hero_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_hero_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'hero_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_hero_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'hero_animation_delay',
				'type' => 'number',
				'default_value' => 0,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 2. Featured Properties Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_featured',
				'label' => '2. Featured Properties',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_featured_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_featured_enable',
				'label' => 'Enable Featured Section',
				'name' => 'featured_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Toggle to show or hide the Featured Properties section.',
			),
			array(
				'key' => 'field_featured_title',
				'label' => 'Section Title',
				'name' => 'featured_title',
				'type' => 'text',
				'default_value' => 'Featured Properties',
				'placeholder' => 'Enter section title...',
				'instructions' => 'The main title of the Featured Properties section.',
			),
			array(
				'key' => 'field_featured_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'featured_subtitle',
				'type' => 'text',
				'default_value' => 'Explore our elite handpicked properties in key communities.',
				'placeholder' => 'Enter short description...',
				'instructions' => 'The subtitle displayed below the title.',
			),
			array(
				'key' => 'field_featured_query_type',
				'label' => 'Property Query Type',
				'name' => 'featured_query_type',
				'type' => 'select',
				'choices' => array(
					'latest' => 'Latest Properties',
					'manual' => 'Manual Selection',
				),
				'default_value' => 'latest',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Choose whether to query latest properties automatically or select manually.',
			),
			array(
				'key' => 'field_featured_posts_count',
				'label' => 'Number of Properties',
				'name' => 'featured_posts_count',
				'type' => 'number',
				'default_value' => 6,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_featured_query_type',
							'operator' => '==',
							'value' => 'latest',
						),
					),
				),
				'wrapper' => array('width' => '50%'),
				'instructions' => 'The number of properties to fetch when query type is Latest.',
			),
			array(
				'key' => 'field_featured_manual_properties',
				'label' => 'Manual Property Selection',
				'name' => 'featured_manual_properties',
				'type' => 'relationship',
				'post_type' => array('property'),
				'filters' => array('search'),
				'return_format' => 'id',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_featured_query_type',
							'operator' => '==',
							'value' => 'manual',
						),
					),
				),
				'instructions' => 'Select specific properties manually to display in this section.',
			),
			array(
				'key' => 'field_featured_view_all_text',
				'label' => 'View All Button Text',
				'name' => 'featured_view_all_text',
				'type' => 'text',
				'default_value' => 'View All Properties',
				'placeholder' => 'View All Properties',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'Label of the archive redirect button.',
			),
			array(
				'key' => 'field_featured_view_all_link',
				'label' => 'View All Link',
				'name' => 'featured_view_all_link',
				'type' => 'text',
				'default_value' => '/properties/',
				'placeholder' => '/properties/',
				'wrapper' => array('width' => '50%'),
				'instructions' => 'URL link destination when View All button is clicked.',
			),
			// Featured Design Settings Tab
			array(
				'key' => 'field_tab_featured_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_featured_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'featured_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'featured_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_bg_color',
				'label' => 'Background Color',
				'name' => 'featured_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_bg_image',
				'label' => 'Background Image',
				'name' => 'featured_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_text_align',
				'label' => 'Text Alignment',
				'name' => 'featured_text_align',
				'type' => 'select',
				'choices' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				),
				'default_value' => 'center',
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_featured_container_width',
				'label' => 'Container Width (px)',
				'name' => 'featured_container_width',
				'type' => 'number',
				'default_value' => 1200,
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_featured_card_gap',
				'label' => 'Card Gap (px)',
				'name' => 'featured_card_gap',
				'type' => 'number',
				'default_value' => 30,
				'wrapper' => array('width' => '34%'),
			),
			array(
				'key' => 'field_featured_image_radius',
				'label' => 'Image Radius (px)',
				'name' => 'featured_image_radius',
				'type' => 'number',
				'default_value' => 12,
				'wrapper' => array('width' => '50%'),
			),
			// Featured Advanced Settings Tab
			array(
				'key' => 'field_tab_featured_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_featured_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'featured_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'featured_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'featured_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_featured_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'featured_animation_delay',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 3. Most Trending Projects Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_trending',
				'label' => '3. Most Trending Projects',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_trending_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_trending_enable',
				'label' => 'Enable Section',
				'name' => 'trending_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Toggle to show or hide the Most Trending Projects section.',
			),
			array(
				'key' => 'field_trending_title',
				'label' => 'Section Title',
				'name' => 'trending_title',
				'type' => 'text',
				'default_value' => 'Most Trending Projects',
				'placeholder' => 'Enter section title...',
				'instructions' => 'Title of the Trending Projects section.',
			),
			array(
				'key' => 'field_trending_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'trending_subtitle',
				'type' => 'text',
				'default_value' => 'Discover our handpicked premium properties for sale and rent.',
				'placeholder' => 'Enter short description...',
				'instructions' => 'Subtitle text block of the section.',
			),
			array(
				'key' => 'field_trending_filter_enable',
				'label' => 'Show Filter Tabs',
				'name' => 'trending_filter_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Show taxonomy category filter tabs above the slider.',
			),
			array(
				'key' => 'field_trending_posts_count',
				'label' => 'Properties Per Page',
				'name' => 'trending_posts_count',
				'type' => 'number',
				'default_value' => 10,
				'instructions' => 'Total properties to query per page load.',
			),
			// Trending Design Settings
			array(
				'key' => 'field_tab_trending_design',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_trending_top_padding',
				'label' => 'Section Top Padding (px)',
				'name' => 'trending_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_bottom_padding',
				'label' => 'Section Bottom Padding (px)',
				'name' => 'trending_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_bg_color',
				'label' => 'Background Color',
				'name' => 'trending_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_card_gap',
				'label' => 'Card Gap (px)',
				'name' => 'trending_card_gap',
				'type' => 'number',
				'default_value' => 32,
				'wrapper' => array('width' => '50%'),
			),
			// Trending Slider Settings
			array(
				'key' => 'field_tab_trending_slider',
				'label' => 'Slider Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_trending_slider_enable',
				'label' => 'Enable Slider',
				'name' => 'trending_slider_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_trending_autoplay_enable',
				'label' => 'Autoplay',
				'name' => 'trending_autoplay_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_autoplay_speed',
				'label' => 'Autoplay Speed (ms)',
				'name' => 'trending_autoplay_speed',
				'type' => 'number',
				'default_value' => 5000,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
						array(
							'field' => 'field_trending_autoplay_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_loop_enable',
				'label' => 'Loop Slides',
				'name' => 'trending_loop_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_pause_on_hover',
				'label' => 'Pause On Hover',
				'name' => 'trending_pause_on_hover',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_nav_arrows',
				'label' => 'Show Navigation Arrows',
				'name' => 'trending_nav_arrows',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_trending_pag_dots',
				'label' => 'Show Pagination Dots',
				'name' => 'trending_pag_dots',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_trending_center_slides',
				'label' => 'Center Slides',
				'name' => 'trending_center_slides',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_trending_slider_enable',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array('width' => '34%'),
			),
			array(
				'key' => 'field_trending_slides_desktop',
				'label' => 'Desktop Slides Per View',
				'name' => 'trending_slides_desktop',
				'type' => 'number',
				'default_value' => 4,
				'wrapper' => array('width' => '20%'),
			),
			array(
				'key' => 'field_trending_slides_laptop',
				'label' => 'Laptop Slides Per View',
				'name' => 'trending_slides_laptop',
				'type' => 'number',
				'default_value' => 4,
				'wrapper' => array('width' => '20%'),
			),
			array(
				'key' => 'field_trending_slides_tablet',
				'label' => 'Tablet Slides Per View',
				'name' => 'trending_slides_tablet',
				'type' => 'number',
				'default_value' => 2,
				'wrapper' => array('width' => '20%'),
			),
			array(
				'key' => 'field_trending_slides_mobile',
				'label' => 'Mobile Slides Per View',
				'name' => 'trending_slides_mobile',
				'type' => 'number',
				'default_value' => 1,
				'wrapper' => array('width' => '20%'),
			),
			array(
				'key' => 'field_trending_slides_space',
				'label' => 'Space Between Slides (px)',
				'name' => 'trending_slides_space',
				'type' => 'number',
				'default_value' => 32,
				'wrapper' => array('width' => '20%'),
			),
			// Trending Advanced Tab
			array(
				'key' => 'field_tab_trending_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_trending_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'trending_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'trending_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'trending_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_trending_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'trending_animation_delay',
				'type' => 'number',
				'default_value' => 200,
				'wrapper' => array('width' => '50%'),
			),



			// ==========================================
			// 5. Explore Districts Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_districts',
				'label' => '5. Explore Districts',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_districts_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_districts_enable',
				'label' => 'Enable Districts Section',
				'name' => 'districts_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_districts_marquee',
				'label' => 'Marquee Text Overlay',
				'name' => 'districts_marquee',
				'type' => 'text',
				'default_value' => 'SIGNATURE VILLA Ã¢â‚¬Â¢ LUXURY APARTMENT Ã¢â‚¬Â¢ PENTHOUSE Ã¢â‚¬Â¢ SIGNATURE VILLA',
				'placeholder' => 'Enter marquee text...',
				'instructions' => 'The scrolling marquee text banner above the section.',
			),
			array(
				'key' => 'field_districts_title',
				'label' => 'Section Title',
				'name' => 'districts_title',
				'type' => 'text',
				'default_value' => 'Explore Districts',
				'placeholder' => 'Enter section title...',
			),
			array(
				'key' => 'field_districts_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'districts_subtitle',
				'type' => 'text',
				'default_value' => 'Find local communities with signature properties carefully selected.',
				'placeholder' => 'Enter short description...',
			),
			array(
				'key' => 'field_districts_list',
				'label' => 'Districts Repeater',
				'name' => 'districts_list',
				'type' => 'repeater',
				'layout' => 'block',
				'collapsed' => 'field_district_name',
				'button_label' => '+ Add District',
				'sub_fields' => array(
					array(
						'key' => 'field_district_name',
						'label' => 'District Name',
						'name' => 'd_name',
						'type' => 'text',
						'placeholder' => 'e.g. Ernakulam',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_district_image',
						'label' => 'District Image',
						'name' => 'd_image',
						'type' => 'image',
						'return_format' => 'url',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_district_count',
						'label' => 'Property Count Override',
						'name' => 'd_count',
						'type' => 'number',
						'placeholder' => 'Leave blank to compute dynamically',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_district_btn_text',
						'label' => 'Button Text',
						'name' => 'd_btn_text',
						'type' => 'text',
						'default_value' => 'Explore',
						'placeholder' => 'Explore',
						'wrapper' => array('width' => '50%'),
					),
					array(
						'key' => 'field_district_btn_link',
						'label' => 'Button Link',
						'name' => 'd_btn_link',
						'type' => 'text',
						'placeholder' => '/properties/?district=ernakulam',
						'wrapper' => array('width' => '50%'),
					),
				),
			),
			// Districts Design Settings
			array(
				'key' => 'field_tab_districts_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_districts_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'districts_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'districts_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_bg_color',
				'label' => 'Background Color',
				'name' => 'districts_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_bg_image',
				'label' => 'Background Image',
				'name' => 'districts_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_image_radius',
				'label' => 'District Card Image Radius (px)',
				'name' => 'districts_image_radius',
				'type' => 'number',
				'default_value' => 16,
				'wrapper' => array('width' => '50%'),
			),
			// Districts Slider/Marquee settings Tab
			array(
				'key' => 'field_tab_districts_slider',
				'label' => 'Slider Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_districts_marquee_enable',
				'label' => 'Enable Marquee',
				'name' => 'districts_marquee_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_districts_marquee_speed',
				'label' => 'Marquee Speed',
				'name' => 'districts_marquee_speed',
				'type' => 'number',
				'default_value' => 35,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_marquee_pause_hover',
				'label' => 'Pause On Hover',
				'name' => 'districts_marquee_pause_hover',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_marquee_item_gap',
				'label' => 'Item Gap',
				'name' => 'districts_marquee_item_gap',
				'type' => 'number',
				'default_value' => 60,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_show_property_count',
				'label' => 'Show Property Count',
				'name' => 'districts_show_property_count',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_marquee_top_padding',
				'label' => 'Marquee Top Padding',
				'name' => 'districts_marquee_top_padding',
				'type' => 'number',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_marquee_bottom_padding',
				'label' => 'Marquee Bottom Padding',
				'name' => 'districts_marquee_bottom_padding',
				'type' => 'number',
				'wrapper' => array('width' => '50%'),
			),
			// Districts Advanced Settings
			array(
				'key' => 'field_tab_districts_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_districts_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'districts_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'districts_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'districts_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_districts_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'districts_animation_delay',
				'type' => 'number',
				'default_value' => 400,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 6. Our Services Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_our_services',
				'label' => '6. Our Services',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_our_services_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_our_services_enable',
				'label' => 'Enable Services Section',
				'name' => 'our_services_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Toggle to show or hide the new Services section on the homepage.',
			),
			array(
				'key' => 'field_our_services_title',
				'label' => 'Section Title',
				'name' => 'our_services_title',
				'type' => 'text',
				'default_value' => 'Our Services',
				'placeholder' => 'Enter section title...',
				'instructions' => 'Headline for the Services section.',
			),
			array(
				'key' => 'field_our_services_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'our_services_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'default_value' => 'Discover our range of bespoke property services.',
				'placeholder' => 'Enter short description...',
				'instructions' => 'Subtitle text description block.',
			),
			array(
				'key' => 'field_our_services_repeater',
				'label' => 'Services Repeater',
				'name' => 'our_services_repeater',
				'type' => 'repeater',
				'collapsed' => 'field_our_service_name',
				'min' => 1,
				'layout' => 'table',
				'button_label' => '+ Add Service',
				'sub_fields' => array(
					array(
						'key' => 'field_our_service_name',
						'label' => 'Service Name (Tab Title)',
						'name' => 'our_service_name',
						'type' => 'text',
						'placeholder' => 'e.g. Commercial Rent',
						'instructions' => 'Short name used on the sidebar tabs.',
					),
					array(
						'key' => 'field_our_service_icon',
						'label' => 'Service Icon (Image URL)',
						'name' => 'our_service_icon',
						'type' => 'image',
						'return_format' => 'url',
						'instructions' => 'Optional custom tab icon image.',
					),
					array(
						'key' => 'field_our_service_image',
						'label' => 'Service Featured Image',
						'name' => 'our_service_image',
						'type' => 'image',
						'return_format' => 'url',
						'instructions' => 'Featured image shown in the center column when active.',
					),
					array(
						'key' => 'field_our_service_title',
						'label' => 'Service Title',
						'name' => 'our_service_title',
						'type' => 'text',
						'placeholder' => 'Enter service title...',
						'instructions' => 'Headline for the service details column.',
					),
					array(
						'key' => 'field_our_service_btn_text',
						'label' => 'Button Text',
						'name' => 'our_service_btn_text',
						'type' => 'text',
						'placeholder' => 'View Details',
						'instructions' => 'Call to action button label.',
					),
					array(
						'key' => 'field_our_service_btn_url',
						'label' => 'Button URL',
						'name' => 'our_service_btn_url',
						'type' => 'text',
						'placeholder' => 'e.g. /properties/ or #',
						'instructions' => 'Destination link for the action button.',
					),
					array(
						'key' => 'field_our_service_description',
						'label' => 'Service Description',
						'name' => 'our_service_description',
						'type' => 'textarea',
						'rows' => 3,
						'placeholder' => 'Enter service description...',
						'instructions' => 'Description paragraph detail text block.',
					),
				),
			),
			array(
				'key' => 'field_tab_our_services_design',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_our_services_bg_color',
				'label' => 'Background Color',
				'name' => 'our_services_bg_color',
				'type' => 'color_picker',
				'default_value' => '#F7F6F2',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_our_services_bg_image',
				'label' => 'Background Image',
				'name' => 'our_services_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_our_services_top_padding',
				'label' => 'Section Top Padding (px)',
				'name' => 'our_services_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_our_services_bottom_padding',
				'label' => 'Section Bottom Padding (px)',
				'name' => 'our_services_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_tab_our_services_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_our_services_card_gap',
				'label' => 'Card Gap (px)',
				'name' => 'our_services_card_gap',
				'type' => 'number',
				'default_value' => 40,
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_our_services_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'our_services_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '33%'),
			),
			array(
				'key' => 'field_our_services_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'our_services_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '34%'),
			),
			array(
				'key' => 'field_our_services_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'our_services_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_our_services_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'our_services_animation_delay',
				'type' => 'number',
				'default_value' => 300,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 7. Apartment Types Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_apartments',
				'label' => '7. Apartment Types',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_apartments_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_apartments_enable',
				'label' => 'Enable Apartments Section',
				'name' => 'apartments_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_apartments_title',
				'label' => 'Section Title',
				'name' => 'apartments_title',
				'type' => 'text',
				'default_value' => 'Apartment Types',
				'placeholder' => 'Enter section title...',
			),
			array(
				'key' => 'field_apartments_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'apartments_subtitle',
				'type' => 'text',
				'default_value' => 'Browse signature apartments by types and luxury configuration.',
				'placeholder' => 'Enter short description...',
			),
			array(
				'key' => 'field_apartments_list',
				'label' => 'Apartment Types Repeater',
				'name' => 'apartments_list',
				'type' => 'repeater',
				'layout' => 'block',
				'collapsed' => 'field_apt_name',
				'button_label' => '+ Add Apartment Type',
				'sub_fields' => array(
					array(
						'key' => 'field_apt_image',
						'label' => 'Image',
						'name' => 'apt_image',
						'type' => 'image',
						'return_format' => 'url',
						'wrapper' => array('width' => '50%'),
					),
					array(
						'key' => 'field_apt_name',
						'label' => 'Name',
						'name' => 'apt_name',
						'type' => 'text',
						'placeholder' => 'e.g. Luxury Villa',
						'wrapper' => array('width' => '50%'),
					),
					array(
						'key' => 'field_apt_description',
						'label' => 'Description',
						'name' => 'apt_description',
						'type' => 'textarea',
						'rows' => 2,
						'placeholder' => 'Enter short description...',
						'wrapper' => array('width' => '100%'),
					),
					array(
						'key' => 'field_apt_link',
						'label' => 'Link',
						'name' => 'apt_link',
						'type' => 'text',
						'placeholder' => '/properties/?type=apartment',
						'wrapper' => array('width' => '100%'),
					),
				),
			),
			// Apartments Design Settings
			array(
				'key' => 'field_tab_apartments_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_apartments_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'apartments_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'apartments_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_bg_color',
				'label' => 'Background Color',
				'name' => 'apartments_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_bg_image',
				'label' => 'Background Image',
				'name' => 'apartments_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			// Apartments Advanced Settings
			array(
				'key' => 'field_tab_apartments_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_apartments_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'apartments_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'apartments_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'apartments_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_apartments_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'apartments_animation_delay',
				'type' => 'number',
				'default_value' => 600,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 8. Meet Our Agents Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_agents',
				'label' => '8. Meet Our Agents',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_agents_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_agents_enable',
				'label' => 'Enable Agents Section',
				'name' => 'agents_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_agents_title',
				'label' => 'Section Title',
				'name' => 'agents_title',
				'type' => 'text',
				'default_value' => 'Meet Our Agents',
				'placeholder' => 'Enter section title...',
			),
			array(
				'key' => 'field_agents_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'agents_subtitle',
				'type' => 'text',
				'default_value' => 'Connect with our elite team of real estate advisors.',
				'placeholder' => 'Enter short description...',
			),
			array(
				'key' => 'field_agents_selection',
				'label' => 'Agent Relationship Field',
				'name' => 'agents_selection',
				'type' => 'relationship',
				'post_type' => array('agent'),
				'filters' => array('search'),
				'return_format' => 'id',
				'instructions' => 'Manually select agents to be listed in this section.',
			),
			// Agents Design Settings
			array(
				'key' => 'field_tab_agents_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_agents_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'agents_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'agents_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_bg_color',
				'label' => 'Background Color',
				'name' => 'agents_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_bg_image',
				'label' => 'Background Image',
				'name' => 'agents_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			// Agents Advanced Settings
			array(
				'key' => 'field_tab_agents_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_agents_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'agents_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'agents_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'agents_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_agents_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'agents_animation_delay',
				'type' => 'number',
				'default_value' => 700,
				'wrapper' => array('width' => '50%'),
			),

			// ==========================================
			// 9. Testimonials Accordion
			// ==========================================
			array(
				'key' => 'field_accordion_testimonials',
				'label' => '9. Testimonials',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_testimonials_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_testimonials_enable',
				'label' => 'Enable Testimonials Section',
				'name' => 'testimonials_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_testimonials_title',
				'label' => 'Section Title',
				'name' => 'testimonials_title',
				'type' => 'text',
				'default_value' => 'Client Testimonials',
				'placeholder' => 'Enter section title...',
			),
			array(
				'key' => 'field_testimonials_subtitle',
				'label' => 'Section Subtitle',
				'name' => 'testimonials_subtitle',
				'type' => 'text',
				'default_value' => 'Read reviews from our premium lifestyle partners.',
				'placeholder' => 'Enter short description...',
			),
			array(
				'key' => 'field_testimonials_list',
				'label' => 'Testimonials Repeater',
				'name' => 'testimonials_list',
				'type' => 'repeater',
				'layout' => 'block',
				'collapsed' => 'field_t_name',
				'button_label' => '+ Add Testimonial',
				'sub_fields' => array(
					array(
						'key' => 'field_t_photo',
						'label' => 'Client Photo',
						'name' => 't_photo',
						'type' => 'image',
						'return_format' => 'url',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_t_name',
						'label' => 'Client Name',
						'name' => 't_name',
						'type' => 'text',
						'placeholder' => 'e.g. John Mathew',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_t_role',
						'label' => 'Designation',
						'name' => 't_role',
						'type' => 'text',
						'placeholder' => 'e.g. Premium Partner',
						'wrapper' => array('width' => '33%'),
					),
					array(
						'key' => 'field_t_rating',
						'label' => 'Rating (Stars)',
						'name' => 't_rating',
						'type' => 'select',
						'choices' => array(
							'5' => '5 Stars',
							'4' => '4 Stars',
							'3' => '3 Stars',
							'2' => '2 Stars',
							'1' => '1 Star',
						),
						'default_value' => '5',
						'wrapper' => array('width' => '100%'),
					),
					array(
						'key' => 'field_t_quote',
						'label' => 'Review',
						'name' => 't_quote',
						'type' => 'textarea',
						'rows' => 3,
						'placeholder' => 'Write client review...',
						'wrapper' => array('width' => '100%'),
					),
				),
			),
			// Testimonials Design Settings
			array(
				'key' => 'field_tab_testimonials_general',
				'label' => 'Design Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_testimonials_top_padding',
				'label' => 'Top Padding (px)',
				'name' => 'testimonials_top_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_bottom_padding',
				'label' => 'Bottom Padding (px)',
				'name' => 'testimonials_bottom_padding',
				'type' => 'number',
				'default_value' => 100,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_bg_color',
				'label' => 'Background Color',
				'name' => 'testimonials_bg_color',
				'type' => 'color_picker',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_bg_image',
				'label' => 'Background Image',
				'name' => 'testimonials_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'wrapper' => array('width' => '50%'),
			),
			// Testimonials Slider Tab
			array(
				'key' => 'field_tab_testimonials_slider',
				'label' => 'Slider Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_testimonials_slider_enable',
				'label' => 'Enable Slider View',
				'name' => 'testimonials_slider_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'instructions' => 'Toggle on to display testimonials in a swipeable slider, or off to show a static grid.',
			),
			// Testimonials Advanced Settings
			array(
				'key' => 'field_tab_testimonials_advanced',
				'label' => 'Advanced Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_testimonials_custom_id',
				'label' => 'Custom Section ID',
				'name' => 'testimonials_custom_id',
				'type' => 'text',
				'placeholder' => 'Enter custom section HTML ID...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_custom_class',
				'label' => 'Custom CSS Class',
				'name' => 'testimonials_custom_class',
				'type' => 'text',
				'placeholder' => 'Enter custom CSS class...',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_animation_enable',
				'label' => 'Animation Enable',
				'name' => 'testimonials_animation_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_testimonials_animation_delay',
				'label' => 'Animation Delay (ms)',
				'name' => 'testimonials_animation_delay',
				'type' => 'number',
				'default_value' => 800,
				'wrapper' => array('width' => '50%'),
			),



			// ==========================================
			// 11. CTA Section / Footer CTA Accordion (Redesigned)
			// ==========================================
			array(
				'key' => 'field_accordion_contact',
				'label' => '8. CTA Section (Redesigned)',
				'type' => 'accordion',
				'open' => 0,
				'multi_expand' => 1,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_tab_contact_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_contact_enable',
				'label' => 'Enable Contact CTA Section',
				'name' => 'contact_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_redesigned_cta_bg_image',
				'label' => 'Background Image',
				'name' => 'redesigned_cta_bg_image',
				'type' => 'image',
				'return_format' => 'url',
				'instructions' => 'Large background image shown on the section.',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_badge',
				'label' => 'Optional Badge/Icon Image',
				'name' => 'redesigned_cta_badge',
				'type' => 'image',
				'return_format' => 'url',
				'instructions' => 'Small icon/badge displayed above the main heading.',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_heading',
				'label' => 'Main Heading',
				'name' => 'redesigned_cta_heading',
				'type' => 'text',
				'default_value' => 'Ready to Find Your Dream Home?',
				'placeholder' => 'e.g. Ready to Find Your Dream Home?',
				'instructions' => 'Main headline statement.',
			),
			array(
				'key' => 'field_redesigned_cta_description',
				'label' => 'Description Text',
				'name' => 'redesigned_cta_description',
				'type' => 'textarea',
				'rows' => 3,
				'default_value' => 'Speak with one of our boutique real estate consultants today and discover luxury lifestyle listings.',
				'placeholder' => 'Enter description...',
				'instructions' => 'Subtext paragraph details.',
			),
			array(
				'key' => 'field_redesigned_cta_btn_primary_text',
				'label' => 'Primary Button Text',
				'name' => 'redesigned_cta_btn_primary_text',
				'type' => 'text',
				'default_value' => 'Contact Us',
				'placeholder' => 'e.g. Contact Us',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_btn_primary_url',
				'label' => 'Primary Button URL',
				'name' => 'redesigned_cta_btn_primary_url',
				'type' => 'text',
				'default_value' => '/contact/',
				'placeholder' => 'e.g. /contact/',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_btn_secondary_text',
				'label' => 'Secondary Button Text',
				'name' => 'redesigned_cta_btn_secondary_text',
				'type' => 'text',
				'default_value' => 'Browse Properties',
				'placeholder' => 'e.g. Browse Properties',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_btn_secondary_url',
				'label' => 'Secondary Button URL',
				'name' => 'redesigned_cta_btn_secondary_url',
				'type' => 'text',
				'default_value' => '/properties/',
				'placeholder' => 'e.g. /properties/',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_cta_overlay',
				'label' => 'Optional Overlay Settings (opacity, color)',
				'name' => 'redesigned_cta_overlay',
				'type' => 'text',
				'default_value' => 'rgba(11, 12, 16, 0.7)',
				'placeholder' => 'e.g. rgba(11, 12, 16, 0.7)',
				'instructions' => 'Overlay background color for readability.',
			),
			// Accordion endpoint to clean up admin page styling
			array(
				'key' => 'field_accordion_homepage_endpoint',
				'label' => 'Section Endpoint',
				'type' => 'accordion',
				'endpoint' => 1,
			)
		),
		'location' => $location_front_page,
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	) );

	// Standalone FAQ Repeater Field Group
	acf_add_local_field_group( array(
		'key' => 'group_homepage_faq_repeater',
		'title' => 'Home FAQ Section',
		'fields' => array(
			array(
				'key' => 'field_tab_faq_content',
				'label' => 'Content Settings',
				'type' => 'tab',
			),
			array(
				'key' => 'field_redesigned_faq_title',
				'label' => 'Section Title',
				'name' => 'redesigned_faq_title',
				'type' => 'text',
				'default_value' => 'Frequently Asked Questions',
				'placeholder' => 'Enter section title...',
				'instructions' => 'Headline for the FAQ section.',
			),
			array(
				'key' => 'field_redesigned_faq_subtitle',
				'label' => 'Section Subtitle/Description',
				'name' => 'redesigned_faq_subtitle',
				'type' => 'textarea',
				'rows' => 3,
				'default_value' => 'Everything you need to know about finding your dream home, renting with confidence, or seamlessly managing your property – all made simple, transparent, and hassle-free.',
				'placeholder' => 'Enter section description...',
				'instructions' => 'Introductory paragraph text block.',
			),
			array(
				'key' => 'field_redesigned_faq_repeater',
				'label' => 'FAQ Items',
				'name' => 'redesigned_faq_repeater',
				'type' => 'repeater',
				'collapsed' => 'field_redesigned_faq_question',
				'layout' => 'block',
				'button_label' => '+ Add FAQ',
				'sub_fields' => array(
					array(
						'key' => 'field_redesigned_faq_question',
						'label' => 'Question',
						'name' => 'question',
						'type' => 'text',
						'placeholder' => 'Enter the question...',
						'instructions' => 'The question asked.',
					),
					array(
						'key' => 'field_redesigned_faq_answer',
						'label' => 'Answer',
						'name' => 'answer',
						'type' => 'wysiwyg',
						'tabs' => 'visual',
						'toolbar' => 'basic',
						'media_upload' => 0,
						'delay' => 0,
						'placeholder' => 'Enter the answer...',
						'instructions' => 'The answer returned.',
					),
				),
			),
			array(
				'key' => 'field_tab_faq_cta_settings',
				'label' => 'Contact Card',
				'type' => 'tab',
			),
			array(
				'key' => 'field_redesigned_faq_cta_text',
				'label' => 'CTA Button Text',
				'name' => 'redesigned_faq_cta_text',
				'type' => 'text',
				'default_value' => 'Contact Us',
				'placeholder' => 'e.g. Contact Us',
				'instructions' => 'Text overlay on the contact action button.',
				'wrapper' => array('width' => '50%'),
			),
			array(
				'key' => 'field_redesigned_faq_cta_url',
				'label' => 'CTA Button URL',
				'name' => 'redesigned_faq_cta_url',
				'type' => 'text',
				'default_value' => '/contact/',
				'placeholder' => 'e.g. /contact/',
				'instructions' => 'Destination link for the action button.',
				'wrapper' => array('width' => '50%'),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				),
			),
			array(
				array(
					'param' => 'page',
					'operator' => '==',
					'value' => '93',
				),
			),
		),
		'menu_order' => 12,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	) );

	// District Settings taxonomy custom fields
	acf_add_local_field_group( array(
		'key' => 'group_district_settings',
		'title' => 'District Settings',
		'fields' => array(
			array(
				'key' => 'field_district_featured_image',
				'label' => 'District Featured Image',
				'name' => 'district_featured_image',
				'type' => 'image',
				'return_format' => 'url',
			),
			array(
				'key' => 'field_district_subtitle',
				'label' => 'District Subtitle',
				'name' => 'district_subtitle',
				'type' => 'text',
			),
			array(
				'key' => 'field_district_accent_color',
				'label' => 'District Accent Color',
				'name' => 'district_accent_color',
				'type' => 'color_picker',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'property_location',
				),
			),
		),
	) );

	// Helper for icon fields
	if ( ! function_exists('get_casaview_icon_fields') ) {
		function get_casaview_icon_fields($prefix) {
			return array(
				array(
					'key' => 'field_' . $prefix . '_type',
					'label' => 'Icon Type',
					'name' => $prefix . '_type',
					'type' => 'select',
					'choices' => array(
						'class' => 'Icon Library Class (FontAwesome)',
						'svg' => 'SVG File Upload',
						'image' => 'PNG/JPG Image Upload',
					),
					'default_value' => 'class',
					'wrapper' => array('width' => '25%'),
				),
				array(
					'key' => 'field_' . $prefix . '_class',
					'label' => 'Icon Class Name',
					'name' => $prefix . '_class',
					'type' => 'text',
					'placeholder' => 'e.g. fa-solid fa-bed',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_' . $prefix . '_type',
								'operator' => '==',
								'value' => 'class',
							),
						),
					),
					'wrapper' => array('width' => '75%'),
				),
				array(
					'key' => 'field_' . $prefix . '_svg',
					'label' => 'SVG File',
					'name' => $prefix . '_svg',
					'type' => 'file',
					'return_format' => 'url',
					'mime_types' => 'svg',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_' . $prefix . '_type',
								'operator' => '==',
								'value' => 'svg',
							),
						),
					),
					'wrapper' => array('width' => '75%'),
				),
				array(
					'key' => 'field_' . $prefix . '_image',
					'label' => 'PNG/JPG Image',
					'name' => $prefix . '_image',
					'type' => 'image',
					'return_format' => 'url',
					'mime_types' => 'png,jpg,jpeg',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_' . $prefix . '_type',
								'operator' => '==',
								'value' => 'image',
							),
						),
					),
					'wrapper' => array('width' => '75%'),
				),
			);
		}
	}

	// Register Global Options page fields
	$icons_keys = array(
		'bedroom' => 'Bedroom',
		'bathroom' => 'Bathroom',
		'area' => 'Area',
		'parking' => 'Parking',
		'pool' => 'Swimming Pool',
		'security' => 'Security',
		'cctv' => 'CCTV',
		'gym' => 'Gym',
		'garden' => 'Garden',
		'lift' => 'Lift',
		'water' => 'Water Connection',
		'electricity' => 'Electricity',
		'road' => 'Road Access'
	);

	$global_icon_fields = array();
	foreach ($icons_keys as $key => $label) {
		$global_icon_fields[] = array(
			'key' => 'field_separator_global_' . $key,
			'label' => $label . ' Icon Settings',
			'type' => 'message',
			'message' => 'Configure global default icon for ' . $label,
		);
		$global_icon_fields = array_merge($global_icon_fields, get_casaview_icon_fields('global_icon_' . $key));
	}

	acf_add_local_field_group( array(
		'key' => 'group_casaview_global_icons',
		'title' => 'Global Icons Management',
		'fields' => $global_icon_fields,
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'casaview-settings',
				),
			),
		),
	) );

	// Featured Listings Slider & Category Tabs Settings
	acf_add_local_field_group( array(
		'key' => 'group_casaview_featured_listings',
		'title' => 'Featured Listings Settings',
		'fields' => array(
			array(
				'key' => 'field_featured_slider_enable',
				'label' => 'Enable Slider',
				'name' => 'featured_listings_enable_slider',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_auto_slide',
				'label' => 'Auto Slide On/Off',
				'name' => 'featured_listings_auto_slide',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_autoplay_speed',
				'label' => 'Autoplay Speed (in ms)',
				'name' => 'featured_listings_autoplay_speed',
				'type' => 'number',
				'default_value' => 5000,
				'min' => 500,
			),
			array(
				'key' => 'field_featured_slide_speed',
				'label' => 'Slider Speed (in ms)',
				'name' => 'featured_listings_slide_speed',
				'type' => 'number',
				'default_value' => 800,
				'min' => 100,
			),
			array(
				'key' => 'field_featured_loop',
				'label' => 'Enable Loop',
				'name' => 'featured_listings_loop',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_navigation',
				'label' => 'Navigation Arrows On/Off',
				'name' => 'featured_listings_navigation',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_pagination',
				'label' => 'Pagination Dots On/Off',
				'name' => 'featured_listings_pagination',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 0,
			),
			array(
				'key' => 'field_featured_touch_swipe',
				'label' => 'Touch Swipe Enable/Disable',
				'name' => 'featured_listings_touch_swipe',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_mouse_drag',
				'label' => 'Mouse Drag Enable/Disable',
				'name' => 'featured_listings_mouse_drag',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_num_cards_desktop',
				'label' => 'Number of Cards (Desktop)',
				'name' => 'featured_listings_num_cards_desktop',
				'type' => 'select',
				'choices' => array(
					1 => '1',
					2 => '2',
					3 => '3',
					4 => '4',
					5 => '5',
				),
				'default_value' => 4,
			),
			array(
				'key' => 'field_featured_num_cards_tablet',
				'label' => 'Number of Cards (Tablet)',
				'name' => 'featured_listings_num_cards_tablet',
				'type' => 'select',
				'choices' => array(
					1 => '1',
					2 => '2',
					3 => '3',
				),
				'default_value' => 2,
			),
			array(
				'key' => 'field_featured_num_cards_mobile',
				'label' => 'Number of Cards (Mobile)',
				'name' => 'featured_listings_num_cards_mobile',
				'type' => 'select',
				'choices' => array(
					1 => '1',
					2 => '2',
				),
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_card_gap',
				'label' => 'Card Gap Control',
				'name' => 'featured_listings_card_gap',
				'type' => 'select',
				'choices' => array(
					5 => '5px',
					10 => '10px',
					15 => '15px',
					20 => '20px',
					25 => '25px',
					30 => '30px',
				),
				'default_value' => 15,
			),
			array(
				'key' => 'field_featured_image_height',
				'label' => 'Image Height Control',
				'name' => 'featured_listings_image_height',
				'type' => 'select',
				'choices' => array(
					250 => '250px',
					280 => '280px',
					300 => '300px',
					350 => '350px',
				),
				'default_value' => 280,
			),
			array(
				'key' => 'field_featured_query_type',
				'label' => 'Featured Property Control',
				'name' => 'featured_listings_query_type',
				'type' => 'select',
				'choices' => array(
					'featured' => 'Show Featured Properties Only',
					'latest'   => 'Show Latest Properties',
					'sale'     => 'Show For Sale Only',
					'rent'     => 'Show For Rent Only',
					'mixed'    => 'Show Mixed Properties',
				),
				'default_value' => 'featured',
			),
			array(
				'key' => 'field_featured_sort_by',
				'label' => 'Sort By',
				'name' => 'featured_listings_sort_by',
				'type' => 'select',
				'choices' => array(
					'featured_order' => 'Featured Order (Default)',
					'latest'         => 'Latest',
					'oldest'         => 'Oldest',
					'price_low'      => 'Price Low to High',
					'price_high'     => 'Price High to Low',
				),
				'default_value' => 'featured_order',
			),
			array(
				'key' => 'field_featured_num_properties',
				'label' => 'Number of Properties (Total fetched)',
				'name' => 'featured_listings_num_properties',
				'type' => 'number',
				'default_value' => 6,
				'min' => 1,
			),
			array(
				'key' => 'field_featured_show_tabs',
				'label' => 'Show Category Tabs On/Off',
				'name' => 'featured_listings_show_tabs',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_show_price',
				'label' => 'Show Price On/Off',
				'name' => 'featured_listings_show_price',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_show_location',
				'label' => 'Show Location On/Off',
				'name' => 'featured_listings_show_location',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
			),
			array(
				'key' => 'field_featured_categories',
				'label' => 'Category Tabs (Property Category)',
				'name' => 'featured_listings_categories',
				'type' => 'repeater',
				'instructions' => 'Add, reorder, and enable/disable property categories for the slider tabs.',
				'layout' => 'table',
				'button_label' => 'Add Category Tab',
				'sub_fields' => array(
					array(
						'key' => 'field_feat_cat_term',
						'label' => 'Category',
						'name' => 'category_term',
						'type' => 'taxonomy',
						'taxonomy' => 'property_category',
						'field_type' => 'select',
						'allow_null' => 0,
						'add_term' => 0,
						'save_terms' => 0,
						'load_terms' => 0,
						'return_format' => 'object',
					),
					array(
						'key' => 'field_feat_cat_enabled',
						'label' => 'Enabled',
						'name' => 'enabled',
						'type' => 'true_false',
						'ui' => 1,
						'default_value' => 1,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'casaview-settings',
				),
			),
		),
	) );

	// Register Term level fields for Amenity taxonomy
	acf_add_local_field_group( array(
		'key' => 'group_casaview_amenity_fields',
		'title' => 'Amenity Icon Settings',
		'fields' => array(
			array(
				'key' => 'field_amenity_icon_type',
				'label' => 'Icon Type',
				'name' => 'amenity_icon_type',
				'type' => 'select',
				'choices' => array(
					'class' => 'Icon Library Class (FontAwesome)',
					'svg' => 'SVG File Upload',
					'image' => 'PNG/JPG Image Upload',
				),
				'default_value' => 'class',
			),
			array(
				'key' => 'field_amenity_icon_class',
				'label' => 'Icon Class Name',
				'name' => 'amenity_icon_class',
				'type' => 'text',
				'placeholder' => 'e.g. fa-solid fa-swimming-pool',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_amenity_icon_type',
							'operator' => '==',
							'value' => 'class',
						),
					),
				),
			),
			array(
				'key' => 'field_amenity_svg',
				'label' => 'SVG File',
				'name' => 'amenity_svg',
				'type' => 'file',
				'return_format' => 'url',
				'mime_types' => 'svg',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_amenity_icon_type',
							'operator' => '==',
							'value' => 'svg',
						),
					),
				),
			),
			array(
				'key' => 'field_amenity_image',
				'label' => 'PNG/JPG Image',
				'name' => 'amenity_image',
				'type' => 'image',
				'return_format' => 'url',
				'mime_types' => 'png,jpg,jpeg',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_amenity_icon_type',
							'operator' => '==',
							'value' => 'image',
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'amenity',
				),
			),
		),
	) );

	// Agent Custom Fields Group
	acf_add_local_field_group( array(
		'key' => 'group_casaview_agent_details',
		'title' => 'Agent Profile Information',
		'fields' => array(
			array(
				'key' => 'field_agent_designation',
				'label' => 'Designation / Title',
				'name' => 'designation',
				'type' => 'text',
				'default_value' => 'Luxury Property Consultant',
				'required' => 0,
			),
			array(
				'key' => 'field_agent_phone',
				'label' => 'Phone Number',
				'name' => 'phone',
				'type' => 'text',
				'placeholder' => '+971 50 XXX XXXX',
				'required' => 1,
			),
			array(
				'key' => 'field_agent_whatsapp',
				'label' => 'WhatsApp Number',
				'name' => 'whatsapp',
				'type' => 'text',
				'placeholder' => '+97150XXXXXXX (Only numbers)',
				'required' => 1,
			),
			array(
				'key' => 'field_agent_email',
				'label' => 'Email Address',
				'name' => 'email',
				'type' => 'email',
				'required' => 1,
			),
			array(
				'key' => 'field_agent_fb',
				'label' => 'Facebook URL',
				'name' => 'facebook',
				'type' => 'text',
			),
			array(
				'key' => 'field_agent_insta',
				'label' => 'Instagram URL',
				'name' => 'instagram',
				'type' => 'text',
			),
			array(
				'key' => 'field_agent_linkedin',
				'label' => 'LinkedIn URL',
				'name' => 'linkedin',
				'type' => 'text',
			),
			array(
				'key' => 'field_agent_wp_user',
				'label' => 'Linked WordPress User',
				'name' => 'linked_wp_user',
				'type' => 'user',
				'role' => array('pr_agent', 'administrator'),
				'allow_null' => 1,
				'required' => 0,
			),
			array(
				'key' => 'field_agent_commission_rate',
				'label' => 'Commission Rate (%)',
				'name' => 'commission_rate',
				'type' => 'number',
				'default_value' => 2,
				'required' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'agent',
				),
			),
		),
	) );

	// Leads Custom Fields Group
	acf_add_local_field_group( array(
		'key' => 'group_casaview_lead_details',
		'title' => 'Lead Inquiry Details',
		'fields' => array(
			array(
				'key' => 'field_lead_phone',
				'label' => 'Phone Number',
				'name' => 'lead_phone',
				'type' => 'text',
				'readonly' => 1,
			),
			array(
				'key' => 'field_lead_email',
				'label' => 'Email Address',
				'name' => 'lead_email',
				'type' => 'email',
				'readonly' => 1,
			),
			array(
				'key' => 'field_lead_message',
				'label' => 'Inquiry Message',
				'name' => 'lead_message',
				'type' => 'textarea',
				'rows' => 3,
				'readonly' => 1,
			),
			array(
				'key' => 'field_lead_property',
				'label' => 'Inquired Property',
				'name' => 'lead_property',
				'type' => 'post_object',
				'post_type' => array( 'property' ),
				'return_format' => 'object',
				'readonly' => 1,
			),
			array(
				'key' => 'field_lead_status',
				'label' => 'Lead Status',
				'name' => 'lead_status',
				'type' => 'select',
				'choices' => array(
					'New' => 'New',
					'Contacted' => 'Contacted',
					'In Progress' => 'In Progress',
					'Lost' => 'Lost',
					'Won' => 'Won',
				),
				'default_value' => 'New',
			),
			array(
				'key' => 'field_lead_notes',
				'label' => 'Follow-up Notes & Log',
				'name' => 'follow_up_notes',
				'type' => 'textarea',
				'rows' => 4,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'lead',
				),
			),
		),
	) );
}

// 5. Options Page Fallback or Options Page definition
if ( function_exists('acf_add_options_sub_page') ) {
	acf_add_options_sub_page( array(
		'page_title'  => 'Footer Options',
		'menu_title'  => 'Footer Options',
		'parent_slug' => 'casaview-settings',
	) );
}

// Include Footer ACF Fields
require_once get_template_directory() . '/inc/acf-footer-options.php';


// 6. AJAX Inquiry Submission Handler
function casaview_submit_inquiry_handler() {
	$name = sanitize_text_field( $_POST['client_name'] ?? '' );
	$phone = sanitize_text_field( $_POST['client_phone'] ?? '' );
	$email = sanitize_email( $_POST['client_email'] ?? '' );
	$message = sanitize_textarea_field( $_POST['client_message'] ?? '' );
	$property_id = intval( $_POST['property_id'] ?? 0 );

	if ( empty( $name ) || empty( $phone ) || empty( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
	}

	// Insert lead post CPT 'lead'
	$lead_title = sprintf( 'Inquiry: %s on %s', $name, get_the_title( $property_id ) );
	$lead_post_id = wp_insert_post( array(
		'post_title'   => $lead_title,
		'post_type'    => 'lead',
		'post_status'  => 'publish',
	) );

	if ( is_wp_error( $lead_post_id ) ) {
		wp_send_json_error( array( 'message' => 'Failed to save your inquiry. Please try again.' ) );
	}

	// Update lead fields
	update_field( 'lead_phone', $phone, $lead_post_id );
	update_field( 'lead_email', $email, $lead_post_id );
	update_field( 'lead_message', $message, $lead_post_id );
	update_field( 'lead_property', $property_id, $lead_post_id );
	update_field( 'lead_status', 'New', $lead_post_id );

	// Sync with plugin lead database
	if ( class_exists( 'PR_Works_Lead_Manager' ) ) {
		PR_Works_Lead_Manager::get_instance()->create_lead( $name, $phone, $email, $message, $property_id );
	}

	wp_send_json_success( array( 'message' => 'Your inquiry has been successfully sent. An agent will contact you soon.' ) );
}
add_action( 'wp_ajax_submit_inquiry', 'casaview_submit_inquiry_handler' );
add_action( 'wp_ajax_nopriv_submit_inquiry', 'casaview_submit_inquiry_handler' );

// 7. AJAX Property Filters Handler
function casaview_filter_properties_handler() {
	$keyword = sanitize_text_field( $_POST['keyword'] ?? '' );
	$listing_type = sanitize_text_field( $_POST['listing_type'] ?? '' );
	$district = sanitize_text_field( $_POST['district'] ?? '' );
	if ( empty( $district ) ) {
		$district = sanitize_text_field( $_POST['location'] ?? '' );
	}
	$place = sanitize_text_field( $_POST['place'] ?? '' );
	
	$type = sanitize_text_field( $_POST['prop_type'] ?? '' );
	$category = sanitize_text_field( $_POST['prop_cat'] ?? '' );
	$min_price = floatval( $_POST['min_price'] ?? 0 );
	$max_price = floatval( $_POST['max_price'] ?? 0 );
	$beds = sanitize_text_field( $_POST['beds'] ?? '' );
	$baths = sanitize_text_field( $_POST['baths'] ?? '' );
	$area_size = floatval( $_POST['area_size'] ?? 0 );
	$selected_amenities = array_filter( array_map( 'sanitize_text_field', (array) ($_POST['amenities'] ?? array()) ) );

	$args = array(
		'post_type'      => 'property',
		'posts_per_page' => 12,
		'post_status'    => 'publish',
		'meta_query'     => array( 'relation' => 'AND' ),
		'tax_query'      => array( 'relation' => 'AND' ),
	);

	if ( ! empty( $keyword ) ) {
		$args['s'] = $keyword;
	}

	if ( ! empty( $listing_type ) ) {
		if ( $listing_type === 'buy' || $listing_type === 'sale' ) {
			$args['meta_query'][] = array(
				'key'     => 'listing_type',
				'value'   => array( 'buy', 'sale' ),
				'compare' => 'IN',
			);
		} elseif ( $listing_type === 'rent' ) {
			$args['meta_query'][] = array(
				'key'     => 'listing_type',
				'value'   => 'rent',
				'compare' => '=',
			);
		}
	}

	if ( ! empty( $district ) ) {
		$args['meta_query'][] = array(
			'key'     => 'district',
			'value'   => $district,
			'compare' => '=',
		);
	}

	if ( ! empty( $place ) ) {
		$args['meta_query'][] = array(
			'key'     => 'place',
			'value'   => $place,
			'compare' => '=',
		);
	}

	if ( ! empty( $type ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $type,
		);
	}

	if ( ! empty( $category ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property_category',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	if ( $min_price > 0 || $max_price > 0 ) {
		$price_query = array(
			'key'     => 'price',
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);
		if ( $min_price > 0 && $max_price > 0 ) {
			$price_query['value'] = array( $min_price, $max_price );
		} elseif ( $min_price > 0 ) {
			$price_query['compare'] = '>=';
			$price_query['value'] = $min_price;
		} else {
			$price_query['compare'] = '<=';
			$price_query['value'] = $max_price;
		}
		$args['meta_query'][] = $price_query;
	}

	if ( ! empty( $beds ) ) {
		$args['meta_query'][] = array(
			'key'     => 'bedrooms',
			'value'   => intval( $beds ),
			'compare' => '=',
		);
	}

	if ( ! empty( $baths ) ) {
		$args['meta_query'][] = array(
			'key'     => 'bathrooms',
			'value'   => intval( $baths ),
			'compare' => '=',
		);
	}

	if ( $area_size > 0 ) {
		$args['meta_query'][] = array(
			'key'     => 'area_sqft',
			'value'   => $area_size,
			'type'    => 'NUMERIC',
			'compare' => '>=',
		);
	}



	if ( ! empty( $selected_amenities ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'amenity',
			'field'    => 'slug',
			'terms'    => $selected_amenities,
			'operator' => 'AND',
		);
	}

	$query = new WP_Query( $args );
	
	ob_start();

	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();
			$price = get_field('price');
			$beds = get_field('bedrooms');
			$baths = get_field('bathrooms');
			$area = get_field('area_sqft');
			
			$display_district = get_post_meta(get_the_ID(), 'district', true);
			$display_place = get_post_meta(get_the_ID(), 'place', true);
			$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
			if ( empty($display_location) ) {
				$display_location = 'India';
			}
			
			$listing_type_val = get_field('listing_type');
			$is_exclusive = get_field('is_exclusive');
			$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: (get_post_meta(get_the_ID(), '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
			
			// Get photo count
			$gallery_images = casaview_get_repeater('gallery_images', get_the_ID());
			$photo_count = 1;
			if ( ! empty( $gallery_images ) ) {
				$photo_count += count($gallery_images);
			}
			$is_featured = get_post_meta(get_the_ID(), 'is_featured', true) === '1' || get_field('is_featured');
			?>
			<div class="property-card">
				<div class="property-image-wrapper">
					<a href="<?php the_permalink(); ?>" class="property-image-link">
						<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>" class="property-image">
					</a>
					<div class="property-badge-wrapper">
						<?php if ( $is_exclusive ) : ?>
							<span class="property-badge-exclusive">Exclusive</span>
						<?php endif; ?>
						<?php if ( $is_featured ) : ?>
							<span class="property-badge-featured">â­ Featured</span>
						<?php endif; ?>
					</div>
					<span class="property-badge-photos">
						ðŸ“· <?php echo esc_html($photo_count); ?> Photos
					</span>
					<div class="property-price">
						<?php 
						$price_val = casaview_format_price($price);
						if ( $listing_type_val === 'rent' ) {
							$price_val .= ' / Month';
						}
						echo esc_html($price_val); 
						?>
					</div>
				</div>
				<div class="property-details">
					<?php 
					$property_types = wp_get_post_terms( get_the_ID(), 'property_type' );
					$type_name = ! empty( $property_types ) ? $property_types[0]->name : 'Property';
					?>
					<span class="property-type-tag"><?php echo esc_html($type_name); ?></span>
					<h3 class="property-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="property-location">
						<i class="fa-solid fa-location-dot"></i>
						<span><?php echo esc_html($display_location); ?></span>
					</div>
					<div class="property-amenities">
						<div class="property-amenity">
							<i class="fa-solid fa-bed"></i>
							<strong><?php echo esc_html($beds ? $beds : '0'); ?></strong> Beds
						</div>
						<div class="property-amenity">
							<i class="fa-solid fa-bath"></i>
							<strong><?php echo esc_html($baths ? $baths : '0'); ?></strong> Baths
						</div>
						<div class="property-amenity">
							<i class="fa-solid fa-ruler-combined"></i>
							<strong><?php echo esc_html(casaview_format_area($area)); ?></strong> Sq.Ft.
						</div>
					</div>
					<div class="property-metas-bottom">
						<div class="ali-left">
							<?php if ( $listing_type_val === 'rent' ) : ?>
								<span class="status-property-label badge-rent">For Rent</span>
							<?php else : ?>
								<span class="status-property-label badge-sale">For Sale</span>
							<?php endif; ?>
						</div>
						<div class="ms-auto action-item d-flex align-items-center gap-2">
							<button class="wishlist-btn-toggle btn-action-circle" data-id="<?php the_ID(); ?>" aria-label="Add to Wishlist">
								<i class="fa-regular fa-heart"></i>
							</button>
							<button class="compare-btn-toggle btn-action-circle" data-id="<?php the_ID(); ?>" data-title="<?php the_title(); ?>" aria-label="Add to Compare">
								<i class="fa-solid fa-code-compare"></i>
							</button>
						</div>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="property-card-overlay-link" aria-label="<?php the_title_attribute(); ?>"></a>
			</div>
			<?php
		endwhile;
		wp_reset_postdata();
	else :
		echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted); font-size: 18px; font-weight: 700;">No Properties Found</div>';
	endif;

	$html = ob_get_clean();
	wp_send_json_success( array( 
		'html'  => $html,
		'count' => $query->found_posts
	) );
}
add_action( 'wp_ajax_filter_properties', 'casaview_filter_properties_handler' );
add_action( 'wp_ajax_nopriv_filter_properties', 'casaview_filter_properties_handler' );

function casaview_get_compare_properties_handler() {
	$ids = array_map( 'intval', (array) ($_POST['ids'] ?? array()) );
	
	if ( empty( $ids ) ) {
		wp_send_json_error( array( 'message' => 'No IDs provided' ) );
	}

	$args = array(
		'post_type'      => 'property',
		'post__in'       => $ids,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );
	
	// Sort posts in the same order as requested IDs
	$ordered_posts = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$ordered_posts[get_the_ID()] = $query->post;
		}
		wp_reset_postdata();
	}

	// Build sorted post list
	$posts = array();
	foreach ( $ids as $id ) {
		if ( isset( $ordered_posts[$id] ) ) {
			$posts[] = $ordered_posts[$id];
		}
	}

	if ( empty( $posts ) ) {
		wp_send_json_error( array( 'message' => 'No matching properties found' ) );
	}

	ob_start();
	?>
	<table class="compare-table">
		<thead>
			<tr>
				<th>Property Details</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<div class="compare-image-card">
							<?php 
							$thumbnail = get_the_post_thumbnail_url($post->ID, 'medium') ?: (get_post_meta($post->ID, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
							?>
							<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
							<button class="compare-remove-item" data-id="<?php echo $post->ID; ?>" aria-label="Remove from compare">
								<i class="fa-solid fa-xmark"></i>
							</button>
						</div>
					</td>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<tr class="compare-row-title">
				<th>Title</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<h4 class="compare-prop-title">
							<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a>
						</h4>
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-price">
				<th>Price</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<div class="compare-prop-price">
							<?php 
							$price = get_field('price', $post->ID);
							$listing_type = get_field('listing_type', $post->ID);
							$price_val = casaview_format_price($price);
							if ( $listing_type === 'rent' ) {
								$price_val .= ' / Month';
							}
							echo esc_html($price_val); 
							?>
						</div>
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-location">
				<th>Location</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<div class="compare-prop-location">
							<i class="fa-solid fa-location-dot"></i>
							<?php 
							$district = get_post_meta($post->ID, 'district', true);
							$place = get_post_meta($post->ID, 'place', true);
							$location = ($place ? $place : '') . ($district ? ', ' . $district : '');
							echo esc_html($location ?: 'India');
							?>
						</div>
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-area">
				<th>Area</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<?php 
						$area = get_field('area_sqft', $post->ID);
						echo esc_html(casaview_format_area($area)) . ' Sq.Ft.';
						?>
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-beds">
				<th>Bedrooms</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<?php 
						$beds = get_field('bedrooms', $post->ID);
						echo esc_html($beds ? $beds : '0'); 
						?> Beds
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-baths">
				<th>Bathrooms</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<?php 
						$baths = get_field('bathrooms', $post->ID);
						echo esc_html($baths ? $baths : '0'); 
						?> Baths
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-amenities">
				<th>Amenities</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<ul class="compare-amenities-list">
							<?php 
							$amenities = wp_get_post_terms( $post->ID, 'amenity' );
							if ( ! empty( $amenities ) && ! is_wp_error( $amenities ) ) {
								$count = 0;
								foreach ( $amenities as $amenity ) {
									if ($count >= 6) {
										echo '<li><i class="fa-solid fa-ellipsis"></i> ' . (count($amenities) - 6) . ' More</li>';
										break;
									}
									echo '<li><i class="fa-solid fa-check"></i> ' . esc_html($amenity->name) . '</li>';
									$count++;
								}
							} else {
								echo '<li style="color:#888;">No amenities specified</li>';
							}
							?>
						</ul>
					</td>
				<?php endforeach; ?>
			</tr>
			<tr class="compare-row-action">
				<th>Action</th>
				<?php foreach ( $posts as $post ) : ?>
					<td>
						<a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="compare-table-btn">View Details</a>
					</td>
				<?php endforeach; ?>
			</tr>
		</tbody>
	</table>
	<?php
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_casaview_get_compare_properties', 'casaview_get_compare_properties_handler' );
add_action( 'wp_ajax_nopriv_casaview_get_compare_properties', 'casaview_get_compare_properties_handler' );

// 8. Custom Helpers for Dynamic Icons
function casaview_get_property_icon($icon_key, $post_id = 0) {
	$post_id = $post_id ?: get_the_ID();
	
	// 1. Check Per-Property Override
	$type = get_field('override_icon_' . $icon_key . '_type', $post_id);
	if ($type) {
		if ($type === 'svg') {
			$svg = get_field('override_icon_' . $icon_key . '_svg', $post_id);
			if ($svg) return array('type' => 'svg', 'value' => $svg);
		} elseif ($type === 'image') {
			$img = get_field('override_icon_' . $icon_key . '_image', $post_id);
			if ($img) return array('type' => 'image', 'value' => $img);
		} elseif ($type === 'class') {
			$cls = get_field('override_icon_' . $icon_key . '_class', $post_id);
			if ($cls) return array('type' => 'class', 'value' => $cls);
		}
	}
	
	// 2. Check Global Options Page Defaults
	$global_type = get_field('global_icon_' . $icon_key . '_type', 'option');
	if ($global_type) {
		if ($global_type === 'svg') {
			$global_svg = get_field('global_icon_' . $icon_key . '_svg', 'option');
			if ($global_svg) return array('type' => 'svg', 'value' => $global_svg);
		} elseif ($global_type === 'image') {
			$global_img = get_field('global_icon_' . $icon_key . '_image', 'option');
			if ($global_img) return array('type' => 'image', 'value' => $global_img);
		} elseif ($global_type === 'class') {
			$global_cls = get_field('global_icon_' . $icon_key . '_class', 'option');
			if ($global_cls) return array('type' => 'class', 'value' => $global_cls);
		}
	}
	
	// 3. Hardcoded Fallbacks (FontAwesome classes)
	$fallbacks = array(
		'bedroom' => 'fa-solid fa-bed',
		'bathroom' => 'fa-solid fa-bath',
		'area' => 'fa-solid fa-ruler-combined',
		'parking' => 'fa-solid fa-car',
		'pool' => 'fa-solid fa-person-swimming',
		'security' => 'fa-solid fa-shield-halved',
		'cctv' => 'fa-solid fa-video',
		'gym' => 'fa-solid fa-dumbbell',
		'garden' => 'fa-solid fa-tree',
		'lift' => 'fa-solid fa-elevator',
		'water' => 'fa-solid fa-droplet',
		'electricity' => 'fa-solid fa-bolt',
		'road' => 'fa-solid fa-road',
	);
	
	return array('type' => 'class', 'value' => $fallbacks[$icon_key] ?? 'fa-solid fa-circle-info');
}

function casaview_render_property_icon($icon_key, $post_id = 0) {
	$icon = casaview_get_property_icon($icon_key, $post_id);
	if ($icon['type'] === 'svg') {
		return '<img src="' . esc_url($icon['value']) . '" class="property-icon-svg" alt="' . esc_attr($icon_key) . '" style="width:20px; height:20px; object-fit:contain; filter: brightness(0.8) sepia(0.2) hue-rotate(15deg);">';
	} elseif ($icon['type'] === 'image') {
		return '<img src="' . esc_url($icon['value']) . '" class="property-icon-img" alt="' . esc_attr($icon_key) . '" style="width:20px; height:20px; object-fit:contain;">';
	} else {
		return '<i class="' . esc_attr($icon['value']) . '"></i>';
	}
}

// Currency mapping symbols
function casaview_get_currency_symbols() {
	return array(
		'AED' => 'AED ',
		'INR' => '₹ ',
		'USD' => '$',
		'EUR' => '€',
		'GBP' => '£',
		'SAR' => '﷼ ',
		'QAR' => '﷼ ',
		'KWD' => 'د.ك ',
		'OMR' => '﷼ ',
		'BHD' => 'د.ب ',
		'CAD' => 'C$',
		'AUD' => 'A$',
		'SGD' => 'S$',
		'NZD' => 'NZ$',
		'CHF' => 'CHF ',
		'JPY' => '¥',
		'CNY' => '¥',
		'MYR' => 'RM ',
		'THB' => '฿',
		'IDR' => 'Rp ',
		'ZAR' => 'R ',
		'TRY' => '₺',
		'RUB' => '₽',
		'BRL' => 'R$',
		'MXN' => 'Mex$',
		'HKD' => 'HK$',
		'PHP' => '₱',
		'PKR' => '₨ ',
		'LKR' => '₨ ',
		'BDT' => '৳ ',
		'NPR' => '₨ ',
	);
}

// Get supported currencies list
function casaview_get_supported_currencies() {
	return array(
		'' => 'Use Global Default',
		'AED' => 'AED (د.إ) - UAE Dirham',
		'INR' => 'INR (₹) - Indian Rupee',
		'USD' => 'USD ($) - US Dollar',
		'EUR' => 'EUR (€) - Euro',
		'GBP' => 'GBP (£) - British Pound',
		'SAR' => 'SAR (﷼) - Saudi Riyal',
		'QAR' => 'QAR (﷼) - Qatari Riyal',
		'KWD' => 'KWD (د.ك) - Kuwaiti Dinar',
		'OMR' => 'OMR (﷼) - Omani Rial',
		'BHD' => 'BHD (د.ب) - Bahraini Dinar',
		'CAD' => 'CAD ($) - Canadian Dollar',
		'AUD' => 'AUD ($) - Australian Dollar',
		'SGD' => 'SGD ($) - Singapore Dollar',
		'NZD' => 'NZD ($) - New Zealand Dollar',
		'CHF' => 'CHF (Fr.) - Swiss Franc',
		'JPY' => 'JPY (¥) - Japanese Yen',
		'CNY' => 'CNY (¥) - Chinese Yuan',
		'MYR' => 'MYR (RM) - Malaysian Ringgit',
		'THB' => 'THB (à¸¿) - Thai Baht',
		'IDR' => 'IDR (Rp) - Indonesian Rupiah',
		'ZAR' => 'ZAR (R) - South African Rand',
		'TRY' => 'TRY (â‚º) - Turkish Lira',
		'RUB' => 'RUB (â‚½) - Russian Ruble',
		'BRL' => 'BRL (R$) - Brazilian Real',
		'MXN' => 'MXN ($) - Mexican Peso',
		'HKD' => 'HKD ($) - Hong Kong Dollar',
		'PHP' => 'PHP (â‚±) - Philippine Peso',
		'PKR' => 'PKR (â‚¨) - Pakistani Rupee',
		'LKR' => 'LKR (â‚¨) - Sri Lankan Rupee',
		'BDT' => 'BDT (à§³) - Bangladeshi Taka',
		'NPR' => 'NPR (â‚¨) - Nepalese Rupee',
	);
}

// Add image quality optimization filter on upload
function casaview_optimize_image_quality( $quality ) {
	return 82; // Set compression level to 82% to balance size and quality
}
add_filter( 'jpeg_quality', 'casaview_optimize_image_quality' );
add_filter( 'wp_editor_set_quality', 'casaview_optimize_image_quality' );

// Resolve property currency
function casaview_get_property_currency($post_id = 0) {
	$post_id = $post_id ?: get_the_ID();
	$currency = get_post_meta($post_id, 'property_currency', true);
	if (empty($currency)) {
		$currency = get_option('casaview_default_currency', 'AED');
	}
	return $currency;
}

// Format price with currency symbol and dynamic grouping
function casaview_format_price_currency($price, $currency_code = 'AED') {
	if (!is_numeric($price)) {
		return 'Price on Request';
	}

	$symbols = casaview_get_currency_symbols();
	$symbol = isset($symbols[$currency_code]) ? $symbols[$currency_code] : '';

	// Indian numbering system formatting for INR
	if ($currency_code === 'INR') {
		$price = round($price);
		if ($price >= 10000000) {
			$crores = $price / 10000000;
			return $symbol . ' ' . (round($crores, 2) == round($crores) ? round($crores) : number_format($crores, 2)) . ' Crore';
		} elseif ($price >= 100000) {
			$lakhs = $price / 100000;
			return $symbol . ' ' . (round($lakhs, 2) == round($lakhs) ? round($lakhs) : number_format($lakhs, 2)) . ' Lakhs';
		}
		
		$str_price = (string)$price;
		$len = strlen($str_price);
		if ($len <= 3) {
			$formatted = $str_price;
		} else {
			$last_three = substr($str_price, -3);
			$rest = substr($str_price, 0, -3);
			$rest_len = strlen($rest);
			$chunks = array();
			for ($i = $rest_len; $i > 0; $i -= 2) {
				$start = max(0, $i - 2);
				$chunks[] = substr($rest, $start, $i - $start);
			}
			$chunks = array_reverse($chunks);
			$formatted = implode(',', $chunks) . ',' . $last_three;
		}
		return $symbol . ' ' . $formatted;
	}

	// Standard international decimal formatting for others
	return $symbol . ' ' . number_format($price);
}

// Overwrite original format price to use resolved currency
function casaview_format_price($price) {
	$currency_code = casaview_get_property_currency();
	return casaview_format_price_currency($price, $currency_code);
}

function casaview_format_area($area) {
	return is_numeric($area) ? number_format($area) : '0';
}

// Custom parser to safely retrieve repeater values under ACF Free
function casaview_get_repeater($field_name, $post_id = 0) {
	$post_id = $post_id ?: get_the_ID();
	
	// Try standard ACF first
	if (function_exists('get_field')) {
		$val = get_field($field_name, $post_id);
		if (is_array($val) && !empty($val)) {
			return $val;
		}
	}

	// Manual fallback parsing
	$count = get_post_meta($post_id, $field_name, true);
	if (!is_numeric($count) || $count <= 0) {
		return array();
	}

	$rows = array();
	// Detect fields dynamically based on field name
	for ($i = 0; $i < $count; $i++) {
		$row = array();
		if ($field_name === 'gallery_images') {
			$row['image'] = get_post_meta($post_id, 'gallery_images_' . $i . '_image', true);
		} elseif ($field_name === 'floor_plans') {
			$row['floor_name'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_name', true);
			$row['floor_area'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_area', true);
			$row['floor_price'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_price', true);
			$row['floor_beds'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_beds', true);
			$row['floor_baths'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_baths', true);
			$row['floor_sort_order'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_sort_order', true);
			$row['floor_plan_image'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_plan_image', true);
			$row['floor_description'] = get_post_meta($post_id, 'floor_plans_' . $i . '_floor_description', true);
		} elseif ($field_name === 'faqs') {
			$row['question'] = get_post_meta($post_id, 'faqs_' . $i . '_question', true);
			$row['answer'] = get_post_meta($post_id, 'faqs_' . $i . '_answer', true);
		} elseif ($field_name === 'nearby_places') {
			$row['place_name'] = get_post_meta($post_id, 'nearby_places_' . $i . '_place_name', true);
			$row['place_type'] = get_post_meta($post_id, 'nearby_places_' . $i . '_place_type', true);
			$row['distance'] = get_post_meta($post_id, 'nearby_places_' . $i . '_distance', true);
			$row['travel_time'] = get_post_meta($post_id, 'nearby_places_' . $i . '_travel_time', true);
		}
		$rows[] = $row;
	}
	return $rows;
}


function casaview_get_amenity_icon($term_id) {
	$type = get_field('amenity_icon_type', 'amenity_' . $term_id);
	if ($type === 'svg') {
		$svg = get_field('amenity_svg', 'amenity_' . $term_id);
		if ($svg) return array('type' => 'svg', 'value' => $svg);
	} elseif ($type === 'image') {
		$img = get_field('amenity_image', 'amenity_' . $term_id);
		if ($img) return array('type' => 'image', 'value' => $img);
	} elseif ($type === 'class') {
		$cls = get_field('amenity_icon_class', 'amenity_' . $term_id);
		if ($cls) return array('type' => 'class', 'value' => $cls);
	}
	
	return array('type' => 'class', 'value' => 'fa-solid fa-check');
}

function casaview_render_amenity_icon($term_id) {
	$icon = casaview_get_amenity_icon($term_id);
	if ($icon['type'] === 'svg') {
		return '<img src="' . esc_url($icon['value']) . '" class="amenity-icon-svg" alt="icon" style="width:18px; height:18px; object-fit:contain; margin-right:8px; filter: brightness(0.8) sepia(0.2) hue-rotate(15deg);">';
	} elseif ($icon['type'] === 'image') {
		return '<img src="' . esc_url($icon['value']) . '" class="amenity-icon-img" alt="icon" style="width:18px; height:18px; object-fit:contain; margin-right:8px;">';
	} else {
		return '<i class="' . esc_attr($icon['value']) . '" style="margin-right:8px;"></i>';
	}
}

// 9. Legacy location sync disabled (replaced by metabox save logic)
/*
function casaview_sync_property_location_fields( $post_id, $post, $update ) {
	// disabled
}
add_action( 'save_post_property', 'casaview_sync_property_location_fields', 20, 3 );
*/

// Disables Block Editor (Gutenberg) for property custom post type
add_filter( 'use_block_editor_for_post_type', function( $use_block_editor, $post_type ) {
	if ( 'property' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}, 10, 2 );

// Dynamically remove Title and Editor post type supports from property to use the metaboxes instead
add_action( 'init', function() {
	remove_post_type_support( 'property', 'title' );
	remove_post_type_support( 'property', 'editor' );
}, 25 );

// Filter ACF assigned_agents value to automatically fetch from our custom metabox key and return WP_Post objects
add_filter( 'acf/load_value/name=assigned_agents', 'casaview_acf_load_assigned_agents', 20, 3 );
function casaview_acf_load_assigned_agents( $value, $post_id, $field ) {
	$agent_id = get_post_meta( $post_id, 'assigned_agent', true );
	if ( $agent_id ) {
		$agent_post = get_post( $agent_id );
		if ( $agent_post && 'agent' === $agent_post->post_type ) {
			return array( $agent_post );
		}
	}
	return array();
}

// Filter ACF listing_type value to fetch from our custom metabox key
add_filter( 'acf/load_value/name=listing_type', 'casaview_acf_load_listing_type', 20, 3 );
function casaview_acf_load_listing_type( $value, $post_id, $field ) {
	$purpose = get_post_meta( $post_id, 'listing_type', true );
	if ( $purpose ) {
		return $purpose;
	}
	return $value;
}

// Custom SEO Meta Tags output on single property pages & About page
function casaview_seo_meta_tags() {
	if ( is_singular( 'property' ) || ( is_page() && get_page_template_slug( get_the_ID() ) === 'page-about.php' ) ) {
		$post_id = get_the_ID();
		$meta_desc = get_post_meta( $post_id, 'seo_meta_description', true );
		if ( $meta_desc ) {
			echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'casaview_seo_meta_tags', 1 );

// Custom SEO title parts overrides on single property pages & About page
function casaview_seo_title_parts( $title_parts ) {
	if ( is_singular( 'property' ) || ( is_page() && get_page_template_slug( get_the_ID() ) === 'page-about.php' ) ) {
		$post_id = get_the_ID();
		$meta_title = get_post_meta( $post_id, 'seo_meta_title', true );
		if ( $meta_title ) {
			$title_parts['title'] = $meta_title;
		}
	}
	return $title_parts;
}
add_filter( 'document_title_parts', 'casaview_seo_title_parts', 10, 1 );

// Helper function to get clean uppercase display label for listing types
function casaview_get_listing_type_label( $listing_type ) {
	$listing_type = strtolower( trim( $listing_type ) );
	if ( $listing_type === 'buy' || $listing_type === 'sale' ) {
		return 'For Sale';
	} elseif ( $listing_type === 'rent' ) {
		return 'For Rent';
	} elseif ( $listing_type === 'sale_rent' || $listing_type === 'buy_rent' ) {
		return 'For Sale & Rent';
	}
	return $listing_type;
}

/**
 * AJAX Handler for Cascading Location Dropdowns
 */
function casaview_ajax_get_location_children() {
	check_ajax_referer( 'casaview_location_nonce', 'nonce' );
	
	$parent_id = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : 0;
	$results = array();
	
	if ( $parent_id ) {
		$terms = get_terms( array(
			'taxonomy'   => 'property_location',
			'parent'     => $parent_id,
			'hide_empty' => false,
		) );
		
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$results[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}
		}
	}
	
	wp_send_json_success( $results );
}
add_action( 'wp_ajax_casaview_get_location_children', 'casaview_ajax_get_location_children' );
add_action( 'wp_ajax_nopriv_casaview_get_location_children', 'casaview_ajax_get_location_children' );

/**
 * Robust helper to get or insert a term in a hierarchical taxonomy
 */
function casaview_get_or_create_term( $name, $taxonomy, $parent_id = 0 ) {
	$term = term_exists( $name, $taxonomy, $parent_id );
	if ( $term ) {
		if ( is_array( $term ) ) {
			return intval( $term['term_id'] );
		}
		return intval( $term );
	}
	
	$inserted = wp_insert_term( $name, $taxonomy, array( 'parent' => $parent_id ) );
	if ( ! is_wp_error( $inserted ) ) {
		return intval( $inserted['term_id'] );
	}
	
	if ( $inserted->get_error_code() === 'term_exists' ) {
		$existing_id = $inserted->get_error_data();
		if ( $existing_id ) {
			return intval( $existing_id );
		}
	}
	
	return 0;
}

/**
 * Seeder to populate the Tamil Nadu location hierarchy terms if not already seeded
 */
function casaview_seed_location_hierarchy() {
	if ( get_option( 'casaview_location_hierarchy_seeded_v3' ) ) {
		return;
	}
	
	if ( ! taxonomy_exists( 'property_location' ) ) {
		return;
	}
	
	$state_id = casaview_get_or_create_term( 'Tamil Nadu', 'property_location', 0 );
	if ( $state_id ) {
		$districts = array( 'Chennai', 'Coimbatore', 'Madurai', 'Kanyakumari', 'Tirunelveli' );
		foreach ( $districts as $district ) {
			$dist_id = casaview_get_or_create_term( $district, 'property_location', $state_id );
			
			if ( $dist_id && $district === 'Kanyakumari' ) {
				$taluks = array( 'Agastheeswaram', 'Kalkulam', 'Killiyoor', 'Thiruvattar', 'Vilavancode', 'Thovalai' );
				foreach ( $taluks as $taluk ) {
					$taluk_id = casaview_get_or_create_term( $taluk, 'property_location', $dist_id );
					
					if ( $taluk_id && $taluk === 'Agastheeswaram' ) {
						$villages = array( 'Nagercoil', 'Suchindram', 'Vadasery', 'Kottaram' );
						foreach ( $villages as $village ) {
							casaview_get_or_create_term( $village, 'property_location', $taluk_id );
						}
					}
				}
			}
		}
	}
	
	update_option( 'casaview_location_hierarchy_seeded_v3', 1 );
}
add_action( 'init', 'casaview_seed_location_hierarchy', 25 );

/**
 * Get dynamic districts from published district CPT posts
 */
function casaview_get_dynamic_districts() {
	global $wpdb;
	$districts = $wpdb->get_col("
		SELECT DISTINCT pm.meta_value 
		FROM {$wpdb->postmeta} pm
		INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		WHERE p.post_type = 'property'
		  AND p.post_status = 'publish'
		  AND pm.meta_key = 'district'
		  AND pm.meta_value IS NOT NULL
		  AND pm.meta_value != ''
		ORDER BY pm.meta_value ASC
	");
	return array_values(array_filter(array_map('trim', $districts)));
}

/**
 * AJAX handler to fetch places dynamically from property database based on selected district
 */
function casaview_ajax_get_places_for_district() {
	$district = isset($_POST['district']) ? sanitize_text_field($_POST['district']) : '';
	$places = array();
	
	if ( ! empty( $district ) ) {
		global $wpdb;
		$results = $wpdb->get_col( $wpdb->prepare( "
			SELECT DISTINCT pm_place.meta_value
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm_dist ON p.ID = pm_dist.post_id AND pm_dist.meta_key = 'district' AND pm_dist.meta_value = %s
			INNER JOIN {$wpdb->postmeta} pm_place ON p.ID = pm_place.post_id AND pm_place.meta_key = 'place'
			WHERE p.post_type = 'property' 
			  AND p.post_status = 'publish' 
			  AND pm_place.meta_value IS NOT NULL 
			  AND pm_place.meta_value != ''
		", $district ) );
		
		if ( $results ) {
			$places = array_unique( array_map( 'trim', $results ) );
			sort( $places );
		}
	}
	
	wp_send_json_success( array_values( $places ) );
}
add_action( 'wp_ajax_casaview_get_places_for_district', 'casaview_ajax_get_places_for_district' );
add_action( 'wp_ajax_nopriv_casaview_get_places_for_district', 'casaview_ajax_get_places_for_district' );

/**
 * Render property card for the featured section
 */
function casaview_render_featured_card( $post_id ) {
	$price = get_field('price', $post_id);
	$beds = get_field('bedrooms', $post_id);
	$baths = get_field('bathrooms', $post_id);
	$area = get_field('area_sqft', $post_id);
	
	$display_district = get_post_meta($post_id, 'district', true);
	$display_place = get_post_meta($post_id, 'place', true);
	$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
	if ( empty($display_location) ) {
		$display_location = 'India';
	}
	
	$listing_type_val = get_field('listing_type', $post_id);
	$thumbnail = get_the_post_thumbnail_url($post_id, 'large') ?: (get_post_meta($post_id, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
	
	$show_price = get_field('featured_listings_show_price', 'option') !== false ? get_field('featured_listings_show_price', 'option') : true;
	$show_location = get_field('featured_listings_show_location', 'option') !== false ? get_field('featured_listings_show_location', 'option') : true;
	
	// Fetch category names
	$cats = get_the_terms($post_id, 'property_category');
	$cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';

	// Resolve assigned agent details
	$assigned_agent = get_post_meta($post_id, 'assigned_agent', true);
	$agent_name = 'John Mathew';
	$agent_photo = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&h=100&q=80'; // Default Alexander/businessman headshot
	
	if ( $assigned_agent ) {
		$agent_post = get_post( $assigned_agent );
		if ( $agent_post && $agent_post->post_status === 'publish' ) {
			$agent_name = $agent_post->post_title;
			$custom_photo = get_the_post_thumbnail_url($assigned_agent, 'thumbnail') ?: get_post_meta($assigned_agent, '_mock_image_url', true);
			if ( $custom_photo ) {
				$agent_photo = $custom_photo;
			} else {
				if ( stripos($agent_name, 'Elena') !== false ) {
					$agent_photo = 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&h=100&q=80';
				} elseif ( stripos($agent_name, 'Alexander') !== false ) {
					$agent_photo = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&h=100&q=80';
				}
			}
		}
	}
	// Get photo count
	$gallery_images = casaview_get_repeater('gallery_images', $post_id);
	$photo_count = 1;
	if ( ! empty( $gallery_images ) ) {
		$photo_count += count($gallery_images);
	}
	$is_featured = get_post_meta($post_id, 'is_featured', true) === '1' || get_field('is_featured', $post_id);
	?>
	<div class="featured-slide-card property-card swiper-slide">
		<div class="property-image-wrapper">
			<a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="property-image-link">
				<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>" class="property-image" loading="lazy">
			</a>
			<div class="property-image-gradient"></div>
			<div class="property-badge-wrapper">
				<span class="property-badge-type <?php echo $listing_type_val === 'rent' ? 'badge-rent' : 'badge-sale'; ?>">
					<?php echo $listing_type_val === 'rent' ? 'For Rent' : 'For Sale'; ?>
				</span>
			</div>
			<a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="property-link-icon" title="View Details">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
					<polyline points="15 3 21 3 21 9"></polyline>
					<line x1="10" y1="14" x2="21" y2="3"></line>
				</svg>
			</a>
		</div>
		
		<div class="property-details">
			<h3 class="property-title">
				<a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(mb_strimwidth(get_the_title($post_id), 0, 50, '...')); ?></a>
			</h3>

			<?php if ( $show_price ) : ?>
				<div class="property-price">
					<?php 
					$price_val = casaview_format_price($price);
					if ( $listing_type_val === 'rent' ) {
						$price_val .= ' / Month';
					}
					echo esc_html($price_val); 
					?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_location ) : ?>
				<div class="property-location" title="<?php echo esc_attr($display_location); ?>">
					<i class="fa-solid fa-location-dot"></i>
					<span><?php echo esc_html($display_location); ?></span>
				</div>
			<?php endif; ?>
			
			<div class="property-amenities">
				<div class="property-amenity">
					<i class="fa-solid fa-bed"></i>
					<span><strong><?php echo esc_html($beds ? $beds : '0'); ?></strong> Beds</span>
				</div>
				<div class="property-amenity">
					<i class="fa-solid fa-bath"></i>
					<span><strong><?php echo esc_html($baths ? $baths : '0'); ?></strong> Baths</span>
				</div>
				<div class="property-amenity">
					<i class="fa-solid fa-ruler-combined"></i>
					<span><strong><?php echo esc_html(casaview_format_area($area)); ?></strong> Sq.Ft</span>
				</div>
			</div>
			
			<div class="featured-card-footer property-card-footer">
				<a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="header-cta view-details-btn">View Details</a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Helper to build query args for the featured slider based on admin settings and current tab status
 */
function casaview_get_slider_query_args($property_status = 'all') {
	$query_type = get_field('featured_listings_query_type', 'option') ?: 'featured';
	$num_properties = intval(get_field('featured_listings_num_properties', 'option') ?: 6);

	$args = array(
		'post_type'      => 'property',
		'posts_per_page' => $num_properties,
		'post_status'    => 'publish',
		'meta_query'     => array(
			'relation' => 'AND',
		)
	);

	// Apply admin query type controls
	if ( $query_type === 'featured' ) {
		$args['meta_query'][] = array(
			'key'     => 'is_featured',
			'value'   => '1',
			'compare' => '=',
		);
	} elseif ( $query_type === 'sale' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => array( 'buy', 'sale' ),
			'compare' => 'IN',
		);
	} elseif ( $query_type === 'rent' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => 'rent',
			'compare' => '=',
		);
	}

	// Apply current tab filtering (overrides or restricts the main query)
	if ( $property_status === 'sale' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => array( 'buy', 'sale' ),
			'compare' => 'IN',
		);
	} elseif ( $property_status === 'rent' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => 'rent',
			'compare' => '=',
		);
	}

	return $args;
}

/**
 * Helper to sort query results in PHP based on admin settings
 */
function casaview_sort_slider_posts($posts, $sort_by) {
	if ( empty($posts) ) {
		return $posts;
	}

	usort($posts, function($a, $b) use ($sort_by) {
		if ( $sort_by === 'price_low' || $sort_by === 'price_high' ) {
			$price_a = floatval(get_field('price', $a->ID) ?: get_post_meta($a->ID, 'price', true) ?: 0);
			$price_b = floatval(get_field('price', $b->ID) ?: get_post_meta($b->ID, 'price', true) ?: 0);
			if ( $price_a == $price_b ) {
				return strtotime($b->post_date) - strtotime($a->post_date);
			}
			return ($sort_by === 'price_low') ? ($price_a - $price_b) : ($price_b - $price_a);
		} elseif ( $sort_by === 'oldest' ) {
			return strtotime($a->post_date) - strtotime($b->post_date);
		} elseif ( $sort_by === 'latest' ) {
			return strtotime($b->post_date) - strtotime($a->post_date);
		} else {
			// Default: sort by featured_order
			$order_a = intval(get_post_meta($a->ID, 'featured_order', true) ?: 0);
			$order_b = intval(get_post_meta($b->ID, 'featured_order', true) ?: 0);
			if ( $order_a === $order_b ) {
				return strtotime($b->post_date) - strtotime($a->post_date);
			}
			return $order_a - $order_b;
		}
	});

	return $posts;
}

/**
 * AJAX handler to retrieve featured properties filtered by listing type
 */
function casaview_ajax_get_featured_listings() {
	$property_status = isset($_POST['property_status']) ? sanitize_text_field($_POST['property_status']) : 'all';
	
	$args = casaview_get_slider_query_args($property_status);
	$query = new WP_Query( $args );
	$posts = $query->posts;
	
	$sort_by = get_field('featured_listings_sort_by', 'option') ?: 'featured_order';
	$posts = casaview_sort_slider_posts($posts, $sort_by);
	
	ob_start();
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			casaview_render_featured_card( $post->ID );
		}
		wp_reset_postdata();
	} else {
		?>
		<div class="no-properties-found">
			<div class="empty-icon-wrapper">
				<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><line x1="9" x2="15" y1="12" y2="12"/><line x1="9" x2="15" y1="16" y2="16"/></svg>
			</div>
			<h3>No Properties Found</h3>
			<p>We couldn't find any properties matching your selection. Please try another tab or check back later.</p>
			<button class="reset-featured-filter-btn">View All Properties</button>
		</div>
		<?php
	}
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_get_featured_listings', 'casaview_ajax_get_featured_listings' );
add_action( 'wp_ajax_nopriv_get_featured_listings', 'casaview_ajax_get_featured_listings' );

/**
 * AJAX handler to retrieve trending projects filtered by listing type
 */
function casaview_ajax_get_trending_projects() {
	$property_status = isset($_POST['property_status']) ? sanitize_text_field($_POST['property_status']) : 'all';
	$posts_count = isset($_POST['posts_count']) ? intval($_POST['posts_count']) : 4;
	
	$args = array(
		'post_type'      => 'property',
		'posts_per_page' => $posts_count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			'relation' => 'AND',
		)
	);
	
	if ( $property_status === 'sale' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => array( 'buy', 'sale' ),
			'compare' => 'IN',
		);
	} elseif ( $property_status === 'rent' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => 'rent',
			'compare' => '=',
		);
	}
	
	$query = new WP_Query( $args );
	
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			casaview_render_featured_card( get_the_ID() );
		}
		wp_reset_postdata();
	} else {
		echo '<div class="no-properties-found" style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #ffffff; border-radius: 12px;"><h3>No Properties Found</h3><p>We couldn\'t find any properties matching this selection.</p></div>';
	}
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_get_trending_projects', 'casaview_ajax_get_trending_projects' );
add_action( 'wp_ajax_nopriv_get_trending_projects', 'casaview_ajax_get_trending_projects' );

/**
 * Retrieve districts list for a state
 */
function casaview_get_districts_list_by_state( $state ) {
	$districts = array();
	if ( empty( $state ) ) {
		return $districts;
	}

	$meta_query = array(
		'relation' => 'OR',
		array(
			'key'     => 'district_state',
			'value'   => $state,
			'compare' => '='
		)
	);

	if ( $state === 'Kerala' ) {
		$meta_query[] = array(
			'key'     => 'district_state',
			'compare' => 'NOT EXISTS'
		);
		$meta_query[] = array(
			'key'     => 'district_state',
			'value'   => '',
			'compare' => '='
		);
	}

	$dist_posts = get_posts( array(
		'post_type'      => 'district',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_query'     => $meta_query
	) );

	if ( ! empty( $dist_posts ) ) {
		foreach ( $dist_posts as $dp ) {
			$is_enabled = get_post_meta( $dp->ID, 'district_enabled', true );
			if ( $is_enabled !== '0' ) {
				$districts[] = $dp->post_title;
			}
		}
	}

	if ( empty( $districts ) ) {
		$fallback_mapping = array(
			'Kerala' => array(
				'Thiruvananthapuram', 'Kollam', 'Pathanamthitta', 'Alappuzha', 'Kottayam', 
				'Idukki', 'Ernakulam', 'Thrissur', 'Palakkad', 'Malappuram', 'Kozhikode', 
				'Wayanad', 'Kannur', 'Kasaragod'
			),
			'Tamil Nadu' => array(
				'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 
				'Vellore', 'Thoothukudi', 'Erode', 'Thanjavur', 'Kanyakumari', 'Dharmapuri', 
				'Kanchipuram', 'Tiruvallur', 'Cuddalore', 'Dindigul', 'Nilgiris', 'Tiruppur', 
				'Virudhunagar', 'Sivaganga', 'Pudukkottai', 'Ramanathapuram', 'Theni', 'Karur'
			),
			'Karnataka' => array(
				'Bengaluru Urban', 'Bengaluru Rural', 'Mysuru', 'Belagavi', 'Kalaburagi', 
				'Davanagere', 'Ballari', 'Shivamogga', 'Tumakuru', 'Udupi', 'Dharwad', 'Mangaluru'
			),
			'Maharashtra' => array(
				'Mumbai City', 'Mumbai Suburban', 'Pune', 'Thane', 'Nagpur', 'Nashik', 
				'Aurangabad', 'Solapur', 'Amravati', 'Kolhapur'
			),
			'Delhi' => array(
				'New Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 'West Delhi', 'Central Delhi'
			),
			'Goa' => array(
				'North Goa', 'South Goa'
			)
		);

		if ( isset( $fallback_mapping[$state] ) ) {
			$districts = $fallback_mapping[$state];
		}
	}

	return array_values( array_unique( $districts ) );
}

/**
 * AJAX Handler to get districts for a selected state
 */
function casaview_ajax_get_districts_for_state() {
	$state = isset( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';
	$districts = casaview_get_districts_list_by_state( $state );
	wp_send_json_success( $districts );
}
add_action( 'wp_ajax_casaview_get_districts_for_state', 'casaview_ajax_get_districts_for_state' );
add_action( 'wp_ajax_nopriv_casaview_get_districts_for_state', 'casaview_ajax_get_districts_for_state' );

/**
 * Automatically create the Search Results page with slug "properties"
 */
function casaview_create_search_results_page() {
	if ( ! get_page_by_path( 'properties' ) ) {
		wp_insert_post( array(
			'post_title'  => 'Properties',
			'post_name'   => 'properties',
			'post_status' => 'publish',
			'post_type'   => 'page',
		) );
	}
}
add_action( 'init', 'casaview_create_search_results_page', 30 );

/**
 * Automatically create the All Properties page with slug "all-properties"
 */
function casaview_create_all_properties_page() {
	if ( ! get_page_by_path( 'all-properties' ) ) {
		wp_insert_post( array(
			'post_title'  => 'All Properties',
			'post_name'   => 'all-properties',
			'post_status' => 'publish',
			'post_type'   => 'page',
		) );
	}
}
add_action( 'init', 'casaview_create_all_properties_page', 30 );

/**
 * Automatically create the For Rent and For Sale pages if missing and assign templates
 */
function casaview_create_rent_sale_pages() {
	// For Rent Page
	$rent_page = get_page_by_path( 'for-rents' );
	if ( ! $rent_page ) {
		$rent_id = wp_insert_post( array(
			'post_title'   => 'For Rents',
			'post_name'    => 'for-rents',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
		if ( $rent_id && ! is_wp_error( $rent_id ) ) {
			update_post_meta( $rent_id, '_wp_page_template', 'page-for-rent.php' );
		}
	} else {
		// Ensure correct template is assigned
		$curr_temp = get_post_meta( $rent_page->ID, '_wp_page_template', true );
		if ( $curr_temp !== 'page-for-rent.php' ) {
			update_post_meta( $rent_page->ID, '_wp_page_template', 'page-for-rent.php' );
		}
	}

	// For Sale Page
	$sale_page = get_page_by_path( 'for-sales' );
	if ( ! $sale_page ) {
		$sale_id = wp_insert_post( array(
			'post_title'   => 'For Sales',
			'post_name'    => 'for-sales',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
		if ( $sale_id && ! is_wp_error( $sale_id ) ) {
			update_post_meta( $sale_id, '_wp_page_template', 'page-for-sale.php' );
		}
	} else {
		// Ensure correct template is assigned
		$curr_temp = get_post_meta( $sale_page->ID, '_wp_page_template', true );
		if ( $curr_temp !== 'page-for-sale.php' ) {
			update_post_meta( $sale_page->ID, '_wp_page_template', 'page-for-sale.php' );
		}
	}
}
add_action( 'init', 'casaview_create_rent_sale_pages', 30 );

/**
 * Resolve query var collision for 'district' on search pages
 */
function casaview_resolve_query_var_collision( $query_vars ) {
	if ( ! is_admin() ) {
		if ( isset( $_SERVER['REQUEST_URI'] ) && ( strpos( $_SERVER['REQUEST_URI'], '/properties/' ) !== false || strpos( $_SERVER['REQUEST_URI'], '/all-properties/' ) !== false ) ) {
			if ( isset( $query_vars['post_type'] ) && 'district' === $query_vars['post_type'] ) {
				unset( $query_vars['post_type'] );
			}
			if ( isset( $query_vars['name'] ) ) {
				unset( $query_vars['name'] );
			}
			if ( isset( $query_vars['district'] ) ) {
				unset( $query_vars['district'] );
			}
		}
	}
	return $query_vars;
}
add_filter( 'request', 'casaview_resolve_query_var_collision' );

/**
 * AJAX handler to filter properties and load map markers
 */
function casaview_ajax_filter_properties() {
	$keyword     = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
	$state       = isset( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';
	$district    = isset( $_POST['district'] ) ? sanitize_text_field( $_POST['district'] ) : '';
	$prop_type   = isset( $_POST['prop_type'] ) ? sanitize_text_field( $_POST['prop_type'] ) : '';
	$listing_val = isset( $_POST['listing_type'] ) ? sanitize_text_field( $_POST['listing_type'] ) : 'all';
	$sort_by     = isset( $_POST['sort_by'] ) ? sanitize_text_field( $_POST['sort_by'] ) : 'newest';
	$paged       = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;

	// Advanced filters
	$prop_cat    = isset( $_POST['prop_cat'] ) ? sanitize_text_field( $_POST['prop_cat'] ) : '';
	$min_price   = isset( $_POST['min_price'] ) && $_POST['min_price'] !== '' ? floatval( $_POST['min_price'] ) : '';
	$max_price   = isset( $_POST['max_price'] ) && $_POST['max_price'] !== '' ? floatval( $_POST['max_price'] ) : '';
	$beds        = isset( $_POST['beds'] ) && $_POST['beds'] !== '' ? intval( $_POST['beds'] ) : '';
	$baths       = isset( $_POST['baths'] ) && $_POST['baths'] !== '' ? intval( $_POST['baths'] ) : '';
	$area_size   = isset( $_POST['area_size'] ) && $_POST['area_size'] !== '' ? floatval( $_POST['area_size'] ) : '';

	$meta_query = array( 'relation' => 'AND' );

	if ( ! empty( $state ) ) {
		$meta_query[] = array(
			'key'     => 'state',
			'value'   => $state,
			'compare' => '=',
		);
	}

	if ( ! empty( $district ) ) {
		$meta_query[] = array(
			'key'     => 'district',
			'value'   => $district,
			'compare' => '=',
		);
	}

	if ( $listing_val === 'buy' || $listing_val === 'sale' ) {
		$meta_query[] = array(
			'key'     => 'listing_type',
			'value'   => array( 'buy', 'sale' ),
			'compare' => 'IN',
		);
	} elseif ( $listing_val === 'rent' ) {
		$meta_query[] = array(
			'key'     => 'listing_type',
			'value'   => 'rent',
			'compare' => '=',
		);
	}

	if ( $min_price !== '' ) {
		$meta_query[] = array(
			'key'     => 'price',
			'value'   => $min_price,
			'type'    => 'NUMERIC',
			'compare' => '>=',
		);
	}

	if ( $max_price !== '' ) {
		$meta_query[] = array(
			'key'     => 'price',
			'value'   => $max_price,
			'type'    => 'NUMERIC',
			'compare' => '<=',
		);
	}

	if ( $beds !== '' ) {
		$meta_query[] = array(
			'key'     => 'bedrooms',
			'value'   => $beds,
			'type'    => 'NUMERIC',
			'compare' => '=',
		);
	}

	if ( $baths !== '' ) {
		$meta_query[] = array(
			'key'     => 'bathrooms',
			'value'   => $baths,
			'type'    => 'NUMERIC',
			'compare' => '=',
		);
	}

	if ( $area_size !== '' ) {
		$meta_query[] = array(
			'key'     => 'area_sqft',
			'value'   => $area_size,
			'type'    => 'NUMERIC',
			'compare' => '>=',
		);
	}

	$tax_query = array( 'relation' => 'AND' );

	if ( ! empty( $prop_type ) ) {
		$tax_query[] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $prop_type,
		);
	}

	if ( ! empty( $prop_cat ) ) {
		$tax_query[] = array(
			'taxonomy' => 'property_category',
			'field'    => 'slug',
			'terms'    => $prop_cat,
		);
	}

	$args = array(
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => $paged,
	);

	if ( ! empty( $keyword ) ) {
		$args['s'] = $keyword;
	}

	if ( count( $meta_query ) > 1 ) {
		$args['meta_query'] = $meta_query;
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	// Apply Sorting
	if ( $sort_by === 'newest' ) {
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	} elseif ( $sort_by === 'oldest' ) {
		$args['orderby'] = 'date';
		$args['order']   = 'ASC';
	} elseif ( $sort_by === 'lowest_price' ) {
		$args['meta_key'] = 'price';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'ASC';
	} elseif ( $sort_by === 'highest_price' ) {
		$args['meta_key'] = 'price';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	} else {
		// default: display properties using the existing property order (no override)
		// We do not set $args['orderby'] or $args['order'] to keep the current query behaviour intact.
	}

	$query = new WP_Query( $args );

	// Build map markers data (for all matching posts, ignoring pagination limits)
	$map_args = $args;
	$map_args['posts_per_page'] = -1;
	$map_query = new WP_Query( $map_args );
	
	$markers = array();
	if ( $map_query->have_posts() ) {
		while ( $map_query->have_posts() ) {
			$map_query->the_post();
			$m_id = get_the_ID();
			$lat  = get_post_meta( $m_id, 'latitude', true );
			$lng  = get_post_meta( $m_id, 'longitude', true );
			$p_district = get_post_meta($m_id, 'district', true);
			$p_state = get_post_meta($m_id, 'state', true);

			// Geocode fallback if coordinates are empty/default/zero and we have a district
			$is_default_or_empty = empty( $lat ) || empty( $lng ) || 
			                       floatval( $lat ) == 0 || floatval( $lng ) == 0 || 
			                       ( $lat === '8.5241' && $lng === '76.9366' );

			if ( $is_default_or_empty && ! empty( $p_district ) ) {
				$coords = casaview_geocode_address( $p_district, $p_state );
				if ( $coords ) {
					$lat = $coords['lat'];
					$lng = $coords['lng'];
					// Cache permanently in post metadata
					update_post_meta( $m_id, 'latitude', $lat );
					update_post_meta( $m_id, 'longitude', $lng );
				}
			}

			if ( ! empty( $lat ) && ! empty( $lng ) && floatval($lat) != 0 && floatval($lng) != 0 ) {
				$thumb = get_the_post_thumbnail_url($m_id, 'medium') ?: (get_post_meta($m_id, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
				$price_val = get_post_meta($m_id, 'price', true) ?: 0;
				$l_type = get_field('listing_type', $m_id) ?: 'buy';
				$formatted_price = casaview_format_price($price_val) . ($l_type === 'rent' ? ' / Month' : '');
				
				$p_place = get_post_meta($m_id, 'place', true);
				$loc_str = ($p_place ? $p_place : '') . ($p_district ? ', ' . $p_district : '');
				if ( empty( $loc_str ) ) {
					$loc_str = 'India';
				}

				$markers[] = array(
					'id'       => $m_id,
					'title'    => get_the_title(),
					'url'      => get_permalink(),
					'img'      => $thumb,
					'price'    => $formatted_price,
					'loc'      => $loc_str,
					'district' => $p_district ?: '',
					'lat'      => floatval( $lat ),
					'lng'      => floatval( $lng ),
				);
			}
		}
		wp_reset_postdata();
	}

	// Build HTML output for property grid
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			
			$card_id = get_the_ID();
			$price = get_post_meta($card_id, 'price', true) ?: 0;
			$beds = get_post_meta($card_id, 'bedrooms', true) ?: 0;
			$baths = get_post_meta($card_id, 'bathrooms', true) ?: 0;
			$area = get_post_meta($card_id, 'area_sqft', true) ?: 0;
			
			$display_district = get_post_meta($card_id, 'district', true);
			$display_place = get_post_meta($card_id, 'place', true);
			$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
			if ( empty($display_location) ) {
				$display_location = 'India';
			}
			
			$listing_type = get_field('listing_type', $card_id) ?: 'buy';
			$is_exclusive = get_field('is_exclusive', $card_id);
			$thumbnail = get_the_post_thumbnail_url($card_id, 'large') ?: (get_post_meta($card_id, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
			
			$gallery_images = casaview_get_repeater('gallery_images', $card_id);
			$photo_count = 1;
			if ( ! empty( $gallery_images ) ) {
				$photo_count += count($gallery_images);
			}
			$is_featured = get_post_meta($card_id, 'is_featured', true) === '1' || get_field('is_featured', $card_id);
			?>
			<div class="property-card">
				<div class="property-image-wrapper">
					<a href="<?php the_permalink(); ?>" class="property-image-link">
						<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>" class="property-image" loading="lazy">
					</a>
					<div class="property-badge-wrapper">
						<?php if ( $listing_type === 'rent' ) : ?>
							<span class="property-status-badge rent-badge">For Rent</span>
						<?php else : ?>
							<span class="property-status-badge sale-badge">For Sale</span>
						<?php endif; ?>
					</div>
					<div class="property-image-actions">
						<a href="<?php the_permalink(); ?>" class="btn-image-action" aria-label="View Details">
							<i class="fa-solid fa-arrow-up-right-from-square"></i>
						</a>
					</div>
				</div>
				<div class="property-details">
					<h3 class="property-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="property-price-row">
						<?php 
						$price_val = casaview_format_price($price);
						if ( $listing_type === 'rent' ) {
							$price_val .= ' / Month';
						}
						echo esc_html($price_val); 
						?>
					</div>
					<div class="property-location">
						<i class="fa-solid fa-location-dot"></i>
						<span><?php echo esc_html($display_location); ?></span>
					</div>
					<div class="property-amenities-boxes">
						<div class="amenity-box">
							<i class="fa-solid fa-bed"></i>
							<span class="amenity-text"><strong><?php echo esc_html($beds); ?></strong> Beds</span>
						</div>
						<div class="amenity-box">
							<i class="fa-solid fa-bath"></i>
							<span class="amenity-text"><strong><?php echo esc_html($baths); ?></strong> Baths</span>
						</div>
						<div class="amenity-box">
							<i class="fa-solid fa-ruler-combined"></i>
							<span class="amenity-text"><strong><?php echo esc_html(casaview_format_area($area)); ?></strong> Sq.Ft.</span>
						</div>
					</div>
					<div class="property-card-bottom">
						<a href="<?php the_permalink(); ?>" class="btn-view-details">View Details</a>
						<button class="wishlist-btn-toggle btn-action-circle ms-auto" data-id="<?php the_ID(); ?>" aria-label="Add to Wishlist">
							<i class="fa-regular fa-heart"></i>
						</button>
					</div>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	} else {
		?>
		<div class="no-properties-found" style="grid-column: 1 / -1; text-align: center; padding: 60px 0;">
			<div class="empty-icon-wrapper" style="margin-bottom: 20px; color: #ff5a3c;">
				<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><line x1="9" x2="15" y1="12" y2="12"/><line x1="9" x2="15" y1="16" y2="16"/></svg>
			</div>
			<h3 style="font-size:24px; color:#fff; margin-bottom:10px;">No Properties Found</h3>
			<p style="color:#888;">We couldn't find any properties matching your filters. Please adjust your criteria and try again.</p>
		</div>
		<?php
	}
	$html = ob_get_clean();

	// Build Pagination HTML
	ob_start();
	if ( $query->max_num_pages > 1 ) {
		echo paginate_links( array(
			'current'   => $paged,
			'total'     => $query->max_num_pages,
			'type'      => 'plain',
			'prev_next' => true,
			'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
			'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
		) );
	}
	$pagination = ob_get_clean();

	// Count details
	$total_results = $query->found_posts;
	$start_res = $total_results > 0 ? ( ( $paged - 1 ) * 9 ) + 1 : 0;
	$end_res   = min( $paged * 9, $total_results );
	$count_text = sprintf( 'Showing %d–%d of %d results', $start_res, $end_res, $total_results );

	wp_send_json_success( array(
		'html'          => $html,
		'pagination'    => $pagination,
		'count_text'    => $count_text,
		'markers'       => $markers,
		'total_results' => $total_results,
	) );
}
add_action( 'wp_ajax_casaview_filter_properties', 'casaview_ajax_filter_properties' );
add_action( 'wp_ajax_nopriv_casaview_filter_properties', 'casaview_ajax_filter_properties' );

/**
 * Geocode a District + State address using OpenStreetMap Nominatim API.
 * Uses a WordPress transient to cache results for 30 days.
 */
function casaview_geocode_address( $district, $state ) {
	if ( empty( $district ) && empty( $state ) ) {
		return false;
	}

	// Create a safe, unique transient key for cache
	$cache_key = 'cv_geo_' . md5( sanitize_title( $district . ' ' . $state ) );
	$cached = get_transient( $cache_key );
	if ( $cached !== false ) {
		return $cached;
	}

	// Build query string
	$query_parts = array();
	if ( ! empty( $district ) ) {
		$query_parts[] = $district;
	}
	if ( ! empty( $state ) ) {
		$query_parts[] = $state;
	}
	$query_parts[] = 'India';

	$query_str = implode( ', ', $query_parts );
	$url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode( $query_str ) . '&format=json&limit=1';

	$response = wp_remote_get( $url, array(
		'timeout'    => 5,
		'user-agent' => 'CasaView Theme Geocoder AntigravityAgent/1.0',
	) );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( ! empty( $data ) && isset( $data[0]['lat'] ) && isset( $data[0]['lon'] ) ) {
		$coords = array(
			'lat' => floatval( $data[0]['lat'] ),
			'lng' => floatval( $data[0]['lon'] ),
		);
		// Cache geocoding results for 30 days
		set_transient( $cache_key, $coords, 30 * DAY_IN_SECONDS );
		return $coords;
	}

	return false;
}

/**
 * Save post hook: Geocode coordinates using State + District if left blank or default.
 */
function casaview_geocode_property_on_save( $post_id ) {
	// Prevent recursion/infinite loop
	remove_action( 'save_post_property', 'casaview_geocode_property_on_save', 20 );

	$lat      = get_post_meta( $post_id, 'latitude', true );
	$lng      = get_post_meta( $post_id, 'longitude', true );
	$district = get_post_meta( $post_id, 'district', true );
	$state    = get_post_meta( $post_id, 'state', true );

	// Determine if coords are empty, zero, or the default metabox values '8.5241' and '76.9366'
	$is_default_or_empty = empty( $lat ) || empty( $lng ) || 
	                       floatval( $lat ) == 0 || floatval( $lng ) == 0 || 
	                       ( $lat === '8.5241' && $lng === '76.9366' );

	if ( $is_default_or_empty && ! empty( $district ) ) {
		$coords = casaview_geocode_address( $district, $state );
		if ( $coords ) {
			update_post_meta( $post_id, 'latitude', $coords['lat'] );
			update_post_meta( $post_id, 'longitude', $coords['lng'] );
		}
	}

	add_action( 'save_post_property', 'casaview_geocode_property_on_save', 20 );
}
add_action( 'save_post_property', 'casaview_geocode_property_on_save', 20 );

/**
 * Register local ACF field group for the Contact Page template.
 */
function casaview_register_contact_page_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// 1. Hero Section Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_hero',
		'title'  => 'Contact Page - Hero Section',
		'fields' => array(
			array(
				'key'           => 'field_contact_hero_title',
				'label'         => 'Page Title',
				'name'          => 'contact_hero_title',
				'type'          => 'text',
				'default_value' => 'Contact Us',
				'instructions'  => 'The main heading displayed on the contact hero banner.',
				'placeholder'   => 'Contact Us',
			),
			array(
				'key'           => 'field_contact_hero_subtitle',
				'label'         => 'Subtitle / Description',
				'name'          => 'contact_hero_subtitle',
				'type'          => 'text',
				'default_value' => 'Guiding You Through Every Step of Your Property Journey with Care and Clarity',
				'instructions'  => 'The secondary text displayed under the main heading.',
				'placeholder'   => 'Guiding You Through Every Step...',
			),
			array(
				'key'           => 'field_contact_hero_bg',
				'label'         => 'Banner Image',
				'name'          => 'contact_hero_bg',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Upload a high-resolution background image for the hero section.',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 1,
	) );

	// 2. Contact Information Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_info',
		'title'  => 'Contact Page - Contact Information',
		'fields' => array(
			array(
				'key'           => 'field_contact_address',
				'label'         => 'Office Address',
				'name'          => 'contact_address',
				'type'          => 'textarea',
				'default_value' => '701, Al Jawahara building, Bank Street, Mankool Bur, Dubai.',
				'instructions'  => 'The physical address of the office.',
				'placeholder'   => '701, Al Jawahara building, Bank Street, Mankool Bur, Dubai.',
			),
			array(
				'key'           => 'field_contact_phone',
				'label'         => 'Primary Phone Number',
				'name'          => 'contact_phone',
				'type'          => 'text',
				'default_value' => '+971 58 583 0143',
				'instructions'  => 'The main phone number for client inquiries.',
				'placeholder'   => 'e.g. +971 58 583 0143',
			),
			array(
				'key'           => 'field_contact_phone_secondary',
				'label'         => 'Secondary Phone Number',
				'name'          => 'contact_phone_secondary',
				'type'          => 'text',
				'default_value' => '',
				'instructions'  => 'An alternate phone number (optional).',
				'placeholder'   => 'e.g. +971 50 123 4567',
			),
			array(
				'key'           => 'field_contact_email',
				'label'         => 'Email Address',
				'name'          => 'contact_email',
				'type'          => 'text',
				'default_value' => 'enquiry@casaviewrealestate.ae',
				'instructions'  => 'The primary email address for client contact.',
				'placeholder'   => 'e.g. enquiry@casaviewrealestate.ae',
			),
			array(
				'key'           => 'field_contact_email_secondary',
				'label'         => 'Additional Email Address (optional)',
				'name'          => 'contact_email_secondary',
				'type'          => 'text',
				'default_value' => '',
				'instructions'  => 'An alternate email address for client contact (optional).',
				'placeholder'   => 'e.g. support@casaviewrealestate.ae',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 2,
	) );

	// 3. Contact Form & Description Settings Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_form',
		'title'  => 'Contact Page - Form & Description Area',
		'fields' => array(
			array(
				'key'           => 'field_contact_intro_title',
				'label'         => 'Form Section Title',
				'name'          => 'contact_intro_title',
				'type'          => 'text',
				'default_value' => "Let's Find Your Perfect Home â€” Together",
				'instructions'  => 'The main heading displayed above the description in the contact section.',
				'placeholder'   => "Let's Find Your Perfect Home â€” Together",
			),
			array(
				'key'           => 'field_contact_intro_text',
				'label'         => 'Form Section Description',
				'name'          => 'contact_intro_text',
				'type'          => 'textarea',
				'default_value' => "Whether you're ready to find your dream home, list your property, or simply have a question about the real estate market, we're here to help. At PRWorks Real Estate, our friendly and experienced team is ready to guide you every step of the way.",
				'instructions'  => 'The description text displayed above the contact info box.',
				'placeholder'   => 'Enter description copy...',
			),
			array(
				'key'           => 'field_contact_form_shortcode',
				'label'         => 'Contact Form 7 Shortcode',
				'name'          => 'contact_form_shortcode',
				'type'          => 'text',
				'instructions'  => 'Enter the Contact Form 7 shortcode to render the form.',
				'placeholder'   => 'e.g. [contact-form-7 id="123" title="Contact form 1"]',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 3,
	) );

	// 4. How to Reach Us Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_reach',
		'title'  => 'Contact Page - How to Reach Us',
		'fields' => array(
			array(
				'key'           => 'field_contact_map_iframe',
				'label'         => 'Google Map URL',
				'name'          => 'contact_map_iframe',
				'type'          => 'textarea',
				'instructions'  => 'Paste the Google Maps share link, coordinates, search query, or iframe HTML. The system automatically converts it into a clean, functional map.',
				'placeholder'   => 'e.g. https://www.google.com/maps/...',
			),
			array(
				'key'           => 'field_contact_map_latitude',
				'label'         => 'Latitude (optional)',
				'name'          => 'contact_map_latitude',
				'type'          => 'text',
				'instructions'  => 'The geographical latitude coordinate for the office location.',
				'placeholder'   => 'e.g. 25.116960',
			),
			array(
				'key'           => 'field_contact_map_longitude',
				'label'         => 'Longitude (optional)',
				'name'          => 'contact_map_longitude',
				'type'          => 'text',
				'instructions'  => 'The geographical longitude coordinate for the office location.',
				'placeholder'   => 'e.g. 55.139618',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 4,
	) );

	// 5. Business Information Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_business',
		'title'  => 'Contact Page - Business Information',
		'fields' => array(
			array(
				'key'           => 'field_contact_working_days',
				'label'         => 'Working Days',
				'name'          => 'contact_working_days',
				'type'          => 'text',
				'default_value' => 'Monday - Saturday',
				'instructions'  => 'The days of the week the office is open.',
				'placeholder'   => 'e.g. Monday - Saturday',
			),
			array(
				'key'           => 'field_contact_opening_time',
				'label'         => 'Opening Time',
				'name'          => 'contact_opening_time',
				'type'          => 'text',
				'default_value' => '9:00 AM',
				'instructions'  => 'The opening hour of the office.',
				'placeholder'   => 'e.g. 9:00 AM',
			),
			array(
				'key'           => 'field_contact_closing_time',
				'label'         => 'Closing Time',
				'name'          => 'contact_closing_time',
				'type'          => 'text',
				'default_value' => '6:00 PM',
				'instructions'  => 'The closing hour of the office.',
				'placeholder'   => 'e.g. 6:00 PM',
			),
			array(
				'key'           => 'field_contact_hours',
				'label'         => 'Working Hours (Legacy / Full Text)',
				'name'          => 'contact_hours',
				'type'          => 'text',
				'default_value' => 'Monday - Saturday: 9:00 AM - 6:00 PM',
				'instructions'  => 'Full text representing working hours. Used as a fallback if individual day/time fields are left empty.',
				'placeholder'   => 'e.g. Monday - Saturday: 9:00 AM - 6:00 PM',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 5,
	) );

	// 6. Social Media Links Metabox
	acf_add_local_field_group( array(
		'key'    => 'group_contact_social',
		'title'  => 'Contact Page - Social Media Links',
		'fields' => array(
			array(
				'key'           => 'field_contact_social_facebook',
				'label'         => 'Facebook Page Link',
				'name'          => 'contact_social_facebook',
				'type'          => 'text',
				'instructions'  => 'The full URL of the Facebook page.',
				'placeholder'   => 'e.g. https://facebook.com/username',
			),
			array(
				'key'           => 'field_contact_social_instagram',
				'label'         => 'Instagram Profile Link',
				'name'          => 'contact_social_instagram',
				'type'          => 'text',
				'instructions'  => 'The full URL of the Instagram profile.',
				'placeholder'   => 'e.g. https://instagram.com/username',
			),
			array(
				'key'           => 'field_contact_social_youtube',
				'label'         => 'YouTube Channel Link',
				'name'          => 'contact_social_youtube',
				'type'          => 'text',
				'instructions'  => 'The full URL of the YouTube channel.',
				'placeholder'   => 'e.g. https://youtube.com/c/channelname',
			),
			array(
				'key'           => 'field_contact_social_linkedin',
				'label'         => 'LinkedIn Profile Link',
				'name'          => 'contact_social_linkedin',
				'type'          => 'text',
				'instructions'  => 'The full URL of the LinkedIn profile or company page.',
				'placeholder'   => 'e.g. https://linkedin.com/company/username',
			),
			array(
				'key'           => 'field_contact_social_twitter',
				'label'         => 'X / Twitter Profile Link',
				'name'          => 'contact_social_twitter',
				'type'          => 'text',
				'instructions'  => 'The full URL of the X (formerly Twitter) profile.',
				'placeholder'   => 'e.g. https://x.com/username',
			),
			array(
				'key'           => 'field_contact_social_whatsapp',
				'label'         => 'WhatsApp Contact Link',
				'name'          => 'contact_social_whatsapp',
				'type'          => 'text',
				'instructions'  => 'The full click-to-chat URL for WhatsApp (including country code).',
				'placeholder'   => 'e.g. https://wa.me/971585830143',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-contact.php',
				),
			),
		),
		'menu_order' => 6,
	) );
}
add_action( 'acf/init', 'casaview_register_contact_page_field_group' );

/**
 * Programmatically create the Contact Page under /contact/ if missing.
 */
function casaview_create_contact_page() {
	$page_slug  = 'contact';
	$page_check = get_page_by_path( $page_slug );

	if ( ! isset( $page_check->ID ) ) {
		$page_id = wp_insert_post( array(
			'post_title'   => 'Contact',
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => $page_slug,
		) );
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-contact.php' );
		}
	} else {
		// Ensure it maps to the correct page template
		$current_template = get_post_meta( $page_check->ID, '_wp_page_template', true );
		if ( $current_template !== 'page-contact.php' ) {
			update_post_meta( $page_check->ID, '_wp_page_template', 'page-contact.php' );
		}
	}
}
add_action( 'init', 'casaview_create_contact_page', 30 );

/**
 * Register the SEO Settings Metabox for Property posts and the Contact Page template.
 */
function casaview_register_seo_metabox( $post_type, $post ) {
	if ( $post_type === 'property' || ( $post_type === 'page' && in_array( get_post_meta( $post->ID, '_wp_page_template', true ), array( 'page-contact.php', 'page-about.php' ) ) ) ) {
		add_meta_box(
			'casaview_seo_settings',
			__( 'SEO Settings', 'casaview' ),
			'casaview_render_seo_metabox',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'casaview_register_seo_metabox', 10, 2 );

/**
 * Render the SEO Settings metabox fields with dynamic character counters.
 */
function casaview_render_seo_metabox( $post ) {
	// Nonce for verification
	wp_nonce_field( 'casaview_save_seo_metabox', 'casaview_seo_metabox_nonce' );

	// Retrieve values
	$meta_title = get_post_meta( $post->ID, 'seo_meta_title', true );
	$meta_desc  = get_post_meta( $post->ID, 'seo_meta_description', true );

	// Pre-fill placeholder calculation
	$site_name = get_bloginfo( 'name' );
	$post_title = get_the_title( $post->ID );
	$placeholder_title = "{$post_title} | {$site_name}";

	$placeholder_desc = '';
	if ( $post->post_type === 'property' ) {
		$content = $post->post_content;
		if ( empty( $content ) ) {
			$content = get_post_meta( $post->ID, 'property_description', true );
		}
	} else {
		$template = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( $template === 'page-contact.php' ) {
			$content = get_field( 'contact_intro_text', $post->ID );
		} elseif ( $template === 'page-about.php' ) {
			$content = get_field( 'about_company_description', $post->ID );
		}
		if ( empty( $content ) ) {
			$content = $post->post_content;
		}
	}
	if ( ! empty( $content ) ) {
		$placeholder_desc = wp_strip_all_tags( $content );
		$placeholder_desc = mb_strimwidth( $placeholder_desc, 0, 155, '' );
	}
	if ( empty( $placeholder_desc ) ) {
		$placeholder_desc = __( 'Enter a custom meta description for search engines.', 'casaview' );
	}

	?>
	<div class="casaview-seo-box-container" style="padding: 10px 0;">
		<!-- Meta Title -->
		<div style="margin-bottom: 20px;">
			<label for="casaview_seo_meta_title" style="display:block; font-weight:700; margin-bottom:8px; font-size:13px; color:#1c1d21;">
				<?php _e( 'Meta Title', 'casaview' ); ?>
			</label>
			<input type="text" 
				id="casaview_seo_meta_title" 
				name="seo_meta_title" 
				value="<?php echo esc_attr( $meta_title ); ?>" 
				placeholder="<?php echo esc_attr( $placeholder_title ); ?>" 
				style="width: 100%; height: 40px; padding: 10px; border: 1px solid #ccd0d4; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0,0,0,.07); font-size: 14px;"
			/>
			<div style="margin-top: 5px; font-size: 12px; color: #646970; display: flex; justify-content: space-between;">
				<span><?php _e( 'Recommended: 50â€“60 characters.', 'casaview' ); ?></span>
				<span>
					<?php _e( 'Length:', 'casaview' ); ?> 
					<strong id="seo-title-counter" style="color: #646970;">0</strong> / 60
				</span>
			</div>
		</div>

		<!-- Meta Description -->
		<div>
			<label for="casaview_seo_meta_description" style="display:block; font-weight:700; margin-bottom:8px; font-size:13px; color:#1c1d21;">
				<?php _e( 'Meta Description', 'casaview' ); ?>
			</label>
			<textarea 
				id="casaview_seo_meta_description" 
				name="seo_meta_description" 
				rows="3" 
				placeholder="<?php echo esc_attr( $placeholder_desc ); ?>" 
				style="width: 100%; padding: 10px; border: 1px solid #ccd0d4; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0,0,0,.07); font-size: 14px; line-height: 1.5; resize: vertical;"
			><?php echo esc_textarea( $meta_desc ); ?></textarea>
			<div style="margin-top: 5px; font-size: 12px; color: #646970; display: flex; justify-content: space-between;">
				<span><?php _e( 'Recommended: 150â€“160 characters.', 'casaview' ); ?></span>
				<span>
					<?php _e( 'Length:', 'casaview' ); ?> 
					<strong id="seo-desc-counter" style="color: #646970;">0</strong> / 160
				</span>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		function checkCounters() {
			// Title count
			var titleVal = $('#casaview_seo_meta_title').val();
			var titleLen = titleVal.length;
			if (titleLen === 0) {
				titleLen = $('#casaview_seo_meta_title').attr('placeholder').length;
			}
			$('#seo-title-counter').text(titleLen);
			if (titleLen >= 50 && titleLen <= 60) {
				$('#seo-title-counter').css('color', '#27ae60'); // Green
			} else {
				$('#seo-title-counter').css('color', '#d94f4f'); // Red
			}

			// Description count
			var descVal = $('#casaview_seo_meta_description').val();
			var descLen = descVal.length;
			if (descLen === 0) {
				descLen = $('#casaview_seo_meta_description').attr('placeholder').length;
			}
			$('#seo-desc-counter').text(descLen);
			if (descLen >= 150 && descLen <= 160) {
				$('#seo-desc-counter').css('color', '#27ae60'); // Green
			} else {
				$('#seo-desc-counter').css('color', '#d94f4f'); // Red
			}
		}

		$('#casaview_seo_meta_title, #casaview_seo_meta_description').on('input keyup change', checkCounters);
		checkCounters();
	});
	</script>
	<?php
}

/**
 * Save the SEO metabox values securely with sanitization and validation.
 */
function casaview_save_seo_metabox( $post_id ) {
	// Security Checks
	if ( ! isset( $_POST['casaview_seo_metabox_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['casaview_seo_metabox_nonce'], 'casaview_save_seo_metabox' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'page', 'property' ) ) ) {
		return;
	}

	// 1. Process Meta Title
	$meta_title = isset( $_POST['seo_meta_title'] ) ? sanitize_text_field( $_POST['seo_meta_title'] ) : '';
	if ( empty( $meta_title ) ) {
		// Auto pre-fill if left empty
		$site_name = get_bloginfo( 'name' );
		$post_title = get_the_title( $post_id );
		$meta_title = "{$post_title} | {$site_name}";
	}
	update_post_meta( $post_id, 'seo_meta_title', $meta_title );

	// 2. Process Meta Description
	$meta_desc = isset( $_POST['seo_meta_description'] ) ? sanitize_textarea_field( $_POST['seo_meta_description'] ) : '';
	if ( empty( $meta_desc ) ) {
		// Auto pre-fill if left empty
		$post = get_post( $post_id );
		$content = $post->post_content;
		if ( $post_type === 'property' ) {
			if ( empty( $content ) ) {
				$content = get_post_meta( $post_id, 'property_description', true );
			}
		} else {
			// For contact or about page
			$template = get_post_meta( $post_id, '_wp_page_template', true );
			if ( $template === 'page-contact.php' ) {
				$intro_text = get_field( 'contact_intro_text', $post_id );
				if ( ! empty( $intro_text ) ) {
					$content = $intro_text;
				}
			} elseif ( $template === 'page-about.php' ) {
				$intro_text = get_field( 'about_company_description', $post_id );
				if ( ! empty( $intro_text ) ) {
					$content = $intro_text;
				}
			}
		}

		if ( ! empty( $content ) ) {
			$meta_desc = wp_strip_all_tags( $content );
			$meta_desc = mb_strimwidth( $meta_desc, 0, 155, '' );
		}
	}
	update_post_meta( $post_id, 'seo_meta_description', $meta_desc );
}
add_action( 'save_post', 'casaview_save_seo_metabox' );

/**
 * Register local ACF field group for the About Page template.
 */
function casaview_register_about_page_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'    => 'group_about_page_settings',
		'title'  => 'About Page - Template Settings',
		'fields' => array(
			// Tab 1: Hero Section
			array(
				'key'   => 'field_about_tab_hero',
				'label' => 'Hero Section',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_hero_enable',
				'label'         => 'Enable Hero Section',
				'name'          => 'about_hero_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_hero_title',
				'label'         => 'Page Title',
				'name'          => 'about_hero_title',
				'type'          => 'text',
				'default_value' => 'About Us',
				'instructions'  => 'The main heading displayed on the about hero banner.',
				'placeholder'   => 'About Us',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_hero_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_hero_subtitle',
				'label'         => 'Subtitle',
				'name'          => 'about_hero_subtitle',
				'type'          => 'text',
				'default_value' => 'Guiding You Through Every Step of Your Property Journey with Care and Clarity',
				'instructions'  => 'The secondary text displayed under the main heading.',
				'placeholder'   => 'Guiding You Through Every Step...',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_hero_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_hero_bg',
				'label'         => 'Background Banner Image',
				'name'          => 'about_hero_bg',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Upload a high-resolution background image for the hero section banner.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_hero_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_hero_breadcrumb_title',
				'label'         => 'Breadcrumb Title',
				'name'          => 'about_hero_breadcrumb_title',
				'type'          => 'text',
				'default_value' => 'About',
				'instructions'  => 'The text used in the breadcrumb trailing position.',
				'placeholder'   => 'About',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_hero_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 2: About Company Section
			array(
				'key'   => 'field_about_tab_company',
				'label' => 'About Company',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_company_enable',
				'label'         => 'Enable About Company Section',
				'name'          => 'about_company_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_company_section_title',
				'label'         => 'Section Title / Label',
				'name'          => 'about_company_section_title',
				'type'          => 'text',
				'default_value' => 'WHO WE ARE',
				'instructions'  => 'Small label displayed above the main heading (e.g. WHO WE ARE).',
				'placeholder'   => 'WHO WE ARE',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_company_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_company_main_heading',
				'label'         => 'Main Heading',
				'name'          => 'about_company_main_heading',
				'type'          => 'text',
				'default_value' => 'PRWorks Real Estate',
				'instructions'  => 'Main prominent heading for the company section.',
				'placeholder'   => 'PRWorks Real Estate',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_company_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_company_description',
				'label'         => 'Company Description',
				'name'          => 'about_company_description',
				'type'          => 'wysiwyg',
				'instructions'  => 'Detailed text editor for the about description block.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_company_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_company_featured_image',
				'label'         => 'Featured Image',
				'name'          => 'about_company_featured_image',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Main featured image on the side of the content.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_company_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_company_secondary_image',
				'label'         => 'Secondary Image (Optional)',
				'name'          => 'about_company_secondary_image',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Optional second image (e.g. for overlapping layouts).',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_company_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 3: Vision & Mission Section
			array(
				'key'   => 'field_about_tab_vision_mission',
				'label' => 'Vision & Mission',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_vision_mission_enable',
				'label'         => 'Enable Vision & Mission Section',
				'name'          => 'about_vision_mission_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_vision_title',
				'label'         => 'Vision Title',
				'name'          => 'about_vision_title',
				'type'          => 'text',
				'default_value' => 'Our Vision',
				'instructions'  => 'Title for the Vision sub-section.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_vision_mission_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_vision_description',
				'label'         => 'Vision Description',
				'name'          => 'about_vision_description',
				'type'          => 'textarea',
				'default_value' => 'To be the most trusted and globally recognized ultra-luxury real estate firm based in Dubai, known for setting benchmarks in exclusivity, service, and performance.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_vision_mission_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_mission_title',
				'label'         => 'Mission Title',
				'name'          => 'about_mission_title',
				'type'          => 'text',
				'default_value' => 'Our Promise',
				'instructions'  => 'Title for the Mission / Promise sub-section.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_vision_mission_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_mission_description',
				'label'         => 'Mission Description',
				'name'          => 'about_mission_description',
				'type'          => 'textarea',
				'default_value' => 'Whether you\'re acquiring a beachfront palace, investing in a high-rise trophy asset, or divesting discreetly, PRWorks Real Estate LLC offers an experience as elevated as the properties we represent.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_vision_mission_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_vision_mission_image',
				'label'         => 'Section Image',
				'name'          => 'about_vision_mission_image',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Image displayed alongside the vision and mission content cards.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_vision_mission_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 4: Why Choose Us Section
			array(
				'key'   => 'field_about_tab_why_choose_us',
				'label' => 'Why Choose Us',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_why_choose_us_enable',
				'label'         => 'Enable Why Choose Us Section',
				'name'          => 'about_why_choose_us_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_why_choose_us_section_title',
				'label'         => 'Section Title / Label',
				'name'          => 'about_why_choose_us_section_title',
				'type'          => 'text',
				'default_value' => 'WHY CHOOSE US?',
				'instructions'  => 'Small label displayed above the main heading.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_why_choose_us_main_heading',
				'label'         => 'Main Heading',
				'name'          => 'about_why_choose_us_main_heading',
				'type'          => 'text',
				'default_value' => 'Why Choose PRWorks Real Estate?',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_why_choose_us_description',
				'label'         => 'Section Intro Description',
				'name'          => 'about_why_choose_us_description',
				'type'          => 'textarea',
				'default_value' => 'Our brand reflects refined taste, exceptional service, and a deep understanding of the lifestyle ambitions of ultra-high-net-worth individuals.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Item 1
			array(
				'key'           => 'field_why_choose_item_1_icon',
				'label'         => 'Item 1 Icon Class or Image URL',
				'name'          => 'why_choose_item_1_icon',
				'type'          => 'text',
				'default_value' => 'fa-solid fa-globe',
				'instructions'  => 'Enter a Font Awesome icon class (e.g. fa-solid fa-globe) or a direct image URL.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_1_title',
				'label'         => 'Item 1 Title',
				'name'          => 'why_choose_item_1_title',
				'type'          => 'text',
				'default_value' => 'Global Network & Local Mastery',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_1_desc',
				'label'         => 'Item 1 Description',
				'name'          => 'why_choose_item_1_description',
				'type'          => 'textarea',
				'default_value' => 'While rooted in Dubai, our reach extends across Europe, the UK, the US, and key investment zones in Asia. Through our global network of partners, family offices, and private banks, we provide access to off-market opportunities.',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Item 2
			array(
				'key'           => 'field_why_choose_item_2_icon',
				'label'         => 'Item 2 Icon Class or Image URL',
				'name'          => 'why_choose_item_2_icon',
				'type'          => 'text',
				'default_value' => 'fa-solid fa-users',
				'instructions'  => 'Enter a Font Awesome icon class or image URL.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_2_title',
				'label'         => 'Item 2 Title',
				'name'          => 'why_choose_item_2_title',
				'type'          => 'text',
				'default_value' => 'Leadership & Team',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_2_desc',
				'label'         => 'Item 2 Description',
				'name'          => 'why_choose_item_2_description',
				'type'          => 'textarea',
				'default_value' => 'Our team is composed of senior real estate advisors, multilingual deal-makers, and market analysts with combined experience in luxury real estate, private banking, and hospitality.',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Item 3
			array(
				'key'           => 'field_why_choose_item_3_icon',
				'label'         => 'Item 3 Icon Class or Image URL',
				'name'          => 'why_choose_item_3_icon',
				'type'          => 'text',
				'default_value' => 'fa-solid fa-building-shield',
				'instructions'  => 'Enter a Font Awesome icon class or image URL.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_3_title',
				'label'         => 'Item 3 Title',
				'name'          => 'why_choose_item_3_title',
				'type'          => 'text',
				'default_value' => 'Regulatory Excellence',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_3_desc',
				'label'         => 'Item 3 Description',
				'name'          => 'why_choose_item_3_description',
				'type'          => 'textarea',
				'default_value' => 'We operate in full compliance with UAE real estate laws and the Dubai Land Department (DLD) and RERA, ensuring legal transparency and fiduciary responsibility.',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Item 4
			array(
				'key'           => 'field_why_choose_item_4_icon',
				'label'         => 'Item 4 Icon Class or Image URL',
				'name'          => 'why_choose_item_4_icon',
				'type'          => 'text',
				'default_value' => 'fa-solid fa-award',
				'instructions'  => 'Enter a Font Awesome icon class or image URL.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_4_title',
				'label'         => 'Item 4 Title',
				'name'          => 'why_choose_item_4_title',
				'type'          => 'text',
				'default_value' => 'Our Philosophy',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_why_choose_item_4_desc',
				'label'         => 'Item 4 Description',
				'name'          => 'why_choose_item_4_description',
				'type'          => 'textarea',
				'default_value' => 'We believe luxury is a standard, not a price point. We act not only as brokers but as long-term wealth advisors, portfolio strategists, and trusted partners.',
				'required'      => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_why_choose_us_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 5: Statistics / Counter Section
			array(
				'key'   => 'field_about_tab_statistics',
				'label' => 'Statistics',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_statistics_enable',
				'label'         => 'Enable Statistics Section',
				'name'          => 'about_statistics_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_statistics_bg',
				'label'         => 'Section Background Image',
				'name'          => 'about_statistics_bg',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Upload a background image for the statistics counter section.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Counter 1
			array(
				'key'           => 'field_about_stat_1_num',
				'label'         => 'Counter 1 Number',
				'name'          => 'about_stat_1_number',
				'type'          => 'text',
				'default_value' => '4M+',
				'instructions'  => 'E.g. "4M+"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_stat_1_lbl',
				'label'         => 'Counter 1 Label',
				'name'          => 'about_stat_1_label',
				'type'          => 'text',
				'default_value' => 'Property Ready (sq. ft.)',
				'instructions'  => 'E.g. "Property Ready (sq. ft.)"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Counter 2
			array(
				'key'           => 'field_about_stat_2_num',
				'label'         => 'Counter 2 Number',
				'name'          => 'about_stat_2_number',
				'type'          => 'text',
				'default_value' => '18K+',
				'instructions'  => 'E.g. "18K+"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_stat_2_lbl',
				'label'         => 'Counter 2 Label',
				'name'          => 'about_stat_2_label',
				'type'          => 'text',
				'default_value' => 'Happy Customers',
				'instructions'  => 'E.g. "Happy Customers"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Counter 3
			array(
				'key'           => 'field_about_stat_3_num',
				'label'         => 'Counter 3 Number',
				'name'          => 'about_stat_3_number',
				'type'          => 'text',
				'default_value' => '20M+',
				'instructions'  => 'E.g. "20M+"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_stat_3_lbl',
				'label'         => 'Counter 3 Label',
				'name'          => 'about_stat_3_label',
				'type'          => 'text',
				'default_value' => 'Property Value Managed',
				'instructions'  => 'E.g. "Property Value Managed"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			// Counter 4
			array(
				'key'           => 'field_about_stat_4_num',
				'label'         => 'Counter 4 Number',
				'name'          => 'about_stat_4_number',
				'type'          => 'text',
				'default_value' => '25+',
				'instructions'  => 'E.g. "25+"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_stat_4_lbl',
				'label'         => 'Counter 4 Label',
				'name'          => 'about_stat_4_label',
				'type'          => 'text',
				'default_value' => 'Trusted Real Estate Experts',
				'instructions'  => 'E.g. "Trusted Real Estate Experts"',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_statistics_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 6: Team Section
			array(
				'key'   => 'field_about_tab_team',
				'label' => 'Team Members',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_team_enable',
				'label'         => 'Enable Team Section',
				'name'          => 'about_team_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_team_section_title',
				'label'         => 'Section Title / Label',
				'name'          => 'about_team_section_title',
				'type'          => 'text',
				'default_value' => 'MEET THE TEAM',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_team_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_team_main_heading',
				'label'         => 'Main Heading',
				'name'          => 'about_team_main_heading',
				'type'          => 'text',
				'default_value' => 'Meet Our Reliable Agents',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_team_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'               => 'field_about_team_list',
				'label'             => 'Team Members List',
				'name'              => 'about_team_list',
				'type'              => 'repeater',
				'layout'            => 'block',
				'button_label'      => 'Add Team Member',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_team_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
				'sub_fields'        => array(
					array(
						'key'           => 'field_about_team_photo',
						'label'         => 'Photo',
						'name'          => 'photo',
						'type'          => 'image',
						'return_format' => 'url',
						'required'      => 1,
					),
					array(
						'key'           => 'field_about_team_name',
						'label'         => 'Name',
						'name'          => 'name',
						'type'          => 'text',
						'required'      => 1,
					),
					array(
						'key'           => 'field_about_team_designation',
						'label'         => 'Designation',
						'name'          => 'designation',
						'type'          => 'text',
						'required'      => 1,
					),
					array(
						'key'           => 'field_about_team_description',
						'label'         => 'Short Bio / Description',
						'name'          => 'description',
						'type'          => 'textarea',
					),
					array(
						'key'           => 'field_about_team_fb',
						'label'         => 'Facebook URL',
						'name'          => 'social_facebook',
						'type'          => 'text',
						'placeholder'   => 'https://...',
					),
					array(
						'key'           => 'field_about_team_ig',
						'label'         => 'Instagram URL',
						'name'          => 'social_instagram',
						'type'          => 'text',
						'placeholder'   => 'https://...',
					),
					array(
						'key'           => 'field_about_team_li',
						'label'         => 'LinkedIn URL',
						'name'          => 'social_linkedin',
						'type'          => 'text',
						'placeholder'   => 'https://...',
					),
					array(
						'key'           => 'field_about_team_tw',
						'label'         => 'Twitter / X URL',
						'name'          => 'social_twitter',
						'type'          => 'text',
						'placeholder'   => 'https://...',
					),
				),
			),

			// Tab 7: Call To Action Section
			array(
				'key'   => 'field_about_tab_cta',
				'label' => 'Call To Action',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_cta_enable',
				'label'         => 'Enable CTA Section',
				'name'          => 'about_cta_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_cta_heading',
				'label'         => 'CTA Heading',
				'name'          => 'about_cta_heading',
				'type'          => 'text',
				'default_value' => 'Ready to Find Your Next Home?',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_cta_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_cta_description',
				'label'         => 'CTA Description',
				'name'          => 'about_cta_description',
				'type'          => 'textarea',
				'default_value' => 'Your perfect property is just a conversation away. Whether you\'re searching for a stylish apartment, a family villa, or an investment opportunity, our dedicated team is here to guide you every step of the way.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_cta_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_cta_btn_text',
				'label'         => 'Button Text',
				'name'          => 'about_cta_btn_text',
				'type'          => 'text',
				'default_value' => 'Contact Us',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_cta_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_cta_btn_url',
				'label'         => 'Button URL',
				'name'          => 'about_cta_btn_url',
				'type'          => 'text',
				'default_value' => '/contact/',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_cta_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_cta_bg',
				'label'         => 'CTA Background Image',
				'name'          => 'about_cta_bg',
				'type'          => 'image',
				'return_format' => 'url',
				'instructions'  => 'Upload a background image for the CTA section banner.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_cta_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),

			// Tab 8: Contact Form Section
			array(
				'key'   => 'field_about_tab_contact_form',
				'label' => 'Contact Form',
				'type'  => 'tab',
			),
			array(
				'key'           => 'field_about_contact_form_enable',
				'label'         => 'Enable Contact Form Section',
				'name'          => 'about_contact_form_enable',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_about_contact_form_title',
				'label'         => 'Section Heading',
				'name'          => 'about_contact_form_title',
				'type'          => 'text',
				'default_value' => 'Get in Touch',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_contact_form_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_contact_form_description',
				'label'         => 'Section Description',
				'name'          => 'about_contact_form_description',
				'type'          => 'textarea',
				'default_value' => 'Contact our luxury real estate team today.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_contact_form_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'           => 'field_about_contact_form_cf7',
				'label'         => 'Contact Form 7 Shortcode',
				'name'          => 'about_contact_form_cf7',
				'type'          => 'text',
				'instructions'  => 'Paste your Contact Form 7 shortcode here (e.g. [contact-form-7 id="123" title="About Form"]).',
				'placeholder'   => '[contact-form-7 id="..."]',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_about_contact_form_enable',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-about.php',
				),
			),
		),
		'menu_order' => 1,
	) );
}
add_action( 'acf/init', 'casaview_register_about_page_field_groups' );

/**
 * Programmatically create the About Page under /about/ if missing.
 */
function casaview_create_about_page() {
	// First check if any page with template 'page-about.php' exists (including trash)
	$existing_pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-about.php',
		'posts_per_page' => 1,
	) );
	if ( ! empty( $existing_pages ) ) {
		return; // An About page already exists! Do not create another one.
	}

	$page_slug  = 'about';
	$page_check = get_page_by_path( $page_slug );

	if ( ! isset( $page_check->ID ) ) {
		$page_id = wp_insert_post( array(
			'post_title'   => 'About',
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => $page_slug,
		) );
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-about.php' );
		}
	} else {
		// Ensure it maps to the correct page template
		$current_template = get_post_meta( $page_check->ID, '_wp_page_template', true );
		if ( $current_template !== 'page-about.php' ) {
			update_post_meta( $page_check->ID, '_wp_page_template', 'page-about.php' );
		}
	}
}
add_action( 'init', 'casaview_create_about_page', 30 );

/**
 * Dynamic ACF format value filter to update database-stored branding references on the frontend.
 * This guarantees "PRWorks Real Estate" is displayed even if the database contains older values.
 */
function casaview_filter_acf_branding( $value, $post_id, $field ) {
	if ( is_string( $value ) ) {
		$value = str_ireplace( 'Casa View Real Estate', 'PRWorks Real Estate', $value );
		$value = str_ireplace( 'Casa View', 'PRWorks Real Estate', $value );
		$value = str_ireplace( 'CasaView', 'PRWorks Real Estate', $value );
	}
	return $value;
}
if ( ! is_admin() ) {
	add_filter( 'acf/format_value', 'casaview_filter_acf_branding', 99, 3 );
}

/**
 * Helper to fetch categories assigned to properties of a specific listing type.
 */
function casaview_get_categories_by_listing_type( $listing_type ) {
	$prop_ids = get_posts( array(
		'post_type'      => 'property',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'     => 'listing_type',
				'value'   => $listing_type === 'buy' || $listing_type === 'sale' ? array( 'buy', 'sale' ) : 'rent',
				'compare' => $listing_type === 'buy' || $listing_type === 'sale' ? 'IN' : '=',
			)
		)
	) );
	if ( empty( $prop_ids ) ) {
		return array();
	}
	$terms = wp_get_object_terms( $prop_ids, 'property_category' );
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	return $terms;
}

/**
 * =========================================================================
 * REDESIGN HOME PAGE BACKEND: OUR SERVICES & FAQ SECTION
 * =========================================================================
 */

// Enqueue styles & script for homepage backend editor
function casaview_enqueue_homepage_backend_redesign_assets() {
	global $pagenow, $post;
	
	// Only load on post/page edit screen
	if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
		return;
	}
	
	// Retrieve the post ID reliably
	$post_id = null;
	if ( isset( $_GET['post'] ) ) {
		$post_id = intval( $_GET['post'] );
	} elseif ( is_object( $post ) && isset( $post->ID ) ) {
		$post_id = $post->ID;
	}
	
	if ( ! $post_id ) {
		return;
	}
	
	// Check if this page is the front page or page ID 93
	$frontpage_id = intval( get_option( 'page_on_front' ) );
	if ( $post_id !== $frontpage_id && $post_id !== 93 ) {
		return;
	}
	
	// Enqueue FontAwesome for drag, delete, and header icons
	wp_enqueue_style( 'font-awesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
	
	// Render inline custom styles to style ACF repeaters to match Floor Plans design
	add_action( 'admin_head', 'casaview_render_homepage_backend_redesign_styles' );
}
add_action( 'acf/input/admin_enqueue_scripts', 'casaview_enqueue_homepage_backend_redesign_assets' );

// Print CSS styles targeting specific homepage ACF repeaters to match Floor Plans
function casaview_render_homepage_backend_redesign_styles() {
	?>
	<style>
		/* Custom Backend styles for Homepage Settings (Our Services & FAQ) */
		.acf-field-repeater[data-name="our_services_repeater"],
		.acf-field-repeater[data-name="redesigned_faq_repeater"] {
			padding: 0 !important;
			border: none !important;
			background: transparent !important;
			margin-top: 15px !important;
			margin-bottom: 20px !important;
		}

		/* Outer wrapper adjustments */
		.acf-field-repeater[data-name="our_services_repeater"] > .acf-label,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] > .acf-label {
			display: none !important; /* we show our custom h3 headers instead */
		}

		/* ACF Table overrides */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table {
			width: 100% !important;
			border-collapse: collapse !important;
			margin-bottom: 15px !important;
			border: none !important;
			box-shadow: none !important;
		}

		/* Th elements */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table > thead > tr > th,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table > thead > tr > th {
			background: #f1f2f6 !important;
			padding: 10px !important;
			text-align: left !important;
			border-bottom: 2px solid #ddd !important;
			font-weight: 600 !important;
			color: #23282d !important;
			border-top: none !important;
			border-left: none !important;
			border-right: none !important;
		}

		/* Td elements */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table > tbody > tr > td,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table > tbody > tr > td {
			padding: 10px !important;
			border-bottom: 1px solid #eee !important;
			vertical-align: top !important;
			background: #fff !important;
			border-top: none !important;
			border-left: none !important;
			border-right: none !important;
		}

		/* Drag handle / order column */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table > tbody > tr > td.acf-row-handle.order,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table > tbody > tr > td.acf-row-handle.order {
			background: #f8f9fa !important;
			color: #a4b0be !important;
			font-weight: 600 !important;
			vertical-align: middle !important;
			text-align: center !important;
			width: 35px !important;
			border-right: 1px solid #eee !important;
			cursor: move !important;
			border-left: none !important;
		}

		/* Inputs, Textareas, Selects */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table input,
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table textarea,
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table select,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table input,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table textarea,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table select {
			width: 100% !important;
			box-sizing: border-box !important;
			border: 1px solid #ccd0d4 !important;
			border-radius: 4px !important;
			padding: 6px 8px !important;
			font-size: 13px !important;
			color: #32373c !important;
		}

		/* Focus state */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table input:focus,
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table textarea:focus,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table input:focus,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table textarea:focus {
			border-color: #c5a880 !important;
			box-shadow: 0 0 0 1px #c5a880 !important;
			outline: none !important;
		}

		/* Add Button styling */
		.acf-field-repeater[data-name="our_services_repeater"] .acf-actions .acf-button,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] .acf-actions .acf-button {
			background: #c5a880 !important;
			border-color: #b59870 !important;
			color: #fff !important;
			box-shadow: none !important;
			text-shadow: none !important;
			font-weight: 600 !important;
			padding: 6px 14px !important;
			font-size: 13px !important;
			line-height: normal !important;
			border-radius: 3px !important;
			display: inline-block !important;
			cursor: pointer !important;
			text-decoration: none !important;
		}
		.acf-field-repeater[data-name="our_services_repeater"] .acf-actions .acf-button:hover,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] .acf-actions .acf-button:hover {
			background: #b59870 !important;
			border-color: #a58860 !important;
		}

		/* Remove row button */
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table td.acf-row-handle.remove,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table td.acf-row-handle.remove {
			vertical-align: middle !important;
			text-align: center !important;
			width: 30px !important;
		}
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table td.acf-row-handle.remove a.acf-icon,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table td.acf-row-handle.remove a.acf-icon {
			border: none !important;
			background: transparent !important;
			box-shadow: none !important;
			color: #ea2027 !important;
			font-size: 18px !important;
			cursor: pointer !important;
			width: auto !important;
			height: auto !important;
			line-height: normal !important;
			display: inline-block !important;
		}
		.acf-field-repeater[data-name="our_services_repeater"] table.acf-table td.acf-row-handle.remove a.acf-icon::before,
		.acf-field-repeater[data-name="redesigned_faq_repeater"] table.acf-table td.acf-row-handle.remove a.acf-icon::before {
			content: "\f056" !important; /* fa-solid fa-circle-minus */
			font-family: "Font Awesome 6 Free" !important;
			font-weight: 900;
		}

		/* Image uploader formatting */
		.acf-field-repeater[data-name="our_services_repeater"] .acf-image-uploader {
			border: 1px dashed #ccd0d4 !important;
			background: #f8f9fa !important;
			padding: 8px !important;
			border-radius: 4px !important;
			text-align: center !important;
			min-height: 80px !important;
			display: flex !important;
			flex-direction: column !important;
			justify-content: center !important;
			align-items: center !important;
		}
		.acf-field-repeater[data-name="our_services_repeater"] .acf-image-uploader .show-if-value img {
			max-width: 100px !important;
			max-height: 80px !important;
			border: 1px solid #eee !important;
			background: #fff !important;
			padding: 4px !important;
			border-radius: 4px !important;
			margin-bottom: 5px !important;
		}
		.acf-field-repeater[data-name="our_services_repeater"] .acf-image-uploader .acf-button.button {
			background: #fff !important;
			border: 1px solid #ccd0d4 !important;
			color: #444 !important;
			box-shadow: 0 1px 0 rgba(0,0,0,0.02) !important;
			font-size: 11px !important;
			padding: 3px 8px !important;
			height: auto !important;
			line-height: normal !important;
		}
	</style>
	<?php
}

// Prepend header to Our Services repeater
function casaview_acf_render_services_header( $field ) {
	echo '<div class="casaview-acf-section-header-wrap" style="margin-bottom: 15px; border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;">';
	echo '<h3 style="font-weight:700; color:#23282d; margin-top:0; font-size:1.3em;"><i class="fa-solid fa-hand-holding-heart" style="color:#c5a880; margin-right:8px;"></i> Our Services</h3>';
	echo '</div>';
}
add_action( 'acf/render_field/name=our_services_repeater', 'casaview_acf_render_services_header', 5 );

// Add and Configure Property Agent Role
function casaview_add_property_agent_role() {
	$role = get_role( 'property_agent' );
	if ( ! $role ) {
		$role = add_role( 'property_agent', __( 'Property Agent', 'casaview' ), array() );
	}
	if ( $role ) {
		$role->add_cap( 'read' );
		$role->add_cap( 'edit_posts' );
		$role->add_cap( 'upload_files' );
		// Removed: $role->add_cap( 'publish_posts' );
		$role->add_cap( 'delete_posts' );
		$role->add_cap( 'delete_published_posts' );
		$role->add_cap( 'edit_published_posts' );
		// Ensure they do not have publish or others_posts
		$role->remove_cap( 'publish_posts' );
		$role->remove_cap( 'edit_others_posts' );
		$role->remove_cap( 'delete_others_posts' );
		$role->remove_cap( 'read_private_posts' );
	}
}
add_action( 'init', 'casaview_add_property_agent_role' );

// Restrict Admin Menus for Property Agent
function casaview_restrict_property_agent_menus() {
	$user = wp_get_current_user();
	if ( in_array( 'property_agent', (array) $user->roles ) ) {
		global $menu;
		
		$allowed_menus = array(
			'upload.php',
			'edit.php?post_type=property',
			'edit.php?post_type=lead',
			'edit.php?post_type=district'
		);

		if ( is_array( $menu ) ) {
			foreach ( $menu as $key => $value ) {
				if ( ! in_array( $value[2], $allowed_menus ) ) {
					remove_menu_page( $value[2] );
				}
			}
		}
	}
}
add_action( 'admin_menu', 'casaview_restrict_property_agent_menus', 999 );

// Restrict Access and Redirect Dashboard for Property Agent
function casaview_restrict_property_agent_access() {
	$user = wp_get_current_user();
	if ( in_array( 'property_agent', (array) $user->roles ) ) {
		global $pagenow;
		
		// Redirect from Dashboard
		if ( $pagenow === 'index.php' ) {
			wp_redirect( admin_url( 'edit.php?post_type=property' ) );
			exit;
		}

		$post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
		if ( isset($_GET['post']) ) {
			$post_type = get_post_type( intval($_GET['post']) );
		}
		
		// If editing a post without explicit post_type in GET, but we are on post-new.php
		if ( $pagenow === 'post-new.php' && empty($post_type) ) {
			$post_type = 'post';
		}

		$allowed_post_types = array( 'property', 'lead', 'district', 'attachment' );
		
		if ( in_array( $pagenow, array( 'edit.php', 'post-new.php', 'post.php' ) ) ) {
			if ( $post_type && ! in_array( $post_type, $allowed_post_types ) ) {
				wp_die( __( 'You do not have permission to access this page.', 'casaview' ) );
			}
		}
	}
}
add_action( 'admin_init', 'casaview_restrict_property_agent_access' );

// Restrict Posts Query to Own Posts for Property Agent
function casaview_restrict_property_agent_queries( $query ) {
	if ( is_admin() && $query->is_main_query() ) {
		$user = wp_get_current_user();
		if ( in_array( 'property_agent', (array) $user->roles ) ) {
			$restrict_types = array( 'property', 'lead', 'district' );
			if ( in_array( $query->get( 'post_type' ), $restrict_types ) ) {
				$query->set( 'author', $user->ID );
			}
		}
	}
}
add_action( 'pre_get_posts', 'casaview_restrict_property_agent_queries' );

// Register "Rejected" post status
function casaview_register_rejected_status() {
	register_post_status( 'rejected', array(
		'label'                     => _x( 'Rejected', 'post' ),
		'public'                    => false,
		'exclude_from_search'       => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>' ),
	) );
}
add_action( 'init', 'casaview_register_rejected_status' );

// Add "Rejected" label to post state in admin
function casaview_display_rejected_state( $states, $post ) {
	if ( get_query_var( 'post_status' ) != 'rejected' && $post->post_status == 'rejected' ) {
		$states['rejected'] = __( 'Rejected', 'casaview' );
	}
	return $states;
}
add_filter( 'display_post_states', 'casaview_display_rejected_state', 10, 2 );

// Add Admin Action Links for Approve and Reject
function casaview_property_row_actions( $actions, $post ) {
	if ( $post->post_type === 'property' && current_user_can( 'publish_posts' ) ) {
		if ( $post->post_status !== 'publish' ) {
			$approve_url = wp_nonce_url( admin_url( 'admin.php?action=casaview_approve_property&post=' . $post->ID ), 'casaview_approve_property_' . $post->ID );
			$actions['approve'] = '<a href="' . esc_url( $approve_url ) . '" style="color: #46b450;">' . __( 'Approve Property', 'casaview' ) . '</a>';
		}
		if ( $post->post_status !== 'rejected' ) {
			$reject_url = wp_nonce_url( admin_url( 'admin.php?action=casaview_reject_property&post=' . $post->ID ), 'casaview_reject_property_' . $post->ID );
			$actions['reject'] = '<a href="' . esc_url( $reject_url ) . '" style="color: #dc3232;">' . __( 'Reject Property', 'casaview' ) . '</a>';
		}
		
		// Add Review Submission link to Edit
		$actions['review'] = '<a href="' . get_edit_post_link( $post->ID ) . '" style="color: #0073aa; font-weight: bold;">' . __( 'Review Submission', 'casaview' ) . '</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'casaview_property_row_actions', 10, 2 );

// Handle Approve Action
function casaview_handle_approve_property() {
	if ( ! current_user_can( 'publish_posts' ) ) {
		wp_die( __( 'You do not have permission to approve this property.', 'casaview' ) );
	}
	$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
	check_admin_referer( 'casaview_approve_property_' . $post_id );
	
	if ( $post_id && get_post_type( $post_id ) === 'property' ) {
		wp_update_post( array(
			'ID'          => $post_id,
			'post_status' => 'publish'
		) );
	}
	wp_redirect( admin_url( 'edit.php?post_type=property' ) );
	exit;
}
add_action( 'admin_action_casaview_approve_property', 'casaview_handle_approve_property' );

// Handle Reject Action
function casaview_handle_reject_property() {
	if ( ! current_user_can( 'publish_posts' ) ) {
		wp_die( __( 'You do not have permission to reject this property.', 'casaview' ) );
	}
	$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
	check_admin_referer( 'casaview_reject_property_' . $post_id );
	
	if ( $post_id && get_post_type( $post_id ) === 'property' ) {
		wp_update_post( array(
			'ID'          => $post_id,
			'post_status' => 'rejected'
		) );
	}
	wp_redirect( admin_url( 'edit.php?post_type=property' ) );
	exit;
}
add_action( 'admin_action_casaview_reject_property', 'casaview_handle_reject_property' );

add_action('init', 'casaview_update_faqs_v2');
function casaview_update_faqs_v2() {
    if (!get_option('casaview_faqs_updated_v3')) {
        $front_page_id = get_option('page_on_front');
        if ($front_page_id) {
            $faqs = array(
                array('question' => 'What types of properties are available on PRWorks Real Estate?', 'answer' => '<p>PRWorks Real Estate offers apartments, villas, commercial properties, plots, and rental properties across various locations.</p>'),
                array('question' => 'How can I search for properties on the website?', 'answer' => '<p>You can use the search and advanced filters to search by location, property category, sale/rent type, and other preferences.</p>'),
                array('question' => 'Can I list my property on PRWorks Real Estate?', 'answer' => '<p>Yes. Property owners and agents can submit their properties through the website for review and approval.</p>'),
                array('question' => 'Do I need an account to enquire about a property?', 'answer' => '<p>No. You can contact us directly through the enquiry form, phone number, or email.</p>'),
                array('question' => 'How do I schedule a property visit?', 'answer' => '<p>Open the property details page and contact our team to arrange a convenient viewing appointment.</p>'),
                array('question' => 'Are all properties verified?', 'answer' => '<p>Yes. Our team reviews and verifies property information before publishing it on the website.</p>'),
                array('question' => 'Can I filter properties for sale and rent separately?', 'answer' => '<p>Yes. Use the Sale and Rent tabs and advanced filters to quickly find suitable properties.</p>'),
                array('question' => 'How can I contact PRWorks Real Estate?', 'answer' => '<p>You can reach us through our phone numbers, email address, contact form, or office location provided on the website.</p>')
            );
            update_field('field_redesigned_faq_repeater', $faqs, $front_page_id);
            update_option('casaview_faqs_updated_v3', true);
        }
    }
}

add_action('admin_init', 'casaview_populate_contact_numbers');
function casaview_populate_contact_numbers() {
    if (!get_option('casaview_contact_numbers_populated_v1')) {
        update_option('options_footer_contact_numbers', 2);
        
        update_option('options_footer_contact_numbers_0_phone_number', '+971 58 583 0143');
        update_option('_options_footer_contact_numbers_0_phone_number', 'field_footer_contact_phone_number');
        
        update_option('options_footer_contact_numbers_1_phone_number', '+971 50 123 4567');
        update_option('_options_footer_contact_numbers_1_phone_number', 'field_footer_contact_phone_number');
        
        update_option('_options_footer_contact_numbers', 'field_footer_contact_numbers_repeater');
        
        update_option('casaview_contact_numbers_populated_v1', true);
    }
}

/**
 * Global Design Settings Frontend Integration
 */

// Filter to allow font files upload
function casaview_allow_font_uploads( $mimes ) {
	$mimes['woff']  = 'font/woff';
	$mimes['woff2'] = 'font/woff2';
	$mimes['ttf']   = 'font/ttf';
	$mimes['otf']   = 'font/otf';
	return $mimes;
}
add_filter( 'upload_mimes', 'casaview_allow_font_uploads' );

// Output global design styling override in wp_head
function casaview_output_dynamic_styles() {
	$font_type = get_option( 'casaview_font_type', 'google' );
	$font = get_option( 'casaview_theme_font', 'Manrope' );
	$custom_font_url = get_option( 'casaview_custom_font_url', '' );
	$custom_font_name = get_option( 'casaview_custom_font_name', 'CustomUploadedFont' );
	$primary = get_option( 'casaview_primary_color', '#c5a880' );
	$secondary = get_option( 'casaview_secondary_color', '#f4f5f8' );
	$btn_bg = get_option( 'casaview_button_color', '#FCB71C' );
	$btn_hover = get_option( 'casaview_button_hover_color', '#000000' );
	$secondary_btn = get_option( 'casaview_secondary_button_color', '#000000' );

	$font_family = '';
	if ( $font_type === 'custom' && ! empty( $custom_font_url ) ) {
		$font_family = "'" . esc_attr( $custom_font_name ) . "'";
	} else {
		if ( in_array( $font, array( 'Playfair Display', 'Lora' ) ) ) {
			$font_family = "'{$font}', serif";
		} else {
			$font_family = "'{$font}', sans-serif";
		}
	}

	?>
	<style id="casaview-global-design-settings">
		<?php if ( $font_type === 'custom' && ! empty( $custom_font_url ) ) : ?>
		@font-face {
			font-family: '<?php echo esc_attr( $custom_font_name ); ?>';
			src: url('<?php echo esc_url( $custom_font_url ); ?>');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
		}
		<?php endif; ?>

		:root {
			--theme-font-family: <?php echo $font_family; ?>;
			--theme-primary-color: <?php echo esc_attr( $primary ); ?>;
			--theme-secondary-color: <?php echo esc_attr( $secondary ); ?>;
			--theme-button-bg: <?php echo esc_attr( $btn_bg ); ?>;
			--theme-button-bg-hover: <?php echo esc_attr( $btn_hover ); ?>;

			/* Global CSS variables */
			--primary-color: <?php echo esc_attr( $primary ); ?>;
			--secondary-color: <?php echo esc_attr( $secondary_btn ); ?>;

			/* Map existing Design System variables to dynamic theme settings */
			--font-en: var(--theme-font-family) !important;
			--font-title: var(--theme-font-family) !important;
			--accent-gold: var(--primary-color) !important;
			--accent-gold-hover: var(--secondary-color) !important;
			--bg-secondary: var(--theme-secondary-color) !important;
		}

		/* Global buttons color override */
		.featured-properties-view-all,
		.form-submit-btn,
		.wpcf7-form input[type="submit"],
		.our-services-button,
		.about-cta-btn,
		.modern-search-button,
		.service-card__btn,
		.cta-btn-primary,
		.hp-contact-cta__btn,
		.btn-modal-apply,
		.trending-projects-tab-btn.active {
			background-color: var(--primary-color) !important;
			background: var(--primary-color) !important;
			border-color: var(--primary-color) !important;
		}

		.featured-properties-view-all:hover,
		.form-submit-btn:hover,
		.wpcf7-form input[type="submit"]:hover,
		.our-services-button:hover,
		.about-cta-btn:hover,
		.modern-search-button:hover,
		.service-card__btn:hover,
		.cta-btn-primary:hover,
		.hp-contact-cta__btn:hover,
		.btn-modal-apply:hover {
			background-color: var(--secondary-color) !important;
			background: var(--secondary-color) !important;
			border-color: var(--secondary-color) !important;
		}

		/* Global secondary buttons override */
		.cta-btn-secondary,
		.button-secondary,
		.btn-secondary {
			background-color: transparent !important;
			color: var(--secondary-color) !important;
			border-color: var(--secondary-color) !important;
		}

		.cta-btn-secondary:hover,
		.button-secondary:hover,
		.btn-secondary:hover {
			background-color: var(--secondary-color) !important;
			color: #ffffff !important;
			border-color: var(--secondary-color) !important;
		}

		/* Header CTA button hover color mapping */
		.header-cta {
			border-color: var(--primary-color) !important;
			color: var(--primary-color) !important;
		}
		.header-cta:hover {
			background-color: var(--secondary-color) !important;
			border-color: var(--secondary-color) !important;
			color: #ffffff !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'casaview_output_dynamic_styles', 100 );


