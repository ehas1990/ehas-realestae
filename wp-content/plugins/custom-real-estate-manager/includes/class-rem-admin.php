<?php
/**
 * Admin Screen Functionality
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Enqueue admin assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		// Capture old status before ACF saves new values
		add_action( 'acf/save_post', array( $this, 'capture_old_property_status' ), 5 );

		// Sync ACF fields (title, image, property ID, agent ownership) on save.
		add_action( 'acf/save_post', array( $this, 'sync_property_fields_to_native' ), 20 );

		// Detect status changes and trigger notifications
		add_action( 'acf/save_post', array( $this, 'check_property_status_change' ), 30 );

		// Hide unnecessary default fields in the Classic Editor admin view.
		add_action( 'admin_head', array( $this, 'hide_default_editor_fields' ) );

		// Custom field validation rules.
		add_filter( 'acf/validate_value/name=property_pincode', array( $this, 'validate_pincode' ), 10, 4 );
		add_filter( 'acf/validate_value/name=agent_phone', array( $this, 'validate_phone_number' ), 10, 4 );
		add_filter( 'acf/validate_value/name=agent_whatsapp', array( $this, 'validate_phone_number' ), 10, 4 );

		// Filter ACF Select2 queries for cascading dropdowns.
		add_filter( 'acf/fields/post_object/query/key=field_property_district', array( $this, 'filter_districts_by_state' ), 10, 3 );
		add_filter( 'acf/fields/post_object/query/key=field_property_taluk', array( $this, 'filter_taluks_by_district' ), 10, 3 );

		// Add custom columns to Property list table
		add_filter( 'manage_property_posts_columns', array( $this, 'add_property_columns' ) );
		add_action( 'manage_property_posts_custom_column', array( $this, 'render_property_columns' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'add_property_row_actions' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'handle_property_status_actions' ) );

		// Custom views and query filters for property list page
		add_filter( 'views_edit-property', array( $this, 'add_property_admin_views' ) );
		add_filter( 'request', array( $this, 'filter_property_admin_list' ) );

		// Native admin dashboard widget
		add_action( 'wp_dashboard_setup', array( $this, 'add_admin_dashboard_widget' ) );

		// Register Agents list submenu page
		add_action( 'admin_menu', array( $this, 'register_agent_submenu' ) );

		// Admin Toolbar Notification Bell
		add_action( 'admin_bar_menu', array( $this, 'add_notification_bell_to_admin_bar' ), 999 );
		add_action( 'admin_footer', array( $this, 'render_admin_notification_elements' ) );
	}

	/**
	 * Enqueue Admin Scripts & Styles.
	 */
	public function enqueue_admin_assets( $hook ) {
		global $post;

		wp_enqueue_style( 'rem-admin-styles', CREM_PLUGIN_URL . 'assets/css/admin.css', array(), CREM_VERSION );
		wp_enqueue_script( 'rem-admin-scripts', CREM_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), CREM_VERSION, true );

		wp_localize_script( 'rem-admin-scripts', 'rem_admin_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'rem_admin_nonce' ),
			'post_id'  => $post ? $post->ID : 0,
		) );
	}

	/**
	 * Synchronize custom ACF fields (Property Title, Featured Image, Property ID) to native WordPress metadata.
	 *
	 * @param int $post_id The post ID.
	 */
	public function sync_property_fields_to_native( $post_id ) {
		// Only run for property post type.
		if ( get_post_type( $post_id ) !== 'property' ) {
			return;
		}

		// Prevent revision saves.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Temporarily unhook to prevent infinite loops.
		remove_action( 'acf/save_post', array( $this, 'sync_property_fields_to_native' ), 20 );

		// 1. Sync Title (ACF property_title -> post_title)
		$property_title = get_field( 'property_title', $post_id );
		if ( ! empty( $property_title ) ) {
			wp_update_post( array(
				'ID'         => $post_id,
				'post_title' => sanitize_text_field( $property_title ),
			) );
		}

		// 2. Sync Featured Image (ACF featured_image -> _thumbnail_id)
		$featured_image_id = get_field( 'featured_image', $post_id );
		if ( ! empty( $featured_image_id ) ) {
			set_post_thumbnail( $post_id, intval( $featured_image_id ) );
		} else {
			delete_post_thumbnail( $post_id );
		}

		// 3. Auto generate Property ID if empty (Format: PROP-{post_id})
		$property_id = get_field( 'property_id', $post_id );
		if ( empty( $property_id ) ) {
			$generated_id = 'PROP-' . $post_id;
			update_field( 'property_id', $generated_id, $post_id );
		}

		// 4. Sync Assigned Agent & Native post_author
		$assigned_agent_id = get_field( 'assigned_agent', $post_id );
		$current_author    = get_post_field( 'post_author', $post_id );

		if ( ! empty( $assigned_agent_id ) ) {
			if ( (int) $current_author !== (int) $assigned_agent_id ) {
				wp_update_post( array(
					'ID'          => $post_id,
					'post_author' => intval( $assigned_agent_id ),
				) );
			}
		} else {
			// If empty, check if post_author is an agent.
			// If so, populate assigned_agent.
			$author_user = get_userdata( $current_author );
			if ( $author_user && in_array( 'agent', (array) $author_user->roles ) ) {
				update_field( 'assigned_agent', $current_author, $post_id );
			} else {
				// If the author is not an agent, ensure post author is an Administrator user.
				// (The default falls to the Admin who created it).
				// We keep assigned_agent empty (null) to represent "Administrator".
			}
		}

		// Re-hook action.
		add_action( 'acf/save_post', array( $this, 'sync_property_fields_to_native' ), 20 );
	}

	/**
	 * Hide default WordPress title editor, main wysiwyg, and featured image block.
	 */
	public function hide_default_editor_fields() {
		global $post_type;
		if ( 'property' === $post_type ) {
			echo '<style>
				#titlewrap,
				#postdivrich,
				#postimagediv {
					display: none !important;
				}
			</style>';
		}
	}

	/**
	 * Validate Property Pincode (must be exactly 6 digits).
	 */
	public function validate_pincode( $valid, $value, $field, $input ) {
		if ( ! $valid || empty( $value ) ) {
			return $valid;
		}

		if ( ! preg_match( '/^[1-9][0-9]{5}$/', $value ) ) {
			$valid = __( 'Please enter a valid 6-digit Pincode (e.g. 682001).', 'custom-real-estate-manager' );
		}

		return $valid;
	}

	/**
	 * Validate Phone Numbers (must be valid phone digits).
	 */
	public function validate_phone_number( $valid, $value, $field, $input ) {
		if ( ! $valid || empty( $value ) ) {
			return $valid;
		}

		// Standard international/local phone formats validation
		if ( ! preg_match( '/^\+?[0-9\s\-()]{10,15}$/', $value ) ) {
			$valid = __( 'Please enter a valid mobile number (10 to 15 digits).', 'custom-real-estate-manager' );
		}

		return $valid;
	}


	/**
	 * Filter Districts based on the selected State.
	 */
	public function filter_districts_by_state( $args, $field, $post_id ) {
		if ( isset( $_REQUEST['state_id'] ) && ! empty( $_REQUEST['state_id'] ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'belongs_to_state',
					'value'   => intval( $_REQUEST['state_id'] ),
					'compare' => '=',
				),
			);
		} else {
			if ( isset( $_REQUEST['state_id'] ) ) {
				$args['post__in'] = array( 0 );
			}
		}
		return $args;
	}

	/**
	 * Filter Taluks based on the selected District.
	 */
	public function filter_taluks_by_district( $args, $field, $post_id ) {
		if ( isset( $_REQUEST['district_id'] ) && ! empty( $_REQUEST['district_id'] ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'belongs_to_district',
					'value'   => intval( $_REQUEST['district_id'] ),
					'compare' => '=',
				),
			);
		} else {
			if ( isset( $_REQUEST['district_id'] ) ) {
				$args['post__in'] = array( 0 );
			}
		}
		return $args;
	}

	/**
	 * Add custom columns to Property CPT edit list.
	 */
	public function add_property_columns( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			$new_columns[$key] = $value;
			if ( 'title' === $key ) {
				$new_columns['property_id']     = __( 'Property ID', 'custom-real-estate-manager' );
				$new_columns['assigned_agent']  = __( 'Assigned Agent', 'custom-real-estate-manager' );
				$new_columns['approval_status'] = __( 'Approval Status', 'custom-real-estate-manager' );
			}
		}
		return $new_columns;
	}

	/**
	 * Render column content in Property edit list.
	 */
	public function render_property_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'property_id':
				$prop_id = get_field( 'property_id', $post_id );
				echo esc_html( $prop_id ? $prop_id : '-' );
				break;
			case 'assigned_agent':
				$author_id = get_post_field( 'post_author', $post_id );
				$user = get_userdata( $author_id );
				if ( $user ) {
					if ( in_array( 'agent', (array) $user->roles ) ) {
						echo esc_html( $user->display_name ) . ' <span class="description">(' . esc_html( $user->user_login ) . ')</span>';
					} else {
						echo '<strong>' . esc_html__( 'Administrator', 'custom-real-estate-manager' ) . '</strong>';
					}
				} else {
					echo '-';
				}
				break;
			case 'approval_status':
				$status = get_field( 'approval_status', $post_id );
				if ( empty( $status ) ) {
					$status = 'pending';
				}
				$labels = array(
					'pending'  => __( 'Pending Approval', 'custom-real-estate-manager' ),
					'approved' => __( 'Approved', 'custom-real-estate-manager' ),
					'rejected' => __( 'Rejected', 'custom-real-estate-manager' ),
				);
				$label = isset( $labels[$status] ) ? $labels[$status] : $status;
				
				$bg_color = '#f0f0f1';
				$text_color = '#3c434a';
				if ( 'approved' === $status ) {
					$bg_color = '#e2f9e1';
					$text_color = '#1e7e34';
				} elseif ( 'rejected' === $status ) {
					$bg_color = '#fbe9e7';
					$text_color = '#d32f2f';
				} elseif ( 'pending' === $status ) {
					$bg_color = '#fff8e1';
					$text_color = '#f57f17';
				}

				echo '<span style="background: ' . esc_attr( $bg_color ) . '; color: ' . esc_attr( $text_color ) . '; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; text-transform: uppercase;">' . esc_html( $label ) . '</span>';
				break;
		}
	}

	/**
	 * Append quick actions Approve/Reject to property row links.
	 */
	/**
	 * Append quick actions Approve/Reject to property row links.
	 */
	public function add_property_row_actions( $actions, $post ) {
		if ( 'property' !== $post->post_type ) {
			return $actions;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return $actions;
		}

		$status = get_field( 'approval_status', $post->ID );
		if ( empty( $status ) ) {
			$status = 'pending';
		}

		$approve_nonce = wp_create_nonce( 'rem_approve_' . $post->ID );
		$reject_nonce  = wp_create_nonce( 'rem_reject_' . $post->ID );

		// Custom ordering: Edit | View | Approve | Reject | Trash
		$new_actions = array();
		if ( isset( $actions['edit'] ) ) {
			$new_actions['edit'] = $actions['edit'];
		}
		if ( isset( $actions['view'] ) ) {
			$new_actions['view'] = $actions['view'];
		}

		if ( 'approved' !== $status ) {
			$approve_url = add_query_arg( array(
				'action'   => 'rem_approve_property',
				'post_id'  => $post->ID,
				'_wpnonce' => $approve_nonce,
			) );
			$new_actions['approve'] = '<a href="' . esc_url( $approve_url ) . '" class="rem-admin-approve" style="color: #1e7e34; font-weight: bold;">' . __( 'Approve', 'custom-real-estate-manager' ) . '</a>';
		}

		if ( 'rejected' !== $status ) {
			$reject_url = add_query_arg( array(
				'action'   => 'rem_reject_property',
				'post_id'  => $post->ID,
				'_wpnonce' => $reject_nonce,
			) );
			$new_actions['reject'] = '<a href="' . esc_url( $reject_url ) . '" class="rem-admin-reject" style="color: #d32f2f; font-weight: bold;">' . __( 'Reject', 'custom-real-estate-manager' ) . '</a>';
		}

		if ( isset( $actions['trash'] ) ) {
			$new_actions['trash'] = $actions['trash'];
		}

		return $new_actions;
	}

	/**
	 * Process Approve / Reject status changes via Admin Row links.
	 */
	public function handle_property_status_actions() {
		if ( ! isset( $_GET['action'] ) || ! isset( $_GET['post_id'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$post_id = intval( $_GET['post_id'] );
		$action  = sanitize_text_field( $_GET['action'] );

		if ( 'rem_approve_property' === $action ) {
			check_admin_referer( 'rem_approve_' . $post_id );
			update_field( 'approval_status', 'approved', $post_id );
			wp_update_post( array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			) );

			wp_safe_redirect( wp_get_referer() );
			exit;
		}

		if ( 'rem_reject_property' === $action ) {
			check_admin_referer( 'rem_reject_' . $post_id );
			
			$reason = isset( $_GET['rejection_reason'] ) ? sanitize_text_field( $_GET['rejection_reason'] ) : '';
			
			// Save rejection reason as meta FIRST (before trigger hooks)
			update_post_meta( $post_id, '_rejection_reason', $reason );

			update_field( 'approval_status', 'rejected', $post_id );
			// Keep property in Draft status
			wp_update_post( array(
				'ID'          => $post_id,
				'post_status' => 'draft',
			) );

			wp_safe_redirect( wp_get_referer() );
			exit;
		}
	}

	/**
	 * Custom property filter views at the top of edit.php property list screen.
	 */
	public function add_property_admin_views( $views ) {
		$post_type = 'property';
		
		$count_all = wp_count_posts( $post_type );
		
		$pending_count = count( get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'approval_status',
					'value'   => 'pending',
					'compare' => '=',
				),
			),
		) ) );

		$approved_count = count( get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'approval_status',
					'value'   => 'approved',
					'compare' => '=',
				),
			),
		) ) );

		$rejected_count = count( get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'approval_status',
					'value'   => 'rejected',
					'compare' => '=',
				),
			),
		) ) );

		$my_count = count( get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
			'author'         => get_current_user_id(),
		) ) );

		$current_view = isset( $_GET['approval_filter'] ) ? sanitize_text_field( $_GET['approval_filter'] ) : 'all';

		$views['all_properties'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			admin_url( 'edit.php?post_type=property' ),
			( 'all' === $current_view && ! isset( $_GET['author'] ) ) ? 'current' : '',
			__( 'All Properties', 'custom-real-estate-manager' ),
			(int) $count_all->publish + (int) $count_all->draft + (int) $count_all->pending
		);

		$views['pending_approval'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			add_query_arg( 'approval_filter', 'pending', admin_url( 'edit.php?post_type=property' ) ),
			'pending' === $current_view ? 'current' : '',
			__( 'Pending Approval', 'custom-real-estate-manager' ),
			$pending_count
		);

		$views['approved'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			add_query_arg( 'approval_filter', 'approved', admin_url( 'edit.php?post_type=property' ) ),
			'approved' === $current_view ? 'current' : '',
			__( 'Approved', 'custom-real-estate-manager' ),
			$approved_count
		);

		$views['rejected'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			add_query_arg( 'approval_filter', 'rejected', admin_url( 'edit.php?post_type=property' ) ),
			'rejected' === $current_view ? 'current' : '',
			__( 'Rejected', 'custom-real-estate-manager' ),
			$rejected_count
		);

		$views['my_properties'] = sprintf(
			'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
			add_query_arg( 'author', get_current_user_id(), admin_url( 'edit.php?post_type=property' ) ),
			( isset( $_GET['author'] ) && (int) $_GET['author'] === (int) get_current_user_id() ) ? 'current' : '',
			__( 'My Properties', 'custom-real-estate-manager' ),
			$my_count
		);

		return array(
			'all'     => $views['all_properties'],
			'pending' => $views['pending_approval'],
			'approved'=> $views['approved'],
			'rejected'=> $views['rejected'],
			'my'      => $views['my_properties'],
		);
	}

	/**
	 * Intercept query variables on property list screen to apply approval filter.
	 */
	public function filter_property_admin_list( $query_vars ) {
		global $pagenow;
		if ( is_admin() && 'edit.php' === $pagenow && isset( $query_vars['post_type'] ) && 'property' === $query_vars['post_type'] ) {
			if ( isset( $_GET['approval_filter'] ) ) {
				$filter = sanitize_text_field( $_GET['approval_filter'] );
				if ( in_array( $filter, array( 'pending', 'approved', 'rejected' ) ) ) {
					$query_vars['meta_query'] = array(
						array(
							'key'     => 'approval_status',
							'value'   => $filter,
							'compare' => '=',
						),
					);
				}
			}
		}
		return $query_vars;
	}

	/**
	 * Register native WordPress dashboard widget.
	 */
	public function add_admin_dashboard_widget() {
		wp_add_dashboard_widget(
			'rem_admin_dashboard_widget',
			__( 'Real Estate Management Summary', 'custom-real-estate-manager' ),
			array( $this, 'render_admin_dashboard_widget' )
		);
	}

	/**
	 * Render native dashboard widget content.
	 */
	public function render_admin_dashboard_widget() {
		$post_type = 'property';
		
		$all_properties = get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
		) );
		
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
		
		$agents_count = count( get_users( array( 'role' => 'agent' ) ) );

		$unread_notifications_count = count( get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => 0, // Admin notifications
			'meta_query'     => array(
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
			),
		) ) );
		
		?>
		<div class="rem-admin-db-widget-content" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 5px 0;">
			<div style="background: #f0f6fc; border-left: 4px solid #0a4b78; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'Total Properties', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-total-count" style="font-size: 20px; color: #0a4b78;"><?php echo esc_html( $total_count ); ?></strong>
			</div>
			<div style="background: #fff8e1; border-left: 4px solid #f57f17; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'Pending Approval', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-pending-count" style="font-size: 20px; color: #f57f17;"><?php echo esc_html( $pending_count ); ?></strong>
			</div>
			<div style="background: #e2f9e1; border-left: 4px solid #1e7e34; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'Approved Properties', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-approved-count" style="font-size: 20px; color: #1e7e34;"><?php echo esc_html( $approved_count ); ?></strong>
			</div>
			<div style="background: #fbe9e7; border-left: 4px solid #d32f2f; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'Rejected Properties', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-rejected-count" style="font-size: 20px; color: #d32f2f;"><?php echo esc_html( $rejected_count ); ?></strong>
			</div>
			<div style="background: #fdf2f8; border-left: 4px solid #db2777; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'New Notifications', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-unread-notifications-count" style="font-size: 20px; color: #db2777;"><?php echo esc_html( $unread_notifications_count ); ?></strong>
			</div>
			<div style="background: #f3f4f6; border-left: 4px solid #6b7280; padding: 12px; border-radius: 4px;">
				<h4 style="margin: 0 0 5px 0; font-size: 13px; color: #1d2327; font-weight: 600;"><?php esc_html_e( 'Total Agents Registered', 'custom-real-estate-manager' ); ?></h4>
				<strong id="rem-admin-db-agents-count" style="font-size: 20px; color: #6b7280;"><?php echo esc_html( $agents_count ); ?></strong>
			</div>
		</div>
		<div style="margin-top: 15px; border-top: 1px solid #dcdcde; padding-top: 10px; text-align: right;">
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=property' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Manage Listings', 'custom-real-estate-manager' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=property&page=rem-agents' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'View Agents Directory', 'custom-real-estate-manager' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Register Registered Agents submenu.
	 */
	public function register_agent_submenu() {
		add_submenu_page(
			'edit.php?post_type=property',
			__( 'Agents Directory', 'custom-real-estate-manager' ),
			__( 'Agents', 'custom-real-estate-manager' ),
			'manage_options',
			'rem-agents',
			array( $this, 'render_agents_directory' )
		);
	}

	/**
	 * Render Registered Agents submenu layout.
	 */
	public function render_agents_directory() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Access denied.', 'custom-real-estate-manager' ) );
		}

		$agents = get_users( array(
			'role'    => 'agent',
			'orderby' => 'display_name',
			'order'   => 'ASC',
		) );

		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Registered Agents', 'custom-real-estate-manager' ); ?></h1>
			<hr class="wp-header-end">

			<table class="wp-list-table widefat fixed striped table-view-list users" style="margin-top: 15px;">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-username column-primary"><?php esc_html_e( 'Agent Name', 'custom-real-estate-manager' ); ?></th>
						<th scope="col" class="manage-column column-email"><?php esc_html_e( 'Email', 'custom-real-estate-manager' ); ?></th>
						<th scope="col" class="manage-column column-phone"><?php esc_html_e( 'Mobile / WhatsApp', 'custom-real-estate-manager' ); ?></th>
						<th scope="col" class="manage-column column-posts"><?php esc_html_e( 'Properties Assigned', 'custom-real-estate-manager' ); ?></th>
						<th scope="col" class="manage-column column-registered"><?php esc_html_e( 'Registered Date', 'custom-real-estate-manager' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $agents ) ) : ?>
						<?php foreach ( $agents as $agent ) : 
							$phone      = get_user_meta( $agent->ID, 'agent_phone', true );
							$whatsapp   = get_user_meta( $agent->ID, 'agent_whatsapp', true );
							$registered = date_i18n( get_option( 'date_format' ), strtotime( $agent->user_registered ) );
							$properties_count = count_user_posts( $agent->ID, 'property', true );
							?>
							<tr>
								<td class="username column-username column-primary">
									<strong><a href="<?php echo esc_url( get_edit_user_link( $agent->ID ) ); ?>"><?php echo esc_html( $agent->display_name ); ?></a></strong>
									<span style="display:block; font-size:12px; color:#646970;"><?php echo esc_html( $agent->user_login ); ?></span>
								</td>
								<td class="email column-email">
									<a href="mailto:<?php echo esc_attr( $agent->user_email ); ?>"><?php echo esc_html( $agent->user_email ); ?></a>
								</td>
								<td class="phone column-phone">
									<?php 
									if ( $phone ) {
										echo esc_html( $phone );
									}
									if ( $whatsapp ) {
										echo ( $phone ? ' / ' : '' ) . esc_html( $whatsapp ) . ' <span style="color: #1e7e34; font-weight: 600;">(WA)</span>';
									}
									if ( ! $phone && ! $whatsapp ) {
										echo '-';
									}
									?>
								</td>
								<td class="posts column-posts">
									<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=property&author=' . $agent->ID ) ); ?>">
										<?php echo esc_html( $properties_count ); ?>
									</a>
								</td>
								<td class="registered column-registered">
									<?php echo esc_html( $registered ); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="5"><?php esc_html_e( 'No agents found.', 'custom-real-estate-manager' ); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	private static $old_statuses = array();

	/**
	 * Capture old property status before ACF saves new values.
	 */
	public function capture_old_property_status( $post_id ) {
		if ( get_post_type( $post_id ) !== 'property' ) {
			return;
		}
		self::$old_statuses[$post_id] = get_post_meta( $post_id, 'approval_status', true );
	}

	/**
	 * Create a notification.
	 *
	 * @param string $title
	 * @param string $message
	 * @param int    $recipient_id
	 * @param string $type
	 * @param int    $property_id
	 * @param array  $meta
	 * @return int|WP_Error
	 */
	public static function create_notification( $title, $message, $recipient_id, $type, $property_id, $meta = array() ) {
		$post_id = wp_insert_post( array(
			'post_title'   => $title,
			'post_content' => $message,
			'post_status'  => 'publish',
			'post_type'    => 'rem_notification',
			'post_author'  => $recipient_id,
		) );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, 'notification_type', $type );
			update_post_meta( $post_id, 'property_id', $property_id );
			update_post_meta( $post_id, 'is_read', '0' );
			update_post_meta( $post_id, 'popup_shown', '0' );
			if ( ! empty( $meta ) ) {
				foreach ( $meta as $key => $val ) {
					update_post_meta( $post_id, $key, $val );
				}
			}
		}
		return $post_id;
	}

	/**
	 * Check status changes and create notifications.
	 */
	public function check_property_status_change( $post_id ) {
		if ( get_post_type( $post_id ) !== 'property' ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$old_status = isset( self::$old_statuses[$post_id] ) ? self::$old_statuses[$post_id] : '';
		$new_status = get_post_meta( $post_id, 'approval_status', true );

		$author_id = get_post_field( 'post_author', $post_id );
		$author_user = get_userdata( $author_id );
		$is_agent = $author_user && in_array( 'agent', (array) $author_user->roles );

		if ( empty( $new_status ) ) {
			$new_status = $is_agent ? 'pending' : 'approved';
		}

		if ( $old_status !== $new_status ) {
			$title = get_the_title( $post_id );
			$prop_id = get_post_meta( $post_id, 'property_id', true );
			if ( empty( $prop_id ) ) {
				$prop_id = 'PROP-' . $post_id;
			}
			$agent_name = $author_user ? $author_user->display_name : __( 'Administrator', 'custom-real-estate-manager' );
			$date_str = current_time( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

			if ( 'pending' === $new_status && $is_agent ) {
				self::create_notification(
					__( 'New Property Submitted for Approval', 'custom-real-estate-manager' ),
					sprintf( __( 'Property "%s" has been submitted by %s.', 'custom-real-estate-manager' ), $title, $agent_name ),
					0, // Super Admin
					'property_submitted',
					$post_id,
					array(
						'agent_name'     => $agent_name,
						'submitted_date' => $date_str,
					)
				);
			} elseif ( 'approved' === $new_status && $is_agent ) {
				self::create_notification(
					__( 'Your Property Has Been Approved', 'custom-real-estate-manager' ),
					sprintf( __( 'Your property "%s" has been approved.', 'custom-real-estate-manager' ), $title ),
					$author_id,
					'property_approved',
					$post_id,
					array(
						'approval_date' => $date_str,
					)
				);

				// Unified Email Notification
				if ( $author_user ) {
					$to = $author_user->user_email;
					$subject = sprintf( __( '[REM] Property Approved: %s', 'custom-real-estate-manager' ), $title );
					$message = sprintf(
						"Hello %s,\n\nCongratulations! Your property listing \"%s\" (ID: %s) has been approved by the Administrator and is now live on our website.\n\nYou can view it here: %s\n\nBest regards,\nB2B Real Estate System",
						$agent_name,
						$title,
						$prop_id,
						get_permalink( $post_id )
					);
					wp_mail( $to, $subject, $message );
				}
			} elseif ( 'rejected' === $new_status && $is_agent ) {
				$reason = get_post_meta( $post_id, '_rejection_reason', true );
				if ( empty( $reason ) ) {
					$reason = __( 'No reason provided.', 'custom-real-estate-manager' );
				}
				self::create_notification(
					__( 'Your Property Submission Was Rejected', 'custom-real-estate-manager' ),
					sprintf( __( 'Your property "%s" was rejected.', 'custom-real-estate-manager' ), $title ),
					$author_id,
					'property_rejected',
					$post_id,
					array(
						'rejection_reason' => $reason,
					)
				);

				// Unified Email Notification
				if ( $author_user ) {
					$to = $author_user->user_email;
					$subject = sprintf( __( '[REM] Property Rejected: %s', 'custom-real-estate-manager' ), $title );
					$message = sprintf(
						"Hello %s,\n\nYour property listing \"%s\" (ID: %s) has been rejected by the Administrator.\n\nReason: %s\n\nPlease log in to your dashboard to edit and re-submit it.\n\nBest regards,\nB2B Real Estate System",
						$agent_name,
						$title,
						$prop_id,
						$reason
					);
					wp_mail( $to, $subject, $message );
				}
			}
		}
	}

	/**
	 * Add custom Notification Bell to WordPress Admin Bar.
	 */
	public function add_notification_bell_to_admin_bar( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$unread_count = count( get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => 0, // Super Admin
			'meta_query'     => array(
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
			),
		) ) );

		$badge = '';
		$badge_class = 'rem-admin-bell-badge';
		if ( $unread_count > 0 ) {
			$badge = ' <span class="' . $badge_class . '">' . $unread_count . '</span>';
		} else {
			$badge = ' <span class="' . $badge_class . '" style="display:none;">0</span>';
		}

		$wp_admin_bar->add_node( array(
			'id'    => 'rem-admin-notifications',
			'title' => '<span class="ab-icon dashicons-bell"></span><span class="ab-label">' . esc_html__( 'Notifications', 'custom-real-estate-manager' ) . $badge . '</span>',
			'href'  => '#',
			'meta'  => array(
				'class' => 'rem-admin-notifications-bell-node',
			),
		) );
	}

	/**
	 * Render dropdown and modal wraps in admin footer.
	 */
	public function render_admin_notification_elements() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div id="rem-admin-notifications-dropdown" class="rem-notifications-dropdown" style="display:none; position:fixed; top:32px; right:20px; z-index:99999; background:#fff; border:1px solid #ccc; width:320px; max-height:400px; overflow-y:auto; box-shadow:0 4px 12px rgba(0,0,0,0.15); border-radius:8px; font-family:'Outfit', -apple-system,BlinkMacSystemFont,sans-serif;">
			<div class="rem-notifications-header" style="padding:12px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
				<h3 style="margin:0; font-size:14px; font-weight:600; color:#1d2327;"><?php esc_html_e( 'Notifications', 'custom-real-estate-manager' ); ?></h3>
				<button type="button" class="rem-mark-all-read-btn" style="background:none; border:none; color:#007cba; cursor:pointer; font-size:11px; padding:0; font-family:inherit; font-weight: 500;"><?php esc_html_e( 'Mark all as read', 'custom-real-estate-manager' ); ?></button>
			</div>
			<ul class="rem-notifications-list" style="list-style:none; margin:0; padding:0;">
				<li class="rem-no-notifications" style="padding:20px; text-align:center; color:#666; font-size:13px;"><?php esc_html_e( 'No new notifications', 'custom-real-estate-manager' ); ?></li>
			</ul>
		</div>
		<div id="rem-notification-modal" class="rem-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background:rgba(0,0,0,0.6); backdrop-filter: blur(4px); align-items:center; justify-content:center;">
			<div class="rem-modal-content" style="background:#fff; padding:28px; border-radius:12px; width:90%; max-width:480px; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.2); font-family:'Outfit', -apple-system,BlinkMacSystemFont,sans-serif; transition: transform 0.3s ease-out; transform: scale(0.9);">
				<span class="rem-modal-close" style="position:absolute; top:12px; right:16px; font-size:24px; font-weight:bold; color:#aaa; cursor:pointer; line-height:1;">&times;</span>
				<div class="rem-modal-body"></div>
			</div>
		</div>
		<?php
	}
}
