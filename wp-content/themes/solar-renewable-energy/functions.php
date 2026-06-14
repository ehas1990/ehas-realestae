<?php
/**
 * Theme functions and definitions
 *
 * @package solar_renewable_energy
 */

// enque files
if ( ! function_exists( 'solar_renewable_energy_enqueue_styles' ) ) :
	/**
	 * Load assets.
	 *
	 * @since 1.0.0
	 */
	function solar_renewable_energy_enqueue_styles() {
		wp_enqueue_style( 'organic-farm-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'solar-renewable-energy-style', get_stylesheet_directory_uri() . '/style.css', array( 'organic-farm-style-parent' ), '1.0.0' );
		// Theme Customize CSS.
		require get_parent_theme_file_path( 'inc/extra_customization.php' );
		wp_add_inline_style( 'solar-renewable-energy-style',$organic_farm_custom_style );
		require get_theme_file_path( 'inc/extra_customization.php' );
		wp_add_inline_style( 'solar-renewable-energy-style',$organic_farm_custom_style );

		// blocks css
        wp_enqueue_style( 'solar-renewable-energy-block-style', get_theme_file_uri( '/assets/css/blocks.css' ), array( 'solar-renewable-energy-style' ), '1.0' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'solar_renewable_energy_enqueue_styles', 99 );

// theme setup
function solar_renewable_energy_setup() {
	load_theme_textdomain( 'solar-renewable-energy', get_template_directory() . '/languages' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( "responsive-embeds" );
	add_theme_support( "wp-block-styles" );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support('custom-background',array(
		'default-color' => 'ffffff',
	));
	add_image_size( 'solar-renewable-energy-featured-image', 2000, 1200, true );
	add_image_size( 'solar-renewable-energy-thumbnail-avatar', 100, 100, true );

	$GLOBALS['content_width'] = 525;
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'solar-renewable-energy' ),
	) );

	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	/*
	* This theme styles the visual editor to resemble the theme style,
	* specifically font, colors, and column width.
	*/
	add_editor_style( array( 'assets/css/editor-style.css', organic_farm_fonts_url() ) );

	if ( ! defined( 'ORGANIC_FARM_SUPPORT' ) ) {
		define('ORGANIC_FARM_SUPPORT',__('https://wordpress.org/support/theme/solar-renewable-energy','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_REVIEW' ) ) {
		define('ORGANIC_FARM_REVIEW',__('https://wordpress.org/support/theme/solar-renewable-energy/reviews/#new-post','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_LIVE_DEMO' ) ) {
	define('ORGANIC_FARM_LIVE_DEMO',__('https://trial.ovationthemes.com/solar-renewable-energy/','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_BUY_PRO' ) ) {
	define('ORGANIC_FARM_BUY_PRO',__('https://www.ovationthemes.com/products/solar-wordpress-theme','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_PRO_DOC' ) ) {
	define('ORGANIC_FARM_PRO_DOC',__('https://trial.ovationthemes.com/docs/ot-solar-renewable-energy-pro-doc/','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_FREE_DOC' ) ) {
	define('ORGANIC_FARM_FREE_DOC',__('https://trial.ovationthemes.com/docs/ot-solar-renewable-energy-free-doc/','solar-renewable-energy'));
	}
	if ( ! defined( 'ORGANIC_FARM_THEME_NAME' ) ) {
	define('ORGANIC_FARM_THEME_NAME',__('Premium Solar Theme','solar-renewable-energy'));
	}
}
add_action( 'after_setup_theme', 'solar_renewable_energy_setup' );

// header setup
function solar_renewable_energy_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'solar_renewable_energy_custom_header_args', array(
        'default-image'          => get_parent_theme_file_uri( '/assets/images/header-img-3.png' ),
        'default-text-color'     => 'fff',
        'header-text'            => false,
        'width'                  => 1200,
        'height'                 => 80,
        'flex-width'             => true,
        'flex-height'            => true,
        'wp-head-callback'       => 'organic_farm_header_style',
    ) ) );
}
add_action( 'after_setup_theme', 'solar_renewable_energy_custom_header_setup' );

// widgets
function solar_renewable_energy_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'solar-renewable-energy' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );

	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'solar-renewable-energy' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your pages and posts', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Sidebar 3', 'solar-renewable-energy' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'solar-renewable-energy' ),
		'id'            => 'footer-1',
		'description'   => __( 'Add widgets here to appear in your footer.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow slideInLeft %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'solar-renewable-energy' ),
		'id'            => 'footer-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow slideInLeft %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 3', 'solar-renewable-energy' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow slideInRight %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 4', 'solar-renewable-energy' ),
		'id'            => 'footer-4',
		'description'   => __( 'Add widgets here to appear in your footer.', 'solar-renewable-energy' ),
		'before_widget' => '<section id="%1$s" class="widget wow slideInRight %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'solar_renewable_energy_widgets_init' );

// remove section
function solar_renewable_energy_customize_register() {
  	global $wp_customize;
	$wp_customize->remove_section( 'organic_farm_pro' );
	$wp_customize->remove_section( 'organic_farm_product_box_section' );

	$wp_customize->remove_setting('organic_farm_slider_excerpt_show_hide');
	$wp_customize->remove_control('organic_farm_slider_excerpt_show_hide');

	$wp_customize->remove_setting( 'organic_farm_slider_opacity' );
	$wp_customize->remove_control( 'organic_farm_slider_opacity' );

	$wp_customize->remove_setting('organic_farm_footer_text');
	$wp_customize->remove_control('organic_farm_footer_text');

	$wp_customize->remove_setting( 'organic_farm_primary_color' );
	$wp_customize->remove_control( 'organic_farm_primary_color' );
	$wp_customize->remove_setting( 'organic_farm_secondary_color' );
	$wp_customize->remove_control( 'organic_farm_secondary_color' );
	$wp_customize->remove_setting( 'organic_farm_slider_heading_color' );
	$wp_customize->remove_control( 'organic_farm_slider_heading_color' );
	$wp_customize->remove_setting( 'organic_farm_slider_excerpt_color' );
	$wp_customize->remove_control( 'organic_farm_slider_excerpt_color' );

}
add_action( 'customize_register', 'solar_renewable_energy_customize_register', 11 );


// customizer
function solar_renewable_energy_customize( $wp_customize ) {

	wp_enqueue_style('customizercustom_css', esc_url( get_stylesheet_directory_uri() ). '/assets/css/customizer.css');

	require get_theme_file_path( 'inc/custom-control.php' );

	// pro section
	$wp_customize->add_section('solar_renewable_energy_pro', array(
		'title'    => __('🔒 Unlock Premium Features', 'solar-renewable-energy'),
		'priority' => 1,
	));
	$wp_customize->add_setting('solar_renewable_energy_pro', array(
		'default'           => null,
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control(new Solar_Renewable_Energy_Pro_Control($wp_customize, 'solar_renewable_energy_pro', array(
		'label'    => __('SOLAR ENERGY PREMIUM', 'solar-renewable-energy'),
		'section'  => 'solar_renewable_energy_pro',
		'settings' => 'solar_renewable_energy_pro',
		'priority' => 1,
	)));

	// slider
	$wp_customize->add_setting('solar_renewable_energy_slider_heading_color', array(
	    'default' => '#ffffff',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'solar_renewable_energy_slider_heading_color', array(
	    'section' => 'organic_farm_slider_section',
	    'label' => esc_html__('Slider Title Color', 'solar-renewable-energy'),
	 	'priority'    => 2,
	)));
	$wp_customize->add_setting(
		'solar_renewable_energy_slider_excerpt_show_hide',
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
		new Solar_Renewable_Energy_Customizer_Customcontrol_Switch(
			$wp_customize,
			'solar_renewable_energy_slider_excerpt_show_hide',
			array(
				'settings'        => 'solar_renewable_energy_slider_excerpt_show_hide',
				'section'         => 'organic_farm_slider_section',
				'label'           => __( 'Show Hide excerpt', 'solar-renewable-energy' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'solar-renewable-energy' ),
					'off'    => __( 'Off', 'solar-renewable-energy' ),
				),
				'priority'    => 3,
			)
		)
	);
	$wp_customize->add_setting('solar_renewable_energy_slider_excerpt_color', array(
	    'default' => '#ffffff',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'solar_renewable_energy_slider_excerpt_color', array(
	    'section' => 'organic_farm_slider_section',
	    'label' => esc_html__('Slider Excerpt Color', 'solar-renewable-energy'),
	 	'priority'    => 4,
	)));

	$wp_customize->add_setting('solar_renewable_energy_slider_opacity',array(
        'default' => '0.5',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('solar_renewable_energy_slider_opacity',array(
		'type' => 'radio',
		'label'     => __('Slider Opacity', 'solar-renewable-energy'),
		'section' => 'organic_farm_slider_section',
		'type' => 'select',
		'choices' => array(
			'0' => __('0','solar-renewable-energy'),
			'0.1' => __('0.1','solar-renewable-energy'),
			'0.2' => __('0.2','solar-renewable-energy'),
			'0.3' => __('0.3','solar-renewable-energy'),
			'0.4' => __('0.4','solar-renewable-energy'),
			'0.5' => __('0.5','solar-renewable-energy'),
			'0.6' => __('0.6','solar-renewable-energy'),
			'0.7' => __('0.7','solar-renewable-energy'),
			'0.8' => __('0.8','solar-renewable-energy'),
			'0.9' => __('0.9','solar-renewable-energy'),
			'1' => __('1','solar-renewable-energy')
		),
		'priority'    => 7,
	) );

	//Category Section
	$wp_customize->add_section('solar_renewable_energy_category_section',array(
		'title'	=> __('Category Section','solar-renewable-energy'),
		'priority'=> 5,
		'panel' => 'organic_farm_custompage_panel',
	));
	$wp_customize->add_setting( 'solar_renewable_energy_section_category_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Solar_Renewable_Energy_Customizer_Customcontrol_Section_Heading( $wp_customize, 'solar_renewable_energy_section_category_heading', array(
		'label'       => esc_html__( 'Category Section', 'solar-renewable-energy' ),
		'section'     => 'solar_renewable_energy_category_section',
		'settings'    => 'solar_renewable_energy_section_category_heading',
	) ) );
	$wp_customize->add_setting(
		'solar_renewable_energy_cate_show_hide',
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
		new Solar_Renewable_Energy_Customizer_Customcontrol_Switch(
			$wp_customize,
			'solar_renewable_energy_cate_show_hide',
			array(
				'settings'        => 'solar_renewable_energy_cate_show_hide',
				'section'         => 'solar_renewable_energy_category_section',
				'label'           => __( 'Check To Show Section', 'solar-renewable-energy' ),
				'choices'		  => array(
					'1'      => __( 'On', 'solar-renewable-energy' ),
					'off'    => __( 'Off', 'solar-renewable-energy' ),
				),
				'active_callback' => '',
			)
		)
	);
    $wp_customize->add_setting('solar_renewable_energy_cate_title',array(
		'default' => '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('solar_renewable_energy_cate_title',array(
		'label'	=> __('Section Title','solar-renewable-energy'),
		'section' => 'solar_renewable_energy_category_section',
		'type' => 'text',
	));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	$cat_pst[]= 'select';
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_pst[$category->slug] = $category->name;
	}
	$wp_customize->add_setting('solar_renewable_energy_category_setting',array(
		'default' => 'select',
		'sanitize_callback' => 'organic_farm_sanitize_choices',
	));
	$wp_customize->add_control('solar_renewable_energy_category_setting',array(
		'type' => 'select',
		'choices' => $cat_pst,
		'label' => __('Select Category to display Post','solar-renewable-energy'),
		'section' => 'solar_renewable_energy_category_section',
	));

	$wp_customize->add_setting('solar_renewable_energy_category_number',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	$wp_customize->add_control('solar_renewable_energy_category_number',array(
		'label'	=> __('Number of post to show in a category','solar-renewable-energy'),
		'section' => 'solar_renewable_energy_category_section',
		'type'	  => 'number',
	));

	$wp_customize->add_setting('solar_renewable_energy_post_order_type',array(
        'default' => 'ascending',
        'sanitize_callback' => 'organic_farm_sanitize_choices'
	));
	$wp_customize->add_control('solar_renewable_energy_post_order_type',array(
        'type' => 'select',
        'label' => __('Post Order','solar-renewable-energy'),
        'section' => 'solar_renewable_energy_category_section',
        'choices' => array(
            'ascending' => __('Oldest to Newest','solar-renewable-energy'),
            'descending' => __('Newest to Oldest','solar-renewable-energy'),
            'a-to-z' => __('A&rarr;Z','solar-renewable-energy'),
            'z-to-a' => __('Z&rarr;A','solar-renewable-energy'),
        ),
	) );

	$wp_customize->add_setting('solar_renewable_energy_footer_text',array(
		'default'	=> 'Solar Renewable Energy WordPress Theme',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('solar_renewable_energy_footer_text',array(
		'label'	=> esc_html__('Copyright Text','solar-renewable-energy'),
		'section'	=> 'organic_farm_footer_copyright',
		'type'		=> 'textarea'
	));
	$wp_customize->selective_refresh->add_partial( 'solar_renewable_energy_footer_text', array(
		'selector' => '.site-info a',
		'render_callback' => 'organic_farm_customize_partial_solar_renewable_energy_footer_text',
	) );


	//colors
	$wp_customize->add_setting('solar_renewable_energy_primary_color', array(
	    'default' => '#70bf4a',
	    'sanitize_callback' => 'sanitize_hex_color',
	    'transport' => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'solar_renewable_energy_primary_color', array(
	    'section' => 'colors',
	    'label' => esc_html__('Theme Color', 'solar-renewable-energy'),
	 
	)));
}
add_action( 'customize_register', 'solar_renewable_energy_customize' );

// comments
function solar_renewable_energy_enqueue_comments_reply() {
  if( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1) ) {
    // Load comment-reply.js (into footer)
    wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', array(), false, true );
  }
}
add_action(  'wp_enqueue_scripts', 'solar_renewable_energy_enqueue_comments_reply' );

// Footer Text
function solar_renewable_energy_copyright_link() {
    $solar_renewable_energy_footer_text = get_theme_mod('solar_renewable_energy_footer_text', esc_html__('Solar Renewable Energy WordPress Theme', 'solar-renewable-energy'));
    $solar_renewable_energy_credit_link = esc_url('https://www.ovationthemes.com/products/free-solar-energy-wordpress-theme');

    echo '<a href="' . $solar_renewable_energy_credit_link . '" target="_blank">' . esc_html($solar_renewable_energy_footer_text) . '<span class="footer-copyright">' . esc_html__(' By Ovation Themes', 'solar-renewable-energy') . '</span></a>';
}

/* Pro control */
if (class_exists('WP_Customize_Control') && !class_exists('Solar_Renewable_Energy_Pro_Control')):
    class Solar_Renewable_Energy_Pro_Control extends WP_Customize_Control{

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
