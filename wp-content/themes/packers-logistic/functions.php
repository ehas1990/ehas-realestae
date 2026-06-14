<?php
/**
 * Packers Logistic functions and definitions
 *
 * @package packers-logistic
 */

/* Theme Setup */
if ( ! function_exists( 'PACKERS_LOGISTIC_SUPPORT' ) ) :

	function PACKERS_LOGISTIC_SUPPORT() {

		load_theme_textdomain( 'packers-logistic', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'experimental-link-color' );

		add_editor_style( 'style.css' );
	}

endif;
add_action( 'after_setup_theme', 'PACKERS_LOGISTIC_SUPPORT' );


/* Enqueue Styles */
if ( ! function_exists( 'packers_logistic_styles' ) ) :

	function packers_logistic_styles() {

		$packers_logistic_theme_version = wp_get_theme()->get( 'Version' );
		$packers_logistic_theme_uri = get_template_directory_uri();

		// Main stylesheet
		wp_enqueue_style(
			'packers-logistic-style',
			$packers_logistic_theme_uri . '/style.css',
			array(),
			$packers_logistic_theme_version
		);

		wp_style_add_data( 'packers-logistic-style', 'rtl', 'replace' );

		// Extra styles
		wp_enqueue_style( 'animate-css', $packers_logistic_theme_uri . '/assets/css/animate.css', array(), '1.0' );

		wp_enqueue_style( 'dashicons' );

		wp_enqueue_style( 'fontawesome', $packers_logistic_theme_uri . '/inc/fontawesome/css/all.css', array(), '6.7.0' );

		wp_enqueue_style( 'owl-carousel-style', $packers_logistic_theme_uri . '/assets/css/owl-carousel.css', array(), '2.3.4' );

		wp_enqueue_style( 'swiper-css', $packers_logistic_theme_uri . '/assets/css/swiper-bundle.css', array(), '10.0' );
	}

endif;
add_action( 'wp_enqueue_scripts', 'packers_logistic_styles' );


/* Enqueue Scripts */
function packers_logistic_scripts() {

	$packers_logistic_theme_uri = get_template_directory_uri();

	// WOW JS
	wp_enqueue_script(
		'wow',
		$packers_logistic_theme_uri . '/assets/js/wow.js',
		array('jquery'),
		'1.0',
		true
	);

	// Custom JS
	wp_enqueue_script(
		'packers-logistic-custom',
		$packers_logistic_theme_uri . '/assets/js/custom.js',
		array('jquery'),
		'1.0',
		true
	);

	// Scroll to top
	wp_enqueue_script(
		'packers-logistic-scroll-to-top',
		$packers_logistic_theme_uri . '/assets/js/scroll-to-top.js',
		array(),
		'1.0',
		true
	);

	// Swiper JS
	wp_enqueue_script(
		'swiper-js',
		$packers_logistic_theme_uri . '/assets/js/swiper-bundle.js',
		array(),
		'10.0',
		true
	);

	// Owl Carousel
	wp_enqueue_script(
		'owl-carousel-js',
		$packers_logistic_theme_uri . '/assets/js/owl-carousel.js',
		array('jquery'),
		'2.3.4',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'packers_logistic_scripts' );

/* Enqueue admin-notice-script js */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'appearance_page_packers-logistic') return;

    wp_enqueue_script('admin-notice-script', get_template_directory_uri() . '/get-started/js/admin-notice-script.js', ['jquery'], null, true);
    wp_localize_script('admin-notice-script', 'pluginInstallerData', [
        'ajaxurl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('install_plugin_nonce'),
        'redirectUrl' => admin_url('themes.php?page=packers-logistic'),
    ]);
});

add_action('wp_ajax_check_plugin_activation', function () {
    if (!isset($_POST['plugin']) || empty($_POST['plugin'])) {
        wp_send_json_error(['message' => 'Missing plugin identifier']);
    }

    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    // Map plugin identifiers to their main files
    $packers_logistic_plugin_map = [
        'woocommerce'          => 'woocommerce/woocommerce.php',
        'wordclever_ai_content_writer'    => 'wordclever-ai-content-writer/wordclever.php',
    ];

    $packers_logistic_requested_plugin = sanitize_text_field($_POST['plugin']);

    if (!isset($packers_logistic_plugin_map[$packers_logistic_requested_plugin])) {
        wp_send_json_error(['message' => 'Invalid plugin']);
    }

    $packers_logistic_plugin_file = $packers_logistic_plugin_map[$packers_logistic_requested_plugin];
    $packers_logistic_is_active   = is_plugin_active($packers_logistic_plugin_file);

    wp_send_json_success(['active' => $packers_logistic_is_active]);
});

add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );

function packers_logistic_theme_setting() {
	
// Add block patterns
require get_template_directory() . '/inc/block-pattern.php';

// Add block Style
require get_template_directory() . '/inc/block-style.php';

// TGM
require get_template_directory() . '/inc/tgm/plugin-activation.php';

// Get Started
require get_template_directory() . '/get-started/getstart.php';

// Get Notice
require get_template_directory() . '/get-started/notice.php';

// Customizer
require get_template_directory() . '/inc/customizer.php';

}
add_action('after_setup_theme', 'packers_logistic_theme_setting');