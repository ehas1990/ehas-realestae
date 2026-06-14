<?php
/**
 * Organic Farm: Customizer
 *
 * @subpackage Organic Farm
 * @since 1.0
 */

function organic_farm_customize_register( $wp_customize ) {

	wp_enqueue_style('customizercustom_css', esc_url( get_template_directory_uri() ). '/assets/css/customizer.css');

	// fontawesome icon-picker

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	// Add custom control.
  	require get_parent_theme_file_path( 'inc/switch/control_switch.php' );

  	require get_parent_theme_file_path( 'inc/custom-control.php' );

  	//Register the sortable control type.
	$wp_customize->register_control_type( 'Organic_Farm_Control_Sortable' );
  	
  	// Add homepage customizer file
  	require get_template_directory() . '/inc/customizer-home-page.php';

  	add_action( 'customize_controls_enqueue_scripts', function() {
    	wp_enqueue_script(
	        'organic-farm-customizer-reset',
	        get_theme_file_uri() . '/assets/js/color-reset.js', // Ensure the JS file exists in your theme
	        array( 'customize-controls', 'jquery' ),
	        '1.0',
	        true
    	);
	} );

  	// pro section
 	$wp_customize->add_section('organic_farm_pro', array(
        'title'    => __('🔒 Unlock Premium Features', 'organic-farm'),
        'priority' => 1,
    ));
    $wp_customize->add_setting('organic_farm_pro', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new Organic_Farm_Pro_Control($wp_customize, 'organic_farm_pro', array(
        'label'    => __('ORGANIC FARM PREMIUM', 'organic-farm'),
        'section'  => 'organic_farm_pro',
        'settings' => 'organic_farm_pro',
        'priority' => 1,
    )));

    // logo
    $wp_customize->add_setting('organic_farm_logo_max_height',array(
		'default'=> '100',
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_logo_max_height',array(
		'label'	=> esc_html__('Logo Width','organic-farm'),
		'section'=> 'title_tagline',
		'settings'=>'organic_farm_logo_max_height',
		'input_attrs' => array(
			'reset'            => 100,
            'step'             => 1,
			'min'              => 0,
			'max'              => 250,
        ),
        'priority' => 9,
	)));
	$wp_customize->add_setting('organic_farm_logo_title',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_logo_title',
			array(
				'settings'        => 'organic_farm_logo_title',
				'section'         => 'title_tagline',
				'label'           => __( 'Show Site Title', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting('organic_farm_site_title_fontsize',array(
		'default'=> 30,
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_site_title_fontsize',array(
		'label' => esc_html__( 'Site Title font size','organic-farm' ),
		'section'=> 'title_tagline',
		'settings'=>'organic_farm_site_title_fontsize',
		'input_attrs' => array(
			'reset'			   => 30,
            'step'             => 1,
			'min'              => 0,
			'max'              => 50,
        ),
	)));

	$wp_customize->add_setting('organic_farm_logo_text',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_logo_text',
			array(
				'settings'        => 'organic_farm_logo_text',
				'section'         => 'title_tagline',
				'label'           => __( 'Show Site Tagline', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting('organic_farm_site_tagline_fontsize',array(
		'default'=> 15,
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_site_tagline_fontsize',array(
		'label' => esc_html__( 'Site Tagline font size','organic-farm' ),
		'section'=> 'title_tagline',
		'settings'=>'organic_farm_site_tagline_fontsize',
		'input_attrs' => array(
			'reset'			   => 15,
            'step'             => 1,
			'min'              => 0,
			'max'              => 30,
        ),
	)));

	//colors
	if ( $wp_customize->get_section( 'colors' ) ) {
        $wp_customize->get_section( 'colors' )->title = __( 'Global Colors', 'organic-farm' );
        $wp_customize->get_section( 'colors' )->priority = 3;
    }

    $wp_customize->add_setting( 'organic_farm_global_color_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_global_color_heading', array(
			'label'       => esc_html__( 'Global Colors', 'organic-farm' ),
			'section'     => 'colors',
			'settings'    => 'organic_farm_global_color_heading',
			'priority'       => 1,

	) ) );

	$wp_customize->add_setting( 'organic_farm_reset_colors', array(
	    'default'           => '',
	    'sanitize_callback' => 'sanitize_text_field',
	    'transport'         => 'postMessage', 
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'organic_farm_reset_colors', array(
	    'label'       => esc_html__( 'Reset Colors', 'organic-farm' ),
	    'section'     => 'colors',
	    'settings'    => 'organic_farm_reset_colors',
	    'type'        => 'button',
	    'input_attrs' => array(
	        'class' => 'button color-reset-btn',
	        'onclick' => 'resetColorsToDefault();', // Attach custom JS function
	    ),
	    'priority' => '2'
	) ) );

    $wp_customize->add_setting('organic_farm_primary_color', array(
	    'default' => '#16a412',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_primary_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Theme Color', 'organic-farm'),
	 
	)));

	$wp_customize->add_setting('organic_farm_top_bg_color', array(
	    'default' => '#f4f9ff',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_top_bg_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Top Bg Color', 'organic-farm'),
	 
	)));

	$wp_customize->add_setting('organic_farm_secondary_color', array(
	    'default' => '#28bc36',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_secondary_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Second Color', 'organic-farm'),
	 
	)));

	$wp_customize->add_setting('organic_farm_heading_color', array(
	    'default' => '#222222',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_heading_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Theme Heading Color', 'organic-farm'),
	 
	)));

	$wp_customize->add_setting('organic_farm_text_color', array(
	    'default' => '#707070',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_text_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Theme Text Color', 'organic-farm'),
	)));

	$wp_customize->add_setting('organic_farm_primary_fade', array(
	    'default' => '#eaffeb',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_primary_fade', array(
	    'section' => 'colors',
	    'label' => esc_html__('theme Color fade', 'organic-farm'),
	 
	)));

	$wp_customize->add_setting('organic_farm_post_bg', array(
	    'default' => '#ffffff',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_post_bg', array(
	    'section' => 'colors',
	    'label' => esc_html__('Theme white bg box color', 'organic-farm'),
	)));

	$wp_customize->add_setting('organic_farm_footer_bg', array(
	    'default' => '#222222',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_footer_bg', array(
	    'section' => 'colors',
	    'label' => esc_html__('Footer Bg color', 'organic-farm'),
	)));

    //typography
	$wp_customize->add_section( 'organic_farm_typography_settings', array(
		'title'       => __( 'Typography', 'organic-farm' ),
		'priority'       => 3,
	) );
	$organic_farm_font_choices = array(
		'' => 'Select',
		'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
		'Open Sans:400italic,700italic,400,700' => 'Open Sans',
		'Oswald:400,700' => 'Oswald',
		'Playfair Display:400,700,400italic' => 'Playfair Display',
		'Montserrat:400,700' => 'Montserrat',
		'Raleway:400,700' => 'Raleway',
		'Droid Sans:400,700' => 'Droid Sans',
		'Lato:400,700,400italic,700italic' => 'Lato',
		'Arvo:400,700,400italic,700italic' => 'Arvo',
		'Lora:400,700,400italic,700italic' => 'Lora',
		'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
		'Oxygen:400,300,700' => 'Oxygen',
		'PT Serif:400,700' => 'PT Serif',
		'PT Sans:400,700,400italic,700italic' => 'PT Sans',
		'PT Sans Narrow:400,700' => 'PT Sans Narrow',
		'Cabin:400,700,400italic' => 'Cabin',
		'Fjalla One:400' => 'Fjalla One',
		'Francois One:400' => 'Francois One',
		'Josefin Sans:400,300,600,700' => 'Josefin Sans',
		'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
		'Arimo:400,700,400italic,700italic' => 'Arimo',
		'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
		'Bitter:400,700,400italic' => 'Bitter',
		'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
		'Roboto:400,400italic,700,700italic' => 'Roboto',
		'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
		'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
		'Roboto Slab:400,700' => 'Roboto Slab',
		'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
		'Rokkitt:400' => 'Rokkitt',
	);
	$wp_customize->add_setting( 'organic_farm_section_typo_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_typo_heading', array(
		'label'       => esc_html__( 'Typography Settings', 'organic-farm' ),
		'section'     => 'organic_farm_typography_settings',
		'settings'    => 'organic_farm_section_typo_heading',
	) ) );
	$wp_customize->add_setting( 'organic_farm_headings_text', array(
		'sanitize_callback' => 'organic_farm_sanitize_fonts',
	));
	$wp_customize->add_control( 'organic_farm_headings_text', array(
		'type' => 'select',
		'description' => __('Select your suitable font for the headings.', 'organic-farm'),
		'section' => 'organic_farm_typography_settings',
		'choices' => $organic_farm_font_choices
	));
	$wp_customize->add_setting( 'organic_farm_body_text', array(
		'sanitize_callback' => 'organic_farm_sanitize_fonts'
	));
	$wp_customize->add_control( 'organic_farm_body_text', array(
		'type' => 'select',
		'description' => __( 'Select your suitable font for the body.', 'organic-farm' ),
		'section' => 'organic_farm_typography_settings',
		'choices' => $organic_farm_font_choices
	) );

    // Theme General Settings
    $wp_customize->add_section('organic_farm_theme_settings',array(
        'title' => __('Theme General Settings', 'organic-farm'),
        'priority' => 3,
    ) );
    $wp_customize->add_setting( 'organic_farm_section_sticky_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_sticky_heading', array(
		'label'       => esc_html__( 'Sticky Header Settings', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_sticky_heading',
	) ) );
    $wp_customize->add_setting(
		'organic_farm_sticky_header',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_sticky_header',
			array(
				'settings'        => 'organic_farm_sticky_header',
				'section'         => 'organic_farm_theme_settings',
				'label'           => __( 'Show Sticky Header', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'organic_farm_section_loader_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_loader_heading', array(
		'label'       => esc_html__( 'Loader Settings', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_loader_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_theme_loader',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_theme_loader',
			array(
				'settings'        => 'organic_farm_theme_loader',
				'section'         => 'organic_farm_theme_settings',
				'label'           => __( 'Show Site Loader', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting('organic_farm_loader_style',array(
        'default' => 'style_one',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_loader_style',array(
        'type' => 'select',
        'label' => __('Select Loader Design','organic-farm'),
        'section' => 'organic_farm_theme_settings',
        'choices' => array(
            'style_one' => __('Circle','organic-farm'),
            'style_two' => __('Bar','organic-farm'),
        ),
	) );
	
	$wp_customize->add_setting( 'organic_farm_theme_width_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_theme_width_heading', array(
		'label'       => esc_html__( 'Theme Width Settings', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_theme_width_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_width_options',array(
        'default' => 'full_width',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_width_options',array(
        'type' => 'select',
        'label' => __('Theme Width Option','organic-farm'),
        'section' => 'organic_farm_theme_settings',
        'choices' => array(
            'full_width' => __('Fullwidth','organic-farm'),
            'Container' => __('Container','organic-farm'),
            'container_fluid' => __('Container Fluid','organic-farm'),
        ),
	) );
	$wp_customize->add_setting( 'organic_farm_section_menu_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_menu_heading', array(
		'label'       => esc_html__( 'Menu Settings', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_menu_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_menu_text_transform',array(
        'default' => 'CAPITALISE',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_menu_text_transform',array(
        'type' => 'select',
        'label' => __('Menus Text Transform','organic-farm'),
        'section' => 'organic_farm_theme_settings',
        'choices' => array(
            'CAPITALISE' => __('CAPITALISE','organic-farm'),
            'UPPERCASE' => __('UPPERCASE','organic-farm'),
            'LOWERCASE' => __('LOWERCASE','organic-farm'),
        ),
	) );
	$wp_customize->add_setting('organic_farm_menu_fontsize',array(
		'default'=> 15,
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_menu_fontsize',array(
		'label' => esc_html__( 'menu font size','organic-farm' ),
		'section'=> 'organic_farm_theme_settings',
		'settings'=>'organic_farm_menu_fontsize',
		'input_attrs' => array(
			'reset'			   => 15,
            'step'             => 1,
			'min'              => 0,
			'max'              => 20,
        ),
	)));
	$wp_customize->add_setting( 'organic_farm_section_scroll_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_scroll_heading', array(
		'label'       => esc_html__( 'Scroll Top Settings', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_scroll_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_scroll_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_scroll_enable',
			array(
				'settings'        => 'organic_farm_scroll_enable',
				'section'         => 'organic_farm_theme_settings',
				'label'           => __( 'Show Scroll Top', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('organic_farm_scroll_options',array(
        'default' => 'right_align',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_scroll_options',array(
		'type' => 'radio',
		'label'     => __('Scroll Top Alignment', 'organic-farm'),
		'section' => 'organic_farm_theme_settings',
		'type' => 'select',
		'choices' => array(
			'left_align' => __('LEFT','organic-farm'),
			'center_align' => __('CENTER','organic-farm'),
			'right_align' => __('RIGHT','organic-farm'),
		)
	) );
	$wp_customize->add_setting('organic_farm_scroll_top_icon',array(
		'default'	=> 'fas fa-chevron-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_scroll_top_icon',array(
		'label'	=> __('Add Scroll Top Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_theme_settings',
		'setting'	=> 'organic_farm_scroll_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'organic_farm_section_cursor_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_cursor_heading', array(
		'label'       => esc_html__( 'Cursor Setting', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_cursor_heading',
	) ) );

	$wp_customize->add_setting(
		'organic_farm_enable_custom_cursor',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_enable_custom_cursor',
			array(
				'settings'        => 'organic_farm_enable_custom_cursor',
				'section'         => 'organic_farm_theme_settings',
				'label'           => __( 'show custom cursor', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting( 'organic_farm_section_animation_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_animation_heading', array(
		'label'       => esc_html__( 'Animation Setting', 'organic-farm' ),
		'section'     => 'organic_farm_theme_settings',
		'settings'    => 'organic_farm_section_animation_heading',
	) ) );

	$wp_customize->add_setting(
		'organic_farm_animation_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_animation_enable',
			array(
				'settings'        => 'organic_farm_animation_enable',
				'section'         => 'organic_farm_theme_settings',
				'label'           => __( 'show/Hide Animation', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	// Post Layouts
	$wp_customize->add_panel( 'organic_farm_post_panel', array(
		'title' => esc_html__( 'Post Layout', 'organic-farm' ),
		'priority' => 4,
	));

	$wp_customize->add_section('organic_farm_blog_meta',array(
        'title' => __('Blog Meta', 'organic-farm'), 
        'panel' => 'organic_farm_post_panel',       
    ) );

    $wp_customize->add_setting( 'organic_farm_section_blog_meta_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_blog_meta_heading', array(
		'label'       => esc_html__( 'Blog Meta Settings', 'organic-farm' ),
		'section'     => 'organic_farm_blog_meta',
		'settings'    => 'organic_farm_section_blog_meta_heading',
	) ) );

	$wp_customize->add_setting('organic_farm_date',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_date',
			array(
				'settings'        => 'organic_farm_date',
				'section'         => 'organic_farm_blog_meta',
				'label'           => __( 'Show Date', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'organic_farm_date', array(
		'selector' => '.date-box',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_date',
	) );
	$wp_customize->add_setting('organic_farm_date_icon',array(
		'default'	=> 'far fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_date_icon',array(
		'label'	=> __('date Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_blog_meta',
		'setting'	=> 'organic_farm_date_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('organic_farm_date_type',array(
        'default' => 'published',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_date_type',array(
		'type' => 'radio',
		'label'     => __('Date Format', 'organic-farm'),
		'section' => 'organic_farm_blog_meta',
		'type' => 'select',
		'choices' => array(
			'published' => __('published date','organic-farm'),
            'modified' => __('modified date','organic-farm'),
		),
	) );



	$wp_customize->add_setting('organic_farm_sticky_icon',array(
		'default'	=> 'fas fa-thumb-tack',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_sticky_icon',array(
		'label'	=> __('Sticky Post Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_blog_meta',
		'setting'	=> 'organic_farm_sticky_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_admin',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_admin',
			array(
				'settings'        => 'organic_farm_admin',
				'section'         => 'organic_farm_blog_meta',
				'label'           => __( 'Show Author/Admin', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'organic_farm_admin', array(
		'selector' => '.entry-author',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_admin',
	) );
	$wp_customize->add_setting('organic_farm_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_author_icon',array(
		'label'	=> __('Author Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_blog_meta',
		'setting'	=> 'organic_farm_author_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_comment',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_comment',
			array(
				'settings'        => 'organic_farm_comment',
				'section'         => 'organic_farm_blog_meta',
				'label'           => __( 'Show Comment', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'organic_farm_comment', array(
		'selector' => '.entry-comments',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_comment',
	) );
	$wp_customize->add_setting('organic_farm_comment_icon',array(
		'default'	=> 'fas fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_comment_icon',array(
		'label'	=> __('comment Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_blog_meta',
		'setting'	=> 'organic_farm_comment_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_tag',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_tag',
			array(
				'settings'        => 'organic_farm_tag',
				'section'         => 'organic_farm_blog_meta',
				'label'           => __( 'Show tag count', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'organic_farm_tag', array(
		'selector' => '.tags',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_tag',
	) );
	$wp_customize->add_setting('organic_farm_tag_icon',array(
		'default'	=> 'fas fa-tags',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_tag_icon',array(
		'label'	=> __('tag Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_blog_meta',
		'setting'	=> 'organic_farm_tag_icon',
		'type'		=> 'icon'
	)));
	// FUll Content // 
	$wp_customize->add_setting('organic_farm_blog_meta_content',array(
    	'default' => 'Excerpt Content',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_blog_meta_content',array(
        'type' => 'radio',
        'label' => __('Blog Post Content Type','organic-farm'),
        'section' => 'organic_farm_blog_meta',
		'type' => 'select',
        'choices' => array(
            'No Content' => __('No Content','organic-farm'),
            'Full Content' => __('Full Content','organic-farm'),
            'Excerpt Content' => __('Excerpt Content','organic-farm'),
        ),
	) );
    $wp_customize->add_setting( 'organic_farm_post_excerpt_number', array(
		'default'              => 20,
		'sanitize_callback'	=> 'absint'
	) );
	$wp_customize->add_control( 'organic_farm_post_excerpt_number', array(
		'label'       => esc_html__( 'Blog Post Excerpt Number (Max 50)','organic-farm' ),
		'section'     => 'organic_farm_blog_meta',
		'type'        => 'number',
		'settings'    => 'organic_farm_post_excerpt_number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 0,
			'max'              => 50,
		),
		'active_callback' => 'organic_farm_excerpt_enabled'
	) );
	$wp_customize->add_setting( 'organic_farm_button_excerpt_suffix', array(
		'default'   => '...',
		'sanitize_callback'	=> 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'organic_farm_button_excerpt_suffix', array(
		'label'       => esc_html__( 'Post Excerpt Suffix','organic-farm' ),
		'section'     => 'organic_farm_blog_meta',
		'type'        => 'text',
		'settings'    => 'organic_farm_button_excerpt_suffix',
		'active_callback' => 'organic_farm_excerpt_enabled'
	) );
    $wp_customize->add_section('organic_farm_layout',array(
        'title' => __('Single-Post Layout', 'organic-farm'),
        'panel' => 'organic_farm_post_panel',
    ) );
    $wp_customize->add_setting( 'organic_farm_section_post_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_post_heading', array(
		'label'       => esc_html__( 'Single Post Structure', 'organic-farm' ),
		'section'     => 'organic_farm_layout',
		'settings'    => 'organic_farm_section_post_heading',
	) ) );
	$wp_customize->add_setting( 'organic_farm_single_post_option',
		array(
			'default' => 'single_right_sidebar',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( new Organic_Farm_Radio_Image_Control( $wp_customize, 'organic_farm_single_post_option',
		array(
			'type'=>'select',
			'label' => __( 'select Single Post Page Layout', 'organic-farm' ),
			'section' => 'organic_farm_layout',
			'choices' => array(

				'single_right_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/2column.jpg',
					'name' => __( 'Right Sidebar', 'organic-farm' )
				),
				'single_left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/left.png',
					'name' => __( 'Left Sidebar', 'organic-farm' )
				),
				'single_full_width' => array(
					'image' => get_template_directory_uri().'/assets/images/1column.jpg',
					'name' => __( 'One Column', 'organic-farm' )
				),
			)
		)
	) );
	$wp_customize->add_setting('organic_farm_single_post_tag',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_single_post_tag',
			array(
				'settings'        => 'organic_farm_single_post_tag',
				'section'         => 'organic_farm_layout',
				'label'           => __( 'Show Tags', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'organic_farm_single_post_tag', array(
		'selector' => '.single-tags',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_single_post_tag',
	) );
	$wp_customize->add_setting('organic_farm_similar_post',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_similar_post',
			array(
				'settings'        => 'organic_farm_similar_post',
				'section'         => 'organic_farm_layout',
				'label'           => __( 'Show Similar post', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('organic_farm_similar_text',array(
		'default' => 'Explore More',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('organic_farm_similar_text',array(
		'label' => esc_html__('Similar Post Heading','organic-farm'),
		'section' => 'organic_farm_layout',
		'setting' => 'organic_farm_similar_text',
		'type'    => 'text'
	));
	$wp_customize->add_section('organic_farm_archieve_post_layot',array(
        'title' => __('Archieve-Post Layout', 'organic-farm'),
        'panel' => 'organic_farm_post_panel',
    ) );
	$wp_customize->add_setting( 'organic_farm_section_archive_post_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_archive_post_heading', array(
		'label'       => esc_html__( 'Archieve Post Structure', 'organic-farm' ),
		'section'     => 'organic_farm_archieve_post_layot',
		'settings'    => 'organic_farm_section_archive_post_heading',
	) ) );
    $wp_customize->add_setting( 'organic_farm_post_option',
		array(
			'default' => 'right_sidebar',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( new Organic_Farm_Radio_Image_Control( $wp_customize, 'organic_farm_post_option',
		array(
			'type'=>'select',
			'label' => __( 'select Post Page Layout', 'organic-farm' ),
			'section' => 'organic_farm_archieve_post_layot',
			'choices' => array(
				'right_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/2column.jpg',
					'name' => __( 'Right Sidebar', 'organic-farm' )
				),
				'left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/left.png',
					'name' => __( 'Left Sidebar', 'organic-farm' )
				),
				'one_column' => array(
					'image' => get_template_directory_uri().'/assets/images/1column.jpg',
					'name' => __( 'One Column', 'organic-farm' )
				),
				'three_column' => array(
					'image' => get_template_directory_uri().'/assets/images/3column.jpg',
					'name' => __( 'Three Column', 'organic-farm' )
				),
				'four_column' => array(
					'image' => get_template_directory_uri().'/assets/images/4column.jpg',
					'name' => __( 'Four Column', 'organic-farm' )
				),
				'grid_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/grid-sidebar.jpg',
					'name' => __( 'Grid-Right-Sidebar Layout', 'organic-farm' )
				),
				'grid_left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/grid-left.png',
					'name' => __( 'Grid-Left-Sidebar Layout', 'organic-farm' )
				),
				'grid_post' => array(
					'image' => get_template_directory_uri().'/assets/images/grid.jpg',
					'name' => __( 'Grid Layout', 'organic-farm' )
				)
			)
		)
	) );
	$wp_customize->add_setting('organic_farm_grid_column',array(
        'default' => '3_column',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_grid_column',array(
		'type' => 'radio',
		'label'     => __('Grid Post Per Row', 'organic-farm'),
		'section' => 'organic_farm_archieve_post_layot',
		'type' => 'select',
		'choices' => array(
			'1_column' => __('1','organic-farm'),
            '2_column' => __('2','organic-farm'),
            '3_column' => __('3','organic-farm'),
            '4_column' => __('4','organic-farm'),
		)
	) );
	$wp_customize->add_setting('archieve_post_order', array(
        'default' => array('title', 'image', 'meta','excerpt','btn'),
        'sanitize_callback' => 'organic_farm_sanitize_sortable',
    ));
    $wp_customize->add_control(new Organic_Farm_Control_Sortable($wp_customize, 'archieve_post_order', array(
    	'label' => esc_html__('Post Order', 'organic-farm'),
        'description' => __('Drag & Drop post items to re-arrange the order and also hide and show items as per the need by clicking on the eye icon.', 'organic-farm') ,
        'section' => 'organic_farm_archieve_post_layot',
        'choices' => array(
            'title' => __('title', 'organic-farm') ,
            'image' => __('media', 'organic-farm') ,
            'meta' => __('meta', 'organic-farm') ,
            'excerpt' => __('excerpt', 'organic-farm') ,
            'btn' => __('Read more', 'organic-farm') ,
        ) ,
    )));
	$wp_customize->add_setting('organic_farm_post_excerpt',array(
		'default'=> 30,
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_post_excerpt',array(
		'label' => esc_html__( 'Excerpt Limit','organic-farm' ),
		'section'=> 'organic_farm_archieve_post_layot',
		'settings'=>'organic_farm_post_excerpt',
		'input_attrs' => array(
			'reset'			   => 30,
            'step'             => 1,
			'min'              => 0,
			'max'              => 100,
        ),
	)));
	$wp_customize->add_setting('organic_farm_read_more_text',array(
		'default' => 'Read More',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('organic_farm_read_more_text',array(
		'label' => esc_html__('Read More Text','organic-farm'),
		'section' => 'organic_farm_archieve_post_layot',
		'setting' => 'organic_farm_read_more_text',
		'type'    => 'text'
	));

	$wp_customize->add_section('organic_farm_blog_pagination',array(
        'title' => __('Pagination', 'organic-farm'), 
        'panel' => 'organic_farm_post_panel',       
    ) );

	$wp_customize->add_setting('organic_farm_pagination_type',array(
        'default' => 'numbered',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_pagination_type',array(
		'type' => 'radio',
		'label'     => __('Blog Pagination', 'organic-farm'),
		'section' => 'organic_farm_blog_pagination',
		'type' => 'select',
		'choices' => array(
			'default' => __('Previous/Next','organic-farm'),
            'numbered' => __('Numbered','organic-farm'),
		),
	) );

	$wp_customize->add_setting('organic_farm_single_post_pagination_type',array(
        'default' => 'default',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_single_post_pagination_type',array(
		'type' => 'radio',
		'label'     => __('Post Pagination', 'organic-farm'),
		'section' => 'organic_farm_blog_pagination',
		'type' => 'select',
		'choices' => array(
			'default' => __('Previous/Next','organic-farm'),
            'post-name' => __('Post Title','organic-farm'),
		),
	) );

	// header-image
	$wp_customize->add_setting( 'organic_farm_section_header_image_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_header_image_heading', array(
		'label'       => esc_html__( 'Header Image Settings', 'organic-farm' ),
		'section'     => 'header_image',
		'settings'    => 'organic_farm_section_header_image_heading',
		'priority'    =>'1',
	) ) );

	$wp_customize->add_setting('organic_farm_show_header_image',array(
        'default' => 'on',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_show_header_image',array(
        'type' => 'select',
        'label' => __('Select Option','organic-farm'),
        'section' => 'header_image',
        'choices' => array(
            'on' => __('With Header Image','organic-farm'),
            'off' => __('Without Header Image','organic-farm'),
        ),
	) );
	
	// breadcrumb setting
	$wp_customize->add_section('organic_farm_breadcrumb_settings',array(
        'title' => __('Breadcrumb Settings', 'organic-farm'),
        'priority' => 4
    ) );
	$wp_customize->add_setting( 'organic_farm_section_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_breadcrumb_heading', array(
		'label'       => esc_html__( ' theme Breadcrumb Settings', 'organic-farm' ),
		'section'     => 'organic_farm_breadcrumb_settings',
		'settings'    => 'organic_farm_section_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_enable_breadcrumb',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_enable_breadcrumb',
			array(
				'settings'        => 'organic_farm_enable_breadcrumb',
				'section'         => 'organic_farm_breadcrumb_settings',
				'label'           => __( 'Show Breadcrumb', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('organic_farm_breadcrumb_separator', array(
        'default' => ' / ',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('organic_farm_breadcrumb_separator', array(
        'label' => __('Breadcrumb Separator', 'organic-farm'),
        'section' => 'organic_farm_breadcrumb_settings',
        'type' => 'text',
    ));
	$wp_customize->add_setting( 'organic_farm_single_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_single_breadcrumb_heading', array(
		'label'       => esc_html__( 'Single post & Page', 'organic-farm' ),
		'section'     => 'organic_farm_breadcrumb_settings',
		'settings'    => 'organic_farm_single_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_single_enable_breadcrumb',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_single_enable_breadcrumb',
			array(
				'settings'        => 'organic_farm_single_enable_breadcrumb',
				'section'         => 'organic_farm_breadcrumb_settings',
				'label'           => __( 'Show Breadcrumb', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	if ( class_exists( 'WooCommerce' ) ) { 
		$wp_customize->add_setting( 'organic_farm_woocommerce_breadcrumb_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_woocommerce_breadcrumb_heading', array(
			'label'       => esc_html__( 'Woocommerce Breadcrumb', 'organic-farm' ),
			'section'     => 'organic_farm_breadcrumb_settings',
			'settings'    => 'organic_farm_woocommerce_breadcrumb_heading',
		) ) );
		$wp_customize->add_setting(
			'organic_farm_woocommerce_enable_breadcrumb',
			array(
				'type'                 => 'option',
				'capability'           => 'edit_theme_options',
				'theme_supports'       => '',
				'default'              => '1',
				'transport'            => 'refresh',
				'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Organic_Farm_Customizer_Customcontrol_Switch(
				$wp_customize,
				'organic_farm_woocommerce_enable_breadcrumb',
				array(
					'settings'        => 'organic_farm_woocommerce_enable_breadcrumb',
					'section'         => 'organic_farm_breadcrumb_settings',
					'label'           => __( 'Show Breadcrumb', 'organic-farm' ),				
					'choices'		  => array(
						'1'      => __( 'On', 'organic-farm' ),
						'off'    => __( 'Off', 'organic-farm' ),
					),
					'active_callback' => '',
				)
			)
		);
		$wp_customize->add_setting('woocommerce_breadcrumb_separator', array(
	        'default' => ' / ',
	        'sanitize_callback' => 'sanitize_text_field',
	    ));
	    $wp_customize->add_control('woocommerce_breadcrumb_separator', array(
	        'label' => __('Breadcrumb Separator', 'organic-farm'),
	        'section' => 'organic_farm_breadcrumb_settings',
	        'type' => 'text',
	    ));
	}

	// woocommerce
	if ( class_exists( 'WooCommerce' ) ) { 
		$wp_customize->add_section('organic_farm_woocommerce_section',array(
	        'title' => __('Custom Woocommerce Settings', 'organic-farm'),
	        'panel' => 'woocommerce',
	        'priority' => 4,
	    ) );
		$wp_customize->add_setting( 'organic_farm_section_shoppage_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_shoppage_heading', array(
			'label'       => esc_html__( 'Sidebar Settings', 'organic-farm' ),
			'section'     => 'organic_farm_woocommerce_section',
			'settings'    => 'organic_farm_section_shoppage_heading',
		) ) );
		$wp_customize->add_setting( 'organic_farm_shop_page_sidebar',
			array(
				'default' => 'right_sidebar',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);
		$wp_customize->add_control( new Organic_Farm_Radio_Image_Control( $wp_customize, 'organic_farm_shop_page_sidebar',
			array(
				'type'=>'select',
				'label' => __( 'Show Shop Page Sidebar', 'organic-farm' ),
				'section'     => 'organic_farm_woocommerce_section',
				'choices' => array(

					'right_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/2column.jpg',
						'name' => __( 'Right Sidebar', 'organic-farm' )
					),
					'left_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/left.png',
						'name' => __( 'Left Sidebar', 'organic-farm' )
					),
					'full_width' => array(
						'image' => get_template_directory_uri().'/assets/images/1column.jpg',
						'name' => __( 'Full Width', 'organic-farm' )
					),
				)
			)
		) );
		$wp_customize->add_setting( 'organic_farm_wocommerce_single_page_sidebar',
			array(
				'default' => 'right_sidebar',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);
		$wp_customize->add_control( new Organic_Farm_Radio_Image_Control( $wp_customize, 'organic_farm_wocommerce_single_page_sidebar',
			array(
				'type'=>'select',
				'label'           => __( 'Show Single Product Page Sidebar', 'organic-farm' ),
				'section'     => 'organic_farm_woocommerce_section',
				'choices' => array(

					'right_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/2column.jpg',
						'name' => __( 'Right Sidebar', 'organic-farm' )
					),
					'left_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/left.png',
						'name' => __( 'Left Sidebar', 'organic-farm' )
					),
					'full_width' => array(
						'image' => get_template_directory_uri().'/assets/images/1column.jpg',
						'name' => __( 'Full Width', 'organic-farm' )
					),
				)
			)
		) );
		$wp_customize->add_setting( 'organic_farm_section_archieve_product_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_archieve_product_heading', array(
			'label'       => esc_html__( 'Archieve Product Settings', 'organic-farm' ),
			'section'     => 'organic_farm_woocommerce_section',
			'settings'    => 'organic_farm_section_archieve_product_heading',
		) ) );
		$wp_customize->add_setting('organic_farm_archieve_item_columns',array(
	        'default' => '3',
	        'sanitize_callback' => 'organic_farm_sanitize_choices'
		));
		$wp_customize->add_control('organic_farm_archieve_item_columns',array(
	        'type' => 'select',
	        'label' => __('Select No of Columns','organic-farm'),
	        'section' => 'organic_farm_woocommerce_section',
	        'choices' => array(
	            '1' => __('One Column','organic-farm'),
	            '2' => __('Two Column','organic-farm'),
	            '3' => __('Three Column','organic-farm'),
	            '4' => __('four Column','organic-farm'),
	            '5' => __('Five Column','organic-farm'),
	            '6' => __('Six Column','organic-farm'),
	        ),
		) );
		$wp_customize->add_setting( 'organic_farm_archieve_shop_perpage', array(
			'default'              => 6,
			'type'                 => 'theme_mod',
			'transport' 		   => 'refresh',
			'sanitize_callback'    => 'organic_farm_sanitize_number_absint',
			'sanitize_js_callback' => 'absint',
		) );
		$wp_customize->add_control( 'organic_farm_archieve_shop_perpage', array(
			'label'       => esc_html__( 'Display Products','organic-farm' ),
			'section'     => 'organic_farm_woocommerce_section',
			'type'        => 'number',
			'input_attrs' => array(
				'step'             => 1,
				'min'              => 0,
				'max'              => 30,
			),
		) );
		$wp_customize->add_setting( 'organic_farm_section_related_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_related_heading', array(
			'label'       => esc_html__( 'Related Product Settings', 'organic-farm' ),
			'section'     => 'organic_farm_woocommerce_section',
			'settings'    => 'organic_farm_section_related_heading',
		) ) );
		$wp_customize->add_setting('organic_farm_related_item_columns',array(
	        'default' => '3',
	        'sanitize_callback' => 'organic_farm_sanitize_choices'
		));
		$wp_customize->add_control('organic_farm_related_item_columns',array(
	        'type' => 'select',
	        'label' => __('Select No of Columns','organic-farm'),
	        'section' => 'organic_farm_woocommerce_section',
	        'choices' => array(
	            '1' => __('One Column','organic-farm'),
	            '2' => __('Two Column','organic-farm'),
	            '3' => __('Three Column','organic-farm'),
	            '4' => __('four Column','organic-farm'),
	            '5' => __('Five Column','organic-farm'),
	            '6' => __('Six Column','organic-farm'),
	        ),
		) );
		$wp_customize->add_setting( 'organic_farm_related_shop_perpage', array(
			'default'              => 3,
			'type'                 => 'theme_mod',
			'transport' 		   => 'refresh',
			'sanitize_callback'    => 'organic_farm_sanitize_number_absint',
			'sanitize_js_callback' => 'absint',
		) );
		$wp_customize->add_control( 'organic_farm_related_shop_perpage', array(
			'label'       => esc_html__( 'Display Products','organic-farm' ),
			'section'     => 'organic_farm_woocommerce_section',
			'type'        => 'number',
			'input_attrs' => array(
				'step'             => 1,
				'min'              => 0,
				'max'              => 10,
			),
		) );
		$wp_customize->add_setting(
			'organic_farm_related_product',
			array(
				'type'                 => 'option',
				'capability'           => 'edit_theme_options',
				'theme_supports'       => '',
				'default'              => '1',
				'transport'            => 'refresh',
				'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
			)
		);
		$wp_customize->add_control(new Organic_Farm_Customizer_Customcontrol_Switch($wp_customize,'organic_farm_related_product',
			array(
				'settings'        => 'organic_farm_related_product',
				'section'         => 'organic_farm_woocommerce_section',
				'label'           => __( 'Show Related Products', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		));
	}
	// mobile width
	$wp_customize->add_section('organic_farm_mobile_options',array(
        'title' => __('Mobile Media settings', 'organic-farm'),
        'priority' => 4,
    ) );
    $wp_customize->add_setting( 'organic_farm_section_mobile_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_mobile_heading', array(
		'label'       => esc_html__( 'Mobile Media settings', 'organic-farm' ),
		'section'     => 'organic_farm_mobile_options',
		'settings'    => 'organic_farm_section_mobile_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_menu_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_menu_icon',array(
		'label'	=> __('Menu Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_mobile_options',
		'setting'	=> 'organic_farm_menu_icon',
		'type'		=> 'icon'
	))); 
	$wp_customize->add_setting(
		'organic_farm_slider_button_mobile_show_hide',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_slider_button_mobile_show_hide',
			array(
				'settings'        => 'organic_farm_slider_button_mobile_show_hide',
				'section'         => 'organic_farm_mobile_options',
				'label'           => __( 'Show Slider Button', 'organic-farm' ),
				'description' => __('Control wont function if the button is off in the main slider settings.', 'organic-farm') ,				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'organic_farm_scroll_enable_mobile',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_scroll_enable_mobile',
			array(
				'settings'        => 'organic_farm_scroll_enable_mobile',
				'section'         => 'organic_farm_mobile_options',
				'label'           => __( 'Show Scroll Top', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'organic_farm_section_mobile_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_mobile_breadcrumb_heading', array(
		'label'       => esc_html__( 'Mobile Breadcrumb settings', 'organic-farm' ),
		'section'     => 'organic_farm_mobile_options',
		'settings'    => 'organic_farm_section_mobile_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_enable_breadcrumb_mobile',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_enable_breadcrumb_mobile',
			array(
				'settings'        => 'organic_farm_enable_breadcrumb_mobile',
				'section'         => 'organic_farm_mobile_options',
				'label'           => __( 'Theme Breadcrumb', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'organic_farm_single_enable_breadcrumb_mobile',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Organic_Farm_Customizer_Customcontrol_Switch(
			$wp_customize,
			'organic_farm_single_enable_breadcrumb_mobile',
			array(
				'settings'        => 'organic_farm_single_enable_breadcrumb_mobile',
				'section'         => 'organic_farm_mobile_options',
				'label'           => __( 'Single Post & Page', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_setting(
			'organic_farm_woocommerce_enable_breadcrumb_mobile',
			array(
				'type'                 => 'option',
				'capability'           => 'edit_theme_options',
				'theme_supports'       => '',
				'default'              => '1',
				'transport'            => 'refresh',
				'sanitize_callback'    => 'organic_farm_callback_sanitize_switch',
			)
		);
		$wp_customize->add_control(
			new Organic_Farm_Customizer_Customcontrol_Switch(
				$wp_customize,
				'organic_farm_woocommerce_enable_breadcrumb_mobile',
				array(
					'settings'        => 'organic_farm_woocommerce_enable_breadcrumb_mobile',
					'section'         => 'organic_farm_mobile_options',
					'label'           => __( 'wooCommerce Breadcrumb', 'organic-farm' ),				
					'choices'		  => array(
						'1'      => __( 'On', 'organic-farm' ),
						'off'    => __( 'Off', 'organic-farm' ),
					),
					'active_callback' => '',
				)
			)
		);
	}
	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'organic_farm_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'organic_farm_customize_partial_blogdescription',
	) );

	//front page
	$num_sections = apply_filters( 'organic_farm_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'organic_farm_sanitize_dropdown_pages',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'organic-farm' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'organic-farm' ) ),
			'section'        => 'theme_options',
			'type'           => 'dropdown-pages',
			'allow_addition' => true,
			'active_callback' => 'organic_farm_is_static_front_page',
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'organic_farm_front_page_section',
			'container_inclusive' => true,
		) );
	}
}
add_action( 'customize_register', 'organic_farm_customize_register' );

function organic_farm_customize_partial_blogname() {
	bloginfo( 'name' );
}
function organic_farm_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
function organic_farm_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}
function organic_farm_is_view_with_layout_option() {
	return ( is_page() || ( is_archive() && ! is_active_sidebar( 'sidebar-1' ) ) );
}

/* Pro control */
if (class_exists('WP_Customize_Control') && !class_exists('Organic_Farm_Pro_Control')):
    class Organic_Farm_Pro_Control extends WP_Customize_Control{

    public function render_content(){?>
        <div style="background: linear-gradient(135deg, #2B136B 0%, #A47AE2 100%); padding: 20px; border-radius: 8px; text-align: center; color: #fff;">
			
			<h3 style="margin-top: 0; color: #fff;">
				<?php esc_html_e('Unlock Advanced Features', 'organic-farm'); ?>
			</h3>
	
			<p style="margin: 15px 0;">
				<?php esc_html_e('Upgrade to Pro to get:', 'organic-farm'); ?>
			</p>
	
			<ul style="list-style: none; padding: 0; text-align: left; max-width: 300px; margin: 20px auto;">
				<li>✓ <?php esc_html_e('12+ Premium Header Layouts', 'organic-farm'); ?></li>
				<li>✓ <?php esc_html_e('Advanced Footer Builder', 'organic-farm'); ?></li>
				<li>✓ <?php esc_html_e('Typography Controls', 'organic-farm'); ?></li>
				<li>✓ <?php esc_html_e('WooCommerce Styling Options', 'organic-farm'); ?></li>
				<li>✓ <?php esc_html_e('Priority Support', 'organic-farm'); ?></li>
				<li>✓ <?php esc_html_e('One-Click Demo Import', 'organic-farm'); ?></li>
			</ul>
	
			<a href="<?php echo esc_url(admin_url('themes.php?page=organic-farm-pro')); ?>"
				style="display:inline-block;background:#fff;color:#667eea;padding:12px 30px;text-decoration:none;border-radius:4px;font-weight:600;margin:10px 5px;">
				<?php esc_html_e('View All Features', 'organic-farm'); ?>
			</a>
	
			<a href="<?php echo esc_url( ORGANIC_FARM_BUY_PRO ); ?>" target="_blank"
				style="display:inline-block;background:#ffc107;color:#333;padding:12px 30px;text-decoration:none;border-radius:4px;font-weight:600;margin:10px 5px;">
				<?php esc_html_e('Upgrade Now 🚀', 'organic-farm'); ?>
			</a>

			<a href="<?php echo esc_url( ORGANIC_FARM_BUNDLE_LINK ); ?>" target="_blank"
				style="display: inline-block; background: #28a745; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: 600; margin: 10px 5px;">
				<?php esc_html_e('WordPress Bundle 🎁', 'organic-farm'); ?>
			</a>
	
		</div>
    <?php } }
endif;


/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */

final class organic_farm_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}


/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );
	}

// icons
/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	 public function enqueue_control_scripts() {

		wp_enqueue_style('organic-farm-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css');

		wp_localize_script(
		'organic-farm-customize-controls',
		'organic_farm_customizer_params',
		array(
			'ajaxurl' =>	admin_url( 'admin-ajax.php' )
		));
	}


}
