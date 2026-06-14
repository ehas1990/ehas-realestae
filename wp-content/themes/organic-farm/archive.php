<?php
/**
 * The template for displaying archive pages
 *
 * @subpackage Organic Farm
 * @since 1.0
 */

get_header(); ?>

<main id="content">
	<?php $organic_farm_header_option = get_theme_mod( 'organic_farm_show_header_image','on');
		if($organic_farm_header_option == 'on'){ ?>
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<div class="header-image"></div>
					<div class="internal-div">
						<?php //breadcrumb
						if ( !is_page_template( 'page-template/custom-home-page.php' ) ) { ?>
							<div class="bread_crumb archieve_breadcrumb align-self-center text-center">
								<?php organic_farm_breadcrumb();  ?>
							</div>
						<?php } ?>
						<?php
							the_archive_title( '<h1 class="page-title mt-4 text-center"><span>', '</span></h1>' );
							the_archive_description( '<div class="taxonomy-description mt-4 text-center">', '</div>' );
						?>
					</div>
				</header>
			<?php endif; ?>
		<?php }
		else if($organic_farm_header_option == 'off'){ ?>
			<?php if ( have_posts() ) : ?>
				<header class="mt-4">
					<?php //breadcrumb
					if ( !is_page_template( 'page-template/custom-home-page.php' ) ) { ?>
						<div class="bread_crumb archieve_breadcrumb align-self-center text-center">
							<div class="without-img">
								<?php organic_farm_breadcrumb();  ?>
							</div>
						</div>
					<?php } ?>
					<?php
						the_archive_title( '<h1 class="page-title withoutimg mt-4 text-center"><span>', '</span></h1>' );
						the_archive_description( '<div class="taxonomy-description mt-4 text-center">', '</div>' );
					?>
				</header>
			<?php endif; ?>
		<?php } ?>
	<div class="container">
		<div class="content-area my-5">
			<div id="main" class="site-main" role="main">
		    	<div class="row m-0">	    		
			        <?php
					get_template_part( 'template-parts/post/post-layout' );
					?>
				</div>		
			</div>
		</div>
	</div>
</main>

<?php get_footer();