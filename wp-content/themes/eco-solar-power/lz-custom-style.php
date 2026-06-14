<?php 

	$luzuk_eco_solar_power_custom_style = '';

	// Logo Size
	$luzuk_eco_solar_power_logo_top_padding = get_theme_mod('luzuk_eco_solar_power_logo_top_padding');
	$luzuk_eco_solar_power_logo_bottom_padding = get_theme_mod('luzuk_eco_solar_power_logo_bottom_padding');
	$luzuk_eco_solar_power_logo_left_padding = get_theme_mod('luzuk_eco_solar_power_logo_left_padding');
	$luzuk_eco_solar_power_logo_right_padding = get_theme_mod('luzuk_eco_solar_power_logo_right_padding');

	if( $luzuk_eco_solar_power_logo_top_padding != '' || $luzuk_eco_solar_power_logo_bottom_padding != '' || $luzuk_eco_solar_power_logo_left_padding != '' || $luzuk_eco_solar_power_logo_right_padding != ''){
		$luzuk_eco_solar_power_custom_style .=' .logo {';
			$luzuk_eco_solar_power_custom_style .=' padding-top: '.esc_attr($luzuk_eco_solar_power_logo_top_padding).'px; padding-bottom: '.esc_attr($luzuk_eco_solar_power_logo_bottom_padding).'px; padding-left: '.esc_attr($luzuk_eco_solar_power_logo_left_padding).'px; padding-right: '.esc_attr($luzuk_eco_solar_power_logo_right_padding).'px;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_logo_size = get_theme_mod('luzuk_eco_solar_power_logo_size');
	if( $luzuk_eco_solar_power_logo_size != '') {
		if($luzuk_eco_solar_power_logo_size == 100) {
			$luzuk_eco_solar_power_custom_style .=' .custom-logo-link img {';
				$luzuk_eco_solar_power_custom_style .=' width: 350px;';
			$luzuk_eco_solar_power_custom_style .=' }';
		} else if($luzuk_eco_solar_power_logo_size >= 10 && $luzuk_eco_solar_power_logo_size < 100) {
			$luzuk_eco_solar_power_custom_style .=' .custom-logo-link img {';
				$luzuk_eco_solar_power_custom_style .=' width: '.esc_attr($luzuk_eco_solar_power_logo_size).'%;';
			$luzuk_eco_solar_power_custom_style .=' }';
		}
	}

	// Header Image
	$header_image_url = luzuk_eco_solar_power_banner_image( $image_url = '' );
	if( $header_image_url != ''){
		$luzuk_eco_solar_power_custom_style .=' #inner-pages-header {';
			$luzuk_eco_solar_power_custom_style .=' background-image: url('. esc_url( $header_image_url ).') !important; background-size: cover; background-repeat: no-repeat; background-attachment: fixed;';
		$luzuk_eco_solar_power_custom_style .=' }';

		$luzuk_eco_solar_power_custom_style .='  #header .top-head {';
			$luzuk_eco_solar_power_custom_style .=' background: none ';
		$luzuk_eco_solar_power_custom_style .=' }';
	} else {
		$luzuk_eco_solar_power_custom_style .=' #inner-pages-header {';
			$luzuk_eco_solar_power_custom_style .=' background: radial-gradient(circle at center, rgba(0,0,0,0) 0%, #000000 100%); ';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_hide_show = get_theme_mod('luzuk_eco_solar_power_slider_hide_show',false);
	if( $luzuk_eco_solar_power_slider_hide_show == true){
		$luzuk_eco_solar_power_custom_style .=' .page-template-custom-home-page #inner-pages-header {';
			$luzuk_eco_solar_power_custom_style .=' display:none;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}


	$luzuk_eco_solar_power_headermailaddress_color = get_theme_mod('luzuk_eco_solar_power_headermailaddress_color');
	if ( $luzuk_eco_solar_power_headermailaddress_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #header .address p, #header .mail a, #header .address {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_headermailaddress_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headermailaddressicons_color = get_theme_mod('luzuk_eco_solar_power_headermailaddressicons_color');
	if ( $luzuk_eco_solar_power_headermailaddressicons_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #header .mail a i, #header .address i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_headermailaddressicons_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headermailhover_color = get_theme_mod('luzuk_eco_solar_power_headermailhover_color');
	if ( $luzuk_eco_solar_power_headermailhover_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #header .mail a:hover {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_headermailhover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headertopsocialicon_col = get_theme_mod('luzuk_eco_solar_power_headertopsocialicon_col');
	if ( $luzuk_eco_solar_power_headertopsocialicon_col != '') {
		$luzuk_eco_solar_power_custom_style .=' #header .s-media a i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_headertopsocialicon_col).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headertopsocialiconhover_col = get_theme_mod('luzuk_eco_solar_power_headertopsocialiconhover_col');
	if ( $luzuk_eco_solar_power_headertopsocialiconhover_col != '') {
		$luzuk_eco_solar_power_custom_style .=' #header .s-media a:hover i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_headertopsocialiconhover_col).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headerbottombg_col = get_theme_mod('luzuk_eco_solar_power_headerbottombg_col');
	if ( $luzuk_eco_solar_power_headerbottombg_col != '') {
		$luzuk_eco_solar_power_custom_style .='#header {';
			$luzuk_eco_solar_power_custom_style .=' background-color:'.esc_attr($luzuk_eco_solar_power_headerbottombg_col).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_headertopmbg_col = get_theme_mod('luzuk_eco_solar_power_headertopmbg_col');
	if ( $luzuk_eco_solar_power_headertopmbg_col != '') {
		$luzuk_eco_solar_power_custom_style .='#header .tphead {';
			$luzuk_eco_solar_power_custom_style .=' background-color:'.esc_attr($luzuk_eco_solar_power_headertopmbg_col).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_menu_color = get_theme_mod('luzuk_eco_solar_power_menu_color');
	if ( $luzuk_eco_solar_power_menu_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .primary-menu a, .primary-menu li .icon{';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_menu_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_menuhover_color = get_theme_mod('luzuk_eco_solar_power_menuhover_color');
	if ( $luzuk_eco_solar_power_menuhover_color != '') {
		$luzuk_eco_solar_power_custom_style .='.primary-menu li:hover .icon, .primary-menu li a:hover{';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_menuhover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_submenu_color = get_theme_mod('luzuk_eco_solar_power_submenu_color');
	if ( $luzuk_eco_solar_power_submenu_color != '') {
		$luzuk_eco_solar_power_custom_style .='.primary-menu ul a{';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_submenu_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_submenubg_color = get_theme_mod('luzuk_eco_solar_power_submenubg_color');
	if ( $luzuk_eco_solar_power_submenubg_color != '') {
		$luzuk_eco_solar_power_custom_style .='.primary-menu ul{';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_submenubg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_header_btntext_color = get_theme_mod('luzuk_eco_solar_power_header_btntext_color');
	if ( $luzuk_eco_solar_power_header_btntext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .headerbtn .butinn,.headerbtn a i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_header_btntext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}
	
	$luzuk_eco_solar_power_header_btnbg_color = get_theme_mod('luzuk_eco_solar_power_header_btnbg_color');
	if ( $luzuk_eco_solar_power_header_btnbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .headerbtn .butinn,.headerbtn a i {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_header_btnbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_header_btntexthover_color = get_theme_mod('luzuk_eco_solar_power_header_btntexthover_color');
	if ( $luzuk_eco_solar_power_header_btntexthover_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .headerbtn a:hover .butinn,.headerbtn a:hover i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_header_btntexthover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}


	//site title tagline
	$luzuk_eco_solar_power_site_title_color = get_theme_mod('luzuk_eco_solar_power_site_title_color');
	if ( $luzuk_eco_solar_power_site_title_color != '') {
		$luzuk_eco_solar_power_custom_style .=' h1.site-title a, p.site-title a{';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_site_title_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_site_tagline_color = get_theme_mod('luzuk_eco_solar_power_site_tagline_color');
	if ( $luzuk_eco_solar_power_site_tagline_color != '') {
		$luzuk_eco_solar_power_custom_style .=' p.site-description{';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_site_tagline_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	//layout width
	$luzuk_eco_solar_power_boxfull_width = get_theme_mod('luzuk_eco_solar_power_boxfull_width');
	if ($luzuk_eco_solar_power_boxfull_width !== '') {
		switch ($luzuk_eco_solar_power_boxfull_width) {
			case 'container':
				$luzuk_eco_solar_power_custom_style .= ' body, #header, .bottom-header {
					max-width: 1140px;
					width: 100%;
					padding-right: 15px;
					padding-left: 15px;
					margin-right: auto;
					margin-left: auto;
					}';
				break;
			case 'container-fluid':
				$luzuk_eco_solar_power_custom_style .= ' body, #header, .bottom-header { 
					width: 100%;
					padding-right: 15px;
					padding-left: 15px;
					margin-right: auto;
					margin-left: auto;
					}';
				break;
			case 'none':
				// No specific width specified, so no additional style needed.
				break;
			default:
				// Handle unexpected values.
				break;
		}
	}

	//Menu animation
	$luzuk_eco_solar_power_dropdown_anim = get_theme_mod('luzuk_eco_solar_power_dropdown_anim');

	if ( $luzuk_eco_solar_power_dropdown_anim != '') {
		$luzuk_eco_solar_power_custom_style .=' .primary-menu ul{';
			$luzuk_eco_solar_power_custom_style .=' animation:'.esc_attr($luzuk_eco_solar_power_dropdown_anim).' 1s ease;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}



	// slider colors
	$luzuk_eco_solar_power_slider_font_size = get_theme_mod('luzuk_eco_solar_power_slider_font_size');
	if ( $luzuk_eco_solar_power_slider_font_size != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider h2 {';
			$luzuk_eco_solar_power_custom_style .=' font-size:'.esc_attr($luzuk_eco_solar_power_slider_font_size).'px;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_text_font_size = get_theme_mod('luzuk_eco_solar_power_slider_text_font_size');
	if ( $luzuk_eco_solar_power_slider_text_font_size != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider p {';
			$luzuk_eco_solar_power_custom_style .=' font-size:'.esc_attr($luzuk_eco_solar_power_slider_text_font_size).'px;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_title_color = get_theme_mod('luzuk_eco_solar_power_slider_title_color');
	if ( $luzuk_eco_solar_power_slider_title_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider h2, #slider p {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_title_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_description_color = get_theme_mod('luzuk_eco_solar_power_slider_description_color');
	if ( $luzuk_eco_solar_power_slider_description_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .content p {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_description_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_btn1text_color = get_theme_mod('luzuk_eco_solar_power_slider_btn1text_color');
	if ( $luzuk_eco_solar_power_slider_btn1text_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .sbtn1inn,#slider .sbtn1 a i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_btn1text_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_btn1bg_color = get_theme_mod('luzuk_eco_solar_power_slider_btn1bg_color');
	if ( $luzuk_eco_solar_power_slider_btn1bg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .sbtn1inn,#slider .sbtn1 a i {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_slider_btn1bg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_btn1texthrv_color = get_theme_mod('luzuk_eco_solar_power_slider_btn1texthrv_color');
	if ( $luzuk_eco_solar_power_slider_btn1texthrv_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .sbtn1 a:hover .sbtn1inn, #slider .sbtn1 a:hover i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_btn1texthrv_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_btn2text_color = get_theme_mod('luzuk_eco_solar_power_slider_btn2text_color');
	if ( $luzuk_eco_solar_power_slider_btn2text_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .sbtn2 a {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_btn2text_color).';';
			$luzuk_eco_solar_power_custom_style .=' border-color:'.esc_attr($luzuk_eco_solar_power_slider_btn2text_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_btn2texthover_color = get_theme_mod('luzuk_eco_solar_power_slider_btn2texthover_color');
	if ( $luzuk_eco_solar_power_slider_btn2texthover_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .sbtn2 a:hover {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_btn2texthover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_arrowicon_color = get_theme_mod('luzuk_eco_solar_power_slider_arrowicon_color');
	if ( $luzuk_eco_solar_power_slider_arrowicon_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .carousel-control-prev i, #slider .carousel-control-next i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_slider_arrowicon_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_slider_arrowbg_color = get_theme_mod('luzuk_eco_solar_power_slider_arrowbg_color');
	if ( $luzuk_eco_solar_power_slider_arrowbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #slider .carousel-control-prev, #slider .carousel-control-next {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_slider_arrowbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}


	// aboutus colors
	$luzuk_eco_solar_power_aboutus_heading_color = get_theme_mod('luzuk_eco_solar_power_aboutus_heading_color');
	if ( $luzuk_eco_solar_power_aboutus_heading_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section h6, #aboutus-section h6 i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_heading_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_title_color = get_theme_mod('luzuk_eco_solar_power_aboutus_title_color');
	if ( $luzuk_eco_solar_power_aboutus_title_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section h5 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_title_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_description_color = get_theme_mod('luzuk_eco_solar_power_aboutus_description_color');
	if ( $luzuk_eco_solar_power_aboutus_description_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .description p {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_description_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_listicon_color = get_theme_mod('luzuk_eco_solar_power_aboutus_listicon_color');
	if ( $luzuk_eco_solar_power_aboutus_listicon_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .list li h4 i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_listicon_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_lists_color = get_theme_mod('luzuk_eco_solar_power_aboutus_lists_color');
	if ( $luzuk_eco_solar_power_aboutus_lists_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .list li h4 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_lists_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_btntext_color = get_theme_mod('luzuk_eco_solar_power_aboutus_btntext_color');
	if ( $luzuk_eco_solar_power_aboutus_btntext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .bttn a {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_btntext_color).';';
			$luzuk_eco_solar_power_custom_style .=' border-color:'.esc_attr($luzuk_eco_solar_power_aboutus_btntext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_btnhrv_color = get_theme_mod('luzuk_eco_solar_power_aboutus_btnhrv_color');
	if ( $luzuk_eco_solar_power_aboutus_btnhrv_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .bttn a:hover {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_btnhrv_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_yearofexpnum_color = get_theme_mod('luzuk_eco_solar_power_aboutus_yearofexpnum_color');
	if ( $luzuk_eco_solar_power_aboutus_yearofexpnum_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .expe h2 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_yearofexpnum_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_yearofexptext_color = get_theme_mod('luzuk_eco_solar_power_aboutus_yearofexptext_color');
	if ( $luzuk_eco_solar_power_aboutus_yearofexptext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .exptxt {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_yearofexptext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_yearofexpicon_color = get_theme_mod('luzuk_eco_solar_power_aboutus_yearofexpicon_color');
	if ( $luzuk_eco_solar_power_aboutus_yearofexpicon_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .expebx i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_aboutus_yearofexpicon_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_yearofexpiconbg_color = get_theme_mod('luzuk_eco_solar_power_aboutus_yearofexpiconbg_color');
	if ( $luzuk_eco_solar_power_aboutus_yearofexpiconbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .expebx i {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_aboutus_yearofexpiconbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_aboutus_yearofexpboxbg_color = get_theme_mod('luzuk_eco_solar_power_aboutus_yearofexpboxbg_color');
	if ( $luzuk_eco_solar_power_aboutus_yearofexpboxbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #aboutus-section .expebx {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_aboutus_yearofexpboxbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}


	//services colors
	$luzuk_eco_solar_power_services_subheading_color = get_theme_mod('luzuk_eco_solar_power_services_subheading_color');
	if ( $luzuk_eco_solar_power_services_subheading_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .head h4 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_subheading_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_subheading_color = get_theme_mod('luzuk_eco_solar_power_services_subheading_color');
	if ( $luzuk_eco_solar_power_services_subheading_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .head h4 i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_subheading_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_heading_color = get_theme_mod('luzuk_eco_solar_power_services_heading_color');
	if ( $luzuk_eco_solar_power_services_heading_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .head h2 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_heading_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_title_color = get_theme_mod('luzuk_eco_solar_power_services_title_color');
	if ( $luzuk_eco_solar_power_services_title_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .serbx h4 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_title_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}
	
	$luzuk_eco_solar_power_services_bottomdescription_color = get_theme_mod('luzuk_eco_solar_power_services_bottomdescription_color');
	if ( $luzuk_eco_solar_power_services_bottomdescription_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .txtbx p {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_bottomdescription_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_bottombtntext_color = get_theme_mod('luzuk_eco_solar_power_services_bottombtntext_color');
	if ( $luzuk_eco_solar_power_services_bottombtntext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .bttn1 {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_bottombtntext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_bottombtntext_color = get_theme_mod('luzuk_eco_solar_power_services_bottombtntext_color');
	if ( $luzuk_eco_solar_power_services_bottombtntext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .bttn1,#services-section .bttn2 i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_bottombtntext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_bottombtnbg_color = get_theme_mod('luzuk_eco_solar_power_services_bottombtnbg_color');
	if ( $luzuk_eco_solar_power_services_bottombtnbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .bttn1,#services-section .bttn2 i {';
			$luzuk_eco_solar_power_custom_style .=' background:'.esc_attr($luzuk_eco_solar_power_services_bottombtnbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_services_bottombtntexthrv_color = get_theme_mod('luzuk_eco_solar_power_services_bottombtntexthrv_color');
	if ( $luzuk_eco_solar_power_services_bottombtntexthrv_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #services-section .bttn a:hover .bttn1, #services-section .bttn a:hover .bttn2 i {';
			$luzuk_eco_solar_power_custom_style .=' color:'.esc_attr($luzuk_eco_solar_power_services_bottombtntexthrv_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}



	//footer colors
	$luzuk_eco_solar_power_footertext_color = get_theme_mod('luzuk_eco_solar_power_footertext_color');
	if ( $luzuk_eco_solar_power_footertext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #colophon h1, #colophon h2, #colophon h3, #colophon h4, #colophon h5,
		 #colophon h6,#colophon,#colophon p,.site-footer a, .site-footer p, #colophon caption, .site-footer .widget_rss .rss-date,
		  .site-footer .widget_rss li cite {';
			$luzuk_eco_solar_power_custom_style .=' color: '.esc_attr($luzuk_eco_solar_power_footertext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}	

	$luzuk_eco_solar_power_footeractivemenu_color = get_theme_mod('luzuk_eco_solar_power_footeractivemenu_color');
	if ( $luzuk_eco_solar_power_footeractivemenu_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .site-footer .current-menu-item a {';
			$luzuk_eco_solar_power_custom_style .=' color: '.esc_attr($luzuk_eco_solar_power_footeractivemenu_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}	

	$luzuk_eco_solar_power_footercopyright_color = get_theme_mod('luzuk_eco_solar_power_footercopyright_color');
	if ( $luzuk_eco_solar_power_footercopyright_color != '') {
		$luzuk_eco_solar_power_custom_style .=' #colophon .site-info p {';
			$luzuk_eco_solar_power_custom_style .=' color: '.esc_attr($luzuk_eco_solar_power_footercopyright_color).' !important;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_footercopyrightbrd_color = get_theme_mod('luzuk_eco_solar_power_footercopyrightbrd_color');
	if ( $luzuk_eco_solar_power_footercopyrightbrd_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .copyright {';
			$luzuk_eco_solar_power_custom_style .=' border-color: '.esc_attr($luzuk_eco_solar_power_footercopyrightbrd_color).' !important;';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_footerscrolltotoptext_color = get_theme_mod('luzuk_eco_solar_power_footerscrolltotoptext_color');
	if ( $luzuk_eco_solar_power_footerscrolltotoptext_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .back-to-top-text {';
			$luzuk_eco_solar_power_custom_style .=' color: '.esc_attr($luzuk_eco_solar_power_footerscrolltotoptext_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_footerscrolltotopbg_color = get_theme_mod('luzuk_eco_solar_power_footerscrolltotopbg_color');
	if ( $luzuk_eco_solar_power_footerscrolltotopbg_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .back-to-top {';
			$luzuk_eco_solar_power_custom_style .=' background: '.esc_attr($luzuk_eco_solar_power_footerscrolltotopbg_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_footerscrolltotoptexthover_color = get_theme_mod('luzuk_eco_solar_power_footerscrolltotoptexthover_color');
	if ( $luzuk_eco_solar_power_footerscrolltotoptexthover_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .back-to-top:hover .back-to-top-text {';
			$luzuk_eco_solar_power_custom_style .=' color: '.esc_attr($luzuk_eco_solar_power_footerscrolltotoptexthover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}

	$luzuk_eco_solar_power_footerscrolltotophover_color = get_theme_mod('luzuk_eco_solar_power_footerscrolltotophover_color');
	if ( $luzuk_eco_solar_power_footerscrolltotophover_color != '') {
		$luzuk_eco_solar_power_custom_style .=' .back-to-top:hover::after {';
			$luzuk_eco_solar_power_custom_style .=' background: '.esc_attr($luzuk_eco_solar_power_footerscrolltotophover_color).';';
		$luzuk_eco_solar_power_custom_style .=' }';
	}