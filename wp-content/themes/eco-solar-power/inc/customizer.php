<?php
/**
 * Eco Solar Power: Customizer
 *
 * @subpackage Eco Solar Power
 * @since 1.0
 */

use WPTRT\Customize\Section\Luzuk_Eco_Solar_Power_Button;

add_action( 'customize_register', function( $manager ) {

	$manager->register_section_type( Luzuk_Eco_Solar_Power_Button::class );

	$manager->add_section(
		new Luzuk_Eco_Solar_Power_Button( $manager, 'luzuk_eco_solar_power_pro', [
			'title' => __( 'Eco Solar Power Pro', 'eco-solar-power' ),
			'priority' => 0,
			'button_text' => __( 'Go Pro', 'eco-solar-power' ),
			'button_url'  => esc_url( 'https://luzuk.com/products/premium-eco-solar-power-wordpress-theme/', 'eco-solar-power')
		] )
	);

} );


// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'eco-solar-power-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
		[ 'customize-controls' ],
		$version,
		true
	);

	wp_enqueue_style(
		'eco-solar-power-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
		[ 'customize-controls' ],
 		$version
	);

} );

function luzuk_eco_solar_power_customize_register( $wp_customize ) {

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_size',array(
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_logo_size',array(
		'type' => 'range',
		'label' => __('Logo Size','eco-solar-power'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_padding',array(
		'sanitize_callback'	=> 'esc_html'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_logo_padding',array(
		'label' => __('Logo Margin','eco-solar-power'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_top_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_logo_top_padding',array(
		'type' => 'number',
		'description' => __('Top','eco-solar-power'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_bottom_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_logo_bottom_padding',array(
		'type' => 'number',
		'description' => __('Bottom','eco-solar-power'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_left_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_logo_left_padding',array(
		'type' => 'number',
		'description' => __('Left','eco-solar-power'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_logo_right_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
 	));
 	$wp_customize->add_control('luzuk_eco_solar_power_logo_right_padding',array(
		'type' => 'number',
		'description' => __('Right','eco-solar-power'),
		'section' => 'title_tagline',
    ));

	$wp_customize->add_setting('luzuk_eco_solar_power_show_site_title',array(
		'default' => true,
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_checkbox'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_show_site_title',array(
		'type' => 'checkbox',
		'label' => __('Show / Hide Site Title','eco-solar-power'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_site_title_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_site_title_color', array(
		'label' => 'Title Color',
		'section' => 'title_tagline',
	)));

	$wp_customize->add_setting('luzuk_eco_solar_power_show_tagline',array(
		'default' => true,
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_checkbox'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_show_tagline',array(
		'type' => 'checkbox',
		'label' => __('Show / Hide Site Tagline','eco-solar-power'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_site_tagline_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_site_tagline_color', array(
		'label' => 'Tagline Color',
		'section' => 'title_tagline',
	)));

	$wp_customize->add_panel( 'luzuk_eco_solar_power_panel_id', array(
		'priority' => 10,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Theme Settings', 'eco-solar-power' ),
		'description' => __( 'Description of what this panel does.', 'eco-solar-power' ),
	) );

	$wp_customize->add_section( 'luzuk_eco_solar_power_theme_options_section', array(
    	'title'      => __( 'General Settings', 'eco-solar-power' ),
		'priority'   => 30,
		'panel' => 'luzuk_eco_solar_power_panel_id'
	) );

	$wp_customize->add_setting('luzuk_eco_solar_power_theme_options',array(
		'default' => 'One Column',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_theme_options',array(
		'type' => 'select',
		'label' => __('Blog Page Sidebar Layout','eco-solar-power'),
		'section' => 'luzuk_eco_solar_power_theme_options_section',
		'choices' => array(
		   'Left Sidebar' => __('Left Sidebar','eco-solar-power'),
		   'Right Sidebar' => __('Right Sidebar','eco-solar-power'),
		   'One Column' => __('One Column','eco-solar-power'),
		   'Grid Layout' => __('Grid Layout','eco-solar-power')
		),
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_single_post_sidebar',array(
		'default' => 'Right Sidebar',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_single_post_sidebar',array(
        'type' => 'select',
        'label' => __('Single Post Sidebar Layout','eco-solar-power'),
        'section' => 'luzuk_eco_solar_power_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','eco-solar-power'),
            'Right Sidebar' => __('Right Sidebar','eco-solar-power'),
            'One Column' => __('One Column','eco-solar-power')
        ),
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_page_sidebar',array(
		'default' => 'One Column',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_page_sidebar',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','eco-solar-power'),
        'section' => 'luzuk_eco_solar_power_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','eco-solar-power'),
            'Right Sidebar' => __('Right Sidebar','eco-solar-power'),
            'One Column' => __('One Column','eco-solar-power')
        ),
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_archive_page_sidebar',array(
		'default' => 'One Column',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_archive_page_sidebar',array(
        'type' => 'select',
        'label' => __('Archive & Search Page Sidebar Layout','eco-solar-power'),
        'section' => 'luzuk_eco_solar_power_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','eco-solar-power'),
            'Right Sidebar' => __('Right Sidebar','eco-solar-power'),
            'One Column' => __('One Column','eco-solar-power'),
            'Grid Layout' => __('Grid Layout','eco-solar-power')
        ),
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_boxfull_width', array(
		'default'           => '',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	
	$wp_customize->add_control( 'luzuk_eco_solar_power_boxfull_width', array(
		'label'    => __( 'Section Width', 'eco-solar-power' ),
		'section'  => 'luzuk_eco_solar_power_theme_options_section',
		'type'     => 'select',
		'choices'  => array(
			'container'  => __('Box Width', 'eco-solar-power'),
			'container-fluid' => __('Full Width', 'eco-solar-power'),
			'none' => __('None', 'eco-solar-power')
		),
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_dropdown_anim', array(
		'default'           => 'None',
		'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_choices'
	));
	$wp_customize->add_control( 'luzuk_eco_solar_power_dropdown_anim', array(
		'label'    => __( 'Menu Dropdown Animations', 'eco-solar-power' ),
		'section'  => 'luzuk_eco_solar_power_theme_options_section',
		'type'     => 'select',
		'choices'  => array(
			'bounceInUp'  => __('bounceInUp', 'eco-solar-power'),
			'fadeInUp' => __('fadeInUp', 'eco-solar-power'),
			'zoomIn'    => __('zoomIn', 'eco-solar-power'),
			'None'    => __('None', 'eco-solar-power')
		),
	));
 
	//Header
	$wp_customize->add_section( 'luzuk_eco_solar_power_header' , array(
    	'title'    => __( 'Header Settings', 'eco-solar-power' ),
		'priority' => null,
		'panel' => 'luzuk_eco_solar_power_panel_id'
	) );
	
	$wp_customize->add_setting('luzuk_eco_solar_power_header_mail',array(
		'default' => 'info@example.com',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_header_mail',array(
	   	'type' => 'text',
	   	'label' => __('Mail','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_header_address',array(
		'default' => 'JI. Raya Puputan No 142, NY - 80234',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_header_address',array(
	   	'type' => 'text',
	   	'label' => __('Phone Number','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));
	
	$wp_customize->add_setting('luzuk_eco_solar_power_fblink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_fblink',array(
	   	'type' => 'url',
	   	'label' => __('Facebook Icon Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));
	
	$wp_customize->add_setting('luzuk_eco_solar_power_instagramlink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_instagramlink',array(
	   	'type' => 'url',
	   	'label' => __('Instagram Icon Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));

	
	$wp_customize->add_setting('luzuk_eco_solar_power_twitterlink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_twitterlink',array(
	   	'type' => 'url',
	   	'label' => __('Twitter Icon Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_linkedinlink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_linkedinlink',array(
	   	'type' => 'url',
	   	'label' => __('Linkedin Icon Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));
	
	$wp_customize->add_setting('luzuk_eco_solar_power_headerbtntext',array(
    	'default' => 'Contact Us',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_headerbtntext',array(
	   	'type' => 'text',
	   	'label' => __('Button Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_headerbtnlink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('luzuk_eco_solar_power_headerbtnlink',array(
	   	'type' => 'url',
	   	'label' => __('Button Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_header',
	));


	$wp_customize->add_setting( 'luzuk_eco_solar_power_headermailaddress_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headermailaddress_color', array(
		'label' => 'Mail & Address Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_headermailaddressicons_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headermailaddressicons_color', array(
		'label' => 'Mail & Address Icons Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_headermailhover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headermailhover_color', array(
		'label' => 'Mail Hover Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));
	
	$wp_customize->add_setting( 'luzuk_eco_solar_power_headertopsocialicon_col', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headertopsocialicon_col', array(
		'label' => 'Social Icon Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_headertopsocialiconhover_col', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headertopsocialiconhover_col', array(
		'label' => 'Social Icon Hover Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_headertopmbg_col', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headertopmbg_col', array(
		'label' => 'Top Header BG Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));


	$wp_customize->add_setting( 'luzuk_eco_solar_power_headerbottombg_col', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_headerbottombg_col', array(
		'label' => 'Header BG Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_menu_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_menu_color', array(
		'label' => 'Menu Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_menuhover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_menuhover_color', array(
		'label' => 'Menu Hover Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));


	$wp_customize->add_setting( 'luzuk_eco_solar_power_submenu_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_submenu_color', array(
		'label' => 'Submenu Text Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_submenubg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_submenubg_color', array(
		'label' => 'Submenu BG Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_header_btntext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_header_btntext_color', array(
		'label' => 'Button Text Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_header_btnbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_header_btnbg_color', array(
		'label' => 'Button BG Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_header_btntexthover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_header_btntexthover_color', array(
		'label' => 'Button Text Hover Color',
		'section' => 'luzuk_eco_solar_power_header',
	)));


	//home page slider
	$wp_customize->add_section( 'luzuk_eco_solar_power_slider_section' , array(
    	'title'    => __( 'Slider Settings', 'eco-solar-power' ),
		'description'=> __('<b>Note :</b> Please Add Image in 1440*900 Ratio.','eco-solar-power'),
		'priority' => null,
		'panel' => 'luzuk_eco_solar_power_panel_id'
	) );

	$wp_customize->add_setting('luzuk_eco_solar_power_slider_hide_show',array(
    	'default' => false,
    	'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_checkbox'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_slider_hide_show',array(
	   	'type' => 'checkbox',
	   	'label' => __('Show / Hide Slider','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_slider_section',
	));


	for ( $count = 1; $count <= 4; $count++ ) {
		$wp_customize->add_setting( 'luzuk_eco_solar_power_slider' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'luzuk_eco_solar_power_sanitize_dropdown_pages'
		));
		$wp_customize->add_control( 'luzuk_eco_solar_power_slider' . $count, array(
			'label' => __('Select A Page', 'eco-solar-power' ),
			'section' => 'luzuk_eco_solar_power_slider_section',
			'type' => 'dropdown-pages'
		));
	}

	$wp_customize->add_setting('luzuk_eco_solar_power_sliderbtntext',array(
    	'default' => 'Book a Call',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_sliderbtntext',array(
	   	'type' => 'text',
	   	'label' => __('Button 1 Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_slider_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_sliderbtnlink',array(
    	'default' => 'Book a Call',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_sliderbtnlink',array(
	   	'type' => 'url',
	   	'label' => __('Button 1 Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_slider_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_sliderbtn2text',array(
    	'default' => 'Explore Our Services',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_sliderbtn2text',array(
	   	'type' => 'text',
	   	'label' => __('Button 2 Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_slider_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_sliderbtn2link',array(
    	'default' => 'Explore Our Services',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_sliderbtn2link',array(
	   	'type' => 'url',
	   	'label' => __('Button 2 Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_slider_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_slider_excerpt_length',array(
		'default' => '15',
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_slider_excerpt_length',array(
		'type' => 'number',
		'label' => __('Description Excerpt Length','eco-solar-power'),
		'section' => 'luzuk_eco_solar_power_slider_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_slider_font_size',array(
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_slider_font_size',array(
		'type' => 'text',
		'label' => __('Title font Size','eco-solar-power'),
		'section' => 'luzuk_eco_solar_power_slider_section'
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_slider_text_font_size',array(
		'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_float'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_slider_text_font_size',array(
		'type' => 'text',
		'label' => __('Description font Size','eco-solar-power'),
		'section' => 'luzuk_eco_solar_power_slider_section'
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_title_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_title_color', array(
		'label' => 'Title Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_description_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_description_color', array(
		'label' => 'Description Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));
	
	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_btn1text_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_btn1text_color', array(
		'label' => 'Button 1 Text Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_btn1bg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_btn1bg_color', array(
		'label' => 'Button 1 BG Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_btn1texthrv_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_btn1texthrv_color', array(
		'label' => 'Button 1 Text Hover Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_btn2text_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_btn2text_color', array(
		'label' => 'Button 2 Text Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_btn2texthover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_btn2texthover_color', array(
		'label' => 'Button 2 Text Hover Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_arrowicon_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_arrowicon_color', array(
		'label' => 'Arrow Icon Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_slider_arrowbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_slider_arrowbg_color', array(
		'label' => 'Arrow BG Color',
		'section' => 'luzuk_eco_solar_power_slider_section',
	)));


	// aboutus Section
	$wp_customize->add_section('luzuk_eco_solar_power_aboutus_section',array(
		'title'	=> __('About Us Settings','eco-solar-power'),
		'description'=> __('<b>Note :</b> This section will appear below the Slider.','eco-solar-power'),
		'panel' => 'luzuk_eco_solar_power_panel_id',
	));

	$wp_customize->add_setting(
    	'luzuk_eco_solar_power_aboutus_img1',
	    array(
	        'sanitize_callback' => 'esc_url_raw'
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'luzuk_eco_solar_power_aboutus_img1',
	        array(
			    'label'   		=> __('Image 1','eco-solar-power'),
				'description'   		=> __('Image size 640*960','eco-solar-power'),
	            'section' => 'luzuk_eco_solar_power_aboutus_section',
	            'settings' => 'luzuk_eco_solar_power_aboutus_img1',
	        )
	    )
	);

	$wp_customize->add_setting(
    	'luzuk_eco_solar_power_aboutus_img2',
	    array(
	        'sanitize_callback' => 'esc_url_raw'
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'luzuk_eco_solar_power_aboutus_img2',
	        array(
			    'label'   		=> __('Image 2','eco-solar-power'),
				'description'   		=> __('Image size 640*853','eco-solar-power'),
	            'section' => 'luzuk_eco_solar_power_aboutus_section',
	            'settings' => 'luzuk_eco_solar_power_aboutus_img2',
	        )
	    )
	);

	$wp_customize->add_setting(
    	'luzuk_eco_solar_power_aboutus_img3',
	    array(
	        'sanitize_callback' => 'esc_url_raw'
	    )
	);
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'luzuk_eco_solar_power_aboutus_img3',
	        array(
			    'label'   		=> __('Image 3','eco-solar-power'),
				'description'   		=> __('Image size 640*960','eco-solar-power'),
	            'section' => 'luzuk_eco_solar_power_aboutus_section',
	            'settings' => 'luzuk_eco_solar_power_aboutus_img3',
	        )
	    )
	);
	
	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusyearofexperiencenum',array(
    	'default' => '25+',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusyearofexperiencenum',array(
	   	'type' => 'text',
	   	'label' => __('Year Of Experience Number','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusyearofexperiencetext',array(
    	'default' => 'Years Of Experience',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusyearofexperiencetext',array(
	   	'type' => 'text',
	   	'label' => __('Year Of Experience Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusheading',array(
    	'default' => 'About us',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusheading',array(
	   	'type' => 'text',
	   	'label' => __('Heading','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustitle',array(
    	'default' => 'WE PROVIDE THE BEST SOLAR ENERGY SOLUTIONS',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustitle',array(
	   	'type' => 'text',
	   	'label' => __('Title','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusdescription',array(
    	'default' => 'The solar solution company specializes in providing innovation, eco-friendly energy systems that harness the sun power, reducing carbon footprints and energy costs of residental, commercial, and industrial clients worldwide.',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusdescription',array(
	   	'type' => 'text',
	   	'label' => __('Description','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list1',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list1',array(
	   	'type' => 'text',
	   	'label' => __('List 1','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list2',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list2',array(
	   	'type' => 'text',
	   	'label' => __('List 2','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list3',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list3',array(
	   	'type' => 'text',
	   	'label' => __('List 3','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list4',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list4',array(
	   	'type' => 'text',
	   	'label' => __('List 4','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list5',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list5',array(
	   	'type' => 'text',
	   	'label' => __('List 5','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutustabsyears1list6',array(
    	'default' => 'Lorem Ipsum are',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutustabsyears1list6',array(
	   	'type' => 'text',
	   	'label' => __('List 6','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusbtnlink',array(
    	'default' => '#',
    	'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusbtnlink',array(
	   	'type' => 'text',
	   	'label' => __('Button Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_aboutusbtntext',array(
    	'default' => 'Learn More',
    	'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_aboutusbtntext',array(
	   	'type' => 'text',
	   	'label' => __('Button Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_aboutus_section',
	));

	

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_heading_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_heading_color', array(
		'label' => 'Heading Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_title_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_title_color', array(
		'label' => 'Title Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_description_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_description_color', array(
		'label' => 'Description Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_listicon_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_listicon_color', array(
		'label' => 'Lists Icon Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_lists_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_lists_color', array(
		'label' => 'Lists Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_btntext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_btntext_color', array(
		'label' => 'Button Text Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_btnhrv_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_btnhrv_color', array(
		'label' => 'Button Hover Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_yearofexpnum_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_yearofexpnum_color', array(
		'label' => 'Year Of Experience Number Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_yearofexptext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_yearofexptext_color', array(
		'label' => 'Year Of Experience Text Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_yearofexpicon_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_yearofexpicon_color', array(
		'label' => 'Year Of Experience Icon Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_yearofexpiconbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_yearofexpiconbg_color', array(
		'label' => 'Year Of Experience Icon BG Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_aboutus_yearofexpboxbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_aboutus_yearofexpboxbg_color', array(
		'label' => 'Year Of Experience Box BG Color',
		'section' => 'luzuk_eco_solar_power_aboutus_section',
	)));


	// services Section
	$wp_customize->add_section('luzuk_eco_solar_power_services_section',array(
		'title'	=> __('Services Settings','eco-solar-power'),
		'description'=> __('<b>Note :</b> This section will appear below the About Us.','eco-solar-power'),
		'panel' => 'luzuk_eco_solar_power_panel_id',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_servicessubheading',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_servicessubheading',array(
	   	'type' => 'text',
	   	'label' => __('Sub Heading','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_services_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_servicesheading',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_servicesheading',array(
	   	'type' => 'text',
	   	'label' => __('Heading','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_services_section',
	));

	$pages = get_pages(); // Retrieve pages
	$page_options = array(); // Initialize page options array
	foreach ($pages as $page) {
		$page_options[$page->ID] = $page->post_title; // Store page ID and title in options array
	}

	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_1', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_1', array(
		'label'    => __('Select Page 1', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));



	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_2', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_2', array(
		'label'    => __('Select Page 2', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));


	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_3', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_3', array(
		'label'    => __('Select Page 3', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_4', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_4', array(
		'label'    => __('Select Page 4', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_5', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_5', array(
		'label'    => __('Select Page 5', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_page_setting_6', array(
		'default'            => '',
		'sanitize_callback'  => 'absint', // Use absint to ensure the value is an integer
	));

	$wp_customize->add_control('luzuk_eco_solar_power_page_setting_6', array(
		'label'    => __('Select Page 6', 'eco-solar-power'),
		'section'  => 'luzuk_eco_solar_power_services_section', 
		'type'     => 'dropdown-pages',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_servicesbottom_description',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_servicesbottom_description',array(
	   	'type' => 'text',
	   	'label' => __('Bottom Description','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_services_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_servicesbottombtntext',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_servicesbottombtntext',array(
	   	'type' => 'text',
	   	'label' => __('Bottom Button Text','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_services_section',
	));

	$wp_customize->add_setting('luzuk_eco_solar_power_servicesbottombtnlink',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('luzuk_eco_solar_power_servicesbottombtnlink',array(
	   	'type' => 'text',
	   	'label' => __('Bottom Button Link','eco-solar-power'),
	   	'section' => 'luzuk_eco_solar_power_services_section',
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_subheading_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_subheading_color', array(
		'label' => 'Sub Heading Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_heading_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_heading_color', array(
		'label' => 'Heading Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_title_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_title_color', array(
		'label' => 'Title Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_bottomdescription_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_bottomdescription_color', array(
		'label' => 'Bottom Description Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_bottombtntext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_bottombtntext_color', array(
		'label' => 'Bottom Button Text Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_bottombtnbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_bottombtnbg_color', array(
		'label' => 'Bottom Button BG Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_services_bottombtntexthrv_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_services_bottombtntexthrv_color', array(
		'label' => 'Bottom Button Text Hover Color',
		'section' => 'luzuk_eco_solar_power_services_section',
	)));
	

	//Footer
    $wp_customize->add_section( 'luzuk_eco_solar_power_footer', array(
    	'title'  => __( 'Footer Settings', 'eco-solar-power' ),
		'priority' => null,
		'panel' => 'luzuk_eco_solar_power_panel_id'
	) );

	$wp_customize->add_setting('luzuk_eco_solar_power_show_back_totop',array(
       'default' => true,
       'sanitize_callback'	=> 'luzuk_eco_solar_power_sanitize_checkbox'
    ));
    $wp_customize->add_control('luzuk_eco_solar_power_show_back_totop',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Back to Top','eco-solar-power'),
       'section' => 'luzuk_eco_solar_power_footer'
    ));

    $wp_customize->add_setting('luzuk_eco_solar_power_footer_copy',array(
		'default' => 'Eco Solar Power WordPress Theme By Luzuk',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('luzuk_eco_solar_power_footer_copy',array(
		'label'	=> __('Copyright Text','eco-solar-power'),
		'section' => 'luzuk_eco_solar_power_footer',
		'setting' => 'luzuk_eco_solar_power_footer_copy',
		'type' => 'text'
	));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footertext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footertext_color', array(
		'label' => 'Text Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footeractivemenu_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footeractivemenu_color', array(
		'label' => 'Active Menu Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footercopyright_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footercopyright_color', array(
		'label' => 'Copyright Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footercopyrightbrd_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footercopyrightbrd_color', array(
		'label' => 'Border Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footerscrolltotoptext_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footerscrolltotoptext_color', array(
		'label' => 'Scroll To Top Text Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footerscrolltotopbg_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footerscrolltotopbg_color', array(
		'label' => 'Scroll To Top BG Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footerscrolltotoptexthover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footerscrolltotoptexthover_color', array(
		'label' => 'Scroll To Top Text Hover Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));

	$wp_customize->add_setting( 'luzuk_eco_solar_power_footerscrolltotophover_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luzuk_eco_solar_power_footerscrolltotophover_color', array(
		'label' => 'Scroll To Top Hover Color',
		'section' => 'luzuk_eco_solar_power_footer',
	)));




	

	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'luzuk_eco_solar_power_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'luzuk_eco_solar_power_customize_partial_blogdescription',
	) );
}
add_action( 'customize_register', 'luzuk_eco_solar_power_customize_register' );

function luzuk_eco_solar_power_customize_partial_blogname() {
	bloginfo( 'name' );
}

function luzuk_eco_solar_power_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if (class_exists('WP_Customize_Control')) {

   	class Luzuk_Eco_Solar_Power_Fontawesome_Icon_Chooser extends WP_Customize_Control {

      	public $type = 'icon';

      	public function render_content() { ?>
	     	<label>
	            <span class="customize-control-title">
	               <?php echo esc_html($this->label); ?>
	            </span>

	            <?php if ($this->description) { ?>
	                <span class="description customize-control-description">
	                   <?php echo wp_kses_post($this->description); ?>
	                </span>
	            <?php } ?>

	            <div class="eco-solar-power-selected-icon">
	                <i class="fa <?php echo esc_attr($this->value()); ?>"></i>
	                <span><i class="fa fa-angle-down"></i></span>
	            </div>

	            <ul class="eco-solar-power-icon-list clearfix">
	                <?php
	                $luzuk_eco_solar_power_font_awesome_icon_array = luzuk_eco_solar_power_font_awesome_icon_array();
	                foreach ($luzuk_eco_solar_power_font_awesome_icon_array as $luzuk_eco_solar_power_font_awesome_icon) {
	                   $icon_class = $this->value() == $luzuk_eco_solar_power_font_awesome_icon ? 'icon-active' : '';
	                   echo '<li class=' . esc_attr($icon_class) . '><i class="' . esc_attr($luzuk_eco_solar_power_font_awesome_icon) . '"></i></li>';
	                }
	                ?>
	            </ul>
	            <input type="hidden" value="<?php $this->value(); ?>" <?php $this->link(); ?> />
	        </label>
	        <?php
      	}
  	}
}
function luzuk_eco_solar_power_customizer_script() {
   wp_enqueue_style( 'font-awesome-1', esc_url(get_template_directory_uri()).'/assets/css/fontawesome-all.css');
}
add_action( 'customize_controls_enqueue_scripts', 'luzuk_eco_solar_power_customizer_script' );