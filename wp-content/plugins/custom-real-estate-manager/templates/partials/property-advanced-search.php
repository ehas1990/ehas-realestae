<?php
/**
 * Property Advanced Search & Filter Widget Template Partial
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

// 2. Define Status choices
$status_choices = array(
	'for_sale' => __( 'For Sale', 'custom-real-estate-manager' ),
	'for_rent' => __( 'For Rent', 'custom-real-estate-manager' ),
	'sold'     => __( 'Sold', 'custom-real-estate-manager' ),
	'featured' => __( 'Featured', 'custom-real-estate-manager' ),
);

// 3. Define Property Type choices
$property_types = array(
	'house'      => __( 'House', 'custom-real-estate-manager' ),
	'villa'      => __( 'Villa', 'custom-real-estate-manager' ),
	'apartment'  => __( 'Apartment', 'custom-real-estate-manager' ),
	'flat'       => __( 'Flat', 'custom-real-estate-manager' ),
	'commercial' => __( 'Commercial', 'custom-real-estate-manager' ),
	'land'       => __( 'Land', 'custom-real-estate-manager' ),
	'office'     => __( 'Office', 'custom-real-estate-manager' ),
);

// 4. Define Amenities list
$amenities_list = array(
	'car_parking'   => __( 'Parking', 'custom-real-estate-manager' ),
	'lift'          => __( 'Lift', 'custom-real-estate-manager' ),
	'swimming_pool' => __( 'Swimming Pool', 'custom-real-estate-manager' ),
	'security'      => __( 'Security', 'custom-real-estate-manager' ),
	'garden'        => __( 'Garden', 'custom-real-estate-manager' ),
	'gym'           => __( 'Gym', 'custom-real-estate-manager' ),
);
?>
<div class="rem-adv-search-widget-container">
	<!-- Desktop & Tablet Horizontal Search Bar -->
	<form id="rem-adv-search-form" class="rem-adv-search-form" method="POST" action="">
		<div class="rem-search-bar-main">
			<!-- Keyword Place Search -->
			<div class="rem-search-bar-field rem-search-keyword">
				<span class="dashicons dashicons-search"></span>
				<input type="text" id="rem-search-place" name="search" placeholder="<?php esc_attr_e( 'Search by keyword, place, landmark...', 'custom-real-estate-manager' ); ?>">
			</div>

			<!-- State Cascade -->
			<div class="rem-search-bar-field">
				<select id="rem-search-state" name="state_id">
					<option value=""><?php esc_html_e( 'Select State', 'custom-real-estate-manager' ); ?></option>
					<?php foreach ( $states as $state ) : ?>
						<option value="<?php echo esc_attr( $state->ID ); ?>"><?php echo esc_html( $state->post_title ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<!-- District Cascade -->
			<div class="rem-search-bar-field">
				<select id="rem-search-district" name="district_id" disabled>
					<option value=""><?php esc_html_e( 'Select District', 'custom-real-estate-manager' ); ?></option>
				</select>
			</div>

			<!-- Taluk Cascade -->
			<div class="rem-search-bar-field">
				<select id="rem-search-taluk" name="taluk_id" disabled>
					<option value=""><?php esc_html_e( 'Select Taluk', 'custom-real-estate-manager' ); ?></option>
				</select>
			</div>

			<!-- Location Cascade -->
			<div class="rem-search-bar-field">
				<select id="rem-search-location" name="place_id" disabled>
					<option value=""><?php esc_html_e( 'Select Location', 'custom-real-estate-manager' ); ?></option>
				</select>
			</div>

			<!-- Buttons -->
			<div class="rem-search-bar-actions">
				<button type="button" id="rem-adv-toggle-btn" class="rem-btn rem-btn-advanced">
					<span class="dashicons dashicons-filter"></span>
					<span class="btn-text"><?php esc_html_e( 'Filters', 'custom-real-estate-manager' ); ?></span>
				</button>
				<button type="submit" class="rem-btn rem-btn-primary rem-btn-search">
					<span class="dashicons dashicons-search"></span>
					<span class="btn-text"><?php esc_html_e( 'Search', 'custom-real-estate-manager' ); ?></span>
				</button>
				<button type="button" id="rem-adv-reset-btn" class="rem-btn rem-btn-outline rem-btn-reset">
					<span class="dashicons dashicons-trash"></span>
				</button>
			</div>
		</div>

		<!-- Expandable Advanced Filter Panel (Desktop/Tablet) -->
		<div id="rem-adv-filter-panel" class="rem-adv-filter-panel" style="display: none;">
			<div class="rem-filter-panel-grid">
				<!-- Property Type -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Property Type', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector">
						<?php foreach ( $property_types as $key => $lbl ) : ?>
							<label class="rem-pill-checkbox">
								<input type="checkbox" name="property_type[]" value="<?php echo esc_attr( $key ); ?>">
								<span><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Property Status -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Property Status', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector">
						<?php foreach ( $status_choices as $key => $lbl ) : ?>
							<label class="rem-pill-checkbox">
								<input type="checkbox" name="status[]" value="<?php echo esc_attr( $key ); ?>">
								<span><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Price Range -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Price Range (₹)', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-range-inputs">
						<input type="number" id="rem-search-min-price" name="min_price" placeholder="<?php esc_attr_e( 'Min Price', 'custom-real-estate-manager' ); ?>" min="0">
						<span class="rem-range-sep">-</span>
						<input type="number" id="rem-search-max-price" name="max_price" placeholder="<?php esc_attr_e( 'Max Price', 'custom-real-estate-manager' ); ?>" min="0">
					</div>
				</div>

				<!-- Area Size -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Area Size (Sq Ft)', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-range-inputs">
						<input type="number" id="rem-search-min-area" name="min_area" placeholder="<?php esc_attr_e( 'Min Area', 'custom-real-estate-manager' ); ?>" min="0">
						<span class="rem-range-sep">-</span>
						<input type="number" id="rem-search-max-area" name="max_area" placeholder="<?php esc_attr_e( 'Max Area', 'custom-real-estate-manager' ); ?>" min="0">
					</div>
				</div>

				<!-- Bedrooms -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Bedrooms', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector single-select">
						<?php foreach ( array( '1+', '2+', '3+', '4+', '5+' ) as $bd ) : ?>
							<label class="rem-pill-radio">
								<input type="radio" name="bedrooms" value="<?php echo esc_attr( str_replace( '+', '', $bd ) ); ?>">
								<span><?php echo esc_html( $bd ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Bathrooms -->
				<div class="rem-panel-group">
					<label class="rem-group-label"><?php esc_html_e( 'Bathrooms', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector single-select">
						<?php foreach ( array( '1+', '2+', '3+', '4+' ) as $bt ) : ?>
							<label class="rem-pill-radio">
								<input type="radio" name="bathrooms" value="<?php echo esc_attr( str_replace( '+', '', $bt ) ); ?>">
								<span><?php echo esc_html( $bt ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Amenities Checklist -->
				<div class="rem-panel-group span-full">
					<label class="rem-group-label"><?php esc_html_e( 'Amenities', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-amenities-checklist">
						<?php foreach ( $amenities_list as $key => $lbl ) : ?>
							<label class="rem-checkbox-label">
								<input type="checkbox" name="amenities[]" value="<?php echo esc_attr( $key ); ?>">
								<span class="checkbox-custom"></span>
								<span class="label-text"><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</form>

	<!-- Mobile Floating Filter Button -->
	<div class="rem-mobile-floating-filter-trigger">
		<button type="button" id="rem-mobile-filter-trigger-btn" class="rem-btn rem-btn-floating">
			<span class="dashicons dashicons-search"></span>
			<span><?php esc_html_e( 'Filter', 'custom-real-estate-manager' ); ?></span>
		</button>
	</div>

	<!-- Mobile Full Screen Slide Panel Overlay -->
	<div id="rem-mobile-filter-panel" class="rem-mobile-filter-panel">
		<div class="rem-mobile-filter-header">
			<h3><?php esc_html_e( 'Filter Properties', 'custom-real-estate-manager' ); ?></h3>
			<button type="button" id="rem-mobile-filter-close" class="rem-close-btn">&times;</button>
		</div>

		<div class="rem-mobile-filter-body">
			<form id="rem-mobile-filter-form" class="rem-mobile-filter-form-content" onsubmit="return false;">
				<!-- Place Search Keyword -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Keyword Search', 'custom-real-estate-manager' ); ?></label>
					<input type="text" id="rem-mob-search" name="search" placeholder="<?php esc_attr_e( 'Search by keyword, place, landmark...', 'custom-real-estate-manager' ); ?>">
				</div>

				<!-- State Cascade -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'State', 'custom-real-estate-manager' ); ?></label>
					<select id="rem-mob-state" name="state_id">
						<option value=""><?php esc_html_e( 'Select State', 'custom-real-estate-manager' ); ?></option>
						<?php foreach ( $states as $state ) : ?>
							<option value="<?php echo esc_attr( $state->ID ); ?>"><?php echo esc_html( $state->post_title ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<!-- District Cascade -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'District', 'custom-real-estate-manager' ); ?></label>
					<select id="rem-mob-district" name="district_id" disabled>
						<option value=""><?php esc_html_e( 'Select District', 'custom-real-estate-manager' ); ?></option>
					</select>
				</div>

				<!-- Taluk Cascade -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Taluk', 'custom-real-estate-manager' ); ?></label>
					<select id="rem-mob-taluk" name="taluk_id" disabled>
						<option value=""><?php esc_html_e( 'Select Taluk', 'custom-real-estate-manager' ); ?></option>
					</select>
				</div>

				<!-- Location Cascade -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Location', 'custom-real-estate-manager' ); ?></label>
					<select id="rem-mob-location" name="place_id" disabled>
						<option value=""><?php esc_html_e( 'Select Location', 'custom-real-estate-manager' ); ?></option>
					</select>
				</div>

				<!-- Property Type -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Property Type', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector">
						<?php foreach ( $property_types as $key => $lbl ) : ?>
							<label class="rem-pill-checkbox">
								<input type="checkbox" name="property_type[]" value="<?php echo esc_attr( $key ); ?>">
								<span><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Property Status -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Property Status', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector">
						<?php foreach ( $status_choices as $key => $lbl ) : ?>
							<label class="rem-pill-checkbox">
								<input type="checkbox" name="status[]" value="<?php echo esc_attr( $key ); ?>">
								<span><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Price Range -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Price Range (₹)', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-range-inputs">
						<input type="number" id="rem-mob-min-price" name="min_price" placeholder="<?php esc_attr_e( 'Min Price', 'custom-real-estate-manager' ); ?>" min="0">
						<span class="rem-range-sep">-</span>
						<input type="number" id="rem-mob-max-price" name="max_price" placeholder="<?php esc_attr_e( 'Max Price', 'custom-real-estate-manager' ); ?>" min="0">
					</div>
				</div>

				<!-- Area Size -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Area Size (Sq Ft)', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-range-inputs">
						<input type="number" id="rem-mob-min-area" name="min_area" placeholder="<?php esc_attr_e( 'Min Area', 'custom-real-estate-manager' ); ?>" min="0">
						<span class="rem-range-sep">-</span>
						<input type="number" id="rem-mob-max-area" name="max_area" placeholder="<?php esc_attr_e( 'Max Area', 'custom-real-estate-manager' ); ?>" min="0">
					</div>
				</div>

				<!-- Bedrooms -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Bedrooms', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector single-select">
						<?php foreach ( array( '1+', '2+', '3+', '4+', '5+' ) as $bd ) : ?>
							<label class="rem-pill-radio">
								<input type="radio" name="bedrooms" value="<?php echo esc_attr( str_replace( '+', '', $bd ) ); ?>">
								<span><?php echo esc_html( $bd ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Bathrooms -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Bathrooms', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-pills-selector single-select">
						<?php foreach ( array( '1+', '2+', '3+', '4+' ) as $bt ) : ?>
							<label class="rem-pill-radio">
								<input type="radio" name="bathrooms" value="<?php echo esc_attr( str_replace( '+', '', $bt ) ); ?>">
								<span><?php echo esc_html( $bt ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Amenities Checklist -->
				<div class="rem-mobile-group">
					<label><?php esc_html_e( 'Amenities', 'custom-real-estate-manager' ); ?></label>
					<div class="rem-amenities-checklist">
						<?php foreach ( $amenities_list as $key => $lbl ) : ?>
							<label class="rem-checkbox-label">
								<input type="checkbox" name="amenities[]" value="<?php echo esc_attr( $key ); ?>">
								<span class="checkbox-custom"></span>
								<span class="label-text"><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>
			</form>
		</div>

		<!-- Mobile Sticky Actions -->
		<div class="rem-mobile-filter-footer">
			<button type="button" id="rem-mobile-reset-btn" class="rem-btn rem-btn-outline rem-btn-half"><?php esc_html_e( 'Reset Filter', 'custom-real-estate-manager' ); ?></button>
			<button type="button" id="rem-mobile-apply-btn" class="rem-btn rem-btn-primary rem-btn-half"><?php esc_html_e( 'Search', 'custom-real-estate-manager' ); ?></button>
		</div>
	</div>
</div>
