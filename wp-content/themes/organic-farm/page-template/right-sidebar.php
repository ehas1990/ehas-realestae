<?php
/*
 Template Name: Right Sidebar Template
*/
get_header(); ?>

<main id="content" class="site-main" role="main">
	<?php $organic_farm_header_option = get_theme_mod( 'organic_farm_show_header_image','on');
		if($organic_farm_header_option == 'on'){ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="outer-div">
				<?php if(has_post_thumbnail()){ ?>
		             <div class="single-post-image">
						<?php the_post_thumbnail(); ?>
					</div>
		        <?php }
		            else { ?>
		            	<div class="header-image"></div>
		        <?php } ?>
				<div class="inner-div">
					<?php //breadcrumb
					if ( !is_page_template( 'page-template/custom-home-page.php' ) ) { ?>
						<div class="bread_crumb single_breadcrumb align-self-center text-center">
							<?php organic_farm_breadcrumb();  ?>
						</div>
					<?php } ?>
		    		<h2 class="mt-4 text-center"><?php the_title();?></h2>				
		    	</div>
			</div>
		<?php endwhile; ?>
		<div class="content-area my-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8">
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="entry-content">
									<?php the_content(); ?>
									<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . __( 'Pages:', 'organic-farm' ),
											'after'  => '</div>',
										) );
									?>
								</div>
							</article>
						<?php endwhile; // End of the loop. ?>
					</div>
					<div id="sidebar" class="col-lg-4 col-md-4">
						<?php dynamic_sidebar('sidebar-2'); ?>
			            <div class="clearfix"></div>
					</div>
					<div class="clearfix"></div> 
				</div>
			</div>	
		</div>
	<?php }
	else if($organic_farm_header_option == 'off'){ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="without-img-head py-5">
				<?php //breadcrumb
				if ( !is_page_template( 'page-template/custom-home-page.php' ) ) { ?>
					<div class="bread_crumb single_breadcrumb align-self-center text-center">
						<div class="without-img">
							<?php organic_farm_breadcrumb();  ?>
						</div>
					</div>
				<?php } ?>
	    		<h2 class="my-4 withoutimg text-center"><span><?php the_title();?></span></h2>			
			</div>
		<?php endwhile; ?>
		<div class="content-area my-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8">
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="entry-content">
									<?php if(has_post_thumbnail()){ ?>
										<div class="pb-4"><?php the_post_thumbnail(); ?></div>
									<?php } ?>
									<?php the_content(); ?>
									<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . __( 'Pages:', 'organic-farm' ),
											'after'  => '</div>',
										) );
									?>
								</div>
							</article>
						<?php endwhile; // End of the loop. ?>
					</div>
					<div id="sidebar" class="col-lg-4 col-md-4">
						<?php dynamic_sidebar('sidebar-2'); ?>
			            <div class="clearfix"></div>
					</div>
					<div class="clearfix"></div> 
				</div>
			</div>	
		</div>
	<?php } ?>
</main>

<?php get_footer();