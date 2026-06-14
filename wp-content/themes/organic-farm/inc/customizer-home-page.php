<?php
/**
 * Organic Farm: Customizer-home-page
 *
 * @subpackage Organic Farm
 * @since 1.0
 */

	//  Home Page Panel
	$wp_customize->add_panel( 'organic_farm_custompage_panel', array(
		'title' => esc_html__( 'Custom Page Settings', 'organic-farm' ),
		'priority' => 2,
	));
	// Top Header
    $wp_customize->add_section('organic_farm_top',array(
        'title' => __('Contact info', 'organic-farm'),
        'priority'=> 2,
        'panel' => 'organic_farm_custompage_panel',
    ) );
    $wp_customize->add_setting( 'organic_farm_section_contact_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_contact_heading', array(
		'label'       => esc_html__( 'Contact Settings', 'organic-farm' ),
        'description' => __( 'Add contact info in the below feilds', 'organic-farm' ),
		'section'     => 'organic_farm_top',
		'settings'    => 'organic_farm_section_contact_heading',
		'priority'    => 1,
	) ) );
    $wp_customize->add_setting('organic_farm_email_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_email_text',array(
		'label' => esc_html__('Add Text','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_email_text',
		'type'    => 'text'
	));
	$wp_customize->selective_refresh->add_partial( 'organic_farm_email_text', array(
		'selector' => '.mail-box',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_email_text',
	) );
	$wp_customize->add_setting('organic_farm_email',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_email'
	));
	$wp_customize->add_control('organic_farm_email',array(
		'label' => esc_html__('Add Email Address','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_email',
		'type'    => 'text'
	));
	$wp_customize->add_setting('organic_farm_email_icon',array(
		'default'	=> 'fas fa-envelope',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_email_icon',array(
		'label'	=> __('Add Email Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_top',
		'setting'	=> 'organic_farm_email_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_call_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_call_text',array(
		'label' => esc_html__('Add Text','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_call_text',
		'type'    => 'text'
	));
	$wp_customize->selective_refresh->add_partial( 'organic_farm_call_text', array(
		'selector' => '.phone-box',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_call_text',
	) );
	$wp_customize->add_setting('organic_farm_call',array(
		'default' => '',
		'sanitize_callback' => 'organic_farm_sanitize_phone_number'
	));
	$wp_customize->add_control('organic_farm_call',array(
		'label' => esc_html__('Add Phone Number','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_call',
		'type'    => 'text'
	));
	$wp_customize->add_setting('organic_farm_call_icon',array(
		'default'	=> 'fas fa-phone',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_call_icon',array(
		'label'	=> __('Add Phone Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_top',
		'setting'	=> 'organic_farm_call_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_quote_btn',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_quote_btn',array(
		'label' => esc_html__('Add Button Text','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_quote_btn',
		'type'    => 'text'
	));
	$wp_customize->selective_refresh->add_partial( 'organic_farm_quote_btn', array(
		'selector' => '.quote-btn',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_quote_btn',
	) );
    $wp_customize->add_setting('organic_farm_quote_btn_link',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('organic_farm_quote_btn_link',array(
		'label' => esc_html__('Add Button Link','organic-farm'),
		'section' => 'organic_farm_top',
		'setting' => 'organic_farm_quote_btn_link',
		'type'    => 'url'
	));

	// Social Media
    $wp_customize->add_section('organic_farm_urls',array(
        'title' => __('Social Media', 'organic-farm'),
        'priority'=> 2,
        'panel' => 'organic_farm_custompage_panel',
    ) );
    $wp_customize->add_setting( 'organic_farm_section_social_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_social_heading', array(
		'label'       => esc_html__( 'Social Media Settings', 'organic-farm' ),
		'description' => __( 'Add social media links in the below feilds', 'organic-farm' ),
		'section'     => 'organic_farm_urls',
		'settings'    => 'organic_farm_section_social_heading',
	) ) );
	$wp_customize->add_setting(
		'header_social_icon_enable',
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
			'header_social_icon_enable',
			array(
				'settings'        => 'header_social_icon_enable',
				'section'         => 'organic_farm_urls',
				'label'           => __( 'Check to show social fields', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'header_social_icon_enable', array(
		'selector' => '.links a i',
		'render_callback' => 'organic_farm_customize_partial_header_social_icon_enable',
	) );
	$wp_customize->add_setting( 'organic_farm_theme_twitter_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_theme_twitter_heading', array(
		'label'       => esc_html__( 'Twitter Settings', 'organic-farm' ),
		'section'     => 'organic_farm_urls',
		'settings'    => 'organic_farm_theme_twitter_heading',
	) ) );
    $wp_customize->add_setting('organic_farm_twitter_icon',array(
		'default'	=> 'fab fa-x-twitter',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_twitter_icon',array(
		'label'	=> __('Add Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_urls',
		'setting'	=> 'organic_farm_twitter_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_twitter',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('organic_farm_twitter',array(
		'label' => esc_html__('Add URL','organic-farm'),
		'section' => 'organic_farm_urls',
		'setting' => 'organic_farm_twitter',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'organic_farm_header_twt_target',
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
			'organic_farm_header_twt_target',
			array(
				'settings'        => 'organic_farm_header_twt_target',
				'section'         => 'organic_farm_urls',
				'label'           => __( 'Open link in a new tab', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'organic_farm_theme_fb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_theme_fb_heading', array(
		'label'       => esc_html__( 'Facebook Settings', 'organic-farm' ),
		'section'     => 'organic_farm_urls',
		'settings'    => 'organic_farm_theme_fb_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_fb_icon',array(
		'default'	=> 'fab fa-facebook-f',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_fb_icon',array(
		'label'	=> __('Add Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_urls',
		'setting'	=> 'organic_farm_fb_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_fb',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('organic_farm_fb',array(
		'label' => esc_html__('Add URL','organic-farm'),
		'section' => 'organic_farm_urls',
		'setting' => 'organic_farm_fb',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'organic_farm_header_fb_target',
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
			'organic_farm_header_fb_target',
			array(
				'settings'        => 'organic_farm_header_fb_target',
				'section'         => 'organic_farm_urls',
				'label'           => __( 'Open link in a new tab', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'organic_farm_theme_youtube_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_theme_youtube_heading', array(
		'label'       => esc_html__( 'Youtube Settings', 'organic-farm' ),
		'section'     => 'organic_farm_urls',
		'settings'    => 'organic_farm_theme_youtube_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_youtube_icon',array(
		'default'	=> 'fab fa-youtube',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_youtube_icon',array(
		'label'	=> __('Add Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_urls',
		'setting'	=> 'organic_farm_youtube_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_youtube',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('organic_farm_youtube',array(
		'label' => esc_html__('Add URL','organic-farm'),
		'section' => 'organic_farm_urls',
		'setting' => 'organic_farm_youtube',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'organic_farm_header_youtube_target',
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
			'organic_farm_header_youtube_target',
			array(
				'settings'        => 'organic_farm_header_youtube_target',
				'section'         => 'organic_farm_urls',
				'label'           => __( 'Open link in a new tab', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'organic_farm_theme_instagram_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_theme_instagram_heading', array(
		'label'       => esc_html__( 'Instagram Settings', 'organic-farm' ),
		'section'     => 'organic_farm_urls',
		'settings'    => 'organic_farm_theme_instagram_heading',
	) ) );
	$wp_customize->add_setting('organic_farm_instagram_icon',array(
		'default'	=> 'fab fa-instagram',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
        $wp_customize,'organic_farm_instagram_icon',array(
		'label'	=> __('Add Icon','organic-farm'),
		'transport' => 'refresh',
		'section'	=> 'organic_farm_urls',
		'setting'	=> 'organic_farm_instagram_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('organic_farm_instagram',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('organic_farm_instagram',array(
		'label' => esc_html__('Add URL','organic-farm'),
		'section' => 'organic_farm_urls',
		'setting' => 'organic_farm_instagram',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'organic_farm_header_instagram_target',
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
			'organic_farm_header_instagram_target',
			array(
				'settings'        => 'organic_farm_header_instagram_target',
				'section'         => 'organic_farm_urls',
				'label'           => __( 'Open link in a new tab', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

    //Slider
	$wp_customize->add_section( 'organic_farm_slider_section' , array(
    	'title'      => __( 'Slider Settings', 'organic-farm' ),
    	'priority'   => 2,
    	'panel' => 'organic_farm_custompage_panel',
	) );
	$wp_customize->add_setting( 'organic_farm_section_slide_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_slide_heading', array(
		'label'       => esc_html__( 'Slider Settings', 'organic-farm' ),
		'description' => __( 'Slider Image Dimension ( 1400 x 650 ) px', 'organic-farm' ),
		'section'     => 'organic_farm_slider_section',
		'settings'    => 'organic_farm_section_slide_heading',
		'priority'    => 1,
	) ) );
	$wp_customize->add_setting(
		'organic_farm_slider_arrows',
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
			'organic_farm_slider_arrows',
			array(
				'settings'        => 'organic_farm_slider_arrows',
				'section'         => 'organic_farm_slider_section',
				'label'           => __( 'Check To show Slider', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
				'priority'    => 1,
			)
		)
	);

	$wp_customize->add_setting('organic_farm_slider_count',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_slider_count',array(
		'label'	=> esc_html__('Slider Count','organic-farm'),
		'section'	=> 'organic_farm_slider_section',
		'description' => __( 'After increasing/decreasing counter refresh site for changes to be applied.', 'organic-farm' ),
		'type'		=> 'number',
		'priority'    => 1,
	));

	$organic_farm_slider_count = get_theme_mod('organic_farm_slider_count');

	$organic_farm_args = array('numberposts' => -1);
	$post_list = get_posts($organic_farm_args);
	$i = 0;
	$pst_sls[]= __('Select','organic-farm');
	foreach ($post_list as $key => $p_post) {
		$pst_sls[$p_post->ID]=$p_post->post_title;
	}
	for ( $i = 1; $i <= $organic_farm_slider_count; $i++ ) {
		$wp_customize->add_setting('organic_farm_post_setting'.$i,array(
			'sanitize_callback' => 'organic_farm_sanitize_choices',
		));
		$wp_customize->add_control('organic_farm_post_setting'.$i,array(
			'type'    => 'select',
			'choices' => $pst_sls,
			'label' => __('Select post','organic-farm'),
			'section' => 'organic_farm_slider_section',
			'priority'    => 1,
		));

		$wp_customize->selective_refresh->add_partial( 'organic_farm_post_setting'.$i, array(
			'selector' => '.carousel-caption',
			'render_callback' => 'organic_farm_customize_partial_organic_farm_post_setting'.$i,
		) );
	}
	wp_reset_postdata();

	$wp_customize->add_setting('organic_farm_slider_heading_color', array(
	    'default' => '#222222',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_slider_heading_color', array(
	    'section' => 'organic_farm_slider_section',
	    'label' => esc_html__('Slider Title Color', 'organic-farm'),
	 	'priority'    => 2,
	)));

	$wp_customize->add_setting(
		'organic_farm_slider_excerpt_show_hide',
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
			'organic_farm_slider_excerpt_show_hide',
			array(
				'settings'        => 'organic_farm_slider_excerpt_show_hide',
				'section'         => 'organic_farm_slider_section',
				'label'           => __( 'Show Hide excerpt', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'priority'    => 3,
			)
		)
	);
	$wp_customize->add_setting('organic_farm_slider_excerpt_count',array(
		'default'=> 25,
		'transport' => 'refresh',
		'sanitize_callback' => 'organic_farm_sanitize_integer'
	));
	$wp_customize->add_control(new Organic_Farm_Slider_Custom_Control( $wp_customize, 'organic_farm_slider_excerpt_count',array(
		'label' => esc_html__( 'Excerpt Limit','organic-farm' ),
		'section'=> 'organic_farm_slider_section',
		'settings'=>'organic_farm_slider_excerpt_count',
		'input_attrs' => array(
			'reset'			   => 25,
            'step'             => 1,
			'min'              => 0,
			'max'              => 50,
        ),
        'priority'    => 3,
	)));
	$wp_customize->add_setting('organic_farm_slider_excerpt_color', array(
	    'default' => '#222222',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_slider_excerpt_color', array(
	    'section' => 'organic_farm_slider_section',
	    'label' => esc_html__('Slider Excerpt Color', 'organic-farm'),
	 	'priority'    => 4,
	)));
	$wp_customize->add_setting(
		'organic_farm_slider_button_show_hide',
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
			'organic_farm_slider_button_show_hide',
			array(
				'settings'        => 'organic_farm_slider_button_show_hide',
				'section'         => 'organic_farm_slider_section',
				'label'           => __( 'Show Hide Button', 'organic-farm' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'priority'    => 5,
			)
		)
	);
	$wp_customize->add_setting('organic_farm_slider_read_more',array(
		'default' => 'Read More',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('organic_farm_slider_read_more',array(
		'label' => esc_html__('Button Text','organic-farm'),
		'section' => 'organic_farm_slider_section',
		'setting' => 'organic_farm_slider_read_more',
		'type'    => 'text',
		'priority'    => 5,
	));

	$wp_customize->add_setting('organic_farm_slider_content_alignment',array(
        'default' => 'LEFT-ALIGN',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_slider_content_alignment',array(
		'type' => 'radio',
		'label'     => __('Slider Content Alignment', 'organic-farm'),
		'section' => 'organic_farm_slider_section',
		'type' => 'select',
		'choices' => array(
			'LEFT-ALIGN' => __('LEFT','organic-farm'),
            'CENTER-ALIGN' => __('CENTER','organic-farm'),
            'RIGHT-ALIGN' => __('RIGHT','organic-farm'),
		),
		'priority'    => 6,
	) );

	$wp_customize->add_setting('organic_farm_slider_overlay', array(
	    'default' => '#222222',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'organic_farm_slider_overlay', array(
	    'section' => 'organic_farm_slider_section',
	    'label' => esc_html__('Slider Overlay Color', 'organic-farm'),
	 	'priority'    => 7,
	)));

	$wp_customize->add_setting('organic_farm_slider_opacity',array(
        'default' => '1',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_slider_opacity',array(
		'type' => 'radio',
		'label'     => __('Slider Opacity', 'organic-farm'),
		'section' => 'organic_farm_slider_section',
		'type' => 'select',
		'choices' => array(
			'0' => __('0','organic-farm'),
			'0.1' => __('0.1','organic-farm'),
			'0.2' => __('0.2','organic-farm'),
			'0.3' => __('0.3','organic-farm'),
			'0.4' => __('0.4','organic-farm'),
			'0.5' => __('0.5','organic-farm'),
			'0.6' => __('0.6','organic-farm'),
			'0.7' => __('0.7','organic-farm'),
			'0.8' => __('0.8','organic-farm'),
			'0.9' => __('0.9','organic-farm'),
			'1' => __('1','organic-farm')
		),
		'priority'    => 7,
	) );

	//Middle Section
	$wp_customize->add_section( 'organic_farm_middle_section' , array(
    	'title'      => __( 'Services Settings', 'organic-farm' ),
    	'panel' => 'organic_farm_custompage_panel',
		'priority'   => 2,
	) );
	$wp_customize->add_setting( 'organic_farm_section_middle_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_middle_heading', array(
		'label'       => esc_html__( 'Services Settings', 'organic-farm' ),
		'description' => __( 'Image Dimension ( 80 x 80 ) px', 'organic-farm' ),
		'section'     => 'organic_farm_middle_section',
		'settings'    => 'organic_farm_section_middle_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_services_show_hide',
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
			'organic_farm_services_show_hide',
			array(
				'settings'        => 'organic_farm_services_show_hide',
				'section'         => 'organic_farm_middle_section',
				'label'           => __( 'Check To show Section', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$organic_farm_args = array('numberposts' => -1);
	$post_list = get_posts($organic_farm_args);
	$s = 0;
	$pst_sls[]= __('Select','organic-farm');
	foreach ($post_list as $key => $p_post) {
		$pst_sls[$p_post->ID]=$p_post->post_title;
	}
	for ( $s = 1; $s <= 3; $s++ ) {
		$wp_customize->add_setting('organic_farm_middle_sec_settigs'.$s,array(
			'sanitize_callback' => 'organic_farm_sanitize_choices',
		));
		$wp_customize->add_control('organic_farm_middle_sec_settigs'.$s,array(
			'type'    => 'select',
			'choices' => $pst_sls,
			'label' => __('Select post','organic-farm'),
			'section' => 'organic_farm_middle_section',
		));
		$wp_customize->selective_refresh->add_partial( 'organic_farm_middle_sec_settigs'.$s, array(
			'selector' => '.inner-box',
			'render_callback' => 'organic_farm_customize_partial_organic_farm_middle_sec_settigs'.$s,
		) );

		$wp_customize->add_setting('organic_farm_service_icon'.$s,array(
			'default'	=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		));	
		$wp_customize->add_control(new Organic_Farm_Fontawesome_Icon_Chooser(
	        $wp_customize,'organic_farm_service_icon'.$s,array(
			'label'	=> __('Icon','organic-farm').$s,
			'transport' => 'refresh',
			'section'	=> 'organic_farm_middle_section',
			'setting'	=> 'organic_farm_service_icon',
			'type'		=> 'icon',
		)));
	}
	wp_reset_postdata();

	// Product Box
	$wp_customize->add_section( 'organic_farm_product_box_section' , array(
    	'title'      => __( 'Product Settings', 'organic-farm' ),
		'priority'   => 2,
		'panel' => 'organic_farm_custompage_panel',
	) );
	$wp_customize->add_setting( 'organic_farm_section_product_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_product_heading', array(
		'label'       => esc_html__( 'Product Settings', 'organic-farm' ),
		'section'     => 'organic_farm_product_box_section',
		'settings'    => 'organic_farm_section_product_heading',
	) ) );
	$wp_customize->add_setting(
		'organic_farm_services_product_hide',
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
			'organic_farm_services_product_hide',
			array(
				'settings'        => 'organic_farm_services_product_hide',
				'section'         => 'organic_farm_product_box_section',
				'label'           => __( 'Check To show Section', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);
	
	$wp_customize->add_setting('organic_farm_product_box_title',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_product_box_title',array(
		'label'	=> esc_html__('Section Title','organic-farm'),
		'section'	=> 'organic_farm_product_box_section',
		'type'		=> 'text',
	));


	$organic_farm_args = array(
		'type'                     => 'product',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'term_group',
		'order'                    => 'ASC',
		'hide_empty'               => false,
		'hierarchical'             => 1,
		'number'                   => '',
		'taxonomy'                 => 'product_cat',
		'pad_counts'               => false
	);
	$categories = get_categories($organic_farm_args);
	$cat_posts = array();
	$m = 0;
	$cat_posts[]='Select';
	foreach($categories as $category){
	if($m==0){
		$default = $category->slug;
			$m++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('organic_farm_product_box_category_number',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	$wp_customize->add_control('organic_farm_product_box_category_number',array(
		'label'	=> __('Number of products to show in a category','organic-farm'),
		'section' => 'organic_farm_product_box_section',
		'type'	  => 'number',
	));

	$wp_customize->add_setting('organic_farm_product_box_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'organic_farm_sanitize_select',
	));
	$wp_customize->add_control('organic_farm_product_box_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select category to display products ','organic-farm'),
		'section' => 'organic_farm_product_box_section',
	));

	$wp_customize->add_setting('organic_farm_product_order_type',array(
        'default' => 'ascending',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_product_order_type',array(
        'type' => 'select',
        'label' => __('Product Order','organic-farm'),
        'section' => 'organic_farm_product_box_section',
        'choices' => array(
            'ascending' => __('Oldest to Newest','organic-farm'),
            'descending' => __('Newest to Oldest','organic-farm'),
            'a-to-z' => __('A&rarr;Z','organic-farm'),
            'z-to-a' => __('Z&rarr;A','organic-farm'),
        ),
	) );
	

	$wp_customize->selective_refresh->add_partial( 'organic_farm_product_box_category', array(
		'selector' => '#product-box h3',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_product_box_category',
	) );

	//Footer
    $wp_customize->add_section( 'organic_farm_footer_copyright', array(
    	'title'      => esc_html__( 'Footer Text', 'organic-farm' ),
    	'panel' => 'organic_farm_custompage_panel',
	) );
	$wp_customize->add_setting( 'organic_farm_section_footer_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Organic_Farm_Customizer_Customcontrol_Section_Heading( $wp_customize, 'organic_farm_section_footer_heading', array(
		'label'       => esc_html__( 'Footer Settings', 'organic-farm' ),
		'section'     => 'organic_farm_footer_copyright',
		'settings'    => 'organic_farm_section_footer_heading',
		'priority'   => 1,
	) ) );
    $wp_customize->add_setting('organic_farm_footer_text',array(
		'default'	=> 'Organic Farm WordPress Theme',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('organic_farm_footer_text',array(
		'label'	=> esc_html__('Copyright Text','organic-farm'),
		'section'	=> 'organic_farm_footer_copyright',
		'type'		=> 'textarea'
	));
	$wp_customize->selective_refresh->add_partial( 'organic_farm_footer_text', array(
		'selector' => '.site-info a',
		'render_callback' => 'organic_farm_customize_partial_organic_farm_footer_text',
	) );
	$wp_customize->add_setting('organic_farm_footer_content_alignment',array(
        'default' => 'CENTER-ALIGN',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_footer_content_alignment',array(
		'type' => 'radio',
		'label'     => __('Footer Content Alignment', 'organic-farm'),
		'section' => 'organic_farm_footer_copyright',
		'type' => 'select',
		'choices' => array(
			'LEFT-ALIGN' => __('LEFT','organic-farm'),
            'CENTER-ALIGN' => __('CENTER','organic-farm'),
            'RIGHT-ALIGN' => __('RIGHT','organic-farm'),
		),
	) );

	$wp_customize->add_setting(
		'organic_farm_footer_widgets_show_hide',
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
			'organic_farm_footer_widgets_show_hide',
			array(
				'settings'        => 'organic_farm_footer_widgets_show_hide',
				'section'         => 'organic_farm_footer_copyright',
				'label'           => __( 'Check To show Footer Widgets', 'organic-farm' ),
				'choices'		  => array(
					'1'      => __( 'On', 'organic-farm' ),
					'off'    => __( 'Off', 'organic-farm' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting('organic_farm_footer_widget',array(
        'default' => '4',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('organic_farm_footer_widget',array(
		'type' => 'radio',
		'label'     => __('Footer Per Column', 'organic-farm'),
		'section' => 'organic_farm_footer_copyright',
		'type' => 'select',
		'choices' => array(
			'1' => __('1','organic-farm'),
            '2' => __('2','organic-farm'),
            '3' => __('3','organic-farm'),
            '4' => __('4','organic-farm'),
		)
	) );
	