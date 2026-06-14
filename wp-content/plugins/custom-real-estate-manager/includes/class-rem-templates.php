<?php
/**
 * Handles Custom Template Loading
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Templates {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'load_templates' ), 99 );
	}

	/**
	 * Hook template redirections for Property Single and Archive.
	 */
	public function load_templates( $template ) {
		// 1. Single Property Template
		if ( is_singular( 'property' ) ) {
			// Check if active theme has single-property.php first
			$theme_file = locate_template( array( 'single-property.php' ) );
			if ( ! $theme_file ) {
				// Fallback to plugin's single-property.php template
				return CREM_PLUGIN_DIR . 'templates/single-property.php';
			}
		}

		// 2. Archive Property Template
		if ( is_post_type_archive( 'property' ) ) {
			// Check if active theme has archive-property.php first
			$theme_file = locate_template( array( 'archive-property.php' ) );
			if ( ! $theme_file ) {
				// Fallback to plugin's archive-property.php template
				return CREM_PLUGIN_DIR . 'templates/archive-property.php';
			}
		}

		return $template;
	}
}
