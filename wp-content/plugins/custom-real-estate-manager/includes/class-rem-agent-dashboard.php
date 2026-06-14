<?php
/**
 * Agent Dashboard and Frontend Actions
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Agent_Dashboard {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Restrict access to /wp-admin for agents
		add_action( 'admin_init', array( $this, 'restrict_wp_admin' ) );
		add_action( 'init', array( $this, 'disable_admin_bar_for_agents' ) );

		// Redirect to dashboard after login
		add_filter( 'login_redirect', array( $this, 'agent_login_redirect' ), 10, 3 );

		// Register shortcode
		add_action( 'init', array( $this, 'register_shortcodes' ) );

		// Block direct URL access to other agent properties
		add_action( 'template_redirect', array( $this, 'restrict_property_viewing' ) );

		// Intercept form submissions before output starts
		add_action( 'template_redirect', array( $this, 'process_dashboard_actions' ) );
	}

	/**
	 * Restrict wp-admin access for Agents.
	 */
	public function restrict_wp_admin() {
		if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
			$user = wp_get_current_user();
			if ( in_array( 'agent', (array) $user->roles ) ) {
				// Allow AJAX requests
				if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
					wp_safe_redirect( home_url( '/agent-dashboard/' ) );
					exit;
				}
			}
		}
	}

	/**
	 * Disable admin bar for Agent role on frontend.
	 */
	public function disable_admin_bar_for_agents() {
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			if ( in_array( 'agent', (array) $user->roles ) ) {
				show_admin_bar( false );
			}
		}
	}

	/**
	 * Redirect agents to the agent dashboard page after logging in.
	 */
	public function agent_login_redirect( $redirect_to, $request, $user ) {
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'agent', $user->roles ) ) {
				return home_url( '/agent-dashboard/' );
			}
		}
		return $redirect_to;
	}

	/**
	 * Block agents from viewing other agents' properties.
	 */
	public function restrict_property_viewing() {
		if ( is_singular( 'property' ) ) {
			$user = wp_get_current_user();
			if ( in_array( 'agent', (array) $user->roles ) ) {
				$post = get_post();
				if ( (int) $post->post_author !== (int) $user->ID ) {
					wp_die( 
						__( 'Access denied. You can only view your own properties.', 'custom-real-estate-manager' ), 
						__( 'Access Denied', 'custom-real-estate-manager' ), 
						array( 'response' => 403 ) 
					);
				}
			}
		}
	}

	/**
	 * Register Shortcodes.
	 */
	public function register_shortcodes() {
		add_shortcode( 'agent_dashboard', array( $this, 'render_dashboard_shortcode' ) );
	}

	/**
	 * Render the Agent Dashboard shortcode content.
	 */
	public function render_dashboard_shortcode() {
		ob_start();

		if ( ! is_user_logged_in() ) {
			$this->render_login_register_screens();
		} else {
			$user = wp_get_current_user();
			if ( in_array( 'agent', (array) $user->roles ) || current_user_can( 'manage_options' ) ) {
				$this->render_dashboard_panel( $user );
			} else {
				// Non-agents see a message or log out option
				echo '<div class="rem-dashboard-alert">';
				echo '<p>' . sprintf( __( 'You are logged in as <strong>%s</strong>. Only users with the Agent role can access the Agent Dashboard.', 'custom-real-estate-manager' ), esc_html( $user->display_name ) ) . '</p>';
				echo '<a href="' . esc_url( wp_logout_url( home_url( '/agent-dashboard/' ) ) ) . '" class="rem-btn rem-btn-primary">' . __( 'Log Out', 'custom-real-estate-manager' ) . '</a>';
				echo '</div>';
			}
		}

		return ob_get_clean();
	}

	/**
	 * Render Login and Register Forms.
	 */
	private function render_login_register_screens() {
		// Output notifications if any
		$this->display_notifications();
		?>
		<div class="rem-auth-container">
			<div class="rem-auth-card">
				<div class="rem-auth-tabs">
					<button class="rem-auth-tab-btn active" data-target="rem-login-form"><?php esc_html_e( 'Agent Login', 'custom-real-estate-manager' ); ?></button>
					<button class="rem-auth-tab-btn" data-target="rem-register-form"><?php esc_html_e( 'Register Account', 'custom-real-estate-manager' ); ?></button>
				</div>

				<!-- Login Screen -->
				<div id="rem-login-form" class="rem-auth-tab-content active">
					<form method="post" action="">
						<?php wp_nonce_field( 'rem_agent_login_action', 'rem_agent_login_nonce' ); ?>
						<input type="hidden" name="rem_action" value="login">
						
						<div class="rem-form-group">
							<label for="login-username"><?php esc_html_e( 'Username or Email', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="login-username" name="username" required placeholder="<?php esc_attr_e( 'Enter username or email', 'custom-real-estate-manager' ); ?>">
						</div>
						
						<div class="rem-form-group">
							<label for="login-password"><?php esc_html_e( 'Password', 'custom-real-estate-manager' ); ?></label>
							<input type="password" id="login-password" name="password" required placeholder="<?php esc_attr_e( 'Enter password', 'custom-real-estate-manager' ); ?>">
						</div>
						
						<button type="submit" class="rem-btn rem-btn-primary rem-btn-block"><?php esc_html_e( 'Sign In', 'custom-real-estate-manager' ); ?></button>
					</form>
				</div>

				<!-- Registration Screen -->
				<div id="rem-register-form" class="rem-auth-tab-content">
					<form method="post" action="" enctype="multipart/form-data">
						<?php wp_nonce_field( 'rem_agent_register_action', 'rem_agent_register_nonce' ); ?>
						<input type="hidden" name="rem_action" value="register">

						<div class="rem-form-grid">
							<div class="rem-form-group">
								<label for="reg-username"><?php esc_html_e( 'Username', 'custom-real-estate-manager' ); ?> *</label>
								<input type="text" id="reg-username" name="username" required placeholder="<?php esc_attr_e( 'e.g. johndoe', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="reg-email"><?php esc_html_e( 'Email Address', 'custom-real-estate-manager' ); ?> *</label>
								<input type="email" id="reg-email" name="email" required placeholder="<?php esc_attr_e( 'e.g. john@example.com', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="reg-password"><?php esc_html_e( 'Password', 'custom-real-estate-manager' ); ?> *</label>
								<input type="password" id="reg-password" name="password" required placeholder="<?php esc_attr_e( 'Create secure password', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="reg-fullname"><?php esc_html_e( 'Full Name (Agent Name)', 'custom-real-estate-manager' ); ?> *</label>
								<input type="text" id="reg-fullname" name="full_name" required placeholder="<?php esc_attr_e( 'e.g. John Doe', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="reg-phone"><?php esc_html_e( 'Mobile Number', 'custom-real-estate-manager' ); ?> *</label>
								<input type="text" id="reg-phone" name="phone" required placeholder="<?php esc_attr_e( 'e.g. +91 9876543210', 'custom-real-estate-manager' ); ?>">
							</div>

							<div class="rem-form-group">
								<label for="reg-whatsapp"><?php esc_html_e( 'WhatsApp Number', 'custom-real-estate-manager' ); ?></label>
								<input type="text" id="reg-whatsapp" name="whatsapp" placeholder="<?php esc_attr_e( 'e.g. +91 9876543210', 'custom-real-estate-manager' ); ?>">
							</div>
						</div>

						<div class="rem-form-group">
							<label for="reg-photo"><?php esc_html_e( 'Agent Photo', 'custom-real-estate-manager' ); ?></label>
							<input type="file" id="reg-photo" name="agent_photo_file" accept="image/*">
							<p class="description"><?php esc_html_e( 'Upload a professional photo (JPG/PNG). Max 5MB.', 'custom-real-estate-manager' ); ?></p>
						</div>

						<button type="submit" class="rem-btn rem-btn-primary rem-btn-block"><?php esc_html_e( 'Create Account', 'custom-real-estate-manager' ); ?></button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Logged In Agent Panel.
	 */
	private function render_dashboard_panel( $user ) {
		$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'overview';

		// Output notification notices
		$this->display_notifications();

		?>
		<div class="rem-dashboard-wrapper">
			
			<!-- Dashboard Header -->
			<header class="rem-db-header">
				<div class="rem-db-header-left">
					<span class="rem-db-logo"><span class="dashicons dashicons-admin-home"></span> <?php esc_html_e( 'B2B Real Estate System', 'custom-real-estate-manager' ); ?></span>
					<span class="rem-db-welcome"><?php printf( __( 'Welcome back, <strong>%s</strong>', 'custom-real-estate-manager' ), esc_html( $user->display_name ) ); ?></span>
				</div>
				<div class="rem-db-header-right">
					<!-- Notification Bell -->
					<div class="rem-db-bell-wrapper" style="position:relative; display:inline-block; margin-right:15px; vertical-align:middle;">
						<button type="button" class="rem-db-bell-trigger" style="background:none; border:none; color:var(--rem-text-main); font-size:22px; cursor:pointer; padding:5px; position:relative; display:flex; align-items:center; outline:none;">
							<span class="ab-icon dashicons-bell" style="font-size:24px; width:24px; height:24px;"></span>
							<?php
							$recipient_id = current_user_can( 'manage_options' ) ? 0 : $user->ID;
							$bell_unread = count( get_posts( array(
								'post_type'      => 'rem_notification',
								'post_status'    => 'publish',
								'posts_per_page' => -1,
								'author'         => $recipient_id,
								'meta_query'     => array(
									array(
										'key'     => 'is_read',
										'value'   => '0',
										'compare' => '=',
									),
								),
							) ) );
							$badge_style = $bell_unread > 0 ? '' : 'display:none;';
							?>
							<span class="rem-db-bell-badge" style="position:absolute; top:-2px; right:-2px; background:#ef4444; color:#fff; border-radius:50%; width:18px; height:18px; font-size:10px; font-weight:bold; display:flex; align-items:center; justify-content:center; line-height:1; <?php echo esc_attr( $badge_style ); ?>"><?php echo esc_html( $bell_unread ); ?></span>
						</button>
						
						<!-- Dropdown menu -->
						<div class="rem-db-notifications-dropdown" style="display:none; position:absolute; top:40px; right:0; z-index:9999; background:#fff; border:1px solid var(--rem-border-light); width:320px; max-height:400px; overflow-y:auto; box-shadow:var(--rem-shadow-popover); border-radius:var(--rem-radius-md); font-family:var(--rem-font);">
							<div class="rem-notifications-header" style="padding:12px; border-bottom:1px solid var(--rem-border-light); display:flex; justify-content:space-between; align-items:center;">
								<h3 style="margin:0; font-size:14px; font-weight:600; color:var(--rem-text-main);"><?php esc_html_e( 'Notifications', 'custom-real-estate-manager' ); ?></h3>
								<button type="button" class="rem-mark-all-read-btn" style="background:none; border:none; color:var(--rem-primary); cursor:pointer; font-size:11px; padding:0; font-family:inherit; font-weight: 600;"><?php esc_html_e( 'Mark all as read', 'custom-real-estate-manager' ); ?></button>
							</div>
							<ul class="rem-notifications-list" style="list-style:none; margin:0; padding:0;">
								<li class="rem-no-notifications" style="padding:20px; text-align:center; color:var(--rem-text-muted); font-size:13px;"><?php esc_html_e( 'No new notifications', 'custom-real-estate-manager' ); ?></li>
							</ul>
						</div>
					</div>
					<a href="<?php echo esc_url( home_url( '/properties/' ) ); ?>" target="_blank" class="rem-btn rem-btn-outline"><span class="dashicons dashicons-external"></span> <?php esc_html_e( 'Visit Site', 'custom-real-estate-manager' ); ?></a>
					<a href="<?php echo esc_url( wp_logout_url( home_url( '/agent-dashboard/' ) ) ); ?>" class="rem-btn rem-btn-danger"><span class="dashicons dashicons-logout"></span> <?php esc_html_e( 'Logout', 'custom-real-estate-manager' ); ?></a>
				</div>
			</header>

			<div class="rem-db-layout">
				
				<!-- Sidebar Nav -->
				<aside class="rem-db-sidebar">
					<ul class="rem-db-menu">
						<li>
							<a href="<?php echo esc_url( home_url( '/agent-dashboard/' ) ); ?>" class="<?php echo $action === 'overview' ? 'active' : ''; ?>">
								<span class="dashicons dashicons-dashboard"></span>
								<?php esc_html_e( 'Dashboard Overview', 'custom-real-estate-manager' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'action', 'add', home_url( '/agent-dashboard/' ) ) ); ?>" class="<?php echo $action === 'add' ? 'active' : ''; ?>">
								<span class="dashicons dashicons-plus"></span>
								<?php esc_html_e( 'Add New Property', 'custom-real-estate-manager' ); ?>
							</a>
						</li>
					</ul>
				</aside>

				<!-- Main Content Area -->
				<main class="rem-db-content">
					<?php
					if ( 'add' === $action ) {
						$this->render_property_form();
					} elseif ( 'edit' === $action ) {
						$this->render_property_form( true );
					} else {
						$this->render_overview_screen( $user->ID );
					}
					?>
				</main>

			</div>
		</div>
		<div id="rem-notification-modal" class="rem-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background:rgba(0,0,0,0.6); backdrop-filter: blur(4px); align-items:center; justify-content:center;">
			<div class="rem-modal-content" style="background:#fff; padding:28px; border-radius:12px; width:90%; max-width:480px; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.2); font-family:var(--rem-font); transition: transform 0.3s ease-out; transform: scale(0.9);">
				<span class="rem-modal-close" style="position:absolute; top:12px; right:16px; font-size:24px; font-weight:bold; color:#aaa; cursor:pointer; line-height:1;">&times;</span>
				<div class="rem-modal-body"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render widgets and properties table.
	 */
	private function render_overview_screen( $agent_id ) {
		$is_admin = current_user_can( 'manage_options' );

		// 1. Gather stats
		$query_args = array(
			'post_type'      => 'property',
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
		);
		if ( ! $is_admin ) {
			$query_args['author'] = $agent_id;
		}

		$all_properties = get_posts( $query_args );

		$total_count    = count( $all_properties );
		$approved_count = 0;
		$pending_count  = 0;
		$rejected_count = 0;

		foreach ( $all_properties as $p ) {
			$status = get_field( 'approval_status', $p->ID );
			if ( 'approved' === $status ) {
				$approved_count++;
			} elseif ( 'rejected' === $status ) {
				$rejected_count++;
			} else {
				$pending_count++;
			}
		}

		$agents_count = 0;
		if ( $is_admin ) {
			$agents_count = count( get_users( array( 'role' => 'agent' ) ) );
		}

		?>
		<?php
		$recipient_id = $is_admin ? 0 : $agent_id;
		$unread_notifications_count = count( get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => $recipient_id,
			'meta_query'     => array(
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
			),
		) ) );
		?>
		<!-- Widgets Row -->
		<div class="rem-db-widgets">
			<div class="rem-db-widget-card total">
				<div class="widget-icon"><span class="dashicons dashicons-admin-home"></span></div>
				<div class="widget-details">
					<h3 id="rem-agent-db-total-count"><?php echo esc_html( $total_count ); ?></h3>
					<p><?php echo $is_admin ? esc_html__( 'Total Properties', 'custom-real-estate-manager' ) : esc_html__( 'My Properties', 'custom-real-estate-manager' ); ?></p>
				</div>
			</div>

			<div class="rem-db-widget-card approved">
				<div class="widget-icon"><span class="dashicons dashicons-yes-alt"></span></div>
				<div class="widget-details">
					<h3 id="rem-agent-db-approved-count"><?php echo esc_html( $approved_count ); ?></h3>
					<p><?php esc_html_e( 'Approved Properties', 'custom-real-estate-manager' ); ?></p>
				</div>
			</div>

			<div class="rem-db-widget-card pending">
				<div class="widget-icon"><span class="dashicons dashicons-clock"></span></div>
				<div class="widget-details">
					<h3 id="rem-agent-db-pending-count"><?php echo esc_html( $pending_count ); ?></h3>
					<p><?php esc_html_e( 'Pending Properties', 'custom-real-estate-manager' ); ?></p>
				</div>
			</div>

			<div class="rem-db-widget-card rejected">
				<div class="widget-icon"><span class="dashicons dashicons-dismiss"></span></div>
				<div class="widget-details">
					<h3 id="rem-agent-db-rejected-count"><?php echo esc_html( $rejected_count ); ?></h3>
					<p><?php esc_html_e( 'Rejected Properties', 'custom-real-estate-manager' ); ?></p>
				</div>
			</div>

			<div class="rem-db-widget-card notifications" style="background:#fdf2f8; border-left:4px solid #db2777;">
				<div class="widget-icon" style="color:#db2777;"><span class="dashicons dashicons-bell"></span></div>
				<div class="widget-details">
					<h3 id="rem-agent-db-unread-notifications-count"><?php echo esc_html( $unread_notifications_count ); ?></h3>
					<p><?php esc_html_e( 'Notifications', 'custom-real-estate-manager' ); ?></p>
				</div>
			</div>

			<?php if ( $is_admin ) : ?>
				<div class="rem-db-widget-card agents">
					<div class="widget-icon"><span class="dashicons dashicons-admin-users"></span></div>
					<div class="widget-details">
						<h3><?php echo esc_html( $agents_count ); ?></h3>
						<p><?php esc_html_e( 'Total Agents', 'custom-real-estate-manager' ); ?></p>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Overview Header Panel -->
		<div class="rem-db-overview-header">
			<h2><?php echo $is_admin ? esc_html__( 'All Property Listings (Moderation Review)', 'custom-real-estate-manager' ) : esc_html__( 'My Property Listings', 'custom-real-estate-manager' ); ?></h2>
			<a href="<?php echo esc_url( add_query_arg( 'action', 'add', home_url( '/agent-dashboard/' ) ) ); ?>" class="rem-btn rem-btn-primary"><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Quick Add Property', 'custom-real-estate-manager' ); ?></a>
		</div>

		<!-- Listings Table -->
		<div class="rem-db-table-container">
			<table class="rem-db-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'ID', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Title', 'custom-real-estate-manager' ); ?></th>
						<?php if ( $is_admin ) : ?>
							<th><?php esc_html_e( 'Agent Name', 'custom-real-estate-manager' ); ?></th>
						<?php endif; ?>
						<th><?php esc_html_e( 'Price', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Type', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Location', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Date Created', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Moderation Status', 'custom-real-estate-manager' ); ?></th>
						<th><?php esc_html_e( 'Actions', 'custom-real-estate-manager' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $all_properties ) ) : ?>
						<?php foreach ( $all_properties as $p ) : 
							$p_id            = $p->ID;
							$prop_id         = get_field( 'property_id', $p_id );
							$price           = get_field( 'property_price', $p_id );
							$price_lbl       = get_field( 'property_price_label', $p_id );
							$formatted_price = ! empty( $price_lbl ) ? $price_lbl : ( $price ? rem_format_property_price( $price, $p_id ) : '-' );
							
							$type            = get_field( 'property_type', $p_id );
							$type_field      = acf_get_field( 'property_type' );
							$type_lbl        = ( $type_field && isset( $type_field['choices'][$type] ) ) ? $type_field['choices'][$type] : ucfirst( $type );

							$state_id        = get_field( 'property_state', $p_id );
							$district_id     = get_field( 'property_district', $p_id );
							$taluk_id        = get_field( 'property_taluk', $p_id );
							$place_val       = get_field( 'property_place', $p_id );
							$place           = '';
							if ( $place_val ) {
								if ( is_numeric( $place_val ) ) {
									$place = get_the_title( $place_val );
								} else {
									$place = $place_val;
								}
							}
							
							$loc_parts       = array();
							if ( $place ) $loc_parts[] = $place;
							if ( $taluk_id ) $loc_parts[] = get_the_title( $taluk_id );
							if ( $district_id ) $loc_parts[] = get_the_title( $district_id );
							$location_lbl    = implode( ', ', $loc_parts );

							$created_date    = get_the_date( get_option( 'date_format' ), $p_id );
							
							// Approval indicator
							$app_status      = get_field( 'approval_status', $p_id );
							if ( empty( $app_status ) ) {
								$app_status = 'pending';
							}

							$badge_styles    = array(
								'approved' => 'background:#e2f9e1; color:#1e7e34;',
								'pending'  => 'background:#fff8e1; color:#f57f17;',
								'rejected' => 'background:#fbe9e7; color:#d32f2f;'
							);
							$badge_labels    = array(
								'approved' => __( 'Approved', 'custom-real-estate-manager' ),
								'pending'  => __( 'Pending', 'custom-real-estate-manager' ),
								'rejected' => __( 'Rejected', 'custom-real-estate-manager' )
							);
							$bg_style        = isset( $badge_styles[$app_status] ) ? $badge_styles[$app_status] : $badge_styles['pending'];
							$lbl             = isset( $badge_labels[$app_status] ) ? $badge_labels[$app_status] : $badge_labels['pending'];

							$author_id       = get_post_field( 'post_author', $p_id );
							$author_user     = get_userdata( $author_id );
							$author_name     = $author_user ? $author_user->display_name : __( 'Administrator', 'custom-real-estate-manager' );
							?>
							<tr>
								<td data-label="ID"><strong><?php echo esc_html( $prop_id ? $prop_id : '-' ); ?></strong></td>
								<td data-label="Title"><?php echo esc_html( $p->post_title ); ?></td>
								<?php if ( $is_admin ) : ?>
									<td data-label="Agent">
										<?php echo esc_html( $author_name ); ?>
										<?php if ( $author_user && ! in_array( 'agent', (array) $author_user->roles ) ) : ?>
											<span style="font-size:10px; color:#6b7280; font-weight:bold;">(Admin)</span>
										<?php endif; ?>
									</td>
								<?php endif; ?>
								<td data-label="Price"><?php echo esc_html( $formatted_price ); ?></td>
								<td data-label="Type"><?php echo esc_html( $type_lbl ); ?></td>
								<td data-label="Location"><?php echo esc_html( $location_lbl ? $location_lbl : '-' ); ?></td>
								<td data-label="Date Created"><?php echo esc_html( $created_date ); ?></td>
								<td data-label="Moderation">
									<span style="<?php echo esc_attr( $bg_style ); ?> padding:4px 8px; border-radius:4px; font-weight:600; font-size:11px; text-transform:uppercase; display:inline-block;">
										<?php echo esc_html( $lbl ); ?>
									</span>
								</td>
								<td data-label="Actions" class="rem-table-actions">
									<?php if ( 'approved' === $app_status || $is_admin ) : ?>
										<a href="<?php echo esc_url( get_permalink( $p_id ) ); ?>" target="_blank" class="rem-action-btn view" title="<?php esc_attr_e( 'View Public Listing', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-visibility"></span></a>
									<?php else : ?>
										<span class="rem-action-btn view disabled" title="<?php esc_attr_e( 'Pending approval listings cannot be viewed publicly.', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-visibility"></span></span>
									<?php endif; ?>
									
									<?php if ( $is_admin || (int) $author_id === (int) get_current_user_id() ) : ?>
										<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $p_id ), home_url( '/agent-dashboard/' ) ) ); ?>" class="rem-action-btn edit" title="<?php esc_attr_e( 'Edit Listing', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-edit"></span></a>
									<?php endif; ?>
									
									<?php if ( $is_admin ) : ?>
										<?php if ( 'approved' !== $app_status ) : ?>
											<?php
											$approve_url = wp_nonce_url( add_query_arg( array(
												'rem_action' => 'approve_property',
												'id'         => $p_id
											), home_url( '/agent-dashboard/' ) ), 'rem_approve_' . $p_id );
											?>
											<a href="<?php echo esc_url( $approve_url ); ?>" class="rem-action-btn approve rem-admin-approve" style="background-color:#e2f9e1; color:#1e7e34;" title="<?php esc_attr_e( 'Approve Property', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-yes"></span></a>
										<?php endif; ?>
										
										<?php if ( 'rejected' !== $app_status ) : ?>
											<?php
											$reject_url = wp_nonce_url( add_query_arg( array(
												'rem_action' => 'reject_property',
												'id'         => $p_id
											), home_url( '/agent-dashboard/' ) ), 'rem_reject_' . $p_id );
											?>
											<a href="<?php echo esc_url( $reject_url ); ?>" class="rem-action-btn reject rem-admin-reject" style="background-color:#fbe9e7; color:#d32f2f;" title="<?php esc_attr_e( 'Reject Property', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-no"></span></a>
										<?php endif; ?>
									<?php endif; ?>

									<?php if ( $is_admin || (int) $author_id === (int) get_current_user_id() ) : ?>
										<?php
										$del_url = add_query_arg( array(
											'rem_action' => 'delete_property',
											'id'         => $p_id,
											'nonce'      => wp_create_nonce( 'rem_delete_' . $p_id )
										), home_url( '/agent-dashboard/' ) );
										?>
										<a href="<?php echo esc_url( $del_url ); ?>" class="rem-action-btn delete rem-delete-confirm" title="<?php esc_attr_e( 'Delete Listing', 'custom-real-estate-manager' ); ?>"><span class="dashicons dashicons-trash"></span></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="<?php echo $is_admin ? 9 : 8; ?>" style="text-align: center; padding: 30px;"><?php echo $is_admin ? esc_html__( 'No properties found in the system.', 'custom-real-estate-manager' ) : esc_html__( 'You have not submitted any properties yet. Click "Quick Add Property" to create your first listing.', 'custom-real-estate-manager' ); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Property Add / Edit Form.
	 */
	private function render_property_form( $is_edit = false ) {
		$post_id = 0;
		if ( $is_edit ) {
			$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
			
			// Verify ownership
			if ( ! $post_id || (int) get_post_field( 'post_author', $post_id ) !== (int) get_current_user_id() ) {
				echo '<div class="notice notice-error"><p>' . esc_html__( 'Unauthorized listing access.', 'custom-real-estate-manager' ) . '</p></div>';
				return;
			}
		}

		// Retrieve all States
		$states = get_posts( array(
			'post_type'      => 'state',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		) );

		// Pre-populate fields in edit mode
		$title         = $is_edit ? get_the_title( $post_id ) : '';
		$status        = $is_edit ? get_field( 'property_status', $post_id ) : 'for_sale';
		$type          = $is_edit ? get_field( 'property_type', $post_id ) : 'house';
		$price         = $is_edit ? get_field( 'property_price', $post_id ) : '';
		$price_label   = $is_edit ? get_field( 'property_price_label', $post_id ) : '';
		$description   = $is_edit ? get_field( 'property_description', $post_id ) : '';
		
		$state_id      = $is_edit ? get_field( 'property_state', $post_id ) : '';
		$district_id   = $is_edit ? get_field( 'property_district', $post_id ) : '';
		$taluk_id      = $is_edit ? get_field( 'property_taluk', $post_id ) : '';
		$place         = $is_edit ? get_field( 'property_place', $post_id ) : '';
		$address       = $is_edit ? get_field( 'property_address_1', $post_id ) : '';
		$landmark      = $is_edit ? get_field( 'property_landmark', $post_id ) : '';
		$pincode       = $is_edit ? get_field( 'property_pincode', $post_id ) : '';
		$map_url       = $is_edit ? get_field( 'property_map_url', $post_id ) : '';
		$lat           = $is_edit ? get_field( 'property_latitude', $post_id ) : '';
		$lng           = $is_edit ? get_field( 'property_longitude', $post_id ) : '';

		$area          = $is_edit ? get_field( 'property_area', $post_id ) : '';
		$area_unit     = $is_edit ? get_field( 'property_area_unit', $post_id ) : 'sq_ft';
		$bedrooms      = $is_edit ? get_field( 'property_bedrooms', $post_id ) : '';
		$bathrooms     = $is_edit ? get_field( 'property_bathrooms', $post_id ) : '';
		$floors        = $is_edit ? get_field( 'property_total_floors', $post_id ) : '';
		$parking       = $is_edit ? get_field( 'property_parking', $post_id ) : 'no';
		$parking_count = $is_edit ? get_field( 'property_parking_count', $post_id ) : '';
		$furnishing    = $is_edit ? get_field( 'property_furnishing', $post_id ) : 'unfurnished';
		$age           = $is_edit ? get_field( 'property_age', $post_id ) : '';
		$road_access   = $is_edit ? get_field( 'property_road_access', $post_id ) : '';

		$features      = $is_edit ? (array) get_field( 'property_features', $post_id ) : array();
		$video_url     = $is_edit ? get_field( 'property_video_url', $post_id ) : '';
		
		// File details in Edit mode
		$featured_img_url = $is_edit && get_post_thumbnail_id( $post_id ) ? wp_get_attachment_image_url( get_post_thumbnail_id( $post_id ), 'medium' ) : '';
		$gallery_attachments = $is_edit ? (array) get_field( 'property_gallery', $post_id ) : array();

		// Fetch cascading districts and taluks in edit mode
		$districts = array();
		if ( $is_edit && $state_id ) {
			$districts = get_posts( array(
				'post_type'      => 'district',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'belongs_to_state',
						'value'   => $state_id,
						'compare' => '=',
					),
				),
				'orderby'        => 'title',
				'order'          => 'ASC',
			) );
		}

		$taluks = array();
		if ( $is_edit && $district_id ) {
			$taluks = get_posts( array(
				'post_type'      => 'taluk',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'belongs_to_district',
						'value'   => $district_id,
						'compare' => '=',
					),
				),
				'orderby'        => 'title',
				'order'          => 'ASC',
			) );
		}

		$places = array();
		if ( $is_edit && $taluk_id ) {
			$places = get_posts( array(
				'post_type'      => 'location_place',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'belongs_to_taluk',
						'value'   => $taluk_id,
						'compare' => '=',
					),
				),
				'orderby'        => 'title',
				'order'          => 'ASC',
			) );
		}

		// Agent profiles fallback
		$current_user    = wp_get_current_user();
		$agent_name      = $is_edit ? get_field( 'agent_name', $post_id ) : get_user_meta( $current_user->ID, 'first_name', true ) . ' ' . get_user_meta( $current_user->ID, 'last_name', true );
		$agent_phone     = $is_edit ? get_field( 'agent_phone', $post_id ) : get_user_meta( $current_user->ID, 'agent_phone', true );
		$agent_whatsapp  = $is_edit ? get_field( 'agent_whatsapp', $post_id ) : get_user_meta( $current_user->ID, 'agent_whatsapp', true );
		$agent_email     = $is_edit ? get_field( 'agent_email', $post_id ) : $current_user->user_email;
		
		if ( empty( trim( $agent_name ) ) ) {
			$agent_name = $current_user->display_name;
		}

		?>
		<div class="rem-form-card-wrapper">
			<h2><?php echo $is_edit ? esc_html__( 'Edit Property Listing', 'custom-real-estate-manager' ) : esc_html__( 'Add New Property', 'custom-real-estate-manager' ); ?></h2>
			
			<form id="rem-property-form" method="post" action="" enctype="multipart/form-data">
				<?php wp_nonce_field( $is_edit ? 'rem_edit_property_' . $post_id : 'rem_add_property_action', 'rem_property_nonce' ); ?>
				<input type="hidden" name="rem_action" value="<?php echo $is_edit ? 'edit_property' : 'add_property'; ?>">
				<?php if ( $is_edit ) : ?>
					<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
				<?php endif; ?>

				<!-- Tab Navigation -->
				<div class="rem-form-tabs">
					<button type="button" class="rem-form-tab-btn active" data-tab="tab-basic"><span class="dashicons dashicons-admin-home"></span> <?php esc_html_e( 'Basic Info', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-form-tab-btn" data-tab="tab-location"><span class="dashicons dashicons-location"></span> <?php esc_html_e( 'Location', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-form-tab-btn" data-tab="tab-specs"><span class="dashicons dashicons-forms"></span> <?php esc_html_e( 'Details', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-form-tab-btn" data-tab="tab-amenities"><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Amenities', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-form-tab-btn" data-tab="tab-media"><span class="dashicons dashicons-format-gallery"></span> <?php esc_html_e( 'Images & Attachments', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-form-tab-btn" data-tab="tab-contact"><span class="dashicons dashicons-admin-users"></span> <?php esc_html_e( 'Contact Info', 'custom-real-estate-manager' ); ?></button>
				</div>

				<!-- Tab 1: Basic Info -->
				<div id="tab-basic" class="rem-form-tab-panel active">
					<div class="rem-form-grid">
						<div class="rem-form-group span-2">
							<label for="prop-title"><?php esc_html_e( 'Property Title', 'custom-real-estate-manager' ); ?> *</label>
							<input type="text" id="prop-title" name="property_title" value="<?php echo esc_attr( $title ); ?>" required placeholder="<?php esc_attr_e( 'Enter a descriptive title', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-status"><?php esc_html_e( 'Property Status', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-status" name="property_status" required>
								<option value="for_sale" <?php selected( $status, 'for_sale' ); ?>><?php esc_html_e( 'For Sale', 'custom-real-estate-manager' ); ?></option>
								<option value="sold" <?php selected( $status, 'sold' ); ?>><?php esc_html_e( 'Sold', 'custom-real-estate-manager' ); ?></option>
								<option value="booked" <?php selected( $status, 'booked' ); ?>><?php esc_html_e( 'Booked', 'custom-real-estate-manager' ); ?></option>
								<option value="off_plan" <?php selected( $status, 'off_plan' ); ?>><?php esc_html_e( 'Off Plan', 'custom-real-estate-manager' ); ?></option>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-type"><?php esc_html_e( 'Property Type', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-type" name="property_type" required>
								<option value="house" <?php selected( $type, 'house' ); ?>><?php esc_html_e( 'House', 'custom-real-estate-manager' ); ?></option>
								<option value="villa" <?php selected( $type, 'villa' ); ?>><?php esc_html_e( 'Villa', 'custom-real-estate-manager' ); ?></option>
								<option value="apartment" <?php selected( $type, 'apartment' ); ?>><?php esc_html_e( 'Apartment', 'custom-real-estate-manager' ); ?></option>
								<option value="flat" <?php selected( $type, 'flat' ); ?>><?php esc_html_e( 'Flat', 'custom-real-estate-manager' ); ?></option>
								<option value="commercial_building" <?php selected( $type, 'commercial_building' ); ?>><?php esc_html_e( 'Commercial Building', 'custom-real-estate-manager' ); ?></option>
								<option value="commercial_space" <?php selected( $type, 'commercial_space' ); ?>><?php esc_html_e( 'Commercial Space', 'custom-real-estate-manager' ); ?></option>
								<option value="plot" <?php selected( $type, 'plot' ); ?>><?php esc_html_e( 'Plot', 'custom-real-estate-manager' ); ?></option>
								<option value="agricultural_land" <?php selected( $type, 'agricultural_land' ); ?>><?php esc_html_e( 'Agricultural Land', 'custom-real-estate-manager' ); ?></option>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-price"><?php esc_html_e( 'Asking Price (INR)', 'custom-real-estate-manager' ); ?> *</label>
							<input type="number" id="prop-price" name="property_price" value="<?php echo esc_attr( $price ); ?>" required min="0" placeholder="<?php esc_attr_e( 'Numeric price (digits only)', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-price-lbl"><?php esc_html_e( 'Price Display Label', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-price-lbl" name="property_price_label" value="<?php echo esc_attr( $price_label ); ?>" placeholder="<?php esc_attr_e( 'e.g. ₹ 85 Lakhs', 'custom-real-estate-manager' ); ?>">
						</div>
					</div>

					<div class="rem-form-group" style="margin-top: 15px;">
						<label for="prop-description"><?php esc_html_e( 'Property Description', 'custom-real-estate-manager' ); ?> *</label>
						<?php 
						// Render editor safely or fallback to textarea. Since wp_editor can be finicky on frontend inside forms,
						// we'll use a styled textarea with basic formatting helper or wp_editor with config.
						wp_editor( $description, 'property_description', array(
							'textarea_name' => 'property_description',
							'media_buttons' => false,
							'textarea_rows' => 8,
							'teeny'         => true,
							'quicktags'     => false
						) );
						?>
					</div>
				</div>

				<!-- Tab 2: Location -->
				<div id="tab-location" class="rem-form-tab-panel">
					<div class="rem-form-grid">
						<div class="rem-form-group">
							<label for="prop-state"><?php esc_html_e( 'State', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-state" name="property_state" required>
								<option value=""><?php esc_html_e( 'Select State', 'custom-real-estate-manager' ); ?></option>
								<?php foreach ( $states as $s ) : ?>
									<option value="<?php echo esc_attr( $s->ID ); ?>" <?php selected( $state_id, $s->ID ); ?>><?php echo esc_html( $s->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-district"><?php esc_html_e( 'District', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-district" name="property_district" required <?php disabled( empty( $districts ) ); ?>>
								<option value=""><?php esc_html_e( 'Select District', 'custom-real-estate-manager' ); ?></option>
								<?php foreach ( $districts as $d ) : ?>
									<option value="<?php echo esc_attr( $d->ID ); ?>" <?php selected( $district_id, $d->ID ); ?>><?php echo esc_html( $d->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-taluk"><?php esc_html_e( 'Taluk', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-taluk" name="property_taluk" required <?php disabled( empty( $taluks ) ); ?>>
								<option value=""><?php esc_html_e( 'Select Taluk', 'custom-real-estate-manager' ); ?></option>
								<?php foreach ( $taluks as $t ) : ?>
									<option value="<?php echo esc_attr( $t->ID ); ?>" <?php selected( $taluk_id, $t->ID ); ?>><?php echo esc_html( $t->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-place"><?php esc_html_e( 'Location / Place', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-place" name="property_place" required <?php disabled( empty( $places ) ); ?>>
								<option value=""><?php esc_html_e( 'Select Location', 'custom-real-estate-manager' ); ?></option>
								<?php foreach ( $places as $pl ) : ?>
									<option value="<?php echo esc_attr( $pl->ID ); ?>" <?php selected( $place, $pl->ID ); ?>><?php echo esc_html( $pl->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="rem-form-group" style="margin-top: 15px;">
						<label for="prop-address"><?php esc_html_e( 'Full Address', 'custom-real-estate-manager' ); ?> *</label>
						<textarea id="prop-address" name="property_address_1" rows="3" required placeholder="<?php esc_attr_e( 'Complete postal address details', 'custom-real-estate-manager' ); ?>"><?php echo esc_textarea( $address ); ?></textarea>
					</div>

					<div class="rem-form-grid" style="margin-top: 15px;">
						<div class="rem-form-group">
							<label for="prop-landmark"><?php esc_html_e( 'Landmark', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-landmark" name="property_landmark" value="<?php echo esc_attr( $landmark ); ?>" placeholder="<?php esc_attr_e( 'e.g. Near Police Station', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-pincode"><?php esc_html_e( 'Pincode', 'custom-real-estate-manager' ); ?> *</label>
							<input type="text" id="prop-pincode" name="property_pincode" value="<?php echo esc_attr( $pincode ); ?>" required placeholder="<?php esc_attr_e( '6-digit postal code', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group span-2">
							<label for="prop-map"><?php esc_html_e( 'Google Map URL', 'custom-real-estate-manager' ); ?></label>
							<input type="url" id="prop-map" name="property_map_url" value="<?php echo esc_url( $map_url ); ?>" placeholder="<?php esc_attr_e( 'Paste Google Maps location link', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-lat"><?php esc_html_e( 'Latitude', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-lat" name="property_latitude" value="<?php echo esc_attr( $lat ); ?>" placeholder="<?php esc_attr_e( 'e.g. 10.998273', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-lng"><?php esc_html_e( 'Longitude', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-lng" name="property_longitude" value="<?php echo esc_attr( $lng ); ?>" placeholder="<?php esc_attr_e( 'e.g. 76.012384', 'custom-real-estate-manager' ); ?>">
						</div>
					</div>
				</div>

				<!-- Tab 3: Specs -->
				<div id="tab-specs" class="rem-form-tab-panel">
					<div class="rem-form-grid">
						<div class="rem-form-group">
							<label for="prop-area"><?php esc_html_e( 'Property Area', 'custom-real-estate-manager' ); ?> *</label>
							<input type="number" id="prop-area" name="property_area" value="<?php echo esc_attr( $area ); ?>" required min="0" placeholder="<?php esc_attr_e( 'e.g. 1500', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-area-unit"><?php esc_html_e( 'Area Unit', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-area-unit" name="property_area_unit" required>
								<option value="sq_ft" <?php selected( $area_unit, 'sq_ft' ); ?>><?php esc_html_e( 'Sq Ft', 'custom-real-estate-manager' ); ?></option>
								<option value="cent" <?php selected( $area_unit, 'cent' ); ?>><?php esc_html_e( 'Cent', 'custom-real-estate-manager' ); ?></option>
								<option value="acre" <?php selected( $area_unit, 'acre' ); ?>><?php esc_html_e( 'Acre', 'custom-real-estate-manager' ); ?></option>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-bedrooms"><?php esc_html_e( 'Bedrooms (BHK)', 'custom-real-estate-manager' ); ?></label>
							<input type="number" id="prop-bedrooms" name="property_bedrooms" value="<?php echo esc_attr( $bedrooms ); ?>" min="0" placeholder="<?php esc_attr_e( 'e.g. 3', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-bathrooms"><?php esc_html_e( 'Bathrooms', 'custom-real-estate-manager' ); ?></label>
							<input type="number" id="prop-bathrooms" name="property_bathrooms" value="<?php echo esc_attr( $bathrooms ); ?>" min="0" placeholder="<?php esc_attr_e( 'e.g. 3', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-floors"><?php esc_html_e( 'Total Floors', 'custom-real-estate-manager' ); ?></label>
							<input type="number" id="prop-floors" name="property_total_floors" value="<?php echo esc_attr( $floors ); ?>" min="0" placeholder="<?php esc_attr_e( 'e.g. 2', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-parking"><?php esc_html_e( 'Car Parking Available', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-parking" name="property_parking" required>
								<option value="no" <?php selected( $parking, 'no' ); ?>><?php esc_html_e( 'No', 'custom-real-estate-manager' ); ?></option>
								<option value="yes" <?php selected( $parking, 'yes' ); ?>><?php esc_html_e( 'Yes', 'custom-real-estate-manager' ); ?></option>
							</select>
						</div>

						<div id="parking-count-group" class="rem-form-group" style="<?php echo 'yes' === $parking ? '' : 'display:none;'; ?>">
							<label for="prop-parking-count"><?php esc_html_e( 'Number of Parking Slots', 'custom-real-estate-manager' ); ?></label>
							<input type="number" id="prop-parking-count" name="property_parking_count" value="<?php echo esc_attr( $parking_count ); ?>" min="0" placeholder="<?php esc_attr_e( 'e.g. 1', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-furnishing"><?php esc_html_e( 'Furnishing Status', 'custom-real-estate-manager' ); ?> *</label>
							<select id="prop-furnishing" name="property_furnishing" required>
								<option value="unfurnished" <?php selected( $furnishing, 'unfurnished' ); ?>><?php esc_html_e( 'Unfurnished', 'custom-real-estate-manager' ); ?></option>
								<option value="semi_furnished" <?php selected( $furnishing, 'semi_furnished' ); ?>><?php esc_html_e( 'Semi Furnished', 'custom-real-estate-manager' ); ?></option>
								<option value="fully_furnished" <?php selected( $furnishing, 'fully_furnished' ); ?>><?php esc_html_e( 'Fully Furnished', 'custom-real-estate-manager' ); ?></option>
							</select>
						</div>

						<div class="rem-form-group">
							<label for="prop-age"><?php esc_html_e( 'Property Age', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-age" name="property_age" value="<?php echo esc_attr( $age ); ?>" placeholder="<?php esc_attr_e( 'e.g. New / 5 Years', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="prop-road"><?php esc_html_e( 'Road Access (Ft)', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="prop-road" name="property_road_access" value="<?php echo esc_attr( $road_access ); ?>" placeholder="<?php esc_attr_e( 'e.g. 20 Ft Lorry Access', 'custom-real-estate-manager' ); ?>">
						</div>
					</div>
				</div>

				<!-- Tab 4: Amenities -->
				<div id="tab-amenities" class="rem-form-tab-panel">
					<h3><?php esc_html_e( 'Check Available Amenities', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-checkbox-list">
						<?php 
						$choices = array(
							'water_connection' => __( 'Water Connection', 'custom-real-estate-manager' ),
							'electricity'      => __( 'Electricity', 'custom-real-estate-manager' ),
							'compound_wall'    => __( 'Compound Wall', 'custom-real-estate-manager' ),
							'well_water'       => __( 'Well Water', 'custom-real-estate-manager' ),
							'swimming_pool'    => __( 'Swimming Pool', 'custom-real-estate-manager' ),
							'lift'             => __( 'Lift', 'custom-real-estate-manager' ),
							'cctv'             => __( 'CCTV', 'custom-real-estate-manager' ),
							'security'         => __( 'Security', 'custom-real-estate-manager' ),
							'garden'           => __( 'Garden', 'custom-real-estate-manager' ),
							'gym'              => __( 'Gym', 'custom-real-estate-manager' ),
							'kids_play_area'   => __( 'Kids Play Area', 'custom-real-estate-manager' ),
							'car_parking'      => __( 'Car Parking', 'custom-real-estate-manager' ),
						);

						foreach ( $choices as $val => $lbl ) :
							$checked = in_array( $val, $features ) ? 'checked' : '';
							?>
							<label class="checkbox-container">
								<input type="checkbox" name="property_features[]" value="<?php echo esc_attr( $val ); ?>" <?php echo $checked; ?>>
								<span class="checkbox-label"><?php echo esc_html( $lbl ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Tab 5: Media -->
				<div id="tab-media" class="rem-form-tab-panel">
					<!-- Featured Image -->
					<div class="rem-file-upload-block">
						<label><?php esc_html_e( 'Featured Image (JPEG/PNG)', 'custom-real-estate-manager' ); ?> *</label>
						<div class="rem-file-input-wrapper">
							<input type="file" name="featured_image_file" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>
							<?php if ( $featured_img_url ) : ?>
								<div class="rem-file-preview">
									<img src="<?php echo esc_url( $featured_img_url ); ?>" alt="Featured Image Preview">
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Gallery Images -->
					<div class="rem-file-upload-block" style="margin-top: 20px;">
						<label><?php esc_html_e( 'Property Photo Gallery (Upload multiple)', 'custom-real-estate-manager' ); ?></label>
						<div class="rem-file-input-wrapper">
							<input type="file" name="gallery_files[]" accept="image/*" multiple>
							<?php if ( ! empty( $gallery_attachments ) ) : ?>
								<div class="rem-gallery-preview-grid">
									<?php foreach ( $gallery_attachments as $img ) : 
										$img_url = is_array( $img ) ? $img['sizes']['thumbnail'] : wp_get_attachment_image_url( $img, 'thumbnail' );
										if ( $img_url ) :
										?>
											<div class="rem-gallery-preview-img">
												<img src="<?php echo esc_url( $img_url ); ?>" alt="Gallery Image">
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="rem-form-group" style="margin-top: 20px;">
						<label for="prop-video"><?php esc_html_e( 'Property Video URL (YouTube/Vimeo)', 'custom-real-estate-manager' ); ?></label>
						<input type="url" id="prop-video" name="property_video_url" value="<?php echo esc_url( $video_url ); ?>" placeholder="<?php esc_attr_e( 'https://www.youtube.com/watch?v=...', 'custom-real-estate-manager' ); ?>">
					</div>

					<!-- PDF Documents uploads -->
					<h3 style="margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 8px;"><?php esc_html_e( 'Verified Documents (PDF / Images)', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-form-grid" style="margin-top: 15px;">
						<?php 
						$docs = array(
							'brochure_file'         => __( 'Property Brochure', 'custom-real-estate-manager' ),
							'approval_cert_file'    => __( 'Approval Certificate', 'custom-real-estate-manager' ),
							'land_tax_file'         => __( 'Land Tax Receipt', 'custom-real-estate-manager' ),
							'ownership_doc_file'    => __( 'Ownership Document', 'custom-real-estate-manager' ),
							'survey_doc_file'       => __( 'Survey Document', 'custom-real-estate-manager' ),
							'building_permit_file'  => __( 'Building Permit Copy', 'custom-real-estate-manager' ),
							'floor_plan_file'       => __( 'Floor Plan Layout', 'custom-real-estate-manager' ),
							'other_attachments_file'=> __( 'Other Attachments', 'custom-real-estate-manager' ),
						);

						foreach ( $docs as $input_name => $label ) :
							$meta_key = str_replace( '_file', '', $input_name );
							// Map input name to ACF meta keys
							if ( 'brochure' === $meta_key ) $meta_key = 'property_brochure_pdf';
							elseif ( 'approval_cert' === $meta_key ) $meta_key = 'property_approval_certificate';
							elseif ( 'land_tax' === $meta_key ) $meta_key = 'property_land_tax_receipt';
							elseif ( 'ownership_doc' === $meta_key ) $meta_key = 'property_ownership_document';
							elseif ( 'survey_doc' === $meta_key ) $meta_key = 'property_survey_document';
							elseif ( 'building_permit' === $meta_key ) $meta_key = 'property_building_permit';
							elseif ( 'floor_plan' === $meta_key ) $meta_key = 'property_floor_plan_pdf';
							elseif ( 'other_attachments' === $meta_key ) $meta_key = 'property_other_attachments';
							
							$doc_meta = $is_edit ? get_field( $meta_key, $post_id ) : null;
							$doc_name = '';
							if ( $doc_meta ) {
								$doc_name = is_array( $doc_meta ) ? $doc_meta['filename'] : basename( get_attached_file( $doc_meta ) );
							}
							?>
							<div class="rem-form-group">
								<label><?php echo esc_html( $label ); ?></label>
								<input type="file" name="<?php echo esc_attr( $input_name ); ?>" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
								<?php if ( $doc_name ) : ?>
									<span class="file-name-meta"><span class="dashicons dashicons-media-document"></span> <?php echo esc_html( $doc_name ); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Tab 6: Contact Info -->
				<div id="tab-contact" class="rem-form-tab-panel">
					<h3><?php esc_html_e( 'Display Contact Information', 'custom-real-estate-manager' ); ?></h3>
					<div class="rem-form-grid" style="margin-top: 15px;">
						<div class="rem-form-group">
							<label for="agent-name"><?php esc_html_e( 'Agent / Owner Name', 'custom-real-estate-manager' ); ?> *</label>
							<input type="text" id="agent-name" name="agent_name" value="<?php echo esc_attr( $agent_name ); ?>" required placeholder="<?php esc_attr_e( 'Name displayed on property page', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="agent-phone"><?php esc_html_e( 'Mobile Number', 'custom-real-estate-manager' ); ?> *</label>
							<input type="text" id="agent-phone" name="agent_phone" value="<?php echo esc_attr( $agent_phone ); ?>" required placeholder="<?php esc_attr_e( 'e.g. +91 9876543210', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="agent-whatsapp"><?php esc_html_e( 'WhatsApp Number', 'custom-real-estate-manager' ); ?></label>
							<input type="text" id="agent-whatsapp" name="agent_whatsapp" value="<?php echo esc_attr( $agent_whatsapp ); ?>" placeholder="<?php esc_attr_e( 'Include country code, e.g. +91 9876543210', 'custom-real-estate-manager' ); ?>">
						</div>

						<div class="rem-form-group">
							<label for="agent-email"><?php esc_html_e( 'Email Address', 'custom-real-estate-manager' ); ?> *</label>
							<input type="email" id="agent-email" name="agent_email" value="<?php echo esc_attr( $agent_email ); ?>" required placeholder="<?php esc_attr_e( 'Agent email address', 'custom-real-estate-manager' ); ?>">
						</div>
					</div>

					<div class="rem-file-upload-block" style="margin-top: 20px;">
						<label><?php esc_html_e( 'Agent Photo', 'custom-real-estate-manager' ); ?></label>
						<input type="file" name="agent_photo_file" accept="image/*">
						<?php 
						$agent_photo = $is_edit ? get_field( 'agent_photo', $post_id ) : null;
						if ( $agent_photo ) : 
							$photo_url = is_numeric( $agent_photo ) ? wp_get_attachment_image_url( $agent_photo, 'thumbnail' ) : $agent_photo;
							if ( $photo_url ) :
							?>
								<div class="rem-file-preview">
									<img src="<?php echo esc_url( $photo_url ); ?>" alt="Agent Photo Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>

				<!-- Form Actions Footer -->
				<div class="rem-form-footer-actions">
					<button type="button" class="rem-btn rem-btn-outline prev-tab-btn" style="display:none;"><?php esc_html_e( 'Back', 'custom-real-estate-manager' ); ?></button>
					<button type="button" class="rem-btn rem-btn-primary next-tab-btn"><?php esc_html_e( 'Next Section', 'custom-real-estate-manager' ); ?></button>
					<button type="submit" class="rem-btn rem-btn-success submit-form-btn" style="display:none;"><?php echo $is_edit ? esc_html__( 'Update Property', 'custom-real-estate-manager' ) : esc_html__( 'Submit Property', 'custom-real-estate-manager' ); ?></button>
				</div>

			</form>
		</div>
		<?php
	}

	/**
	 * Intercept and process agent actions (Login, Registration, Submissions).
	 */
	public function process_dashboard_actions() {
		// Output notifications are saved in transients/sessions for redirects
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}

		$action = isset( $_POST['rem_action'] ) ? sanitize_text_field( $_POST['rem_action'] ) : '';
		
		// If GET delete action is triggered
		if ( isset( $_GET['rem_action'] ) && 'delete_property' === $_GET['rem_action'] ) {
			$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
			$nonce   = isset( $_GET['nonce'] ) ? sanitize_text_field( $_GET['nonce'] ) : '';

			if ( wp_verify_nonce( $nonce, 'rem_delete_' . $post_id ) ) {
				// Verify ownership or admin permission
				if ( (int) get_post_field( 'post_author', $post_id ) === (int) get_current_user_id() || current_user_can( 'manage_options' ) ) {
					// Trash property
					wp_trash_post( $post_id );
					$_SESSION['rem_notice'] = array(
						'type'    => 'success',
						'message' => __( 'Property moved to Trash successfully.', 'custom-real-estate-manager' )
					);
				} else {
					$_SESSION['rem_notice'] = array(
						'type'    => 'error',
						'message' => __( 'Unauthorized access.', 'custom-real-estate-manager' )
					);
				}
			} else {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Security verification failed.', 'custom-real-estate-manager' )
				);
			}

			wp_safe_redirect( home_url( '/agent-dashboard/' ) );
			exit;
		}

		// If GET approve property action is triggered
		if ( isset( $_GET['rem_action'] ) && 'approve_property' === $_GET['rem_action'] ) {
			$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
			$nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( $_GET['_wpnonce'] ) : '';

			if ( wp_verify_nonce( $nonce, 'rem_approve_' . $post_id ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					update_field( 'approval_status', 'approved', $post_id );
					wp_update_post( array(
						'ID'          => $post_id,
						'post_status' => 'publish',
					) );

					// Notify Agent via email
					$author_id = get_post_field( 'post_author', $post_id );
					$author_user = get_userdata( $author_id );
					if ( $author_user ) {
						$agent_name = $author_user->display_name;
						$to = $author_user->user_email;
						$subject = sprintf( __( '[REM] Property Approved: %s', 'custom-real-estate-manager' ), get_the_title( $post_id ) );
						$prop_id_lbl = get_field( 'property_id', $post_id );
						$message = sprintf(
							"Hello %s,\n\nCongratulations! Your property listing \"%s\" (ID: %s) has been approved by the Administrator and is now live on our website.\n\nYou can view it here: %s\n\nBest regards,\nB2B Real Estate System",
							$agent_name,
							get_the_title( $post_id ),
							$prop_id_lbl ? $prop_id_lbl : 'PROP-' . $post_id,
							get_permalink( $post_id )
						);
						wp_mail( $to, $subject, $message );
					}

					$_SESSION['rem_notice'] = array(
						'type'    => 'success',
						'message' => __( 'Property approved and published successfully.', 'custom-real-estate-manager' )
					);
				} else {
					$_SESSION['rem_notice'] = array(
						'type'    => 'error',
						'message' => __( 'Unauthorized access.', 'custom-real-estate-manager' )
					);
				}
			} else {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Security verification failed.', 'custom-real-estate-manager' )
				);
			}

			wp_safe_redirect( home_url( '/agent-dashboard/' ) );
			exit;
		}

		// If GET reject property action is triggered
		if ( isset( $_GET['rem_action'] ) && 'reject_property' === $_GET['rem_action'] ) {
			$post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
			$nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( $_GET['_wpnonce'] ) : '';
			$reason  = isset( $_GET['rejection_reason'] ) ? sanitize_text_field( $_GET['rejection_reason'] ) : '';

			if ( wp_verify_nonce( $nonce, 'rem_reject_' . $post_id ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					update_field( 'approval_status', 'rejected', $post_id );
					wp_update_post( array(
						'ID'          => $post_id,
						'post_status' => 'draft',
					) );

					// Save rejection reason
					update_post_meta( $post_id, '_rejection_reason', $reason );

					// Notify Agent via email
					$author_id = get_post_field( 'post_author', $post_id );
					$author_user = get_userdata( $author_id );
					if ( $author_user ) {
						$agent_name = $author_user->display_name;
						$to = $author_user->user_email;
						$subject = sprintf( __( '[REM] Property Rejected: %s', 'custom-real-estate-manager' ), get_the_title( $post_id ) );
						$prop_id_lbl = get_field( 'property_id', $post_id );
						$message = sprintf(
							"Hello %s,\n\nYour property listing \"%s\" (ID: %s) has been rejected by the Administrator.\n\nReason: %s\n\nPlease log in to your dashboard to edit and re-submit it.\n\nBest regards,\nB2B Real Estate System",
							$agent_name,
							get_the_title( $post_id ),
							$prop_id_lbl ? $prop_id_lbl : 'PROP-' . $post_id,
							$reason ? $reason : __( 'No reason provided.', 'custom-real-estate-manager' )
						);
						wp_mail( $to, $subject, $message );
					}

					$_SESSION['rem_notice'] = array(
						'type'    => 'success',
						'message' => __( 'Property rejected successfully.', 'custom-real-estate-manager' )
					);
				} else {
					$_SESSION['rem_notice'] = array(
						'type'    => 'error',
						'message' => __( 'Unauthorized access.', 'custom-real-estate-manager' )
					);
				}
			} else {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Security verification failed.', 'custom-real-estate-manager' )
				);
			}

			wp_safe_redirect( home_url( '/agent-dashboard/' ) );
			exit;
		}

		if ( empty( $action ) ) {
			return;
		}

		// 1. Process Custom Login
		if ( 'login' === $action ) {
			check_admin_referer( 'rem_agent_login_action', 'rem_agent_login_nonce' );
			
			$info = array(
				'user_login'    => sanitize_text_field( $_POST['username'] ),
				'user_password' => $_POST['password'],
				'remember'      => true
			);

			$user_signon = wp_signon( $info, false );

			if ( is_wp_error( $user_signon ) ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => $user_signon->get_error_message()
				);
			} else {
				// Login success, redirect to agent dashboard page
				wp_safe_redirect( home_url( '/agent-dashboard/' ) );
				exit;
			}
		}

		// 2. Process Custom Agent Registration
		if ( 'register' === $action ) {
			check_admin_referer( 'rem_agent_register_action', 'rem_agent_register_nonce' );

			$username  = sanitize_user( $_POST['username'] );
			$email     = sanitize_email( $_POST['email'] );
			$password  = $_POST['password'];
			$full_name = sanitize_text_field( $_POST['full_name'] );
			$phone     = sanitize_text_field( $_POST['phone'] );
			$whatsapp  = sanitize_text_field( $_POST['whatsapp'] );

			// Basic validations
			if ( username_exists( $username ) ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Username already exists.', 'custom-real-estate-manager' )
				);
				return;
			}
			if ( email_exists( $email ) ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Email address already registered.', 'custom-real-estate-manager' )
				);
				return;
			}

			// Parse display name
			$name_parts  = explode( ' ', $full_name, 2 );
			$first_name  = isset( $name_parts[0] ) ? $name_parts[0] : '';
			$last_name   = isset( $name_parts[1] ) ? $name_parts[1] : '';

			// Create WordPress User
			$user_id = wp_create_user( $username, $password, $email );

			if ( is_wp_error( $user_id ) ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => $user_id->get_error_message()
				);
				return;
			}

			// Set role to Agent
			$user = new WP_User( $user_id );
			$user->set_role( 'agent' );

			// Save profile meta fields
			wp_update_user( array(
				'ID'           => $user_id,
				'display_name' => $full_name,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
			) );

			update_user_meta( $user_id, 'agent_phone', $phone );
			if ( $whatsapp ) {
				update_user_meta( $user_id, 'agent_whatsapp', $whatsapp );
			}

			// Process registration photo upload if present
			if ( ! empty( $_FILES['agent_photo_file']['name'] ) ) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );

				// Assign photo to registration user context
				$photo_attachment_id = media_handle_upload( 'agent_photo_file', 0 );
				if ( ! is_wp_error( $photo_attachment_id ) ) {
					update_user_meta( $user_id, 'agent_photo', $photo_attachment_id );
				}
			}

			// Auto signon the newly registered agent
			$info = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => true
			);
			wp_signon( $info, false );

			$_SESSION['rem_notice'] = array(
				'type'    => 'success',
				'message' => __( 'Account created successfully! Welcome to your dashboard.', 'custom-real-estate-manager' )
			);

			wp_safe_redirect( home_url( '/agent-dashboard/' ) );
			exit;
		}

		// 3. Process Add / Edit Property Submission Form
		if ( 'add_property' === $action || 'edit_property' === $action ) {
			$is_edit = 'edit_property' === $action;
			$post_id = 0;

			if ( $is_edit ) {
				$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
				check_admin_referer( 'rem_edit_property_' . $post_id, 'rem_property_nonce' );

				// Verify ownership or admin permission
				if ( ! $post_id || ( (int) get_post_field( 'post_author', $post_id ) !== (int) get_current_user_id() && ! current_user_can( 'manage_options' ) ) ) {
					$_SESSION['rem_notice'] = array(
						'type'    => 'error',
						'message' => __( 'Unauthorized access.', 'custom-real-estate-manager' )
					);
					wp_safe_redirect( home_url( '/agent-dashboard/' ) );
					exit;
				}
			} else {
				check_admin_referer( 'rem_add_property_action', 'rem_property_nonce' );
			}

			// Gather fields
			$title        = isset( $_POST['property_title'] ) ? sanitize_text_field( $_POST['property_title'] ) : '';
			$description  = isset( $_POST['property_description'] ) ? wp_kses_post( $_POST['property_description'] ) : '';
			$status       = isset( $_POST['property_status'] ) ? sanitize_text_field( $_POST['property_status'] ) : 'for_sale';
			$type         = isset( $_POST['property_type'] ) ? sanitize_text_field( $_POST['property_type'] ) : 'house';
			$price        = isset( $_POST['property_price'] ) ? floatval( $_POST['property_price'] ) : 0.0;
			$price_label  = isset( $_POST['property_price_label'] ) ? sanitize_text_field( $_POST['property_price_label'] ) : '';

			$state        = isset( $_POST['property_state'] ) ? intval( $_POST['property_state'] ) : 0;
			$district     = isset( $_POST['property_district'] ) ? intval( $_POST['property_district'] ) : 0;
			$taluk        = isset( $_POST['property_taluk'] ) ? intval( $_POST['property_taluk'] ) : 0;
			$place        = isset( $_POST['property_place'] ) ? intval( $_POST['property_place'] ) : 0;
			$address      = isset( $_POST['property_address_1'] ) ? sanitize_textarea_field( $_POST['property_address_1'] ) : '';
			$landmark     = isset( $_POST['property_landmark'] ) ? sanitize_text_field( $_POST['property_landmark'] ) : '';
			$pincode      = isset( $_POST['property_pincode'] ) ? sanitize_text_field( $_POST['property_pincode'] ) : '';
			$map_url      = isset( $_POST['property_map_url'] ) ? esc_url_raw( $_POST['property_map_url'] ) : '';
			$lat          = isset( $_POST['property_latitude'] ) ? sanitize_text_field( $_POST['property_latitude'] ) : '';
			$lng          = isset( $_POST['property_longitude'] ) ? sanitize_text_field( $_POST['property_longitude'] ) : '';

			$area         = isset( $_POST['property_area'] ) ? floatval( $_POST['property_area'] ) : 0.0;
			$area_unit    = isset( $_POST['property_area_unit'] ) ? sanitize_text_field( $_POST['property_area_unit'] ) : 'sq_ft';
			$bedrooms     = isset( $_POST['property_bedrooms'] ) ? intval( $_POST['property_bedrooms'] ) : 0;
			$bathrooms    = isset( $_POST['property_bathrooms'] ) ? intval( $_POST['property_bathrooms'] ) : 0;
			$floors       = isset( $_POST['property_total_floors'] ) ? intval( $_POST['property_total_floors'] ) : 0;
			$parking      = isset( $_POST['property_parking'] ) ? sanitize_text_field( $_POST['property_parking'] ) : 'no';
			$parking_cnt  = isset( $_POST['property_parking_count'] ) ? intval( $_POST['property_parking_count'] ) : 0;
			$furnishing   = isset( $_POST['property_furnishing'] ) ? sanitize_text_field( $_POST['property_furnishing'] ) : 'unfurnished';
			$age          = isset( $_POST['property_age'] ) ? sanitize_text_field( $_POST['property_age'] ) : '';
			$road_access  = isset( $_POST['property_road_access'] ) ? sanitize_text_field( $_POST['property_road_access'] ) : '';

			$features     = isset( $_POST['property_features'] ) ? array_map( 'sanitize_text_field', $_POST['property_features'] ) : array();
			$video        = isset( $_POST['property_video_url'] ) ? esc_url_raw( $_POST['property_video_url'] ) : '';

			$agent_name   = isset( $_POST['agent_name'] ) ? sanitize_text_field( $_POST['agent_name'] ) : '';
			$agent_phone  = isset( $_POST['agent_phone'] ) ? sanitize_text_field( $_POST['agent_phone'] ) : '';
			$agent_whatsapp = isset( $_POST['agent_whatsapp'] ) ? sanitize_text_field( $_POST['agent_whatsapp'] ) : '';
			$agent_email  = isset( $_POST['agent_email'] ) ? sanitize_email( $_POST['agent_email'] ) : '';

			// Validate required fields
			if ( empty( $title ) || ! $price || ! $state || ! $district || ! $taluk || ! $place ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Please fill in all required fields (Title, Price, State, District, Taluk, and Location).', 'custom-real-estate-manager' )
				);
				$redirect_url = home_url( '/agent-dashboard/' );
				if ( $is_edit ) {
					$redirect_url = add_query_arg( array( 'action' => 'edit', 'id' => $post_id ), $redirect_url );
				} else {
					$redirect_url = add_query_arg( 'action', 'add', $redirect_url );
				}
				wp_safe_redirect( $redirect_url );
				exit;
			}

			// Insert/Update CPT Post
			$post_args = array(
				'post_title'   => $title,
				'post_content' => $description,
				'post_type'    => 'property',
			);

			if ( $is_edit ) {
				$post_args['ID'] = $post_id;
				// If agent is editing, force post_status to draft so it goes back to review!
				if ( ! current_user_can( 'manage_options' ) ) {
					$post_args['post_status'] = 'draft';
				}
				wp_update_post( $post_args );
			} else {
				// Agents' properties default to draft, Admins' properties default to publish
				$post_args['post_status'] = current_user_can( 'manage_options' ) ? 'publish' : 'draft';
				$post_args['post_author'] = get_current_user_id();
				$post_id = wp_insert_post( $post_args );
			}

			if ( is_wp_error( $post_id ) || ! $post_id ) {
				$_SESSION['rem_notice'] = array(
					'type'    => 'error',
					'message' => __( 'Failed to save property. Please try again.', 'custom-real-estate-manager' )
				);
				return;
			}

			// Include file libraries to upload attachments
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			// Save ACF details
			update_field( 'property_title', $title, $post_id );
			update_field( 'property_status', $status, $post_id );
			update_field( 'property_type', $type, $post_id );
			update_field( 'property_price', $price, $post_id );
			update_field( 'property_price_label', $price_label, $post_id );
			update_field( 'property_description', $description, $post_id );

			update_field( 'property_state', $state, $post_id );
			update_field( 'property_district', $district, $post_id );
			update_field( 'property_taluk', $taluk, $post_id );
			update_field( 'property_place', $place, $post_id );
			update_field( 'property_address_1', $address, $post_id );
			update_field( 'property_landmark', $landmark, $post_id );
			update_field( 'property_pincode', $pincode, $post_id );
			update_field( 'property_map_url', $map_url, $post_id );
			update_field( 'property_latitude', $lat, $post_id );
			update_field( 'property_longitude', $lng, $post_id );

			update_field( 'property_area', $area, $post_id );
			update_field( 'property_area_unit', $area_unit, $post_id );
			update_field( 'property_bedrooms', $bedrooms, $post_id );
			update_field( 'property_bathrooms', $bathrooms, $post_id );
			update_field( 'property_total_floors', $floors, $post_id );
			update_field( 'property_parking', $parking, $post_id );
			update_field( 'property_parking_count', $parking_cnt, $post_id );
			update_field( 'property_furnishing', $furnishing, $post_id );
			update_field( 'property_age', $age, $post_id );
			update_field( 'property_road_access', $road_access, $post_id );

			update_field( 'property_features', $features, $post_id );
			update_field( 'property_video_url', $video, $post_id );

			update_field( 'agent_name', $agent_name, $post_id );
			update_field( 'agent_phone', $agent_phone, $post_id );
			update_field( 'agent_whatsapp', $agent_whatsapp, $post_id );
			update_field( 'agent_email', $agent_email, $post_id );

			// Save default settings
			update_field( 'property_availability_status', 'available', $post_id );
			
			if ( current_user_can( 'manage_options' ) ) {
				if ( ! $is_edit ) {
					update_field( 'approval_status', 'approved', $post_id );
					update_field( 'assigned_agent', '', $post_id ); // Administrator default
				}
			} else {
				// For agents: set/reset to pending approval on add/edit
				update_field( 'approval_status', 'pending', $post_id );
				if ( ! $is_edit ) {
					update_field( 'assigned_agent', get_current_user_id(), $post_id );
				}

				// Notify Admin via email
				$admin_email = get_option( 'admin_email' );
				$subject = sprintf( __( '[REM] Property Submitted/Updated for Review: %s', 'custom-real-estate-manager' ), $title );
				$prop_id_lbl = $is_edit ? get_field( 'property_id', $post_id ) : 'PROP-' . $post_id;
				
				$current_user = wp_get_current_user();
				$agent_name = $current_user->display_name;

				$message = sprintf(
					"Hello Admin,\n\nAn Agent has submitted or updated a property listing for review.\n\nProperty Title: %s\nProperty ID: %s\nAgent Name: %s\n\nPlease log in to the admin panel to review this listing: %s\n\nBest regards,\nB2B Real Estate System",
					$title,
					$prop_id_lbl ? $prop_id_lbl : 'PROP-' . $post_id,
					$agent_name,
					admin_url( 'edit.php?post_type=property' )
				);
				wp_mail( $admin_email, $subject, $message );
			}

			// Generate Property ID natively
			$generated_id = 'PROP-' . $post_id;
			update_field( 'property_id', $generated_id, $post_id );

			// Handle uploads:
			// 1. Featured Image
			if ( ! empty( $_FILES['featured_image_file']['name'] ) ) {
				$feat_attachment_id = media_handle_upload( 'featured_image_file', $post_id );
				if ( ! is_wp_error( $feat_attachment_id ) ) {
					update_field( 'featured_image', $feat_attachment_id, $post_id );
					set_post_thumbnail( $post_id, $feat_attachment_id );
				}
			}

			// 2. Photo Gallery upload (multi file input)
			if ( ! empty( $_FILES['gallery_files']['name'][0] ) ) {
				$gallery_ids = array();
				
				// Retain existing gallery on edit unless they replace it
				if ( $is_edit ) {
					$existing_gallery = get_field( 'property_gallery', $post_id );
					if ( is_array( $existing_gallery ) ) {
						foreach ( $existing_gallery as $existing_item ) {
							$gallery_ids[] = is_array( $existing_item ) ? $existing_item['ID'] : $existing_item;
						}
					}
				}

				$files = $_FILES['gallery_files'];
				foreach ( $files['name'] as $key => $val_name ) {
					if ( $files['name'][$key] ) {
						$file_upload = array(
							'name'     => $files['name'][$key],
							'type'     => $files['type'][$key],
							'tmp_name' => $files['tmp_name'][$key],
							'error'    => $files['error'][$key],
							'size'     => $files['size'][$key],
						);
						$_FILES['gallery_single_upload'] = $file_upload;
						$gal_attach_id = media_handle_upload( 'gallery_single_upload', $post_id );
						if ( ! is_wp_error( $gal_attach_id ) ) {
							$gallery_ids[] = $gal_attach_id;
						}
					}
				}
				update_field( 'property_gallery', $gallery_ids, $post_id );
			}

			// 3. Document attachments
			$docs = array(
				'brochure_file'         => 'property_brochure_pdf',
				'approval_cert_file'    => 'property_approval_certificate',
				'land_tax_file'         => 'property_land_tax_receipt',
				'ownership_doc_file'    => 'property_ownership_document',
				'survey_doc_file'       => 'property_survey_document',
				'building_permit_file'  => 'property_building_permit',
				'floor_plan_file'       => 'property_floor_plan_pdf',
				'other_attachments_file'=> 'property_other_attachments',
			);

			foreach ( $docs as $input_name => $meta_key ) {
				if ( ! empty( $_FILES[$input_name]['name'] ) ) {
					$doc_attach_id = media_handle_upload( $input_name, $post_id );
					if ( ! is_wp_error( $doc_attach_id ) ) {
						update_field( $meta_key, $doc_attach_id, $post_id );
					}
				}
			}

			// 4. Agent Photo
			if ( ! empty( $_FILES['agent_photo_file']['name'] ) ) {
				$agent_photo_id = media_handle_upload( 'agent_photo_file', $post_id );
				if ( ! is_wp_error( $agent_photo_id ) ) {
					update_field( 'agent_photo', $agent_photo_id, $post_id );
				}
			}

			$_SESSION['rem_notice'] = array(
				'type'    => 'success',
				'message' => $is_edit ? __( 'Property updated successfully!', 'custom-real-estate-manager' ) : __( 'Property submitted successfully! It is now pending Administrator moderation.', 'custom-real-estate-manager' )
			);

			wp_safe_redirect( home_url( '/agent-dashboard/' ) );
			exit;
		}
	}

	/**
	 * Display session notices / notifications to user.
	 */
	private function display_notifications() {
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}

		if ( isset( $_SESSION['rem_notice'] ) ) {
			$notice = $_SESSION['rem_notice'];
			unset( $_SESSION['rem_notice'] );
			
			$class_type = 'notice-info';
			if ( 'success' === $notice['type'] ) $class_type = 'notice-success';
			elseif ( 'error' === $notice['type'] ) $class_type = 'notice-error';
			?>
			<div class="rem-notice-alert <?php echo esc_attr( $class_type ); ?>">
				<p><?php echo esc_html( $notice['message'] ); ?></p>
			</div>
			<?php
		}
	}
}
