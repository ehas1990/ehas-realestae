<?php
/**
 * Single Property Template (Optimized for Elementor Pro & ACF)
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 0. Elementor Pro Theme Builder Overrides Optimization
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
	return;
}

get_header();

$post_id          = get_the_ID();

// Section 1: Hero & Badges Mappings
$price            = get_field( 'property_price', $post_id );
$price_label      = get_field( 'property_price_label', $post_id );
$formatted_price  = ! empty( $price_label ) ? $price_label : ( $price ? rem_format_property_price( $price, $post_id ) : __( 'Call for Price', 'custom-real-estate-manager' ) );
$property_type    = get_field( 'property_type', $post_id );
$property_type_lbl = '';
if ( $property_type ) {
	$property_type_field = acf_get_field( 'property_type' );
	$property_type_lbl = ( $property_type_field && isset( $property_type_field['choices'][ $property_type ] ) ) ? $property_type_field['choices'][ $property_type ] : ucfirst( str_replace( '_', ' ', $property_type ) );
}

$status_value = get_field( 'property_status', $post_id );
$status_label = '';
$status_class = '';
if ( $status_value ) {
	$status_field = acf_get_field( 'property_status' );
	$status_label = ( $status_field && isset( $status_field['choices'][ $status_value ] ) ) ? $status_field['choices'][ $status_value ] : ucfirst( str_replace( '_', ' ', $status_value ) );
	$status_class = 'rem-status-' . str_replace( '_', '-', $status_value );
}

$state_id         = get_field( 'property_state', $post_id );
$district_id      = get_field( 'property_district', $post_id );
$taluk_id         = get_field( 'property_taluk', $post_id );
$place_val        = get_field( 'property_place', $post_id );
$place_name       = '';
if ( $place_val ) {
	if ( is_numeric( $place_val ) ) {
		$place_name = get_the_title( $place_val );
	} else {
		$place_name = $place_val;
	}
}
$pincode          = get_field( 'property_pincode', $post_id );

$location_parts = array();
if ( $place_name ) {
	$location_parts[] = $place_name;
}
if ( $taluk_id ) {
	$location_parts[] = get_the_title( $taluk_id );
}
if ( $district_id ) {
	$location_parts[] = get_the_title( $district_id );
}
if ( $state_id ) {
	$location_parts[] = get_the_title( $state_id );
}
$full_location = implode( ', ', $location_parts );

// Section 2: Quick Information Mappings
$property_id      = get_field( 'property_id', $post_id );
$area             = get_field( 'property_area', $post_id );
$area_unit        = get_field( 'property_area_unit', $post_id );
$area_unit_lbl    = '';
if ( $area_unit ) {
	$area_unit_field = acf_get_field( 'property_area_unit' );
	$area_unit_lbl = ( $area_unit_field && isset( $area_unit_field['choices'][ $area_unit ] ) ) ? $area_unit_field['choices'][ $area_unit ] : str_replace( '_', ' ', $area_unit );
}
$bedrooms         = get_field( 'property_bedrooms', $post_id );
$bathrooms        = get_field( 'property_bathrooms', $post_id );
$parking          = get_field( 'property_parking', $post_id );
$parking_count    = get_field( 'property_parking_count', $post_id );
$furnishing       = get_field( 'property_furnishing', $post_id );
$furnishing_lbl   = '';
if ( $furnishing ) {
	$furnishing_field = acf_get_field( 'property_furnishing' );
	$furnishing_lbl = ( $furnishing_field && isset( $furnishing_field['choices'][ $furnishing ] ) ) ? $furnishing_field['choices'][ $furnishing ] : ucfirst( str_replace( '_', ' ', $furnishing ) );
}

// Section 3: Description
$description      = get_field( 'property_description', $post_id );
if ( empty( $description ) ) {
	$description = get_the_content();
}

// Section 4: Amenities Checkbox & Road Access
$features         = get_field( 'property_features', $post_id );
$road_access      = get_field( 'property_road_access', $post_id );
$property_age     = get_field( 'property_age', $post_id );

// Section 5: Location Map & Coordinates
$latitude         = get_field( 'property_latitude', $post_id );
$longitude        = get_field( 'property_longitude', $post_id );
$address_1        = get_field( 'property_address_1', $post_id );

// Section 6 & 7: Media Gallery & Documents
$gallery          = get_field( 'property_gallery', $post_id );
$video_url        = get_field( 'property_video_url', $post_id );

$brochure         = get_field( 'property_brochure_pdf', $post_id );
$approval_cert    = get_field( 'property_approval_certificate', $post_id );
$land_tax         = get_field( 'property_land_tax_receipt', $post_id );
$ownership_doc    = get_field( 'property_ownership_document', $post_id );
$survey_doc       = get_field( 'property_survey_document', $post_id );
$building_permit  = get_field( 'property_building_permit', $post_id );
$floor_plan       = get_field( 'property_floor_plan_pdf', $post_id );
$other_attach     = get_field( 'property_other_attachments', $post_id );

// Section 8: Owner / Agent Information Card
$agent_name       = get_field( 'agent_name', $post_id );
$agent_phone      = get_field( 'agent_phone', $post_id );
$agent_whatsapp   = get_field( 'agent_whatsapp', $post_id );
$agent_email      = get_field( 'agent_email', $post_id );
$agent_photo      = get_field( 'agent_photo', $post_id );

?>

<div class="rem-single-property-wrapper premium-layout">
	<div class="rem-container">
		
		<!-- Breadcrumbs -->
		<div class="rem-property-breadcrumbs">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Properties', 'custom-real-estate-manager' ); ?></a>
			<span class="dashicons dashicons-arrow-right-alt2"></span>
			<span><?php the_title(); ?></span>
		</div>

		<!-- 1. HERO SECTION -->
		<div class="rem-hero-section-card">
			<!-- Slider Gallery / Featured Image -->
			<div class="rem-hero-slider">
				<div class="rem-slides-container">
					<?php if ( ! empty( $gallery ) ) : ?>
						<?php foreach ( $gallery as $index => $img ) : ?>
							<div class="rem-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url( $img['sizes']['large'] ); ?>');">
								<a href="<?php echo esc_url( $img['url'] ); ?>" data-elementor-open-lightbox="yes" class="rem-slide-link"></a>
							</div>
						<?php endforeach; ?>
					<?php elseif ( has_post_thumbnail() ) : ?>
						<div class="rem-slide active" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'large' ) ); ?>');">
							<a href="<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'large' ) ); ?>" data-elementor-open-lightbox="yes" class="rem-slide-link"></a>
						</div>
					<?php else : ?>
						<div class="rem-slide active placeholder" style="background-image: url('<?php echo esc_url( CREM_PLUGIN_URL . 'assets/images/placeholder.jpg' ); ?>');"></div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $gallery ) && count( $gallery ) > 1 ) : ?>
					<button class="rem-slider-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
					<button class="rem-slider-next"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
				<?php endif; ?>

				<!-- Status and Type Badges on Image -->
				<div class="rem-slider-badges">
					<?php if ( $status_label ) : ?>
						<span class="rem-badge rem-status-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
					<?php endif; ?>
					<?php if ( $property_type_lbl ) : ?>
						<span class="rem-badge rem-type-badge"><?php echo esc_html( $property_type_lbl ); ?></span>
					<?php endif; ?>
				</div>
			</div>

			<!-- Title, Price & Location Details -->
			<div class="rem-hero-meta-panel">
				<div class="rem-hero-meta-left">
					<h1 class="rem-property-title"><?php the_title(); ?></h1>
					<?php if ( $full_location ) : ?>
						<p class="rem-property-location">
							<span class="dashicons dashicons-location"></span>
							<?php echo esc_html( $full_location ); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="rem-hero-meta-right">
					<p class="rem-price-label"><?php esc_html_e( 'Asking Price', 'custom-real-estate-manager' ); ?></p>
					<h2 class="rem-property-price"><?php echo esc_html( $formatted_price ); ?></h2>
				</div>
			</div>
		</div>

		<!-- TWO COLUMN LAYOUT -->
		<div class="rem-property-main-cols">
			
			<!-- Left Main Column -->
			<div class="rem-property-left-col">

				<!-- 2. QUICK PROPERTY INFORMATION (4-column card layout) -->
				<div class="rem-section-block">
					<h3 class="rem-section-title"><?php esc_html_e( 'Quick Information', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-quick-info-grid">
						
						<?php if ( $property_id ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-tag"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Property ID', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $property_id ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $property_type_lbl ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-admin-home"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Property Type', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $property_type_lbl ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $area ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-editor-expand"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Total Area', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $area ) . ' ' . esc_html( $area_unit_lbl ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $bedrooms ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-businessman"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Bedrooms', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $bedrooms ) . ' BHK'; ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $bathrooms ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-flag"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Bathrooms', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $bathrooms ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $parking ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-car"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Parking Slots', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value">
										<?php 
										if ( 'yes' === $parking ) {
											echo $parking_count ? esc_html( $parking_count ) . ' Slots' : esc_html__( 'Available', 'custom-real-estate-manager' ); 
										} else {
											esc_html_e( 'No', 'custom-real-estate-manager' );
										}
										?>
									</span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $furnishing_lbl ) : ?>
							<div class="rem-quick-info-card">
								<span class="dashicons dashicons-admin-appearance"></span>
								<div class="rem-quick-info-meta">
									<span class="meta-label"><?php esc_html_e( 'Furnishing', 'custom-real-estate-manager' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $furnishing_lbl ); ?></span>
								</div>
							</div>
						<?php endif; ?>

					</div>
				</div>

				<!-- 3. PROPERTY DESCRIPTION SECTION -->
				<div class="rem-section-block">
					<h3 class="rem-section-title"><?php esc_html_e( 'Property Description', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-property-description-container">
						<div class="rem-property-description">
							<?php echo wpautop( wp_kses_post( $description ) ); ?>
						</div>
						<button id="rem-read-more-btn" class="rem-btn rem-btn-outline"><?php esc_html_e( 'Read More', 'custom-real-estate-manager' ); ?></button>
					</div>
				</div>

				<!-- 4. PROPERTY FEATURES & AMENITIES -->
				<div class="rem-section-block">
					<h3 class="rem-section-title"><?php esc_html_e( 'Amenities & Specifications', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-features-section">
						
						<!-- Dynamic checkbox amenities checklist -->
						<?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
							<h4 class="rem-section-subtitle"><?php esc_html_e( 'Features List', 'custom-real-estate-manager' ); ?></h4>
							<ul class="rem-amenities-icon-list">
								<?php 
								$features_field = acf_get_field( 'property_features' );
								foreach ( $features as $feat_val ) : 
									$feat_lbl = ( $features_field && isset( $features_field['choices'][ $feat_val ] ) ) ? $features_field['choices'][ $feat_val ] : ucfirst( str_replace( '_', ' ', $feat_val ) );
									// Map common amenities to custom icons
									$icon_class = 'dashicons-yes';
									if ( 'water_connection' === $feat_val ) $icon_class = 'dashicons-cloud';
									elseif ( 'electricity' === $feat_val ) $icon_class = 'dashicons-performance';
									elseif ( 'swimming_pool' === $feat_val ) $icon_class = 'dashicons-admin-site-alt3';
									elseif ( 'cctv' === $feat_val ) $icon_class = 'dashicons-visibility';
									elseif ( 'garden' === $feat_val ) $icon_class = 'dashicons-carrot';
									elseif ( 'lift' === $feat_val ) $icon_class = 'dashicons-sort';
									elseif ( 'security' === $feat_val ) $icon_class = 'dashicons-shield';
									elseif ( 'car_parking' === $feat_val ) $icon_class = 'dashicons-car';
								?>
									<li>
										<span class="dashicons <?php echo esc_attr( $icon_class ); ?>"></span>
										<span class="amenity-name"><?php echo esc_html( $feat_lbl ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<!-- Specifications metadata -->
						<?php if ( $road_access || $property_age ) : ?>
							<h4 class="rem-section-subtitle spec-title"><?php esc_html_e( 'Access & Age Specifications', 'custom-real-estate-manager' ); ?></h4>
							<ul class="rem-amenities-icon-list specs">
								<?php if ( $road_access ) : ?>
									<li>
										<span class="dashicons dashicons-location-alt"></span>
										<span class="amenity-name"><strong><?php esc_html_e( 'Road Access:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $road_access ); ?></span>
									</li>
								<?php endif; ?>
								<?php if ( $property_age ) : ?>
									<li>
										<span class="dashicons dashicons-calendar-alt"></span>
										<span class="amenity-name"><strong><?php esc_html_e( 'Property Age:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $property_age ); ?></span>
									</li>
								<?php endif; ?>
							</ul>
						<?php endif; ?>

					</div>
				</div>

				<!-- 5. PROPERTY LOCATION DETAILS & MAP -->
				<?php if ( $latitude && $longitude ) : ?>
					<div class="rem-section-block">
						<h3 class="rem-section-title"><?php esc_html_e( 'Location Details & Map', 'custom-real-estate-manager' ); ?></h3>
						<div class="rem-location-details-content">
							<div class="rem-location-info-panel">
								<p><strong><?php esc_html_e( 'Location/Place:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $place_name ? $place_name : 'N/A' ); ?></p>
								<p><strong><?php esc_html_e( 'Taluk:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $taluk_id ? get_the_title( $taluk_id ) : 'N/A' ); ?></p>
								<p><strong><?php esc_html_e( 'District:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $district_id ? get_the_title( $district_id ) : 'N/A' ); ?></p>
								<p><strong><?php esc_html_e( 'State:', 'custom-real-estate-manager' ); ?></strong> <?php echo esc_html( $state_id ? get_the_title( $state_id ) : 'N/A' ); ?></p>
							</div>
							
							<!-- Dynamic Leaflet Map -->
							<div id="rem-single-property-map" 
								 style="height: 350px; width: 100%; border-radius: 12px; margin-top: 15px; border: 1px solid rgba(0,0,0,0.06);" 
								 data-lat="<?php echo esc_attr( $latitude ); ?>" 
								 data-lng="<?php echo esc_attr( $longitude ); ?>"
								 data-title="<?php echo esc_attr( get_the_title() ); ?>">
							</div>
						</div>
					</div>
				<?php endif; ?>

				<!-- 6. PROPERTY GALLERY -->
				<?php if ( ! empty( $gallery ) ) : ?>
					<div class="rem-section-block">
						<h3 class="rem-section-title"><?php esc_html_e( 'Photo Gallery', 'custom-real-estate-manager' ); ?></h3>
						<div class="rem-photo-gallery-grid">
							<?php foreach ( $gallery as $img ) : ?>
								<div class="rem-gallery-item-wrapper">
									<a href="<?php echo esc_url( $img['url'] ); ?>" data-elementor-open-lightbox="yes" class="rem-gallery-lightbox-link">
										<img src="<?php echo esc_url( $img['sizes']['medium_large'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" class="rem-gallery-grid-img">
										<div class="rem-gallery-overlay">
											<span class="dashicons dashicons-search"></span>
										</div>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- 7. PROPERTY DOCUMENTS -->
				<?php if ( $brochure || $approval_cert || $land_tax || $ownership_doc || $survey_doc || $building_permit || $floor_plan || $other_attach ) : ?>
					<div class="rem-section-block">
						<h3 class="rem-section-title"><?php esc_html_e( 'Verified Documents', 'custom-real-estate-manager' ); ?></h3>
						<div class="rem-documents-list-grid">
							
							<?php if ( $ownership_doc ) : ?>
								<a href="<?php echo esc_url( $ownership_doc['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-media-document"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Sale Deed / Ownership Document', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download verified deed copy', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $land_tax ) : ?>
								<a href="<?php echo esc_url( $land_tax['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-media-text"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Land Tax Receipt', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download latest tax paid receipt', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $survey_doc ) : ?>
								<a href="<?php echo esc_url( $survey_doc['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-images-alt"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Survey Sketch / Document', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download survey boundary map sketch', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $approval_cert ) : ?>
								<a href="<?php echo esc_url( $approval_cert['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-awards"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Encumbrance Certificate / Approval', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download encumbrance or layout approval', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $building_permit ) : ?>
								<a href="<?php echo esc_url( $building_permit['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-portfolio"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Building Permit Copy', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download legal building permit blueprint', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $floor_plan ) : ?>
								<a href="<?php echo esc_url( $floor_plan['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-layout"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Floor Plan PDF', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download structural floor plan layout', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $brochure ) : ?>
								<a href="<?php echo esc_url( $brochure['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-pdf"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php esc_html_e( 'Property Brochure PDF', 'custom-real-estate-manager' ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download high-quality brochure PDF', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

							<?php if ( $other_attach ) : ?>
								<a href="<?php echo esc_url( $other_attach['url'] ); ?>" target="_blank" class="rem-doc-download-card">
									<span class="dashicons dashicons-paperclip"></span>
									<div class="rem-doc-info">
										<span class="rem-doc-title"><?php echo esc_html( ! empty( $other_attach['title'] ) ? $other_attach['title'] : __( 'Other Document Attachment', 'custom-real-estate-manager' ) ); ?></span>
										<span class="rem-doc-sub"><?php esc_html_e( 'Download additional documentation files', 'custom-real-estate-manager' ); ?></span>
									</div>
									<span class="dashicons dashicons-download rem-download-icon"></span>
								</a>
							<?php endif; ?>

						</div>
					</div>
				<?php endif; ?>

			</div>

			<!-- Right Sidebar Column -->
			<div class="rem-property-right-col">
				<div class="rem-sidebar-sticky">

					<!-- 8. PROPERTY OWNER / AGENT INFORMATION -->
					<?php if ( $agent_name ) : ?>
						<div class="rem-agent-card premium-card">
							<h4 class="rem-agent-card-title"><?php esc_html_e( 'Owner / Listing Agent', 'custom-real-estate-manager' ); ?></h4>
							
							<div class="rem-agent-profile">
								<?php if ( ! empty( $agent_photo ) ) : ?>
									<img src="<?php echo esc_url( get_attachment_link( $agent_photo ) ? wp_get_attachment_image_url( $agent_photo, 'thumbnail' ) : $agent_photo ); ?>" class="rem-agent-avatar" alt="<?php echo esc_attr( $agent_name ); ?>">
								<?php else : ?>
									<div class="rem-agent-avatar-placeholder">
										<span class="dashicons dashicons-admin-users"></span>
									</div>
								<?php endif; ?>

								<div class="rem-agent-info">
									<h3 class="rem-agent-name"><?php echo esc_html( $agent_name ); ?></h3>
									<?php if ( $agent_email ) : ?>
										<p class="rem-agent-email">
											<span class="dashicons dashicons-email"></span>
											<a href="mailto:<?php echo esc_attr( $agent_email ); ?>"><?php echo esc_html( $agent_email ); ?></a>
										</p>
									<?php endif; ?>
								</div>
							</div>

							<!-- Action Buttons -->
							<div class="rem-agent-actions">
								<?php if ( $agent_phone ) : ?>
									<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $agent_phone ) ); ?>" class="rem-btn rem-btn-block rem-btn-primary">
										<span class="dashicons dashicons-phone"></span>
										<?php esc_html_e( 'Call Owner', 'custom-real-estate-manager' ); ?>
									</a>
								<?php endif; ?>

								<?php if ( $agent_whatsapp ) : ?>
									<?php
									$clean_wa = preg_replace( '/[^0-9]/', '', $agent_whatsapp );
									$wa_msg   = rawurlencode( sprintf( __( 'Hi, I am interested in your property "%s" (ID: %s). Please share details. Link: %s', 'custom-real-estate-manager' ), get_the_title(), $property_id, get_permalink() ) );
									?>
									<a href="https://wa.me/<?php echo esc_attr( $clean_wa ); ?>?text=<?php echo $wa_msg; ?>" target="_blank" class="rem-btn rem-btn-block rem-btn-success">
										<span class="dashicons dashicons-whatsapp"></span>
										<?php esc_html_e( 'WhatsApp Owner', 'custom-real-estate-manager' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- 9. PROPERTY ENQUIRY FORM -->
					<div class="rem-enquiry-form-card">
						<h4 class="rem-enquiry-card-title"><?php esc_html_e( 'Enquire About Property', 'custom-real-estate-manager' ); ?></h4>
						<form id="rem-enquiry-form" method="post" action="">
							<input type="hidden" name="property_title" value="<?php echo esc_attr( get_the_title() ); ?>">
							
							<div class="rem-form-group">
								<label for="enquiry-name"><?php esc_html_e( 'Name', 'custom-real-estate-manager' ); ?> *</label>
								<input type="text" id="enquiry-name" name="name" required placeholder="<?php esc_attr_e( 'Enter your name', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="enquiry-phone"><?php esc_html_e( 'Phone Number', 'custom-real-estate-manager' ); ?> *</label>
								<input type="tel" id="enquiry-phone" name="phone" required placeholder="<?php esc_attr_e( 'Enter your phone number', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="enquiry-email"><?php esc_html_e( 'Email Address', 'custom-real-estate-manager' ); ?> *</label>
								<input type="email" id="enquiry-email" name="email" required placeholder="<?php esc_attr_e( 'Enter your email', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="enquiry-message"><?php esc_html_e( 'Message', 'custom-real-estate-manager' ); ?></label>
								<textarea id="enquiry-message" name="message" rows="4" placeholder="<?php esc_attr_e( 'I would like to enquire about this property...', 'custom-real-estate-manager' ); ?>"></textarea>
							</div>

							<button type="submit" class="rem-btn rem-btn-primary rem-btn-block">
								<span class="dashicons dashicons-email"></span>
								<?php esc_html_e( 'Submit Enquiry', 'custom-real-estate-manager' ); ?>
							</button>

							<div class="rem-enquiry-response"></div>
						</form>
					</div>

				</div>
			</div>

		</div>

		<!-- 10. SIMILAR PROPERTIES SECTION -->
		<?php
		$related_args = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__not_in'   => array( $post_id ),
			'orderby'        => 'rand',
			'meta_query'     => array(
				'relation' => 'AND',
			),
		);

		$similarity_query = array(
			'relation' => 'OR',
		);

		// Similarity filters (District, Property Type, or Status)
		if ( $district_id ) {
			$similarity_query[] = array(
				'key'     => 'property_district',
				'value'   => intval( $district_id ),
				'compare' => '=',
			);
		}
		if ( $property_type ) {
			$similarity_query[] = array(
				'key'     => 'property_type',
				'value'   => $property_type,
				'compare' => '=',
			);
		}
		if ( $status_value ) {
			$similarity_query[] = array(
				'key'     => 'property_status',
				'value'   => $status_value,
				'compare' => '=',
			);
		}

		if ( count( $similarity_query ) > 1 ) {
			$related_args['meta_query'][] = $similarity_query;
		}

		// Ensure we don't display hidden ones
		$related_args['meta_query'][] = array(
			'key'     => 'property_availability_status',
			'value'   => 'hidden',
			'compare' => '!=',
		);

		$related_query = new WP_Query( $related_args );

		if ( $related_query->have_posts() ) :
			?>
			<div class="rem-related-properties-section">
				<h3 class="rem-related-title"><?php esc_html_e( 'Similar Properties', 'custom-real-estate-manager' ); ?></h3>
				<div class="rem-related-grid">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();
						include CREM_PLUGIN_DIR . 'templates/partials/property-card.php';
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<!-- Sticky Mobile Contact Bar & Sticky WhatsApp Button -->
<?php if ( $agent_phone || $agent_whatsapp ) : ?>
	<div class="rem-sticky-mobile-contact-bar">
		<?php if ( $agent_phone ) : ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $agent_phone ) ); ?>" class="rem-sticky-contact-btn call">
				<span class="dashicons dashicons-phone"></span>
				<?php esc_html_e( 'Call', 'custom-real-estate-manager' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( $agent_whatsapp ) : ?>
			<?php
			$clean_wa = preg_replace( '/[^0-9]/', '', $agent_whatsapp );
			$wa_msg   = rawurlencode( sprintf( __( 'Hi, I am interested in your property "%s" (ID: %s). Please share details. Link: %s', 'custom-real-estate-manager' ), get_the_title(), $property_id, get_permalink() ) );
			?>
			<a href="https://wa.me/<?php echo esc_attr( $clean_wa ); ?>?text=<?php echo $wa_msg; ?>" target="_blank" class="rem-sticky-contact-btn whatsapp">
				<span class="dashicons dashicons-whatsapp"></span>
				<?php esc_html_e( 'WhatsApp', 'custom-real-estate-manager' ); ?>
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ( $agent_whatsapp ) : ?>
	<?php
	$clean_wa = preg_replace( '/[^0-9]/', '', $agent_whatsapp );
	$wa_msg   = rawurlencode( sprintf( __( 'Hi, I am interested in your property "%s" (ID: %s). Please share details. Link: %s', 'custom-real-estate-manager' ), get_the_title(), $property_id, get_permalink() ) );
	?>
	<a href="https://wa.me/<?php echo esc_attr( $clean_wa ); ?>?text=<?php echo $wa_msg; ?>" target="_blank" class="rem-floating-whatsapp-btn" title="<?php esc_attr_e( 'Contact WhatsApp', 'custom-real-estate-manager' ); ?>">
		<span class="dashicons dashicons-whatsapp"></span>
	</a>
<?php endif; ?>

<?php
get_footer();
