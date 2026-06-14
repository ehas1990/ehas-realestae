<?php
/**
 * Property Card Template Partial
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_the_ID();
$price            = get_field( 'property_price', $post_id );
$price_label      = get_field( 'property_price_label', $post_id );
$formatted_price  = ! empty( $price_label ) ? $price_label : ( $price ? rem_format_property_price( $price, $post_id ) : __( 'Call for Price', 'custom-real-estate-manager' ) );
$property_type    = get_field( 'property_type', $post_id );
$property_type_lbl = '';
if ( $property_type ) {
	$property_type_field = acf_get_field( 'property_type' );
	$property_type_lbl = ( $property_type_field && isset( $property_type_field['choices'][ $property_type ] ) ) ? $property_type_field['choices'][ $property_type ] : ucfirst( str_replace( '_', ' ', $property_type ) );
}

$area             = get_field( 'property_area', $post_id );
$area_unit        = get_field( 'property_area_unit', $post_id );
$area_unit_lbl    = '';
if ( $area_unit ) {
	$area_unit_field = acf_get_field( 'property_area_unit' );
	$area_unit_lbl = ( $area_unit_field && isset( $area_unit_field['choices'][ $area_unit ] ) ) ? $area_unit_field['choices'][ $area_unit ] : str_replace( '_', ' ', $area_unit );
}

$bedrooms         = get_field( 'property_bedrooms', $post_id );
$bathrooms        = get_field( 'property_bathrooms', $post_id );

// Location post titles
$state_id         = get_field( 'property_state', $post_id );
$district_id      = get_field( 'property_district', $post_id );
$place_val        = get_field( 'property_place', $post_id );
$place_name       = '';
if ( $place_val ) {
	if ( is_numeric( $place_val ) ) {
		$place_name = get_the_title( $place_val );
	} else {
		$place_name = $place_val;
	}
}

$location_parts = array();
if ( $place_name ) {
	$location_parts[] = $place_name;
}
if ( $district_id ) {
	$location_parts[] = get_the_title( $district_id );
}
if ( $state_id ) {
	$location_parts[] = get_the_title( $state_id );
}
$location_text = implode( ', ', $location_parts );

// Status (ACF Field)
$status_value = get_field( 'property_status', $post_id );
$status_class = '';
$status_label = '';
if ( $status_value ) {
	$status_field = acf_get_field( 'property_status' );
	$status_label = ( $status_field && isset( $status_field['choices'][ $status_value ] ) ) ? $status_field['choices'][ $status_value ] : ucfirst( str_replace( '_', ' ', $status_value ) );
	$status_class = 'rem-status-' . str_replace( '_', '-', $status_value );
}

// Availability (ACF Select)
$availability = get_field( 'property_availability_status', $post_id );
$availability_label = $availability ? ucfirst( $availability ) : 'Available';

?>
<div class="rem-property-card" data-post-id="<?php echo esc_attr( $post_id ); ?>">
	
	<!-- Image & Badges -->
	<div class="rem-card-media">
		<a href="<?php the_permalink(); ?>" class="rem-media-link">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rem-card-img' ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( CREM_PLUGIN_URL . 'assets/images/placeholder.jpg' ); ?>" class="rem-card-img placeholder" alt="Placeholder">
			<?php endif; ?>
		</a>
		
		<!-- Status Badge (Taxonomy) -->
		<?php if ( $status_label ) : ?>
			<span class="rem-badge rem-status-badge <?php echo esc_attr( $status_class ); ?>">
				<?php echo esc_html( $status_label ); ?>
			</span>
		<?php endif; ?>

		<!-- Availability Badge (ACF) -->
		<span class="rem-badge rem-availability-badge availability-<?php echo esc_attr( $availability ); ?>">
			<?php echo esc_html( $availability_label ); ?>
		</span>
	</div>

	<!-- Content -->
	<div class="rem-card-body">
		
		<!-- Type & Price -->
		<div class="rem-card-meta">
			<span class="rem-card-type"><?php echo esc_html( $property_type_lbl ); ?></span>
			<h4 class="rem-card-price"><?php echo esc_html( $formatted_price ); ?></h4>
		</div>

		<!-- Title -->
		<h3 class="rem-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<!-- Location -->
		<?php if ( $location_text ) : ?>
			<div class="rem-card-location">
				<span class="dashicons dashicons-location"></span>
				<span class="rem-location-text" title="<?php echo esc_attr( $location_text ); ?>"><?php echo esc_html( $location_text ); ?></span>
			</div>
		<?php endif; ?>

		<!-- Key Specs -->
		<div class="rem-card-specs">
			<?php if ( $area ) : ?>
				<div class="rem-spec-item" title="Area">
					<span class="dashicons dashicons-admin-home"></span>
					<span><?php echo esc_html( $area ) . ' ' . esc_html( $area_unit_lbl ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $bedrooms ) : ?>
				<div class="rem-spec-item" title="Bedrooms">
					<span class="dashicons dashicons-businessman"></span>
					<span><?php echo esc_html( $bedrooms ) . ' BHK'; ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $bathrooms ) : ?>
				<div class="rem-spec-item" title="Bathrooms">
					<span class="dashicons dashicons-flag"></span>
					<span><?php echo esc_html( $bathrooms ) . ' Bath'; ?></span>
				</div>
			<?php endif; ?>
		</div>

	</div>

	<!-- Footer Link -->
	<div class="rem-card-footer">
		<a href="<?php the_permalink(); ?>" class="rem-btn rem-btn-block">
			<?php esc_html_e( 'View Details', 'custom-real-estate-manager' ); ?>
			<span class="dashicons dashicons-arrow-right-alt2"></span>
		</a>
	</div>
</div>
