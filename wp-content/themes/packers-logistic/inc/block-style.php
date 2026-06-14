<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package WordPress
 * @subpackage packers-logistic
 * @since packers-logistic 1.0
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 *
	 * @since packers-logistic 1.0
	 *
	 * @return void
	 */
	function packers_logistic_register_block_styles() {
		

		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'packers-logistic-border',
				'label' => esc_html__( 'Borders', 'packers-logistic' ),
			)
		);

		
	}
	add_action( 'init', 'packers_logistic_register_block_styles' );
}