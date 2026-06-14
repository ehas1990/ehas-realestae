<?php
/**
 * Template Name: Custom Home
 */
get_header(); ?>

<main id="skip-content" role="main">

	<?php do_action( 'luzuk_eco_solar_power_above_slider' ); ?>

	<?php if( get_theme_mod('luzuk_eco_solar_power_slider_hide_show') != ''){ ?>
	<section id="slider">	  
		
			<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			    <?php $luzuk_eco_solar_power_slider_pages = array();
			    for ( $count = 1; $count <= 4; $count++ ) {
			        $mod = intval( get_theme_mod( 'luzuk_eco_solar_power_slider'. $count ));
			        if ( 'page-none-selected' != $mod ) {
			          $luzuk_eco_solar_power_slider_pages[] = $mod;
			        }
			    }
		      	if( !empty($luzuk_eco_solar_power_slider_pages) ) :
			        $args = array(
			          	'post_type' => 'page',
			          	'post__in' => $luzuk_eco_solar_power_slider_pages,
			          	'orderby' => 'post__in'
			        );
		        	$query = new WP_Query( $args );
		        if ( $query->have_posts() ) :
		          	$i = 1;
		    	?>   
		    	 <div class="carousel-inner" role="listbox">
		    	<!-- <div class="container">    -->
				   
				      	<?php  while ( $query->have_posts() ) : $query->the_post(); ?>
				        <div <?php if($i == 1){echo 'class="carousel-item fade-in-image active"';} else{ echo 'class="carousel-item fade-in-image"';}?>>
							<div class="overlay"></div>
							<div class="slideimg">
								<?php
								// Check if the post has a thumbnail
								if (has_post_thumbnail()) {
									// If post has thumbnail, display it
									?>
									<img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
									<?php
								} else {
									// If post does not have thumbnail, display default image
									?>
									<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/abt1.jpg'); ?>" alt="Default Image" />
									<?php
								}
								?>
								<div class="slider-overlay"></div> 
							</div>
												
							<div class="content">
								<!-- <h5></?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_excerpt')); ?></h5>  -->
								<h2> <?php the_title(); ?> </h2>
								<?php 
									$luzuk_eco_solar_power_slider_excerpt_length = get_theme_mod('luzuk_eco_solar_power_slider_excerpt_length','15');
								
									if( $luzuk_eco_solar_power_slider_excerpt_length != ''){?>
									<p ><?php $luzuk_eco_solar_power_excerpt = get_the_excerpt(); echo esc_html( luzuk_eco_solar_power_string_limit_words( $luzuk_eco_solar_power_excerpt, esc_attr(get_theme_mod('luzuk_eco_solar_power_slider_excerpt_length','15') ) )); ?></p>
								<?php } ?>
								<div class="sbtn" >
									<div class="sbtn1" >
										<a href="<?php echo esc_url(get_theme_mod('luzuk_eco_solar_power_sliderbtnlink', '#')); ?>">
											<div class="sbtn1inn" >
												<?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_sliderbtntext', 'Book a Call')); ?>
											</div>
											<i class="fa-solid fa-arrow-right"></i>
										</a>

									</div>
									<div class="sbtn2" >
										<a href="<?php echo esc_url(get_theme_mod('luzuk_eco_solar_power_sliderbtn2link', '#')); ?>">
											<?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_sliderbtn2text', 'Explore Our Services')); ?>
										</a>
									</div>
								</div>
							</div>
				        </div>
				      	<?php $i++; endwhile; 
				      	wp_reset_postdata();?>
				    <!-- </div> -->
			    <?php else : ?>

			    	</div>
			    	<div class="no-postfound"></div>
	      		<?php endif;
			    endif;?>
			<div class="slidebtn">
			    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
						
					<span class="carousel-control-prev-icon" aria-hidden="true">
					  <i class="fas fa-chevron-left"></i>
			      	</span>
			      	<span class="screen-reader-text"><?php esc_html_e( 'Prev','eco-solar-power' );?></span>
			    </a>
			    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
					
					<span class="carousel-control-next-icon" aria-hidden="true">
					  <i class="fas fa-chevron-right"></i>
			      	</span>
			      	<span class="screen-reader-text"><?php esc_html_e( 'Next','eco-solar-power' );?></span>
			    </a>
			</div>
		  	<div class="clearfix"></div>
		</div> 
	</section>
	<?php }?>
	
	<?php do_action('luzuk_eco_solar_power_below_slider'); ?>

	<section id="aboutus-section">
		<div class="container">
			<div class="row mr-0">
				<div class="leftside">
					<div class="sub-imginn">
						<?php 
							$luzuk_eco_solar_power_aboutus_image1 = get_theme_mod('luzuk_eco_solar_power_aboutus_image1');

							if(!empty($luzuk_eco_solar_power_aboutus_image1)){
								echo '<img alt="'. esc_html(get_the_title()) .'" src="'.esc_url($luzuk_eco_solar_power_aboutus_image1).'" class="img-responsive secondry-bg-img" />';
							}else{
								echo '<img alt="luzuk_eco_solar_power_aboutus_image1" src="'.get_template_directory_uri().'/assets/images/abthead.jpg" class="img-responsive" />';
							}
						?>
					</div>
					<div class="middlebx">
					<?php 
						$luzuk_eco_solar_power_aboutus_image2 = get_theme_mod('luzuk_eco_solar_power_aboutus_image2');

						if(!empty($luzuk_eco_solar_power_aboutus_image2)){
							echo '<img alt="'. esc_html(get_the_title()) .'" src="'.esc_url($luzuk_eco_solar_power_aboutus_image2).'" class="img-responsive secondry-bg-img" />';
						}else{
							echo '<img alt="luzuk_eco_solar_power_aboutus_image2" src="'.get_template_directory_uri().'/assets/images/abt3.jpg" class="img-responsive" />';
						}
					?>
					</div>
					<div class="sub-imgbtm">
						<?php 
							$luzuk_eco_solar_power_aboutus_image3 = get_theme_mod('luzuk_eco_solar_power_aboutus_image3');

							if(!empty($luzuk_eco_solar_power_aboutus_image3)){
								echo '<img alt="'. esc_html(get_the_title()) .'" src="'.esc_url($luzuk_eco_solar_power_aboutus_image3).'" class="img-responsive secondry-bg-img" />';
							}else{
								echo '<img alt="luzuk_eco_solar_power_aboutus_image3" src="'.get_template_directory_uri().'/assets/images/abtbtm.jpg" class="img-responsive" />';
							}
						?>
					</div>

					<div class="expebx">
						<div class="expe">
							<h2><i class="fa-solid fa-star-of-david"></i> <?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutusyearofexperiencenum', '25+')); ?></h2>
							<div class="exptxt"><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutusyearofexperiencetext', 'Years Of Experience')); ?></div>
						</div>
					</div>

				</div>

				<div class="rightside">
				
						<div class="sub-title">
							<h6><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutusheading', 'About us')); ?></h6>
						</div>

						<div class="title">
							<h5><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustitle', 'WE PROVIDE THE BEST SOLAR ENERGY SOLUTIONS')); ?></h5>
						</div>

						<div class="description">
							<p><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutusdescription', 'The solar solution company specializes in providing innovation, eco-friendly energy systems that harness the sun power, reducing carbon footprints and energy costs of residental, commercial, and industrial clients worldwide.')); ?></p>
						</div>

						<div class="list">
							<ul>
								<li>									
									<h4> <i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list1', 'Lorem Ipsum are')); ?></h4>
								</li>
								<li>					
									<h4> <i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list2', 'Lorem Ipsum are')); ?></h4>
								</li>
								<li>					
									<h4> <i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list3', 'Lorem Ipsum are')); ?></h4>
								</li>
							</ul>
						</div>
						<div class="list">
							<ul>
								<li>											
									<h4><i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list4', 'Lorem Ipsum are')); ?></h4>
								</li>
								<li>	
									<h4><i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list5', 'Lorem Ipsum are')); ?></h4>
								</li>
								<li>	
									<h4><i class="fa-solid fa-circle-check"></i><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutustabsyears1list6', 'Lorem Ipsum are')); ?></h4>
								</li>
							</ul>
						</div>
						<div class="clearfix"> </div>
						<div class="bttn">
							<a href="<?php echo esc_url(get_theme_mod('luzuk_eco_solar_power_aboutusbtnlink', '#')); ?>">
								<?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_aboutusbtntext', 'Learn More')); ?>
							</a>
						</div>
					
				</div>
			</div>
		</div>
	</section>
	
	<?php do_action('luzuk_eco_solar_power_below_aboutus_section'); ?>

	<section id="services-section">
		<div class="container"> 
			<div class="headbx">		
				<h4 class="subheading"><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_servicessubheading')); ?></h4>
				<h2 class="heading"><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_servicesheading')); ?></h2>				
			</div>
		</div>
		<div class="container">
			<div class="content">
				<div class="row mr-0">
					<?php
					// Check if any page is selected from customizer
					$pages_selected = false;
					for ($i = 1; $i <= 6; $i++) {
						$selected_page_id = get_theme_mod('luzuk_eco_solar_power_page_setting_' . $i);
						if ($selected_page_id) {
							$pages_selected = true;
							break;
						}
					}

					// Display pages in slider if selected, otherwise show a message
					if ($pages_selected) {
						// Loop through each selected page and display in the slider
						for ($i = 1; $i <= 7; $i++) {
							$selected_page_id = get_theme_mod('luzuk_eco_solar_power_page_setting_' . $i);
							if ($selected_page_id) {
								$page = get_post($selected_page_id);
								?>
								<!-- <div class="item"> -->
								<div class="mainserbx">
									<div class="serbx">
										<div class="serbxinn">
											<a href="<?php echo get_permalink($page->ID); ?>">
												<h4><?php echo $page->post_title; ?> <i class="fa-solid fa-arrow-right"></i></h4>
												
											</a>
											<a href="<?php echo get_permalink($page->ID); ?>">
												<?php echo get_the_post_thumbnail($page->ID, 'medium'); ?>
											</a>
											
										</div>
									</div>
								</div>

							<?php }
						}
					} else {
						// Display message if no pages are selected
						echo '<p>Please select pages from the customizer</p>';
					}
					?>
				</div>
			</div>
			<div class="services-bottom">
				<div class="txtbx">
					<div class="row mr-0">
						<p><?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_servicesbottom_description')); ?></p>
						<div class="bttn">
							<a href="<?php echo esc_url(get_theme_mod('luzuk_eco_solar_power_servicesbottombtnlink', '#')); ?>">
								<div class="bttn1">
									<?php echo esc_html(get_theme_mod('luzuk_eco_solar_power_servicesbottombtntext', 'See More Services')); ?>
								</div>
								<div class="bttn2">
									<i class="fa-solid fa-arrow-right"></i>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php do_action('luzuk_eco_solar_power_below_services_section'); ?>

	<div class="container">
	  	<?php while ( have_posts() ) : the_post(); ?>
	  		<div class="lz-content">
	        	<?php the_content(); ?>
	        </div>
	    <?php endwhile; // end of the loop. ?>
	</div>
</main>

<?php get_footer(); ?>