<?php //to use wp udpate plugin

  $home_id=''; $blog_id=''; $page_id=''; $about_id='';


  // Function to check if a page with a specific title exists
  function page_exists_by_title($title) {
    $page_query = new WP_Query(array(
        'post_type'   => 'page',
        'title'       => $title,
        'post_status' => 'publish',
        'numberposts' => 1
    ));
    
    if ($page_query->have_posts()) {
        // Return the ID of the first matching page
        $page = $page_query->posts[0];
        return $page->ID;
    }
  
    return false; // Return false if no page found
  }

  //Homepage
  $home_title = 'Home';
  if (!page_exists_by_title($home_title)) {
    $home_content = '';
    $home = array(
      'post_type'    => 'page',
      'post_title'   => $home_title,
      'post_content' => $home_content,
      'post_status'  => 'publish',
      'post_author'  => 1,
      'post_name'    => 'home'
    );

    $home_id = wp_insert_post($home);
    
    // Set the home page template
    add_post_meta($home_id, '_wp_page_template', 'page-template/custom-home-page.php');
    
    // Set the static front page
    update_option('page_on_front', $home_id);
    update_option('show_on_front', 'page');

  }else {
    // Get the ID of the existing page
    $home_id = page_exists_by_title($home_title);

    // Set the home page template
    add_post_meta($home_id, '_wp_page_template', 'page-template/custom-home-page.php');
    
    // Set the static front page
    update_option('page_on_front', $home_id);
    update_option('show_on_front', 'page');
  }
  


  // Create a Page if it doesn't exist
  if ( !page_exists_by_title('Page') ) {
    $page_title = 'Page';
    $content = 'Te obtinuit ut adepto satis somno. Aliisque institoribus iter deliciae vivet vita. Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semel';

    $ot_page = array(
      'post_type'     => 'page',
      'post_title'    => $page_title,
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_name'     => 'page'
    );
    $page_id = wp_insert_post($ot_page);
  }else {
    // Get the ID of the existing page
    $ot_page = page_exists_by_title('Page');
  }

  if ( !page_exists_by_title('Page Left Sidebar') ) {
    $page_title = 'Page Left Sidebar';
    $content = 'Te obtinuit ut adepto satis somno. Aliisque institoribus iter deliciae vivet vita. Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semelTe obtinuit ut adepto satis somno. Aliisque institoribus iter deliciae vivet vita. Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semel.Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semel.';

    $ot_page = array(
      'post_type'     => 'page',
      'post_title'    => $page_title,
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_name'     => 'page-left'
    );
    $page_id = wp_insert_post($ot_page);

    // Set the page template
    add_post_meta($page_id, '_wp_page_template', 'page-template/left-sidebar.php');
  }else {
    // Get the ID of the existing page
    $ot_page = page_exists_by_title('Page Left Sidebar');
  }

  if ( !page_exists_by_title('Page Right Sidebar') ) {
    $page_title = 'Page Right Sidebar';
    $content = 'Te obtinuit ut adepto satis somno. Aliisque institoribus iter deliciae vivet vita. Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semelTe obtinuit ut adepto satis somno. Aliisque institoribus iter deliciae vivet vita. Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semel.Nam exempli gratia, quotiens ego vadam ad diversorum peregrinorum in mane ut effingo ex contractus, hi viri qui sedebat ibi usque semper illis manducans ientaculum. Solum cum bulla ut debui; EGO youd adepto a macula proiciendi. Sed quis scit si forte quod esset optima res pro me. sicut ea quae sentio. Qui vellem cadunt off ius desk ejus! Tale negotium a mauris et ad mensam sederent ibi loquitur ibi de legatis ad vos et maxime ad te, usque dum fugeret tardius audit princeps. Bene tamen fiduciam Ego got off semel.';

    $ot_page = array(
      'post_type'     => 'page',
      'post_title'    => $page_title,
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_name'     => 'page-right'
    );
    $page_id = wp_insert_post($ot_page);

    // Set the page template
    add_post_meta($page_id, '_wp_page_template', 'page-template/right-sidebar.php');
  }else {
    // Get the ID of the existing page
    $ot_page = page_exists_by_title('Page Right Sidebar');
  } 

  // ------- Create Main Menu --------
  $menuname =  'Primary Menu';
  $bpmenulocation = 'primary';
  $menu_exists = wp_get_nav_menu_object( $menuname );

  if (!$menu_exists) {
    // Create the menu
    $menu_id = wp_create_nav_menu($menuname);

    // Add the HOME item
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Home', 'solar-renewable-energy'),
        'menu-item-classes' => 'home',
        'menu-item-url'     => home_url('/index.php/home/'),
        'menu-item-status'  => 'publish'
    ));

    // Add the PAGE item
    $parent_page_item_id = wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Pages', 'solar-renewable-energy'),
        'menu-item-classes' => 'page',
        'menu-item-url'     => home_url('/index.php/page/'),
        'menu-item-status'  => 'publish'
    ));

    // Add the Page Left Sidebar item as a child of PAGE
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'   => __('Page Left Sidebar', 'solar-renewable-energy'),
        'menu-item-classes' => 'page-left',
        'menu-item-url'     => home_url('/index.php/page-left/'),
        'menu-item-status'  => 'publish',
        'menu-item-parent-id' => $parent_page_item_id
    ));

    // Add the Page Right Sidebar item as a child of PAGE
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'   => __('Page Right Sidebar', 'solar-renewable-energy'),
        'menu-item-classes' => 'page-right',
        'menu-item-url'     => home_url('/index.php/page-right/'),
        'menu-item-status'  => 'publish',
        'menu-item-parent-id' => $parent_page_item_id
    ));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Quality', 'solar-renewable-energy'),
        'menu-item-classes' => 'quality',
        'menu-item-url'     => '#',
        'menu-item-status'  => 'publish'
    ));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Categories', 'solar-renewable-energy'),
        'menu-item-classes' => 'categories',
        'menu-item-url'     => '#',
        'menu-item-status'  => 'publish'
    ));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Top Brands', 'solar-renewable-energy'),
        'menu-item-classes' => 'top brands',
        'menu-item-url'     => '#',
        'menu-item-status'  => 'publish'
    ));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('About', 'solar-renewable-energy'),
        'menu-item-classes' => 'about',
        'menu-item-url'     => '#',
        'menu-item-status'  => 'publish'
    ));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => __('Blog', 'solar-renewable-energy'),
        'menu-item-classes' => 'blog',
        'menu-item-url'     => '#',
        'menu-item-status'  => 'publish'
    ));
    
    // Assign the menu to the desired location if not already assigned
    if (!has_nav_menu($bpmenulocation)) {
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
  }
     
  // --------Header------------------------

  set_theme_mod( 'organic_farm_email_text', 'Email Address' ); 

  set_theme_mod( 'organic_farm_email', 'organic@support.com' ); 

  set_theme_mod( 'organic_farm_email_icon', 'fas fa-envelope' ); 

  set_theme_mod( 'organic_farm_call_text', '24/7 In Touch' ); 

  set_theme_mod( 'organic_farm_call', '000012321545648' );

  set_theme_mod( 'organic_farm_call_icon', 'fas fa-phone' );

  set_theme_mod( 'organic_farm_quote_btn_link', '#' );

  set_theme_mod( 'organic_farm_quote_btn', 'Get a Quote' );

  // --------Social icons------------------------

  set_theme_mod( 'organic_farm_twitter', 'https://twitter.com/' );

  set_theme_mod( 'organic_farm_fb', 'https://facebook.com/' ); 

  set_theme_mod( 'organic_farm_youtube', 'https://youtube.com/' ); 

  set_theme_mod( 'organic_farm_instagram', 'https://instagram.com/' );

  //-------------- Slider-----------------------

  set_theme_mod('organic_farm_slider_count','4');

  for($i=1;$i<=4;$i++){

    $title = "Energize Society By Reliable Energy";
    $content = 'As a world wide distributor of solar supplies we endeavor to provide fast and knowledgeable service, we can get you materials by sea or air.';

    // Create post object
    $organic_farm_my_post = array(
     'post_title'    => wp_strip_all_tags( $title ),
     'post_content'  => $content,
     'post_status'   => 'publish',
     'post_type'     => 'post',
    );

    $organic_farm_slider_post_id = wp_insert_post($organic_farm_my_post);

    $organic_farm_post_image_url = get_template_directory_uri().'/assets/images/header-img-3.png';

    $organic_farm_image_name = 'header-img-3.png';
    $organic_farm_upload_dir       = wp_upload_dir(); 
    // Set upload folder
    $organic_farm_image_data       = file_get_contents($organic_farm_post_image_url); 
     
    // Get image data
    $organic_farm_unique_file_name = wp_unique_filename( $organic_farm_upload_dir['path'], $organic_farm_image_name ); 
    // Generate unique name
    $filename= basename( $organic_farm_unique_file_name ); 
    // Create image file name
    // Check folder permission and define file location
    if( wp_mkdir_p( $organic_farm_upload_dir['path'] ) ) {
        $file = $organic_farm_upload_dir['path'] . '/' . $filename;
    } else {
        $file = $organic_farm_upload_dir['basedir'] . '/' . $filename;
    }
    file_put_contents( $file, $organic_farm_image_data );
    $wp_filetype = wp_check_filetype( $filename, null );
    $organic_farm_attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_type'     => 'post',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment( $organic_farm_attachment, $file, $organic_farm_slider_post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $organic_farm_slider_post_id, $attach_id );

    set_theme_mod('organic_farm_post_setting' . $i, $organic_farm_slider_post_id);

  }

  //-------------- Service-----------------------

  $organic_farm_service_title=array('Efficiency & Power','Trust & Warranty','High Quality Work');

  $organic_farm_service_icon=array('fas fa-bolt','fas fa-leaf','fas fa-certificate');

  for($i=1;$i<=3;$i++){

    $title = $organic_farm_service_title[$i-1];
    $content = 'There are many types of passages many types of Lorem';

    // Create post object
    $organic_farm_service_my_post = array(
     'post_title'    => wp_strip_all_tags( $title ),
     'post_content'  => $content,
     'post_status'   => 'publish',
     'post_type'     => 'post',
    );

    $organic_farm_service_post_id = wp_insert_post($organic_farm_service_my_post);

    set_theme_mod('organic_farm_middle_sec_settigs' . $i, $organic_farm_service_post_id);

    set_theme_mod( 'organic_farm_service_icon'.$i, $organic_farm_service_icon[$i-1]);

  }

  //-------------- project section-----------------------
    
  set_theme_mod( 'solar_renewable_energy_cate_title', 'Recent Projects' );

  $solar_renewable_energy_project_category = wp_create_category('recent projects'); 
  
  set_theme_mod( 'solar_renewable_energy_category_number', '4' );

  for($i=1;$i<=4;$i++){

    $title = "qui blanditiis praesentaiusm voluptatum deleniti atque";
    $content = 'Et harum quidem rerum facilis est expedita distinctio. Nam libero tempore, cum soluta nobis est eligendiEt harum quidem rerum facilis est expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi';

    // Create post object
    $solar_renewable_energy_my_post = array(
     'post_title'    => wp_strip_all_tags( $title ),
     'post_content'  => $content,
     'post_status'   => 'publish',
     'post_type'     => 'post',
     'post_category' => array($solar_renewable_energy_project_category),
    );

    $solar_renewable_energy_project_post_id = wp_insert_post($solar_renewable_energy_my_post);

    $solar_renewable_energy_project_image_url = get_stylesheet_directory_uri().'/assets/project'.$i.'.png';

    $solar_renewable_energy_project_image_name = 'project'.$i.'.png';
    $solar_renewable_energy_project_upload_dir       = wp_upload_dir(); 
    // Set upload folder
    $solar_renewable_energy_project_image_data       = file_get_contents($solar_renewable_energy_project_image_url); 
     
    // Get image data
    $solar_renewable_energy_project_unique_file_name = wp_unique_filename( $solar_renewable_energy_project_upload_dir['path'], $solar_renewable_energy_project_image_name ); 
    // Generate unique name
    $filename= basename( $solar_renewable_energy_project_unique_file_name ); 
    // Create image file name
    // Check folder permission and define file location
    if( wp_mkdir_p( $solar_renewable_energy_project_upload_dir['path'] ) ) {
        $file = $solar_renewable_energy_project_upload_dir['path'] . '/' . $filename;
    } else {
        $file = $solar_renewable_energy_project_upload_dir['basedir'] . '/' . $filename;
    }
    file_put_contents( $file, $solar_renewable_energy_project_image_data );
    $wp_filetype = wp_check_filetype( $filename, null );
    $solar_renewable_energy_project_attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_type'     => 'post',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment( $solar_renewable_energy_project_attachment, $file, $solar_renewable_energy_project_post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $solar_renewable_energy_project_post_id, $attach_id );

  }

  set_theme_mod( 'solar_renewable_energy_category_setting', 'recent projects' );

?>