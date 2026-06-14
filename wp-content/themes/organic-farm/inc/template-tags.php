<?php
/**
 * Custom template tags for this theme
 *
 * @subpackage Organic Farm
 * @since 1.0
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */

if ( ! function_exists( 'organic_farm_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function organic_farm_entry_footer() {
	$organic_farm_separate_meta = __( ', ', 'organic-farm' );
	$organic_farm_categories_list = get_the_category_list( $organic_farm_separate_meta );
	$organic_farm_tags_list = get_the_tag_list( '', $organic_farm_separate_meta );
	if ( ( ( organic_farm_categorized_blog() && $organic_farm_categories_list ) || $organic_farm_tags_list ) || get_edit_post_link() ) {

		echo '<footer class="entry-footer">';

			organic_farm_edit_link();

		echo '</footer> <!-- .entry-footer -->';
	}
}
endif;

if ( ! function_exists( 'organic_farm_edit_link' ) ) :
function organic_farm_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'organic-farm' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

function organic_farm_categorized_blog() {
	$organic_farm_category_count = get_transient( 'organic_farm_categories' );

	if ( false === $organic_farm_category_count ) {
		// Create an array of all the categories that are attached to posts.
		$organic_farm_categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$organic_farm_category_count = count( $organic_farm_categories );

		set_transient( 'organic_farm_categories', $organic_farm_category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $organic_farm_category_count > 1;
}

if ( ! function_exists( 'organic_farm_the_custom_logo' ) ) :

function organic_farm_the_custom_logo() {
	the_custom_logo();
}
endif;

function organic_farm_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'organic_farm_categories' );
}
add_action( 'edit_category', 'organic_farm_category_transient_flusher' );
add_action( 'save_post',     'organic_farm_category_transient_flusher' );
