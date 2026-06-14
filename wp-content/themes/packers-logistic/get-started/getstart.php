<?php
/**
 * Admin functions.
 *
 * @package Carpet Washing
 */

define('PACKERS_LOGISTIC_SUPPORT',__('https://wordpress.org/support/theme/packers-logistic/','packers-logistic'));
define('PACKERS_LOGISTIC_REVIEW',__('https://wordpress.org/support/theme/packers-logistic/reviews/#new-post','packers-logistic'));
define('PACKERS_LOGISTIC_DOC_URL',__('https://preview.wpradiant.net/tutorial/packers-logistic-free/','packers-logistic'));
define('PACKERS_LOGISTIC_BUY_NOW',__('https://www.wpradiant.net/products/carpet-cleaning-wordpress-theme','packers-logistic'));
define('PACKERS_LOGISTIC_LIVE_DEMO',__('https://preview.wpradiant.net/packers-logistic/','packers-logistic'));
define('PACKERS_LOGISTIC_PRO_DOC',__('https://preview.wpradiant.net/tutorial/packers-logistic-pro/','packers-logistic'));
define('PACKERS_LOGISTIC_BUY_BUNDLE',__('https://www.wpradiant.net/products/wordpress-theme-bundle','packers-logistic'));


/**
 * Register admin page.
 *
 * @since 1.0.0
 */

function packers_logistic_admin_menu_page() {

	$packers_logistic_theme = wp_get_theme( get_template() );

	add_theme_page(
		$packers_logistic_theme->display( 'Name' ),
		$packers_logistic_theme->display( 'Name' ),
		'manage_options',
		'packers-logistic',
		'packers_logistic_do_admin_page'
	);

}
add_action( 'admin_menu', 'packers_logistic_admin_menu_page' );

function packers_logistic_admin_theme_style() {
	wp_enqueue_style('packers-logistic-custom-admin-style', esc_url(get_template_directory_uri()) . '/get-started/getstart.css');
	wp_enqueue_script( 'admin-notice-script', get_template_directory_uri() . '/get-started/js/admin-notice-script.js', array( 'jquery' ), null, true );
    wp_localize_script(
		'admin-notice-script',
		'installPluginData',
		array(
			'ajaxurl'     => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'install_activate_nonce' ),
			'redirectUrl' => admin_url( 'themes.php?page=packers-logistic' ),
		)
	);
    if ( isset( $_GET['page'] ) && 'packers-logistic' === $_GET['page'] && isset( $_GET['tab'] ) && 'recommended_plugins' === $_GET['tab'] ) {
        wp_enqueue_style( 'plugin-install' );
        wp_enqueue_script( 'plugin-install' );
        wp_enqueue_script( 'updates' );
    }
}
add_action('admin_enqueue_scripts', 'packers_logistic_admin_theme_style');

/**
 * Render admin page.
 *
 * @since 1.0.0
 */
function packers_logistic_do_admin_page() {

	$packers_logistic_theme = wp_get_theme( get_template() );
	?>
	<div class="packers-logistic-appearence wrap about-wrap">
		<div class="head-btn">
			<div><h1><?php echo $packers_logistic_theme->display( 'Name' ); ?></h1></div>
			<div class="demo-btn">
				<span>
					<a class="button button-pro" href="<?php echo esc_url( PACKERS_LOGISTIC_BUY_NOW ); ?>" target="_blank"><?php esc_html_e( 'Buy Now', 'packers-logistic' ); ?></a>
				</span>
				<span>
					<a class="button button-demo" href="<?php echo esc_url( PACKERS_LOGISTIC_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e( 'Demo', 'packers-logistic' ); ?></a>
				</span>
				<span>
					<a class="button btn-bundle" href="<?php echo esc_url( PACKERS_LOGISTIC_BUY_BUNDLE ); ?>" target="_blank"><?php esc_html_e( 'Buy Bundle', 'packers-logistic' ); ?></a>
				</span>
				<span>
					<a class="button button-doc" href="<?php echo esc_url( PACKERS_LOGISTIC_PRO_DOC ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'packers-logistic' ); ?></a>
				</span>
			</div>
		</div>
		
		<div class="two-col">

			<div class="about-text">
				<?php
					$description_raw = $packers_logistic_theme->display( 'Description' );
					$main_description = explode( 'Official', $description_raw );
					?>
				<?php echo wp_kses_post( $main_description[0] ); ?>
        	<p>
			    <a class="button button-primary" href="<?php echo esc_url( home_url() ); ?>" target="_blank"><?php esc_html_e( 'Visit Site', 'packers-logistic' ); ?></a>
			    <a class="button button-primary" id="form-button" href="<?php echo esc_url( 'https://forms.gle/RhBK4jcPYRm1z38N6' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Free Theme Installation Support', 'packers-logistic' ); ?></a>
		    </p>
			</div><!-- .col -->

			<div class="about-img">
				<a href="<?php echo esc_url( $packers_logistic_theme->display( 'ThemeURI' ) ); ?>" target="_blank"><img src="<?php echo trailingslashit( get_template_directory_uri() ); ?>screenshot.png" alt="<?php echo esc_attr( $packers_logistic_theme->display( 'Name' ) ); ?>" /></a>
			</div><!-- .col -->

		</div><!-- .two-col -->

  <nav class="nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e( 'Secondary menu', 'packers-logistic' ); ?>">
    <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'packers-logistic' ), 'themes.php' ) ) ); ?>" class="nav-tab<?php echo ( isset( $_GET['page'] ) && 'packers-logistic' === $_GET['page'] && ! isset( $_GET['tab'] ) ) ?' nav-tab-active' : ''; ?>"><?php esc_html_e( 'About', 'packers-logistic' ); ?></a>

    <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'packers-logistic', 'tab' => 'free_vs_pro' ), 'themes.php' ) ) ); ?>" class="nav-tab<?php echo ( isset( $_GET['tab'] ) && 'free_vs_pro' === $_GET['tab'] ) ?' nav-tab-active' : ''; ?>"><?php esc_html_e( 'Compare free Vs Pro', 'packers-logistic' ); ?></a>

    <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'packers-logistic', 'tab' => 'recommended_plugins' ), 'themes.php' ) ) ); ?>" class="nav-tab<?php echo ( isset( $_GET['tab'] ) && 'recommended_plugins' === $_GET['tab'] ) ?' nav-tab-active' : ''; ?>"><?php esc_html_e( 'Recommended Plugins', 'packers-logistic' ); ?></a>

    <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'packers-logistic', 'tab' => 'changelog' ), 'themes.php' ) ) ); ?>" class="nav-tab<?php echo ( isset( $_GET['tab'] ) && 'changelog' === $_GET['tab'] ) ?' nav-tab-active' : ''; ?>"><?php esc_html_e( 'Changelog', 'packers-logistic' ); ?></a>
  </nav>

    <?php
      packers_logistic_main_screen();

      packers_logistic_recommended_plugins_screen();

      packers_logistic_changelog_screen();

      packers_logistic_free_vs_pro();
}
/**
 * Output the main about screen.
 */
function packers_logistic_main_screen() {
  if ( isset( $_GET['page'] ) && 'packers-logistic' === $_GET['page'] && ! isset( $_GET['tab'] ) ) {
  ?>
    
<div class="four-col">

	<div class="col">

		<h3><i class="dashicons dashicons-book-alt"></i><?php esc_html_e( 'Free Theme Directives', 'packers-logistic' ); ?></h3>

		<p>
			<?php esc_html_e( 'This article will walk you through the different phases of setting up and handling your WordPress website.', 'packers-logistic' ); ?>
		</p>

		<p>
			<a class="button green button-primary" href="<?php echo esc_url( PACKERS_LOGISTIC_DOC_URL ); ?>" target="_blank"><?php esc_html_e( 'Free Documentation', 'packers-logistic' ); ?></a>
		</p>

	</div><!-- .col -->

	<div class="col">

		<h3><i class="dashicons dashicons-admin-customizer"></i><?php esc_html_e( 'Full Site Editing', 'packers-logistic' ); ?></h3>

		<p>
			<?php esc_html_e( 'We have used Full Site Editing which will help you preview your changes live and fast.', 'packers-logistic' ); ?>
		</p>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>" ><?php esc_html_e( 'Use Site Editor', 'packers-logistic' ); ?></a>
		</p>

	</div><!-- .col -->

	<div class="col">

		<h3><i class="dashicons dashicons-book-alt"></i><?php esc_html_e( 'Leave us a review', 'packers-logistic' ); ?></h3>
		<p>
			<?php esc_html_e( 'We would love to hear your feedback.', 'packers-logistic' ); ?>
		</p>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( PACKERS_LOGISTIC_REVIEW ); ?>" target="_blank"><?php esc_html_e( 'Review', 'packers-logistic' ); ?></a>
		</p>

	</div><!-- .col -->


	<div class="col">

		<h3><i class="dashicons dashicons-sos"></i><?php esc_html_e( 'Help &amp; Support', 'packers-logistic' ); ?></h3>

		<p>
			<?php esc_html_e( 'If you have any question/feedback regarding theme, please post in our official support forum.', 'packers-logistic' ); ?>
		</p>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( PACKERS_LOGISTIC_SUPPORT ); ?>" target="_blank"><?php esc_html_e( 'Get Support', 'packers-logistic' ); ?></a>
		</p>

	</div><!-- .col -->

	<div class="col">

		<h3><i class="dashicons dashicons-visibility"></i><?php esc_html_e( 'Live Demo', 'packers-logistic' ); ?></h3>

		<p>
			<?php esc_html_e( 'Preview the live demo to explore the homepage, inner pages, and overall design ahead of setup.', 'packers-logistic' ); ?>
		</p>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( PACKERS_LOGISTIC_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e( 'View Live Demo', 'packers-logistic' ); ?></a>
		</p>

	</div><!-- .col -->

<?php $theme_slug = get_stylesheet(); ?>

	<div class="col">
		<h3>
			<i class="dashicons dashicons-admin-links"></i>
			<?php esc_html_e( 'Quick Link', 'packers-logistic' ); ?>
		</h3>

		<div class="packers-logistic-card-body">
			<div class="packers-logistic-card-btn-grp">

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . $theme_slug . '//header&canvas=edit' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'Edit Header', 'packers-logistic' ); ?>
				</a>

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . $theme_slug . '//footer&canvas=edit' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'Edit Footer', 'packers-logistic' ); ?>
				</a>

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . $theme_slug . '//sidebar&canvas=edit' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'Edit Sidebar', 'packers-logistic' ); ?>
				</a>

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template_part' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'All Template Parts', 'packers-logistic' ); ?>
				</a>

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template&postId=' . $theme_slug . '//front-page&canvas=edit' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'Edit Frontpage', 'packers-logistic' ); ?>
				</a>

				<a class="button button-hero btn-col"
				   href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template&postId=' . $theme_slug . '//archive&canvas=edit' ) ); ?>"
				   target="_blank">
					<?php esc_html_e( 'Edit Archive Page', 'packers-logistic' ); ?>
				</a>

			</div>
		</div>
	</div>
</div><!-- .four-col -->
  <?php
}
}

/**
 * Recommended plugins helper.
 *
 * @return array
 */
function packers_logistic_get_recommended_plugins() {
	return array(
		array(
			'name'        => esc_html__( 'WooCommerce', 'packers-logistic' ),
			'slug'        => 'woocommerce',
			'file'        => 'woocommerce/woocommerce.php',
			'description' => esc_html__( 'Add a store, bookings, and product catalog to your site.', 'packers-logistic' ),
		),
		array(
			'name'        => esc_html__( 'WordClever – AI Content Writer', 'packers-logistic' ),
			'slug'        => 'wordclever-ai-content-writer',
			'file'        => 'wordclever-ai-content-writer/wordclever.php',
			'description' => esc_html__( 'Generate AI copy for headings, promos and blog content without leaving WordPress.', 'packers-logistic' ),
		),
	);
}

/**
 * URL for the default plugin icon placeholder.
 *
 * @return string
 */
function packers_logistic_get_plugin_placeholder_icon_url() {
	return trailingslashit( get_template_directory_uri() ) . 'assets/images/plugin-placeholder.svg';
}

/**
 * Fetch metadata for a recommended plugin (version, author, icon).
 *
 * @param array $plugin Plugin definition.
 * @return array
 */
function packers_logistic_get_recommended_plugin_metadata( $plugin ) {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$default_icon = 'https://ps.w.org/' . $plugin['slug'] . '/assets/icon-256x256.png';
	$metadata = array(
		'meta_version'    => '',
		'meta_author'     => '',
		'meta_author_url' => '',
		'meta_icon'       => $default_icon,
	);

	$plugin_path = WP_PLUGIN_DIR . '/' . $plugin['file'];

	if ( file_exists( $plugin_path ) ) {
		$plugin_data = get_plugin_data( $plugin_path, false, false );
		$metadata['meta_version']    = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';
		$metadata['meta_author']     = isset( $plugin_data['Author'] ) ? strip_tags( $plugin_data['Author'] ) : '';
		$metadata['meta_author_url'] = isset( $plugin_data['AuthorURI'] ) ? $plugin_data['AuthorURI'] : '';
		$metadata['meta_icon']       = $default_icon;
		return $metadata;
	}

	if ( ! function_exists( 'plugins_api' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	$api = plugins_api(
		'plugin_information',
		array(
			'slug'   => $plugin['slug'],
			'fields' => array(
				'short_description' => false,
				'sections'          => false,
				'versions'          => false,
				'icons'             => true,
				'homepage'          => true,
				'author'            => true,
				'version'           => true,
			),
		)
	);

	if ( ! is_wp_error( $api ) ) {
		$metadata['meta_version']    = isset( $api->version ) ? $api->version : '';
		$metadata['meta_author']     = isset( $api->author ) ? strip_tags( $api->author ) : '';
		$metadata['meta_author_url'] = isset( $api->homepage ) ? $api->homepage : '';

		if ( ! empty( $api->icons ) ) {
			if ( ! empty( $api->icons['svg'] ) ) {
				$metadata['meta_icon'] = $api->icons['svg'];
			} elseif ( ! empty( $api->icons['2x'] ) ) {
				$metadata['meta_icon'] = $api->icons['2x'];
			} elseif ( ! empty( $api->icons['1x'] ) ) {
				$metadata['meta_icon'] = $api->icons['1x'];
			}
		}
	}

	return $metadata;
}

/**
 * Determine what action should be displayed for the plugin.
 *
 * @param array $plugin
 * @return array
 */
function packers_logistic_get_recommended_plugin_state( $plugin ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$file = $plugin['file'];
	$state = array(
		'needs'        => 'install',
		'action_url'   => '',
		'action_label' => '',
		'action_class' => '',
		'status_label' => '',
		'status_class' => '',
	);

	if ( file_exists( WP_PLUGIN_DIR . '/' . $file ) ) {
		$state['needs'] = is_plugin_active( $file ) ? 'deactivate' : 'activate';
	}

	$state['action_url']   = packers_logistic_recommended_plugin_action_link( $state['needs'], $plugin['slug'], $file );
	$state['meta_icon']    = 'https://ps.w.org/' . $plugin['slug'] . '/assets/icon-256x256.png';
	$metadata              = packers_logistic_get_recommended_plugin_metadata( $plugin );
	$state                  = array_merge( $state, $metadata );

	switch ( $state['needs'] ) {
		case 'install':
			$state['action_label'] = esc_html__( 'Install', 'packers-logistic' );
			$state['action_class'] = 'install-now button';
			$state['status_label'] = esc_html__( 'Not installed', 'packers-logistic' );
			$state['status_class'] = 'not-installed';
			break;
		case 'activate':
			$state['action_label'] = esc_html__( 'Activate', 'packers-logistic' );
			$state['action_class'] = 'activate-now button button-primary';
			$state['status_label'] = esc_html__( 'Installed', 'packers-logistic' );
			$state['status_class'] = 'installed';
			break;
		case 'deactivate':
			$state['action_label'] = esc_html__( 'Deactivate', 'packers-logistic' );
			$state['action_class'] = 'deactivate-now button';
			$state['status_label'] = esc_html__( 'Active', 'packers-logistic' );
			$state['status_class'] = 'active';
			break;
	}

	return $state;
}

/**
 * Build the plugin action link.
 *
 * @param string $needs
 * @param string $slug
 * @param string $file
 * @return string
 */
function packers_logistic_recommended_plugin_action_link( $needs, $slug, $file ) {
	$return_url = admin_url(
		add_query_arg(
			array(
				'page' => 'packers-logistic',
				'tab'  => 'recommended_plugins',
			),
			'themes.php'
		)
	);

	switch ( $needs ) {
		case 'install':
			return wp_nonce_url(
				add_query_arg(
					array(
						'action'           => 'install-plugin',
						'plugin'           => $slug,
						'_wp_http_referer' => $return_url,
					),
					network_admin_url( 'update.php' )
				),
				'install-plugin_' . $slug
			);
		case 'activate':
			return wp_nonce_url(
				add_query_arg(
					array(
						'action'   => 'packers_logistic_toggle_plugin',
						'plugin'   => rawurlencode( $file ),
						'needs'    => 'activate',
						'redirect' => rawurlencode( $return_url ),
					),
					admin_url( 'admin.php' )
				),
				'packers_logistic_toggle_plugin_' . $file
			);
		case 'deactivate':
			return wp_nonce_url(
				add_query_arg(
					array(
						'action'   => 'packers_logistic_toggle_plugin',
						'plugin'   => rawurlencode( $file ),
						'needs'    => 'deactivate',
						'redirect' => rawurlencode( $return_url ),
					),
					admin_url( 'admin.php' )
				),
				'packers_logistic_toggle_plugin_' . $file
			);
	}

	return '';
}

/**
 * Handle plugin activation/deactivation from the recommended plugins tab.
 *
 * @return void
 */
function packers_logistic_handle_recommended_plugin_toggle() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to manage plugins for this site.', 'packers-logistic' ) );
	}

	$plugin   = isset( $_GET['plugin'] ) ? wp_unslash( $_GET['plugin'] ) : '';
	$needs    = isset( $_GET['needs'] ) ? sanitize_key( wp_unslash( $_GET['needs'] ) ) : '';
	$redirect = isset( $_GET['redirect'] ) ? wp_unslash( $_GET['redirect'] ) : '';

	if ( empty( $plugin ) || ! in_array( $needs, array( 'activate', 'deactivate' ), true ) ) {
		wp_safe_redirect( admin_url( 'themes.php?page=packers-logistic&tab=recommended_plugins' ) );
		exit;
	}

	check_admin_referer( 'packers_logistic_toggle_plugin_' . $plugin );

	if ( ! function_exists( 'activate_plugin' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$redirect_url = admin_url( 'themes.php?page=packers-logistic&tab=recommended_plugins' );
	if ( ! empty( $redirect ) ) {
		$redirect_url = wp_validate_redirect( urldecode( $redirect ), $redirect_url );
	}

	if ( 'activate' === $needs ) {
		if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to activate this plugin.', 'packers-logistic' ) );
		}

		// WordClever depends on WooCommerce, so activate WooCommerce first when available.
		if ( 'wordclever-ai-content-writer/wordclever.php' === $plugin && ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) && current_user_can( 'activate_plugin', 'woocommerce/woocommerce.php' ) ) {
				$dependency_result = activate_plugin( 'woocommerce/woocommerce.php' );
				if ( is_wp_error( $dependency_result ) ) {
					$redirect_url = add_query_arg( 'plugin_error', $dependency_result->get_error_message(), $redirect_url );
					wp_safe_redirect( $redirect_url );
					exit;
				}
			} else {
				$redirect_url = add_query_arg(
					'plugin_error',
					__( 'WordClever requires WooCommerce to be installed and activated first.', 'packers-logistic' ),
					$redirect_url
				);
				wp_safe_redirect( $redirect_url );
				exit;
			}
		}

		$result = activate_plugin( $plugin );
		if ( is_wp_error( $result ) ) {
			$redirect_url = add_query_arg( 'plugin_error', wp_strip_all_tags( $result->get_error_message() ), $redirect_url );
		} else {
			$redirect_url = add_query_arg( 'plugin_activated', '1', $redirect_url );
		}
	} else {
		if ( ! current_user_can( 'deactivate_plugin', $plugin ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to deactivate this plugin.', 'packers-logistic' ) );
		}

		deactivate_plugins( $plugin, false, is_network_admin() );
		$redirect_url = add_query_arg( 'plugin_deactivated', '1', $redirect_url );
	}

	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'admin_action_packers_logistic_toggle_plugin', 'packers_logistic_handle_recommended_plugin_toggle' );

/**
 * Show notices for recommended plugin actions.
 *
 * @return void
 */
function packers_logistic_recommended_plugins_admin_notice() {
	if ( ! isset( $_GET['page'] ) || 'packers-logistic' !== $_GET['page'] ) {
		return;
	}

	if ( ! isset( $_GET['tab'] ) || 'recommended_plugins' !== $_GET['tab'] ) {
		return;
	}

	if ( ! empty( $_GET['plugin_error'] ) ) {
		$message = sanitize_text_field( wp_unslash( $_GET['plugin_error'] ) );
		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			esc_html( $message )
		);
	}

	if ( isset( $_GET['plugin_activated'] ) ) {
		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html__( 'Plugin activated successfully.', 'packers-logistic' )
		);
	}

	if ( isset( $_GET['plugin_deactivated'] ) ) {
		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html__( 'Plugin deactivated successfully.', 'packers-logistic' )
		);
	}
}
add_action( 'admin_notices', 'packers_logistic_recommended_plugins_admin_notice' );

/**
 * Recommended plugins tab content.
 */
function packers_logistic_recommended_plugins_screen() {
	if ( ! isset( $_GET['tab'] ) || 'recommended_plugins' !== $_GET['tab'] ) {
		return;
	}

	$plugins = packers_logistic_get_recommended_plugins();
	if ( empty( $plugins ) ) {
		return;
	}
	$placeholder_icon = packers_logistic_get_plugin_placeholder_icon_url();
	?>
	<div class="wrap about-wrap pluginsscreen">
		<div class="packers-logistic-plugin-wrapper">
			<div class="packers-logistic-plugin-grid">
				<?php foreach ( $plugins as $plugin ) : ?>
					<?php $state = packers_logistic_get_recommended_plugin_state( $plugin ); ?>
					<div class="packers-logistic-plugin-card">
						<div class="packers-logistic-plugin-icon">
							<img src="<?php echo esc_url( $state['meta_icon'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ); ?>" onerror="this.onerror=null;this.src='<?php echo esc_url( $placeholder_icon ); ?>';" />
						</div>
						<div class="plugin-card-content">
							<?php if ( $state['meta_version'] || $state['meta_author'] ) : ?>
								<div class="plugin-meta-line">
									<?php if ( $state['meta_version'] ) : ?>
										<span><?php echo esc_html( $state['meta_version'] ); ?></span>
									<?php endif; ?>
									<?php if ( $state['meta_author'] ) : ?>
										<?php if ( $state['meta_version'] ) : ?>
											<span class="plugin-meta-divider">|</span>
										<?php endif; ?>
										<span class="plugin-meta-author">
											<?php if ( $state['meta_author_url'] ) : ?>
												<a href="<?php echo esc_url( $state['meta_author_url'] ); ?>" target="_blank" rel="noreferrer"><?php echo esc_html( $state['meta_author'] ); ?></a>
											<?php else : ?>
												<?php echo esc_html( $state['meta_author'] ); ?>
											<?php endif; ?>
										</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
							<p><?php echo esc_html( $plugin['description'] ); ?></p>
						</div>
						<div class="plugin-card-footer">
							<p class="plugin-status <?php echo esc_attr( $state['status_class'] ); ?>">
								<?php echo esc_html( $state['status_label'] ); ?>
							</p>
							<?php if ( ! empty( $state['action_label'] ) && ! empty( $state['action_url'] ) ) : ?>
								<p class="plugin-action">
									<a class="<?php echo esc_attr( $state['action_class'] ); ?>" href="<?php echo esc_url( $state['action_url'] ); ?>">
										<?php echo esc_html( $state['action_label'] ); ?>
									</a>
								</p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Output the changelog screen.
 */
function packers_logistic_changelog_screen() {
  if ( isset( $_GET['tab'] ) && 'changelog' === $_GET['tab'] ) {
    global $wp_filesystem;
    ?>
    <div class="wrap about-wrap pluginsscreen">
      <p class="about-description"><?php esc_html_e( 'Want to know whats been happening with the latest changes?', 'packers-logistic' ); ?></p>
      <?php
        // Get the path to the readme.txt file.
        $readme_file = get_template_directory() . '/README.txt';

        // Check if the readme file exists and is readable.
        if ( file_exists( $readme_file ) && is_readable( $readme_file ) ) {
          $changelog = file_get_contents( $readme_file );
          $changelog_list = packers_logistic_parse_changelog( $changelog );
          echo wp_kses_post( $changelog_list );
        } else {
          echo '<p>Changelog file does not exist or is not readable.</p>';
        }
      ?>
    </div>
    <?php
  }
}

/**
 * Parse changelog from readme file.
 * @param  string $content
 * @return string
 */
function packers_logistic_parse_changelog( $content ) {
  // Explode content with '== ' to separate main content into an array of headings.
  $content = explode( '== ', $content );

  $changelog_isolated = '';

  // Find the part that starts with 'Changelog ==', i.e., isolate changelog.
  foreach ( $content as $key => $value ) {
    if ( strpos( $value, 'Changelog ==' ) === 0 ) {
      $changelog_isolated = str_replace( 'Changelog ==', '', $value );
    }
  }

  // Explode $changelog_isolated to manipulate it and add HTML elements.
  $changelog_array = explode( '- ', $changelog_isolated );

  // Prepare the HTML structure.
  $changelog = '<pre class="changelog">';
  foreach ( $changelog_array as $value ) {
    // Add opening and closing div and span, only the first span element will have the heading class.
    $value = '<div class="block"><span class="heading">- ' . esc_html( $value ) . '</span></div>';
    // Append the value to the changelog.
    $changelog .= $value;
  }
  $changelog .= '</pre>';

  return wp_kses_post( $changelog );
}

/**
 * Import Demo data for theme using catch themes demo import plugin
 */
function packers_logistic_free_vs_pro() {
  if ( isset( $_GET['tab'] ) && 'free_vs_pro' === $_GET['tab'] ) {
  ?>
    <div class="wrap about-wrap pluginsscreen">

      <h3 class="about-description"><?php esc_html_e( 'Compare Free Vs Pro', 'packers-logistic' ); ?></h3>
      <div class="vs-theme-table">
        <table>
          <thead>
            <tr><th class="head" scope="col"><?php esc_html_e( 'Theme Features', 'packers-logistic' ); ?></th>
              <th class="head" scope="col"><?php esc_html_e( 'Free Theme', 'packers-logistic' ); ?></th>
              <th class="head" scope="col"><?php esc_html_e( 'Pro Theme', 'packers-logistic' ); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><span><?php esc_html_e( 'Responsive Design', 'packers-logistic' ); ?></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Painless Setup', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-saved"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Color Options', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-saved"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Premium site demo', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Multiple Block Layout', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Premium Patterns', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Multiple Fonts', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Slider Block', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Post Listing Block', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'WooCommerce Filter Block', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Gallery Block', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td headers="features" class="feature"><?php esc_html_e( 'Post Carousel Block', 'packers-logistic' ); ?></td>
              <td><span class="dashicons dashicons-no-alt"></span></td>
              <td><span class="dashicons dashicons-saved"></span></td>
            </tr>
            <tr class="odd" scope="row">
              <td class="feature feature--empty"></td>
              <td class="feature feature--empty"></td>
              <td headers="comp-2" class="td-btn-2"><a target="_blank" href="<?php echo esc_url( PACKERS_LOGISTIC_BUY_NOW ); ?>" class="sidebar-button single-btn" target="_blank"><?php esc_html_e( 'Buy It Now', 'packers-logistic' ); ?></a>

              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }
}