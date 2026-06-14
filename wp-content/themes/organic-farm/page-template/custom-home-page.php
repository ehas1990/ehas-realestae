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
            $organic_farm_mod =  get_theme_mod( 'organic_farm_post_setting' . $i );
            if ( 'page-none-selected' != $organic_farm_mod ) {
              $organic_farm_slide_post[] = $organic_farm_mod;
            }
          }
           if( !empty($organic_farm_slide_post) ) :
          $organic_farm_args = array(
            'post_type' =>array('post','page'),
            'post__in' => $organic_farm_slide_post,
            'ignore_sticky_posts'  => true, // Exclude sticky posts by default
          );

          // Check if specific posts are selected
          if (empty($organic_farm_slide_post) && is_sticky()) {
              $organic_farm_args['post__in'] = get_option('sticky_posts');
          }

          $organic_farm_query = new WP_Query( $organic_farm_args );
          if ( $organic_farm_query->have_posts() ) :
            $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <?php  while ( $organic_farm_query->have_posts() ) : $organic_farm_query->the_post(); ?>
          <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
            <?php if(has_post_thumbnail()){ ?>
              <img src="<?php the_post_thumbnail_url('full'); ?>"/>
            <?php }else{?>
              <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/header-img.png" alt="" />
            <?php } ?>
            <div class="carousel-caption slider-inner">
              <h2 class="slid-head"><?php the_title();?></h2>
              <?php if( get_option('organic_farm_slider_excerpt_show_hide',true) != 'off'){ ?>
                <p class="slider-excerpt mb-0"><?php echo wp_trim_words(get_the_content(), get_theme_mod('organic_farm_slider_excerpt_count',25) );?></p>
              <?php } ?>
              <div class="home-btn my-4">
                <a class="py-3 px-4" href="<?php the_permalink(); ?>"><?php echo esc_html(get_theme_mod('organic_farm_slider_read_more',__('Read More','organic-farm'))); ?></a>
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

  <?php if( get_option('organic_farm_services_product_hide', false) !== 'off'){ ?> 
    <section id="product-box" class="my-5">
      <div class="container">
        <?php if( get_theme_mod('organic_farm_product_box_title') != '' ){ ?>
          <h3 class="text-center mb-3"><?php echo esc_html(get_theme_mod('organic_farm_product_box_title','')); ?></h3>
        <?php }?>
        <div class="ico-border my-4 mx-auto"><i class="fab fa-envira text-center"></i></div>
        <div class="row mt-5 mx-0">
          <?php
          $organic_farm_catData = get_theme_mod('organic_farm_product_box_category');
          $organic_farm_count_catData = get_theme_mod('organic_farm_product_box_category_number');
          $organic_farm_product_order = get_theme_mod('organic_farm_product_order_type','ascending');
          if ( class_exists( 'WooCommerce' ) ) {

              // Default query arguments
              $organic_farm_args = array(
                'post_type'      => 'product',
                'posts_per_page' => $organic_farm_count_catData,
                'product_cat'    => $organic_farm_catData,
                'order'          => 'ASC', // Default order
              );
              // Adjust ordering based on user selection
              if ($organic_farm_product_order == 'descending') {
                $organic_farm_args['order'] = 'DESC';
              } else if ($organic_farm_product_order == 'a-to-z') {
                $organic_farm_args['orderby'] = 'title';
                $organic_farm_args['order'] = 'ASC';
              } else if ($organic_farm_product_order == 'z-to-a') {
                $organic_farm_args['orderby'] = 'title';
                $organic_farm_args['order'] = 'DESC';
              }
            $loop = new WP_Query( $organic_farm_args );
            while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
              <div class="col-lg-3 col-md-6 col-sm-6 text-center mb-4">
                <div class="product-wrapper wow zoomIn" data-wow-duration="2s">
                  <div class="product-img wrapper mb-3">
                    <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, ''); else echo '<img src="'.esc_url(wc_placeholder_img_src()).'" />'; ?>
                    <div class="sale-tag">
                      <span><?php woocommerce_show_product_sale_flash( $post, $product ); ?></span>
                    </div>
                  </div>
                  <div class="product-details text-center">
                    <h4><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h4>
                    <span><?php esc_attr( apply_filters( 'woocommerce_product_price_class', '' ) ); ?><?php echo ( $product->get_price_html()); ?></span>
                    <?php if( $product->is_type( 'simple' ) ) { woocommerce_template_loop_add_to_cart(  $loop->post, $product );} ?>
                  </div>
                </div>
              </div>
            <?php endwhile; wp_reset_query(); ?>
          <?php } ?>
        </div>    
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
