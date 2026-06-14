<?php
/**
 * Archive Property Template
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="rem-archive-property-wrapper">
	<div class="rem-archive-banner">
		<div class="rem-container">
			<h1 class="rem-archive-title"><?php esc_html_e( 'Find Your Dream Home', 'custom-real-estate-manager' ); ?></h1>
			<p class="rem-archive-subtitle"><?php esc_html_e( 'Explore our curated list of properties for sale, off sale, and off plan.', 'custom-real-estate-manager' ); ?></p>
		</div>
	</div>

	<div class="rem-container">
		<div class="rem-archive-layout">
			
			<!-- Left Column: Filters Sidebar -->
			<aside class="rem-archive-sidebar">
				<?php 
				// Render the filters shortcode content.
				echo do_shortcode( '[property_search_filters]' ); 
				?>
			</aside>

			<!-- Right Column: Listings Grid & Interactive Map -->
			<main class="rem-archive-content">
				<?php 
				// Render the listings grid shortcode content.
				echo do_shortcode( '[property_listing posts_per_page="6" show_map="yes"]' ); 
				?>
			</main>

		</div>
	</div>
</div>

<?php
get_footer();
