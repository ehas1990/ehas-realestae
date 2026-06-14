<?php
/**
 * Elementor Advanced Search Widget
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Elementor_Search_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rem_advanced_search';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Real Estate Search & Filter', 'custom-real-estate-manager' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		// No content controls needed since the markup is standard
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		if ( file_exists( CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php' ) ) {
			include CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php';
		}
	}
}
