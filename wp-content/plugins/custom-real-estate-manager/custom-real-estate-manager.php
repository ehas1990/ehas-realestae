<?php
/**
 * Plugin Name: Real Estate Management System (Custom)
 * Description: A professional Real Estate Management System with custom post types, dynamic cascading location selection via AJAX, custom taxonomies, programmatically registered ACF Pro fields, and premium templates.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: custom-real-estate-manager
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants.
define( 'CREM_VERSION', '1.0.0' );
define( 'CREM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CREM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CREM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Include the core plugin class.
if ( file_exists( CREM_PLUGIN_DIR . 'includes/class-rem.php' ) ) {
	require_once CREM_PLUGIN_DIR . 'includes/class-rem.php';
}

/**
 * Main instance of Real Estate Manager.
 *
 * @return RealEstateManager
 */
function CREM() {
	return RealEstateManager::instance();
}

// Run the plugin.
add_action( 'plugins_loaded', 'CREM' );

/**
 * Format property prices in short Indian currency format (e.g. 25K, 25 Lakh, 1.2 Crore)
 *
 * @param float|int $price   Property price value.
 * @param int|null  $post_id Optional post ID to check transaction status (Sale vs Rent).
 * @return string Formatted price string.
 */
function rem_format_property_price( $price, $post_id = null ) {
	if ( ! $price ) {
		return '₹ 0';
	}

	$price = floatval( $price );

	// Determine transaction type (Sale vs. Rent)
	$is_rent = false;
	if ( $post_id ) {
		$status = get_field( 'property_status', $post_id );
		if ( 'for_rent' === $status || 'rented' === $status ) {
			$is_rent = true;
		}
	}

	$formatted = '';
	if ( $price >= 10000000 ) {
		// Crore
		$val = $price / 10000000;
		$val = round( $val, 1 );
		if ( (int) $val == $val ) {
			$val = (int) $val;
		}
		$formatted = $val . ' Crore';
	} elseif ( $price >= 100000 ) {
		// Lakh
		$val = $price / 100000;
		$val = round( $val, 1 );
		if ( (int) $val == $val ) {
			$val = (int) $val;
		}
		$formatted = $val . ' Lakh';
	} elseif ( $price >= 1000 ) {
		// Thousand
		$val = $price / 1000;
		$val = round( $val, 1 );
		if ( (int) $val == $val ) {
			$val = (int) $val;
		}
		if ( $is_rent ) {
			$formatted = $val . 'K'; // No space for rent
		} else {
			$formatted = $val . ' K'; // Space for sale
		}
	} else {
		// Less than 1000
		$formatted = $price;
	}

	$result = '₹ ' . $formatted;
	if ( $is_rent ) {
		$result .= ' / Month';
	}

	return $result;
}

