<?php
/**
 * Custom template tags for this theme
 * 
 * @subpackage Eco Solar Power
 * @since 1.0
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */

if ( ! function_exists( 'luzuk_eco_solar_power_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function luzuk_eco_solar_power_entry_footer() {

	$separate_meta = __( ', ', 'eco-solar-power' );
	$categories_list = get_the_category_list( $separate_meta );
	$tags_list = get_the_tag_list( '', $separate_meta );
	if ( ( ( luzuk_eco_solar_power_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

		echo '<footer class="entry-footer">';			

			luzuk_eco_solar_power_edit_link();

		echo '</footer> <!-- .entry-footer -->';
	}
}
endif;

if ( ! function_exists( 'luzuk_eco_solar_power_edit_link' ) ) :

function luzuk_eco_solar_power_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'eco-solar-power' ),
			esc_html( get_the_title() )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

function luzuk_eco_solar_power_categorized_blog() {
	$category_count = get_transient( 'luzuk_eco_solar_power_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'luzuk_eco_solar_power_categories', $category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}

if ( ! function_exists( 'luzuk_eco_solar_power_the_custom_logo' ) ) :

function luzuk_eco_solar_power_the_custom_logo() {
	the_custom_logo();
}
endif;

function luzuk_eco_solar_power_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'luzuk_eco_solar_power_categories' );
}
add_action( 'edit_category', 'luzuk_eco_solar_power_category_transient_flusher' );
add_action( 'save_post',     'luzuk_eco_solar_power_category_transient_flusher' );


/**
 * Menus
 */

/**
 * Filters classes of wp_list_pages items to match menu items.
 *
 * Filter the class applied to wp_list_pages() items with children to match the menu class, to simplify.
 * styling of sub levels in the fallback. Only applied if the match_menu_classes argument is set.
 *
 * @since Eco Solar Power 1.0
 *
 * @param string[] $css_class    An array of CSS classes to be applied to each list item.
 * @param WP_Post  $page         Page data object.
 * @param int      $depth        Depth of page, used for padding.
 * @param array    $args         An array of arguments.
 * @return array CSS class names.
 */
function eco_solar_power_filter_wp_list_pages_item_classes( $css_class, $page, $depth, $args ) {

	// Only apply to wp_list_pages() calls with match_menu_classes set to true.
	$match_menu_classes = isset( $args['match_menu_classes'] );

	if ( ! $match_menu_classes ) {
		return $css_class;
	}

	// Add current menu item class.
	if ( in_array( 'current_page_item', $css_class, true ) ) {
		$css_class[] = 'current-menu-item';
	}

	// Add menu item has children class.
	if ( in_array( 'page_item_has_children', $css_class, true ) ) {
		$css_class[] = 'menu-item-has-children';
	}

	return $css_class;
}

add_filter( 'page_css_class', 'eco_solar_power_filter_wp_list_pages_item_classes', 10, 4 );

/**
 * Adds a Sub Nav Toggle to the Expanded Menu and Mobile Menu.
 *
 * @since Eco Solar Power 1.0
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @return stdClass An object of wp_nav_menu() arguments.
 */
function eco_solar_power_add_sub_toggles_to_main_menu( $args, $item ) {

	// Add sub menu toggles to the Expanded Menu with toggles.
	if ( isset( $args->show_toggles ) && $args->show_toggles ) {

		// Wrap the menu item link contents in a div, used for positioning.
		$args->before = '<div class="ancestor-wrapper">';
		$args->after  = '';

		// Add a toggle to items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

			$toggle_target_string = '.menu-modal .menu-item-' . $item->ID . ' > .sub-menu';
			$toggle_duration      = eco_solar_power_toggle_duration();

			// Add the sub menu toggle.
			$args->after .= '<button class="toggle sub-menu-toggle fill-children-current-color" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="' . absint( $toggle_duration ) . '" aria-expanded="false"><span class="screen-reader-text">' .
				/* translators: Hidden accessibility text. */
				__( 'Show sub menu', 'eco-solar-power' ) .
			'</span><i class="fa fa-chevron-down" aria-hidden="true"></i></button>';

		}

		// Close the wrapper.
		$args->after .= '</div><!-- .ancestor-wrapper -->';

		// Add sub menu icons to the primary menu without toggles.
	} elseif ( 'primary' === $args->theme_location ) {
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$args->after = '<span class="icon"></span>';
		} else {
			$args->after = '';
		}
	}

	return $args;
}

add_filter( 'nav_menu_item_args', 'eco_solar_power_add_sub_toggles_to_main_menu', 10, 2 );


/**
 * Miscellaneous
 */

/**
 * Toggles animation duration in milliseconds.
 *
 * @since Eco Solar Power 1.0
 *
 * @return int Duration in milliseconds
 */
function eco_solar_power_toggle_duration() {
	/**
	 * Filters the animation duration/speed used usually for submenu toggles.
	 *
	 * @since Eco Solar Power 1.0
	 *
	 * @param int $duration Duration in milliseconds.
	 */
	$duration = apply_filters( 'eco_solar_power_toggle_duration', 250 );

	return $duration;
}
