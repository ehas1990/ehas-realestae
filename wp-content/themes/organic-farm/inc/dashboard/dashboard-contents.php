<?php
/**
 * Wizard
 *
 * @package Organic_Farm_Whizzie
 * @author Catapult Themes
 * @since 1.0.0
 */

class Organic_Farm_Whizzie {
	
	protected $version = '1.1.0';
	
	/** @var string Current theme name, used as namespace in actions. */
	protected $organic_farm_theme_name = '';
	protected $organic_farm_theme_title = '';
	
	/** @var string Wizard page slug and title. */
	protected $organic_farm_page_slug = '';
	protected $organic_farm_page_title = '';
	
	/** @var array Wizard steps set by user. */
	protected $config_steps = array();
	
	/**
	 * Relative plugin url for this plugin folder
	 * @since 1.0.0
	 * @var string
	 */
	protected $organic_farm_plugin_url = '';

	public $organic_farm_plugin_path;
	public $parent_slug;
	
	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;
	
	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';
	
	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';
	
	/**
	 * Constructor
	 *
	 * @param $config	Our config parameters
	 */
	public function __construct( $config ) {
		$this->set_vars( $config );
		$this->init();
	}
	
	/**
	 * Set some settings
	 * @since 1.0.0
	 * @param $config	Our config parameters
	 */
	public function set_vars( $config ) {
	
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/class-tgm-plugin-activation.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/tgm.php';

		if( isset( $config['organic_farm_page_slug'] ) ) {
			$this->organic_farm_page_slug = esc_attr( $config['organic_farm_page_slug'] );
		}
		if( isset( $config['organic_farm_page_title'] ) ) {
			$this->organic_farm_page_title = esc_attr( $config['organic_farm_page_title'] );
		}
		if( isset( $config['steps'] ) ) {
			$this->config_steps = $config['steps'];
		}
		
		$this->organic_farm_plugin_path = trailingslashit( dirname( __FILE__ ) );
		$relative_url = str_replace( get_template_directory(), '', $this->organic_farm_plugin_path );
		$this->organic_farm_plugin_url = trailingslashit( get_template_directory_uri() . $relative_url );

		$organic_farm_current_theme = wp_get_theme();

		$this->organic_farm_theme_title = $organic_farm_current_theme->get( 'Name' );
		$this->organic_farm_theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $organic_farm_current_theme->get( 'Name' ) ) );
		
		$this->organic_farm_page_slug = apply_filters( $this->organic_farm_theme_name . '_theme_setup_wizard_organic_farm_page_slug', $this->organic_farm_theme_name . '-wizard' );
		$this->parent_slug = apply_filters( $this->organic_farm_theme_name . '_theme_setup_wizard_parent_slug', '' );

	}
	
	/**
	 * Hooks and filters
	 * @since 1.0.0
	 */	
	public function init() {
		
		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instance' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_init', array( $this, 'get_plugins' ), 30 );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_setup_plugins', array( $this, 'setup_plugins' ) );
		add_action( 'wp_ajax_organic_farm_setup_widgets', array( $this, 'organic_farm_setup_widgets' ) );
		
	}
	
	public function enqueue_scripts() {
		wp_enqueue_style( 'organic-farm-dashboard-style', get_template_directory_uri() . '/inc/dashboard/assets/css/dashboard.css');
		wp_register_script( 'organic-farm-dashboard-script', get_template_directory_uri() . '/inc/dashboard/assets/js/dashboard.js', array( 'jquery' ), time() );
		wp_localize_script( 
			'organic-farm-dashboard-script',
			'whizzie_params',
			array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'wpnonce' 		=> wp_create_nonce( 'whizzie_nonce' ),
				'verify_text'	=> esc_html( 'verifying', 'organic-farm' )
			)
		);
		wp_enqueue_script( 'organic-farm-dashboard-script' );
	}
	
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}
			
	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function get_tgmpa_instance() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}
	
	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->organic_farm_theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->organic_farm_theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}
	
	/**
	 * Make a modal screen for the wizard
	 */
	public function menu_page() {
		add_theme_page( esc_html( $this->organic_farm_page_title ), esc_html( $this->organic_farm_page_title ), 'manage_options', $this->organic_farm_page_slug, array( $this, 'wizard_page' ) );
	}
	
	/**
	 * Make an interface for the wizard
	 */
	public function wizard_page() { 
		tgmpa_load_bulk_installer();

		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( esc_html__( 'Failed to find TGM', 'organic-farm' ) );
		}

		$url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );
		$method = '';
		$fields = array_keys( $_POST );

		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}

		$organic_farm_theme = wp_get_theme();
		$organic_farm_theme_title = $organic_farm_theme->get( 'Name' );

		?>
		<div class="getting-started__header">
			<div class="header-box-left">
				<h2><?php echo esc_html( $organic_farm_theme_title ); ?></h2>
				<p><?php esc_html_e('Version: ', 'organic-farm'); ?><?php echo esc_html($organic_farm_theme['Version']);?></p>
			</div>
			<div class="header-box-right">
				<div class="btn_box">
					<a class="button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=organic-farm-pro' ) ); ?>" target="_blank"><?php esc_html_e('Theme Information', 'organic-farm'); ?></a>
					<a class="button-primary" href="<?php echo esc_url( ORGANIC_FARM_FREE_DOC ); ?>" target="_blank"><?php esc_html_e('Theme Documentation', 'organic-farm'); ?></a>
					<a class="button-primary" href="<?php echo esc_url( ORGANIC_FARM_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support', 'organic-farm'); ?></a>
					<a class="button-primary" href="<?php echo esc_url( ORGANIC_FARM_REVIEW ); ?>" target="_blank"><?php esc_html_e('Review', 'organic-farm'); ?></a>
				</div>
			</div>
		</div>
		
		<div class="import-box">
			<div class="whizzie-wrap">
				<div class="demo_content">
					<?php
						$organic_farm_steps = $this->get_steps();
						echo '<ul class="whizzie-menu">';
						foreach ( $organic_farm_steps as $organic_farm_step ) {
							$class = 'step step-' . esc_attr( $organic_farm_step['id'] );
							echo '<li data-step="' . esc_attr( $organic_farm_step['id'] ) . '" class="' . esc_attr( $class ) . '">';
							printf( '<h2>%s</h2>', esc_html( $organic_farm_step['title'] ) );

							$content = call_user_func( array( $this, $organic_farm_step['view'] ) );
							if ( isset( $content['summary'] ) ) {
								printf(
									'<div class="summary">%s</div>',
									wp_kses_post( $content['summary'] )
								);
							}
							if ( isset( $content['detail'] ) ) {
								printf(
									'<div class="detail">%s</div>',
									wp_kses_post( $content['detail'] )
								);
							}
							if ( isset( $organic_farm_step['button_text'] ) && $organic_farm_step['button_text'] ) {
								printf( 
									'<div class="button-wrap"><a href="#" class="button button-primary do-it" data-callback="%s" data-step="%s">%s</a></div>',
									esc_attr( $organic_farm_step['callback'] ),
									esc_attr( $organic_farm_step['id'] ),
									esc_html( $organic_farm_step['button_text'] )
								);
							}
							echo '</li>';
						}
						echo '</ul>';
					?>
						
					<ul class="whizzie-nav">
						<?php
						$step_number = 1;	
						foreach ( $organic_farm_steps as $organic_farm_step ) {
							echo '<li class="nav-step-' . esc_attr( $organic_farm_step['id'] ) . '">';
							echo '<span class="step-number">' . esc_html( $step_number ) . '</span>';
							echo '</li>';
							$step_number++;
						}
						?>
						<div class="blank-border"></div>
					</ul>

					<div class="step-loading"><span class="spinner"></span></div>
				</div>
			</div>
		</div>
		<?php
	}


	/**
	 * Set options for the steps
	 * Incorporate any options set by the theme dev
	 * Return the array for the steps
	 * @return Array
	 */
	public function get_steps() {
		$organic_farm_dev_steps = $this->config_steps;
		$organic_farm_steps = array( 
			'plugins' => array(
				'id'			=> 'plugins',
				'title'			=> __( 'Install and Activate Recommended Plugins', 'organic-farm' ),
				'icon'			=> 'admin-plugins',
				'view'			=> 'get_step_plugins',
				'callback'		=> 'install_plugins',
				'button_text'	=> __( 'Install Recommended Plugins', 'organic-farm' ),
				'can_skip'		=> true
			),
			'widgets' => array(
				'id'			=> 'widgets',
				'title'			=> __( 'Begin With Demo Import', 'organic-farm' ),
				'icon'			=> 'welcome-widgets-menus',
				'view'			=> 'get_step_widgets',
				'callback'		=> 'organic_farm_install_widgets',
				'button_text'	=> __( 'Begin With Demo Import', 'organic-farm' ),
				'can_skip'		=> false
			),
			'done' => array(
				'id'			=> 'done',
				'title'			=> __( 'Customize Your Site', 'organic-farm' ),
				'icon'			=> 'yes',
				'view'			=> 'get_step_done',
				'callback'		=> ''
			)
		);
		
		// Iterate through each step and replace with dev config values
		if( $organic_farm_dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from dashboard-settings.php
			$can_config = array( 'title', 'icon', 'button_text', 'can_skip' );
			foreach( $organic_farm_dev_steps as $organic_farm_dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $organic_farm_dev_step['id'] ) ) {
					$id = $organic_farm_dev_step['id'];
					if( isset( $organic_farm_steps[$id] ) ) {
						foreach( $can_config as $element ) {
							if( isset( $organic_farm_dev_step[$element] ) ) {
								$organic_farm_steps[$id][$element] = $organic_farm_dev_step[$element];
							}
						}
					}
				}
			}
		}
		return $organic_farm_steps;
	}

	/**
	 * Get the content for the plugins step
	 * @return $content Array
	 */
	public function get_step_plugins() {
		$plugins = $this->get_plugins();
		$content = array(); 
		
		// Add plugin name and type at the top
		$content['detail'] = '<div class="plugin-info">';
		$content['detail'] .= '<p><strong>Plugin</strong></p>';
		$content['detail'] .= '<p><strong>Type</strong></p>';
		$content['detail'] .= '</div>';
		
		// The detail element is initially hidden from the user
		$content['detail'] .= '<ul class="whizzie-do-plugins">';
		
		// Add each plugin into a list
		foreach( $plugins['all'] as $slug=>$plugin ) {
				$content['detail'] .= '<li data-slug="' . esc_attr( $slug ) . '">' . esc_html( $plugin['name'] ) . '<span>';
				$keys = array();
				if ( isset( $plugins['install'][ $slug ] ) ) {
					$keys[] = 'Installation';
				}
				if ( isset( $plugins['update'][ $slug ] ) ) {
					$keys[] = 'Update';
				}
				if ( isset( $plugins['activate'][ $slug ] ) ) {
					$keys[] = 'Activation';
				}
				$content['detail'] .= implode( ' and ', $keys ) . ' required';
				$content['detail'] .= '</span></li>';		}
		
		$content['detail'] .= '</ul>';
		
		return $content;
	}
	
	/**
	 * Print the content for the widgets step
	 * @since 1.1.0
	 */
	public function get_step_widgets() { ?> 
		<p class="note">
		    <?php
		    echo esc_html(
		        sprintf(
		            // Translators: %s refers to the theme or plugin name.
		            __( 'If your website is already live and containing data, please make a backup. This importer will override the %s\'s new customizable values.', 'organic-farm' ),
		            $this->organic_farm_theme_title
		        )
		    );
		    ?>
		</p>

	<?php }
	
	/**
	 * Print the content for the final step
	 */
	public function get_step_done() { ?>
			<h3><?php echo esc_html( 'Demo Import Successful' ); ?></h3>
			<div class="last_step_btns">
				<a target="_blank" href="<?php echo esc_url( get_home_url() ); ?>" class="button button-primary">
					<?php esc_html_e( 'View Site', 'organic-farm' ); ?>
				</a>
				<a target="_blank" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Customize Site', 'organic-farm' ); ?>
				</a>
				<a href="<?php echo esc_url(admin_url()); ?>" class="button button-primary">
					<?php esc_html_e( 'Done', 'organic-farm' ); ?>
				</a>
			</div>
	<?php }

	/**
	 * Get the plugins registered with TGMPA
	 */
	public function get_plugins() {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $instance->plugins as $slug=>$plugin ) {
			if( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}

	/**
	 * Get the widgets.wie file from the /content folder
	 * @return Mixed	Either the file or false
	 * @since 1.1.0
	 */
	public function has_widget_file() {
		if( file_exists( $this->widget_file_url ) ) {
			return true;
		}
		return false;
	}
	
	public function setup_plugins() {
		if ( ! check_ajax_referer( 'whizzie_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found','organic-farm' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();
		
		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin','organic-farm' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin','organic-farm' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin','organic-farm' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success','organic-farm' ) ) );
		}
		exit;
	}


	
	/**
	 * Imports the Demo Content
	 * @since 1.1.0
	 */
	public function organic_farm_setup_widgets(){

		require get_theme_file_path( '/inc/dashboard/setup.php' ); 

	    exit;
	}
}