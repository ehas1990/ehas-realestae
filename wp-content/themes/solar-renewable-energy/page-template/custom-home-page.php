<?php
/**
 * Template Name: Custom Home Page
 */
get_header(); ?>

<main id="content">
  <?php if( get_option('organic_farm_slider_arrows', false) !== 'off'){ ?>
    <section id="slider">
      <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel"> 
        <?php $organic_farm_slider_count = get_theme_mod('organic_farm_slider_count'); ?>
        <?php
          for ( $i = 1; $i <= $organic_farm_slider_count; $i++ ) {
            $mod =  get_theme_mod( 'organic_farm_post_setting' . $i );
            if ( 'page-none-selected' != $mod ) {
              $organic_farm_slide_post[] = $mod;
            }
          }
           if( !empty($organic_farm_slide_post) ) :
          $args = array(
            'post_type' =>array('post','page'),
            'post__in' => $organic_farm_slide_post,
            'ignore_sticky_posts'  => true, // Exclude sticky posts by default
          );

          // Check if specific posts are selected
          if (empty($organic_farm_slide_post) && is_sticky()) {
              $args['post__in'] = get_option('sticky_posts');
          }

          $query = new WP_Query( $args );
          if ( $query->have_posts() ) :
            $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <?php  while ( $query->have_posts() ) : $query->the_post(); ?>
          <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
            <?php if(has_post_thumbnail()){ ?>
              <img src="<?php the_post_thumbnail_url('full'); ?>"/>
            <?php }else{?>
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/header-img-3.png" alt="" />
            <?php } ?>
            <div class="carousel-caption slider-inner">
              <h2><?php the_title();?></h2>
              <?php if( get_option('solar_renewable_energy_slider_excerpt_show_hide',false) != 'off'){ ?>
                <p class="slider-excerpt mb-0"><?php echo wp_trim_words(get_the_content(), get_theme_mod('organic_farm_slider_excerpt_count',25) );?></p>
              <?php } ?>
              <div class="home-btn my-4">
                <a class="py-3 px-4" href="<?php the_permalink(); ?>"><?php echo esc_html(get_theme_mod('organic_farm_slider_read_more',__('Read More','solar-renewable-energy'))); ?></a>
              </div>
            </div>
          </div>
          <?php $i++; endwhile;
          wp_reset_postdata();?>
        </div>
        <?php else : ?>
        <div class="no-postfound"></div>
          <?php endif;
        endif;?>
          <a class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
          </a>
          <a class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
          </a>
      </div>
      <div class="clearfix"></div>
    </section>
  <?php }?>

  <?php if( get_option('organic_farm_services_show_hide', false) !== 'off'){ ?>
    <section id="middle-sec">
      <div class="container">
        <div class="row">
          <?php
            for ( $organic_farm_s = 1; $organic_farm_s <= 3; $organic_farm_s++ ) {
              $organic_farm_mod =  get_theme_mod( 'organic_farm_middle_sec_settigs' . $organic_farm_s );
              if ( 'page-none-selected' != $organic_farm_mod ) {
                $organic_farm_post[] = $organic_farm_mod;
              }
            }
             if( !empty($organic_farm_post) ) :
            $organic_farm_args = array(
              'post_type' =>array('post','page'),
              'post__in' => $organic_farm_post,
              'ignore_sticky_posts'  => true, // Exclude sticky posts by default
            );
            // Check if specific posts are selected
            if (empty($organic_farm_post) && is_sticky()) {
                $organic_farm_args['post__in'] = get_option('sticky_posts');
            }
            
            $organic_farm_query = new WP_Query( $organic_farm_args );
            if ( $organic_farm_query->have_posts() ) :
              $organic_farm_s = 1;
          ?>
          <?php  while ( $organic_farm_query->have_posts() ) : $organic_farm_query->the_post(); ?>
            <div class="col-lg-4 col-md-4 wow zoomIn">
              <div class="inner-box p-3 text-center text-md-start text-lg-start">
                <div class="row">
                  <div class="col-lg-4 col-md-12 align-self-center text-center">
                    <i class="<?php echo esc_attr(get_theme_mod('organic_farm_service_icon' . $organic_farm_s)); ?>"></i>
                  </div>
                  <div class="col-lg-8 col-md-12 ps-lg-0 align-self-center">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4>
                    <p class="mb-0"><?php echo wp_trim_words( get_the_content(), 8 );?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php $organic_farm_s++; endwhile;
          wp_reset_postdata();?>
          <?php else : ?>
          <div class="no-postfound"></div>
            <?php endif;
          endif;?>
        </div>
      </div>
    </section>
  <?php }?>

  <?php if( get_option('solar_renewable_energy_cate_show_hide', false) !== 'off'){ ?>
    <section id="home-mission" class="py-5">
      <div class="container">
        <?php if( get_theme_mod('solar_renewable_energy_cate_title') != '' ){ ?>
          <h3 class="text-center"><?php echo esc_html(get_theme_mod('solar_renewable_energy_cate_title','')); ?></h3>
          <div class="ico-border my-4 mx-auto"><i class="fab fa-envira text-center"></i></div>
        <?php }?>
        
        <?php $solar_renewable_energy_catData1 =  get_theme_mod('solar_renewable_energy_category_setting');
        $solar_renewable_energy_post_order = get_theme_mod('solar_renewable_energy_post_order_type','ascending');
          if($solar_renewable_energy_catData1){ 
            $args = array(
              'post_type' => 'post',
              'category_name' => esc_html($solar_renewable_energy_catData1 ,'solar-renewable-energy'),
              'posts_per_page' => get_theme_mod('solar_renewable_energy_category_number'),
              'order'          => 'ASC', // Default order
            ); 
            // Adjust ordering based on user selection
              if ($solar_renewable_energy_post_order == 'descending') {
                $args['order'] = 'DESC';
              } else if ($solar_renewable_energy_post_order == 'a-to-z') {
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
              } else if ($solar_renewable_energy_post_order == 'z-to-a') {
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
              }
            $i=1; ?>
          <div class="row">
            <?php $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
            while( $query->have_posts() ) : $query->the_post(); ?>
              <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="cat-box mb-4">
                  <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5">
                      <div class="cat-img">
                        <?php if(has_post_thumbnail()){ ?>
                          <?php the_post_thumbnail(); ?>
                        <?php }?>
                      </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                      <div class="cat-content">
                        <h4><?php the_title(); ?></h4>
                         <p class="mb-0"><?php echo wp_trim_words( get_the_content(),15 );?></p>
                        <a class="" href="<?php the_permalink(); ?>"><?php echo esc_html('Read More','solar-renewable-energy'); ?></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $i++; endwhile; 
              wp_reset_postdata(); ?>
            <?php else : ?>
              <div class="no-postfound"></div>
            <?php endif; ?>
          </div>
        <?php }?>
      </div>
    </section>
  <?php }?>

  <section id="custom-page-content" <?php if ( have_posts() && trim( get_the_content() ) !== '' ) echo 'class="pt-3"'; ?>>
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>