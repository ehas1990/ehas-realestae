<?php
/**
 * Core Plugin Class
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RealEstateManager {

	/**
	 * Singleton instance.
	 *
	 * @var RealEstateManager|null
	 */
	private static $instance = null;

	/**
	 * Instances of internal modules.
	 *
	 * @var array
	 */
	public $modules = [];

	/**
	 * Get the singleton instance.
	 *
	 * @return RealEstateManager
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->check_dependencies();
		$this->includes();
		$this->init_modules();
		$this->hooks();
	}

	/**
	 * Check dependencies (like ACF) and show admin notice if missing.
	 */
	private function check_dependencies() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Admin notices for missing dependencies.
	 */
	public function admin_notices() {
		if ( ! class_exists( 'ACF' ) ) {
			?>
			<div class="notice notice-warning is-dismissible">
				<p><?php esc_html_e( 'Real Estate Management System requires Advanced Custom Fields (ACF or ACF Pro) to be installed and active.', 'custom-real-estate-manager' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Include all required component files.
	 */
	private function includes() {
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-post-types.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-taxonomies.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-acf-fields.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-admin.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-ajax.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-templates.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-shortcodes.php';
		require_once CREM_PLUGIN_DIR . 'includes/class-rem-agent-dashboard.php';
	}

	/**
	 * Initialize internal modules.
	 */
	private function init_modules() {
		$this->modules['post_types']      = new REM_Post_Types();
		$this->modules['taxonomies']      = new REM_Taxonomies();
		$this->modules['acf_fields']      = new REM_ACF_Fields();
		$this->modules['admin']           = new REM_Admin();
		$this->modules['ajax']            = new REM_AJAX();
		$this->modules['templates']       = new REM_Templates();
		$this->modules['shortcodes']      = new REM_Shortcodes();
		$this->modules['agent_dashboard'] = new REM_Agent_Dashboard();
	}

	/**
	 * Register hooks.
	 */
	private function hooks() {
		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		
		// Register Elementor Widget.
		add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );

		// Activation and Deactivation hook handlers.
		register_activation_hook( CREM_PLUGIN_BASENAME, array( $this, 'activate' ) );
		register_deactivation_hook( CREM_PLUGIN_BASENAME, array( $this, 'deactivate' ) );
	}

	/**
	 * Enqueue frontend CSS and JavaScript, plus third-party assets like Leaflet.
	 */
	public function enqueue_frontend_assets() {
		// Leaflet.js Assets for map rendering (free, open source)
		wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );

		// Custom plugin styles
		wp_enqueue_style( 'rem-frontend-styles', CREM_PLUGIN_URL . 'assets/css/frontend.css', array(), CREM_VERSION );

		// Custom plugin scripts
		wp_enqueue_script( 'rem-frontend-scripts', CREM_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery', 'leaflet-js' ), CREM_VERSION, true );

		// Pass data to Javascript
		wp_localize_script( 'rem-frontend-scripts', 'rem_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'rem_frontend_nonce' ),
			'plugin_url' => CREM_PLUGIN_URL
		) );
	}

	/**
	 * Activation callback.
	 */
	public function activate() {
		// Register CPTs & Taxonomies first so they are available when creating posts
		if ( isset( $this->modules['post_types'] ) ) {
			$this->modules['post_types']->register_post_types();
		}
		if ( isset( $this->modules['taxonomies'] ) ) {
			$this->modules['taxonomies']->register_taxonomies();
		}
		
		// Add Agent Role
		if ( ! get_role( 'agent' ) ) {
			add_role( 'agent', __( 'Agent', 'custom-real-estate-manager' ), array(
				'read'         => true,
				'upload_files' => true,
			) );
		}

		// Create Agent Dashboard page programmatically
		$dashboard_page = get_page_by_path( 'agent-dashboard' );
		if ( ! $dashboard_page ) {
			wp_insert_post( array(
				'post_title'   => 'Agent Dashboard',
				'post_content' => '[agent_dashboard]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_name'    => 'agent-dashboard',
			) );
		}

		flush_rewrite_rules();
	}

	/**
	 * Deactivation callback.
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Register custom Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_elementor_widgets( $widgets_manager ) {
		if ( file_exists( CREM_PLUGIN_DIR . 'includes/class-rem-elementor-widget.php' ) ) {
			require_once CREM_PLUGIN_DIR . 'includes/class-rem-elementor-widget.php';
			$widgets_manager->register( new \REM_Elementor_Search_Widget() );
		}
	}
}
