<?php
/**
 * Template part for displaying posts
 *
 * @subpackage Organic Farm
 * @since 1.0
 */
?>
<div id="Category-section" class="entry-content">
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="postbox smallpostimage p-3 wow zoomIn">
			<?php $blog_archive_ordering = get_theme_mod('archieve_post_order', array('title', 'image', 'meta','btn'));
			foreach ($blog_archive_ordering as $post_data_order) :
				if ('title' === $post_data_order) :?>
				    <h3 class="text-center"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
				<?php elseif ('image' === $post_data_order) :?>
			        <?php
					// Get the post content
					$post_content = apply_filters('the_content', get_the_content());

					// Create a DOMDocument to parse the HTML content
					$dom = new DOMDocument();
					@$dom->loadHTML($post_content);

					// Find and display the first image in the post content
					$images = $dom->getElementsByTagName('img');

					if ($images->length > 0) {
					    $first_image = $images->item(0);
					    $image_url = $first_image->getAttribute('src');
					    
					    if (!empty($image_url)) {
					        echo '<img src="' . esc_url($image_url) . '">';
					    }
					}
				?>	
				<?php elseif ('meta' === $post_data_order) :?>
				    <div class="date-box mb-2 text-center">
				    	<?php if ( is_sticky() ) { ?>
				    		<span class="me-2"><i class="<?php echo esc_attr(get_theme_mod('organic_farm_sticky_icon','fas fa-thumb-tack')); ?> me-2"></i><?php echo esc_html( __('Sticky', 'organic-farm') ); ?></span>
				    	<?php } ?>
						<?php if( get_option('organic_farm_date',false) != 'off'){ ?>
							<span class="me-2"><i class="<?php echo esc_attr(get_theme_mod('organic_farm_date_icon','far fa-calendar-alt')); ?> me-2"></i><?php organic_farm_display_shop_date(); ?></span>
						<?php } ?>
						<?php if( get_option('organic_farm_admin',false) != 'off'){ ?>
							<span class="entry-author me-2"><i class="<?php echo esc_attr(get_theme_mod('organic_farm_author_icon','fas fa-user')); ?> me-2"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?></a></span>
						<?php }?>
						<?php if( get_option('organic_farm_comment',false) != 'off'){ ?>
							<span class="entry-comments me-2"><i class="<?php echo esc_attr(get_theme_mod('organic_farm_comment_icon','fas fa-comments')); ?> me-2"></i> <?php comments_number( __('0 Comments','organic-farm'), __('0 Comments','organic-farm'), __('% Comments','organic-farm')); ?></span>
						<?php }?>
						<?php if( get_option('organic_farm_tag',false) != 'off'){ ?>
							<span class="tags"><i class="<?php echo esc_attr(get_theme_mod('organic_farm_tag_icon','fas fa-tags')); ?> me-2"></i> <?php organic_farm_display_post_tag_count(); ?></span>
						<?php }?>
					</div>
					<?php if(get_theme_mod('organic_farm_blog_meta_content') == 'Full Content'){ ?>
						<?php the_content(); ?>
					<?php }
					if(get_theme_mod('organic_farm_blog_meta_content', 'Excerpt Content') == 'Excerpt Content'){ ?>
						<?php if(get_the_excerpt()) { ?>
						<div class="entry-content"><?php $organic_farm_excerpt = get_the_excerpt(); echo esc_html( organic_farm_string_limit_words( $organic_farm_excerpt, esc_attr(get_theme_mod('organic_farm_post_excerpt_number','20')))); ?> <?php echo esc_html( get_theme_mod('organic_farm_button_excerpt_suffix','...') ); ?></div>
						<?php }?>
					<?php }?>	
				<?php elseif ('btn' === $post_data_order) :?>
				    <div class="link-more mb-2 text-center">
						<a class="more-link" href="<?php echo esc_url( get_permalink() );?>"><?php echo esc_html(get_theme_mod('organic_farm_read_more_text',__('Read More','organic-farm'))); ?></a>
			  		</div>
				<?php endif;
			endforeach;
			?>       
		  	<div class="clearfix"></div>
		</div>
	</div>
</div>