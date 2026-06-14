<?php
/**
 * Programmatically Register ACF Fields
 *
 * @package RealEstateManager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class REM_ACF_Fields {

	public function __construct() {
		add_action( 'acf/init', array( $this, 'register_field_groups' ) );
		add_filter( 'acf/prepare_field/name=approval_status', array( $this, 'restrict_admin_only_fields' ) );
		add_filter( 'acf/prepare_field/name=assigned_agent', array( $this, 'restrict_admin_only_fields' ) );
	}

	/**
	 * Restrict fields to Administrators only.
	 */
	public function restrict_admin_only_fields( $field ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return $field;
	}

	/**
	 * Register ACF Field Groups.
	 */
	public function register_field_groups() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		// 1. State Custom Fields (Featured Image)
		acf_add_local_field_group( array(
			'key'    => 'group_state_details',
			'title'  => __( 'State Details', 'custom-real-estate-manager' ),
			'fields' => array(
				array(
					'key'           => 'field_state_featured_image',
					'label'         => __( 'Featured Image', 'custom-real-estate-manager' ),
					'name'          => 'featured_image',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'state',
					),
				),
			),
		) );

		// 2. District Custom Fields (Belongs to State)
		acf_add_local_field_group( array(
			'key'    => 'group_district_details',
			'title'  => __( 'District Details', 'custom-real-estate-manager' ),
			'fields' => array(
				array(
					'key'           => 'field_district_belongs_to_state',
					'label'         => __( 'Belongs to State', 'custom-real-estate-manager' ),
					'name'          => 'belongs_to_state',
					'type'          => 'post_object',
					'post_type'     => array( 'state' ),
					'allow_null'    => 0,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'district',
					),
				),
			),
		) );

		// 3. Taluk Custom Fields (Belongs to District)
		acf_add_local_field_group( array(
			'key'    => 'group_taluk_details',
			'title'  => __( 'Taluk Details', 'custom-real-estate-manager' ),
			'fields' => array(
				array(
					'key'           => 'field_taluk_belongs_to_district',
					'label'         => __( 'Belongs to District', 'custom-real-estate-manager' ),
					'name'          => 'belongs_to_district',
					'type'          => 'post_object',
					'post_type'     => array( 'district' ),
					'allow_null'    => 0,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'taluk',
					),
				),
			),
		) );

		// 4. Location Place Custom Fields (Belongs to Taluk)
		acf_add_local_field_group( array(
			'key'    => 'group_place_details',
			'title'  => __( 'Place Details', 'custom-real-estate-manager' ),
			'fields' => array(
				array(
					'key'           => 'field_place_belongs_to_taluk',
					'label'         => __( 'Belongs to Taluk', 'custom-real-estate-manager' ),
					'name'          => 'belongs_to_taluk',
					'type'          => 'post_object',
					'post_type'     => array( 'taluk' ),
					'allow_null'    => 0,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'location_place',
					),
				),
			),
		) );

		// 5. Property Details Field Group
		acf_add_local_field_group( array(
			'key'    => 'group_property_details',
			'title'  => __( 'Property Details', 'custom-real-estate-manager' ),
			'fields' => array(
				
				// TAB 1: Property Information
				array(
					'key'   => 'tab_basic_info',
					'label' => __( '<span class="dashicons dashicons-admin-home"></span> Property Information', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_basic_info',
					'label'        => __( 'General Details', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_title',
					'label'         => __( 'Property Title', 'custom-real-estate-manager' ),
					'name'          => 'property_title',
					'type'          => 'text',
					'required'      => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_id',
					'label'         => __( 'Property ID', 'custom-real-estate-manager' ),
					'name'          => 'property_id',
					'type'          => 'text',
					'instructions'  => __( 'Auto-generated on save.', 'custom-real-estate-manager' ),
					'readonly'      => 1,
					'placeholder'   => 'PROP-XXXX',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_status',
					'label'         => __( 'Property Status', 'custom-real-estate-manager' ),
					'name'          => 'property_status',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'for_sale' => __( 'For Sale', 'custom-real-estate-manager' ),
						'for_rent' => __( 'For Rent', 'custom-real-estate-manager' ),
						'sold'     => __( 'Sold', 'custom-real-estate-manager' ),
						'rented'   => __( 'Rented', 'custom-real-estate-manager' ),
						'featured' => __( 'Featured', 'custom-real-estate-manager' ),
						'booked'   => __( 'Booked', 'custom-real-estate-manager' ),
						'off_plan' => __( 'Off Plan', 'custom-real-estate-manager' ),
					),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_availability_status',
					'label'         => __( 'Availability Status', 'custom-real-estate-manager' ),
					'name'          => 'property_availability_status',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'available' => __( 'Available', 'custom-real-estate-manager' ),
						'hidden'    => __( 'Hidden', 'custom-real-estate-manager' ),
					),
					'default_value' => 'available',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_approval_status',
					'label'         => __( 'Approval Status', 'custom-real-estate-manager' ),
					'name'          => 'approval_status',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'pending'  => __( 'Pending Approval', 'custom-real-estate-manager' ),
						'approved' => __( 'Approved', 'custom-real-estate-manager' ),
						'rejected' => __( 'Rejected', 'custom-real-estate-manager' ),
					),
					'default_value' => 'pending',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_assigned_agent',
					'label'         => __( 'Assigned Agent', 'custom-real-estate-manager' ),
					'name'          => 'assigned_agent',
					'type'          => 'user',
					'required'      => 0,
					'role'          => array( 'agent' ),
					'allow_null'    => 1,
					'multiple'      => 0,
					'return_format' => 'id',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_type',
					'label'         => __( 'Property Type', 'custom-real-estate-manager' ),
					'name'          => 'property_type',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'house'              => __( 'House', 'custom-real-estate-manager' ),
						'villa'              => __( 'Villa', 'custom-real-estate-manager' ),
						'apartment'          => __( 'Apartment', 'custom-real-estate-manager' ),
						'flat'               => __( 'Flat', 'custom-real-estate-manager' ),
						'commercial'         => __( 'Commercial', 'custom-real-estate-manager' ),
						'land'               => __( 'Land', 'custom-real-estate-manager' ),
						'office'             => __( 'Office', 'custom-real-estate-manager' ),
						'commercial_building' => __( 'Commercial Building', 'custom-real-estate-manager' ),
						'commercial_space'    => __( 'Commercial Space', 'custom-real-estate-manager' ),
						'plot'               => __( 'Plot', 'custom-real-estate-manager' ),
						'agricultural_land'  => __( 'Agricultural Land', 'custom-real-estate-manager' ),
					),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_price',
					'label'         => __( 'Property Price (INR)', 'custom-real-estate-manager' ),
					'name'          => 'property_price',
					'type'          => 'number',
					'required'      => 1,
					'min'           => 0,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_price_label',
					'label'         => __( 'Price Label', 'custom-real-estate-manager' ),
					'name'          => 'property_price_label',
					'type'          => 'text',
					'placeholder'   => __( 'e.g. ₹ 85 Lakhs', 'custom-real-estate-manager' ),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'          => 'accordion_basic_description',
					'label'        => __( 'Description', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_description',
					'label'         => __( 'Property Description', 'custom-real-estate-manager' ),
					'name'          => 'property_description',
					'type'          => 'wysiwyg',
					'required'      => 1,
					'tabs'          => 'all',
					'toolbar'       => 'full',
					'media_upload'  => 1,
					'delay'         => 0,
					'wrapper'       => array(
						'width' => '100',
					),
				),
				array(
					'key'      => 'accordion_basic_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 2: Location Details
				array(
					'key'   => 'tab_location_details',
					'label' => __( '<span class="dashicons dashicons-location-alt"></span> Location Details', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_location_cascade',
					'label'        => __( 'Regional Dropdowns', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_state',
					'label'         => __( 'State', 'custom-real-estate-manager' ),
					'name'          => 'property_state',
					'type'          => 'post_object',
					'post_type'     => array( 'state' ),
					'required'      => 1,
					'allow_null'    => 1,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_district',
					'label'         => __( 'District', 'custom-real-estate-manager' ),
					'name'          => 'property_district',
					'type'          => 'post_object',
					'post_type'     => array( 'district' ),
					'required'      => 1,
					'allow_null'    => 1,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_taluk',
					'label'         => __( 'Taluk', 'custom-real-estate-manager' ),
					'name'          => 'property_taluk',
					'type'          => 'post_object',
					'post_type'     => array( 'taluk' ),
					'required'      => 1,
					'allow_null'    => 1,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_place',
					'label'         => __( 'Location', 'custom-real-estate-manager' ),
					'name'          => 'property_place',
					'type'          => 'post_object',
					'post_type'     => array( 'location_place' ),
					'required'      => 1,
					'allow_null'    => 1,
					'multiple'      => 0,
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'          => 'accordion_location_address',
					'label'        => __( 'Address & Coordinates', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_address_1',
					'label'         => __( 'Full Address', 'custom-real-estate-manager' ),
					'name'          => 'property_address_1',
					'type'          => 'textarea',
					'required'      => 1,
					'rows'          => 3,
					'wrapper'       => array(
						'width' => '100',
					),
				),
				array(
					'key'           => 'field_property_landmark',
					'label'         => __( 'Landmark', 'custom-real-estate-manager' ),
					'name'          => 'property_landmark',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_pincode',
					'label'         => __( 'Pincode', 'custom-real-estate-manager' ),
					'name'          => 'property_pincode',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_map_url',
					'label'         => __( 'Google Map URL', 'custom-real-estate-manager' ),
					'name'          => 'property_map_url',
					'type'          => 'url',
					'wrapper'       => array(
						'width' => '100',
					),
				),
				array(
					'key'           => 'field_property_latitude',
					'label'         => __( 'Latitude', 'custom-real-estate-manager' ),
					'name'          => 'property_latitude',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_longitude',
					'label'         => __( 'Longitude', 'custom-real-estate-manager' ),
					'name'          => 'property_longitude',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'      => 'accordion_location_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 3: Property Details
				array(
					'key'   => 'tab_property_specs',
					'label' => __( '<span class="dashicons dashicons-forms"></span> Property Details', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_specs_layout',
					'label'        => __( 'Dimensions & Layout', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_total_area',
					'label'         => __( 'Property Area', 'custom-real-estate-manager' ),
					'name'          => 'property_area',
					'type'          => 'number',
					'required'      => 1,
					'min'           => 0,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_area_unit',
					'label'         => __( 'Area Unit', 'custom-real-estate-manager' ),
					'name'          => 'property_area_unit',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'sq_ft' => __( 'Sq Ft', 'custom-real-estate-manager' ),
						'cent'  => __( 'Cent', 'custom-real-estate-manager' ),
						'acre'  => __( 'Acre', 'custom-real-estate-manager' ),
					),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_bedrooms',
					'label'         => __( 'Bedrooms', 'custom-real-estate-manager' ),
					'name'          => 'property_bedrooms',
					'type'          => 'number',
					'min'           => 0,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_bathrooms',
					'label'         => __( 'Bathrooms', 'custom-real-estate-manager' ),
					'name'          => 'property_bathrooms',
					'type'          => 'number',
					'min'           => 0,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'          => 'accordion_specs_details',
					'label'        => __( 'Additional Details', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_total_floors',
					'label'         => __( 'Floors', 'custom-real-estate-manager' ),
					'name'          => 'property_total_floors',
					'type'          => 'number',
					'min'           => 0,
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_parking',
					'label'         => __( 'Parking', 'custom-real-estate-manager' ),
					'name'          => 'property_parking',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'yes' => __( 'Yes', 'custom-real-estate-manager' ),
						'no'  => __( 'No', 'custom-real-estate-manager' ),
					),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'               => 'field_property_parking_count',
					'label'             => __( 'Parking Count', 'custom-real-estate-manager' ),
					'name'              => 'property_parking_count',
					'type'              => 'number',
					'min'               => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_property_parking',
								'operator' => '==',
								'value'    => 'yes',
							),
						),
					),
					'wrapper'           => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_furnishing',
					'label'         => __( 'Furnished Status', 'custom-real-estate-manager' ),
					'name'          => 'property_furnishing',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'fully_furnished' => __( 'Fully Furnished', 'custom-real-estate-manager' ),
						'semi_furnished'  => __( 'Semi Furnished', 'custom-real-estate-manager' ),
						'unfurnished'     => __( 'Unfurnished', 'custom-real-estate-manager' ),
					),
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_age',
					'label'         => __( 'Property Age', 'custom-real-estate-manager' ),
					'name'          => 'property_age',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_road_access',
					'label'         => __( 'Road Access', 'custom-real-estate-manager' ),
					'name'          => 'property_road_access',
					'type'          => 'text',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'      => 'accordion_specs_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 4: Amenities
				array(
					'key'   => 'tab_property_features',
					'label' => __( '<span class="dashicons dashicons-star-filled"></span> Amenities', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_features_checklist',
					'label'        => __( 'Select Amenities', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'     => 'field_property_features',
					'label'   => __( 'Amenities Options', 'custom-real-estate-manager' ),
					'name'    => 'property_features',
					'type'    => 'checkbox',
					'choices' => array(
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
					),
					'layout'  => 'vertical',
					'wrapper' => array(
						'width' => '100',
					),
				),
				array(
					'key'      => 'accordion_features_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 5: Images & Documents
				array(
					'key'   => 'tab_property_media',
					'label' => __( '<span class="dashicons dashicons-format-gallery"></span> Images & Documents', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_media_visuals',
					'label'        => __( 'Visuals', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_featured_image',
					'label'         => __( 'Featured Image', 'custom-real-estate-manager' ),
					'name'          => 'featured_image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
					'max_size'      => '20MB',
					'mime_types'    => 'jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_gallery',
					'label'         => __( 'Property Gallery', 'custom-real-estate-manager' ),
					'name'          => 'property_gallery',
					'type'          => 'gallery',
					'return_format' => 'id',
					'preview_size'  => 'thumbnail',
					'insert'        => 'append',
					'library'       => 'all',
					'max_size'      => '20MB',
					'mime_types'    => 'jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '100',
					),
				),
				array(
					'key'     => 'field_property_video_url',
					'label'   => __( 'Property Video URL', 'custom-real-estate-manager' ),
					'name'    => 'property_video_url',
					'type'    => 'url',
					'wrapper' => array(
						'width' => '100',
					),
				),
				array(
					'key'          => 'accordion_media_docs',
					'label'        => __( 'PDFs & Links', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'           => 'field_property_brochure_pdf',
					'label'         => __( 'Property Brochure PDF', 'custom-real-estate-manager' ),
					'name'          => 'property_brochure_pdf',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_approval_certificate',
					'label'         => __( 'Property Approval Certificate', 'custom-real-estate-manager' ),
					'name'          => 'property_approval_certificate',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_land_tax_receipt',
					'label'         => __( 'Land Tax Receipt', 'custom-real-estate-manager' ),
					'name'          => 'property_land_tax_receipt',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_ownership_document',
					'label'         => __( 'Ownership Document', 'custom-real-estate-manager' ),
					'name'          => 'property_ownership_document',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_survey_document',
					'label'         => __( 'Survey Document', 'custom-real-estate-manager' ),
					'name'          => 'property_survey_document',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_building_permit',
					'label'         => __( 'Building Permit', 'custom-real-estate-manager' ),
					'name'          => 'property_building_permit',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_floor_plan_pdf',
					'label'         => __( 'Floor Plan PDF', 'custom-real-estate-manager' ),
					'name'          => 'property_floor_plan_pdf',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_property_other_attachments',
					'label'         => __( 'Other Attachments', 'custom-real-estate-manager' ),
					'name'          => 'property_other_attachments',
					'type'          => 'file',
					'return_format' => 'array',
					'max_size'      => '20MB',
					'mime_types'    => 'pdf,doc,docx,jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'      => 'accordion_media_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 6: Agent Details
				array(
					'key'   => 'tab_agent_info',
					'label' => __( '<span class="dashicons dashicons-admin-users"></span> Agent Details', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_agent_info',
					'label'        => __( 'Contact Information', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'      => 'field_agent_name',
					'label'    => __( 'Agent Name', 'custom-real-estate-manager' ),
					'name'     => 'agent_name',
					'type'     => 'text',
					'required' => 1,
					'wrapper'  => array(
						'width' => '50',
					),
				),
				array(
					'key'      => 'field_agent_phone',
					'label'    => __( 'Mobile Number', 'custom-real-estate-manager' ),
					'name'     => 'agent_phone',
					'type'     => 'text',
					'required' => 1,
					'wrapper'  => array(
						'width' => '50',
					),
				),
				array(
					'key'     => 'field_agent_whatsapp',
					'label'   => __( 'WhatsApp Number', 'custom-real-estate-manager' ),
					'name'    => 'agent_whatsapp',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '50',
					),
				),
				array(
					'key'     => 'field_agent_email',
					'label'   => __( 'Email Address', 'custom-real-estate-manager' ),
					'name'    => 'agent_email',
					'type'    => 'email',
					'wrapper' => array(
						'width' => '50',
					),
				),
				array(
					'key'           => 'field_agent_photo',
					'label'         => __( 'Agent Photo', 'custom-real-estate-manager' ),
					'name'          => 'agent_photo',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
					'max_size'      => '20MB',
					'mime_types'    => 'jpg,jpeg,png',
					'wrapper'       => array(
						'width' => '50',
					),
				),
				array(
					'key'      => 'accordion_agent_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),

				// TAB 7: SEO
				array(
					'key'   => 'tab_seo_fields',
					'label' => __( '<span class="dashicons dashicons-search"></span> SEO', 'custom-real-estate-manager' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'accordion_seo_fields',
					'label'        => __( 'Search Metadata', 'custom-real-estate-manager' ),
					'type'         => 'accordion',
					'open'         => 1,
					'multi_expand' => 1,
				),
				array(
					'key'     => 'field_seo_meta_title',
					'label'   => __( 'Meta Title', 'custom-real-estate-manager' ),
					'name'    => 'seo_meta_title',
					'type'    => 'text',
					'wrapper' => array(
						'width' => '100',
					),
				),
				array(
					'key'     => 'field_seo_meta_description',
					'label'   => __( 'Meta Description', 'custom-real-estate-manager' ),
					'name'    => 'seo_meta_description',
					'type'    => 'textarea',
					'rows'    => 3,
					'wrapper' => array(
						'width' => '100',
					),
				),
				array(
					'key'      => 'accordion_seo_end',
					'type'     => 'accordion',
					'endpoint' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'property',
					),
				),
			),
		) );
	}
}
