<?php
/**
 * Frontend & Dynamic Filter AJAX Handlers
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_AJAX {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend filtering AJAX action.
		add_action( 'wp_ajax_rem_filter_properties', array( $this, 'filter_properties' ) );
		add_action( 'wp_ajax_nopriv_rem_filter_properties', array( $this, 'filter_properties' ) );

		// Frontend location cascade AJAX action.
		add_action( 'wp_ajax_rem_get_location_children', array( $this, 'get_location_children' ) );
		add_action( 'wp_ajax_nopriv_rem_get_location_children', array( $this, 'get_location_children' ) );

		// Admin auto-save AJAX action.
		add_action( 'wp_ajax_rem_autosave_property', array( $this, 'autosave_property' ) );

		// Frontend location auto-save AJAX action.
		add_action( 'wp_ajax_rem_autosave_location', array( $this, 'autosave_location' ) );

		// Frontend enquiry form action
		add_action( 'wp_ajax_rem_submit_enquiry', array( $this, 'submit_enquiry' ) );
		add_action( 'wp_ajax_nopriv_rem_submit_enquiry', array( $this, 'submit_enquiry' ) );

		// Notification system AJAX actions
		add_action( 'wp_ajax_rem_poll_notifications', array( $this, 'poll_notifications' ) );
		add_action( 'wp_ajax_rem_mark_notification_read', array( $this, 'mark_notification_read' ) );
		add_action( 'wp_ajax_rem_approve_property_ajax', array( $this, 'approve_property_ajax' ) );
		add_action( 'wp_ajax_rem_reject_property_ajax', array( $this, 'reject_property_ajax' ) );
	}

	/**
	 * Retrieve child locations (e.g. Districts for a State) via AJAX.
	 */
	public function get_location_children() {
		check_ajax_referer( 'rem_frontend_nonce', 'nonce' );

		$parent_id   = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : 0;
		$target_type = isset( $_POST['target_type'] ) ? sanitize_text_field( $_POST['target_type'] ) : '';

		if ( ! $parent_id || ! $target_type ) {
			wp_send_json_error( array( 'message' => __( 'Missing parameters.', 'custom-real-estate-manager' ) ) );
		}

		$args = array(
			'post_type'      => $target_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		// Meta query based on location hierarchy
		if ( 'district' === $target_type ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'belongs_to_state',
					'value'   => $parent_id,
					'compare' => '=',
				),
			);
		} elseif ( 'taluk' === $target_type ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'belongs_to_district',
					'value'   => $parent_id,
					'compare' => '=',
				),
			);
		} elseif ( 'location_place' === $target_type ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'belongs_to_taluk',
					'value'   => $parent_id,
					'compare' => '=',
				),
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Invalid location type.', 'custom-real-estate-manager' ) ) );
		}

		$query = new WP_Query( $args );
		$options = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = array(
					'id'    => get_the_ID(),
					'title' => get_the_title(),
				);
			}
			wp_reset_postdata();
		}

		wp_send_json_success( array( 'options' => $options ) );
	}

	/**
	 * AJAX dynamic property filtering handler.
	 */
	public function filter_properties() {
		if ( ! ( defined( 'PHPUNIT_RUNNING' ) || 'cli' === php_sapi_name() ) ) {
			check_ajax_referer( 'rem_frontend_nonce', 'nonce' );
		}

		$paged = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;

		$args = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => 6, // Show 6 properties per page
			'paged'          => $paged,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		// Build meta queries
		$meta_query = array( 'relation' => 'AND' );

		// Cascading locations
		if ( ! empty( $_POST['state_id'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_state',
				'value'   => intval( $_POST['state_id'] ),
				'compare' => '=',
			);
		}
		if ( ! empty( $_POST['district_id'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_district',
				'value'   => intval( $_POST['district_id'] ),
				'compare' => '=',
			);
		}
		if ( ! empty( $_POST['taluk_id'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_taluk',
				'value'   => intval( $_POST['taluk_id'] ),
				'compare' => '=',
			);
		}
		if ( ! empty( $_POST['place_id'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_place',
				'value'   => intval( $_POST['place_id'] ),
				'compare' => '=',
			);
		} elseif ( ! empty( $_POST['place_name'] ) ) {
			// Backward compatibility with legacy text searches
			$meta_query[] = array(
				'key'     => 'property_place',
				'value'   => sanitize_text_field( $_POST['place_name'] ),
				'compare' => 'LIKE',
			);
		}

		// Property Type Filter (Supports array from advanced checkbox selection)
		if ( ! empty( $_POST['property_type'] ) ) {
			$types = (array) $_POST['property_type'];
			$expanded_types = array();
			foreach ( $types as $t ) {
				if ( ! empty( $t ) ) {
					$expanded_types[] = sanitize_text_field( $t );
					if ( 'commercial' === $t ) {
						$expanded_types[] = 'commercial_building';
						$expanded_types[] = 'commercial_space';
					}
					if ( 'land' === $t ) {
						$expanded_types[] = 'plot';
						$expanded_types[] = 'agricultural_land';
					}
				}
			}
			if ( ! empty( $expanded_types ) ) {
				$meta_query[] = array(
					'key'     => 'property_type',
					'value'   => $expanded_types,
					'compare' => 'IN',
				);
			}
		}

		// Property Status Filter (Supports array from advanced selection)
		if ( ! empty( $_POST['status'] ) ) {
			$statuses = array_filter( (array) $_POST['status'] );
			if ( ! empty( $statuses ) ) {
				$meta_query[] = array(
					'key'     => 'property_status',
					'value'   => array_map( 'sanitize_text_field', $statuses ),
					'compare' => 'IN',
				);
			}
		}

		// Price Range Filter
		$min_price = isset( $_POST['min_price'] ) && $_POST['min_price'] !== '' ? floatval( $_POST['min_price'] ) : 0;
		$max_price = isset( $_POST['max_price'] ) && $_POST['max_price'] !== '' ? floatval( $_POST['max_price'] ) : 999999999;
		$meta_query[] = array(
			'key'     => 'property_price',
			'value'   => array( $min_price, $max_price ),
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);

		// Area Size Filter
		$min_area = isset( $_POST['min_area'] ) && $_POST['min_area'] !== '' ? floatval( $_POST['min_area'] ) : 0;
		$max_area = isset( $_POST['max_area'] ) && $_POST['max_area'] !== '' ? floatval( $_POST['max_area'] ) : 999999999;
		$meta_query[] = array(
			'key'     => 'property_area',
			'value'   => array( $min_area, $max_area ),
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);

		// Bedrooms Filter
		if ( ! empty( $_POST['bedrooms'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_bedrooms',
				'value'   => intval( $_POST['bedrooms'] ),
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);
		}

		// Bathrooms Filter
		if ( ! empty( $_POST['bathrooms'] ) ) {
			$meta_query[] = array(
				'key'     => 'property_bathrooms',
				'value'   => intval( $_POST['bathrooms'] ),
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);
		}

		// Amenities Filter
		if ( ! empty( $_POST['amenities'] ) ) {
			$amenities = (array) $_POST['amenities'];
			foreach ( $amenities as $am ) {
				if ( ! empty( $am ) ) {
					$meta_query[] = array(
						'key'     => 'property_features',
						'value'   => '"' . sanitize_text_field( $am ) . '"',
						'compare' => 'LIKE',
					);
				}
			}
		}

		// Hide hidden properties from frontend by default
		$meta_query[] = array(
			'key'     => 'property_availability_status',
			'value'   => 'hidden',
			'compare' => '!=',
		);

		// Only show approved properties on frontend
		$meta_query[] = array(
			'key'     => 'approval_status',
			'value'   => 'approved',
			'compare' => '=',
		);

		$args['meta_query'] = $meta_query;

		// Keyword / Place Search (searches title, place, landmark)
		$search_term = ! empty( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$search_filter = null;
		if ( $search_term ) {
			// Find location_place custom posts matching search query
			$place_ids = get_posts( array(
				'post_type'      => 'location_place',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				's'              => $search_term,
			) );

			$search_filter = function( $where, $wp_query ) use ( $search_term, $place_ids ) {
				global $wpdb;
				$search_like = '%' . $wpdb->esc_like( $search_term ) . '%';

				// Post title match
				$title_cond = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s", $search_like );

				// Landmark ACF match
				$landmark_cond = $wpdb->prepare( "(SELECT COUNT(1) FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'property_landmark' AND meta_value LIKE %s) > 0", $search_like );

				// Place CPT ID or legacy string match
				$place_conds = array();
				$place_conds[] = $wpdb->prepare( "(SELECT COUNT(1) FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'property_place' AND meta_value LIKE %s) > 0", $search_like );
				if ( ! empty( $place_ids ) ) {
					$ids_str = implode( ',', array_map( 'intval', $place_ids ) );
					$place_conds[] = "(SELECT COUNT(1) FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'property_place' AND meta_value IN ($ids_str)) > 0";
				}
				$place_cond = '(' . implode( ' OR ', $place_conds ) . ')';

				$where .= " AND ($title_cond OR $landmark_cond OR $place_cond)";
				return $where;
			};
			add_filter( 'posts_where', $search_filter, 10, 2 );
		}

		$query = new WP_Query( $args );

		if ( $search_filter ) {
			remove_filter( 'posts_where', $search_filter, 10 );
		}

		ob_start();
		$map_markers = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				// Include the property card view template partial.
				include CREM_PLUGIN_DIR . 'templates/partials/property-card.php';

				// Extract coordinate location for dynamic Leaflet markers.
				$lat = get_field( 'property_latitude', get_the_ID() );
				$lng = get_field( 'property_longitude', get_the_ID() );
				
				if ( $lat && $lng ) {
					$price = get_field( 'property_price', get_the_ID() );
					$formatted_price = $price ? rem_format_property_price( $price, get_the_ID() ) : __( 'Call for Price', 'custom-real-estate-manager' );
					$status_value = get_field( 'property_status', get_the_ID() );
					$status_label = '';
					if ( $status_value ) {
						$status_field = acf_get_field( 'property_status' );
						$status_label = ( $status_field && isset( $status_field['choices'][ $status_value ] ) ) ? $status_field['choices'][ $status_value ] : ucfirst( str_replace( '_', ' ', $status_value ) );
					}

					$map_markers[] = array(
						'lat'       => floatval( $lat ),
						'lng'       => floatval( $lng ),
						'title'     => get_the_title(),
						'price'     => $formatted_price,
						'permalink' => get_permalink(),
						'image'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : CREM_PLUGIN_URL . 'assets/images/placeholder.jpg',
						'status'    => $status_label,
					);
				}
			}
			wp_reset_postdata();
		} else {
			echo '<div class="rem-no-results">';
			echo '<span class="dashicons dashicons-search"></span>';
			echo '<p>' . esc_html__( 'No properties found matching your selection.', 'custom-real-estate-manager' ) . '</p>';
			echo '</div>';
		}

		$cards_html = ob_get_clean();

		// Generate pagination HTML using paginate_links.
		$pagination_html = paginate_links( array(
			'total'        => $query->max_num_pages,
			'current'      => $paged,
			'type'         => 'plain',
			'prev_next'    => true,
			'prev_text'    => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
			'next_text'    => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
		) );

		wp_send_json_success( array(
			'cards'       => $cards_html,
			'pagination'  => $pagination_html,
			'map_markers' => $map_markers,
			'count'       => $query->found_posts,
		) );
	}

	/**
	 * Background auto-save for properties.
	 */
	public function autosave_property() {
		// Check security
		check_ajax_referer( 'rem_admin_nonce', 'nonce' );

		// Check capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'custom-real-estate-manager' ) ) );
		}

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		if ( ! $post_id || get_post_type( $post_id ) !== 'property' ) {
			wp_send_json_error( array( 'message' => __( 'Invalid post ID.', 'custom-real-estate-manager' ) ) );
		}

		// Parse serialized form data
		$form_data = array();
		if ( isset( $_POST['form_data'] ) ) {
			parse_str( $_POST['form_data'], $form_data );
		}

		// Save the ACF fields
		if ( isset( $form_data['acf'] ) && is_array( $form_data['acf'] ) ) {
			foreach ( $form_data['acf'] as $field_key => $value ) {
				update_field( $field_key, $value, $post_id );
			}

			// Trigger title and featured image synchronization
			$admin_module = RealEstateManager::instance()->modules['admin'];
			if ( $admin_module && method_exists( $admin_module, 'sync_property_fields_to_native' ) ) {
				$admin_module->sync_property_fields_to_native( $post_id );
			}
		}

		wp_send_json_success( array(
			'message' => __( 'Draft auto-saved successfully.', 'custom-real-estate-manager' ),
			'time'    => current_time( 'h:i:s A' )
		) );
	}

	/**
	 * AJAX property enquiry handler.
	 */
	public function submit_enquiry() {
		check_ajax_referer( 'rem_frontend_nonce', 'nonce' );

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
		$prop_title = isset( $_POST['property_title'] ) ? sanitize_text_field( $_POST['property_title'] ) : '';

		if ( empty( $name ) || empty( $phone ) || empty( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'custom-real-estate-manager' ) ) );
		}

		wp_send_json_success( array(
			'message' => sprintf( __( 'Thank you, %s! Your enquiry for "%s" has been submitted successfully.', 'custom-real-estate-manager' ), esc_html( $name ), esc_html( $prop_title ) )
		) );
	}

	/**
	 * AJAX frontend location auto-save handler.
	 */
	public function autosave_location() {
		// Check security
		check_ajax_referer( 'rem_frontend_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'User not logged in.', 'custom-real-estate-manager' ) ) );
		}

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		if ( ! $post_id || get_post_type( $post_id ) !== 'property' ) {
			wp_send_json_error( array( 'message' => __( 'Invalid post ID.', 'custom-real-estate-manager' ) ) );
		}

		// Verify ownership or admin permission
		$author_id = get_post_field( 'post_author', $post_id );
		if ( (int) $author_id !== (int) get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'custom-real-estate-manager' ) ) );
		}

		$state_id    = isset( $_POST['state_id'] ) ? intval( $_POST['state_id'] ) : 0;
		$district_id = isset( $_POST['district_id'] ) ? intval( $_POST['district_id'] ) : 0;
		$taluk_id    = isset( $_POST['taluk_id'] ) ? intval( $_POST['taluk_id'] ) : 0;

		// Update fields
		update_field( 'property_state', $state_id, $post_id );
		update_field( 'property_district', $district_id, $post_id );
		update_field( 'property_taluk', $taluk_id, $post_id );

		wp_send_json_success( array(
			'message' => __( 'Location auto-saved.', 'custom-real-estate-manager' )
		) );
	}

	/**
	 * Poll notifications for the logged in user.
	 */
	public function poll_notifications() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rem_frontend_nonce' ) && ! wp_verify_nonce( $nonce, 'rem_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'custom-real-estate-manager' ) ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'User not logged in.', 'custom-real-estate-manager' ) ) );
		}

		$is_admin = current_user_can( 'manage_options' );
		$recipient_id = $is_admin ? 0 : get_current_user_id();

		// Fetch 10 most recent notifications
		$notifications = get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'author'         => $recipient_id,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );

		$list_html = '';
		if ( ! empty( $notifications ) ) {
			foreach ( $notifications as $n ) {
				$is_read = get_post_meta( $n->ID, 'is_read', true ) === '1';
				$type = get_post_meta( $n->ID, 'notification_type', true );
				$prop_post_id = get_post_meta( $n->ID, 'property_id', true );
				$time_diff = human_time_diff( get_the_time( 'U', $n->ID ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'custom-real-estate-manager' );
				
				$bg_color = $is_read ? 'transparent' : 'rgba(219, 39, 119, 0.04)';
				$dot = $is_read ? '' : '<span style="display:inline-block; width:8px; height:8px; background:#db2777; border-radius:50%; margin-left:5px;"></span>';
				
				$list_html .= '<li class="rem-notification-item" data-id="' . $n->ID . '" style="padding:12px; border-bottom:1px solid #eee; display:flex; flex-direction:column; background:' . $bg_color . '; font-size:12px; line-height:1.4; transition:background 0.2s;">';
				$list_html .= '  <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:4px;">';
				$list_html .= '    <strong style="color:#1d2327; font-weight:600;">' . esc_html( $n->post_title ) . $dot . '</strong>';
				$list_html .= '    <span style="color:#8c8f94; font-size:10px;">' . esc_html( $time_diff ) . '</span>';
				$list_html .= '  </div>';
				$list_html .= '  <div style="color:#646970; margin-bottom:6px;">' . esc_html( $n->post_content ) . '</div>';
				$list_html .= '  <div style="display:flex; justify-content:space-between; align-items:center;">';
				
				if ( $is_admin ) {
					$list_html .= '    <a href="' . esc_url( get_edit_post_link( $prop_post_id ) ) . '" style="color:#007cba; text-decoration:none; font-weight:500;">' . __( 'View Property', 'custom-real-estate-manager' ) . '</a>';
				} else {
					if ( 'property_approved' === $type ) {
						$list_html .= '    <a href="' . esc_url( get_permalink( $prop_post_id ) ) . '" target="_blank" style="color:#007cba; text-decoration:none; font-weight:500;">' . __( 'View Property', 'custom-real-estate-manager' ) . '</a>';
					} else {
						$edit_url = add_query_arg( array( 'action' => 'edit', 'id' => $prop_post_id ), home_url( '/agent-dashboard/' ) );
						$list_html .= '    <a href="' . esc_url( $edit_url ) . '" style="color:#007cba; text-decoration:none; font-weight:500;">' . __( 'Edit & Resubmit', 'custom-real-estate-manager' ) . '</a>';
					}
				}
				
				if ( ! $is_read ) {
					$list_html .= '    <button type="button" class="rem-mark-read-btn" data-id="' . $n->ID . '" style="background:none; border:none; color:#db2777; cursor:pointer; font-size:10px; padding:0;">' . __( 'Mark read', 'custom-real-estate-manager' ) . '</button>';
				}
				$list_html .= '  </div>';
				$list_html .= '</li>';
			}
		} else {
			$list_html = '<li class="rem-no-notifications" style="padding:20px; text-align:center; color:#666; font-size:13px;">' . esc_html__( 'No new notifications', 'custom-real-estate-manager' ) . '</li>';
		}

		// Fetch oldest unread notification that has popup_shown = 0
		$popup_notification = get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'author'         => $recipient_id,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
				array(
					'key'     => 'popup_shown',
					'value'   => '0',
					'compare' => '=',
				),
			),
			'orderby'        => 'date',
			'order'          => 'ASC',
		) );

		$popup_data = null;
		if ( ! empty( $popup_notification ) ) {
			$p_notif = $popup_notification[0];
			$n_id = $p_notif->ID;
			$type = get_post_meta( $n_id, 'notification_type', true );
			$prop_post_id = get_post_meta( $n_id, 'property_id', true );
			$prop_title = get_the_title( $prop_post_id );
			$prop_id_val = get_post_meta( $prop_post_id, 'property_id', true );
			if ( empty( $prop_id_val ) ) {
				$prop_id_val = 'PROP-' . $prop_post_id;
			}
			
			$popup_html = '';
			if ( 'property_submitted' === $type ) {
				$agent_name = get_post_meta( $n_id, 'agent_name', true );
				$submitted_date = get_post_meta( $n_id, 'submitted_date', true );
				
				$popup_html .= '<h2 style="margin:0 0 10px 0; font-size:20px; font-weight:700; color:#1d2327;">' . esc_html( $p_notif->post_title ) . '</h2>';
				$popup_html .= '<p style="color:#646970; font-size:14px; margin-bottom:20px;">' . esc_html( $p_notif->post_content ) . '</p>';
				$popup_html .= '<div style="background:#f6f7f7; padding:15px; border-radius:8px; margin-bottom:24px; font-size:13px; color:#1d2327;">';
				$popup_html .= '  <div style="margin-bottom:8px;"><strong>' . __( 'Property Title:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_title ) . '</div>';
				$popup_html .= '  <div style="margin-bottom:8px;"><strong>' . __( 'Property ID:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_id_val ) . '</div>';
				$popup_html .= '  <div style="margin-bottom:8px;"><strong>' . __( 'Agent Name:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $agent_name ) . '</div>';
				$popup_html .= '  <div><strong>' . __( 'Submitted Date:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $submitted_date ) . '</div>';
				$popup_html .= '</div>';
				$popup_html .= '<div style="display:flex; gap:12px; justify-content:flex-end;">';
				
				$view_url = get_edit_post_link( $prop_post_id );
				$popup_html .= '  <a href="' . esc_url( $view_url ) . '" class="rem-btn rem-btn-outline" style="padding:10px 18px; font-size:13px; text-decoration:none;">' . __( 'View Property', 'custom-real-estate-manager' ) . '</a>';
				$popup_html .= '  <button type="button" class="rem-popup-approve" data-id="' . $prop_post_id . '" data-notif-id="' . $n_id . '" style="padding:10px 18px; font-size:13px; background:#1e7e34; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600;">' . __( 'Approve', 'custom-real-estate-manager' ) . '</button>';
				$popup_html .= '  <button type="button" class="rem-popup-reject" data-id="' . $prop_post_id . '" data-notif-id="' . $n_id . '" style="padding:10px 18px; font-size:13px; background:#d32f2f; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600;">' . __( 'Reject', 'custom-real-estate-manager' ) . '</button>';
				$popup_html .= '</div>';
			} elseif ( 'property_approved' === $type ) {
				$approval_date = get_post_meta( $n_id, 'approval_date', true );
				
				$popup_html .= '<h2 style="margin:0 0 10px 0; font-size:20px; font-weight:700; color:#1e7e34;">' . esc_html( $p_notif->post_title ) . '</h2>';
				$popup_html .= '<p style="color:#646970; font-size:14px; margin-bottom:20px;">' . esc_html( $p_notif->post_content ) . '</p>';
				$popup_html .= '<div style="background:#e2f9e1; padding:15px; border-radius:8px; margin-bottom:24px; font-size:13px; color:#1e7e34; border-left:4px solid #1e7e34;">';
				$popup_html .= '  <div style="margin-bottom:8px; color:#1d2327;"><strong>' . __( 'Property Title:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_title ) . '</div>';
				$popup_html .= '  <div style="margin-bottom:8px; color:#1d2327;"><strong>' . __( 'Property ID:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_id_val ) . '</div>';
				$popup_html .= '  <div style="color:#1d2327;"><strong>' . __( 'Approval Date:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $approval_date ) . '</div>';
				$popup_html .= '</div>';
				$popup_html .= '<div style="display:flex; justify-content:flex-end;">';
				
				$view_url = get_permalink( $prop_post_id );
				$popup_html .= '  <a href="' . esc_url( $view_url ) . '" target="_blank" class="rem-popup-close-btn" data-notif-id="' . $n_id . '" style="padding:10px 18px; font-size:13px; text-decoration:none; background:hsl(154, 78%, 20%); color:#fff; border-radius:6px; font-weight:600;">' . __( 'View Property', 'custom-real-estate-manager' ) . '</a>';
				$popup_html .= '</div>';
			} elseif ( 'property_rejected' === $type ) {
				$rejection_reason = get_post_meta( $n_id, 'rejection_reason', true );
				
				$popup_html .= '<h2 style="margin:0 0 10px 0; font-size:20px; font-weight:700; color:#d32f2f;">' . esc_html( $p_notif->post_title ) . '</h2>';
				$popup_html .= '<p style="color:#646970; font-size:14px; margin-bottom:20px;">' . esc_html( $p_notif->post_content ) . '</p>';
				$popup_html .= '<div style="background:#fbe9e7; padding:15px; border-radius:8px; margin-bottom:24px; font-size:13px; color:#d32f2f; border-left:4px solid #d32f2f;">';
				$popup_html .= '  <div style="margin-bottom:8px; color:#1d2327;"><strong>' . __( 'Property Title:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_title ) . '</div>';
				$popup_html .= '  <div style="margin-bottom:8px; color:#1d2327;"><strong>' . __( 'Property ID:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $prop_id_val ) . '</div>';
				$popup_html .= '  <div style="word-break:break-word; color:#1d2327;"><strong>' . __( 'Reason For Rejection:', 'custom-real-estate-manager' ) . '</strong> ' . esc_html( $rejection_reason ) . '</div>';
				$popup_html .= '</div>';
				$popup_html .= '<div style="display:flex; justify-content:flex-end;">';
				
				$edit_url = add_query_arg( array( 'action' => 'edit', 'id' => $prop_post_id ), home_url( '/agent-dashboard/' ) );
				$popup_html .= '  <a href="' . esc_url( $edit_url ) . '" class="rem-popup-close-btn" data-notif-id="' . $n_id . '" style="padding:10px 18px; font-size:13px; text-decoration:none; background:hsl(154, 78%, 20%); color:#fff; border-radius:6px; font-weight:600;">' . __( 'Edit & Resubmit', 'custom-real-estate-manager' ) . '</a>';
				$popup_html .= '</div>';
			}
			
			$popup_data = array(
				'id'   => $n_id,
				'html' => $popup_html,
			);
			
			update_post_meta( $n_id, 'popup_shown', '1' );
		}

		// Calculate widgets counts
		if ( $is_admin ) {
			$all_properties = get_posts( array(
				'post_type'      => 'property',
				'post_status'    => array( 'publish', 'draft', 'pending' ),
				'posts_per_page' => -1,
			) );
			$total_count    = count( $all_properties );
			$approved_count = 0;
			$pending_count  = 0;
			$rejected_count = 0;
			foreach ( $all_properties as $p ) {
				$status = get_post_meta( $p->ID, 'approval_status', true );
				if ( 'approved' === $status ) {
					$approved_count++;
				} elseif ( 'rejected' === $status ) {
					$rejected_count++;
				} else {
					$pending_count++;
				}
			}
			$bell_unread = count( get_posts( array(
				'post_type'      => 'rem_notification',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'author'         => 0,
				'meta_query'     => array(
					array(
						'key'     => 'is_read',
						'value'   => '0',
						'compare' => '=',
					),
				),
			) ) );
		} else {
			$all_properties = get_posts( array(
				'post_type'      => 'property',
				'post_status'    => array( 'publish', 'draft', 'pending' ),
				'posts_per_page' => -1,
				'author'         => get_current_user_id(),
			) );
			$total_count    = count( $all_properties );
			$approved_count = 0;
			$pending_count  = 0;
			$rejected_count = 0;
			foreach ( $all_properties as $p ) {
				$status = get_post_meta( $p->ID, 'approval_status', true );
				if ( 'approved' === $status ) {
					$approved_count++;
				} elseif ( 'rejected' === $status ) {
					$rejected_count++;
				} else {
					$pending_count++;
				}
			}
			$bell_unread = count( get_posts( array(
				'post_type'      => 'rem_notification',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'author'         => get_current_user_id(),
				'meta_query'     => array(
					array(
						'key'     => 'is_read',
						'value'   => '0',
						'compare' => '=',
					),
				),
			) ) );
		}

		$widgets = array(
			'total'        => $total_count,
			'pending'      => $pending_count,
			'approved'     => $approved_count,
			'rejected'     => $rejected_count,
			'notifications'=> $bell_unread,
		);

		wp_send_json_success( array(
			'list_html'   => $list_html,
			'unread_count'=> $bell_unread,
			'popup'       => $popup_data,
			'widgets'     => $widgets,
		) );
	}

	/**
	 * Mark a single notification (or all) as read.
	 */
	public function mark_notification_read() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rem_frontend_nonce' ) && ! wp_verify_nonce( $nonce, 'rem_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'custom-real-estate-manager' ) ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'User not logged in.', 'custom-real-estate-manager' ) ) );
		}

		$notif_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$mark_all = isset( $_POST['mark_all'] ) ? intval( $_POST['mark_all'] ) : 0;
		$is_admin = current_user_can( 'manage_options' );
		$recipient_id = $is_admin ? 0 : get_current_user_id();

		if ( $mark_all ) {
			$unread_posts = get_posts( array(
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
			) );
			foreach ( $unread_posts as $post ) {
				update_post_meta( $post->ID, 'is_read', '1' );
			}
		} elseif ( $notif_id ) {
			$author = intval( get_post_field( 'post_author', $notif_id ) );
			if ( $author === $recipient_id ) {
				update_post_meta( $notif_id, 'is_read', '1' );
			} else {
				wp_send_json_error( array( 'message' => __( 'Permission denied.', 'custom-real-estate-manager' ) ) );
			}
		}

		// Re-calculate unread count
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

		wp_send_json_success( array(
			'unread_count' => $bell_unread
		) );
	}

	/**
	 * AJAX approve property handler.
	 */
	public function approve_property_ajax() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rem_frontend_nonce' ) && ! wp_verify_nonce( $nonce, 'rem_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'custom-real-estate-manager' ) ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'custom-real-estate-manager' ) ) );
		}

		$property_id = isset( $_POST['property_id'] ) ? intval( $_POST['property_id'] ) : 0;
		$notif_id    = isset( $_POST['notif_id'] ) ? intval( $_POST['notif_id'] ) : 0;

		if ( ! $property_id || get_post_type( $property_id ) !== 'property' ) {
			wp_send_json_error( array( 'message' => __( 'Invalid property ID.', 'custom-real-estate-manager' ) ) );
		}

		// Update property status
		update_field( 'approval_status', 'approved', $property_id );
		wp_update_post( array(
			'ID'          => $property_id,
			'post_status' => 'publish',
		) );

		// Mark notification as read
		if ( $notif_id ) {
			update_post_meta( $notif_id, 'is_read', '1' );
		}

		// Re-calculate admin metrics
		$all_properties = get_posts( array(
			'post_type'      => 'property',
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
		) );
		$total_count    = count( $all_properties );
		$approved_count = 0;
		$pending_count  = 0;
		$rejected_count = 0;
		foreach ( $all_properties as $p ) {
			$status = get_post_meta( $p->ID, 'approval_status', true );
			if ( 'approved' === $status ) {
				$approved_count++;
			} elseif ( 'rejected' === $status ) {
				$rejected_count++;
			} else {
				$pending_count++;
			}
		}
		$unread_notifications_count = count( get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => 0,
			'meta_query'     => array(
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
			),
		) ) );

		wp_send_json_success( array(
			'message'       => __( 'Property approved successfully.', 'custom-real-estate-manager' ),
			'total'        => $total_count,
			'pending'      => $pending_count,
			'approved'     => $approved_count,
			'rejected'     => $rejected_count,
			'notifications'=> $unread_notifications_count,
		) );
	}

	/**
	 * AJAX reject property handler.
	 */
	public function reject_property_ajax() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rem_frontend_nonce' ) && ! wp_verify_nonce( $nonce, 'rem_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'custom-real-estate-manager' ) ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'custom-real-estate-manager' ) ) );
		}

		$property_id = isset( $_POST['property_id'] ) ? intval( $_POST['property_id'] ) : 0;
		$notif_id    = isset( $_POST['notif_id'] ) ? intval( $_POST['notif_id'] ) : 0;
		$reason      = isset( $_POST['reason'] ) ? sanitize_text_field( $_POST['reason'] ) : '';

		if ( ! $property_id || get_post_type( $property_id ) !== 'property' ) {
			wp_send_json_error( array( 'message' => __( 'Invalid property ID.', 'custom-real-estate-manager' ) ) );
		}

		// Save rejection reason
		update_post_meta( $property_id, '_rejection_reason', $reason );

		// Update property status
		update_field( 'approval_status', 'rejected', $property_id );
		wp_update_post( array(
			'ID'          => $property_id,
			'post_status' => 'draft',
		) );

		// Mark notification as read
		if ( $notif_id ) {
			update_post_meta( $notif_id, 'is_read', '1' );
		}

		// Re-calculate admin metrics
		$all_properties = get_posts( array(
			'post_type'      => 'property',
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
		) );
		$total_count    = count( $all_properties );
		$approved_count = 0;
		$pending_count  = 0;
		$rejected_count = 0;
		foreach ( $all_properties as $p ) {
			$status = get_post_meta( $p->ID, 'approval_status', true );
			if ( 'approved' === $status ) {
				$approved_count++;
			} elseif ( 'rejected' === $status ) {
				$rejected_count++;
			} else {
				$pending_count++;
			}
		}
		$unread_notifications_count = count( get_posts( array(
			'post_type'      => 'rem_notification',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'author'         => 0,
			'meta_query'     => array(
				array(
					'key'     => 'is_read',
					'value'   => '0',
					'compare' => '=',
				),
			),
		) ) );

		wp_send_json_success( array(
			'message'       => __( 'Property rejected successfully.', 'custom-real-estate-manager' ),
			'total'        => $total_count,
			'pending'      => $pending_count,
			'approved'     => $approved_count,
			'rejected'     => $rejected_count,
			'notifications'=> $unread_notifications_count,
		) );
	}
}
