<?php
/**
 * Customizer
 * 
 * @package WordPress
 * @subpackage packers-logistic
 * @since packers-logistic 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function packers_logistic_customize_register( $wp_customize ) {
	$wp_customize->add_section( new Packers_Logistic_Upsell_Section($wp_customize,'upsell_section',array(
		'title'            => __( 'Packers Logistic Pro', 'packers-logistic' ),
		'button_text'      => __( 'Upgrade Pro', 'packers-logistic' ),
		'url'              => 'https://www.wpradiant.net/products/packers-and-movers-wordpress-theme',
		'priority'         => 9,
	)));
}
add_action( 'customize_register', 'packers_logistic_customize_register',11 );

/**
 * Enqueue script for custom customize control.
 */
function packers_logistic_custom_control_scripts() {
	wp_enqueue_script( 'packers-logistic-custom-controls-js', get_template_directory_uri() . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0', true );
	wp_enqueue_style( 'packers-logistic-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'packers_logistic_custom_control_scripts' );