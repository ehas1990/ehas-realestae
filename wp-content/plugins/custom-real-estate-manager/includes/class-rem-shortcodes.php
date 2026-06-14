<?php
/**
 * Register Frontend Shortcodes
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Shortcodes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'property_search_filters', array( $this, 'render_filters' ) );
		add_shortcode( 'property_listing', array( $this, 'render_listing' ) );
		add_shortcode( 'rem_advanced_search', array( $this, 'render_advanced_search' ) );
		add_shortcode( 'rem_field', array( $this, 'render_rem_field' ) );
	}

	/**
	 * Render a specific property field with formatting and fallback.
	 */
	public function render_rem_field( $atts ) {
		$atts = shortcode_atts( array(
			'name'     => '',
			'post_id'  => 0,
			'fallback' => '',
			'prefix'   => '',
			'suffix'   => '',
			'format'   => 'text', // text, list, link, button
		), $atts, 'rem_field' );

		$post_id = $atts['post_id'] ? intval( $atts['post_id'] ) : get_the_ID();
		if ( ! $post_id ) {
			return '';
		}

		$field_name = $atts['name'];
		if ( empty( $field_name ) ) {
			return '';
		}

		$output = '';

		switch ( $field_name ) {
			case 'property_title':
				$output = get_the_title( $post_id );
				break;

			case 'property_description':
				$output = get_field( 'property_description', $post_id );
				if ( empty( $output ) ) {
					$output = get_post_field( 'post_content', $post_id );
				}
				$output = wp_kses_post( $output );
				break;

			case 'property_price':
				$price = get_field( 'property_price', $post_id );
				$price_label = get_field( 'property_price_label', $post_id );
				if ( ! empty( $price_label ) ) {
					$output = $price_label;
				} elseif ( $price ) {
					$output = rem_format_property_price( $price, $post_id );
				} else {
					$output = $atts['fallback'] ? $atts['fallback'] : __( 'Call for Price', 'custom-real-estate-manager' );
				}
				break;

			case 'property_type':
				$val = get_field( 'property_type', $post_id );
				if ( $val ) {
					$field = acf_get_field( 'property_type' );
					$output = ( $field && isset( $field['choices'][ $val ] ) ) ? $field['choices'][ $val ] : ucfirst( str_replace( '_', ' ', $val ) );
				}
				break;

			case 'property_status':
				$val = get_field( 'property_status', $post_id );
				if ( $val ) {
					$field = acf_get_field( 'property_status' );
					$output = ( $field && isset( $field['choices'][ $val ] ) ) ? $field['choices'][ $val ] : ucfirst( str_replace( '_', ' ', $val ) );
				}
				break;

			case 'property_state':
			case 'state':
				$state_id = get_field( 'property_state', $post_id );
				if ( ! $state_id ) {
					$district_id = get_field( 'property_district', $post_id );
					if ( ! $district_id ) {
						$taluk_id = get_field( 'property_taluk', $post_id );
						if ( ! $taluk_id ) {
							$place_id = get_field( 'property_place', $post_id );
							if ( $place_id && is_numeric( $place_id ) ) {
								$taluk_id = get_field( 'belongs_to_taluk', $place_id );
							}
						}
						if ( $taluk_id ) {
							$district_id = get_field( 'belongs_to_district', $taluk_id );
						}
					}
					if ( $district_id ) {
						$state_id = get_field( 'belongs_to_state', $district_id );
					}
				}
				if ( $state_id ) {
					$output = get_the_title( $state_id );
				}
				break;

			case 'property_district':
			case 'district':
				$district_id = get_field( 'property_district', $post_id );
				if ( ! $district_id ) {
					$taluk_id = get_field( 'property_taluk', $post_id );
					if ( ! $taluk_id ) {
						$place_id = get_field( 'property_place', $post_id );
						if ( $place_id && is_numeric( $place_id ) ) {
							$taluk_id = get_field( 'belongs_to_taluk', $place_id );
						}
					}
					if ( $taluk_id ) {
						$district_id = get_field( 'belongs_to_district', $taluk_id );
					}
				}
				if ( $district_id ) {
					$output = get_the_title( $district_id );
				}
				break;

			case 'property_taluk':
			case 'taluk':
				$taluk_id = get_field( 'property_taluk', $post_id );
				if ( ! $taluk_id ) {
					$place_id = get_field( 'property_place', $post_id );
					if ( $place_id && is_numeric( $place_id ) ) {
						$taluk_id = get_field( 'belongs_to_taluk', $place_id );
					}
				}
				if ( $taluk_id ) {
					$output = get_the_title( $taluk_id );
				}
				break;

			case 'property_place':
			case 'location':
				$place_id = get_field( 'property_place', $post_id );
				if ( $place_id ) {
					$output = is_numeric( $place_id ) ? get_the_title( $place_id ) : $place_id;
				}
				break;

			case 'property_area':
				$area = get_field( 'property_area', $post_id );
				$unit = get_field( 'property_area_unit', $post_id );
				if ( $area ) {
					$unit_lbl = '';
					if ( $unit ) {
						$field = acf_get_field( 'property_area_unit' );
						$unit_lbl = ( $field && isset( $field['choices'][ $unit ] ) ) ? $field['choices'][ $unit ] : str_replace( '_', ' ', $unit );
					}
					$output = $area . ' ' . $unit_lbl;
				}
				break;

			case 'property_bedrooms':
				$bedrooms = get_field( 'property_bedrooms', $post_id );
				if ( $bedrooms ) {
					$output = $bedrooms . ' BHK';
				}
				break;

			case 'property_bathrooms':
				$bathrooms = get_field( 'property_bathrooms', $post_id );
				if ( $bathrooms ) {
					$output = $bathrooms;
				}
				break;

			case 'property_features':
			case 'amenities':
				$features = get_field( 'property_features', $post_id );
				if ( ! empty( $features ) && is_array( $features ) ) {
					$features_field = acf_get_field( 'property_features' );
					$list_items = array();
					foreach ( $features as $feat_val ) {
						$list_items[] = ( $features_field && isset( $features_field['choices'][ $feat_val ] ) ) ? $features_field['choices'][ $feat_val ] : ucfirst( str_replace( '_', ' ', $feat_val ) );
					}
					if ( 'list' === $atts['format'] ) {
						$output = '<ul class="rem-property-amenities-list">';
						foreach ( $list_items as $item ) {
							$output .= '<li><span class="dashicons dashicons-yes"></span> ' . esc_html( $item ) . '</li>';
						}
						$output .= '</ul>';
					} else {
						$output = implode( ', ', $list_items );
					}
				}
				break;

			case 'agent_phone':
			case 'contact_number':
				$output = get_field( 'agent_phone', $post_id );
				if ( $output && 'link' === $atts['format'] ) {
					$clean_phone = preg_replace( '/\s+/', '', $output );
					$output = '<a href="tel:' . esc_attr( $clean_phone ) . '" class="rem-phone-link"><span class="dashicons dashicons-phone"></span> ' . esc_html( $output ) . '</a>';
				}
				break;

			case 'agent_whatsapp':
			case 'whatsapp_number':
				$output = get_field( 'agent_whatsapp', $post_id );
				if ( empty( $output ) ) {
					$output = get_field( 'agent_phone', $post_id );
				}
				if ( $output ) {
					if ( 'link' === $atts['format'] ) {
						$clean_wa = preg_replace( '/[^0-9]/', '', $output );
						$wa_msg = rawurlencode( sprintf( __( 'Hi, I am interested in your property "%s" (ID: %s). Please share details. Link: %s', 'custom-real-estate-manager' ), get_the_title( $post_id ), get_field( 'property_id', $post_id ), get_permalink( $post_id ) ) );
						$output = '<a href="https://wa.me/' . esc_attr( $clean_wa ) . '?text=' . $wa_msg . '" target="_blank" class="rem-whatsapp-link"><span class="dashicons dashicons-whatsapp"></span> ' . esc_html( $output ) . '</a>';
					}
				}
				break;

			case 'documents':
			case 'property_documents':
				$docs = array(
					'brochure'      => get_field( 'property_brochure_pdf', $post_id ),
					'approval'      => get_field( 'property_approval_certificate', $post_id ),
					'tax'           => get_field( 'property_land_tax_receipt', $post_id ),
					'ownership'     => get_field( 'property_ownership_document', $post_id ),
					'survey'        => get_field( 'property_survey_document', $post_id ),
					'permit'        => get_field( 'property_building_permit', $post_id ),
					'floor_plan'    => get_field( 'property_floor_plan_pdf', $post_id ),
					'other'         => get_field( 'property_other_attachments', $post_id ),
				);

				$output_list = array();
				foreach ( $docs as $key => $doc ) {
					if ( $doc ) {
						$title = '';
						switch ( $key ) {
							case 'brochure': $title = __( 'Property Brochure', 'custom-real-estate-manager' ); break;
							case 'approval': $title = __( 'Approval Certificate', 'custom-real-estate-manager' ); break;
							case 'tax': $title = __( 'Land Tax Receipt', 'custom-real-estate-manager' ); break;
							case 'ownership': $title = __( 'Ownership Document', 'custom-real-estate-manager' ); break;
							case 'survey': $title = __( 'Survey Document', 'custom-real-estate-manager' ); break;
							case 'permit': $title = __( 'Building Permit', 'custom-real-estate-manager' ); break;
							case 'floor_plan': $title = __( 'Floor Plan', 'custom-real-estate-manager' ); break;
							case 'other': $title = ! empty( $doc['title'] ) ? $doc['title'] : __( 'Other Attachment', 'custom-real-estate-manager' ); break;
						}
						$url = is_array( $doc ) ? $doc['url'] : ( is_numeric( $doc ) ? wp_get_attachment_url( $doc ) : $doc );
						if ( $url ) {
							$output_list[] = '<a href="' . esc_url( $url ) . '" target="_blank" class="rem-doc-link"><span class="dashicons dashicons-media-document"></span> ' . esc_html( $title ) . '</a>';
						}
					}
				}

				if ( ! empty( $output_list ) ) {
					$output = '<div class="rem-shortcode-documents-list">' . implode( '', $output_list ) . '</div>';
				}
				break;

			default:
				// Fallback to standard ACF field retrieval
				$val = get_field( $field_name, $post_id );
				if ( is_array( $val ) ) {
					$output = implode( ', ', array_filter( array_map( 'strval', $val ) ) );
				} elseif ( is_object( $val ) ) {
					$output = ''; // Cannot display object directly
				} else {
					$output = strval( $val );
				}
				break;
		}

		if ( empty( $output ) && ! empty( $atts['fallback'] ) ) {
			$output = $atts['fallback'];
		}

		if ( ! empty( $output ) ) {
			return $atts['prefix'] . $output . $atts['suffix'];
		}

		return '';
	}

	/**
	 * Render the Property Filters Form.
	 */
	public function render_filters( $atts ) {
		ob_start();
		
		// Load the filters template partial.
		if ( file_exists( CREM_PLUGIN_DIR . 'templates/partials/property-filters.php' ) ) {
			include CREM_PLUGIN_DIR . 'templates/partials/property-filters.php';
		}

		return ob_get_clean();
	}

	/**
	 * Render the Property Listing Grid & Map.
	 */
	public function render_listing( $atts ) {
		// Parse shortcode attributes
		$atts = shortcode_atts( array(
			'posts_per_page' => 6,
			'show_map'       => 'yes',
		), $atts, 'property_listing' );

		// Initial query of properties (SEO friendly initial render)
		$args = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $atts['posts_per_page'] ),
			'paged'          => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'property_availability_status',
					'value'   => 'hidden',
					'compare' => '!=',
				),
				array(
					'key'     => 'approval_status',
					'value'   => 'approved',
					'compare' => '=',
				),
			),
		);

		$query = new WP_Query( $args );

		ob_start();
		?>
		<div class="rem-listing-wrapper" data-posts-per-page="<?php echo esc_attr( $atts['posts_per_page'] ); ?>">
			
			<?php if ( 'yes' === $atts['show_map'] ) : ?>
				<!-- Map Container -->
				<div class="rem-map-container-wrapper">
					<div id="rem-properties-map" style="height: 400px; width: 100%; border-radius: 12px; margin-bottom: 30px; border: 1px solid rgba(0,0,0,0.08);"></div>
				</div>
			<?php endif; ?>

			<!-- Grid and Search Meta Info -->
			<div class="rem-results-header">
				<div class="rem-results-count">
					<span class="rem-count-number"><?php echo esc_html( $query->found_posts ); ?></span> <?php esc_html_e( 'properties found', 'custom-real-estate-manager' ); ?>
				</div>
			</div>

			<!-- Property Cards Grid -->
			<div class="rem-property-grid-container">
				<div class="rem-loader-overlay">
					<div class="rem-spinner"></div>
				</div>
				<div class="rem-property-grid">
					<?php
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							// Render property card template.
							include CREM_PLUGIN_DIR . 'templates/partials/property-card.php';
						}
						wp_reset_postdata();
					} else {
						echo '<div class="rem-no-results">';
						echo '<span class="dashicons dashicons-search"></span>';
						echo '<p>' . esc_html__( 'No properties found.', 'custom-real-estate-manager' ) . '</p>';
						echo '</div>';
					}
					?>
				</div>
			</div>

			<!-- Pagination Links -->
			<div class="rem-property-pagination">
				<?php
				echo paginate_links( array(
					'total'     => $query->max_num_pages,
					'current'   => 1,
					'type'      => 'plain',
					'prev_next' => true,
					'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
					'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
				) );
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the Advanced Real Estate Search Widget.
	 */
	public function render_advanced_search( $atts ) {
		ob_start();
		if ( file_exists( CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php' ) ) {
			include CREM_PLUGIN_DIR . 'templates/partials/property-advanced-search.php';
		}
		return ob_get_clean();
	}
}
