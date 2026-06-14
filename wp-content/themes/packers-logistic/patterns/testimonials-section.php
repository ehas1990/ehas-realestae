<?php
/**
 * Testimonials Section
 * 
 * slug: packers-logistic/testimonials-section
 * title: Testimonials Section
 * categories: packers-logistic
 */

return array(
    'title'      =>__( 'Testimonials Section', 'packers-logistic' ),
    'categories' => array( 'packers-logistic' ),
    'content'    => '<!-- wp:group {"className":"testimonials-section","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"backgroundColor":"background","layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group testimonials-section has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","className":"testimonial-heading","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"border":{"radius":"5px"},"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"textColor":"secaccent","fontSize":"medium","fontFamily":"fira-sans"} -->
<p class="has-text-align-center testimonial-heading has-secaccent-color has-text-color has-link-color has-fira-sans-font-family has-medium-font-size" style="border-radius:5px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40);font-style:normal;font-weight:600">'. esc_html__('Testimonials','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"27px"},"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"textColor":"accent","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center has-accent-color has-text-color has-link-color has-fira-sans-font-family" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);font-size:27px;font-style:normal;font-weight:600">'. esc_html__('What Say Clients','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"test-prev-next"} -->
<div class="wp-block-columns test-prev-next"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:buttons {"className":"swiper-test-button","style":{"spacing":{"blockGap":{"top":"0"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-buttons swiper-test-button"><!-- wp:button {"backgroundColor":"sixthaccent","className":"testimonial-swiper-button-prev","style":{"border":{"radius":"26px"}}} -->
<div class="wp-block-button testimonial-swiper-button-prev"><a class="wp-block-button__link has-sixthaccent-background-color has-background wp-element-button" style="border-radius:26px"><img class="wp-image-132" style="width: 10px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/prev.png" alt=""></a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"sixthaccent","className":"testimonial-swiper-button-next","style":{"border":{"radius":"26px"}}} -->
<div class="wp-block-button testimonial-swiper-button-next"><a class="wp-block-button__link has-sixthaccent-background-color has-background wp-element-button" style="border-radius:26px"><img class="wp-image-131" style="width: 10px;" src="'.esc_url(get_template_directory_uri()) .'/assets/images/next.png" alt=""></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"testimonial-swiper-slider mySwiper","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group testimonial-swiper-slider mySwiper" style="margin-top:var(--wp--preset--spacing--40)"><!-- wp:group {"className":"testimonials-slider swiper-wrapper","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"100%","wideSize":"100%"}} -->
<div class="wp-block-group testimonials-slider swiper-wrapper" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"><!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('Jean Kalvin','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('Manager','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('John Doe','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('Project Manager','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('Jane Smith','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('Manager','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('Ankit Mehta','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('Operations Manager','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('Amit Verma','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('CEO','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"testimonials-slider-block swiper-slide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group testimonials-slider-block swiper-slide has-background-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.8","fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"textColor":"secaccent","fontSize":"extra-small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-extra-small-font-size" style="margin-bottom:var(--wp--preset--spacing--60);font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.','packers-logistic').'</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"testimonial-author-name","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"0"}}},"textColor":"accent","fontSize":"upper-heading","fontFamily":"fira-sans"} -->
<h2 class="wp-block-heading has-text-align-center testimonial-author-name has-accent-color has-text-color has-link-color has-fira-sans-font-family has-upper-heading-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:0;font-style:normal;font-weight:600">'. esc_html__('Ankit Mehta','packers-logistic').'</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|secaccent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"textColor":"secaccent","fontSize":"small"} -->
<p class="has-text-align-center has-secaccent-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:500">'. esc_html__('Team Lead','packers-logistic').'</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"65px"} -->
<div style="height:65px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->',
);