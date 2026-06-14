<?php
/**
 * Additional features to allow styling of the templates
 *
 * @subpackage Organic Farm
 * @since 1.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function organic_farm_body_classes( $classes ) {
	// Add class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add class if we're viewing the Customizer for easier styling of theme options.
	if ( is_customize_preview() ) {
		$classes[] = 'organic-farm-customizer';
	}

	// Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
		$classes[] = 'organic-farm-front-page';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	// Add class if sidebar is used.
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
		$classes[] = 'has-sidebar';
	}

	// Add class for one or two column page layouts.
	if ( is_page() || is_archive() ) {
		if ( 'one-column' === get_theme_mod( 'page_layout' ) ) {
			$classes[] = 'page-one-column';
		} else {
			$classes[] = 'page-two-column';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'organic_farm_body_classes' );

function organic_farm_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Pagination for blog post.
 */
function organic_farm_render_blog_pagination() {
	$organic_farm_pagination_type = get_theme_mod( 'organic_farm_pagination_type', 'numbered' );
	if ($organic_farm_pagination_type == 'default') {
		the_posts_navigation(array(
            'prev_text'          => __( 'Previous page', 'organic-farm' ),
            'next_text'          => __( 'Next page', 'organic-farm' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'organic-farm' ) . ' </span>',
        ) );
	}
	else if($organic_farm_pagination_type == 'numbered'){
		the_posts_pagination( array(
            'prev_text'          => __( 'Previous page', 'organic-farm' ),
            'next_text'          => __( 'Next page', 'organic-farm' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'organic-farm' ) . ' </span>',
        ) );
	}
}
add_action( 'organic_farm_blog_pagination', 'organic_farm_render_blog_pagination', 10 );

/**
 * Pagination for single post.
 */
function organic_farm_render_single_post_pagination() {
	$organic_farm_single_post_pagination_type = get_theme_mod( 'organic_farm_single_post_pagination_type', 'default' );
	if ($organic_farm_single_post_pagination_type == 'default') {
		the_post_navigation( array(
			'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'organic-farm' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'organic-farm' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'organic-farm' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'organic-farm' ) . '</span> ',
		) );
	}
	else if($organic_farm_single_post_pagination_type == 'post-name'){
		the_post_navigation( array(
			'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'organic-farm' ) . '</span><span class="nav-title">%title</span>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'organic-farm' ) . '</span><span class="nav-title">%title</span>',
		) );
	}
}
add_action( 'organic_farm_single_post_pagination', 'organic_farm_render_single_post_pagination', 10 );