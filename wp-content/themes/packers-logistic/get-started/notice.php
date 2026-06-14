<?php

// Upsell
if ( class_exists( 'WP_Customize_Section' ) ) {
	class Packers_Logistic_Upsell_Section extends WP_Customize_Section {
		public $type = 'packers-logistic-upsell';
		public $button_text = '';
		public $url = '';
		public $background_color = '';
		public $text_color = '';
		protected function render() {
			$background_color = ! empty( $this->background_color ) ? esc_attr( $this->background_color ) : '#3e5aef';
			$text_color       = ! empty( $this->text_color ) ? esc_attr( $this->text_color ) : '#fff';
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="packers_logistic_upsell_section accordion-section control-section control-section-<?php echo esc_attr( $this->id ); ?> cannot-expand">
				<h3 class="accordion-section-title" style="color:#fff; background:<?php echo esc_attr( $background_color ); ?>;border-left-color:<?php echo esc_attr( $background_color ); ?>;">
					<?php echo esc_html( $this->title ); ?>
					<a href="<?php echo esc_url( $this->url ); ?>" class="button button-secondary alignright" target="_blank" style="margin-top: -4px;"><?php echo esc_html( $this->button_text ); ?></a>
				</h3>
			</li>
			<?php
		}
	}
}
function packers_logistic_admin_notice_style() {
	wp_enqueue_style('packers-logistic-custom-admin-notice-style', esc_url(get_template_directory_uri()) . '/get-started/getstart.css');
}
add_action('admin_enqueue_scripts', 'packers_logistic_admin_notice_style');

/**
 * Display the admin notice if not dismissed.
 */
function packers_logistic_admin_notice() {
    // Check if the notice is dismissed
    $packers_logistic_dismissed = get_user_meta(get_current_user_id(), 'packers_logistic_dismissed_notice', true);
    $packers_logistic_current_page = '';
    if(isset($_GET['page'])) {
    	$packers_logistic_current_page = admin_url( "admin.php?page=".sanitize_text_field($_GET["page"]));
    }

    // Display the notice only if not dismissed
    if (!$packers_logistic_dismissed && $packers_logistic_current_page != admin_url( "admin.php?page=wordclever-templates")) {
        ?>
        <div class="updated notice notice-success is-dismissible notice-get-started-class" data-notice="get-start" style="display: flex;padding: 10px;">
        		<div class="notice-content">
	        		<div class="notice-holder">
	                        <h5><span class="theme-name"><span><?php echo __('Welcome to Carpet Washing', 'packers-logistic'); ?></span></h5>
	                        <h1><?php echo __('Enhance Your Website Development with Radiant Blocks!!', 'packers-logistic'); ?></h1>
	                        </h3>
	                        <div class="notice-text">
	                            <p class="blocks-text"><?php echo __('Effortlessly craft websites for any niche with Radiant Blocks! Experience seamless functionality and stunning responsiveness as you enhance your digital presence with Block WordPress Themes. Start building your ideal website today!', 'packers-logistic') ?></p>
	                        </div>
	                        <a href="javascript:void(0);" id="install-activate-button" class="button admin-button info-button" data-redirect="<?php echo esc_url( admin_url( 'themes.php?page=packers-logistic' ) ); ?>">
					   <?php echo __('Getting started', 'packers-logistic'); ?>
					</a>

                   <a href="<?php echo esc_url( PACKERS_LOGISTIC_BUY_NOW ); ?>" target="_blank" id="go-pro-button" class="button admin-button buy-now-button"><?php echo __('Buy Now ', 'packers-logistic'); ?></a>

                    <a href="<?php echo esc_url( 'https://forms.gle/RhBK4jcPYRm1z38N6' ); ?>" target="_blank" rel="noopener noreferrer" id="form-button" class="button admin-button installation-form-button"><?php echo esc_html__( 'Free Theme Installation Support', 'packers-logistic' ); ?></a>
                    
					<a href="<?php echo esc_url( PACKERS_LOGISTIC_BUY_BUNDLE ); ?>" target="_blank" id="bundle-button" class="button admin-button bundle-button"><?php echo __('Get Bundle', 'packers-logistic'); ?></a>

                    <a href="<?php echo esc_url( PACKERS_LOGISTIC_DOC_URL ); ?>" target="_blank" id="doc-button" class="button admin-button bundle-button"><?php echo __('Free Documentation', 'packers-logistic'); ?></a>
            	</div>
            </div>
            <div class="theme-hero-screens">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/get-started/notice.png'); ?>" />
            </div>
        </div>
        <?php
    }
}

// Hook to display the notice
add_action('admin_notices', 'packers_logistic_admin_notice');

/**
 * AJAX handler to dismiss the notice.
 */
function packers_logistic_dismissed_notice() {
    // Set user meta to indicate the notice is dismissed
    update_user_meta(get_current_user_id(), 'packers_logistic_dismissed_notice', true);
    die();
}

// Hook for the AJAX action
add_action('wp_ajax_packers_logistic_dismissed_notice', 'packers_logistic_dismissed_notice');

/**
 * Clear dismissed notice state when switching themes.
 */
function packers_logistic_switch_theme() {
    // Clear the dismissed notice state when switching themes
    delete_user_meta(get_current_user_id(), 'packers_logistic_dismissed_notice');
}

// Hook for switching themes
add_action('after_switch_theme', 'packers_logistic_switch_theme');