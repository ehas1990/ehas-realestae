<?php

	require get_template_directory() . '/inc/dashboard/tgm/class-tgm-plugin-activation.php';
/**
 * Recommended plugins.
 */
function organic_farm_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'Ovation Elements', 'organic-farm' ),
			'slug'             => 'ovation-elements',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __( 'WooCommerce', 'organic-farm' ),
			'slug'             => 'woocommerce',
			'required'         => false,
			'force_activation' => false,
		)
		

	);
	$config = array();
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'organic_farm_register_recommended_plugins' );