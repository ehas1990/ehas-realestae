<?php
/**
 * Single Property Template Page - Fully Redesigned & Premium
 */

get_header();

// Fetch fields with robust postmeta lookup and ACF fallbacks
$post_id = get_the_ID();
$property_id = get_post_meta( $post_id, 'property_id', true ) ?: (get_field('property_id') ?: 'PR-' . $post_id);
$price = get_post_meta( $post_id, 'price', true );
$monthly_rent = get_post_meta( $post_id, 'monthly_rent', true );
$is_negotiable = get_post_meta( $post_id, 'is_negotiable', true );
$price_prefix = get_post_meta( $post_id, 'price_prefix', true ) ?: (get_field('price_prefix') ?: '');
$price_suffix = get_post_meta( $post_id, 'price_suffix', true ) ?: (get_field('price_suffix') ?: '');

$taluk = get_post_meta( $post_id, 'taluk', true );
$city = get_post_meta( $post_id, 'city', true );
$district = get_post_meta( $post_id, 'district', true );
$state = get_post_meta( $post_id, 'state', true );
$country = get_post_meta( $post_id, 'country', true );
$address = get_post_meta( $post_id, 'address', true ) ?: get_field('address');

// Format location string dynamically using hierarchy
$loc_parts = array_filter( array( $taluk, $city, $district, $state, $country ) );
$display_location = ! empty( $loc_parts ) ? implode( ', ', $loc_parts ) : 'India';

$latitude = get_post_meta( $post_id, 'latitude', true ) ?: '25.1124';
$longitude = get_post_meta( $post_id, 'longitude', true ) ?: '55.1390';
$google_maps_location_url = get_post_meta( $post_id, 'google_maps_location_url', true ) ?: '';

$area_sqft = get_post_meta( $post_id, 'area_sqft', true );
$land_area = get_post_meta( $post_id, 'land_area', true );
$unit = get_post_meta( $post_id, 'unit', true ) ?: 'Sq.Ft.';
$bedrooms = get_post_meta( $post_id, 'bedrooms', true ) ?: '0';
$bathrooms = get_post_meta( $post_id, 'bathrooms', true ) ?: '0';
$balcony = get_post_meta( $post_id, 'balcony', true ) ?: '0';
$parking = get_post_meta( $post_id, 'parking', true ) ?: '0';
$floors = get_post_meta( $post_id, 'floors', true ) ?: '1';
$year_built = get_post_meta( $post_id, 'year_built', true );
$furnishing_status = get_post_meta( $post_id, 'furnishing_status', true ) ?: 'Fully Furnished';

// Media & Files
$video_source = get_post_meta( $post_id, 'video_source', true ) ?: 'youtube';
$video_url = get_post_meta( $post_id, 'property_video', true ); // YouTube URL
$video_vimeo = get_post_meta( $post_id, 'video_vimeo', true ); // Vimeo URL
$video_file = get_post_meta( $post_id, 'video_file', true ); // MP4 Upload URL
$virtual_tour = get_post_meta( $post_id, 'virtual_tour_url', true );

$pdf_brochure = get_post_meta( $post_id, 'pdf_brochure', true );
$master_plan = get_post_meta( $post_id, 'master_plan', true );
$property_documents = get_post_meta( $post_id, 'property_documents', true );

// Repeaters
$gallery_images = casaview_get_repeater('gallery_images', $post_id);
$floor_plans = casaview_get_repeater('floor_plans', $post_id);
$nearby_places = casaview_get_repeater('nearby_places', $post_id);
$faqs = casaview_get_repeater('faqs', $post_id);

$pincode = get_post_meta( $post_id, 'pincode', true );

// Build actual slides array globally at the top to fix global scope bug
$featured_img = get_the_post_thumbnail_url($post_id, 'large') ?: (get_post_meta($post_id, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
$actual_slides = array( $featured_img );
if ( ! empty( $gallery_images ) ) {
	foreach ( $gallery_images as $gal ) {
		$actual_slides[] = isset($gal['image']) ? $gal['image'] : $gal;
	}
}
$total_actual_photos = count($actual_slides);

$assigned_agents = get_field('assigned_agents') ?: array();
$listing_type = get_field('listing_type') ?: 'buy';
$is_exclusive = get_field('is_exclusive');

$status_text = 'FOR SALE';
if ( $listing_type === 'rent' ) {
	$status_text = 'FOR RENT';
} elseif ( $listing_type === 'sale_rent' ) {
	$status_text = 'FOR SALE & RENT';
}

// Active theme color setup
$accent_color = '#c5a880';

// Video embed url parser
function casaview_get_video_embed_url($video_url, $source = 'youtube', $video_file = '') {
	if ($source === 'upload') {
		return esc_url($video_file);
	}
	if ($source === 'vimeo') {
		if (preg_match('/vimeo\.com\/([0-9]+)/', $video_url, $matches)) {
			return 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1';
		}
		return esc_url($video_url);
	}
	// Default YouTube
	if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches)) {
		return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1';
	}
	return esc_url($video_url);
}

$categories = get_the_terms($post_id, 'property_category');

// Gallery fallbacks setup
$default_fallbacks = array(
	'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80',
	'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
	'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80',
	'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
);
$display_slides = $actual_slides;
$fallback_count = 0;
while ( count($display_slides) < 5 ) {
	$display_slides[] = $default_fallbacks[$fallback_count % count($default_fallbacks)];
	$fallback_count++;
}
$display_slides = array_slice($display_slides, 0, 5);

if ( ! function_exists( 'casaview_render_district_property_card' ) ) {
	function casaview_render_district_property_card( $prop_id ) {
		$price = get_post_meta($prop_id, 'price', true) ?: 0;
		$beds = get_post_meta($prop_id, 'bedrooms', true) ?: 0;
		$baths = get_post_meta($prop_id, 'bathrooms', true) ?: 0;
		$area = get_post_meta($prop_id, 'area_sqft', true) ?: 0;
		
		$display_district = get_post_meta($prop_id, 'district', true);
		$display_place = get_post_meta($prop_id, 'place', true);
		$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
		if ( empty($display_location) ) {
			$display_location = 'India';
		}
		
		$listing_type = get_field('listing_type', $prop_id) ?: 'buy';
		$is_exclusive = get_field('is_exclusive', $prop_id);
		$thumbnail = get_the_post_thumbnail_url($prop_id, 'large') ?: (get_post_meta($prop_id, '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
		
		$gallery_images = casaview_get_repeater('gallery_images', $prop_id);
		$photo_count = 1;
		if ( ! empty( $gallery_images ) ) {
			$photo_count += count($gallery_images);
		}
		$is_featured = get_post_meta($prop_id, 'is_featured', true) === '1' || get_field('is_featured', $prop_id);
		$prop_title = get_the_title($prop_id);
		$prop_permalink = get_permalink($prop_id);
		?>
		<div class="property-card">
			<div class="property-image-wrapper">
				<a href="<?php echo esc_url($prop_permalink); ?>" class="property-image-link">
					<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($prop_title); ?>" class="property-image">
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
				$property_types = wp_get_post_terms( $prop_id, 'property_type' );
				$type_name = ! empty( $property_types ) ? $property_types[0]->name : 'Property';
				?>
				<span class="property-type-tag"><?php echo esc_html($type_name); ?></span>
				<h3 class="property-title"><a href="<?php echo esc_url($prop_permalink); ?>"><?php echo esc_html($prop_title); ?></a></h3>
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
						<button class="wishlist-btn-toggle btn-action-circle" data-id="<?php echo esc_attr($prop_id); ?>" aria-label="Add to Wishlist">
							<i class="fa-regular fa-heart"></i>
						</button>
					</div>
				</div>
			</div>
			<a href="<?php echo esc_url($prop_permalink); ?>" class="property-card-overlay-link" aria-label="<?php echo esc_attr($prop_title); ?>"></a>
		</div>
		<?php
	}
}

// Fetch district banner image dynamically
$district_banner_url = '';
$district_id = get_post_meta( $post_id, 'district_id', true );
if ( ! empty( $district_id ) ) {
	$district_banner_url = get_the_post_thumbnail_url( $district_id, 'full' );
}
if ( empty( $district_banner_url ) && ! empty( $district ) ) {
	$district_posts = get_posts( array(
		'post_type'      => 'district',
		'name'           => sanitize_title( $district ),
		'posts_per_page' => 1,
		'post_status'    => 'any',
	) );
	if ( ! empty( $district_posts ) ) {
		$district_banner_url = get_the_post_thumbnail_url( $district_posts[0]->ID, 'full' );
	} else {
		$district_query_by_title = new WP_Query( array(
			'post_type'      => 'district',
			'title'          => $district,
			'posts_per_page' => 1,
			'post_status'    => 'any',
		) );
		if ( $district_query_by_title->have_posts() ) {
			$district_banner_url = get_the_post_thumbnail_url( $district_query_by_title->posts[0]->ID, 'full' );
		}
	}
}
if ( empty( $district_banner_url ) ) {
	$district_banner_url = get_template_directory_uri() . '/assets/images/hero_waterfront_yacht.png';
}

// Query district properties excluding current property
$all_district_properties = array();
if ( ! empty( $district ) ) {
	$all_args = array(
		'post_type'      => 'property',
		'posts_per_page' => 6,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'meta_query'     => array(
			array(
				'key'     => 'district',
				'value'   => $district,
				'compare' => '=',
			)
		)
	);
	$all_query = new WP_Query( $all_args );
	if ( $all_query->have_posts() ) {
		$all_district_properties = $all_query->posts;
	}
	wp_reset_postdata();
}

$top_properties = array_slice( $all_district_properties, 0, 3 );
$bottom_properties = array_slice( $all_district_properties, 3, 3 );
if ( empty( $bottom_properties ) ) {
	$bottom_properties = $top_properties;
}
?>

<!-- Custom CSS for Premium Single Property Page -->
<style>
	.single-property .district-full-width-banner,
	.single-property section.single-property-search-section {
		display: none !important;
	}

	.single-property-wrapper {
		padding-top: 0px;
		padding-bottom: 80px;
		background-color: var(--bg-primary);
		color: var(--text-white);
		font-family: var(--font-en);
		--shadow-premium: 0 10px 30px rgba(0, 0, 0, 0.05);
	}

	/* Banner Header Background #EDEBE4 */
	.property-banner-header {
		background-color: #EDEBE4;
		padding-top: 120px;
		padding-bottom: 40px;
		border-bottom: 1px solid rgba(0, 0, 0, 0.06);
		margin-bottom: 40px;
	}

	/* Breadcrumbs */
	.breadcrumb-section {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 15px;
	}
	.breadcrumbs {
		font-size: 14px;
		color: #62697a;
	}
	.breadcrumbs a {
		color: #62697a;
		text-decoration: none;
		transition: color 0.3s;
	}
	.breadcrumbs a:hover {
		color: var(--accent-gold);
	}
	.breadcrumbs span {
		color: #1c1d21;
		font-weight: 500;
	}

	/* Property Header Section */
	.property-detail-header {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 30px;
	}
	.property-header-main h1 {
		font-size: 40px;
		font-weight: 700;
		line-height: 1.2;
		margin: 12px 0 8px 0;
		color: #1c1d21;
	}
	.property-type-status-badges {
		display: flex;
		gap: 10px;
	}
	.badge-type {
		background: rgba(197, 168, 128, 0.12);
		color: var(--accent-gold);
		border: 1px solid rgba(197, 168, 128, 0.3);
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		padding: 6px 14px;
		border-radius: 4px;
		letter-spacing: 0.5px;
	}
	.badge-status {
		background: #FCB71C;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		padding: 6px 14px;
		border-radius: 4px;
		letter-spacing: 0.5px;
	}
	.badge-status.rent {
		background: #ff5a3c;
	}
	.property-badge-exclusive {
		background: #000000 !important;
		color: #ffffff !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		padding: 6px 14px !important;
		border-radius: 4px !important;
		letter-spacing: 0.5px !important;
		display: inline-block !important;
	}
	.property-address-row {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #62697a;
		font-size: 16px;
	}
	.property-address-row i {
		color: var(--accent-gold);
	}

	.property-header-price-actions {
		text-align: right;
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		gap: 15px;
	}
	.property-price-container {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}
	.price-label {
		font-size: 12px;
		text-transform: uppercase;
		color: #62697a;
		letter-spacing: 1px;
	}
	.price-val {
		font-size: 36px;
		font-weight: 800;
		color: var(--accent-gold);
		line-height: 1.1;
	}
	.negotiable-badge {
		background: rgba(46, 204, 113, 0.15);
		color: #2ecc71;
		border: 1px solid rgba(46, 204, 113, 0.3);
		font-size: 11px;
		padding: 3px 8px;
		border-radius: 4px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		align-self: flex-end;
		width: max-content;
	}
	.header-action-buttons {
		display: flex;
		gap: 12px;
	}
	.btn-action {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.1);
		color: #1c1d21;
		padding: 10px 18px;
		font-size: 14px;
		font-weight: 600;
		border-radius: 6px;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s;
		text-decoration: none;
	}
	.btn-action:hover, .btn-action.active {
		border-color: var(--accent-gold);
		color: var(--accent-gold);
		background: rgba(197, 168, 128, 0.05);
	}

	/* Share Dropdown Menu */
	.share-dropdown-wrapper {
		position: relative;
	}
	.share-dropdown-menu {
		display: none;
		position: absolute;
		right: 0;
		top: 110%;
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.1);
		border-radius: 8px;
		padding: 8px;
		box-shadow: var(--shadow-premium);
		z-index: 999;
		width: 180px;
	}
	.share-dropdown-menu a, .share-dropdown-menu button {
		display: flex;
		align-items: center;
		gap: 10px;
		color: #1c1d21;
		padding: 10px 12px;
		text-decoration: none;
		font-size: 13px;
		transition: background 0.3s;
		border-radius: 6px;
		width: 100%;
		background: transparent;
		border: none;
		cursor: pointer;
		text-align: left;
		font-family: inherit;
	}
	.share-dropdown-menu a:hover, .share-dropdown-menu button:hover {
		background: rgba(0, 0, 0, 0.03);
		color: var(--accent-gold);
	}
	.dropdown-divider {
		height: 1px;
		background: rgba(0, 0, 0, 0.08);
		margin: 6px 0;
	}

	/* 5-Image Grid Gallery styles */
	.property-gallery-container {
		margin-bottom: 50px;
	}
	.casaview-gallery-grid-layout {
		display: grid;
		grid-template-columns: 2fr 1fr 1fr;
		grid-template-rows: 240px 240px;
		gap: 10px;
		border-radius: 12px;
		overflow: hidden;
		position: relative;
		box-shadow: var(--shadow-premium);
	}
	.gallery-grid-item {
		position: relative;
		overflow: hidden;
		cursor: pointer;
		background: #000;
	}
	.gallery-grid-item img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.4s ease, opacity 0.3s ease;
		display: block;
	}
	.gallery-grid-item:hover img {
		transform: scale(1.03);
		opacity: 0.9;
	}
	.casaview-gallery-grid-layout .gallery-grid-item:nth-child(1) {
		grid-column: 1 / 2;
		grid-row: 1 / 3;
		height: 490px;
	}
	.casaview-gallery-grid-layout .gallery-grid-item:nth-child(2) {
		grid-column: 2 / 3;
		grid-row: 1 / 2;
		height: 240px;
	}
	.casaview-gallery-grid-layout .gallery-grid-item:nth-child(3) {
		grid-column: 3 / 4;
		grid-row: 1 / 2;
		height: 240px;
	}
	.casaview-gallery-grid-layout .gallery-grid-item:nth-child(4) {
		grid-column: 2 / 3;
		grid-row: 2 / 3;
		height: 240px;
	}
	.casaview-gallery-grid-layout .gallery-grid-item:nth-child(5) {
		grid-column: 3 / 4;
		grid-row: 2 / 3;
		height: 240px;
	}

	/* Gallery Overlays */
	.see-all-photos-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.45);
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 5;
		transition: background 0.3s;
	}
	.gallery-grid-item:hover .see-all-photos-overlay {
		background: rgba(0, 0, 0, 0.55);
	}
	.gallery-video-trigger {
		position: absolute;
		bottom: 20px;
		left: 20px;
		background: #ff5a3c;
		color: #fff;
		border: none;
		padding: 10px 18px;
		border-radius: 30px;
		font-size: 13px;
		font-weight: 700;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
		box-shadow: 0 4px 15px rgba(255, 90, 60, 0.3);
		transition: all 0.3s;
		z-index: 10;
	}
	.gallery-video-trigger:hover {
		background: #e04428;
		transform: scale(1.04);
	}

	/* CSS Grid Layout Rules */
	.property-page-layout-grid {
		display: grid;
		grid-template-columns: 2fr 1fr;
		gap: 40px;
		margin-bottom: 50px;
	}
	.grid-left-col {
		grid-column: 1;
	}
	.grid-sidebar-col {
		grid-column: 2;
		grid-row: 1 / span 12;
		align-self: start;
		position: sticky;
		top: 100px;
	}

	/* Common Section Block styling */
	.section-block {
		background: var(--bg-tertiary);
		border: 1px solid var(--border-white);
		border-radius: 12px;
		padding: 32px;
		margin-bottom: 32px;
		box-shadow: var(--shadow-premium);
	}
	.section-title {
		font-size: 20px;
		font-weight: 700;
		margin-top: 0;
		margin-bottom: 22px;
		color: var(--text-white);
		display: flex;
		align-items: center;
		gap: 10px;
		border-bottom: 1px solid var(--border-white);
		padding-bottom: 10px;
	}
	.section-title i {
		color: var(--accent-gold);
	}

	/* 3. Quick Information Card Grid */
	.quick-info-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
		gap: 16px;
	}
	.quick-info-card {
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		padding: 16px;
		display: flex;
		align-items: center;
		gap: 12px;
		transition: all 0.3s;
	}
	.quick-info-card:hover {
		border-color: var(--accent-gold);
		transform: translateY(-2px);
	}
	.quick-info-card i {
		font-size: 20px;
		color: var(--accent-gold);
		width: 25px;
		text-align: center;
	}
	.card-text {
		display: flex;
		flex-direction: column;
	}
	.card-text span {
		font-size: 11px;
		color: var(--text-muted);
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.card-text strong {
		font-size: 15px;
		color: var(--text-white);
		font-weight: 700;
		margin-top: 2px;
	}

	/* 4. Collapsible Description */
	.description-wrapper {
		position: relative;
		max-height: 220px;
		overflow: hidden;
		transition: max-height 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
	}
	.description-content {
		font-size: 15px;
		line-height: 1.8;
		color: var(--text-muted);
	}
	.description-content p {
		margin-bottom: 15px;
	}
	.description-fade {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 80px;
		background: linear-gradient(to top, var(--bg-tertiary), transparent);
		pointer-events: none;
		transition: opacity 0.3s;
	}
	.btn-read-more {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		background: transparent;
		border: 1px solid var(--accent-gold);
		color: var(--accent-gold);
		padding: 8px 18px;
		font-weight: 700;
		font-size: 13px;
		border-radius: 6px;
		cursor: pointer;
		margin-top: 15px;
		transition: all 0.3s;
	}
	.btn-read-more:hover {
		background: var(--accent-gold);
		color: #fff;
	}

	/* 5. Features & Amenities */
	.amenities-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
		gap: 12px;
	}
	.amenity-card {
		display: flex;
		align-items: center;
		gap: 10px;
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		padding: 14px 18px;
		font-size: 14px;
		color: var(--text-muted);
		transition: all 0.3s;
	}
	.amenity-card:hover {
		border-color: var(--accent-gold);
		color: var(--text-white);
	}
	.amenity-card i {
		color: var(--accent-gold);
		font-size: 16px;
	}

	/* 6. Location Details */
	.location-details-table {
		display: flex;
		flex-direction: column;
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		overflow: hidden;
	}
	.loc-row {
		display: flex;
		justify-content: space-between;
		padding: 14px 20px;
		border-bottom: 1px solid var(--border-white);
		font-size: 14px;
	}
	.loc-row:last-child {
		border-bottom: none;
	}
	.loc-row strong {
		color: var(--text-muted);
		font-weight: 600;
	}
	.loc-row span {
		color: var(--text-white);
		font-weight: 500;
	}

	/* 7. Floor Plans Accordion */
	.accordion-item {
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		margin-bottom: 12px;
		overflow: hidden;
		transition: border-color 0.3s;
	}
	.accordion-item.active {
		border-color: var(--accent-gold);
	}
	.accordion-header {
		padding: 18px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		cursor: pointer;
		transition: background 0.3s;
	}
	.accordion-header:hover {
		background: rgba(0, 0, 0, 0.02);
	}
	.acc-header-left {
		display: flex;
		align-items: center;
		gap: 12px;
		font-weight: 700;
		color: var(--text-white);
		font-size: 15px;
	}
	.accordion-icon {
		color: var(--accent-gold);
		transition: transform 0.3s;
	}
	.accordion-item.active .accordion-icon {
		transform: rotate(180deg);
	}
	.floor-area {
		font-size: 12px;
		color: var(--accent-gold);
		font-weight: 700;
		background: rgba(197, 168, 128, 0.1);
		padding: 3px 10px;
		border-radius: 4px;
	}
	.accordion-content {
		display: none;
		padding: 20px;
		border-top: 1px solid var(--border-white);
		background: var(--bg-tertiary);
	}
	.floor-plan-meta {
		display: flex;
		gap: 16px;
		margin-bottom: 15px;
		border-bottom: 1px dashed var(--border-white);
		padding-bottom: 12px;
		flex-wrap: wrap;
		font-size: 13px;
		color: var(--text-muted);
	}
	.floor-plan-meta i {
		color: var(--accent-gold);
		margin-right: 5px;
	}
	.floor-price-badge {
		color: var(--accent-gold);
		font-weight: 700;
	}
	.floor-plan-img {
		max-width: 100%;
		height: auto;
		border-radius: 6px;
		border: 1px solid var(--border-white);
		margin-bottom: 12px;
		display: block;
	}
	.floor-desc {
		font-size: 14px;
		color: var(--text-muted);
		line-height: 1.6;
		margin: 0;
	}

	/* 8. Video Tour inline */
	.video-section-box {
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		padding: 12px;
	}
	.video-viewport {
		position: relative;
		padding-bottom: 56.25%;
		height: 0;
		overflow: hidden;
		border-radius: 6px;
		background: #000;
	}
	.video-viewport iframe, .video-viewport video {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border: none;
	}

	/* 9. Sticky Sidebar Agent Card */
	@keyframes agentCardFadeUp {
		from {
			opacity: 0;
			transform: translateY(20px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}
	.agent-card-wrapper {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.06);
		border-radius: 24px;
		padding: 32px;
		box-shadow: 0 15px 40px rgba(0, 0, 0, 0.04);
		transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
		animation: agentCardFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
	}
	.agent-card-wrapper:hover {
		box-shadow: 0 20px 45px rgba(0, 0, 0, 0.08);
	}
	.sidebar-card-title {
		font-size: 30px;
		font-weight: 700;
		margin-top: 0;
		margin-bottom: 28px;
		color: #1c1d21;
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.sidebar-card-title i {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 46px;
		height: 46px;
		border-radius: 50%;
		background: rgba(197, 168, 128, 0.1);
		color: var(--accent-gold);
		font-size: 18px;
	}
	.agent-profile-header {
		display: flex;
		align-items: center;
		gap: 20px;
		margin-bottom: 28px;
	}
	.agent-profile-header.divider-top {
		border-top: 1px solid rgba(0, 0, 0, 0.06);
		padding-top: 20px;
		margin-top: 20px;
	}
	.agent-avatar {
		width: 90px;
		height: 90px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid var(--accent-gold);
		box-shadow: 0 8px 20px rgba(197, 168, 128, 0.15);
	}
	.agent-meta h4 {
		font-size: 24px;
		font-weight: 700;
		margin: 0 0 4px 0;
		color: #1c1d21;
	}
	.agent-meta p {
		font-size: 16px;
		color: var(--accent-gold);
		font-weight: 500;
		margin: 0;
	}
	.agent-action-buttons {
		display: flex;
		flex-direction: column;
		gap: 16px;
		margin-bottom: 24px;
	}
	.agent-action-buttons a {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		height: 58px;
		border-radius: 14px;
		font-size: 15px;
		font-weight: 700;
		text-decoration: none;
		transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
		text-align: center;
		cursor: pointer;
		box-sizing: border-box;
	}
	.btn-agent-whatsapp {
		background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
		color: #ffffff;
		border: none;
	}
	.btn-agent-whatsapp:hover {
		transform: translateY(-3px);
		box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
	}
	.btn-agent-call {
		background: linear-gradient(135deg, #c5a880 0%, #a88a60 100%);
		color: #ffffff;
		border: none;
	}
	.btn-agent-call:hover {
		transform: translateY(-3px);
		box-shadow: 0 8px 24px rgba(197, 168, 128, 0.3);
	}
	.btn-agent-email {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.08);
		color: #1c1d21;
	}
	.btn-agent-email:hover {
		background: #f8f9fa;
		border-color: rgba(0, 0, 0, 0.12);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
	}
	.btn-agent-view-all {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		font-size: 15px;
		color: var(--accent-gold);
		text-decoration: none;
		font-weight: 600;
		transition: all 0.3s ease;
		margin-top: 8px;
		width: 100%;
	}
	.btn-agent-view-all i {
		transition: transform 0.3s ease;
	}
	.btn-agent-view-all:hover {
		color: var(--accent-gold-hover);
	}
	.btn-agent-view-all:hover i {
		transform: translateX(5px);
	}

	/* 11. Property Map */
	.map-viewport-wrapper {
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		padding: 12px;
	}
	#property-map {
		height: 380px;
		border-radius: 6px;
		border: 1px solid var(--border-white);
		margin-bottom: 12px;
		z-index: 1;
	}
	.map-actions-buttons {
		display: flex;
		gap: 12px;
	}
	.btn-map-action {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 11px 18px;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 700;
		text-decoration: none;
		transition: all 0.3s;
	}
	.btn-directions {
		background: rgba(197, 168, 128, 0.1);
		border: 1px solid var(--accent-gold);
		color: var(--accent-gold);
	}
	.btn-directions:hover {
		background: var(--accent-gold);
		color: #fff;
	}
	.btn-open-maps {
		background: transparent;
		border: 1px solid var(--border-white);
		color: var(--text-white);
	}
	.btn-open-maps:hover {
		border-color: var(--text-white);
	}

	/* 12. Related Properties Section */
	.related-properties-section {
		margin-top: 50px;
		border-top: 1px solid var(--border-white);
		padding-top: 40px;
	}
	.related-properties-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 24px;
		margin-top: 20px;
	}
	.related-properties-slider-wrapper {
		margin-top: 20px;
	}
	.property-card {
		background: var(--bg-tertiary);
		border: 1px solid var(--border-white);
		border-radius: 12px;
		overflow: hidden;
		box-shadow: var(--shadow-premium);
		transition: all 0.3s;
		display: flex;
		flex-direction: column;
		position: relative;
	}
	.property-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
		border-color: var(--accent-gold);
	}
	.property-image-wrapper {
		position: relative;
		height: 200px;
		overflow: hidden;
		background: #000;
	}
	.property-image-wrapper::after {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(to top, rgba(0,0,0,.55), rgba(0,0,0,.10));
		z-index: 3;
		pointer-events: none;
	}
	.property-image {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.3s ease;
	}
	.property-card:hover .property-image {
		transform: scale(1.05);
	}
	.property-badge-wrapper {
		position: absolute;
		top: 12px;
		left: 12px;
		display: flex;
		align-items: center;
		gap: 8px;
		z-index: 4;
	}
	.property-badge-type {
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 1.5px;
		padding: 6px 14px;
		border-radius: 50px;
		text-transform: uppercase;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		color: #ffffff !important;
		box-shadow: 0 4px 10px rgba(0,0,0,0.15);
		width: auto !important;
		max-width: max-content;
		white-space: nowrap;
		flex: none;
		transition: transform 0.3s ease;
	}
	.property-card:hover .property-badge-type {
		transform: translateY(-3px);
	}
	.property-badge-type.badge-sale {
		background: #FCB71C !important;
	}
	.property-badge-type.badge-rent {
		background: #1C1D21 !important;
	}
	.property-badge-photos {
		position: absolute;
		top: 12px;
		right: 12px;
		background: rgba(28, 29, 33, 0.65);
		backdrop-filter: blur(6px);
		-webkit-backdrop-filter: blur(6px);
		border: 1px solid rgba(255, 255, 255, 0.15);
		color: #ffffff;
		font-size: 11px;
		font-weight: 700;
		padding: 6px 12px;
		border-radius: 6px;
		z-index: 4;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		box-shadow: 0 4px 10px rgba(0,0,0,0.15);
	}
	.property-badge-featured {
		position: absolute;
		bottom: 12px;
		right: 12px;
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 11px;
		font-weight: 700;
		padding: 6px 12px;
		border-radius: 6px;
		z-index: 4;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		box-shadow: 0 4px 10px rgba(0,0,0,0.15);
	}
	.property-image-wrapper .property-price {
		position: absolute;
		bottom: 12px;
		left: 12px;
		background: rgba(15, 16, 21, 0.75) !important;
		backdrop-filter: blur(8px) !important;
		-webkit-backdrop-filter: blur(8px) !important;
		border: 1px solid rgba(255, 255, 255, 0.15) !important;
		padding: 8px 16px;
		border-radius: 12px;
		color: #ffffff !important;
		font-size: 18px;
		font-weight: 800;
		z-index: 4;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
		transition: all 0.3s ease;
		margin-bottom: 0;
	}
	.property-card:hover .property-image-wrapper .property-price {
		box-shadow: 0 0 20px rgba(197, 168, 128, 0.6);
		border-color: var(--accent-gold) !important;
	}
	.property-details {
		padding: 16px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}
	.property-title {
		font-size: 16px;
		font-weight: 700;
		margin: 0 0 8px 0;
		line-height: 1.3;
		height: 42px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
	}
	.property-title a {
		color: var(--text-white);
		text-decoration: none;
		transition: color 0.3s;
	}
	.property-title a:hover {
		color: var(--accent-gold);
	}
	.property-location {
		display: flex;
		align-items: center;
		gap: 5px;
		color: var(--text-muted);
		font-size: 13px;
		margin-bottom: 12px;
	}
	.property-location i {
		color: var(--accent-gold);
	}
	.property-amenities {
		display: flex;
		gap: 12px;
		border-top: 1px solid var(--border-white);
		padding-top: 10px;
		margin-top: auto;
	}
	.property-amenity {
		font-size: 11px;
		color: var(--text-muted);
		display: flex;
		align-items: center;
		gap: 4px;
	}
	.property-amenity i {
		color: var(--accent-gold);
	}

	/* Lightbox Popup Upgrade */
	.casaview-lightbox {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.96);
		z-index: 999999;
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}
	.casaview-lightbox-content {
		position: relative;
		width: 100%;
		max-width: 90%;
		max-height: 80%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.casaview-lightbox-content img {
		max-width: 100%;
		max-height: 80vh;
		object-fit: contain;
		display: block;
		margin: 0 auto;
		box-shadow: 0 10px 30px rgba(0,0,0,0.5);
		transition: opacity 0.18s ease-in-out;
		opacity: 1;
	}
	.casaview-lightbox-content img.fade-out {
		opacity: 0;
	}
	.casaview-lightbox-close {
		position: fixed;
		top: 20px;
		right: 20px;
		color: #fff;
		font-size: 30px;
		cursor: pointer;
		background: rgba(0,0,0,0.5);
		border: none;
		width: 46px;
		height: 46px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 1000000;
		transition: background 0.3s, color 0.3s;
	}
	.casaview-lightbox-close:hover {
		background: rgba(0,0,0,0.8);
		color: var(--accent-gold);
	}
	.casaview-lightbox-arrow {
		position: fixed;
		top: 50%;
		transform: translateY(-50%);
		color: #fff;
		font-size: 22px;
		background: rgba(0,0,0,0.5);
		border: none;
		cursor: pointer;
		width: 50px;
		height: 50px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: background 0.3s, color 0.3s;
		z-index: 1000000;
	}
	.casaview-lightbox-arrow:hover {
		background: rgba(0,0,0,0.8);
		color: var(--accent-gold);
	}
	.casaview-lightbox-prev {
		left: 20px;
	}
	.casaview-lightbox-next {
		right: 20px;
	}
	.casaview-lightbox-caption {
		color: #ccc;
		margin-top: 20px;
		font-size: 14px;
		position: fixed;
		bottom: 25px;
		z-index: 1000000;
		font-weight: 500;
		letter-spacing: 0.5px;
	}

	/* Video modal popup */
	.casaview-video-modal {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.92);
		z-index: 999999;
		align-items: center;
		justify-content: center;
	}
	.casaview-video-content {
		position: relative;
		width: 850px;
		max-width: 90%;
		aspect-ratio: 16/9;
		background: #000;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 15px 50px rgba(0,0,0,0.6);
		border: 1px solid rgba(255, 255, 255, 0.1);
	}
	.casaview-video-close {
		position: absolute;
		top: 15px;
		right: 15px;
		color: #fff;
		font-size: 24px;
		cursor: pointer;
		z-index: 1000;
		background: rgba(0,0,0,0.5);
		border: none;
		width: 38px;
		height: 38px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.3s;
	}
	.casaview-video-close:hover {
		background: rgba(0,0,0,0.8);
		color: var(--accent-gold);
	}

	/* Responsive viewport styling */
	@media (max-width: 991px) {
		.property-page-layout-grid {
			display: flex;
			flex-direction: column;
			gap: 30px;
		}
		.grid-left-col, .grid-sidebar-col {
			width: 100%;
		}
		#section-agent-sidebar {
			position: static !important;
			top: auto !important;
			bottom: auto !important;
			z-index: auto !important;
			transform: none !important;
		}
		
		/* Strict Mobile ordering logic */
		.property-page-layout-grid > #section-quick-info { order: 1; }
		.property-page-layout-grid > #section-description { order: 2; }
		.property-page-layout-grid > #section-amenities { order: 3; }
		.property-page-layout-grid > #section-location-details { order: 4; }
		.property-page-layout-grid > #section-floor-plans { order: 5; }
		.property-page-layout-grid > #section-video { order: 6; }
		.property-page-layout-grid > #section-agent-sidebar { order: 7; }
		.property-page-layout-grid > #section-faq { order: 8; }
		.property-page-layout-grid > #section-map { order: 9; }
		
		.related-properties-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}
		
		.map-actions-buttons {
			flex-direction: column;
		}
	}

	@media (max-width: 768px) {
		.casaview-gallery-grid-layout {
			grid-template-columns: 1fr !important;
			grid-template-rows: 300px !important;
		}
		.gallery-grid-item {
			display: none;
		}
		.gallery-grid-item:first-child {
			display: block;
			grid-column: 1 / -1 !important;
			grid-row: 1 / -1 !important;
			height: 300px !important;
		}
		.mobile-see-all-badge {
			display: inline-block !important;
		}
		.agent-meta h4 {
			font-size: 20px !important;
			font-weight: 700 !important;
			margin: 0 0 4px 0 !important;
			color: #1c1d21 !important;
		}
	}

	@media (max-width: 600px) {
		.property-banner-header {
			padding-top: 100px;
			padding-bottom: 25px;
		}
		.property-detail-header {
			flex-direction: column;
			gap: 20px;
		}
		.property-header-price-actions {
			align-items: flex-start;
			text-align: left;
			width: 100%;
		}
		.negotiable-badge {
			align-self: flex-start;
		}
		.section-block {
			padding: 20px;
		}
		.quick-info-grid {
			grid-template-columns: 1fr;
		}
		.amenities-grid {
			grid-template-columns: 1fr;
		}
		.loc-row {
			padding: 12px 15px;
			font-size: 13px;
		}
	}

	/* Custom District Banner & Listing Sections for Single Property Page */
	.district-full-width-banner {
		position: relative;
		width: 100%;
		height: 380px;
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-top: 0;
	}
	.district-banner-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(180deg, rgba(11, 12, 16, 0.4) 0%, rgba(11, 12, 16, 0.8) 100%);
	}
	.district-banner-content {
		position: relative;
		z-index: 2;
		text-align: center;
		padding: 0 20px;
	}
	.district-banner-title {
		font-size: 48px;
		font-weight: 800;
		color: #ffffff;
		text-transform: uppercase;
		letter-spacing: 1px;
		margin: 0;
		text-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
		font-family: var(--font-en, 'Manrope', sans-serif);
	}

	.district-section-wrapper {
		background-color: var(--bg-primary, #0b0c10);
		padding: 60px 0;
		width: 100%;
	}
	.district-section-title {
		font-size: 30px;
		font-weight: 700;
		color: #ffffff;
		margin-bottom: 30px;
		text-align: left;
		font-family: var(--font-en, 'Manrope', sans-serif);
	}
	.district-properties-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 30px;
	}

	/* Card layout specifically for single property page context */
	.district-section-wrapper .property-card {
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
	.district-section-wrapper .property-card:hover {
		transform: translateY(-6px);
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
		border-color: var(--accent-gold, #c5a880);
	}
	.district-section-wrapper .property-image-wrapper {
		position: relative;
		height: 230px;
		overflow: hidden;
		border-radius: 12px 12px 0 0;
	}
	.district-section-wrapper .property-image {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}
	.district-section-wrapper .property-card:hover .property-image {
		transform: scale(1.06);
	}
	.district-section-wrapper .property-badge-wrapper {
		position: absolute;
		top: 15px;
		left: 15px;
		display: flex;
		flex-direction: column;
		gap: 6px;
		z-index: 4;
	}
	.district-section-wrapper .property-badge-exclusive {
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 10px;
		font-weight: 800;
		text-transform: uppercase;
		padding: 4px 10px;
		border-radius: 4px;
		letter-spacing: 1px;
	}
	.district-section-wrapper .property-badge-featured {
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
	.district-section-wrapper .property-badge-photos {
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
	.district-section-wrapper .property-price {
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
	.district-section-wrapper .property-details {
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		background: #ffffff;
	}
	.district-section-wrapper .property-type-tag {
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		color: var(--accent-gold, #c5a880);
		margin-bottom: 6px;
		letter-spacing: 0.5px;
		display: block;
	}
	.district-section-wrapper .property-title {
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
	.district-section-wrapper .property-title a {
		color: #181a20;
		text-decoration: none;
		transition: color 0.3s ease;
	}
	.district-section-wrapper .property-title a:hover {
		color: var(--accent-gold, #c5a880);
	}
	.district-section-wrapper .property-location {
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
	.district-section-wrapper .property-location i {
		color: var(--accent-gold, #c5a880);
	}
	.district-section-wrapper .property-amenities {
		display: flex;
		gap: 15px;
		border-top: 1px solid #e9e9e9;
		padding-top: 12px;
		margin-top: auto;
		margin-bottom: 0;
	}
	.district-section-wrapper .property-amenity {
		display: flex;
		align-items: center;
		font-size: 13px;
		color: #717171;
		gap: 6px;
		background: transparent;
		padding: 0;
		border-radius: 0;
	}
	.district-section-wrapper .property-amenity i {
		color: var(--accent-gold, #c5a880);
		font-size: 14px;
	}
	.district-section-wrapper .property-amenity strong {
		color: #181a20;
	}
	.district-section-wrapper .property-metas-bottom {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-top: 1px solid #e9e9e9;
		padding-top: 12px;
		margin-top: 12px;
	}
	.district-section-wrapper .status-property-label {
		font-size: 11px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.district-section-wrapper .status-property-label.badge-rent {
		background: #1C1D21 !important;
		color: #ffffff !important;
	}
	.district-section-wrapper .status-property-label.badge-sale {
		background: #FCB71C !important;
		color: #1C1D21 !important;
	}
	.district-section-wrapper .btn-action-circle {
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
	.district-section-wrapper .btn-action-circle:hover {
		background: var(--accent-gold, #c5a880);
		color: #ffffff;
		border-color: var(--accent-gold, #c5a880);
	}
	.district-section-wrapper .property-card-overlay-link {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 2;
	}

	/* Custom styles for the search section */
	.single-property-search-section {
		background-color: var(--bg-secondary, #1a1b1f);
		padding: 40px 0;
		width: 100%;
		border-top: 1px solid rgba(255, 255, 255, 0.05);
		border-bottom: 1px solid rgba(255, 255, 255, 0.05);
	}
	.single-property-search-section .hero-filter-bar {
		background: #ffffff !important;
		border-radius: 18px !important;
		padding: 12px !important;
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
		width: 100% !important;
		box-sizing: border-box !important;
		max-width: 1200px;
		margin: 0 auto;
	}
</style>

<?php if ( false ) : ?>
<!-- Full-Width District Banner -->
<div class="district-full-width-banner" style="background-image: url('<?php echo esc_url($district_banner_url); ?>');">
	<div class="district-banner-overlay"></div>
	<div class="district-banner-content">
		<h1 class="district-banner-title">Properties in <?php echo esc_html( $district ? $district : 'Kerala' ); ?></h1>
	</div>
</div>

<!-- Properties Listing Section -->
<?php if ( ! empty( $top_properties ) ) : ?>
<section class="district-section-wrapper" id="section-top-district-properties">
	<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
		<h3 class="district-section-title">Properties in <?php echo esc_html( $district ? $district : 'Kerala' ); ?></h3>
		<div class="district-properties-grid">
			<?php 
			foreach ( $top_properties as $prop ) {
				casaview_render_district_property_card( $prop->ID );
			}
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Search Section below top listing -->
<section class="single-property-search-section">
	<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
		<form id="single-property-search-form" class="hero-filter-bar">
			<!-- Keyword search -->
			<input type="text" name="keyword" id="single-search-keyword" class="hero-filter-input" placeholder="Search properties..." style="color: #1c1d21; background: transparent; border: none; padding: 10px 14px; font-size: 15px; width: 100%; outline: none;" value="">

			<!-- State -->
			<select name="state" id="single-search-state" class="hero-filter-select" style="color: #1c1d21; background: transparent; border: none; padding: 10px 14px; font-size: 15px; outline: none; cursor: pointer;">
				<option value="">All State</option>
				<?php
				$indian_states = array(
					'Kerala', 'Tamil Nadu', 'Karnataka', 'Maharashtra', 'Delhi', 'Goa',
					'Andhra Pradesh', 'Telangana', 'Gujarat', 'Rajasthan', 'Uttar Pradesh', 'West Bengal'
				);
				foreach ( $indian_states as $s ) {
					echo '<option value="' . esc_attr($s) . '">' . esc_html($s) . '</option>';
				}
				?>
			</select>

			<!-- District -->
			<select name="district" id="single-search-district" class="hero-filter-select" disabled style="color: #1c1d21; background: transparent; border: none; padding: 10px 14px; font-size: 15px; outline: none; cursor: pointer;">
				<option value="">Select District...</option>
			</select>

			<!-- Property Type -->
			<select name="prop_type" id="single-search-prop-type" class="hero-filter-select" style="color: #1c1d21; background: transparent; border: none; padding: 10px 14px; font-size: 15px; outline: none; cursor: pointer;">
				<option value="">All Type</option>
				<?php
				$types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
				foreach ( $types as $t ) {
					echo '<option value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
				}
				?>
			</select>

			<div class="hero-filter-btn-group">
				<!-- Submit Button -->
				<button type="submit" class="hero-filter-search" style="background: var(--accent-gold, #c5a880); color: #fff; border: none; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; padding: 0;">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</div>
		</form>
	</div>
</section>
<?php endif; ?>

<div class="property-banner-header">
	<div class="container">
		<!-- Breadcrumbs -->
		<div class="breadcrumb-section">
			<div class="breadcrumbs">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> &nbsp;/&nbsp; 
				<a href="<?php echo esc_url( home_url( '/property/' ) ); ?>">Properties</a> &nbsp;/&nbsp; 
				<span><?php the_title(); ?></span>
			</div>
		</div>

		<!-- 1. Property Header -->
		<div class="property-detail-header">
			<div class="property-header-main">
				<div class="property-type-status-badges">
					<?php
					$category_name = $categories ? $categories[0]->name : '';
					$show_category_badge = true;
					if ( ! empty( $category_name ) ) {
						$cat_lower = strtolower($category_name);
						$status_lower = strtolower($status_text);
						if ( $cat_lower === 'for rent' || $cat_lower === 'for sale' || $cat_lower === 'rent' || $cat_lower === 'sale' || $cat_lower === $status_lower || strpos($status_lower, $cat_lower) !== false || strpos($cat_lower, $status_lower) !== false ) {
							$show_category_badge = false;
						}
					}
					if ( $show_category_badge && ! empty( $category_name ) ) : ?>
						<span class="badge-type"><?php echo esc_html(strtoupper($category_name)); ?></span>
					<?php endif; ?>
					<span class="badge-status <?php echo esc_attr($listing_type); ?>"><?php echo esc_html($status_text); ?></span>
					<?php if ( $is_exclusive ) : ?>
						<span class="property-badge-exclusive">Exclusive</span>
					<?php endif; ?>
				</div>
				<h1><?php the_title(); ?></h1>
				<div class="property-address-row">
					<i class="fa-solid fa-location-dot"></i>
					<span><?php echo esc_html($display_location); ?></span>
				</div>
			</div>
			<div class="property-header-price-actions">
				<div class="property-price-container">
					<span class="price-label">Price</span>
					<div class="price-val">
						<?php 
						if ( $listing_type === 'sale_rent' ) {
							echo casaview_format_price( $price ) . ' / ' . casaview_format_price( $monthly_rent ) . ' (Rent)';
						} else {
							if ( ! empty( $price_prefix ) ) echo esc_html( $price_prefix ) . ' ';
							echo casaview_format_price( $price );
							if ( $listing_type === 'rent' && ! empty( $monthly_rent ) ) {
								echo ' / Month';
							}
							if ( ! empty( $price_suffix ) ) echo ' ' . esc_html( $price_suffix );
						}
						?>
					</div>
					<?php if ( $is_negotiable ) : ?>
						<span class="negotiable-badge">Negotiable</span>
					<?php endif; ?>
				</div>
				<div class="header-action-buttons">
					<button class="btn-action wishlist-btn-toggle" data-id="<?php the_ID(); ?>">
						<i class="fa-regular fa-heart"></i> <span>Favorite</span>
					</button>
					
					<div class="share-dropdown-wrapper">
						<button class="btn-action share-btn-toggle" id="share-btn">
							<i class="fa-solid fa-share-nodes"></i> Share
						</button>
						<div class="share-dropdown-menu" id="share-menu">
							<a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank">
								<i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> WhatsApp
							</a>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank">
								<i class="fa-brands fa-facebook" style="color: #1877F2;"></i> Facebook
							</a>
							<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank">
								<i class="fa-brands fa-twitter" style="color: #1DA1F2;"></i> Twitter
							</a>
							<div class="dropdown-divider"></div>
							<button id="copy-link-btn">
								<i class="fa-solid fa-copy"></i> Copy Link
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<main class="single-property-wrapper">
	<div class="container">

		<!-- 2. Property Gallery 5-Image Grid -->
		<div class="property-gallery-container" id="gallery-section">
			<div class="casaview-gallery-grid-layout">
				<?php for ( $i = 0; $i < 5; $i++ ) : 
					$is_fallback = ($i >= $total_actual_photos);
					$is_last_item = ($i === 4);
					$img_url = $display_slides[$i];
					?>
					<div class="gallery-grid-item" data-index="<?php echo $i; ?>" <?php echo $is_fallback ? 'data-is-fallback="true"' : ''; ?>>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="Property Image <?php echo $i + 1; ?>" loading="lazy">
						
						<?php if ( $i === 0 ) : ?>

							
							<?php if ( ! empty( $video_url ) || ! empty( $video_vimeo ) || ! empty( $video_file ) ) : ?>
								<button class="gallery-video-trigger" id="gallery-play-btn">
									<i class="fa-solid fa-circle-play"></i> Watch Video
								</button>
							<?php endif; ?>

							<span class="mobile-see-all-badge">
								<i class="fa-regular fa-images"></i> 1/<?php echo $total_actual_photos; ?>
							</span>
						<?php endif; ?>

						<?php if ( $is_last_item ) : ?>
							<div class="see-all-photos-overlay">
								<span style="color: #fff; font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
									<i class="fa-regular fa-images"></i> See All (<?php echo $total_actual_photos; ?>)
								</span>
							</div>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>

		<!-- Grid Layout for Left Content & Right Sidebar -->
		<div class="property-page-layout-grid">
			
			<!-- 3. Property Quick Information -->
			<div class="grid-left-col section-block" id="section-quick-info">
				<h3 class="section-title">
					<i class="fa-solid fa-circle-info"></i> Quick Information
				</h3>
				<div class="quick-info-grid">
					<div class="quick-info-card">
						<i class="fa-solid fa-bed"></i>
						<div class="card-text">
							<span>Bedrooms</span>
							<strong><?php echo esc_html($bedrooms); ?> Beds</strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-solid fa-bath"></i>
						<div class="card-text">
							<span>Bathrooms</span>
							<strong><?php echo esc_html($bathrooms); ?> Baths</strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-solid fa-ruler-combined"></i>
						<div class="card-text">
							<span>Area</span>
							<strong><?php echo esc_html(casaview_format_area($area_sqft)) . ' ' . esc_html($unit); ?></strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-solid fa-building"></i>
						<div class="card-text">
							<span>Property Type</span>
							<strong><?php echo esc_html($categories ? $categories[0]->name : 'Property'); ?></strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-solid fa-hashtag"></i>
						<div class="card-text">
							<span>Property ID</span>
							<strong><?php echo esc_html($property_id); ?></strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-solid fa-tag"></i>
						<div class="card-text">
							<span>Status</span>
							<strong><?php echo esc_html(casaview_get_listing_type_label($listing_type)); ?></strong>
						</div>
					</div>
					<div class="quick-info-card">
						<i class="fa-regular fa-calendar-days"></i>
						<div class="card-text">
							<span>Posted Date</span>
							<strong><?php echo esc_html(get_the_date()); ?></strong>
						</div>
					</div>
				</div>
			</div>

			<!-- 4. Property Description / Overview -->
			<div class="grid-left-col section-block" id="section-description">
				<h3 class="section-title">
					<i class="fa-solid fa-align-left"></i> Description
				</h3>
				<div class="description-wrapper" id="desc-wrapper">
					<div class="description-content">
						<?php the_content(); ?>
						<?php if ( ! get_the_content() ) : ?>
							<p>This exclusive property offers signature boutique design, customized interior details, and premium high-end specifications situated in one of the most prestigious neighborhood sectors. Designed to combine contemporary modern layouts with elegant aesthetics, this represents a unique asset acquisition opportunity.</p>
						<?php endif; ?>
					</div>
					<div class="description-fade" id="desc-fade"></div>
				</div>
				<button class="btn-read-more" id="read-more-btn">
					<span>Read More</span> <i class="fa-solid fa-chevron-down"></i>
				</button>
			</div>

			<!-- 5. Property Features & Amenities -->
			<div class="grid-left-col section-block" id="section-amenities">
				<h3 class="section-title">
					<i class="fa-solid fa-list-check"></i> Features & Amenities
				</h3>
				<div class="amenities-grid">
					<?php 
					$terms = get_the_terms( get_the_ID(), 'amenity' );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
						foreach ( $terms as $term ) : ?>
							<div class="amenity-card">
								<?php echo casaview_render_amenity_icon($term->term_id); ?>
								<span><?php echo esc_html($term->name); ?></span>
							</div>
						<?php endforeach;
					else : 
						$mock_amens_icons = array(
							'Air Conditioning' => 'cctv',
							'Parking' => 'parking',
							'Swimming Pool' => 'pool',
							'Security' => 'security',
							'Gym' => 'gym',
							'Garden' => 'garden'
						);
						foreach ( $mock_amens_icons as $name => $key ) : ?>
							<div class="amenity-card">
								<?php echo casaview_render_property_icon($key); ?>
								<span><?php echo esc_html($name); ?></span>
							</div>
						<?php endforeach;
					endif; ?>
				</div>
			</div>

			<!-- 6. Property Location Details -->
			<div class="grid-left-col section-block" id="section-location-details">
				<h3 class="section-title">
					<i class="fa-solid fa-map-pin"></i> Location Details
				</h3>
				<div class="location-details-table">
					<div class="loc-row"><strong>Country</strong><span><?php echo esc_html( $country ?: 'India' ); ?></span></div>
					<div class="loc-row"><strong>State</strong><span><?php echo esc_html( $state ?: '-' ); ?></span></div>
					<div class="loc-row"><strong>District</strong><span><?php echo esc_html( $district ?: '-' ); ?></span></div>
					<div class="loc-row"><strong>Taluk</strong><span><?php echo esc_html( $taluk ?: '-' ); ?></span></div>
					<div class="loc-row"><strong>Village/City</strong><span><?php echo esc_html( $city ?: '-' ); ?></span></div>
					<div class="loc-row"><strong>Address</strong><span><?php echo esc_html( $address ?: '-' ); ?></span></div>
					<div class="loc-row"><strong>Pincode</strong><span><?php echo esc_html( $pincode ?: '-' ); ?></span></div>
				</div>
			</div>

			<!-- 7. Floor Plans accordion -->
			<?php if ( ! empty( $floor_plans ) ) : ?>
				<div class="grid-left-col section-block" id="section-floor-plans">
					<h3 class="section-title">
						<i class="fa-solid fa-layer-group"></i> Floor Plans
					</h3>
					<div class="accordion-container">
						<?php 
						usort( $floor_plans, function($a, $b) {
							$a_sort = isset($a['floor_sort_order']) ? intval($a['floor_sort_order']) : 0;
							$b_sort = isset($b['floor_sort_order']) ? intval($b['floor_sort_order']) : 0;
							return $a_sort <=> $b_sort;
						});
						
						$floor_count = 0;
						foreach ( $floor_plans as $plan ) : 
							$floor_count++;
							$floor_beds = isset($plan['floor_beds']) ? intval($plan['floor_beds']) : 0;
							$floor_baths = isset($plan['floor_baths']) ? intval($plan['floor_baths']) : 0;
							$floor_area = isset($plan['floor_area']) ? $plan['floor_area'] : '';
							$floor_price = isset($plan['floor_price']) ? $plan['floor_price'] : '';
							$floor_desc = isset($plan['floor_description']) ? $plan['floor_description'] : '';
							?>
							<div class="accordion-item <?php echo $floor_count == 1 ? 'active' : ''; ?>">
								<div class="accordion-header">
									<div class="acc-header-left">
										<i class="fa-solid fa-chevron-down accordion-icon"></i>
										<span><?php echo esc_html($plan['floor_name']); ?></span>
									</div>
									<div class="acc-header-right">
										<?php if ($floor_area) : ?>
											<span class="floor-area"><?php echo esc_html($floor_area); ?></span>
										<?php endif; ?>
									</div>
								</div>
								<div class="accordion-content" style="<?php echo $floor_count == 1 ? 'display:block;' : ''; ?>">
									<div class="floor-plan-meta">
										<?php if ($floor_beds > 0) : ?>
											<span><i class="fa-solid fa-bed"></i> <strong><?php echo $floor_beds; ?></strong> Beds</span>
										<?php endif; ?>
										<?php if ($floor_baths > 0) : ?>
											<span><i class="fa-solid fa-bath"></i> <strong><?php echo $floor_baths; ?></strong> Baths</span>
										<?php endif; ?>
										<?php if ($floor_area) : ?>
											<span><i class="fa-solid fa-ruler-combined"></i> <?php echo esc_html($floor_area); ?></span>
										<?php endif; ?>
										<?php if ($floor_price) : ?>
											<span class="floor-price-badge"><i class="fa-solid fa-tag"></i> <?php echo esc_html($floor_price); ?></span>
										<?php endif; ?>
									</div>
									<?php if ( ! empty( $plan['floor_plan_image'] ) ) : ?>
										<img src="<?php echo esc_url($plan['floor_plan_image']); ?>" alt="<?php echo esc_attr($plan['floor_name']); ?>" class="floor-plan-img">
									<?php endif; ?>
									<?php if ( ! empty($floor_desc) ) : ?>
										<p class="floor-desc"><?php echo esc_html($floor_desc); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- 8. Property Video Inline -->
			<?php if ( ! empty( $video_url ) || ! empty( $video_vimeo ) || ! empty( $video_file ) ) : 
				$embed_url = casaview_get_video_embed_url( $video_url ?: $video_vimeo, $video_source, $video_file );
				?>
				<div class="grid-left-col section-block" id="section-video">
					<h3 class="section-title">
						<i class="fa-solid fa-circle-play"></i> Property Video Tour
					</h3>
					<div class="video-section-box">
						<div class="video-viewport">
							<?php if ( $video_source === 'upload' ) : ?>
								<video src="<?php echo esc_url($embed_url); ?>" controls></video>
							<?php else : ?>
								<iframe src="<?php echo esc_url($embed_url); ?>" allowfullscreen></iframe>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- 9. Assigned Agent Information (Right Sidebar on Desktop / Stacks on Mobile) -->
			<div class="grid-sidebar-col agent-sidebar-card" id="section-agent-sidebar">
				<div class="sidebar-sticky-inner">
					<div class="agent-card-wrapper">
						<h3 class="sidebar-card-title"><i class="fa-solid fa-user-tie"></i> Assigned Agent</h3>
						
						<?php 
						if ( ! empty( $assigned_agents ) ) :
							$agent_count = 0;
							foreach ( $assigned_agents as $agent_post ) :
								$agent_count++;
								$agent_name = $agent_post->post_title;
								$agent_title = get_field('designation', $agent_post->ID) ?: 'Luxury Real Estate Advisor';
								$agent_phone = get_field('phone', $agent_post->ID) ?: '+91 98765 43210';
								$agent_whatsapp = get_field('whatsapp', $agent_post->ID) ?: '919876543210';
								$agent_email = get_field('email', $agent_post->ID) ?: 'info@casaview.com';
								$agent_avatar = get_the_post_thumbnail_url($agent_post->ID, 'thumbnail') ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80';
								$wa_text = urlencode("Hi {$agent_name}, I am interested in property {$property_id} - " . get_the_title() . " (" . get_permalink() . "). Please provide more details.");
								$wa_url = "https://api.whatsapp.com/send?phone={$agent_whatsapp}&text={$wa_text}";
								?>
								<div class="agent-profile-header <?php echo $agent_count > 1 ? 'divider-top' : ''; ?>">
									<img src="<?php echo esc_url($agent_avatar); ?>" alt="<?php echo esc_attr($agent_name); ?>" class="agent-avatar">
									<div class="agent-meta">
										<h4><?php echo esc_html($agent_name); ?></h4>
										<p><?php echo esc_html($agent_title); ?></p>
									</div>
								</div>
								
								<div class="agent-action-buttons">
									<a href="tel:<?php echo esc_attr($agent_phone); ?>" class="btn-agent-call">
										<i class="fa-solid fa-phone"></i> Call Now
									</a>
									<a href="mailto:<?php echo esc_attr($agent_email); ?>?subject=Inquiry: <?php echo esc_attr(get_the_title()); ?>&body=Hi, I am interested in property ID <?php echo esc_attr($property_id); ?>. Please contact me." class="btn-agent-email">
										<i class="fa-solid fa-envelope"></i> Email Agent
									</a>
									<a href="<?php echo esc_url($wa_url); ?>" target="_blank" class="btn-agent-whatsapp">
										<i class="fa-brands fa-whatsapp"></i> WhatsApp
									</a>
								</div>
								
								<a href="<?php echo esc_url(get_permalink($agent_post->ID)); ?>" class="btn-agent-view-all">
									View All Properties <i class="fa-solid fa-arrow-right"></i>
								</a>
							<?php endforeach;
						else :
							// Fallback Agent
							$agent_name = 'PR Works Consultant';
							$agent_title = 'Luxury Real Estate Advisor';
							$agent_phone = '+91 98765 43210';
							$agent_whatsapp = '919876543210';
							$agent_email = 'info@prworks.com';
							$agent_avatar = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80';
							$wa_text = urlencode("Hi, I am interested in property {$property_id} - " . get_the_title() . " (" . get_permalink() . "). Please provide more details.");
							$wa_url = "https://api.whatsapp.com/send?phone={$agent_whatsapp}&text={$wa_text}";
							?>
							<div class="agent-profile-header">
								<img src="<?php echo esc_url($agent_avatar); ?>" alt="Agent" class="agent-avatar">
								<div class="agent-meta">
									<h4><?php echo esc_html($agent_name); ?></h4>
									<p><?php echo esc_html($agent_title); ?></p>
								</div>
							</div>
							
							<div class="agent-action-buttons">
								<a href="tel:<?php echo esc_attr($agent_phone); ?>" class="btn-agent-call">
									<i class="fa-solid fa-phone"></i> Call Now
								</a>
								<a href="mailto:<?php echo esc_attr($agent_email); ?>?subject=Inquiry: <?php echo esc_attr(get_the_title()); ?>&body=Hi, I am interested in property ID <?php echo esc_attr($property_id); ?>. Please contact me." class="btn-agent-email">
									<i class="fa-solid fa-envelope"></i> Email Agent
								</a>
								<a href="<?php echo esc_url($wa_url); ?>" target="_blank" class="btn-agent-whatsapp">
									<i class="fa-brands fa-whatsapp"></i> WhatsApp
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- 10. Frequently Asked Questions -->
			<div class="grid-left-col section-block" id="section-faq">
				<h3 class="section-title">
					<i class="fa-solid fa-circle-question"></i> Frequently Asked Questions
				</h3>
				<div class="accordion-container">
					<?php if ( ! empty( $faqs ) ) : 
						$faq_count = 0;
						foreach ( $faqs as $faq ) : 
							$faq_count++;
							?>
							<div class="accordion-item <?php echo $faq_count == 1 ? 'active' : ''; ?>">
								<div class="accordion-header">
									<div class="acc-header-left">
										<i class="fa-solid fa-chevron-down accordion-icon"></i>
										<span><strong><?php echo esc_html($faq['question']); ?></strong></span>
									</div>
								</div>
								<div class="accordion-content" style="<?php echo $faq_count == 1 ? 'display:block;' : ''; ?>">
									<p><?php echo esc_html($faq['answer']); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<!-- Default FAQs -->
						<div class="accordion-item active">
							<div class="accordion-header">
								<div class="acc-header-left">
									<i class="fa-solid fa-chevron-down accordion-icon"></i>
									<span><strong>Is this property available for immediate keys handover?</strong></span>
								</div>
							</div>
							<div class="accordion-content" style="display:block;">
								<p>Yes, this property is fully completed with RERA certification and is available for immediate occupancy. Legal handover transfers can be closed in 10-14 working days.</p>
							</div>
						</div>
						<div class="accordion-item">
							<div class="accordion-header">
								<div class="acc-header-left">
									<i class="fa-solid fa-chevron-down accordion-icon"></i>
									<span><strong>What payment structures are accepted?</strong></span>
								</div>
							</div>
							<div class="accordion-content">
								<p>Standard transactions require a 10% booking deposit. Mortgages from local licensed UAE banks are fully supported. Payment plans are available for verified buyers.</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- 11. Property Location & Google Map -->
			<div class="grid-left-col section-block" id="section-map">
				<h3 class="section-title">
					<i class="fa-solid fa-map-location-dot"></i> Property Location & Map
				</h3>
				<div class="map-viewport-wrapper">
					<div id="property-map"></div>
					<div class="map-actions-buttons">
						<a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $latitude; ?>,<?php echo $longitude; ?>" target="_blank" class="btn-map-action btn-directions">
							<i class="fa-solid fa-diamond-turn-right"></i> Get Directions
						</a>
						<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $latitude; ?>,<?php echo $longitude; ?>" target="_blank" class="btn-map-action btn-open-maps">
							<i class="fa-solid fa-map-location-dot"></i> Open in Google Maps
						</a>
					</div>
				</div>
			</div>

		</div>

		<?php 
		if ( ! empty( $bottom_properties ) ) :
		?>
		<!-- Similar District Properties Grid -->
		<section class="district-section-wrapper" id="section-more-district-properties">
			<h3 class="district-section-title">
				<i class="fa-solid fa-house-chimney"></i> More Properties in <?php echo esc_html( $district ? $district : 'Kerala' ); ?>
			</h3>
			<div class="district-properties-grid">
				<?php 
				foreach ( $bottom_properties as $prop ) {
					casaview_render_district_property_card( $prop->ID );
				}
				?>
			</div>
		</section>
		<?php endif; ?>

	</div>
</main>

<!-- Vanilla JS Fullscreen Lightbox structure -->
<div class="casaview-lightbox" id="casaview-lightbox-modal">
	<div class="casaview-lightbox-content">
		<button type="button" class="casaview-lightbox-close" id="lightbox-close-btn">&times;</button>
		<img src="" id="lightbox-active-img" alt="Fullscreen Image">
		<button type="button" class="casaview-lightbox-arrow casaview-lightbox-prev" id="lightbox-prev-btn"><i class="fa-solid fa-chevron-left"></i></button>
		<button type="button" class="casaview-lightbox-arrow casaview-lightbox-next" id="lightbox-next-btn"><i class="fa-solid fa-chevron-right"></i></button>
	</div>
	<div class="casaview-lightbox-caption" id="lightbox-caption">Image 1 of 1</div>
</div>

<!-- Video Popup Modal HTML -->
<div class="casaview-video-modal" id="casaview-video-modal">
	<div class="casaview-video-content">
		<button type="button" class="casaview-video-close" id="video-modal-close-btn">&times;</button>
		<?php if ( ! empty( $video_url ) || ! empty( $video_vimeo ) || ! empty( $video_file ) ) : ?>
			<?php if ( $video_source === 'upload' ) : ?>
				<video id="modal-video-player" src="<?php echo esc_url($embed_url); ?>" style="width:100%; height:100%;" controls></video>
			<?php else : ?>
				<iframe id="modal-video-iframe" src="<?php echo esc_url($embed_url); ?>" style="width:100%; height:100%; border:none;" allow="autoplay; fullscreen" allowfullscreen></iframe>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	
	// 1. Share Dropdown Toggle
	const shareBtn = document.getElementById('share-btn');
	const shareMenu = document.getElementById('share-menu');
	if (shareBtn && shareMenu) {
		shareBtn.addEventListener('click', function(e) {
			e.stopPropagation();
			const isVisible = shareMenu.style.display === 'block';
			shareMenu.style.display = isVisible ? 'none' : 'block';
			shareBtn.classList.toggle('active', !isVisible);
		});
		document.addEventListener('click', function(e) {
			if (!shareMenu.contains(e.target) && e.target !== shareBtn) {
				shareMenu.style.display = 'none';
				shareBtn.classList.remove('active');
			}
		});
	}

	// Copy Link to Clipboard
	const copyLinkBtn = document.getElementById('copy-link-btn');
	if (copyLinkBtn) {
		copyLinkBtn.addEventListener('click', function() {
			navigator.clipboard.writeText(window.location.href).then(() => {
				const origContent = copyLinkBtn.innerHTML;
				copyLinkBtn.innerHTML = '<i class="fa-solid fa-check" style="color: #2e7d32;"></i> Copied!';
				setTimeout(() => {
					copyLinkBtn.innerHTML = origContent;
				}, 2000);
			});
		});
	}

	// 2. Favorite / Wishlist LocalStorage Toggle
	const wishlistToggles = document.querySelectorAll('.wishlist-btn-toggle');
	
	function updateWishlistUI() {
		const wishlist = JSON.parse(localStorage.getItem('property_wishlist') || '[]');
		wishlistToggles.forEach(btn => {
			const id = btn.dataset.id;
			const icon = btn.querySelector('i');
			const text = btn.querySelector('span');
			if (wishlist.includes(id)) {
				btn.classList.add('active');
				if (icon) {
					icon.className = 'fa-solid fa-heart';
					icon.style.color = '#e74c3c';
				}
				if (text) text.textContent = 'Favorited';
			} else {
				btn.classList.remove('active');
				if (icon) {
					icon.className = 'fa-regular fa-heart';
					icon.style.color = '';
				}
				if (text) text.textContent = 'Favorite';
			}
		});
	}
	
	if (wishlistToggles) {
		wishlistToggles.forEach(btn => {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				const id = this.dataset.id;
				let wishlist = JSON.parse(localStorage.getItem('property_wishlist') || '[]');
				if (wishlist.includes(id)) {
					wishlist = wishlist.filter(item => item !== id);
				} else {
					wishlist.push(id);
				}
				localStorage.setItem('property_wishlist', JSON.stringify(wishlist));
				updateWishlistUI();
			});
		});
		updateWishlistUI();
	}

	// 3. Interactive Gallery Slider Actions
	const gallerySlides = [];
	<?php 
	if ( ! empty( $actual_slides ) ) {
		foreach ( $actual_slides as $slide_url ) {
			echo "gallerySlides.push('" . esc_js( $slide_url ) . "');\n";
		}
	}
	?>

	// Click handlers for grid items
	const gridItems = document.querySelectorAll('.gallery-grid-item');
	if (gridItems) {
		gridItems.forEach(item => {
			item.addEventListener('click', function(e) {
				e.preventDefault();
				const isFallback = this.getAttribute('data-is-fallback') === 'true';
				let idx = isFallback ? 0 : (parseInt(this.dataset.index) || 0);
				
				// Don't open if clicked on play video button
				if (e.target.closest('#gallery-play-btn')) {
					return;
				}
				
				openLightbox(idx);
			});
		});
	}

	// Touch Gestures on Main Gallery Viewport (Only for first image on mobile)
	const galleryViewport = document.querySelector('.casaview-gallery-grid-layout');
	let touchStartX = 0;
	let touchEndX = 0;
	
	if (galleryViewport) {
		galleryViewport.addEventListener('touchstart', function(e) {
			touchStartX = e.changedTouches[0].screenX;
		}, {passive: true});

		galleryViewport.addEventListener('touchend', function(e) {
			touchEndX = e.changedTouches[0].screenX;
			const threshold = 45;
			if (window.innerWidth <= 768) {
				// On mobile view, swiping the viewport directly navigates the lightbox
				if (touchEndX < touchStartX - threshold) {
					openLightbox(1);
				} else if (touchEndX > touchStartX + threshold) {
					openLightbox(gallerySlides.length - 1);
				}
			}
		}, {passive: true});
	}

	// 4. Fullscreen Lightbox Modal Upgrades
	const lightboxModal = document.getElementById('casaview-lightbox-modal');
	const lightboxActiveImg = document.getElementById('lightbox-active-img');
	const lightboxClose = document.getElementById('lightbox-close-btn');
	const lightboxPrev = document.getElementById('lightbox-prev-btn');
	const lightboxNext = document.getElementById('lightbox-next-btn');
	const lightboxCaption = document.getElementById('lightbox-caption');
	let lightboxIndex = 0;

	function openLightbox(index) {
		if (!lightboxModal) return;
		lightboxModal.style.display = 'flex';
		updateLightbox(index);
	}

	function updateLightbox(index) {
		if (index < 0 || index >= gallerySlides.length) return;
		lightboxIndex = index;
		
		if (lightboxActiveImg) {
			lightboxActiveImg.classList.add('fade-out');
			setTimeout(() => {
				lightboxActiveImg.src = gallerySlides[lightboxIndex];
				if (lightboxCaption) {
					lightboxCaption.textContent = `Image ${lightboxIndex + 1} of ${gallerySlides.length}`;
				}
				lightboxActiveImg.onload = () => {
					lightboxActiveImg.classList.remove('fade-out');
				};
				
				// Preload next image
				const nextIdx = (lightboxIndex + 1) % gallerySlides.length;
				if (gallerySlides[nextIdx]) {
					const imgPre = new Image();
					imgPre.src = gallerySlides[nextIdx];
				}
			}, 120);
		}
	}

	if (lightboxClose) {
		lightboxClose.addEventListener('click', function() {
			lightboxModal.style.display = 'none';
		});
	}
	
	if (lightboxModal) {
		lightboxModal.addEventListener('click', function(e) {
			if (e.target === lightboxModal || e.target.classList.contains('casaview-lightbox-content')) {
				lightboxModal.style.display = 'none';
			}
		});
		
		// Touch gestures in lightbox
		lightboxModal.addEventListener('touchstart', function(e) {
			touchStartX = e.changedTouches[0].screenX;
		}, {passive: true});

		lightboxModal.addEventListener('touchend', function(e) {
			touchEndX = e.changedTouches[0].screenX;
			const threshold = 45;
			if (touchEndX < touchStartX - threshold) {
				lightboxNext.click();
			} else if (touchEndX > touchStartX + threshold) {
				lightboxPrev.click();
			}
		}, {passive: true});
	}

	if (lightboxPrev && lightboxNext) {
		lightboxPrev.addEventListener('click', function(e) {
			e.stopPropagation();
			let target = lightboxIndex - 1;
			if (target < 0) target = gallerySlides.length - 1;
			updateLightbox(target);
		});
		lightboxNext.addEventListener('click', function(e) {
			e.stopPropagation();
			let target = lightboxIndex + 1;
			if (target >= gallerySlides.length) target = 0;
			updateLightbox(target);
		});
	}

	// Keyboard arrows in Lightbox
	document.addEventListener('keydown', function(e) {
		if (lightboxModal && lightboxModal.style.display === 'flex') {
			if (e.key === 'ArrowLeft' && lightboxPrev) {
				lightboxPrev.click();
			} else if (e.key === 'ArrowRight' && lightboxNext) {
				lightboxNext.click();
			} else if (e.key === 'Escape' && lightboxClose) {
				lightboxClose.click();
			}
		}
	});

	// 5. Collapsible Description Panel
	const readMoreBtn = document.getElementById('read-more-btn');
	const descWrapper = document.getElementById('desc-wrapper');
	if (readMoreBtn && descWrapper) {
		if (descWrapper.scrollHeight <= 220) {
			readMoreBtn.style.display = 'none';
			const fade = document.getElementById('desc-fade');
			if (fade) fade.style.display = 'none';
			descWrapper.style.maxHeight = 'none';
		} else {
			readMoreBtn.addEventListener('click', function() {
				const isExpanded = descWrapper.classList.toggle('expanded');
				const icon = readMoreBtn.querySelector('i');
				const text = readMoreBtn.querySelector('span');
				if (isExpanded) {
					descWrapper.style.maxHeight = descWrapper.scrollHeight + 'px';
					text.textContent = 'Read Less';
					icon.className = 'fa-solid fa-chevron-up';
					const fade = document.getElementById('desc-fade');
					if (fade) fade.style.opacity = '0';
				} else {
					descWrapper.style.maxHeight = '220px';
					text.textContent = 'Read More';
					icon.className = 'fa-solid fa-chevron-down';
					const fade = document.getElementById('desc-fade');
					if (fade) fade.style.opacity = '1';
					descWrapper.scrollIntoView({ behavior: 'smooth' });
				}
			});
		}
	}

	// 6. Accordions toggling logic (Floor Plans & FAQs)
	const accHeaders = document.querySelectorAll('.accordion-header');
	accHeaders.forEach(hdr => {
		hdr.addEventListener('click', function() {
			const item = this.parentElement;
			const content = this.nextElementSibling;
			const isActive = item.classList.contains('active');
			
			if (isActive) {
				item.classList.remove('active');
				content.style.display = 'none';
			} else {
				item.classList.add('active');
				content.style.display = 'block';
			}
		});
	});

	// 7. Leaflet Map setup
	const lat = parseFloat("<?php echo esc_js($latitude); ?>");
	const lng = parseFloat("<?php echo esc_js($longitude); ?>");
	if (document.getElementById('property-map') && typeof L !== 'undefined') {
		const map = L.map('property-map', { scrollWheelZoom: false }).setView([lat, lng], 14);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap'
		}).addTo(map);
		const marker = L.marker([lat, lng]).addTo(map);
		marker.bindPopup("<b><?php echo esc_js(get_the_title()); ?></b><br><?php echo esc_js($display_location); ?>").openPopup();
	}

	// 8. Video Popup Modal actions
	const videoPlayBtn = document.getElementById('gallery-play-btn');
	const videoModal = document.getElementById('casaview-video-modal');
	const videoModalClose = document.getElementById('video-modal-close-btn');
	const videoPlayer = document.getElementById('modal-video-player');
	const videoIframe = document.getElementById('modal-video-iframe');

	if (videoPlayBtn && videoModal) {
		videoPlayBtn.addEventListener('click', function(e) {
			e.stopPropagation();
			videoModal.style.display = 'flex';
			if (videoPlayer) {
				videoPlayer.play();
			}
		});
	}

	if (videoModalClose) {
		videoModalClose.addEventListener('click', function() {
			videoModal.style.display = 'none';
			if (videoPlayer) {
				videoPlayer.pause();
			}
			if (videoIframe) {
				const src = videoIframe.src;
				videoIframe.src = '';
				videoIframe.src = src;
			}
		});
		videoModal.addEventListener('click', function(e) {
			if (e.target === videoModal) {
				videoModalClose.click();
			}
		});
	}
	// 9. Related Properties Swiper Initialization
	const relatedSwiperContainer = document.querySelector('.related-properties-swiper');
	if (relatedSwiperContainer) {
		const slideCount = relatedSwiperContainer.querySelectorAll('.property-card').length;
		if (slideCount > 0) {
			new Swiper('.related-properties-swiper', {
				loop: slideCount > 3,
				slidesPerView: 1,
				spaceBetween: 30,
				watchSlidesProgress: true,
				autoplay: {
					delay: 4000,
					disableOnInteraction: false,
					pauseOnMouseEnter: true,
				},
				speed: 800,
				grabCursor: true,
				simulateTouch: true,
				allowTouchMove: true,
				navigation: {
					nextEl: '#related-properties-next-btn',
					prevEl: '#related-properties-prev-btn',
				},
				pagination: {
					el: '.related-properties-slider-wrapper .swiper-pagination',
					clickable: true,
				},
				breakpoints: {
					576: {
						slidesPerView: 2,
						spaceBetween: 20
					},
					992: {
						slidesPerView: 3,
						spaceBetween: 30
					}
				}
			});
		}
	}

	// Single Property Page search form logic
	const singleSearchForm = document.getElementById('single-property-search-form');
	if (singleSearchForm) {
		singleSearchForm.addEventListener('submit', function(e) {
			e.preventDefault();
			const keyword = document.getElementById('single-search-keyword') ? document.getElementById('single-search-keyword').value : '';
			const state = document.getElementById('single-search-state') ? document.getElementById('single-search-state').value : '';
			const district = document.getElementById('single-search-district') ? document.getElementById('single-search-district').value : '';
			const propType = document.getElementById('single-search-prop-type') ? document.getElementById('single-search-prop-type').value : '';

			const params = new URLSearchParams();
			if (keyword.trim() !== '') {
				params.append('keyword', keyword.trim());
			}
			if (state !== '') {
				params.append('state', state);
			}
			if (district !== '') {
				params.append('district', district);
			}
			if (propType !== '') {
				params.append('type', propType);
			}

			const baseUrl = '<?php echo esc_url(home_url('/properties/')); ?>';
			const queryString = params.toString();
			const finalUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;
			
			window.location.href = finalUrl;
		});
	}

	// Dynamic cascading dropdown function for single property page
	function updateDistrictsDropdown(state, selectElement, callback) {
		if (!selectElement) return;
		selectElement.innerHTML = '<option value="">Select District...</option>';
		if (!state) {
			selectElement.disabled = true;
			if (callback) callback();
			return;
		}

		const data = new URLSearchParams();
		data.append('action', 'casaview_get_districts_for_state');
		data.append('state', state);

		fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: data.toString()
		})
		.then(res => res.json())
		.then(response => {
			if (response.success && Array.isArray(response.data)) {
				response.data.forEach(d => {
					selectElement.innerHTML += `<option value="${d}">${d}</option>`;
				});
				selectElement.disabled = false;
			} else {
				selectElement.disabled = true;
			}
			if (callback) callback();
		})
		.catch(err => {
			console.error(err);
			selectElement.disabled = true;
			if (callback) callback();
		});
	}

	const singleSearchStateSelect = document.getElementById('single-search-state');
	const singleSearchDistrictSelect = document.getElementById('single-search-district');

	if (singleSearchStateSelect && singleSearchDistrictSelect) {
		singleSearchStateSelect.addEventListener('change', function() {
			const state = this.value;
			updateDistrictsDropdown(state, singleSearchDistrictSelect);
		});
	}

});
</script>

<?php 
get_footer();
