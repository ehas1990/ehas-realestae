<?php
/**
 * Template Name: Contact Page
 * Description: A premium, dynamic Contact Page with all contents editable via ACF fields.
 */

get_header();

/**
 * Safely parse any Google Map URL (regular link, embed iframe, coordinates, etc.)
 * and return a valid Google Maps Embed URL.
 */
if ( ! function_exists( 'casaview_get_clean_map_embed_url' ) ) {
	function casaview_get_clean_map_embed_url( $input ) {
		$input = trim( $input );
		if ( empty( $input ) ) {
			return '';
		}

		// Case 1: If it's a full <iframe> code, extract the src attribute
		if ( strpos( $input, '<iframe' ) !== false ) {
			if ( preg_match( '/src=["\']([^"\']+)["\']/', $input, $matches ) ) {
				return $matches[1];
			}
		}

		// Case 2: If it's already an embed URL format (e.g. contains /maps/embed or output=embed)
		if ( strpos( $input, '/maps/embed' ) !== false || strpos( $input, 'output=embed' ) !== false ) {
			return $input;
		}

		// Case 3: Try to parse coordinate query params in standard Google Map share link:
		// Look for @lat,lng pattern (e.g., @8.400111,77.088278)
		if ( preg_match( '/@(-?\d+\.\d+),(-?\d+\.\d+)/', $input, $matches ) ) {
			return 'https://maps.google.com/maps?q=' . $matches[1] . ',' . $matches[2] . '&z=15&output=embed';
		}

		// Look for 3dLat!4dLng pattern (e.g., 3d8.4001111!4d77.0882778)
		if ( preg_match( '/3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $input, $matches ) ) {
			return 'https://maps.google.com/maps?q=' . $matches[1] . ',' . $matches[2] . '&z=15&output=embed';
		}

		// Look for /place/Name pattern
		if ( preg_match( '/\/place\/([^\/]+)/', $input, $matches ) ) {
			$place = urldecode( $matches[1] );
			if ( strpos( $place, '/@' ) !== false ) {
				$parts = explode( '/@', $place );
				$place = $parts[0];
			}
			return 'https://maps.google.com/maps?q=' . urlencode( $place ) . '&z=15&output=embed';
		}

		// Case 4: Handle Google Maps short links (maps.app.goo.gl or goo.gl/maps)
		if ( strpos( $input, 'maps.app.goo.gl' ) !== false || strpos( $input, 'goo.gl/maps' ) !== false ) {
			$response = wp_safe_remote_head( $input, array( 'redirection' => 5, 'timeout' => 5 ) );
			if ( ! is_wp_error( $response ) ) {
				$location = wp_remote_retrieve_header( $response, 'location' );
				if ( ! empty( $location ) ) {
					return casaview_get_clean_map_embed_url( $location );
				}
			}
		}

		// Case 5: If it is a URL we didn't match, or a plain text address
		return 'https://maps.google.com/maps?q=' . urlencode( $input ) . '&z=15&output=embed';
	}
}

// Fetch ACF fields with high-end luxury defaults
$hero_title    = get_field( 'contact_hero_title' ) ?: 'Contact Us';
$hero_subtitle = get_field( 'contact_hero_subtitle' ) ?: 'Guiding You Through Every Step of Your Property Journey with Care and Clarity';
$hero_bg       = get_field( 'contact_hero_bg' ) ?: ( get_template_directory_uri() . '/assets/images/hero-default.jpg' );
$phone           = get_field( 'contact_phone' );
$phone_secondary = get_field( 'contact_phone_secondary' );

if ( empty( $phone ) && empty( $phone_secondary ) ) {
	$phone = '+971 58 583 0143';
}
$email         = get_field( 'contact_email' ) ?: 'sales@casaviewrealestate.ae, info@casaviewrealestate.ae';
$address       = get_field( 'contact_address' ) ?: 'Office 123, Luxury Business Tower, Marina, Dubai, UAE';
$hours         = get_field( 'contact_hours' ) ?: 'Monday - Saturday: 9:00 AM - 6:00 PM';
$intro_title   = get_field( 'contact_intro_title' ) ?: "We'd Love to Hear From You";
$intro_text    = get_field( 'contact_intro_text' ) ?: 'Whether you are looking to buy, sell, or rent luxury properties, our professional agents are assist you at every step.';
$cf7_shortcode = get_field( 'contact_form_shortcode' ) ?: '';
$map_iframe_raw = get_field( 'contact_map_iframe' ) ?: '';
$map_iframe     = casaview_get_clean_map_embed_url( $map_iframe_raw );
?>

<!-- Custom Premium CSS for Contact Page -->
<style>
	.contact-page-wrapper {
		background-color: #f3f2eb;
		color: #1c1d21;
		font-family: 'Manrope', sans-serif;
		padding-bottom: 80px;
	}

	/* Hero Section */
	.contact-hero {
		position: relative;
		height: 420px;
		background: url('<?php echo esc_url($hero_bg); ?>') no-repeat center center;
		background-size: cover;
		display: flex;
		align-items: center;
		padding-top: 80px; /* Offset for absolute header */
		box-sizing: border-box;
	}
	.contact-hero::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(90deg, rgba(11, 12, 16, 0.7) 0%, rgba(11, 12, 16, 0.35) 100%);
		z-index: 1;
	}
	.contact-hero-container {
		position: relative;
		z-index: 2;
		width: 100%;
	}
	.contact-hero-content {
		max-width: 800px;
		text-align: left;
	}
	.contact-hero-title {
		font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
		font-size: clamp(38px, 4.5vw, 56px) !important;
		font-weight: 400 !important;
		color: #ffffff !important;
		margin-bottom: 12px !important;
		line-height: 1.2 !important;
		letter-spacing: -0.5px !important;
		text-transform: none !important;
	}
	.contact-hero-subtitle {
		font-family: var(--font-en), sans-serif !important;
		font-size: clamp(14px, 1.8vw, 15px) !important;
		color: #ffffff !important;
		font-weight: 400 !important;
		max-width: 650px;
		line-height: 1.6 !important;
		margin-bottom: 20px !important;
		opacity: 0.95 !important;
	}
	.contact-hero-breadcrumbs {
		font-family: var(--font-en), sans-serif !important;
		font-size: clamp(13px, 1.5vw, 14px) !important;
		font-weight: 600 !important;
		color: #ffffff !important;
		letter-spacing: 0.5px !important;
	}
	.contact-hero-breadcrumbs a {
		color: #ffffff !important;
		text-decoration: none !important;
		transition: color 0.3s ease !important;
	}
	.contact-hero-breadcrumbs a:hover {
		color: var(--accent-gold) !important;
	}
	.contact-hero-breadcrumbs span.sep {
		margin: 0 8px !important;
		color: rgba(255, 255, 255, 0.6) !important;
	}
	.contact-hero-breadcrumbs span.active {
		color: #c5a880 !important;
	}

	/* Main Content Section */
	.contact-main-section {
		padding: 80px 0 40px 0;
		position: relative;
		z-index: 5;
	}
	.contact-grid {
		display: grid;
		grid-template-columns: 45% 55%;
		gap: 60px;
		align-items: start;
		max-width: 94%;
		margin: 0 auto;
	}
	
	/* Left Column: Info */
	.contact-info-column {
		display: flex;
		flex-direction: column;
	}
	.contact-intro-title {
		font-family: var(--font-title), 'Ivy Mode', 'Playfair Display', Georgia, serif !important;
		font-size: clamp(32px, 3.5vw, 44px) !important;
		line-height: 1.25 !important;
		color: #1c1d21 !important;
		margin-bottom: 20px !important;
		font-weight: 400 !important;
		text-transform: none !important;
		letter-spacing: normal !important;
	}
	.contact-intro-title span.highlight {
		color: var(--accent-gold) !important; /* Elegant gold highlight */
	}
	.contact-intro-text {
		font-family: var(--font-en), sans-serif !important;
		font-size: 15px !important;
		color: #5e6677 !important;
		line-height: 1.7 !important;
		margin-bottom: 30px !important;
	}
	
	/* How to Reach Us Box */
	.contact-reach-box {
		background-color: var(--primary-color)!important; /* Orange-red theme color */
		color: #ffffff !important;
		border-radius: 12px !important;
		padding: 35px 30px !important;
		box-shadow: 0 10px 30px rgba(240, 100, 60, 0.15) !important;
		max-width: 460px;
		width: 100%;
	}
	.contact-reach-title {
		font-family: var(--font-title), 'Ivy Mode', serif !important;
		font-size: 24px !important;
		color: #ffffff !important;
		font-weight: 400 !important;
		margin-bottom: 5px !important;
		letter-spacing: normal !important;
	}
	.reach-divider {
		height: 2px;
		background: #e5c59e; /* Soft gold accent divider line */
		width: 120px;
		margin: 15px 0 25px 0;
		opacity: 0.8;
	}
	.reach-list {
		display: flex;
		flex-direction: column;
		gap: 20px;
	}
	.reach-item {
		display: flex;
		align-items: flex-start;
		gap: 16px;
	}
	.reach-icon {
		font-size: 18px !important;
		color: #ffffff !important;
		width: 20px;
		text-align: center;
		margin-top: 2px;
	}
	.reach-content {
		flex: 1;
		font-family: var(--font-en), sans-serif !important;
		font-size: 15px !important;
		line-height: 1.5 !important;
		color: #ffffff !important;
	}
	.reach-content a {
		color: #ffffff !important;
		text-decoration: none !important;
		transition: opacity 0.3s ease !important;
	}
	.reach-content a:hover {
		opacity: 0.8 !important;
	}
	
	/* Right Column: Let's Talk Form Card */
	.contact-form-column {
		width: 100%;
	}
	.contact-form-card {
		background: #ffffff !important;
		border-radius: 16px !important;
		padding: 45px 40px !important;
		box-shadow: 0 15px 45px rgba(0, 0, 0, 0.04) !important;
		border: 1px solid rgba(0, 0, 0, 0.01) !important;
	}
	.contact-form-title {
		font-family: var(--font-title), 'Ivy Mode', serif !important;
		font-size: 28px !important;
		font-weight: 400 !important;
		color: #1c1d21 !important;
		margin-bottom: 30px !important;
		border-bottom: none !important;
		padding-bottom: 0 !important;
		letter-spacing: normal !important;
		text-transform: none !important;
	}
	
	/* Form Fields Styling (Both custom HTML form and CF7 elements) */
	.form-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px 24px;
		margin-bottom: 24px;
	}
	.form-group {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}
	.form-group.full-width {
		grid-column: span 2;
	}
	.form-group label,
	.wpcf7-form label {
		font-family: var(--font-en), sans-serif !important;
		font-size: 14px !important;
		font-weight: 600 !important;
		color: #1c1d21 !important;
		text-transform: none !important;
		letter-spacing: normal !important;
		margin-bottom: 0 !important;
		display: block !important;
	}
	.form-group input[type="text"],
	.form-group input[type="email"],
	.form-group input[type="tel"],
	.form-group textarea,
	.wpcf7-form input[type="text"],
	.wpcf7-form input[type="email"],
	.wpcf7-form input[type="tel"],
	.wpcf7-form textarea {
		width: 100% !important;
		background: #ffffff !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 8px !important;
		color: #1c1d21 !important;
		padding: 14px 16px !important;
		font-size: 14px !important;
		font-weight: 500 !important;
		outline: none !important;
		transition: all 0.3s ease !important;
		font-family: var(--font-en), sans-serif !important;
		box-sizing: border-box !important;
	}
	.form-group input::placeholder,
	.form-group textarea::placeholder,
	.wpcf7-form input::placeholder,
	.wpcf7-form textarea::placeholder {
		color: #a0aec0 !important;
	}
	.form-group input:focus,
	.form-group textarea:focus,
	.wpcf7-form input:focus,
	.wpcf7-form textarea:focus {
		border-color: var(--accent-gold) !important;
		box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.15) !important;
		background: #ffffff !important;
	}
	.form-group textarea,
	.wpcf7-form textarea {
		height: 120px !important;
		resize: none !important;
	}
	
	/* Phone Input Wrapper with Country Code Flag */
	.phone-input-wrapper {
		display: flex;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		overflow: hidden;
		background: #ffffff;
		height: 50px;
		align-items: center;
		transition: border-color 0.3s ease, box-shadow 0.3s ease;
		box-sizing: border-box;
	}
	.phone-input-wrapper:focus-within {
		border-color: var(--accent-gold);
		box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.15);
	}
	.country-prefix {
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 0 14px;
		background: #fafafa;
		border-right: 1px solid #e2e8f0;
		height: 100%;
		user-select: none;
	}
	.prefix-code {
		font-family: var(--font-en), sans-serif;
		font-size: 14px;
		font-weight: 500;
		color: #4a5568;
	}
	.phone-input-wrapper input {
		border: none !important;
		height: 100% !important;
		flex: 1;
		padding: 0 16px !important;
		outline: none !important;
		box-shadow: none !important;
		margin: 0 !important;
	}
	
	/* Submit button */
	.form-submit-wrapper {
		display: flex;
		justify-content: flex-start;
	}
	.form-submit-btn,
	.wpcf7-form input[type="submit"] {
		background: var(--primary-color) !important; /* Orange-red background */
		color: #ffffff !important;
		font-weight: 600 !important;
		font-size: 14px !important;
		text-transform: none !important;
		letter-spacing: normal !important;
		padding: 14px 28px !important;
		border-radius: 8px !important;
		border: none !important;
		cursor: pointer !important;
		transition: all 0.3s ease !important;
		margin-top: 5px !important;
		width: auto !important;
		box-sizing: border-box !important;
		display: inline-block !important;
	}
	.form-submit-btn:hover,
	.wpcf7-form input[type="submit"]:hover {
		background: #d44e27 !important;
		transform: translateY(-1px) !important;
		box-shadow: 0 6px 20px rgba(240, 100, 60, 0.25) !important;
	}
	
	/* Map Section at bottom */
	.contact-map-section {
		padding: 20px 0 80px 0;
	}
	.contact-map-container {
		border-radius: 16px !important;
		overflow: hidden;
		border: 1px solid rgba(0, 0, 0, 0.05) !important;
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
		height: 400px;
		background: #ffffff;
		width: 100%;
		max-width: 94%;
		margin: 0 auto;
	}
	.contact-map-container iframe {
		width: 100%;
		height: 100%;
		border: 0;
	}
	
	/* CF7 overrides for standard layouts */
	.wpcf7-form {
		display: flex;
		flex-direction: column;
		gap: 20px;
	}
	.wpcf7-form-control-wrap {
		display: block;
		width: 100%;
	}
	
	/* Response Messages */
	.wpcf7-response-output {
		margin: 20px 0 0 0 !important;
		padding: 12px 16px !important;
		border-radius: 8px !important;
		font-size: 13px !important;
		border: 1px solid !important;
		font-weight: 600 !important;
	}
	div.wpcf7-validation-errors {
		background: rgba(240, 100, 60, 0.1) !important;
		border-color: #f0643c !important;
		color: #f0643c !important;
	}
	div.wpcf7-mail-sent-ok {
		background: rgba(28, 187, 140, 0.1) !important;
		border-color: #1cbb8c !important;
		color: #1cbb8c !important;
	}
	span.wpcf7-not-valid-tip {
		color: #f0643c !important;
		font-size: 12px !important;
		margin-top: 5px !important;
		display: block !important;
	}

	/* Responsive grid scaling */
	@media (max-width: 991px) {
		.contact-grid {
			grid-template-columns: 1fr;
			gap: 50px;
			max-width: 100%;
		}
		.contact-reach-box {
			max-width: 100%;
		}
		.form-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}
		.form-group.full-width {
			grid-column: span 1;
		}
		.contact-form-card {
			padding: 30px 24px !important;
		}
		.contact-map-container {
			max-width: 100%;
			height: 300px;
		}
	}
</style>

<div class="contact-page-wrapper">
	<!-- 1. Hero Section -->
	<div class="contact-hero">
		<div class="container contact-hero-container">
			<div class="contact-hero-content">
				<h1 class="contact-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
				<p class="contact-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
				<div class="contact-hero-breadcrumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
					<span class="sep">/</span>
					<span class="active">Contact</span>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Rebuilt Information & Form Section (Matches Attachment) -->
	<div class="contact-main-section">
		<div class="container contact-main-container">
			<div class="contact-grid">
				
				<!-- Left Column: Title, Description, and Reach Box -->
				<div class="contact-info-column">
					<h2 class="contact-intro-title">
						<?php
						// Formats "Perfect" and "Home" with gold color highlights
						$formatted_title = str_ireplace(
							array( 'Perfect', 'Home' ),
							array( '<span class="highlight">Perfect</span>', '<span class="highlight">Home</span>' ),
							esc_html( $intro_title )
						);
						echo $formatted_title;
						?>
					</h2>
					<p class="contact-intro-text"><?php echo nl2br( esc_html( $intro_text ) ); ?></p>
					
					<div class="contact-reach-box">
						<h3 class="contact-reach-title">How to Reach Us</h3>
						<div class="reach-divider"></div>
						
						<div class="reach-list">
							<?php if ( ! empty( $email ) ) : ?>
								<div class="reach-item">
									<div class="reach-icon">
										<i class="fa-regular fa-envelope"></i>
									</div>
									<div class="reach-content">
										<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
									</div>
								</div>
							<?php endif; ?>
							
							<?php 
							$has_primary = ! empty( $phone );
							$has_secondary = ! empty( $phone_secondary );
							if ( $has_primary || $has_secondary ) : 
							?>
								<div class="reach-item">
									<div class="reach-icon">
										<i class="fa-solid fa-phone"></i>
									</div>
									<div class="reach-content">
										<?php if ( $has_primary && $has_secondary ) : ?>
											<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
											<span class="sep">/</span>
											<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_secondary ) ); ?>"><?php echo esc_html( $phone_secondary ); ?></a>
										<?php elseif ( $has_primary ) : ?>
											<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
										<?php else : ?>
											<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_secondary ) ); ?>"><?php echo esc_html( $phone_secondary ); ?></a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							
							<?php if ( ! empty( $address ) ) : ?>
								<div class="reach-item">
									<div class="reach-icon">
										<i class="fa-solid fa-location-dot"></i>
									</div>
									<div class="reach-content">
										<span><?php echo esc_html( $address ); ?></span>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
				<!-- Right Column: "Let's Talk" Form Card -->
				<div class="contact-form-column">
					<div class="contact-form-card">
						<h3 class="contact-form-title">Let's Talk</h3>
						
						<?php 
						if ( ! empty( $cf7_shortcode ) ) {
							echo do_shortcode( $cf7_shortcode );
						} else {
							// Render the exact HTML form from the mockup as default/fallback
							?>
							<form class="custom-contact-form" action="" method="post">
								<div class="form-grid">
									<div class="form-group">
										<label for="form-name">Name</label>
										<input type="text" id="form-name" name="your-name" placeholder="Name" required>
									</div>
									<div class="form-group">
										<label for="form-email">Email</label>
										<input type="email" id="form-email" name="your-email" placeholder="Email" required>
									</div>
									<div class="form-group">
										<label for="form-address">Address</label>
										<input type="text" id="form-address" name="your-address" placeholder="Address">
									</div>
									<div class="form-group">
										<label for="form-phone">Phone</label>
										<div class="phone-input-wrapper">
											<div class="country-prefix">
												<svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="border: 1px solid #eaeaea; display: block;">
													<rect width="5" height="12" fill="#E31B23"/>
													<rect x="5" width="15" height="4" fill="#00732F"/>
													<rect x="5" y="4" width="15" height="4" fill="#FFFFFF"/>
													<rect x="5" y="8" width="15" height="4" fill="#000000"/>
												</svg>
												<span class="prefix-code">+971</span>
											</div>
											<input type="tel" id="form-phone" name="your-phone" placeholder="Phone" required>
										</div>
									</div>
									<div class="form-group full-width">
										<label for="form-message">Message</label>
										<textarea id="form-message" name="your-message" placeholder="Message" required></textarea>
									</div>
								</div>
								<div class="form-submit-wrapper">
									<button type="submit" class="form-submit-btn">Send Message</button>
								</div>
							</form>
							<?php
						}
						?>
					</div>
				</div>
				
			</div>
		</div>
	</div>

	<!-- 3. Google Map Section (Full Width, Mapped from ACF, Safe Fallback) -->
	<?php if ( ! empty( $map_iframe ) ) : ?>
		<div class="contact-map-section">
			<div class="container">
				<div class="contact-map-container">
					<iframe 
						src="<?php echo esc_url( $map_iframe ); ?>" 
						allowfullscreen="" 
						loading="lazy" 
						referrerpolicy="no-referrer-when-downgrade">
					</iframe>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();

