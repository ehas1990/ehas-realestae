<?php
/**
 * PRWorks Real Estate Theme Settings Page
 * Manages Global Default Currency and Single Property Page Drag-and-Drop Layout
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register options page in WP Admin
function casaview_add_theme_settings_page() {
	add_menu_page(
		'PRWorks Real Estate Options',
		'PRWorks Real Estate Options',
		'manage_options',
		'casaview-settings',
		'casaview_render_theme_settings_page',
		'dashicons-admin-generic',
		60
	);
}
add_action( 'admin_menu', 'casaview_add_theme_settings_page' );

// Enqueue WP Media Library on the options page
function casaview_enqueue_media_uploader( $hook ) {
	if ( $hook === 'toplevel_page_casaview-settings' ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'casaview_enqueue_media_uploader' );

// Get default layout settings
function casaview_get_default_layout_settings() {
	return array(
		array( 'id' => 'gallery', 'label' => 'Property Gallery', 'icon' => 'fa-regular fa-image', 'visible' => 1 ),
		array( 'id' => 'overview', 'label' => 'Key Details', 'icon' => 'fa-solid fa-circle-info', 'visible' => 1 ),
		array( 'id' => 'description', 'label' => 'Description', 'icon' => 'fa-solid fa-align-left', 'visible' => 1 ),
		array( 'id' => 'amenities', 'label' => 'Features & Amenities', 'icon' => 'fa-solid fa-list-check', 'visible' => 1 ),
		array( 'id' => 'floor_plans', 'label' => 'Floor Plans', 'icon' => 'fa-solid fa-map', 'visible' => 1 ),
		array( 'id' => 'faqs', 'label' => 'Frequently Asked Questions', 'icon' => 'fa-solid fa-circle-question', 'visible' => 1 ),
		array( 'id' => 'documents', 'label' => 'Property Documents', 'icon' => 'fa-solid fa-file-pdf', 'visible' => 1 ),
		array( 'id' => 'location', 'label' => 'Property Location & Map', 'icon' => 'fa-solid fa-map-location-dot', 'visible' => 1 ),
		array( 'id' => 'agent', 'label' => 'Listing Agents', 'icon' => 'fa-solid fa-user-tie', 'visible' => 1 ),
		array( 'id' => 'similar', 'label' => 'Similar Properties', 'icon' => 'fa-solid fa-house-chimney', 'visible' => 1 ),
	);
}

// Render settings page
function casaview_render_theme_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Handle Save Action
	if ( isset( $_POST['casaview_save_settings'] ) && check_admin_referer( 'casaview_settings_nonce_action', 'casaview_settings_nonce' ) ) {
		// Save currency
		$default_currency = sanitize_text_field( $_POST['casaview_default_currency'] ?? 'AED' );
		update_option( 'casaview_default_currency', $default_currency );

		// Save Global Design Settings
		$font_type = sanitize_text_field( $_POST['casaview_font_type'] ?? 'google' );
		$theme_font = sanitize_text_field( $_POST['casaview_theme_font'] ?? 'Manrope' );
		$custom_font_url = esc_url_raw( $_POST['casaview_custom_font_url'] ?? '' );
		$custom_font_name = sanitize_text_field( $_POST['casaview_custom_font_name'] ?? 'CustomUploadedFont' );
		$primary_color = sanitize_hex_color( $_POST['casaview_primary_color'] ?? '#c5a880' );
		$secondary_color = sanitize_hex_color( $_POST['casaview_secondary_color'] ?? '#f4f5f8' );
		$button_color = sanitize_hex_color( $_POST['casaview_button_color'] ?? '#FCB71C' );
		$button_hover_color = sanitize_hex_color( $_POST['casaview_button_hover_color'] ?? '#000000' );
		$secondary_button_color = sanitize_hex_color( $_POST['casaview_secondary_button_color'] ?? '#000000' );

		update_option( 'casaview_font_type', $font_type );
		update_option( 'casaview_theme_font', $theme_font );
		update_option( 'casaview_custom_font_url', $custom_font_url );
		update_option( 'casaview_custom_font_name', $custom_font_name );
		update_option( 'casaview_primary_color', $primary_color );
		update_option( 'casaview_secondary_color', $secondary_color );
		update_option( 'casaview_button_color', $button_color );
		update_option( 'casaview_button_hover_color', $button_hover_color );
		update_option( 'casaview_secondary_button_color', $secondary_button_color );

		// Save layout ordering
		$sections_ordered = array();
		if ( isset( $_POST['layout_order'] ) && is_array( $_POST['layout_order'] ) ) {
			foreach ( $_POST['layout_order'] as $index => $section_id ) {
				$section_id = sanitize_key( $section_id );
				$label = sanitize_text_field( $_POST['layout_label'][$section_id] ?? '' );
				$icon = sanitize_text_field( $_POST['layout_icon'][$section_id] ?? '' );
				$visible = isset( $_POST['layout_visible'][$section_id] ) ? 1 : 0;
				
				$sections_ordered[] = array(
					'id'      => $section_id,
					'label'   => $label,
					'icon'    => $icon,
					'visible' => $visible,
				);
			}
			update_option( 'casaview_layout_settings', $sections_ordered );
		}

		// Save Featured Listings Settings
		$enable_slider = isset( $_POST['featured_listings_enable_slider'] ) ? 1 : 0;
		$auto_slide = isset( $_POST['featured_listings_auto_slide'] ) ? 1 : 0;
		$slide_speed = intval( $_POST['featured_listings_slide_speed'] ?? 3000 );
		$num_properties = intval( $_POST['featured_listings_num_properties'] ?? 6 );
		$show_tabs = isset( $_POST['featured_listings_show_tabs'] ) ? 1 : 0;
		$show_price = isset( $_POST['featured_listings_show_price'] ) ? 1 : 0;
		$show_location = isset( $_POST['featured_listings_show_location'] ) ? 1 : 0;

		update_option( 'options_featured_listings_enable_slider', $enable_slider );
		update_option( 'options_featured_listings_auto_slide', $auto_slide );
		update_option( 'options_featured_listings_slide_speed', $slide_speed );
		update_option( 'options_featured_listings_num_properties', $num_properties );
		update_option( 'options_featured_listings_show_tabs', $show_tabs );
		update_option( 'options_featured_listings_show_price', $show_price );
		update_option( 'options_featured_listings_show_location', $show_location );

		// Save categories tabs repeater
		$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
		$categories_tabs_data = array();
		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				$enabled = isset( $_POST['cat_tab_enabled'][$cat->term_id] ) ? 1 : 0;
				$order = intval( $_POST['cat_tab_order'][$cat->term_id] ?? 0 );
				if ( $enabled ) {
					$categories_tabs_data[] = array(
						'category_term' => $cat->term_id,
						'enabled' => 1,
						'order' => $order
					);
				}
			}
			// Sort by order ASC
			usort( $categories_tabs_data, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			
			// Map to ACF format
			$acf_categories_data = array();
			foreach ($categories_tabs_data as $row) {
				$acf_categories_data[] = array(
					'field_feat_cat_term' => $row['category_term'],
					'field_feat_cat_enabled' => 1
				);
			}
			update_option( 'options_featured_listings_categories', $acf_categories_data );
			update_option( '_options_featured_listings_categories', 'field_featured_categories' );
		}

		// Save Districts Slider Settings
		$dist_autoplay = isset( $_POST['district_slider_autoplay'] ) ? 1 : 0;
		$dist_speed = intval( $_POST['district_slider_speed'] ?? 800 );
		$dist_loop = isset( $_POST['district_slider_loop'] ) ? 1 : 0;
		$dist_nav = isset( $_POST['district_slider_nav'] ) ? 1 : 0;
		$dist_items_desktop = intval( $_POST['district_slider_items_desktop'] ?? 4 );
		$dist_items_tablet = intval( $_POST['district_slider_items_tablet'] ?? 3 );
		$dist_items_mobile = intval( $_POST['district_slider_items_mobile'] ?? 2 );

		update_option( 'options_district_slider_autoplay', $dist_autoplay );
		update_option( 'options_district_slider_speed', $dist_speed );
		update_option( 'options_district_slider_loop', $dist_loop );
		update_option( 'options_district_slider_nav', $dist_nav );
		update_option( 'options_district_slider_items_desktop', $dist_items_desktop );
		update_option( 'options_district_slider_items_tablet', $dist_items_tablet );
		update_option( 'options_district_slider_items_mobile', $dist_items_mobile );
		
		flush_rewrite_rules(); // Flush rewrite rules so slug changes are registered immediately

		echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
	}

	// Retrieve values
	$default_currency = get_option( 'casaview_default_currency', 'AED' );
	
	// Retrieve global design settings values
	$font_type = get_option( 'casaview_font_type', 'google' );
	$theme_font = get_option( 'casaview_theme_font', 'Manrope' );
	$custom_font_url = get_option( 'casaview_custom_font_url', '' );
	$custom_font_name = get_option( 'casaview_custom_font_name', 'CustomUploadedFont' );
	$primary_color = get_option( 'casaview_primary_color', '#c5a880' );
	$secondary_color = get_option( 'casaview_secondary_color', '#f4f5f8' );
	$button_color = get_option( 'casaview_button_color', '#FCB71C' );
	$button_hover_color = get_option( 'casaview_button_hover_color', '#000000' );
	$secondary_button_color = get_option( 'casaview_secondary_button_color', '#000000' );

	$layout_settings = get_option( 'casaview_layout_settings' );
	if ( ! is_array( $layout_settings ) || empty( $layout_settings ) ) {
		$layout_settings = casaview_get_default_layout_settings();
	}

	// Supported currencies list
	$currencies = array(
		'AED' => 'AED (د.إ) - UAE Dirham',
		'INR' => 'INR (₹) - Indian Rupee',
		'USD' => 'USD ($) - US Dollar',
		'EUR' => 'EUR (€) - Euro',
		'GBP' => 'GBP (£) - British Pound',
		'SAR' => 'SAR (﷼) - Saudi Riyal',
		'QAR' => 'QAR (﷼) - Qatari Riyal',
		'KWD' => 'KWD (د.ك) - Kuwaiti Dinar',
		'OMR' => 'OMR (﷼) - Omani Rial',
		'BHD' => 'BHD (د.ب) - Bahraini Dinar',
	);
	?>
	<div class="wrap">
		<h1 style="font-weight: 700; margin-bottom: 20px;">PRWorks Real Estate Property Management Settings</h1>
		
		<style>
			.casaview-settings-card {
				background: #fff;
				border: 1px solid #ccd0d4;
				box-shadow: 0 1px 1px rgba(0,0,0,.04);
				padding: 24px;
				margin-bottom: 24px;
				max-width: 900px;
				border-radius: 8px;
			}
			.casaview-settings-title {
				margin-top: 0;
				border-bottom: 1px solid #eee;
				padding-bottom: 12px;
				font-size: 18px;
				font-weight: 600;
			}
			.casaview-layout-list {
				list-style: none;
				padding: 0;
				margin: 20px 0;
			}
			.casaview-layout-item {
				background: #f8f9fa;
				border: 1px solid #dcdde1;
				padding: 12px 18px;
				margin-bottom: 8px;
				cursor: move;
				display: flex;
				align-items: center;
				border-radius: 4px;
				transition: background 0.2s, box-shadow 0.2s;
			}
			.casaview-layout-item:hover {
				background: #f1f2f6;
				box-shadow: 0 2px 5px rgba(0,0,0,0.05);
			}
			.casaview-layout-item .drag-handle {
				color: #a4b0be;
				margin-right: 15px;
				font-size: 18px;
				user-select: none;
			}
			.casaview-layout-item .item-details {
				flex-grow: 1;
				display: flex;
				gap: 15px;
				align-items: center;
			}
			.casaview-layout-item input[type="text"] {
				padding: 5px 8px;
				border: 1px solid #ccd0d4;
				border-radius: 4px;
			}
			.casaview-layout-item .visibility-toggle {
				margin-right: 15px;
			}
			.casaview-layout-item .icon-preview {
				width: 30px;
				text-align: center;
				color: #c5a880;
				font-size: 16px;
			}
		</style>

		<form method="post" action="">
			<?php wp_nonce_field( 'casaview_settings_nonce_action', 'casaview_settings_nonce' ); ?>

			<!-- Currency Settings Card -->
			<div class="casaview-settings-card">
				<h2 class="casaview-settings-title">Global Currency Settings</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="casaview_default_currency">Global Default Currency</label></th>
						<td>
							<select name="casaview_default_currency" id="casaview_default_currency" class="regular-text" style="padding: 6px 10px; height: auto;">
								<?php foreach ( $currencies as $code => $label ) : ?>
									<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $default_currency, $code ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
							<p class="description">This default currency will be applied to all properties unless overridden on a specific property.</p>
						</td>
					</tr>
				</table>
			</div>

			<!-- Global Design & Styling Settings Card -->
			<div class="casaview-settings-card">
				<h2 class="casaview-settings-title">Global Design & Styling Settings</h2>
				<table class="form-table">
					<!-- Font Source Selection -->
					<tr>
						<th scope="row"><label for="casaview_font_type">Font Source</label></th>
						<td>
							<label style="margin-right: 25px; font-weight: 600;">
								<input type="radio" name="casaview_font_type" value="google" <?php checked( $font_type, 'google' ); ?>> Google Fonts
							</label>
							<label style="font-weight: 600;">
								<input type="radio" name="casaview_font_type" value="custom" <?php checked( $font_type, 'custom' ); ?>> Upload Custom Font File
							</label>
						</td>
					</tr>

					<!-- Google Fonts selection -->
					<tr class="font-row-google" style="<?php echo $font_type === 'google' ? '' : 'display: none;'; ?>">
						<th scope="row"><label for="casaview_theme_font">Select Google Font</label></th>
						<td>
							<?php
							$google_fonts = array(
								'Manrope' => 'Manrope (Sans-Serif)',
								'Playfair Display' => 'Playfair Display (Serif)',
								'Inter' => 'Inter (Sans-Serif)',
								'Roboto' => 'Roboto (Sans-Serif)',
								'Poppins' => 'Poppins (Sans-Serif)',
								'Montserrat' => 'Montserrat (Sans-Serif)',
								'Lato' => 'Lato (Sans-Serif)',
								'Open Sans' => 'Open Sans (Sans-Serif)',
								'Tajawal' => 'Tajawal (Arabic/Sans-Serif)',
								'Outfit' => 'Outfit (Sans-Serif)',
								'Lora' => 'Lora (Serif)',
							);
							?>
							<select name="casaview_theme_font" id="casaview_theme_font" class="regular-text" style="padding: 6px 10px; height: auto;">
								<?php foreach ( $google_fonts as $value => $label ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $theme_font, $value ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
							<p class="description">Select a popular font family to apply site-wide.</p>
						</td>
					</tr>

					<!-- Custom Font Upload URL -->
					<tr class="font-row-custom" style="<?php echo $font_type === 'custom' ? '' : 'display: none;'; ?>">
						<th scope="row"><label for="casaview_custom_font_url">Custom Font File</label></th>
						<td>
							<input type="text" name="casaview_custom_font_url" id="casaview_custom_font_url" value="<?php echo esc_attr( $custom_font_url ); ?>" class="regular-text" style="width: 70%; margin-right: 10px;">
							<button type="button" id="casaview_upload_font_btn" class="button">Upload/Select Font</button>
							<p class="description">Supported formats: .woff2, .woff, .ttf, .otf. Select a file from media library or upload a new one.</p>
						</td>
					</tr>

					<!-- Custom Font Family Name -->
					<tr class="font-row-custom" style="<?php echo $font_type === 'custom' ? '' : 'display: none;'; ?>">
						<th scope="row"><label for="casaview_custom_font_name">Custom Font Family Name</label></th>
						<td>
							<input type="text" name="casaview_custom_font_name" id="casaview_custom_font_name" value="<?php echo esc_attr( $custom_font_name ); ?>" class="regular-text" placeholder="e.g. MyCustomFont">
							<p class="description">Enter the font-family name to use in CSS (alphanumeric, no spaces or special characters).</p>
						</td>
					</tr>

					<!-- Colors -->
					<tr>
						<th scope="row"><label for="casaview_primary_color">Global Primary Button Color</label></th>
						<td>
							<input type="color" name="casaview_primary_color" id="casaview_primary_color" value="<?php echo esc_attr( $primary_color ); ?>" style="width: 60px; height: 35px; padding: 0; border: none; border-radius: 4px; vertical-align: middle; cursor: pointer;">
							<span style="margin-left: 10px; font-family: monospace; font-size: 13px; font-weight: 600;"><?php echo esc_html( $primary_color ); ?></span>
							<p class="description" style="display: inline-block; margin-left: 15px; margin-top: 0;">Main brand accent color and primary button color (Default: #c5a880).</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="casaview_secondary_button_color">Secondary Button Color</label></th>
						<td>
							<input type="color" name="casaview_secondary_button_color" id="casaview_secondary_button_color" value="<?php echo esc_attr( $secondary_button_color ); ?>" style="width: 60px; height: 35px; padding: 0; border: none; border-radius: 4px; vertical-align: middle; cursor: pointer;">
							<span style="margin-left: 10px; font-family: monospace; font-size: 13px; font-weight: 600;"><?php echo esc_html( $secondary_button_color ); ?></span>
							<p class="description" style="display: inline-block; margin-left: 15px; margin-top: 0;">Secondary button color and hover state color (Default: #e05a36).</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="casaview_secondary_color">Secondary Color</label></th>
						<td>
							<input type="color" name="casaview_secondary_color" id="casaview_secondary_color" value="<?php echo esc_attr( $secondary_color ); ?>" style="width: 60px; height: 35px; padding: 0; border: none; border-radius: 4px; vertical-align: middle; cursor: pointer;">
							<span style="margin-left: 10px; font-family: monospace; font-size: 13px; font-weight: 600;"><?php echo esc_html( $secondary_color ); ?></span>
							<p class="description" style="display: inline-block; margin-left: 15px; margin-top: 0;">Secondary background elements/sections color (Default: #f4f5f8).</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="casaview_button_color">Button Color</label></th>
						<td>
							<input type="color" name="casaview_button_color" id="casaview_button_color" value="<?php echo esc_attr( $button_color ); ?>" style="width: 60px; height: 35px; padding: 0; border: none; border-radius: 4px; vertical-align: middle; cursor: pointer;">
							<span style="margin-left: 10px; font-family: monospace; font-size: 13px; font-weight: 600;"><?php echo esc_html( $button_color ); ?></span>
							<p class="description" style="display: inline-block; margin-left: 15px; margin-top: 0;">Default CTA button background fill (Default: #FCB71C).</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="casaview_button_hover_color">Button Hover Color</label></th>
						<td>
							<input type="color" name="casaview_button_hover_color" id="casaview_button_hover_color" value="<?php echo esc_attr( $button_hover_color ); ?>" style="width: 60px; height: 35px; padding: 0; border: none; border-radius: 4px; vertical-align: middle; cursor: pointer;">
							<span style="margin-left: 10px; font-family: monospace; font-size: 13px; font-weight: 600;"><?php echo esc_html( $button_hover_color ); ?></span>
							<p class="description" style="display: inline-block; margin-left: 15px; margin-top: 0;">CTA button background fill on hover (Default: #e05a36).</p>
						</td>
					</tr>
				</table>
			</div>

			<!-- Layout Manager Settings Card -->
			<div class="casaview-settings-card">
				<h2 class="casaview-settings-title">Single Property Page Layout Customizer</h2>
				<p class="description">Drag and drop sections to rearrange the layout order of the single property details page. Toggle the checkbox to show or hide a section, and edit the labels and icons dynamically.</p>
				
				<ul class="casaview-layout-list" id="layout-sortable">
					<?php foreach ( $layout_settings as $section ) : 
						$sid = esc_attr( $section['id'] );
						?>
						<li class="casaview-layout-item" draggable="true" data-id="<?php echo $sid; ?>">
							<span class="drag-handle">&#9776;</span>
							<input type="hidden" name="layout_order[]" value="<?php echo $sid; ?>">
							
							<div class="item-details">
								<!-- Visibility Checkbox -->
								<label class="visibility-toggle">
									<input type="checkbox" name="layout_visible[<?php echo $sid; ?>]" value="1" <?php checked( $section['visible'], 1 ); ?>>
									Active
								</label>
								
								<!-- Icon Preview -->
								<div class="icon-preview">
									<i class="<?php echo esc_attr( $section['icon'] ); ?>"></i>
								</div>
								
								<!-- Section ID Label -->
								<div style="font-weight: 600; width: 120px; text-transform: uppercase; font-size: 11px; color: #7f8c8d;">
									<?php echo esc_html( str_replace( '_', ' ', $sid ) ); ?>
								</div>

								<!-- Custom Label -->
								<div>
									<label style="font-size:11px; display:block; color:#7f8c8d; margin-bottom:2px;">Custom Label</label>
									<input type="text" name="layout_label[<?php echo $sid; ?>]" value="<?php echo esc_attr( $section['label'] ); ?>" placeholder="Section Heading" style="width: 200px;">
								</div>

								<!-- Custom Icon -->
								<div>
									<label style="font-size:11px; display:block; color:#7f8c8d; margin-bottom:2px;">FontAwesome Icon Class</label>
									<input type="text" name="layout_icon[<?php echo $sid; ?>]" value="<?php echo esc_attr( $section['icon'] ); ?>" placeholder="e.g. fa-solid fa-bed" style="width: 220px;" class="icon-class-input">
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Featured Listings Slider Settings Card -->
			<div class="casaview-settings-card">
				<h2 class="casaview-settings-title">Featured Listings Slider & Tabs Settings</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="featured_listings_enable_slider">Enable Slider</label></th>
						<td>
							<input type="checkbox" name="featured_listings_enable_slider" id="featured_listings_enable_slider" value="1" <?php checked( get_option('options_featured_listings_enable_slider', 1), 1 ); ?>>
							<span class="description">Toggle slider mode on the homepage (if disabled, displays a static responsive grid layout instead).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_auto_slide">Auto Slide On/Off</label></th>
						<td>
							<input type="checkbox" name="featured_listings_auto_slide" id="featured_listings_auto_slide" value="1" <?php checked( get_option('options_featured_listings_auto_slide', 1), 1 ); ?>>
							<span class="description">Enable automatic sliding of listings.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_slide_speed">Slide Speed (in ms)</label></th>
						<td>
							<input type="number" name="featured_listings_slide_speed" id="featured_listings_slide_speed" value="<?php echo esc_attr( get_option('options_featured_listings_slide_speed', 3000) ); ?>" class="small-text">
							<span class="description">Duration between slides in milliseconds (e.g. 3000).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_num_properties">Number of Properties to Display</label></th>
						<td>
							<input type="number" name="featured_listings_num_properties" id="featured_listings_num_properties" value="<?php echo esc_attr( get_option('options_featured_listings_num_properties', 6) ); ?>" class="small-text">
							<span class="description">Maximum number of featured properties to load.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_show_tabs">Show Category Tabs</label></th>
						<td>
							<input type="checkbox" name="featured_listings_show_tabs" id="featured_listings_show_tabs" value="1" <?php checked( get_option('options_featured_listings_show_tabs', 1), 1 ); ?>>
							<span class="description">Show category filter tabs above properties.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_show_price">Show Price</label></th>
						<td>
							<input type="checkbox" name="featured_listings_show_price" id="featured_listings_show_price" value="1" <?php checked( get_option('options_featured_listings_show_price', 1), 1 ); ?>>
							<span class="description">Show listing price in the cards.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="featured_listings_show_location">Show Location & District</label></th>
						<td>
							<input type="checkbox" name="featured_listings_show_location" id="featured_listings_show_location" value="1" <?php checked( get_option('options_featured_listings_show_location', 1), 1 ); ?>>
							<span class="description">Show district and place in the cards.</span>
						</td>
					</tr>
				</table>

				<!-- Category Tabs List & Ordering Table -->
				<h3 style="margin-top:25px; margin-bottom:10px; font-size:15px; border-bottom:1px solid #eee; padding-bottom:8px;">Category Tabs Configuration</h3>
				<p class="description" style="margin-bottom:15px;">Enable or disable category tabs and set their display ordering (lower numbers appear first). All active tabs will be shown above the featured listings section.</p>
				
				<table class="wp-list-table widefat fixed striped" style="max-width:100%; border:1px solid #ccd0d4; border-radius:4px;">
					<thead>
						<tr>
							<th style="padding:10px; font-weight:600; width:100px;">Enabled</th>
							<th style="padding:10px; font-weight:600;">Category Name</th>
							<th style="padding:10px; font-weight:600;">Slug</th>
							<th style="padding:10px; font-weight:600; width:150px;">Display Order</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
						
						// Get currently saved ACF configuration for lookup
						$saved_categories = get_option('options_featured_listings_categories', array());
						$saved_lookup = array();
						if ( is_array($saved_categories) ) {
							foreach ($saved_categories as $idx => $row) {
								$t_id = isset($row['field_feat_cat_term']) ? $row['field_feat_cat_term'] : (isset($row['category_term']) ? $row['category_term'] : 0);
								if ($t_id) {
									$saved_lookup[$t_id] = array(
										'enabled' => 1,
										'order' => $idx
									);
								}
							}
						}
						
						if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) :
							// Sort categories to show saved order in the table initial state
							usort($categories, function($a, $b) use ($saved_lookup) {
								$order_a = isset($saved_lookup[$a->term_id]) ? $saved_lookup[$a->term_id]['order'] : 999;
								$order_b = isset($saved_lookup[$b->term_id]) ? $saved_lookup[$b->term_id]['order'] : 999;
								return $order_a - $order_b;
							});
							
							foreach ( $categories as $cat ) :
								$is_enabled = isset($saved_lookup[$cat->term_id]);
								$order_val = $is_enabled ? $saved_lookup[$cat->term_id]['order'] : 0;
								?>
								<tr>
									<td style="padding:10px; vertical-align:middle;">
										<input type="checkbox" name="cat_tab_enabled[<?php echo $cat->term_id; ?>]" value="1" <?php checked($is_enabled, true); ?>>
									</td>
									<td style="padding:10px; vertical-align:middle; font-weight:600;"><?php echo esc_html($cat->name); ?></td>
									<td style="padding:10px; vertical-align:middle; color:#666; font-family:monospace;"><?php echo esc_html($cat->slug); ?></td>
									<td style="padding:10px; vertical-align:middle;">
										<input type="number" name="cat_tab_order[<?php echo $cat->term_id; ?>]" value="<?php echo esc_attr($order_val); ?>" class="small-text" style="width:70px; padding:3px 5px;">
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="4" style="padding:15px; text-align:center; color:#999;">No categories found. Add categories under <strong>Properties -> Categories</strong> first.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Districts Section Settings Card -->
			<div class="casaview-settings-card" style="margin-top: 30px;">
				<h2 class="casaview-settings-title">Districts Section Slider Settings</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="district_slider_autoplay">Enable Autoplay</label></th>
						<td>
							<input type="checkbox" name="district_slider_autoplay" id="district_slider_autoplay" value="1" <?php checked( get_option('options_district_slider_autoplay', 1), 1 ); ?>>
							<span class="description">Enable automatic sliding of districts.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_speed">Slider Speed (in ms)</label></th>
						<td>
							<input type="number" name="district_slider_speed" id="district_slider_speed" value="<?php echo esc_attr( get_option('options_district_slider_speed', 800) ); ?>" class="small-text">
							<span class="description">Transition animation duration in milliseconds (default: 800).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_loop">Infinite Loop</label></th>
						<td>
							<input type="checkbox" name="district_slider_loop" id="district_slider_loop" value="1" <?php checked( get_option('options_district_slider_loop', 1), 1 ); ?>>
							<span class="description">Loop slides infinitely.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_nav">Show Navigation Arrows</label></th>
						<td>
							<input type="checkbox" name="district_slider_nav" id="district_slider_nav" value="1" <?php checked( get_option('options_district_slider_nav', 1), 1 ); ?>>
							<span class="description">Show previous/next navigation arrows.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_items_desktop">Desktop Items Count</label></th>
						<td>
							<input type="number" name="district_slider_items_desktop" id="district_slider_items_desktop" value="<?php echo esc_attr( get_option('options_district_slider_items_desktop', 4) ); ?>" class="small-text" min="1" max="10">
							<span class="description">Number of districts to show per view on desktop (default: 4, range: 4-5).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_items_tablet">Tablet Items Count</label></th>
						<td>
							<input type="number" name="district_slider_items_tablet" id="district_slider_items_tablet" value="<?php echo esc_attr( get_option('options_district_slider_items_tablet', 3) ); ?>" class="small-text" min="1" max="10">
							<span class="description">Number of districts to show per view on tablet (default: 3).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="district_slider_items_mobile">Mobile Items Count</label></th>
						<td>
							<input type="number" name="district_slider_items_mobile" id="district_slider_items_mobile" value="<?php echo esc_attr( get_option('options_district_slider_items_mobile', 2) ); ?>" class="small-text" min="1" max="10">
							<span class="description">Number of districts to show per view on mobile (default: 2).</span>
						</td>
					</tr>
				</table>
			</div>

			<p class="submit">
				<input type="submit" name="casaview_save_settings" id="submit" class="button button-primary button-large" value="Save Settings" style="background: #c5a880; border-color: #b59870; text-shadow: none; box-shadow: none;">
			</p>
		</form>
	</div>

	<!-- Admin Drag and Drop JS -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const list = document.getElementById('layout-sortable');
		let dragEl;
		
		if (list) {
			list.addEventListener('dragstart', function(e) {
				dragEl = e.target;
				e.dataTransfer.effectAllowed = 'move';
				e.dataTransfer.setData('text/html', dragEl.innerHTML);
				dragEl.style.opacity = '0.5';
			});
			
			list.addEventListener('dragover', function(e) {
				e.preventDefault();
				e.dataTransfer.dropEffect = 'move';
				const target = e.target.closest('.casaview-layout-item');
				if (target && target !== dragEl) {
					const rect = target.getBoundingClientRect();
					const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
					list.insertBefore(dragEl, next ? target.nextSibling : target);
				}
			});
			
			list.addEventListener('dragend', function(e) {
				dragEl.style.opacity = '1';
				dragEl = null;
			});
		}

		// Dynamically update icon preview when class is edited
		const inputs = document.querySelectorAll('.icon-class-input');
		inputs.forEach(input => {
			input.addEventListener('input', function() {
				const item = this.closest('.casaview-layout-item');
				const preview = item.querySelector('.icon-preview i');
				if (preview) {
					preview.className = this.value;
				}
			});
		});

		// Font source toggle logic
		const fontTypeRadios = document.querySelectorAll('input[name="casaview_font_type"]');
		const googleRows = document.querySelectorAll('.font-row-google');
		const customRows = document.querySelectorAll('.font-row-custom');

		fontTypeRadios.forEach(radio => {
			radio.addEventListener('change', function() {
				if (this.value === 'google') {
					googleRows.forEach(row => row.style.display = '');
					customRows.forEach(row => row.style.display = 'none');
				} else {
					googleRows.forEach(row => row.style.display = 'none');
					customRows.forEach(row => row.style.display = '');
				}
			});
		});

		// Media Uploader for Custom Font
		const uploadBtn = document.getElementById('casaview_upload_font_btn');
		const fontUrlInput = document.getElementById('casaview_custom_font_url');

		if (uploadBtn && fontUrlInput) {
			uploadBtn.addEventListener('click', function(e) {
				e.preventDefault();
				let file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select or Upload Custom Font File',
					button: {
						text: 'Use Selected Font File'
					},
					multiple: false,
					library: {
						type: ['font', 'application/x-font-woff', 'application/x-font-ttf', 'font/woff', 'font/woff2', 'font/ttf', 'font/otf', 'application/vnd.ms-opentype']
					}
				});

				file_frame.on('select', function() {
					let attachment = file_frame.state().get('selection').first().toJSON();
					fontUrlInput.value = attachment.url;
				});

				file_frame.open();
			});
		}

		// Sync color input hex values display
		const colorInputs = document.querySelectorAll('input[type="color"]');
		colorInputs.forEach(input => {
			input.addEventListener('input', function() {
				const span = this.nextElementSibling;
				if (span && span.tagName === 'SPAN') {
					span.textContent = this.value;
				}
			});
		});
	});
	</script>
	<?php
}

