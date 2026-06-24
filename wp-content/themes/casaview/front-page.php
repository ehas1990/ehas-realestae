<?php
/**
 * The template for displaying the custom homepage
 */

get_header();

// Helper to generate section styling from ACF field names
if ( ! function_exists('hp_get_section_style') ) {
	function hp_get_section_style( $prefix, $default_top = 100, $default_bottom = 100, $default_color = '' ) {
		$top = get_field( $prefix . '_top_padding' );
		$top = ( $top !== null && $top !== '' ) ? intval( $top ) : $default_top;

		$bottom = get_field( $prefix . '_bottom_padding' );
		$bottom = ( $bottom !== null && $bottom !== '' ) ? intval( $bottom ) : $default_bottom;

		$bg_color = get_field( $prefix . '_bg_color' ) ?: $default_color;
		$bg_image = get_field( $prefix . '_bg_image' );

		$style = "padding-top: {$top}px; padding-bottom: {$bottom}px;";
		if ( $bg_color ) {
			$style .= " background-color: {$bg_color};";
		}
		if ( $bg_image ) {
			$style .= " background-image: url('" . esc_url( $bg_image ) . "');";
		}
		return $style;
	}
}

// Helper to dynamically count properties in a district
if ( ! function_exists('hp_get_district_property_count') ) {
	function hp_get_district_property_count( $d_name ) {
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare("
			SELECT COUNT(p.ID) 
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = 'property'
			  AND p.post_status = 'publish'
			  AND pm.meta_key = 'district'
			  AND pm.meta_value = %s
		", $d_name) );
		return intval($count);
	}
}

// Helper to check if a section is enabled (supports new page NULL defaults)
if ( ! function_exists('hp_is_section_enabled') ) {
	function hp_is_section_enabled( $field_name, $default = true ) {
		$val = get_field( $field_name );
		if ( $val === null || $val === '' ) {
			return $default;
		}
		return (bool) $val;
	}
}
?>

<!-- 1. Hero Banner Section -->
<?php
$hero_enable = hp_is_section_enabled('hero_enable');
if ( $hero_enable ) :
	$hero_bg = get_field('hero_bg_image') ?: get_template_directory_uri() . '/assets/images/hero_waterfront_yacht.png';
	$hero_mobile_bg = get_field('hero_mobile_bg_image') ?: $hero_bg;
	$hero_small_title = get_field('hero_small_title') ?: 'Boutique Real Estate Agency';
	$hero_main_heading = get_field('hero_main_heading') ?: 'Find Your Signature Address';
	$hero_description = get_field('hero_description') ?: 'Elite boutique properties, penthouses, and signature villas in the most exclusive postcodes.';
	$hero_search_enable = hp_is_section_enabled('hero_search_enable');
	$hero_search_btn_text = get_field('hero_search_btn_text') ?: 'Search Properties';
	$hero_cta_text = get_field('hero_cta_text') ?: 'Explore Projects';
	$hero_cta_link = get_field('hero_cta_link') ?: '#properties';

	$hero_style = hp_get_section_style('hero', 100, 100, '#0f172a');
?>
<style>
.hp-hero {
	background-image: url('<?php echo esc_url($hero_bg); ?>') !important;
}
.modern-search-button {
	width: auto !important;
	padding: 0 20px !important;
	gap: 8px !important;
}
<?php if ( $hero_mobile_bg ) : ?>
@media (max-width: 768px) {
	.hp-hero {
		background-image: url('<?php echo esc_url($hero_mobile_bg); ?>') !important;
	}
}
<?php endif; ?>

/* Scoped Redesigned FAQ Section Styles */
.premium-faq-section {
	background-color: #ffffff;
	color: #1a1b1f;
	padding: 100px 0;
	font-family: var(--font-en, 'Manrope', sans-serif);
}
.faq-container {
	max-width: 95%;
	margin: 0 auto;
	padding: 0 20px;
	box-sizing: border-box;
}
.faq-grid {
	display: grid;
	grid-template-columns: 1fr 1.3fr;
	gap: 60px;
}
@media (max-width: 991px) {
	.faq-grid {
		grid-template-columns: 1fr;
		gap: 40px;
	}
}
.faq-left-col {
	display: flex;
	flex-direction: column;
	gap: 30px;
}
.faq-main-title {
	font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
	font-size: clamp(36px, 4vw, 54px) !important;
	font-weight: 400 !important;
	line-height: 1.15 !important;
	color: #1a1b1f !important;
	margin: 0 !important;
	text-transform: none !important;
	letter-spacing: -0.5px !important;
}
.faq-main-title .gold-text {
	color: var(--accent-gold, #c5a880) !important;
}
.faq-ask-card {
	background-color: #f0ede8;
	border-radius: 24px;
	padding: 40px 30px;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 20px;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}
.ask-card-title {
	font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
	font-size: 24px !important;
	font-weight: 400 !important;
	color: #1a1b1f !important;
	margin: 0 !important;
}
.ask-card-desc {
	font-size: 15px !important;
	color: #606060 !important;
	margin: 0 !important;
	line-height: 1.5 !important;
	max-width: 250px !important;
}
.ask-card-btn {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	background-color: #f25b38;
	color: #ffffff !important;
	padding: 12px 28px;
	border-radius: 10px;
	font-size: 14px;
	font-weight: 700;
	text-decoration: none;
	transition: all 0.3s ease;
	box-shadow: 0 4px 15px rgba(242, 91, 56, 0.2);
}
.ask-card-btn:hover {
	background-color: #e04f2f;
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(242, 91, 56, 0.35);
}

.faq-right-col {
	display: flex;
	flex-direction: column;
	gap: 35px;
}
.faq-section-desc {
	font-size: 16px !important;
	line-height: 1.6 !important;
	color: #404040 !important;
	margin: 0 !important;
}
.faq-accordion-list {
	display: flex;
	flex-direction: column;
	gap: 15px;
}
.faq-accordion-item {
	background-color: #ffffff;
	border: 1px solid #e2e8f0;
	border-radius: 16px;
	overflow: hidden;
	transition: all 0.3s ease;
}
.faq-accordion-item.active {
	background-color: #f0ede8;
	border-color: #f0ede8;
}
.faq-accordion-header {
	width: 100%;
	background: transparent;
	border: none;
	padding: 24px 30px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	cursor: pointer;
	text-align: left;
	outline: none;
}
.faq-accordion-question {
	font-family: var(--font-en), sans-serif;
	font-size: 16px;
	font-weight: 600;
	color: #1a1b1f;
	line-height: 1.4;
}
.faq-accordion-item.active .faq-accordion-question {
	font-weight: 700;
}
.faq-accordion-icon {
	font-size: 20px;
	color: #1a1b1f;
	font-weight: 300;
	user-select: none;
	flex-shrink: 0;
}
.faq-accordion-body {
	max-height: 0;
	overflow: hidden;
	transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.faq-accordion-content {
	padding: 0 30px 24px 30px;
	font-size: 14px;
	line-height: 1.6;
	color: #505050;
}
.faq-accordion-content p {
	margin: 0;
}

/* Scoped Redesigned CTA Section Styles */
.premium-cta-section {
	position: relative;
	padding: 120px 0;
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
	display: flex;
	align-items: center;
	justify-content: center;
	text-align: center;
	color: #ffffff;
}
.cta-overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 1;
}
.cta-container {
	position: relative;
	z-index: 2;
	max-width: 800px;
	margin: 0 auto;
	padding: 0 20px;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 24px;
}
.cta-badge-wrapper {
	margin-bottom: 8px;
}
.cta-badge-img {
	max-height: 64px;
	object-fit: contain;
}
.cta-title {
	font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
	font-size: clamp(32px, 5vw, 48px) !important;
	font-weight: 400 !important;
	line-height: 1.2 !important;
	color: #ffffff !important;
	margin: 0 !important;
	text-transform: none !important;
	letter-spacing: -0.5px !important;
}
.cta-desc {
	font-family: var(--font-en), sans-serif !important;
	font-size: clamp(15px, 2vw, 18px) !important;
	line-height: 1.6 !important;
	color: rgba(255, 255, 255, 0.9) !important;
	margin: 0 !important;
	max-width: 600px !important;
}
.cta-btn-group {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 16px;
	margin-top: 10px;
}
@media (max-width: 576px) {
	.cta-btn-group {
		flex-direction: column;
		width: 100%;
	}
}
.cta-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 14px 32px;
	border-radius: 8px;
	font-size: 14px;
	font-weight: 700;
	text-decoration: none;
	text-transform: uppercase;
	letter-spacing: 1px;
	transition: all 0.3s ease;
}
@media (max-width: 576px) {
	.cta-btn {
		width: 100%;
		box-sizing: border-box;
	}
}
.cta-btn-primary {
	background-color: var(--accent-gold, #c5a880);
	color: #1a1b1f !important;
	border: 1px solid var(--accent-gold, #c5a880);
	box-shadow: 0 4px 15px rgba(197, 168, 128, 0.2);
}
.cta-btn-primary:hover {
	background-color: #d4b88f;
	border-color: #d4b88f;
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(197, 168, 128, 0.35);
}
.cta-btn-secondary {
	background-color: transparent;
	color: #ffffff !important;
	border: 1px solid rgba(255, 255, 255, 0.3);
}
.cta-btn-secondary:hover {
	background-color: rgba(255, 255, 255, 0.1);
	border-color: #ffffff;
	transform: translateY(-2px);
}
</style>
<section class="hp-section hp-hero hp-reveal" style="<?php echo esc_attr($hero_style); ?>">
	<div class="hp-hero__overlay"></div>
	<div class="hp-hero__container">
		<?php if ( $hero_small_title ) : ?>
			<span class="hp-hero__small-title"><?php echo esc_html($hero_small_title); ?></span>
		<?php endif; ?>
		<?php if ( $hero_main_heading ) : ?>
			<h1 class="hp-hero__main-heading"><?php echo esc_html($hero_main_heading); ?></h1>
		<?php endif; ?>
		<?php if ( $hero_description ) : ?>
			<p class="hp-hero__description"><?php echo esc_html($hero_description); ?></p>
		<?php endif; ?>

		<?php if ( $hero_search_enable ) : ?>
			<div class="hp-hero__search-wrapper">
				<div class="hp-hero__search-panel">
					<div class="modern-tabs">
						<button class="modern-tab active" type="button" data-type="all">All</button>
						<button class="modern-tab" type="button" data-type="buy">For Sale</button>
						<button class="modern-tab" type="button" data-type="rent">For Rent</button>
					</div>
					<form id="homepage-search-form" class="hero-filter-bar">
						<input type="hidden" name="listing_type" id="search-listing-type" value="all">
						
						<!-- Keyword search -->
						<input type="text" name="keyword" id="search-keyword" class="hero-filter-input" placeholder="Search properties..." value="<?php echo esc_attr($_GET['keyword'] ?? ''); ?>">

						<!-- State -->
						<select name="state" id="search-state" class="hero-filter-select">
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
						<select name="district" id="search-district" class="hero-filter-select" disabled>
							<option value="">Select District...</option>
						</select>

						<!-- Property Type -->
						<select name="prop_type" id="search-prop-type" class="hero-filter-select">
							<option value="">All Type</option>
							<?php
							$types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
							foreach ( $types as $t ) {
								echo '<option value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
							}
							?>
						</select>

						<!-- Action buttons grouped side by side -->
						<div class="hero-filter-btn-group">
							<!-- Advanced Trigger Button -->
							<button type="button" class="hero-filter-advanced" id="advanced-toggle-btn" title="Advanced Filters">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<line x1="4" y1="21" x2="4" y2="14"></line>
									<line x1="4" y1="10" x2="4" y2="3"></line>
									<line x1="12" y1="21" x2="12" y2="12"></line>
									<line x1="12" y1="8" x2="12" y2="3"></line>
									<line x1="20" y1="21" x2="20" y2="16"></line>
									<line x1="20" y1="12" x2="20" y2="3"></line>
									<line x1="1" y1="14" x2="7" y2="14"></line>
									<line x1="9" y1="8" x2="15" y2="8"></line>
									<line x1="17" y1="16" x2="23" y2="16"></line>
								</svg>
							</button>

							<!-- Submit Button -->
							<button type="submit" class="hero-filter-search">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="11" cy="11" r="8"></circle>
									<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
								</svg>
							</button>
						</div>

						<!-- Advanced Fields Panel (toggled) -->
						<div class="modern-advanced-panel" id="advanced-panel" style="display: none;">
							<div class="advanced-grid">
								<!-- Property Category (taxonomy) -->
								<div class="advanced-field-group">
									<label>Property Category</label>
									<select name="prop_cat" id="adv-prop-cat" class="modern-select">
										<option value="">All Categories</option>
										<?php
										$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
										foreach ( $categories as $c ) {
											echo '<option value="' . esc_attr($c->slug) . '">' . esc_html($c->name) . '</option>';
										}
										?>
									</select>
								</div>

								<!-- Min Price -->
								<div class="advanced-field-group">
									<label>Min Price (₹)</label>
									<input type="number" name="min_price" id="adv-min-price" class="modern-text-input" placeholder="e.g. 500000" style="padding: 10px 14px;">
								</div>

								<!-- Max Price -->
								<div class="advanced-field-group">
									<label>Max Price (₹)</label>
									<input type="number" name="max_price" id="adv-max-price" class="modern-text-input" placeholder="e.g. 10000000" style="padding: 10px 14px;">
								</div>

								<!-- Bedrooms -->
								<div class="advanced-field-group">
									<label>Bedrooms</label>
									<select name="beds" id="adv-beds" class="modern-select">
										<option value="">Any Beds</option>
										<option value="1">1 Bed</option>
										<option value="2">2 Beds</option>
										<option value="3">3 Beds</option>
										<option value="4">4 Beds</option>
										<option value="5">5+ Beds</option>
									</select>
								</div>

								<!-- Bathrooms -->
								<div class="advanced-field-group">
									<label>Bathrooms</label>
									<select name="baths" id="adv-baths" class="modern-select">
										<option value="">Any Baths</option>
										<option value="1">1 Bath</option>
										<option value="2">2 Baths</option>
										<option value="3">3 Baths</option>
										<option value="4">4 Baths</option>
										<option value="5">5+ Baths</option>
									</select>
								</div>

								<!-- Area Size -->
								<div class="advanced-field-group">
									<label>Area Size (Min Sq.Ft.)</label>
									<input type="number" name="area_size" id="adv-area-size" class="modern-text-input" placeholder="e.g. 1500" style="padding: 10px 14px;">
								</div>
							</div>
						</div>
					</form>
				</div>

				<!-- Mobile Modal Sheet inside search wrapper -->
				<div class="advanced-filters-backdrop" id="advanced-filters-backdrop"></div>
				<div class="advanced-filters-modal" id="advanced-filters-modal">
					<div class="modal-drag-handle"></div>
					<div class="advanced-filters-header">
						<div class="modal-title-row">
							<h3>Filters</h3>
							<button type="button" class="btn-close-modal" id="close-modal-btn">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
							</button>
						</div>
					</div>
					<div class="advanced-filters-body">
						<!-- State -->
						<div class="modal-field-group">
							<label class="modal-field-label">State</label>
							<select name="state_mobile" id="search-state-mobile" class="modern-select">
								<option value="">All States</option>
								<?php
								foreach ( $indian_states as $s ) {
									echo '<option value="' . esc_attr($s) . '">' . esc_html($s) . '</option>';
								}
								?>
							</select>
						</div>
						<!-- District -->
						<div class="modal-field-group">
							<label class="modal-field-label">District</label>
							<select name="district_mobile" id="search-district-mobile" class="modern-select" disabled>
								<option value="">Select District...</option>
							</select>
						</div>
						<!-- Property Type -->
						<div class="modal-field-group">
							<label class="modal-field-label">Property Type</label>
							<select name="prop_type_mobile" id="search-prop-type-mobile" class="modern-select">
								<option value="">All Types</option>
								<?php
								foreach ( $types as $t ) {
									echo '<option value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
								}
								?>
							</select>
						</div>
						<!-- Property Category -->
						<div class="modal-field-group">
							<label class="modal-field-label">Property Category</label>
							<select name="prop_cat_mobile" id="adv-prop-cat-mobile" class="modern-select">
								<option value="">All Categories</option>
								<?php
								$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
								foreach ( $categories as $c ) {
									echo '<option value="' . esc_attr($c->slug) . '">' . esc_html($c->name) . '</option>';
								}
								?>
							</select>
						</div>
						<!-- Bedrooms -->
						<div class="modal-field-group">
							<label class="modal-field-label">Bedrooms</label>
							<select name="beds_mobile" id="adv-beds-mobile" class="modern-select">
								<option value="">Any Beds</option>
								<option value="1">1 Bed</option>
								<option value="2">2 Beds</option>
								<option value="3">3 Beds</option>
								<option value="4">4 Beds</option>
								<option value="5">5+ Beds</option>
							</select>
						</div>
						<!-- Bathrooms -->
						<div class="modal-field-group">
							<label class="modal-field-label">Bathrooms</label>
							<select name="baths_mobile" id="adv-baths-mobile" class="modern-select">
								<option value="">Any Baths</option>
								<option value="1">1 Bath</option>
								<option value="2">2 Baths</option>
								<option value="3">3 Baths</option>
								<option value="4">4 Baths</option>
								<option value="5">5+ Baths</option>
							</select>
						</div>
						<!-- Min Price -->
						<div class="modal-field-group">
							<label class="modal-field-label">Min Price (₹)</label>
							<input type="number" name="min_price_mobile" id="adv-min-price-mobile" class="modern-text-input" placeholder="e.g. 500000">
						</div>
						<!-- Max Price -->
						<div class="modal-field-group">
							<label class="modal-field-label">Max Price (₹)</label>
							<input type="number" name="max_price_mobile" id="adv-max-price-mobile" class="modern-text-input" placeholder="e.g. 10000000">
						</div>
						<!-- Area Size -->
						<div class="modal-field-group">
							<label class="modal-field-label">Area Size (Min Sq.Ft.)</label>
							<input type="number" name="area_size_mobile" id="adv-area-size-mobile" class="modern-text-input" placeholder="e.g. 1500">
						</div>
					</div>
					<div class="advanced-filters-footer">
						<button type="button" class="btn-modal-reset" id="mobile-reset-btn">Reset</button>
						<button type="button" class="btn-modal-apply" id="mobile-apply-btn">Apply Filters</button>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- Scroll Indicator Mouse Animation -->
		<a href="#properties" class="hp-hero__scroll-indicator">
			<span>Scroll</span>
			<div class="hp-hero__scroll-indicator-mouse">
				<div class="hp-hero__scroll-indicator-wheel"></div>
			</div>
		</a>
	</div>
</section>
<?php endif; ?>


<!-- 2. Most Trending Projects Section -->
<?php
$trending_enable = hp_is_section_enabled('trending_enable');
if ( $trending_enable ) :
	$trending_title = get_field('trending_title') ?: 'Most Trending Projects';
	$trending_subtitle = get_field('trending_subtitle') ?: 'Discover our handpicked premium properties for sale and rent.';
	$trending_filter_enable = hp_is_section_enabled('trending_filter_enable', true);
	$trending_posts_count = intval(get_field('trending_posts_count') ?: 10);

	$trending_top = get_field('trending_top_padding');
	$trending_bottom = get_field('trending_bottom_padding');
	$top_padding = ( $trending_top !== null && $trending_top !== '' ) ? intval( $trending_top ) : 100;
	$bottom_padding = ( $trending_bottom !== null && $trending_bottom !== '' ) ? intval( $trending_bottom ) : 100;

	$trending_style = "padding-top: {$top_padding}px; padding-bottom: {$bottom_padding}px; background-color: #F8F8F8;";

	// Slider Settings
	$trending_slider_enable = hp_is_section_enabled('trending_slider_enable', true);
	$trending_autoplay_enable = hp_is_section_enabled('trending_autoplay_enable', true);
	$trending_autoplay_speed = intval(get_field('trending_autoplay_speed') ?: 5000);
	$trending_loop_enable = hp_is_section_enabled('trending_loop_enable', true);
	$trending_pause_on_hover = hp_is_section_enabled('trending_pause_on_hover', true);
	$trending_nav_arrows = hp_is_section_enabled('trending_nav_arrows', true);
	$trending_pag_dots = hp_is_section_enabled('trending_pag_dots', true);
	$trending_center_slides = hp_is_section_enabled('trending_center_slides', false);

	// Responsive Settings
	$trending_slides_desktop = intval(get_field('trending_slides_desktop') ?: 4);
	$trending_slides_laptop = intval(get_field('trending_slides_laptop') ?: 4);
	$trending_slides_tablet = intval(get_field('trending_slides_tablet') ?: 2);
	$trending_slides_mobile = intval(get_field('trending_slides_mobile') ?: 1);
	$trending_slides_space = intval(get_field('trending_slides_space') ?: 32);

	// Query latest properties
	$trending_args = array(
		'post_type'      => 'property',
		'posts_per_page' => $trending_posts_count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC'
	);
	$trending_query = new WP_Query($trending_args);
?>
<!-- Most Trending Projects — Scoped Card Redesign CSS -->
<style id="trending-projects-card-styles">
/* ================================================================
   TRENDING PROJECTS SECTION — scoped card redesign
   All selectors prefixed with .trending-projects-section
   Zero impact on any other section.
   ================================================================ */

/* ── Card Shell ── */
.trending-projects-section .featured-slide-card.property-card {
	border-radius: 16px !important;
	box-shadow: 0 4px 24px rgba(0,0,0,0.08) !important;
	border: 1px solid #f0f0f0 !important;
	overflow: hidden !important;
	background: #ffffff !important;
	display: flex !important;
	flex-direction: column !important;
	height: 100% !important;
	transition: transform 0.32s ease, box-shadow 0.32s ease !important;
}
.trending-projects-section .featured-slide-card.property-card:hover {
	transform: translateY(-6px) !important;
	box-shadow: 0 16px 48px rgba(0,0,0,0.13) !important;
}

/* ── Image Block ── */
.trending-projects-section .featured-slide-card .property-image-wrapper {
	position: relative !important;
	width: 100% !important;
	aspect-ratio: 4 / 3 !important;
	height: auto !important;
	overflow: hidden !important;
	border-radius: 0 !important;
	flex-shrink: 0 !important;
	background: #e8e8e8 !important;
}
.trending-projects-section .featured-slide-card .property-image {
	width: 100% !important;
	height: 100% !important;
	object-fit: cover !important;
	transition: transform 0.55s cubic-bezier(.25,.8,.25,1) !important;
	display: block !important;
}
.trending-projects-section .featured-slide-card.property-card:hover .property-image {
	transform: scale(1.07) !important;
}
/* Subtle bottom-fade gradient */
.trending-projects-section .featured-slide-card .property-image-gradient {
	position: absolute !important;
	inset: auto 0 0 0 !important;
	height: 40% !important;
	background: linear-gradient(to top, rgba(0,0,0,0.28), transparent) !important;
	z-index: 2 !important;
	pointer-events: none !important;
}

/* ── FOR SALE / FOR RENT Badge (pill) ── */
.trending-projects-section .featured-slide-card .property-badge-wrapper {
	position: absolute !important;
	top: 14px !important;
	left: 14px !important;
	z-index: 5 !important;
}
.trending-projects-section .featured-slide-card .property-badge-type {
	background: var(--primary-color) !important;
	color: #ffffff !important;
	padding: 6px 12px !important;
	font-size: 11px !important;
	font-weight: 700 !important;
	text-transform: uppercase !important;
	border-radius: 4px !important;
	letter-spacing: 0.5px !important;
	box-shadow: none !important;
}
.trending-projects-section .featured-slide-card .property-badge-type.badge-rent {
	background: #1C1D21 !important;
}


/* ── Link / Quick-View Icon (bottom-right of image) ── */
.trending-projects-section .featured-slide-card .property-link-icon {
	position: absolute !important;
	bottom: 14px !important;
	right: 14px !important;
	z-index: 5 !important;
	width: 36px !important;
	height: 36px !important;
	background: #ffffff !important;
	color: #1c1d21 !important;
	border-radius: 8px !important;
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	box-shadow: 0 2px 10px rgba(0,0,0,0.14) !important;
	transition: background 0.2s, color 0.2s !important;
	text-decoration: none !important;
}
.trending-projects-section .featured-slide-card .property-link-icon:hover {
	background: #1c1d21 !important;
	color: #ffffff !important;
}
.trending-projects-section .featured-slide-card .property-link-icon svg {
	width: 16px !important;
	height: 16px !important;
}

/* ── Card Body ── */
.trending-projects-section .featured-slide-card .property-details {
	padding: 20px 20px 20px !important;
	display: flex !important;
	flex-direction: column !important;
	flex-grow: 1 !important;
}

/* ── Title ── */
.trending-projects-section .featured-slide-card .property-title {
	font-size: 16px !important;
	font-weight: 600 !important;
	line-height: 1.45 !important;
	margin-bottom: 6px !important;
	height: auto !important;
	min-height: auto !important;
	white-space: normal !important;
	display: -webkit-box !important;
	-webkit-line-clamp: 2 !important;
	-webkit-box-orient: vertical !important;
	overflow: hidden !important;
	text-overflow: ellipsis !important;
	color: #1c1d21 !important;
}
.trending-projects-section .featured-slide-card .property-title a {
	color: #1c1d21 !important;
	text-decoration: none !important;
}
.trending-projects-section .featured-slide-card .property-title a:hover {
	color: #c5a880 !important;
}

/* ── Price ── */
.trending-projects-section .featured-slide-card .property-price {
	font-size: 17px !important;
	font-weight: 700 !important;
	color: #c5a880 !important;
	margin-top: 0 !important;
	margin-bottom: 8px !important;
}

/* ── Location ── */
.trending-projects-section .featured-slide-card .property-location {
	font-size: 12px !important;
	color: #62697a !important;
	gap: 5px !important;
	margin-bottom: 16px !important;
	display: flex !important;
	align-items: center !important;
	white-space: nowrap !important;
	overflow: hidden !important;
	text-overflow: ellipsis !important;
}
.trending-projects-section .featured-slide-card .property-location i {
	color: #c5a880 !important;
	font-size: 12px !important;
	flex-shrink: 0 !important;
}

/* ── Amenities Row (Beds / Baths / Sq.Ft) ── */
.trending-projects-section .featured-slide-card .property-amenities {
	display: grid !important;
	grid-template-columns: repeat(3, 1fr) !important;
	gap: 8px !important;
	border-top: 1px solid #f0f0f0 !important;
	padding-top: 14px !important;
	margin-top: auto !important;
	margin-bottom: 16px !important;
}
.trending-projects-section .featured-slide-card .property-amenity {
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
.trending-projects-section .featured-slide-card .property-amenity i {
	color: #c5a880 !important;
	font-size: 13px !important;
}
.trending-projects-section .featured-slide-card .property-amenity strong {
	color: #1c1d21 !important;
	font-size: 12px !important;
}

/* ── View Details Button — outlined gold, full-width ── */
/* Target both classes to win specificity over global .header-cta */
.trending-projects-section .featured-slide-card .featured-card-footer {
	display: block !important;
	margin-top: auto !important;
	padding-top: 0 !important;
}
.trending-projects-section .featured-slide-card a.header-cta.view-details-btn {
	display: inline-block !important;
	width: auto !important;
	min-width: 120px !important;
	text-align: center !important;
	padding: 10px 16px !important;
	font-size: 13px !important;
	font-weight: 600 !important;
	color: #c5a880 !important;
	background: transparent !important;
	border: 1.5px solid #c5a880 !important;
	border-radius: 8px !important;
	text-decoration: none !important;
	letter-spacing: 0.4px !important;
	transition: background 0.22s ease, color 0.22s ease !important;
	box-sizing: border-box !important;
	line-height: 1.5 !important;
	cursor: pointer !important;
	box-shadow: none !important;
}
.trending-projects-section .featured-slide-card a.header-cta.view-details-btn:hover {
	background: #c5a880 !important;
	color: #ffffff !important;
	border-color: #c5a880 !important;
	box-shadow: 0 4px 12px rgba(197,168,128,0.3) !important;
}

/* ── Responsive tweaks ── */
@media (max-width: 768px) {
	.trending-projects-section .featured-slide-card .property-details {
		padding: 16px !important;
	}
	.trending-projects-section .featured-slide-card .property-title {
		font-size: 15px !important;
	}
}
</style>

<section class="hp-section trending-projects-section hp-reveal" id="properties" style="<?php echo esc_attr($trending_style); ?>">
	<div class="trending-projects-container">
		<div class="trending-projects-header">
			<h2 class="trending-projects-title">
				<?php echo str_ireplace('Projects', '<span class="trending-projects-title-accent">Projects</span>', esc_html($trending_title)); ?>
			</h2>
			<?php if ( $trending_subtitle ) : ?>
				<p class="trending-projects-description"><?php echo esc_html($trending_subtitle); ?></p>
			<?php endif; ?>
		</div>

		<!-- Filter Tabs: All, For Rent, For Sale -->
		<?php if ( $trending_filter_enable ) : ?>
			<div class="trending-projects-tabs">
				<button class="trending-projects-tab-btn active" data-filter="all">All</button>
				<button class="trending-projects-tab-btn" data-filter="rent">For Rent</button>
				<button class="trending-projects-tab-btn" data-filter="sale">For Sale</button>
			</div>
		<?php endif; ?>

		<?php if ( $trending_slider_enable ) : ?>
			<!-- Swiper Slider Wrapper -->
			<div class="trending-projects-slider-wrapper">
				<div class="swiper trending-projects-swiper"
					 data-autoplay="<?php echo $trending_autoplay_enable ? 'true' : 'false'; ?>"
					 data-speed="<?php echo esc_attr($trending_autoplay_speed); ?>"
					 data-loop="<?php echo $trending_loop_enable ? 'true' : 'false'; ?>"
					 data-hover="<?php echo $trending_pause_on_hover ? 'true' : 'false'; ?>"
					 data-center="<?php echo $trending_center_slides ? 'true' : 'false'; ?>"
					 data-desktop="<?php echo esc_attr($trending_slides_desktop); ?>"
					 data-laptop="<?php echo esc_attr($trending_slides_laptop); ?>"
					 data-tablet="<?php echo esc_attr($trending_slides_tablet); ?>"
					 data-mobile="<?php echo esc_attr($trending_slides_mobile); ?>"
					 data-space="<?php echo esc_attr($trending_slides_space); ?>">
					
					<div class="swiper-wrapper" id="trending-projects-slider-track">
						<?php
						if ( $trending_query->have_posts() ) {
							while ( $trending_query->have_posts() ) {
								$trending_query->the_post();
								casaview_render_featured_card( get_the_ID() );
							}
							wp_reset_postdata();
						} else {
							echo '<div class="no-properties-found swiper-slide" style="text-align: center; padding: 40px; background: #ffffff; border-radius: 12px; width: 100%;"><h3>No Properties Found</h3><p>We couldn\'t find any properties matching this selection.</p></div>';
						}
						?>
					</div>
				</div>

				<!-- Navigation Arrows -->
				<?php if ( $trending_nav_arrows ) : ?>
					<button class="trending-projects-arrow arrow-left" id="trending-projects-prev-btn" aria-label="Previous Slide">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
					</button>
					<button class="trending-projects-arrow arrow-right" id="trending-projects-next-btn" aria-label="Next Slide">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
					</button>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<!-- Static Grid Fallback -->
			<div class="trending-projects-grid" id="trending-projects-grid-container">
				<?php
				if ( $trending_query->have_posts() ) {
					while ( $trending_query->have_posts() ) {
						$trending_query->the_post();
						casaview_render_featured_card( get_the_ID() );
					}
					wp_reset_postdata();
				} else {
					echo '<div class="no-properties-found" style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #ffffff; border-radius: 12px;"><h3>No Properties Found</h3><p>We couldn\'t find any properties matching this selection.</p></div>';
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>


<!-- 2.5 Our Services Section -->
<?php
$services_enable = get_field('our_services_enable') !== null ? get_field('our_services_enable') : true;

if ( $services_enable ) :
	$services_title = get_field('our_services_title') ?: 'Our Services';
	$services_subtitle = get_field('our_services_subtitle') ?: 'Discover our range of bespoke property services.';
	
	$services_top_padding = get_field('our_services_top_padding');
	$services_bottom_padding = get_field('our_services_bottom_padding');
	if ( $services_top_padding === null || $services_top_padding === '' ) {
		$services_top_padding = 100;
	}
	if ( $services_bottom_padding === null || $services_bottom_padding === '' ) {
		$services_bottom_padding = 100;
	}

	$services_bg_color = get_field('our_services_bg_color') ?: '#F7F6F2';
	$services_bg_image = get_field('our_services_bg_image');

	$services_style = "padding-top: {$services_top_padding}px; padding-bottom: {$services_bottom_padding}px; background-color: {$services_bg_color}; width: 100%;";
	if ( $services_bg_image ) {
		$services_style .= " background-image: url('" . esc_url($services_bg_image) . "'); background-size: cover; background-position: center; background-repeat: no-repeat;";
	}

	$services_list = get_field('our_services_repeater');
	if ( empty( $services_list ) ) {
		$services_list = array(
			array(
				'our_service_name'        => 'Property Valuation',
				'our_service_icon'        => '',
				'our_service_title'       => 'Accurate & Market-Driven Valuation',
				'our_service_description' => '<p>Get precise valuation reports based on real-time market trends, transaction history, and detailed comparative analysis for your luxury properties.</p>',
				'our_service_image'       => site_url('/wp-content/uploads/2026/06/gemini-generated-image-yhd0ixyhd0ixyhd0-1.png'),
				'our_service_btn_text'    => 'Book Valuation',
				'our_service_btn_url'     => '#',
			),
			array(
				'our_service_name'        => 'Exclusive Brokerage',
				'our_service_icon'        => '',
				'our_service_title'       => 'Bespoke Property Marketing',
				'our_service_description' => '<p>List your signature villas or penthouses exclusively with us to reach an international network of qualified high-net-worth investors and buyers.</p>',
				'our_service_image'       => site_url('/wp-content/uploads/2026/06/gemini-generated-image-uwpuk4uwpuk4uwpu.png'),
				'our_service_btn_text'    => 'List Your Property',
				'our_service_btn_url'     => '#',
			),
			array(
				'our_service_name'        => 'Legal Consulting',
				'our_service_icon'        => '',
				'our_service_title'       => 'Safe & Compliant Transactions',
				'our_service_description' => '<p>Navigate local property laws, title transfers, and contract drafting with our dedicated team of legal and regulatory compliance experts.</p>',
				'our_service_image'       => site_url('/wp-content/uploads/2026/06/gemini-generated-image-ayb6k8ayb6k8ayb6.png'),
				'our_service_btn_text'    => 'Consult Expert',
				'our_service_btn_url'     => '#',
			),
		);
	}
?>
<section class="hp-section our-services-section hp-reveal" style="<?php echo esc_attr($services_style); ?>">
	<div class="container">
		<!-- Section Header -->
		<div class="hp-section__header" style="max-width: 800px; margin-bottom: 50px;">
			<h2 class="hp-section__title" style="font-size: 56px; font-weight: 400; line-height: 1.1;">
				<?php 
				// "Services" word accent color: #C6A15B
				$title_html = str_ireplace('Services', '<span style="color: #C6A15B;">Services</span>', esc_html($services_title));
				echo $title_html;
				?>
			</h2>
			<?php if ( $services_subtitle ) : ?>
				<p class="hp-section__subtitle" style="font-size: 18px; line-height: 1.8; color: #555; margin-top: 15px; max-width: 800px;">
					<?php echo esc_html($services_subtitle); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty($services_list) ) : ?>
			<div class="our-services-container">
				<!-- Column 1: Vertical Tabs -->
				<div class="our-services-tabs">
					<?php 
					foreach ( $services_list as $index => $item ) :
						$s_name = $item['our_service_name'] ?? '';
						$s_icon = $item['our_service_icon'] ?? '';
						$active_class = ($index === 0) ? ' active' : '';
						?>
						<div class="our-services-tab<?php echo $active_class; ?>" data-index="<?php echo $index; ?>">
							<?php if ( $s_icon ) : ?>
								<img src="<?php echo esc_url($s_icon); ?>" alt="<?php echo esc_attr($s_name); ?>" class="our-services-tab-icon" />
							<?php else : ?>
								<?php 
								// Premium golden vector SVG fallback icons
								switch ($index) {
									case 0:
										echo '<svg class="our-services-tab-icon-svg" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C6A15B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>';
										break;
									case 1:
										echo '<svg class="our-services-tab-icon-svg" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C6A15B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';
										break;
									case 2:
										echo '<svg class="our-services-tab-icon-svg" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C6A15B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
										break;
									default:
										echo '<svg class="our-services-tab-icon-svg" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C6A15B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>';
										break;
								}
								?>
							<?php endif; ?>
							<span class="our-services-tab-name"><?php echo esc_html($s_name); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Column 2: Center Featured Image -->
				<div class="our-services-image-wrapper">
					<?php 
					foreach ( $services_list as $index => $item ) :
						$s_image = $item['our_service_image'] ?? '';
						$active_class = ($index === 0) ? ' active' : '';
						?>
						<img src="<?php echo esc_url($s_image); ?>" 
							 alt="Service Image" 
							 class="our-services-image<?php echo $active_class; ?>" 
							 data-index="<?php echo $index; ?>" />
					<?php endforeach; ?>
				</div>

				<!-- Column 3: Right Details Content -->
				<div class="our-services-content-wrapper">
					<?php 
					foreach ( $services_list as $index => $item ) :
						$s_title = $item['our_service_title'] ?? '';
						$s_desc = $item['our_service_description'] ?? '';
						$s_btn_text = $item['our_service_btn_text'] ?? '';
						$s_btn_url_field = $item['our_service_btn_url'] ?? '';

						$s_btn_url = '';
						$s_btn_target = '_self';
						if ( is_array( $s_btn_url_field ) ) {
							$s_btn_url = $s_btn_url_field['url'] ?? '';
							$s_btn_target = $s_btn_url_field['target'] ?? '_self';
						} elseif ( is_string( $s_btn_url_field ) ) {
							$s_btn_url = $s_btn_url_field;
						}
						?>
						<div class="our-services-content<?php echo ($index === 0) ? ' active' : ''; ?>" 
							 data-index="<?php echo $index; ?>">
							<h3 class="our-services-content-title"><?php echo esc_html($s_title); ?></h3>
							<div class="our-services-content-description"><?php echo wp_kses_post($s_desc); ?></div>
							<?php if ( $s_btn_text && $s_btn_url ) : ?>
								<a href="<?php echo esc_url($s_btn_url); ?>" 
								   target="<?php echo esc_attr($s_btn_target); ?>" 
								   class="our-services-button">
									<?php echo esc_html($s_btn_text); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>


<!-- 3. Featured Properties Section -->
<?php
$featured_enable = hp_is_section_enabled('featured_enable');
if ( $featured_enable ) :
	$featured_title = get_field('featured_title') ?: 'Featured Properties';
	$featured_subtitle = get_field('featured_subtitle') ?: 'Explore our elite handpicked properties in key communities.';
	$featured_query_type = get_field('featured_query_type') ?: 'latest';
	$featured_posts_count = get_field('featured_posts_count') ?: 6;
	$featured_manual_properties = get_field('featured_manual_properties');
	$featured_view_all_text = get_field('featured_view_all_text') ?: 'View All Properties';
	$featured_view_all_link = get_field('featured_view_all_link') ?: '/properties/';

	$featured_style = hp_get_section_style('featured', 100, 100, '#ffffff');

	// Query build (Show Latest 5 Properties Only)
	$args = [
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	$featured_query = new WP_Query($args);
?>
<!-- Featured Properties — Scoped Widescreen Grid CSS -->
<style id="featured-properties-grid-styles">
@media (min-width: 1600px) {
	.hp-featured #homepage-properties-grid.properties-grid {
		grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
	}
}
/* Style the For Rent badge to have dark background and white text */
.hp-featured .property-badge-type.badge-rent {
	background-color: #1C1D21 !important;
	color: #ffffff !important;
}
</style>
<section class="hp-section hp-featured hp-reveal" style="<?php echo esc_attr($featured_style); ?>">
	<div class="container">
		<div class="hp-section__header">
			<h2 class="hp-section__title"><?php echo esc_html($featured_title); ?></h2>
			<p class="hp-section__subtitle"><?php echo esc_html($featured_subtitle); ?></p>
		</div>

		<!-- AJAX Search Binding Container -->
		<div id="homepage-properties-grid" class="properties-grid">
			<?php 
			if ( $featured_query->have_posts() ) {
				while ( $featured_query->have_posts() ) {
					$featured_query->the_post();
					casaview_render_featured_card( get_the_ID() );
				}
				wp_reset_postdata();
			} else {
				?>
				<div class="no-properties-found" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
					<h3>No Properties Found</h3>
					<p>We couldn't find any properties matching this query.</p>
				</div>
				<?php
			}
			?>
		</div>
		<div class="hp-featured__footer" style="text-align: center; margin-top: 50px;">
			<?php
			$archive_link = get_post_type_archive_link('property');
			$view_all_url = ($archive_link && !is_wp_error($archive_link)) ? $archive_link : site_url('/properties/');
			?>
			<a href="<?php echo esc_url($view_all_url); ?>" class="featured-properties-view-all">
				View All Properties â†’
			</a>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- 4. Explore Districts Section -->
<?php
$districts_enable = hp_is_section_enabled('districts_enable');
if ( $districts_enable ) :
	$districts_marquee = get_field('districts_marquee') ?: 'SIGNATURE VILLA â€¢ LUXURY APARTMENT â€¢ PENTHOUSE â€¢ SIGNATURE VILLA';
	$districts_title = get_field('districts_title') ?: 'Explore Districts';
	$districts_subtitle = get_field('districts_subtitle') ?: 'Find local communities with signature properties carefully selected.';
	$districts_list = get_field('districts_list');

	$districts_style = hp_get_section_style('districts', 100, 100, '#0f172a');

	// Build the items list
	$district_items = array();
	if ( ! empty($districts_list) ) {
		foreach ( $districts_list as $item ) {
			$name = $item['d_name'];
			$image = $item['d_image'];
			
			if ( empty($image) ) {
				$term = get_term_by('name', $name, 'property_location');
				if ( $term ) {
					$term_image = get_field('district_featured_image', 'property_location_' . $term->term_id);
					if ( ! $term_image ) {
						$term_image = get_field('district_featured_image', 'term_' . $term->term_id);
					}
					if ( ! $term_image ) {
						$term_image = get_field('district_featured_image', $term);
					}
					if ( $term_image ) {
						$image = $term_image;
					}
				}
			}
			if ( empty($image) ) {
				$image = get_template_directory_uri() . '/assets/images/district-default.jpg';
			}
			
			if ( isset($item['d_count']) && $item['d_count'] !== '' && $item['d_count'] !== null ) {
				$count = intval($item['d_count']);
			} else {
				$count = hp_get_district_property_count($name);
			}
			
			$btn_text = $item['d_btn_text'] ?: 'Explore';
			$btn_link = $item['d_btn_link'] ?: home_url('/properties/?district=' . urlencode($name));
			
			$district_items[] = array(
				'name' => $name,
				'image' => $image,
				'count' => $count,
				'btn_text' => $btn_text,
				'btn_link' => $btn_link
			);
		}
	} else {
		// Fallback to distinct query
		global $wpdb;
		$unique_districts = $wpdb->get_col("
			SELECT DISTINCT pm.meta_value 
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
			WHERE p.post_type = 'property'
			  AND p.post_status = 'publish'
			  AND pm.meta_key = 'district'
			  AND pm.meta_value IS NOT NULL
			  AND pm.meta_value != ''
			ORDER BY pm.meta_value ASC
		");
		$unique_districts = array_values(array_filter(array_map('trim', $unique_districts)));
		
		foreach ( $unique_districts as $d_name ) {
			$count = hp_get_district_property_count($d_name);
			$image = get_template_directory_uri() . '/assets/images/district-default.jpg';
			
			$term = get_term_by('name', $d_name, 'property_location');
			if ( $term ) {
				$term_image = get_field('district_featured_image', 'property_location_' . $term->term_id);
				if ( ! $term_image ) {
					$term_image = get_field('district_featured_image', 'term_' . $term->term_id);
				}
				if ( ! $term_image ) {
					$term_image = get_field('district_featured_image', $term);
				}
				if ( $term_image ) {
					$image = $term_image;
				}
			}
			
			$district_items[] = array(
				'name' => $d_name,
				'image' => $image,
				'count' => $count,
				'btn_text' => 'Explore',
				'btn_link' => home_url('/properties/?district=' . urlencode($d_name))
			);
		}
	}
?>
<section class="hp-section hp-districts districts-marquee-section hp-reveal" style="<?php echo esc_attr($districts_style); ?> color: #ffffff;" id="districts">
	<?php if ( $districts_marquee ) : ?>
		<div class="hp-districts__marquee-container">
			<span class="hp-districts__marquee-text"><?php echo esc_html($districts_marquee); ?></span>
		</div>
	<?php endif; ?>

	<div class="container" style="padding-top: 50px;">
		<div class="hp-section__header">
			<h2 class="hp-section__title" style="color: #ffffff;"><?php echo esc_html($districts_title); ?></h2>
			<p class="hp-section__subtitle" style="color: rgba(255,255,255,0.7);"><?php echo esc_html($districts_subtitle); ?></p>
		</div>
	</div>

	<?php
	$marquee_enable = hp_is_section_enabled('districts_marquee_enable', true);
	$marquee_speed = get_field('districts_marquee_speed') ?: 35;
	$marquee_pause = hp_is_section_enabled('districts_marquee_pause_hover', true);
	$marquee_gap = get_field('districts_marquee_item_gap') ?: 60;
	$show_count = hp_is_section_enabled('districts_show_property_count', true);
	?>

	<?php if ( $marquee_enable ) : ?>
		<div class="districts-marquee-wrapper" style="--item-gap: <?php echo esc_attr($marquee_gap); ?>px; --marquee-speed: <?php echo esc_attr($marquee_speed); ?>s;">
			<div class="districts-marquee-track <?php echo $marquee_pause ? 'pause-on-hover' : ''; ?>">
				<!-- Loop 1 -->
				<div class="districts-marquee-group">
					<?php foreach ( $district_items as $item ) : ?>
						<a href="<?php echo esc_url($item['btn_link']); ?>" class="district-marquee-item">
							<div class="district-marquee-image-circle">
								<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>" loading="lazy">
							</div>
							<span class="district-marquee-name"><?php echo esc_html($item['name']); ?></span>
							<?php if ( $show_count ) : ?>
								<span class="district-marquee-count">(<?php echo esc_html($item['count']); ?>)</span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
				<!-- Loop 2 -->
				<div class="districts-marquee-group">
					<?php foreach ( $district_items as $item ) : ?>
						<a href="<?php echo esc_url($item['btn_link']); ?>" class="district-marquee-item">
							<div class="district-marquee-image-circle">
								<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>" loading="lazy">
							</div>
							<span class="district-marquee-name"><?php echo esc_html($item['name']); ?></span>
							<?php if ( $show_count ) : ?>
								<span class="district-marquee-count">(<?php echo esc_html($item['count']); ?>)</span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="container">
			<div class="districts-slider-outer" style="position: relative; width: 100%;">
				<div class="swiper districts-swiper" style="overflow: hidden; width: 100%; padding-bottom: 40px;">
					<div class="swiper-wrapper">
						<?php foreach ( $district_items as $item ) : ?>
							<div class="swiper-slide">
								<a href="<?php echo esc_url($item['btn_link']); ?>" class="district-card">
									<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>" class="district-card__image" loading="lazy">
									<div class="district-card__overlay"></div>
									<div class="district-card__info">
										<h3 class="district-card__title"><?php echo esc_html($item['name']); ?></h3>
										<p class="district-card__count"><?php echo esc_html($item['count']); ?> Properties</p>
										<span class="district-card__btn">
											<?php echo esc_html($item['btn_text']); ?>
											<i class="fa-solid fa-arrow-right-long"></i>
										</span>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="swiper-pagination" style="bottom: 0;"></div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
<?php endif; ?>


<!-- 5. Our Services Section -->
<?php
$services_enable = hp_is_section_enabled('services_enable');
if ( $services_enable ) :
	$services_title = get_field('services_title') ?: 'Our Services';
	$services_subtitle = get_field('services_subtitle') ?: 'Provide client-focused property solutions across sectors.';
	$services_list = get_field('services_list');

	$services_style = hp_get_section_style('services', 100, 100, '#ffffff');

	$services_items = array();
	if ( ! empty($services_list) ) {
		$services_items = $services_list;
	} else {
		$services_items = array(
			array(
				's_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=80',
				's_icon' => 'fa-solid fa-house-chimney',
				's_title' => 'Property Brokerage',
				's_description' => 'Helping clients purchase, lease, or sell residential and commercial assets in prestigious locations.',
				's_btn_text' => 'Learn More',
				's_btn_link' => '#contact'
			),
			array(
				's_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80',
				's_icon' => 'fa-solid fa-chart-line',
				's_title' => 'Investment Advisory',
				's_description' => 'Providing custom real estate portfolio growth strategies, ROI assessment, and off-market options.',
				's_btn_text' => 'Learn More',
				's_btn_link' => '#contact'
			),
			array(
				's_image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=600&q=80',
				's_icon' => 'fa-solid fa-scale-balanced',
				's_title' => 'Valuation & Legal Support',
				's_description' => 'Assuring title verification, transaction security compliance, and RERA property valuation reviews.',
				's_btn_text' => 'Learn More',
				's_btn_link' => '#contact'
			)
		);
	}
?>
<section class="hp-section hp-services hp-reveal" style="<?php echo esc_attr($services_style); ?>">
	<div class="container">
		<div class="hp-section__header">
			<h2 class="hp-section__title"><?php echo esc_html($services_title); ?></h2>
			<p class="hp-section__subtitle"><?php echo esc_html($services_subtitle); ?></p>
		</div>

		<div class="hp-services__grid">
			<?php foreach ( $services_items as $item ) : ?>
				<div class="service-card">
					<div class="service-card__image-wrapper">
						<img src="<?php echo esc_url($item['s_image']); ?>" alt="<?php echo esc_attr($item['s_title']); ?>" class="service-card__image" loading="lazy">
						<?php if ( ! empty($item['s_icon']) ) : ?>
							<div class="service-card__icon-badge">
								<i class="<?php echo esc_attr($item['s_icon']); ?>"></i>
							</div>
						<?php endif; ?>
					</div>
					<div class="service-card__body">
						<h3 class="service-card__title"><?php echo esc_html($item['s_title']); ?></h3>
						<p class="service-card__description"><?php echo esc_html($item['s_description']); ?></p>
						<?php if ( ! empty($item['s_btn_text']) ) : ?>
							<a href="<?php echo esc_url($item['s_btn_link'] ?: '#'); ?>" class="service-card__btn"><?php echo esc_html($item['s_btn_text']); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- 6. Apartment Types Section -->
<?php
$apartments_enable = hp_is_section_enabled('apartments_enable');
if ( $apartments_enable ) :
	$apartments_title = get_field('apartments_title') ?: 'Apartment Types';
	$apartments_subtitle = get_field('apartments_subtitle') ?: 'Browse signature apartments by types and luxury configuration.';
	$apartments_list = get_field('apartments_list');

	$apartments_style = hp_get_section_style('apartments', 100, 100, '#fafafb');

	$apartments_items = array();
	if ( ! empty($apartments_list) ) {
		$apartments_items = $apartments_list;
	} else {
		$apartments_items = array(
			array(
				'apt_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80',
				'apt_name' => 'Penthouse Suites',
				'apt_description' => 'Top-floor residences offering panoramic city skylines, private pools, and bespoke layout designs.',
				'apt_link' => '#contact'
			),
			array(
				'apt_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80',
				'apt_name' => 'Duplex Apartments',
				'apt_description' => 'Spacious multi-level homes combining modern floor-to-ceiling glass layout with premium privacy.',
				'apt_link' => '#contact'
			),
			array(
				'apt_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=600&q=80',
				'apt_name' => 'Garden Residences',
				'apt_description' => 'Ground level luxury spaces featuring private landscaped backyards and dedicated entry gateways.',
				'apt_link' => '#contact'
			),
			array(
				'apt_image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80',
				'apt_name' => 'Sleek Studios',
				'apt_description' => 'Premium compact layouts ideal for executives, situated in the heart of elite business district communities.',
				'apt_link' => '#contact'
			)
		);
	}
?>
<section class="hp-section hp-apartments hp-reveal" style="<?php echo esc_attr($apartments_style); ?>">
	<div class="container">
		<div class="hp-section__header">
			<h2 class="hp-section__title"><?php echo esc_html($apartments_title); ?></h2>
			<p class="hp-section__subtitle"><?php echo esc_html($apartments_subtitle); ?></p>
		</div>

		<div class="hp-apartments__grid">
			<?php foreach ( $apartments_items as $item ) : ?>
				<div class="apartment-card">
					<img src="<?php echo esc_url($item['apt_image']); ?>" alt="<?php echo esc_attr($item['apt_name']); ?>" class="apartment-card__image" loading="lazy">
					<div class="apartment-card__overlay"></div>
					<div class="apartment-card__info">
						<h3 class="apartment-card__title"><?php echo esc_html($item['apt_name']); ?></h3>
						<p class="apartment-card__description"><?php echo esc_html($item['apt_description']); ?></p>
					</div>
					<?php if ( ! empty($item['apt_link']) ) : ?>
						<a href="<?php echo esc_url($item['apt_link']); ?>" class="apartment-card__link"></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- 7. Meet Our Agents Section -->
<?php
$agents_enable = hp_is_section_enabled('agents_enable');
if ( $agents_enable ) :
	$agents_title = get_field('agents_title') ?: 'Meet Our Agents';
	$agents_subtitle = get_field('agents_subtitle') ?: 'Connect with our elite team of real estate advisors.';
	$agents_selection = get_field('agents_selection');

	$agents_style = hp_get_section_style('agents', 100, 100, '#ffffff');

	// Prepare Query
	$agent_posts = array();
	if ( ! empty($agents_selection) ) {
		$agent_posts = get_posts( array(
			'post_type' => 'agent',
			'post_status' => 'publish',
			'post__in' => $agents_selection,
			'posts_per_page' => -1,
			'orderby' => 'post__in'
		) );
	} else {
		$agent_posts = get_posts( array(
			'post_type' => 'agent',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
	}

	$agents_list_display = array();
	if ( ! empty($agent_posts) ) {
		foreach ( $agent_posts as $agent ) {
			$agent_id = $agent->ID;
			$designation = get_field('designation', $agent_id) ?: 'Luxury Property Consultant';
			$phone = get_field('phone', $agent_id);
			$whatsapp = get_field('whatsapp', $agent_id);
			$email = get_field('email', $agent_id);
			$fb = get_field('facebook', $agent_id);
			$ig = get_field('instagram', $agent_id);
			$li = get_field('linkedin', $agent_id);
			
			$photo = get_the_post_thumbnail_url($agent_id, 'medium_large') ?: (get_post_meta($agent_id, '_mock_image_url', true) ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&h=500&q=80');

			$agents_list_display[] = array(
				'name' => $agent->post_title,
				'designation' => $designation,
				'photo' => $photo,
				'phone' => $phone,
				'whatsapp' => $whatsapp,
				'email' => $email,
				'fb' => $fb,
				'ig' => $ig,
				'li' => $li
			);
		}
	} else {
		// Mock agents fallback
		$agents_list_display = array(
			array(
				'name' => 'Alexander Vlasov',
				'designation' => 'Principal Partner',
				'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&h=500&q=80',
				'phone' => '+971 4 123 4567',
				'whatsapp' => '+971501234567',
				'email' => 'alexander@prworksrealestate.ae',
				'fb' => '#', 'ig' => '#', 'li' => '#'
			),
			array(
				'name' => 'Elena Petrova',
				'designation' => 'Luxury Sales Director',
				'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&h=500&q=80',
				'phone' => '+971 4 123 4568',
				'whatsapp' => '+971501234568',
				'email' => 'elena@prworksrealestate.ae',
				'fb' => '#', 'ig' => '#', 'li' => '#'
			),
			array(
				'name' => 'Marcus Aurelius',
				'designation' => 'Off-Plan Specialist',
				'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&h=500&q=80',
				'phone' => '+971 4 123 4569',
				'whatsapp' => '+971501234569',
				'email' => 'marcus@prworksrealestate.ae',
				'fb' => '#', 'ig' => '#', 'li' => '#'
			),
			array(
				'name' => 'Sarah Connor',
				'designation' => 'Beachfront Advisor',
				'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&h=500&q=80',
				'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&h=500&q=80',
				'phone' => '+971 4 123 4570',
				'whatsapp' => '+971501234570',
				'email' => 'sarah@prworksrealestate.ae',
				'fb' => '#', 'ig' => '#', 'li' => '#'
			)
		);
	}
?>
<section class="hp-section hp-agents hp-reveal" style="<?php echo esc_attr($agents_style); ?>">
	<div class="container">
		<div class="hp-section__header">
			<h2 class="hp-section__title"><?php echo esc_html($agents_title); ?></h2>
			<p class="hp-section__subtitle"><?php echo esc_html($agents_subtitle); ?></p>
		</div>

		<div class="hp-agents__grid">
			<?php foreach ( $agents_list_display as $item ) : ?>
				<div class="agent-card">
					<div class="agent-card__image-wrapper">
						<img src="<?php echo esc_url($item['photo']); ?>" alt="<?php echo esc_attr($item['name']); ?>" class="agent-card__image" loading="lazy">
						<div class="agent-card__socials-overlay">
							<?php if ( ! empty($item['phone']) ) : ?>
								<a href="tel:<?php echo esc_attr($item['phone']); ?>" class="agent-card__social-icon" title="Call"><i class="fa-solid fa-phone"></i></a>
							<?php endif; ?>
							<?php if ( ! empty($item['whatsapp']) ) : ?>
								<a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $item['whatsapp'])); ?>" class="agent-card__social-icon" target="_blank" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
							<?php endif; ?>
							<?php if ( ! empty($item['email']) ) : ?>
								<a href="mailto:<?php echo esc_attr($item['email']); ?>" class="agent-card__social-icon" title="Email"><i class="fa-solid fa-envelope"></i></a>
							<?php endif; ?>
							<?php if ( ! empty($item['li']) ) : ?>
								<a href="<?php echo esc_url($item['li']); ?>" class="agent-card__social-icon" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="agent-card__body">
						<h3 class="agent-card__name"><?php echo esc_html($item['name']); ?></h3>
						<p class="agent-card__position"><?php echo esc_html($item['designation']); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- 8. Client Testimonials Section -->
<?php
$testimonials_enable = hp_is_section_enabled('testimonials_enable');
if ( $testimonials_enable ) :
	$testimonials_slider_enable = hp_is_section_enabled('testimonials_slider_enable');
	$testimonials_title = get_field('testimonials_title') ?: 'Client Testimonials';
	$testimonials_subtitle = get_field('testimonials_subtitle') ?: 'Read reviews from our premium lifestyle partners.';
	$testimonials_list = get_field('testimonials_list');

	$testimonials_style = hp_get_section_style('testimonials', 100, 100, '#fafafb');

	$testimonials_items = array();
	if ( ! empty($testimonials_list) ) {
		$testimonials_items = $testimonials_list;
	} else {
		$testimonials_items = array(
			array(
				't_photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80',
				't_name' => 'Jean-Luc Dubois',
				't_role' => 'CEO, Dubois Enterprises',
				't_rating' => '5',
				't_quote' => 'PRWorks Real Estate secured our beachfront estate completely off-market. Their discretion and professional standard are unmatched in the real estate brokerage market.'
			),
			array(
				't_photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&h=120&q=80',
				't_name' => 'Samantha Miller',
				't_role' => 'Managing Director, Capital Group',
				't_rating' => '5',
				't_quote' => 'Their expert insights and deep knowledge of high-end developments saved us weeks of research. They are truly the go-to agents for luxury investments.'
			)
		);
	}
?>
<section class="hp-section hp-testimonials hp-reveal" style="<?php echo esc_attr($testimonials_style); ?>">
	<div class="container">
		<div class="hp-section__header">
			<h2 class="hp-section__title"><?php echo esc_html($testimonials_title); ?></h2>
			<p class="hp-section__subtitle"><?php echo esc_html($testimonials_subtitle); ?></p>
		</div>

		<?php if ( $testimonials_slider_enable ) : ?>
			<div class="swiper testimonials-swiper" style="overflow: hidden; max-width: 900px; margin: 0 auto; padding-bottom: 50px;">
				<div class="swiper-wrapper">
					<?php foreach ( $testimonials_items as $item ) : ?>
						<div class="swiper-slide testimonial-slide">
							<div class="testimonial-slide__rating">
								<?php 
								$stars = intval($item['t_rating'] ?: 5);
								for ( $i = 0; $i < 5; $i++ ) {
									if ( $i < $stars ) {
										echo '<i class="fa-solid fa-star"></i>';
									} else {
										echo '<i class="fa-regular fa-star"></i>';
									}
								}
								?>
							</div>
							<blockquote class="testimonial-slide__quote">
								"<?php echo esc_html($item['t_quote']); ?>"
							</blockquote>
							<div class="testimonial-slide__client">
								<?php if ( ! empty($item['t_photo']) ) : ?>
									<img src="<?php echo esc_url($item['t_photo']); ?>" alt="<?php echo esc_attr($item['t_name']); ?>" class="testimonial-slide__avatar" loading="lazy">
								<?php endif; ?>
								<div class="testimonial-slide__info">
									<cite class="testimonial-slide__name"><?php echo esc_html($item['t_name']); ?></cite>
									<span class="testimonial-slide__role"><?php echo esc_html($item['t_role']); ?></span>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		<?php else : ?>
			<div class="testimonials-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
				<?php foreach ( $testimonials_items as $item ) : ?>
					<div class="testimonial-grid-card" style="background: #ffffff; border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(15,23,42,0.03); display: flex; flex-direction: column;">
						<div class="testimonial-slide__rating" style="justify-content: flex-start; color: #f59e0b; margin-bottom: 20px; display: flex; gap: 4px;">
							<?php 
							$stars = intval($item['t_rating'] ?: 5);
							for ( $i = 0; $i < 5; $i++ ) {
								if ( $i < $stars ) {
									echo '<i class="fa-solid fa-star"></i>';
								} else {
									echo '<i class="fa-regular fa-star"></i>';
								}
							}
							?>
						</div>
						<blockquote class="testimonial-quote" style="font-family: var(--font-en); font-size: 15px; line-height: 1.6; color: #62697a; font-style: italic; margin-bottom: 30px; flex-grow: 1;">
							"<?php echo esc_html($item['t_quote']); ?>"
						</blockquote>
						<div class="testimonial-slide__client" style="display: flex; align-items: center; gap: 16px; margin-top: auto;">
							<?php if ( ! empty($item['t_photo']) ) : ?>
								<img src="<?php echo esc_url($item['t_photo']); ?>" alt="<?php echo esc_attr($item['t_name']); ?>" class="testimonial-slide__avatar" loading="lazy" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
							<?php endif; ?>
							<div class="testimonial-slide__info" style="text-align: left;">
								<div class="testimonial-slide__name" style="font-family: var(--font-title); font-size: 15px; font-weight: 600; color: #0f172a;"><?php echo esc_html($item['t_name']); ?></div>
								<div class="testimonial-slide__role" style="font-family: var(--font-en); font-size: 12px; color: #62697a;"><?php echo esc_html($item['t_role']); ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>


<!-- 9. FAQ Section -->
<?php
$repeater_name = 'redesigned_faq_repeater';
if ( ! have_rows($repeater_name) && have_rows('faq') ) {
	$repeater_name = 'faq';
}

if ( have_rows($repeater_name) ) :
	$faq_title = get_field('redesigned_faq_title') ?: 'Frequently Asked Questions';
	$faq_subtitle = get_field('redesigned_faq_subtitle') ?: 'Everything you need to know about finding your dream home, renting with confidence, or seamlessly managing your property – all made simple, transparent, and hassle-free with Casa View\'s dedicated support and expert guidance.';
	$faq_subtitle = str_replace( 'Casa View', 'PRWORKS', $faq_subtitle );
	$faq_cta_text = get_field('redesigned_faq_cta_text') ?: 'Contact Us';
	$faq_cta_url = get_field('redesigned_faq_cta_url') ?: '/contact/';
	$faq_style = hp_get_section_style('faq', 100, 100, '#ffffff');
?>
<section class="hp-section hp-faq hp-reveal" style="<?php echo esc_attr($faq_style); ?>" id="faq">
	<div class="container hp-faq-container-new">
		<!-- Left Column -->
		<div class="hp-faq-left-col">
			<h2 class="hp-faq-title-new">
				Frequently <span class="accent-gold">Asked</span><br>
				<span class="accent-gold">Questions</span>
			</h2>
		</div>
		
		<!-- Right Column -->
		<div class="hp-faq-right-col">
			<p class="hp-faq-desc-new"><?php echo esc_html($faq_subtitle); ?></p>
			
			<div class="hp-faq-accordion-new">
				<?php 
// 				$index = 0;
				while ( have_rows($repeater_name) ) : the_row(); 
					$q = str_replace( 'Casa View', 'PRWORKS', get_sub_field('question') ?? '' );
					$a = str_replace( 'Casa View', 'PRWORKS', get_sub_field('answer') ?? '' );
					$is_first = ($index === 0);
					?>
					<div class="faq-item <?php echo $is_first ? 'faq-item--active' : ''; ?>">
						<div class="faq-item__header">
							<h3 class="faq-item__question">What services does PRWORKS Real Estate provide?</h3>
							<span class="faq-item__icon">
								<i class="fa-solid <?php echo $is_first ? 'fa-minus' : 'fa-plus'; ?>"></i>
							</span>
						</div>
						<div class="faq-item__body" <?php echo $is_first ? 'style="max-height: 2000px;"' : ''; ?>>
							<div class="faq-item__content">
								PRWORKS Real Estate offers property buying, selling, renting, and real estate consultation services, helping clients find the right residential and commercial properties.
							</div>
						</div>
					</div>
				<div class="faq-item <?php echo $is_second ? 'faq-item--active' : ''; ?>">
						<div class="faq-item__header">
							<h3 class="faq-item__question">How can I search for properties on PRWORKS?</h3>
							<span class="faq-item__icon">
								<i class="fa-solid <?php echo $is_first ? 'fa-minus' : 'fa-plus'; ?>"></i>
							</span>
						</div>
						<div class="faq-item__body" <?php echo $is_second ? 'style="max-height: 2000px;"' : ''; ?>>
							<div class="faq-item__content">
								You can browse available properties using our property listings and filters to find properties based on location, budget, and property type.
							</div>
						</div>
					</div>
					<div class="faq-item <?php echo $is_thrid ? 'faq-item--active' : ''; ?>">
						<div class="faq-item__header">
							<h3 class="faq-item__question">Can I list my property on PRWORKS Real Estate?</h3>
							<span class="faq-item__icon">
								<i class="fa-solid <?php echo $is_first ? 'fa-minus' : 'fa-plus'; ?>"></i>
							</span>
						</div>
						<div class="faq-item__body" <?php echo $is_third ? 'style="max-height: 2000px;"' : ''; ?>>
							<div class="faq-item__content">
								Yes. Property owners and agents can submit their properties through our website for review and publication.
							</div>
						</div>
					</div>
				<div class="faq-item <?php echo $is_fourth ? 'faq-item--active' : ''; ?>">
						<div class="faq-item__header">
							<h3 class="faq-item__question">Can I contact property owners or agents directly?</h3>
							<span class="faq-item__icon">
								<i class="fa-solid <?php echo $is_first ? 'fa-minus' : 'fa-plus'; ?>"></i>
							</span>
						</div>
						<div class="faq-item__body" <?php echo $is_fourth ? 'style="max-height: 2000px;"' : ''; ?>>
							<div class="faq-item__content">
								Yes. Each property listing includes enquiry options that allow you to connect directly with the concerned property owner or agent.
							</div>
						</div>
					</div>
<!-- 				<?php 
					$index++;
				endwhile; 
				?> -->
			</div>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- 10. Contact CTA Section -->
<?php
$contact_enable = hp_is_section_enabled('contact_enable');
if ( $contact_enable ) :
	$cta_bg_image = get_field('redesigned_cta_bg_image') ?: 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80';
	$cta_heading = get_field('redesigned_cta_heading') ?: 'Ready to Find Your Dream Home?';
	$cta_description = get_field('redesigned_cta_description') ?: 'Speak with one of our boutique real estate consultants today and discover luxury lifestyle listings.';
	$cta_btn_primary_text = get_field('redesigned_cta_btn_primary_text') ?: 'Contact Us';
	$cta_btn_primary_url = get_field('redesigned_cta_btn_primary_url') ?: '/contact/';
	$cta_btn_secondary_text = get_field('redesigned_cta_btn_secondary_text') ?: 'Browse Properties';
	$cta_btn_secondary_url = get_field('redesigned_cta_btn_secondary_url') ?: '/properties/';
	$cta_badge = get_field('redesigned_cta_badge');
	$cta_overlay = get_field('redesigned_cta_overlay') ?: 'rgba(11, 12, 16, 0.7)';

	$cta_style = "";
	if ( $cta_bg_image ) {
		$cta_style = "background-image: url('" . esc_url($cta_bg_image) . "');";
	}
?>
<section class="hp-section premium-cta-section hp-reveal" style="<?php echo esc_attr($cta_style); ?>">
	<div class="cta-overlay" style="background-color: <?php echo esc_attr($cta_overlay); ?>;"></div>
	<div class="cta-container">
		<?php if ( $cta_badge ) : ?>
			<div class="cta-badge-wrapper">
				<img src="<?php echo esc_url($cta_badge); ?>" alt="CTA Badge" class="cta-badge-img">
			</div>
		<?php endif; ?>
		
		<h2 class="cta-title"><?php echo esc_html($cta_heading); ?></h2>
		<p class="cta-desc"><?php echo esc_html($cta_description); ?></p>
		
		<div class="cta-btn-group">
			<?php if ( $cta_btn_primary_text && $cta_btn_primary_url ) : ?>
				<a href="<?php echo esc_url($cta_btn_primary_url); ?>" class="cta-btn cta-btn-primary">
					<?php echo esc_html($cta_btn_primary_text); ?>
				</a>
			<?php endif; ?>
			<?php if ( $cta_btn_secondary_text && $cta_btn_secondary_url ) : ?>
				<a href="<?php echo esc_url($cta_btn_secondary_url); ?>" class="cta-btn cta-btn-secondary">
					<?php echo esc_html($cta_btn_secondary_text); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>


<!-- Homepage Swiper Slider & AJAX filtering JS Scripts -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	// 1. FAQ Accordion Toggle Interaction
	const faqHeaders = document.querySelectorAll('.faq-item__header');
	faqHeaders.forEach(header => {
		header.addEventListener('click', function() {
			const item = this.parentElement;
			const body = item.querySelector('.faq-item__body');
			const isActive = item.classList.contains('faq-item--active');
			
			// Close all other items and change their icons to plus
			document.querySelectorAll('.faq-item').forEach(el => {
				el.classList.remove('faq-item--active');
				const elBody = el.querySelector('.faq-item__body');
				if (elBody) {
					elBody.style.maxHeight = null;
				}
				const icon = el.querySelector('.faq-item__icon i');
				if (icon) {
					icon.classList.remove('fa-minus');
					icon.classList.add('fa-plus');
				}
			});
			
			if (!isActive) {
				item.classList.add('faq-item--active');
				if (body) {
					body.style.maxHeight = body.scrollHeight + 'px';
				}
				const icon = item.querySelector('.faq-item__icon i');
				if (icon) {
					icon.classList.remove('fa-plus');
					icon.classList.add('fa-minus');
				}
			}
		});
	});


	// 2. Explore Districts Swiper Initialization
	const districtsContainer = document.querySelector('.districts-swiper');
	if (districtsContainer) {
		const districtSlideCount = districtsContainer.querySelectorAll('.swiper-slide').length;
		new Swiper('.districts-swiper', {
			loop: districtSlideCount > 6,
			slidesPerView: 2,
			spaceBetween: 16,
			grabCursor: true,
			autoplay: districtSlideCount > 2 ? {
				delay: 4000,
				disableOnInteraction: false,
				pauseOnMouseEnter: true
			} : false,
			pagination: {
				el: '.districts-swiper .swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				576: {
					slidesPerView: 4,
					spaceBetween: 20
				},
				992: {
					slidesPerView: 5,
					spaceBetween: 24
				},
				1200: {
					slidesPerView: 6,
					spaceBetween: 30
				}
			}
		});
	}

	// 3. Testimonials Swiper Initialization
	const testimonialsContainer = document.querySelector('.testimonials-swiper');
	if (testimonialsContainer) {
		new Swiper('.testimonials-swiper', {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			grabCursor: true,
			autoplay: {
				delay: 5000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.testimonials-swiper .swiper-pagination',
				clickable: true,
			}
		});
	}

	// 4. Hero Search Form Dropdown Cascadence
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

	const searchStateSelect = document.getElementById('search-state');
	const searchDistrictSelect = document.getElementById('search-district');

	if (searchStateSelect && searchDistrictSelect) {
		searchStateSelect.addEventListener('change', function() {
			const state = this.value;
			updateDistrictsDropdown(state, searchDistrictSelect);
		});
	}

	// Modern tab switcher logic for Hero Search
	const modernTabs = document.querySelectorAll('.modern-tab');
	const typeInput = document.getElementById('search-listing-type');
	if (modernTabs && typeInput) {
		modernTabs.forEach(tab => {
			tab.addEventListener('click', function(e) {
				e.preventDefault();
				modernTabs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');
				typeInput.value = this.dataset.type;
			});
		});
	}

	// Advanced fields panel toggle in Hero Search
	const advToggle = document.getElementById('advanced-toggle-btn');
	const advPanel = document.getElementById('advanced-panel');
	const modalBackdrop = document.getElementById('advanced-filters-backdrop');
	const modalElement = document.getElementById('advanced-filters-modal');
	const closeModalBtn = document.getElementById('close-modal-btn');
	const mobileResetBtn = document.getElementById('mobile-reset-btn');
	const mobileApplyBtn = document.getElementById('mobile-apply-btn');

	// References to main filters (to sync)
	const mainBeds = document.getElementById('adv-beds');
	const mainBaths = document.getElementById('adv-baths');
	const mainMinPrice = document.getElementById('adv-min-price');
	const mainMaxPrice = document.getElementById('adv-max-price');
	const mainAreaSize = document.getElementById('adv-area-size');
	const mainPropCat = document.getElementById('adv-prop-cat');
	const mainState = document.getElementById('search-state');
	const mainDistrict = document.getElementById('search-district');
	const mainPropType = document.getElementById('search-prop-type');

	// References to modal filters
	const modalBeds = document.getElementById('adv-beds-mobile');
	const modalBaths = document.getElementById('adv-baths-mobile');
	const modalMinPrice = document.getElementById('adv-min-price-mobile');
	const modalMaxPrice = document.getElementById('adv-max-price-mobile');
	const modalAreaSize = document.getElementById('adv-area-size-mobile');
	const modalPropCat = document.getElementById('adv-prop-cat-mobile');
	const modalState = document.getElementById('search-state-mobile');
	const modalDistrict = document.getElementById('search-district-mobile');
	const modalPropType = document.getElementById('search-prop-type-mobile');

	function openMobileModal() {
		if (modalBackdrop && modalElement) {
			// Sync modal values from main filters
			if (mainBeds && modalBeds) modalBeds.value = mainBeds.value;
			if (mainBaths && modalBaths) modalBaths.value = mainBaths.value;
			if (mainMinPrice && modalMinPrice) modalMinPrice.value = mainMinPrice.value;
			if (mainMaxPrice && modalMaxPrice) modalMaxPrice.value = mainMaxPrice.value;
			if (mainAreaSize && modalAreaSize) modalAreaSize.value = mainAreaSize.value;
			if (mainPropCat && modalPropCat) modalPropCat.value = mainPropCat.value;
			if (mainPropType && modalPropType) modalPropType.value = mainPropType.value;
			
			if (mainState && modalState) {
				modalState.value = mainState.value;
				const state = mainState.value;
				updateDistrictsDropdown(state, modalDistrict, function() {
					if (mainDistrict && modalDistrict) {
						modalDistrict.value = mainDistrict.value;
					}
				});
			}

			modalBackdrop.classList.add('active');
			modalElement.classList.add('active');
			document.body.style.overflow = 'hidden';
		}
	}

	function closeMobileModal() {
		if (modalBackdrop && modalElement) {
			modalBackdrop.classList.remove('active');
			modalElement.classList.remove('active');
			document.body.style.overflow = '';
		}
	}

	if (advToggle) {
		advToggle.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			if (window.innerWidth <= 768) {
				openMobileModal();
			} else {
				if (advPanel) {
					if (advPanel.style.display === 'none' || advPanel.style.display === '') {
						advPanel.style.display = 'block';
						advToggle.classList.add('active');
					} else {
						advPanel.style.display = 'none';
						advToggle.classList.remove('active');
					}
				}
			}
		});
	}

	if (closeModalBtn) closeModalBtn.addEventListener('click', closeMobileModal);
	if (modalBackdrop) modalBackdrop.addEventListener('click', closeMobileModal);

	// Reset filters inside modal
	if (mobileResetBtn) {
		mobileResetBtn.addEventListener('click', function() {
			if (modalBeds) modalBeds.value = '';
			if (modalBaths) modalBaths.value = '';
			if (modalMinPrice) modalMinPrice.value = '';
			if (modalMaxPrice) modalMaxPrice.value = '';
			if (modalAreaSize) modalAreaSize.value = '';
			if (modalPropCat) modalPropCat.value = '';
			if (modalState) modalState.value = '';
			if (modalDistrict) modalDistrict.innerHTML = '<option value="">Select District...</option>';
			if (modalPropType) modalPropType.value = '';
		});
	}

	// Apply filters from modal
	if (mobileApplyBtn) {
		mobileApplyBtn.addEventListener('click', function() {
			// Sync back to main inputs
			if (modalBeds && mainBeds) mainBeds.value = modalBeds.value;
			if (modalBaths && mainBaths) mainBaths.value = modalBaths.value;
			if (modalMinPrice && mainMinPrice) mainMinPrice.value = modalMinPrice.value;
			if (modalMaxPrice && mainMaxPrice) mainMaxPrice.value = modalMaxPrice.value;
			if (modalAreaSize && mainAreaSize) mainAreaSize.value = modalAreaSize.value;
			if (modalPropCat && mainPropCat) mainPropCat.value = modalPropCat.value;
			if (modalPropType && mainPropType) mainPropType.value = modalPropType.value;
			if (modalState && mainState) mainState.value = modalState.value;

			closeMobileModal();

			if (modalState && modalDistrict && mainDistrict) {
				const state = modalState.value;
				updateDistrictsDropdown(state, mainDistrict, function() {
					mainDistrict.value = modalDistrict.value;
					executeHomepageSearchRedirect();
				});
			} else {
				executeHomepageSearchRedirect();
			}
		});
	}

	// Mobile modal district cascade dropdown
	if (modalState && modalDistrict) {
		modalState.addEventListener('change', function() {
			const state = this.value;
			updateDistrictsDropdown(state, modalDistrict);
		});
	}

	// Search execution via redirect to properties list page
	const searchForm = document.getElementById('homepage-search-form');

	if (searchForm) {
		searchForm.addEventListener('submit', function(e) {
			e.preventDefault();
			executeHomepageSearchRedirect();
		});
	}

	function executeHomepageSearchRedirect() {
		const listingType = document.getElementById('search-listing-type') ? document.getElementById('search-listing-type').value : 'all';
		const keyword = document.getElementById('search-keyword') ? document.getElementById('search-keyword').value : '';
		const state = document.getElementById('search-state') ? document.getElementById('search-state').value : '';
		const district = document.getElementById('search-district') ? document.getElementById('search-district').value : '';
		const propType = document.getElementById('search-prop-type') ? document.getElementById('search-prop-type').value : '';

		const params = new URLSearchParams();
		
		if (listingType && listingType !== 'all') {
			params.append('listing_type', listingType);
		}
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
	}

	// 5. Trending Section Redesign Filter Tabs & Swiper Slider
	<?php if ( $trending_enable ) : ?>
	const trendingProjectTabs = document.querySelectorAll('.trending-projects-tab-btn');
	const trendingSliderTrack = document.getElementById('trending-projects-slider-track');
	const trendingGridContainer = document.getElementById('trending-projects-grid-container');
	const swiperContainerEl = document.querySelector('.trending-projects-swiper');
	
	let trendingSwiperInstance = null;

	function initTrendingProjectsSwiper() {
		if (!swiperContainerEl) return;
		
		// Destroy existing Swiper instance
		if (trendingSwiperInstance) {
			trendingSwiperInstance.destroy(true, true);
			trendingSwiperInstance = null;
		}

		// Check if there are slides
		const slideCount = swiperContainerEl.querySelectorAll('.property-card').length;
		if (slideCount === 0 || swiperContainerEl.querySelector('.no-properties-found')) {
			return;
		}

		// Read parameters from data attributes
		const autoplay = swiperContainerEl.dataset.autoplay === 'true';
		const speed = parseInt(swiperContainerEl.dataset.speed || 5000);
		const loop = swiperContainerEl.dataset.loop === 'true';
		const pauseOnHover = swiperContainerEl.dataset.hover === 'true';
		const centerSlides = swiperContainerEl.dataset.center === 'true';
		const desktopSlides = parseInt(swiperContainerEl.dataset.desktop || 4);
		const laptopSlides = parseInt(swiperContainerEl.dataset.laptop || 4);
		const tabletSlides = parseInt(swiperContainerEl.dataset.tablet || 2);
		const mobileSlides = parseInt(swiperContainerEl.dataset.mobile || 1);
		const spaceBetween = parseInt(swiperContainerEl.dataset.space || 32);

		trendingSwiperInstance = new Swiper('.trending-projects-swiper', {
			loop: loop && (slideCount > Math.max(desktopSlides, laptopSlides, tabletSlides, mobileSlides)),
			slidesPerView: mobileSlides,
			spaceBetween: spaceBetween,
			centeredSlides: centerSlides,
			watchSlidesProgress: true,
			autoplay: autoplay ? {
				delay: speed,
				disableOnInteraction: false,
				pauseOnMouseEnter: pauseOnHover,
			} : false,
			speed: 800,
			grabCursor: true,
			simulateTouch: true,
			allowTouchMove: true,
			navigation: {
				nextEl: '#trending-projects-next-btn',
				prevEl: '#trending-projects-prev-btn',
			},
			pagination: {
				el: '.trending-projects-slider-wrapper .swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				576: {
					slidesPerView: tabletSlides,
					spaceBetween: spaceBetween
				},
				992: {
					slidesPerView: laptopSlides,
					spaceBetween: spaceBetween
				},
				1200: {
					slidesPerView: desktopSlides,
					spaceBetween: spaceBetween
				}
			}
		});
	}

	// Initialize Swiper on page load
	if (swiperContainerEl) {
		initTrendingProjectsSwiper();
	}

	if (trendingProjectTabs) {
		trendingProjectTabs.forEach(tab => {
			tab.addEventListener('click', function(e) {
				e.preventDefault();
				if (this.classList.contains('active')) return;

				trendingProjectTabs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');

				const filterValue = this.getAttribute('data-filter');
				const containerToAnimate = swiperContainerEl ? swiperContainerEl : trendingGridContainer;
				
				if (containerToAnimate) {
					containerToAnimate.style.opacity = '0.5';
				}

				const data = new URLSearchParams();
				data.append('action', 'get_trending_projects');
				data.append('property_status', filterValue);
				data.append('posts_count', '<?php echo esc_js($trending_posts_count); ?>');

				fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: data.toString()
				})
				.then(res => res.json())
				.then(response => {
					if (response.success) {
						if (swiperContainerEl && trendingSliderTrack) {
							// Update slider track
							trendingSliderTrack.innerHTML = response.data.html;
							
							// If returned no properties, wrap in swiper-slide to render correctly
							if (trendingSliderTrack.querySelector('.no-properties-found')) {
								const noProp = trendingSliderTrack.querySelector('.no-properties-found');
								noProp.classList.add('swiper-slide');
							}
							
							// Reinitialize Swiper
							initTrendingProjectsSwiper();
						} else if (trendingGridContainer) {
							trendingGridContainer.innerHTML = response.data.html;
						}
					}
					if (containerToAnimate) {
						containerToAnimate.style.opacity = '1';
					}
				})
				.catch(err => {
					console.error(err);
					const errorHtml = '<div class="no-properties-found" style="text-align: center; padding: 40px; background: #ffffff; border-radius: 12px; width: 100%;"><h3>An error occurred</h3></div>';
					if (swiperContainerEl && trendingSliderTrack) {
						trendingSliderTrack.innerHTML = errorHtml;
						if (trendingSwiperInstance) {
							trendingSwiperInstance.destroy(true, true);
							trendingSwiperInstance = null;
						}
					} else if (trendingGridContainer) {
						trendingGridContainer.innerHTML = errorHtml;
					}
					if (containerToAnimate) {
						containerToAnimate.style.opacity = '1';
					}
				});
			});
		});
	}
	<?php endif; ?>

	// 6. Scroll Reveal Observer
	if ('IntersectionObserver' in window) {
		const revealObserver = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add('hp-reveal--active');
					revealObserver.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.05,
			rootMargin: '0px 0px -50px 0px'
		});

		document.querySelectorAll('.hp-reveal').forEach(el => {
			revealObserver.observe(el);
		});
	} else {
		document.querySelectorAll('.hp-reveal').forEach(el => {
			el.classList.add('hp-reveal--active');
		});
	}

	// 7. Our Services Section Tabs and Content Swapping
	const serviceTabs = document.querySelectorAll('.our-services-tab');
	const serviceImages = document.querySelectorAll('.our-services-image');
	const serviceContents = document.querySelectorAll('.our-services-content');

	if (serviceTabs.length > 0) {
		serviceTabs.forEach(tab => {
			tab.addEventListener('click', function() {
				const index = this.getAttribute('data-index');

				// Update active class on tabs
				serviceTabs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');

				// Update active class on images
				serviceImages.forEach(img => {
					if (img.getAttribute('data-index') === index) {
						img.classList.add('active');
					} else {
						img.classList.remove('active');
					}
				});

				// Update active class on contents
				serviceContents.forEach(content => {
					if (content.getAttribute('data-index') === index) {
						content.classList.add('active');
					} else {
						content.classList.remove('active');
					}
				});

				// Auto scroll active tab into view horizontally on mobile
				if (window.innerWidth < 768) {
					this.scrollIntoView({
						behavior: 'smooth',
						block: 'nearest',
						inline: 'center'
					});
				}
			});
		});
	}

	// 8. FAQ Section Tabs and Content Swapping
	const faqTabs = document.querySelectorAll('.faq-tab-btn');
	const faqTabContents = document.querySelectorAll('.faq-tab-content');

	if (faqTabs.length > 0) {
		faqTabs.forEach(tab => {
			tab.addEventListener('click', function() {
				// Update active class on tab buttons
				faqTabs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');

				const targetSelector = this.getAttribute('data-tab-target');
				const targetContent = document.querySelector(targetSelector);

				// Swap active class on content wrappers with opacity transition
				faqTabContents.forEach(content => {
					if (content === targetContent) {
						content.style.display = 'block';
						// Force reflow
						content.offsetHeight;
						content.classList.add('active');
						content.style.opacity = '1';
					} else {
						content.classList.remove('active');
						content.style.opacity = '0';
						setTimeout(() => {
							if (!content.classList.contains('active')) {
								content.style.display = 'none';
							}
						}, 400);
					}
				});
			});
		});
	}
});
</script>

<?php
get_footer();


