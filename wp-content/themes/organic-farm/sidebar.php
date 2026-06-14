<?php
/**
 * The sidebar containing the main widget area
 * 
 * @subpackage Organic Farm
 * @since 1.0
 */
?>

<aside id="sidebar" class="widget-area" role="complementary">
    <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
        <div id="search" class="widget widget_search wow zoomIn">
            <h3 class="widget-title"><?php esc_html_e( 'Search', 'organic-farm' ); ?></h3>
            <?php get_search_form(); ?>
        </div>
        <div id="recent-posts" class="widget widget_recent_entries wow zoomIn">
            <h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'organic-farm' ); ?></h3>
            <ul>
                <?php
                    $recent_posts = wp_get_recent_posts(array('numberposts' => 5));
                    foreach( $recent_posts as $recent ){
                        echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   esc_html($recent["post_title"]) . '</a></li>';
                    }
                ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>