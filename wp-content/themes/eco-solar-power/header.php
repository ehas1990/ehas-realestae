<?php
/**
 * The header for our theme
 *
 * @subpackage Eco Solar Power
 * @since 1.0
 * @version 0.1
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}?>

<a class="screen-reader-text skip-link" href="#skip-content"><?php esc_html_e( 'Skip to content', 'eco-solar-power' ); ?></a>

<div id="header">
	<div class="main-header">
		<div class="container">
			<div class="tphead">		
				<div class="row mr-0">
					<div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-6 col-4 mailbx">
						<div class="mail">
							<?php
								$mail_number = esc_html(get_theme_mod('luzuk_eco_solar_power_header_mail', 'info@example.com'));

								if (!empty($mail_number)) {
									
									echo '
										<a href="mailto:' . $mail_number . '"> <i class="far fa-envelope"></i> <span class="tooltiptext"> '  . $mail_number . '</a> </span>';
								}
							?>
						</div>

						<div class="address">				
							<?php
								$address_number = esc_html(get_theme_mod('luzuk_eco_solar_power_header_address', 'JI. Raya Puputan No 142, NY - 80234'));

								if (!empty($address_number)) {
									
									echo '
										<p> <i class="fa-solid fa-location-dot"></i> <span class="tooltiptext">'  . $address_number . '</p> </span>' ;
								}
							?>
						</div>

					</div>
					<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-8 pd-0">
						<div class="s-media">

							<li>
								<?php 
									$fb_link = get_theme_mod('luzuk_eco_solar_power_fblink', '#');
									if ($fb_link) { 
									?>
										<a href="<?php echo esc_html($fb_link); ?>">
											<i class="fab fa-facebook-f"></i>
										</a>
								<?php } ?>
							</li>
							
							<li>
								<?php 
									$instagram_link = get_theme_mod('luzuk_eco_solar_power_instagramlink', '#');
									if ($instagram_link) { 
									?>
										<a href="<?php echo esc_html($instagram_link); ?>">
											<i class="fa-brands fa-instagram"></i>
										</a>
								<?php } ?>
							</li>

							<li>
								<?php 
									$twitter_link = get_theme_mod('luzuk_eco_solar_power_twitterlink', '#');
									if ($twitter_link) { 
									?>
										<a href="<?php echo esc_html($twitter_link); ?>">
											<i class="fa-brands fa-x-twitter"></i>
										</a>
								<?php } ?>
							</li> 

							<li>
								<?php 
									$linkedin_link = get_theme_mod('luzuk_eco_solar_power_linkedinlink', '#');
									if ($linkedin_link) { 
									?>
										<a href="<?php echo esc_html($linkedin_link); ?>">
											<i class="fa-brands fa-linkedin"></i>				
										</a>
								<?php } ?>
							</li>
						
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="bott-head">
				<div class="row mr-0">
					<div class="col-lg-3 col-md-5 col-sm-5 col-6">
						<div class="logo">
							<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php endif; ?>
							<?php if (get_theme_mod('luzuk_eco_solar_power_show_site_title',true)) {?>
								<?php $blog_info = get_bloginfo( 'name' ); ?>
								<?php if ( ! empty( $blog_info ) ) : ?>
									<?php if ( is_front_page() && is_home() ) : ?>
										<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
									<?php else : ?>
										<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
									<?php endif; ?>
								<?php endif; ?>
							<?php }?>
							<?php if (get_theme_mod('luzuk_eco_solar_power_show_tagline',true)) {?>
								<?php $description = get_bloginfo( 'description', 'display' );
								if ( $description || is_customize_preview() ) : ?>
									<p class="site-description"><?php echo esc_html($description); ?></p>
								<?php endif; ?>
							<?php }?>
						</div>
					</div>
					<div class="col-lg-6 col-md-2 col-sm-2 col-2 m-headbox">
						<div class="m-head">
							<div class="header-inner section-inner">
								<div class="header-titles-wrapper">
									<button class="toggle nav-toggle mobile-nav-toggle" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
										<span class="toggle-inner">
											<span class="toggle-icon">
												<i class="fas fa-bars"></i>
											</span>
											<!-- <span class="toggle-text"><//?php _e( 'Menu', 'eco-solar-power' ); ?></span> -->
										</span>
									</button><!-- .nav-toggle -->
								</div><!-- .header-titles-wrapper -->

								<div class="header-navigation-wrapper">
									<?php
									if ( has_nav_menu( 'primary' ) || ! has_nav_menu( 'expanded' ) ) {
										?>
										<nav class="primary-menu-wrapper" aria-label="<?php echo esc_attr_x( 'Horizontal', 'menu', 'eco-solar-power' ); ?>">
											<ul class="primary-menu reset-list-style">
												<?php
												if ( has_nav_menu( 'primary' ) ) {

													wp_nav_menu(
														array(
															'container'  => '',
															'items_wrap' => '%3$s',
															'theme_location' => 'primary',
														)
													);

												} elseif ( ! has_nav_menu( 'expanded' ) ) {

													wp_list_pages(
														array(
															'match_menu_classes' => true,
															'show_sub_menu_icons' => true,
															'title_li' => false,
															'walker'   => new Eco_Solar_Power_Walker_Page(),
														)
													);

												}
												?>
											</ul>
										</nav><!-- .primary-menu-wrapper -->
									<?php } ?>
								</div><!-- .header-navigation-wrapper -->
							</div><!-- .header-inner -->
							<?php
								// Output the menu modal.
								get_template_part( '/inc/modal-menu' );
							?>
							<!-- </div> -->
						</div>
					</div>
					<div class="col-lg-3 col-md-5 col-sm-5 col-4 h-btnbox">
						<div class="headerbtn">
							<a href="<?php echo esc_url(get_theme_mod('luzuk_eco_solar_power_headerbtnlink', '#')); ?>">
								<div class="butinn">
									<?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_headerbtntext', 'Contact Us')); ?>
								</div>
								<i class="fa-solid fa-arrow-right"></i>
								<div class="clearfix"></div>
								
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(is_singular()) {?>
	<div id="inner-pages-header">
		<div class="header-overlay"></div>
	    <div class="header-content">
		    <div class="container"> 
		      	<h1><?php single_post_title(); ?></h1>
		      	<div class="innheader-border"></div>
		      	<div class="theme-breadcrumb mt-2">
					<?php luzuk_eco_solar_power_breadcrumb();?>
				</div>
		    </div>
		</div>
	</div>
<?php } ?>