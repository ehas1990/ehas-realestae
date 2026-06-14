<?php
/**
 * Register Custom Taxonomies
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_Taxonomies {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Redundant taxonomy disabled: status is stored purely in ACF.
		// add_action( 'init', array( $this, 'register_taxonomies' ), 9 );
	}

	/**
	 * Register Taxonomies.
	 */
	public function register_taxonomies() {
		$labels = array(
			'name'                       => _x( 'Property Statuses', 'taxonomy general name', 'custom-real-estate-manager' ),
			'singular_name'              => _x( 'Property Status', 'taxonomy singular name', 'custom-real-estate-manager' ),
			'search_items'               => __( 'Search Property Statuses', 'custom-real-estate-manager' ),
			'all_items'                  => __( 'All Property Statuses', 'custom-real-estate-manager' ),
			'parent_item'                => __( 'Parent Property Status', 'custom-real-estate-manager' ),
			'parent_item_colon'          => __( 'Parent Property Status:', 'custom-real-estate-manager' ),
			'edit_item'                  => __( 'Edit Property Status', 'custom-real-estate-manager' ),
			'update_item'                => __( 'Update Property Status', 'custom-real-estate-manager' ),
			'add_new_item'               => __( 'Add New Property Status', 'custom-real-estate-manager' ),
			'new_item_name'              => __( 'New Property Status Name', 'custom-real-estate-manager' ),
			'menu_name'                  => __( 'Property Status', 'custom-real-estate-manager' ),
		);

		$args = array(
			'hierarchical'          => true, // set to true so it works like categories
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'property-status' ),
			'show_in_rest'          => true,
			// Custom meta box callback can be used to display radio buttons instead of checkboxes
			'meta_box_cb'           => array( $this, 'property_status_radio_meta_box' ),
		);

		register_taxonomy( 'property_status', array( 'property' ), $args );

		// Insert default terms if they don't exist yet
		$this->insert_default_terms();
	}

	/**
	 * Insert default taxonomy terms: For Sale, Off Sale, Off Plan.
	 */
	public function insert_default_terms() {
		$terms = array(
			'For Sale' => 'for-sale',
			'Off Sale' => 'off-sale',
			'Off Plan' => 'off-plan',
		);

		foreach ( $terms as $name => $slug ) {
			if ( ! term_exists( $slug, 'property_status' ) ) {
				wp_insert_term( $name, 'property_status', array( 'slug' => $slug ) );
			}
		}
	}

	/**
	 * Custom Meta Box Callback for Radio Buttons instead of Checkboxes.
	 * Enforces the "one status per property" requirement.
	 */
	public function property_status_radio_meta_box( $post, $box ) {
		$taxonomy = $box['args']['taxonomy'];
		$tax = get_taxonomy( $taxonomy );
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		) );

		$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		$current_value = ! empty( $post_terms ) ? $post_terms[0] : '';

		// Nonce for security
		wp_nonce_field( 'rem_property_status_meta_box', 'rem_property_status_meta_box_nonce' );

		if ( empty( $terms ) ) {
			echo '<p>' . esc_html__( 'No statuses found.', 'custom-real-estate-manager' ) . '</p>';
			return;
		}

		echo '<ul class="categorychecklist form-no-clear">';
		foreach ( $terms as $term ) {
			$id = 'property_status-' . $term->term_id;
			$checked = checked( $current_value, $term->term_id, false );
			?>
			<li id="<?php echo esc_attr( $id ); ?>">
				<label class="selectit">
					<input type="radio" name="tax_input[property_status][]" value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo $checked; ?> />
					<?php echo esc_html( $term->name ); ?>
				</label>
			</li>
			<?php
		}
		echo '</ul>';
	}
}
