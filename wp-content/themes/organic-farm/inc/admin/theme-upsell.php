<?php
/**
 * Theme Get Started / Upsell Page
 *
 * @package ORGANIC_FARM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add theme page to admin menu
 */
add_action( 'admin_menu', 'organic_farm_add_theme_page' );
function organic_farm_add_theme_page() {
    add_theme_page(
        __( 'Upgrade to PRO', 'organic-farm' ),
        __( 'Upgrade to PRO', 'organic-farm' ),
        'manage_options',
        'organic-farm-pro',
        'organic_farm_pro_page_callback'
    );
}

/**
 * Render theme Get Started page
 */
function organic_farm_pro_page_callback() {
	$organic_farm_theme_name_clean = strtolower( preg_replace( '#[^a-zA-Z]#', '', wp_get_theme()->get( 'Name' ) ) );
	$organic_farm_wizard_page_slug = apply_filters( $organic_farm_theme_name_clean . '_theme_setup_wizard_organic_farm_page_slug', $organic_farm_theme_name_clean . '-wizard' );
	$organic_farm_demo_url         = admin_url( 'themes.php?page=' . $organic_farm_wizard_page_slug );
	?>
	<div class="wrap ot-pro-wrap">
		<h1><?php esc_html_e( 'Get Started with Organic Farm 🚀', 'organic-farm' ); ?></h1>

		<div class="ot-pro-hero">
			<div class="hero-content">
				<div class="hero-left">
					<h2><?php esc_html_e( 'Build Your Professional Website Today', 'organic-farm' ); ?></h2>
					<p class="subtitle"><?php esc_html_e( 'Get access to premium features, advanced layouts, demo import, and priority support', 'organic-farm' ); ?></p>
					<div class="button-group">
						<a class="button button-hero theme-install" href="<?php echo esc_url( $organic_farm_demo_url ); ?>">
							<span class="dashicons dashicons-download"></span>
							<?php esc_html_e( 'Demo Import', 'organic-farm' ); ?>
						</a>
						<a href="<?php echo esc_url( ORGANIC_FARM_LIVE_DEMO ); ?>" target="_blank" class="button button-hero button-demo">
							<span class="dashicons dashicons-visibility"></span>
							<?php esc_html_e( 'Live Demo', 'organic-farm' ); ?>
						</a>
						<a href="<?php echo esc_url( ORGANIC_FARM_BUY_PRO ); ?>" target="_blank" class="button button-primary button-hero button-pro">
							<span class="dashicons dashicons-star-filled"></span>
							<?php esc_html_e( 'Get Pro Theme', 'organic-farm' ); ?>
						</a>
						<a href="<?php echo esc_url( ORGANIC_FARM_FREE_DOC ); ?>" target="_blank" class="button button-hero button-docs">
							<span class="dashicons dashicons-book"></span>
							<?php esc_html_e( 'Documentation', 'organic-farm' ); ?>
						</a>
						<a href="<?php echo esc_url( ORGANIC_FARM_BUNDLE_LINK ); ?>" target="_blank" class="button button-hero button-bundle">
							<span class="dashicons dashicons-cart"></span>
							<?php esc_html_e( 'WordPress Theme Bundle', 'organic-farm' ); ?>
						</a>
					</div>
				</div>
				<div class="hero-right">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/screenshot.png" alt="<?php esc_attr_e( 'Organic Farm Theme Screenshot', 'organic-farm' ); ?>" class="theme-screenshot">
				</div>
			</div>
		</div>

		<div class="ot-pro-features">
			<h2><?php esc_html_e( 'Why Upgrade to Pro?', 'organic-farm' ); ?></h2>

			<div class="feature-grid">
				<div class="feature-box">
					<span class="dashicons dashicons-layout"></span>
					<h3><?php esc_html_e( 'Different Styling Options', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Choose from multiple color schemes and styling options to match your brand identity.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-admin-customizer"></span>
					<h3><?php esc_html_e( 'Section Reordering Option', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Rearrange homepage sections in any order to best showcase your services.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-editor-table"></span>
					<h3><?php esc_html_e( 'Footer Builder', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Create custom footers with advanced widgets and flexible column layouts.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-art"></span>
					<h3><?php esc_html_e( 'Typography Controls', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Full control over fonts, sizes, and text styling across all sections of your site.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-cart"></span>
					<h3><?php esc_html_e( 'WooCommerce Styling', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Advanced WooCommerce integration with custom product and service page layouts.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-admin-tools"></span>
					<h3><?php esc_html_e( 'Advanced Options', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Access advanced theme settings to achieve greater customization and control.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-performance"></span>
					<h3><?php esc_html_e( 'Performance Optimized', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( '3X faster loading with optimized code, minified assets, and clean markup.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-sos"></span>
					<h3><?php esc_html_e( 'Priority Support', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Get expert help within 24 hours through our dedicated priority support system.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-admin-appearance"></span>
					<h3><?php esc_html_e( 'Unlimited Color Schemes', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Customize every color to match your brand identity with unlimited color options.', 'organic-farm' ); ?></p>
				</div>

				<div class="feature-box">
					<span class="dashicons dashicons-download"></span>
					<h3><?php esc_html_e( 'One-Click Demo Import', 'organic-farm' ); ?></h3>
					<p><?php esc_html_e( 'Import the complete demo content with one click and get your site ready instantly.', 'organic-farm' ); ?></p>
				</div>
			</div>
		</div>

		<div class="ot-pro-comparison">
			<h2><?php esc_html_e( 'Free vs Pro Comparison', 'organic-farm' ); ?></h2>

			<table class="comparison-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Feature', 'organic-farm' ); ?></th>
						<th><?php esc_html_e( 'Free', 'organic-farm' ); ?></th>
						<th class="pro-col"><?php esc_html_e( 'Pro', 'organic-farm' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'WordPress Customizer Support', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Demo Importer', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Responsive Design', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Color Options', 'organic-farm' ); ?></td>
						<td><?php esc_html_e( 'Limited', 'organic-farm' ); ?></td>
						<td class="pro-col"><?php esc_html_e( 'Unlimited', 'organic-farm' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Typography Controls (Heading &amp; Body Font)', 'organic-farm' ); ?></td>
						<td><?php esc_html_e( 'Basic', 'organic-farm' ); ?></td>
						<td class="pro-col"><?php esc_html_e( 'Advanced', 'organic-farm' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Sticky Header', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Preloader / Loader', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Scroll-to-Top Button', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Homepage Slider Section', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Social Media Links', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'WooCommerce Support', 'organic-farm' ); ?></td>
						<td><?php esc_html_e( 'Basic', 'organic-farm' ); ?></td>
						<td class="pro-col"><?php esc_html_e( 'Advanced Styling', 'organic-farm' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Section Reordering', 'organic-farm' ); ?></td>
						<td>&#10060;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Boxed / Full-Width Layout', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Left / Right Sidebar', 'organic-farm' ); ?></td>
						<td>&#9989;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Priority Support', 'organic-farm' ); ?></td>
						<td>&#10060;</td>
						<td class="pro-col">&#9989;</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="ot-pro-testimonials">
			<h2><?php esc_html_e( 'What Our Users Say', 'organic-farm' ); ?></h2>

			<div class="testimonial-grid">
				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"I was looking for a clean and professional theme for my business and this theme delivered exactly that. Setup was quick and the layout looks very modern."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- John D.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"The theme design is professional and easy to customize. The documentation helped me set up my Organic Farm website without any issues."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Sarah M.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"Very flexible and beginner-friendly. I was able to adjust colors, sections, and layouts directly from the Customizer. Highly recommended."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Michael R.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"The mobile responsive design works perfectly. Most of my clients visit from their phones, and the site looks clean and professional."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Emily T.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"Great theme for professionals. The service sections and homepage layout helped me present my services clearly to potential clients."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- David L.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"Customer support is very helpful and responsive. They guided me during setup and solved my issue quickly."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Jennifer K.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"Fast loading and SEO friendly. After launching my website with this theme, I started receiving more inquiries from clients."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Robert H.', 'organic-farm' ); ?></span>
				</div>

				<div class="testimonial-box">
					<div class="stars">&#11088;&#11088;&#11088;&#11088;</div>
					<p><?php esc_html_e( '"A very good theme with useful features for Organic Farm businesses. Easy to install and the demo import was a real time-saver."', 'organic-farm' ); ?></p>
					<span class="author"><?php esc_html_e( '- Lisa P.', 'organic-farm' ); ?></span>
				</div>
			</div>
		</div>

		<div class="ot-pro-cta">
			<h2><?php esc_html_e( 'Ready to Upgrade?', 'organic-farm' ); ?></h2>
			<p><?php esc_html_e( 'Join hundreds of satisfied customers who upgraded to Pro', 'organic-farm' ); ?></p>
			<?php
			$organic_farm_theme = wp_get_theme();
			$organic_farm_theme_name = $organic_farm_theme->get( 'Name' );
			?>

			<a href="<?php echo esc_url( ORGANIC_FARM_BUY_PRO ); ?>" target="_blank" class="button button-primary button-hero">
				<?php echo esc_html( sprintf( __( 'Get %s Pro Now', 'organic-farm' ), $organic_farm_theme_name ) ); ?> &rarr;
			</a>
		</div>

		<div class="ot-pro-footer">
			<p>
				<?php
				printf(
					/* translators: %s: Support URL */
					__( 'Need help? Contact our <a href="%s" target="_blank">support</a> team anytime.', 'organic-farm' ),
					esc_url( ORGANIC_FARM_SUPPORT )
				);
				?>
			</p>
		</div>
	</div>
	<?php
}
