<?php
/**
 * Property Search & Filters Template Partial
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Get all States
$states = get_posts( array(
	'post_type'      => 'state',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'title',
	'order'          => 'ASC',
) );

// 2. Get Property Status choices (from ACF)
$status_choices = array();
if ( function_exists( 'acf_get_field' ) ) {
	$status_field = acf_get_field( 'property_status' );
	if ( $status_field && ! empty( $status_field['choices'] ) ) {
		$status_choices = $status_field['choices'];
	}
}
if ( empty( $status_choices ) ) {
	$status_choices = array(
		'for_sale' => __( 'For Sale', 'custom-real-estate-manager' ),
		'sold'     => __( 'Sold', 'custom-real-estate-manager' ),
		'booked'   => __( 'Booked', 'custom-real-estate-manager' ),
		'off_plan' => __( 'Off Plan', 'custom-real-estate-manager' ),
	);
}

// 3. Get Property Type choices (from ACF)
$property_types = array(
	'villa'              => __( 'Villa', 'custom-real-estate-manager' ),
	'house'              => __( 'House', 'custom-real-estate-manager' ),
	'apartment'          => __( 'Apartment', 'custom-real-estate-manager' ),
	'flat'               => __( 'Flat', 'custom-real-estate-manager' ),
	'commercial_building' => __( 'Commercial Building', 'custom-real-estate-manager' ),
	'commercial_space'    => __( 'Commercial Space', 'custom-real-estate-manager' ),
	'plot'               => __( 'Plot', 'custom-real-estate-manager' ),
	'agricultural_land'  => __( 'Agricultural Land', 'custom-real-estate-manager' ),
);
?>
<div class="rem-filters-container">
	<h3 class="rem-filters-title">
		<span class="dashicons dashicons-filter"></span>
		<?php esc_html_e( 'Filter Properties', 'custom-real-estate-manager' ); ?>
	</h3>

	<form id="rem-filters-form" method="POST" action="">
		
		<!-- Search Field -->
		<div class="rem-filter-group search-group">
			<label for="rem-search"><?php esc_html_e( 'Keyword Search', 'custom-real-estate-manager' ); ?></label>
			<div class="rem-input-with-icon">
				<span class="dashicons dashicons-search"></span>
				<input type="text" id="rem-search" name="search" placeholder="<?php esc_attr_e( 'Search by title, features...', 'custom-real-estate-manager' ); ?>">
			</div>
		</div>

		<!-- State Selection -->
		<div class="rem-filter-group">
			<label for="rem-state"><?php esc_html_e( 'State', 'custom-real-estate-manager' ); ?></label>
			<select id="rem-state" name="state_id">
				<option value=""><?php esc_html_e( 'Select State', 'custom-real-estate-manager' ); ?></option>
				<?php foreach ( $states as $state ) : ?>
					<option value="<?php echo esc_attr( $state->ID ); ?>"><?php echo esc_html( $state->post_title ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- District Selection -->
		<div class="rem-filter-group">
			<label for="rem-district"><?php esc_html_e( 'District', 'custom-real-estate-manager' ); ?></label>
			<select id="rem-district" name="district_id" disabled>
				<option value=""><?php esc_html_e( 'Select District', 'custom-real-estate-manager' ); ?></option>
			</select>
		</div>

		<!-- Taluk Selection -->
		<div class="rem-filter-group">
			<label for="rem-taluk"><?php esc_html_e( 'Taluk', 'custom-real-estate-manager' ); ?></label>
			<select id="rem-taluk" name="taluk_id" disabled>
				<option value=""><?php esc_html_e( 'Select Taluk', 'custom-real-estate-manager' ); ?></option>
			</select>
		</div>

		<!-- Location Place Selection -->
		<div class="rem-filter-group">
			<label for="rem-place"><?php esc_html_e( 'Location', 'custom-real-estate-manager' ); ?></label>
			<input type="text" id="rem-place" name="place_name" placeholder="<?php esc_attr_e( 'e.g. Kottakkal', 'custom-real-estate-manager' ); ?>">
		</div>

		<!-- Property Type Selection -->
		<div class="rem-filter-group">
			<label for="rem-type"><?php esc_html_e( 'Property Type', 'custom-real-estate-manager' ); ?></label>
			<select id="rem-type" name="property_type">
				<option value=""><?php esc_html_e( 'All Types', 'custom-real-estate-manager' ); ?></option>
				<?php foreach ( $property_types as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- Property Status Selection (ACF Select) -->
		<div class="rem-filter-group">
			<label for="rem-status"><?php esc_html_e( 'Property Status', 'custom-real-estate-manager' ); ?></label>
			<select id="rem-status" name="status">
				<option value=""><?php esc_html_e( 'All Statuses', 'custom-real-estate-manager' ); ?></option>
				<?php foreach ( $status_choices as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- Price Range Selection -->
		<div class="rem-filter-group price-range-group">
			<label><?php esc_html_e( 'Price Range (INR)', 'custom-real-estate-manager' ); ?></label>
			<div class="rem-range-inputs">
				<div class="rem-input-wrap">
					<span class="rem-currency-symbol">₹</span>
					<input type="number" id="rem-min-price" name="min_price" placeholder="<?php esc_attr_e( 'Min Price', 'custom-real-estate-manager' ); ?>" min="0">
				</div>
				<div class="rem-range-divider">-</div>
				<div class="rem-input-wrap">
					<span class="rem-currency-symbol">₹</span>
					<input type="number" id="rem-max-price" name="max_price" placeholder="<?php esc_attr_e( 'Max Price', 'custom-real-estate-manager' ); ?>" min="0">
				</div>
			</div>
		</div>

		<!-- Reset Buttons -->
		<div class="rem-form-actions">
			<button type="button" id="rem-reset-filters" class="rem-btn rem-btn-outline rem-btn-block">
				<span class="dashicons dashicons-trash"></span>
				<?php esc_html_e( 'Clear Filters', 'custom-real-estate-manager' ); ?>
			</button>
		</div>

	</form>
</div>
