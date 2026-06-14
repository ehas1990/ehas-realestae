<?php
/**
 * Custom header implementation
 */

function organic_farm_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'organic_farm_custom_header_args', array(
		'default-image'          => get_parent_theme_file_uri( '/assets/images/header-img.png' ),
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1200,
		'height'                 => 150,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'organic_farm_header_style',
	) ) );
	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-img.png',
			'thumbnail_url' => '%s/assets/images/header-img.png',
			'description'   => __( 'Default Header Image', 'organic-farm' ),
		),
		'default-image-2' => array(
	        'url'           => '%s/assets/images/header-img-2.png',
	        'thumbnail_url' => '%s/assets/images/header-img-2.png',
	        'description'   => __( 'Default Header Image 2', 'organic-farm' ),
    	),
    	'default-image-3' => array(
	        'url'           => '%s/assets/images/header-img-3.png',
	        'thumbnail_url' => '%s/assets/images/header-img-3.png',
	        'description'   => __( 'Default Header Image 3', 'organic-farm' ),
    	),
	) );
}

add_action( 'after_setup_theme', 'organic_farm_custom_header_setup' );

if ( ! function_exists( 'organic_farm_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see organic_farm_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'organic_farm_header_style' );
function organic_farm_header_style() {
	if ( get_header_image() ) :
	$organic_farm_custom_css = "
		.header-image, .woocommerce-page .single-post-image  {
			background-image:url('".esc_url(get_header_image())."');
			background-position: top;
			background-size:cover!important;
			background-repeat:no-repeat!important;
		}
		";

	   	wp_add_inline_style( 'organic-farm-style', $organic_farm_custom_css );
	endif;
}
endif;


