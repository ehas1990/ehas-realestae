<?php
/**
 * Template Name: Search Results Page
 *
 * Displays properties filtered by state, district, keyword, and property type.
 */

get_header();

// Parse query variables
$get_state    = isset( $_GET['state'] ) ? sanitize_text_field( $_GET['state'] ) : '';
$get_district = isset( $_GET['district'] ) ? sanitize_text_field( $_GET['district'] ) : '';
$get_keyword  = isset( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
$get_type     = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

// Build WP_Query args
$meta_query = array();

if ( ! empty( $get_state ) ) {
	$meta_query[] = array(
		'key'     => 'state',
		'value'   => $get_state,
		'compare' => '=',
	);
}

if ( ! empty( $get_district ) ) {
	$meta_query[] = array(
		'key'     => 'district',
		'value'   => $get_district,
		'compare' => '=',
	);
}

$tax_query = array();

if ( ! empty( $get_type ) ) {
	$tax_query[] = array(
		'taxonomy' => 'property_type',
		'field'    => 'slug',
		'terms'    => $get_type,
	);
}

$args = array(
	'post_type'      => 'property',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $paged,
);

if ( ! empty( $get_keyword ) ) {
	$args['s'] = $get_keyword;
}

if ( ! empty( $meta_query ) ) {
	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'AND';
	}
	$args['meta_query'] = $meta_query;
}

if ( ! empty( $tax_query ) ) {
	$args['tax_query'] = $tax_query;
}

$query = new WP_Query( $args );

// Determine location descriptive title
$location_parts = array();
if ( ! empty( $get_district ) ) {
	$location_parts[] = $get_district;
}
if ( ! empty( $get_state ) ) {
	$location_parts[] = $get_state;
}
$location_desc = ! empty( $location_parts ) ? implode( ', ', $location_parts ) : 'India';

// Dynamic Hero Title and Background Image
$hero_title = 'Search Results';
if ( ! empty( $get_district ) ) {
	$hero_title = $get_district;
} elseif ( ! empty( $get_state ) ) {
	$hero_title = $get_state;
}

$hero_bg = '';
if ( ! empty( $get_district ) ) {
	$term = get_term_by( 'name', $get_district, 'property_location' );
	if ( $term ) {
		$term_image = get_field( 'district_featured_image', 'property_location_' . $term->term_id );
		if ( ! $term_image ) {
			$term_image = get_field( 'district_featured_image', 'term_' . $term->term_id );
		}
		if ( $term_image ) {
			$hero_bg = $term_image;
		}
	}
}
if ( empty( $hero_bg ) ) {
	$hero_bg = get_template_directory_uri() . '/assets/images/hero-default.jpg';
}
?>

<style>
	.archive-properties-wrapper {
		padding-top: 0;
		padding-bottom: 80px;
		background-color: var(--bg-primary, #0b0c10);
		color: var(--text-white, #ffffff);
		font-family: var(--font-en, 'Manrope', sans-serif);
		min-height: 100vh;
	}

	/* Properties Hero Banner Section */
	.properties-hero {
		position: relative;
		height: 380px;
		background: url('<?php echo esc_url($hero_bg); ?>') no-repeat center center;
		background-size: cover;
		display: flex;
		align-items: center;
		padding-top: 130px; /* Offset for absolute header and top bar */
		padding-left: 60px;
		padding-right: 60px;
		box-sizing: border-box;
		margin-bottom: 50px;
	}
	.properties-hero::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(90deg, rgba(11, 12, 16, 0.85) 0%, rgba(11, 12, 16, 0.4) 100%);
		z-index: 1;
	}
	.properties-hero-container {
		position: relative;
		z-index: 2;
		width: 100%;
		max-width: 100% !important;
		margin: 0 auto;
	}
	.page-id-145 .container {
		max-width: 95% !important;
		padding: 0 60px !important;
	}
	@media (max-width: 768px) {
		.page-id-145 .container {
			padding: 0 20px !important;
		}
	}
	.properties-hero-content {
		max-width: 800px;
		text-align: left;
	}
	.properties-hero-title {
		font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
		font-size: clamp(38px, 4.5vw, 56px) !important;
		font-weight: 400 !important;
		color: #ffffff !important;
		margin-bottom: 12px !important;
		line-height: 1.2 !important;
		letter-spacing: -0.5px !important;
		text-transform: none !important;
	}
	.properties-hero-breadcrumbs {
		font-family: var(--font-en), sans-serif !important;
		font-size: clamp(13px, 1.5vw, 14px) !important;
		font-weight: 600 !important;
		color: #ffffff !important;
		letter-spacing: 0.5px !important;
	}
	.properties-hero-breadcrumbs a {
		color: #ffffff !important;
		text-decoration: none !important;
		transition: color 0.3s ease !important;
	}
	.properties-hero-breadcrumbs a:hover {
		color: var(--accent-gold, #c5a880) !important;
	}
	.properties-hero-breadcrumbs span.sep {
		margin: 0 8px !important;
		color: rgba(255, 255, 255, 0.6) !important;
	}
	.properties-hero-breadcrumbs span.active {
		color: #f0643c !important; /* Accent color active indicator */
	}
	
	.archive-title-section {
		margin-bottom: 40px;
	}
	
	.archive-title-section h1 {
		font-size: 38px;
		font-weight: 800;
		color: var(--text-white, #ffffff);
		margin-bottom: 10px;
	}
	
	.archive-title-section p {
		color: var(--text-muted, #888888);
		font-size: 15px;
	}

	.archive-properties-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 30px;
		margin-top: 30px;
	}

	/* Redesigned Property Cards */
	.archive-properties-wrapper .property-card {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.08);
		border-radius: 12px;
		overflow: hidden;
		position: relative;
		display: flex;
		flex-direction: column;
		height: 100%;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
	}
	
	.archive-properties-wrapper .property-card:hover {
		transform: translateY(-6px);
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
		border-color: var(--accent-gold, #c5a880);
	}
	
	.archive-properties-wrapper .property-image-wrapper {
		position: relative;
		height: 230px;
		overflow: hidden;
		border-radius: 12px 12px 0 0;
	}
	
	.archive-properties-wrapper .property-image {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}
	
	.archive-properties-wrapper .property-card:hover .property-image {
		transform: scale(1.06);
	}
	
	.archive-properties-wrapper .property-badge-wrapper {
		position: absolute;
		top: 15px;
		left: 15px;
		display: flex;
		flex-direction: column;
		gap: 6px;
		z-index: 4;
	}
	
	.archive-properties-wrapper .property-badge-exclusive {
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 10px;
		font-weight: 800;
		text-transform: uppercase;
		padding: 4px 10px;
		border-radius: 4px;
		letter-spacing: 1px;
	}
	
	.archive-properties-wrapper .property-badge-featured {
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 10px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 4px;
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}
	
	.archive-properties-wrapper .property-badge-photos {
		position: absolute;
		top: 15px;
		right: 15px;
		background: rgba(28, 29, 33, 0.65);
		backdrop-filter: blur(6px);
		-webkit-backdrop-filter: blur(6px);
		color: #ffffff;
		font-size: 11px;
		font-weight: 600;
		padding: 4px 10px;
		border-radius: 6px;
		z-index: 4;
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}
	
	.archive-properties-wrapper .property-price {
		position: absolute;
		bottom: 15px;
		left: 15px;
		background: #ffffff;
		color: #181a20;
		font-size: 15px;
		font-weight: 700;
		padding: 6px 14px;
		border-radius: 6px;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		z-index: 3;
	}
	
	.archive-properties-wrapper .property-details {
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		background: #ffffff;
	}
	
	.archive-properties-wrapper .property-type-tag {
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		color: var(--accent-gold, #c5a880);
		margin-bottom: 6px;
		letter-spacing: 0.5px;
		display: block;
	}
	
	.archive-properties-wrapper .property-title {
		font-size: 16px;
		font-weight: 600;
		line-height: 1.4;
		margin-bottom: 8px;
		height: 44px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
	}
	
	.archive-properties-wrapper .property-title a {
		color: #181a20;
		text-decoration: none;
		transition: color 0.3s ease;
	}
	
	.archive-properties-wrapper .property-title a:hover {
		color: var(--accent-gold, #c5a880);
	}
	
	.archive-properties-wrapper .property-location {
		display: flex;
		align-items: center;
		font-size: 13px;
		color: #717171;
		gap: 6px;
		margin-bottom: 15px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	
	.archive-properties-wrapper .property-location i {
		color: var(--accent-gold, #c5a880);
	}
	
	.archive-properties-wrapper .property-amenities {
		display: grid !important;
		grid-template-columns: repeat(3, 1fr) !important;
		gap: 8px !important;
		border-top: 1px solid #e9e9e9 !important;
		padding-top: 14px !important;
		margin-top: auto !important;
		margin-bottom: 16px !important;
	}
	
	.archive-properties-wrapper .property-amenity {
		display: flex !important;
		flex-direction: column !important;
		align-items: center !important;
		justify-content: center !important;
		background: #f8f6f2 !important;
		border-radius: 8px !important;
		padding: 9px 6px !important;
		gap: 4px !important;
		font-size: 11px !important;
		color: #62697a !important;
	}
	
	.archive-properties-wrapper .property-amenity i {
		color: var(--accent-gold, #c5a880) !important;
		font-size: 13px !important;
	}
	
	.archive-properties-wrapper .property-amenity strong {
		color: #1c1d21 !important;
		font-size: 12px !important;
	}
	
	.archive-properties-wrapper .property-metas-bottom {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-top: 1px solid #e9e9e9;
		padding-top: 12px;
		margin-top: 12px;
	}
	
	.archive-properties-wrapper .status-property-label {
		font-size: 11px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.archive-properties-wrapper .status-property-label.badge-rent {
		background: #1C1D21 !important;
		color: #ffffff !important;
	}
	
	.archive-properties-wrapper .status-property-label.badge-sale {
		background: var(--primary-color) !important;
		color: #1C1D21 !important;
	}
	
	.archive-properties-wrapper .btn-action-circle {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		background: #f7f7f7;
		color: #1c1d21;
		border: 1px solid rgba(0, 0, 0, 0.05);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		padding: 0;
	}
	
	.archive-properties-wrapper .btn-action-circle:hover {
		background: var(--accent-gold, #c5a880);
		color: #ffffff;
		border-color: var(--accent-gold, #c5a880);
	}
	
	.archive-properties-wrapper .property-card-overlay-link {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 2;
	}

	/* Pagination styles */
	.pagination-wrapper {
		display: flex;
		justify-content: center;
		gap: 8px;
		margin-top: 50px;
	}
	.pagination-wrapper .page-numbers {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 40px;
		height: 40px;
		padding: 0 6px;
		border-radius: 8px;
		border: 1px solid rgba(255, 255, 255, 0.15);
		color: #fff;
		text-decoration: none;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s;
		background: rgba(255, 255, 255, 0.05);
	}
	.pagination-wrapper .page-numbers:hover {
		border-color: var(--accent-gold, #c5a880);
		color: var(--accent-gold, #c5a880);
		background: rgba(255, 255, 255, 0.08);
	}
	.pagination-wrapper .page-numbers.current {
		background: var(--accent-gold, #c5a880);
		border-color: var(--accent-gold, #c5a880);
		color: #fff;
	}
	.pagination-wrapper .page-numbers.dots {
		background: transparent;
		border: none;
		color: #888;
	}
</style>

<main class="archive-properties-wrapper">
	<!-- Hero Banner Section showing District/Location Name -->
	<div class="properties-hero">
		<div class="properties-hero-container">
			<div class="properties-hero-content">
				<h1 class="properties-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
				<div class="properties-hero-breadcrumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
					<span class="sep">/</span>
					<a href="<?php echo esc_url( home_url( '/properties/' ) ); ?>">Properties</a>
					<?php if ( ! empty( $get_district ) ) : ?>
						<span class="sep">/</span>
						<span class="active"><?php echo esc_html( $get_district ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
		
		<div class="archive-title-section">
			<p style="font-size: 16px; color: var(--text-muted, #888888); margin-bottom: 30px;">
				Showing <strong><?php echo esc_html( $query->found_posts ); ?></strong> <?php echo esc_html( _n( 'Property', 'Properties', $query->found_posts, 'casaview' ) ); ?> in <?php echo esc_html( $location_desc ); ?>
			</p>
		</div>

		<?php if ( $query->have_posts() ) : ?>
			<div class="archive-properties-grid">
				<?php
				while ( $query->have_posts() ) : $query->the_post();
					$price = get_field('price') ?: 0;
					$beds = get_field('bedrooms') ?: 0;
					$baths = get_field('bathrooms') ?: 0;
					$area = get_field('area_sqft') ?: 0;
					
					$display_district = get_post_meta(get_the_ID(), 'district', true);
					$display_place = get_post_meta(get_the_ID(), 'place', true);
					$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
					if ( empty($display_location) ) {
						$display_location = 'India';
					}
					
					$listing_type = get_field('listing_type') ?: 'buy';
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
									<span class="property-badge-featured">⭐ Featured</span>
								<?php endif; ?>
							</div>
							<span class="property-badge-photos">
								📷 <?php echo esc_html($photo_count); ?> Photos
							</span>
							<div class="property-price">
								<?php 
								$price_val = casaview_format_price($price);
								if ( $listing_type === 'rent' ) {
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
									<strong><?php echo esc_html($beds); ?></strong> Beds
								</div>
								<div class="property-amenity">
									<i class="fa-solid fa-bath"></i>
									<strong><?php echo esc_html($baths); ?></strong> Baths
								</div>
								<div class="property-amenity">
									<i class="fa-solid fa-ruler-combined"></i>
									<strong><?php echo esc_html(casaview_format_area($area)); ?></strong> Sq.Ft.
								</div>
							</div>
							<div class="property-metas-bottom">
								<div class="ali-left">
									<?php if ( $listing_type === 'rent' ) : ?>
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
				<?php endwhile; ?>
			</div>

			<!-- Pagination -->
			<?php if ( $query->max_num_pages > 1 ) : ?>
				<div class="pagination-wrapper">
					<?php
					echo paginate_links( array(
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'total'     => $query->max_num_pages,
						'current'   => $paged,
						'format'    => '?paged=%#%',
						'show_all'  => false,
						'type'      => 'plain',
						'prev_next' => true,
						'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
						'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
					) );
					?>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<div class="no-properties-found" style="text-align: center; padding: 80px 20px; background: rgba(255, 255, 255, 0.02); border-radius: 16px; border: 1px dashed rgba(255, 255, 255, 0.1);">
				<div class="empty-icon-wrapper" style="color: var(--accent-gold, #c5a880); margin-bottom: 20px; background: rgba(197, 168, 128, 0.1); padding: 20px; border-radius: 50%; display: inline-flex;">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><line x1="9" x2="15" y1="12" y2="12"/><line x1="9" x2="15" y1="16" y2="16"/></svg>
				</div>
				<h3 style="font-size: 22px; color: #ffffff; margin-bottom: 10px;">No Properties Found</h3>
				<p style="color: var(--text-muted, #888888); font-size: 14px; max-width: 400px; margin: 0 auto;">We couldn't find any active listings matching your search criteria. Please try adjusting your filters.</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();

