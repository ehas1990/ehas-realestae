<?php
/**
 * Project Section
 * 
 * slug: packers-logistic/project-section
 * title: Project Section
 * categories: packers-logistic
 */
    return array(
        'title'      =>__( 'Project Section', 'packers-logistic' ),
        'categories' => array( 'packers-logistic' ),
        'content'    => '<!-- wp:group {"className":"about-section-main","layout":{"type":"constrained","contentSize":"100%"}} -->
        <div class="wp-block-group about-section-main"><!-- wp:spacer {"height":"20px"} -->
        <div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->

        <!-- wp:group {"className":"project-head-box ","layout":{"type":"constrained","contentSize":"80%"}} -->
        <div class="wp-block-group project-head-box"><!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
        <div class="wp-block-group"><!-- wp:columns {"className":"about-section-main-column","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
        <div class="wp-block-columns about-section-main-column"><!-- wp:column {"width":"43%","className":"about-img-column wow fadeInLeft"} -->
        <div class="wp-block-column about-img-column wow fadeInLeft" style="flex-basis:43%"><!-- wp:image {"id":74,"sizeSlug":"full","linkDestination":"none","className":"about-img","style":{"border":{"radius":{"topLeft":"0px","topRight":"0px","bottomLeft":"0px","bottomRight":"0px"}},"spacing":{"margin":{"right":"30px"}}}} -->
        <figure class="wp-block-image size-full has-custom-border about-img" style="margin-right:30px"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/about-img.png" alt="" class="wp-image-74" style="border-top-left-radius:0px;border-top-right-radius:0px;border-bottom-left-radius:0px;border-bottom-right-radius:0px"/></figure>
        <!-- /wp:image -->

        <!-- wp:group {"className":"medal-section","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"backgroundColor":"accent","layout":{"type":"constrained","contentSize":"%"}} -->
        <div class="wp-block-group medal-section has-accent-background-color has-background"><!-- wp:image {"id":64,"sizeSlug":"full","linkDestination":"none","align":"center"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/medal.png" alt="" class="wp-image-64"/></figure>
        <!-- /wp:image -->

        <!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|background"}}}},"textColor":"background","fontSize":"small"} -->
        <p class="has-text-align-center has-background-color has-text-color has-link-color has-small-font-size">'. esc_html__('20 Year','packers-logistic').'</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|background"}}}},"textColor":"background","fontSize":"small"} -->
        <p class="has-text-align-center has-background-color has-text-color has-link-color has-small-font-size">'. esc_html__('Experience','packers-logistic').'</p>
        <!-- /wp:paragraph --></div>
        <!-- /wp:group --></div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top","width":"50%","className":"wow fadeInRight","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
        <div class="wp-block-column is-vertically-aligned-top wow fadeInRight" style="flex-basis:50%"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"accent","fontSize":"medium"} -->
        <p class="has-accent-color has-text-color has-link-color has-medium-font-size" style="font-style:normal;font-weight:500">'. esc_html__('About Us','packers-logistic').'</p>
        <!-- /wp:paragraph -->

        <!-- wp:heading {"style":{"typography":{"fontSize":"29px"}}} -->
        <h2 class="wp-block-heading" style="font-size:29px">'. esc_html__('I’m A Business Strategist Turned Soulful Entrepreneur And Coach','packers-logistic').'</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1.8"}},"fontFamily":"fira-sans"} -->
        <p class="has-fira-sans-font-family" style="font-style:normal;font-weight:400;line-height:1.8">'. esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et metus augue. Mauris ut libero eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Phasellus et metus augue. Mauris ut libero eget.Lorem ipsum dolor sit amet, consectetur adipisce elit. Phasellus et metus augue. Mauris ut libero eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et metus augue. Mauris ut libero eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et metus augue. Mauris ut libero eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Phasellus et metus augue. Mauris ut libero eget.Lorem ipsum dolor sit amet, consectetur adipisce elit. Phasellus et metus augue.Mauris ut libero eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Phasellus et metus augue. Mauris ut libero eget.Lorem ipsum dolor sit amet, consectetur adipisce elit. Phasellus et metus augue','packers-logistic').'</p>
        <!-- /wp:paragraph -->

        <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group"><!-- wp:image {"id":49,"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"profile-image","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
        <figure class="wp-block-image size-full profile-image"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/profile.png" alt="" class="wp-image-49" style="aspect-ratio:1;object-fit:cover"/></figure>
        <!-- /wp:image -->

        <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"100%"}} -->
        <div class="wp-block-group"><!-- wp:heading -->
        <h2 class="wp-block-heading">'. esc_html__('Smith Shah','packers-logistic').'</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>'. esc_html__('GENERAL MANAGER','packers-logistic').'</p>
        <!-- /wp:paragraph --></div>
        <!-- /wp:group --></div>
        <!-- /wp:group -->

        <!-- wp:image {"id":56,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image size-full"><img src="'.esc_url(get_template_directory_uri()) .'/assets/images/sign.png" alt="" class="wp-image-56"/></figure>
        <!-- /wp:image --></div>
        <!-- /wp:group --></div>
        <!-- /wp:column --></div>
        <!-- /wp:columns --></div>
        <!-- /wp:group -->

        <!-- wp:spacer {"height":"40px"} -->
        <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer --></div>
        <!-- /wp:group --></div>
        <!-- /wp:group -->',
    );