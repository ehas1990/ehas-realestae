<?php
/**
 * Register Frontend Shortcodes
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Shortcodes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'property_search_filters', array( $this, 'render_filters' ) );
		add_shortcode( 'property_listing', array( $this, 'render_listing' ) );
		add_shortcode( 'rem_advanced_search', array( $this, 'render_advanced_search' ) );
	}

	/**
	 * Render the Property Filters Form.
	 */
	public function render_filters( $atts ) {
		ob_start();
		
		// Load the filters template partial.
		if ( file_exists( CREM_PLUGIN_DIR . 'templates/partials/property-filters.php' ) ) {
			include CREM_PLUGIN_DIR . 'templates/partials/property-filters.php';
		}

		return ob_get_clean();
	}

	/**
	 * Render the Property Listing Grid & Map.
	 */
	public function render_listing( $atts ) {
		// Parse shortcode attributes
		$atts = shortcode_atts( array(
			'posts_per_page' => 6,
			'show_map'       => 'yes',
		), $atts, 'property_listing' );

		// Initial query of properties (SEO friendly initial render)
		$args = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $atts['posts_per_page'] ),
			'paged'          => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'property_availability_status',
					'value'   => 'hidden',
					'compare' => '!=',
				),
				array(
					'key'     => 'approval_status',
					'value'   => 'approved',
					'compare' => '=',
				),
			),
		);

		$query = new WP_Query( $args );

		ob_start();
		?>
		<div class="rem-listing-wrapper" data-posts-per-page="<?php echo esc_attr( $atts['posts_per_page'] ); ?>">
			
			<?php if ( 'yes' === $atts['show_map'] ) : ?>
				<!-- Map Container -->
				<div class="rem-map-container-wrapper">
					<div id="rem-properties-map" style="height: 400px; width: 100%; border-radius: 12px; margin-bottom: 30px; border: 1px solid rgba(0,0,0,0.08);"></div>
				</div>
			<?php endif; ?>

			<!-- Grid and Search Meta Info -->
			<div class="rem-results-header">
				<div class="rem-results-count">
					<span class="rem-count-number"><?php echo esc_html( $query->found_posts ); ?></span> <?php esc_html_e( 'properties found', 'custom-real-estate-manager' ); ?>
				</div>
			</div>

			<!-- Property Cards Grid -->
			<div class="rem-property-grid-container">
				<div class="rem-loader-overlay">
					<div class="rem-spinner"></div>
				</div>
				<div class="rem-property-grid">
					<?php
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							// Render property card template.
							include CREM_PLUGIN_DIR . 'templates/partials/property-card.php';
						}
						wp_reset_postdata();
					} else {
						echo '<div class="rem-no-results">';
						echo '<span class="dashicons dashicons-search"></span>';
						echo '<p>' . esc_html__( 'No properties found.', 'custom-real-estate-manager' ) . '</p>';
						echo '</div>';
					}
					?>
				</div>
			</div>

			<!-- Pagination Links -->
			<div class="rem-property-pagination">
				<?php
				echo paginate_links( array(
					'total'     => $query->max_num_pages,
					'current'   => 1,
					'type'      => 'plain',
					'prev_next' => true,
					'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
					'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
				) );
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the Advanced Real Estate Search Widget.
	 */
	public function render_advanced_search( $atts ) {
		ob_start();
		if ( file_exists( CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php' ) ) {
			include CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php';
		}
		return ob_get_clean();
	}
}
