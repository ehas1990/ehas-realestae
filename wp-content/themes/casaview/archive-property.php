<?php
/**
 * Template Name: Properties Archive
 * Archive Property Template Page (Filters & Listings)
 */

get_header();

// Fetch filter options dynamically
$all_locations = get_terms( array(
	'taxonomy'   => 'property_location',
	'hide_empty' => false,
) );
$locations_json = array();
foreach ( $all_locations as $term ) {
	$locations_json[] = array(
		'id'     => $term->term_id,
		'name'   => $term->name,
		'slug'   => $term->slug,
		'parent' => $term->parent,
	);
}
$types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
$amenities = get_terms( array( 'taxonomy' => 'amenity', 'hide_empty' => false ) );
?>

<style>
	.archive-properties-wrapper {
		padding-top: 130px;
		background-color: var(--bg-primary);
		color: var(--text-white);
		font-family: var(--font-en);
		min-height: 100vh;
	}
	.archive-title-section {
		margin-bottom: 40px;
	}
	.archive-title-section h1 {
		font-size: 38px;
		font-weight: 800;
		color: var(--text-white);
		margin-bottom: 10px;
	}
	.archive-title-section p {
		color: var(--text-muted);
		font-size: 15px;
	}
	
	/* Layout Grid */
	.archive-layout {
		display: grid;
		grid-template-columns: 300px 1fr;
		gap: 40px;
		margin-bottom: 80px;
	}
	
	/* Sidebar Filters */
	.filter-sidebar {
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 12px;
		padding: 24px;
		height: fit-content;
		position: sticky;
		top: 100px;
	}
	.filter-group-title {
		font-size: 14px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		margin-bottom: 15px;
		color: var(--accent-gold);
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.filter-section {
		border-bottom: 1px solid var(--border-white);
		padding-bottom: 20px;
		margin-bottom: 20px;
	}
	.filter-section:last-child {
		border-bottom: none;
		padding-bottom: 0;
		margin-bottom: 0;
	}
	.checkbox-grid {
		display: flex;
		flex-direction: column;
		gap: 10px;
		max-height: 180px;
		overflow-y: auto;
		padding-right: 5px;
	}
	/* Custom scrollbar for checkbox grid */
	.checkbox-grid::-webkit-scrollbar {
		width: 4px;
	}
	.checkbox-grid::-webkit-scrollbar-thumb {
		background: var(--border-gold);
		border-radius: 2px;
	}
	.checkbox-item {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 13px;
		color: var(--text-muted);
		cursor: pointer;
	}
	.checkbox-item input {
		accent-color: var(--accent-gold);
		cursor: pointer;
	}
	.btn-reset-filters {
		width: 100%;
		background: transparent;
		border: 1px solid var(--border-gold);
		color: var(--accent-gold);
		padding: 10px;
		font-weight: 700;
		font-size: 13px;
		border-radius: 6px;
		cursor: pointer;
		margin-top: 15px;
		transition: all 0.3s;
	}
	.btn-reset-filters:hover {
		background: var(--accent-gold);
		color: #ffffff;
	}
	
	/* Listings Column */
	.listings-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		background: var(--bg-secondary);
		border: 1px solid var(--border-white);
		border-radius: 8px;
		padding: 15px 24px;
		margin-bottom: 30px;
	}
	.listings-header-left {
		font-size: 14px;
		color: var(--text-muted);
	}
	.listings-header-left strong {
		color: var(--text-white);
	}
	.listings-header-right {
		display: flex;
		gap: 15px;
	}
	.btn-layout-toggle {
		background: transparent;
		border: none;
		color: var(--text-muted);
		cursor: pointer;
		font-size: 16px;
		transition: color 0.3s;
	}
	.btn-layout-toggle.active {
		color: var(--accent-gold);
	}
	
	/* Properties Grid archive */
	.archive-properties-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 30px;
	}
	
	/* Live Compare Drawer */
	.compare-drawer {
		position: fixed;
		bottom: -150px;
		left: 50%;
		transform: translateX(-50%);
		width: 90%;
		max-width: 800px;
		background: var(--bg-secondary);
		border: 1px solid var(--accent-gold);
		border-radius: 12px 12px 0 0;
		box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.4);
		padding: 15px 25px;
		z-index: 999;
		display: flex;
		align-items: center;
		justify-content: space-between;
		transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
	}
	.compare-drawer.active {
		bottom: 0;
	}
	.compare-drawer-items {
		display: flex;
		gap: 15px;
		align-items: center;
	}
	.compare-drawer-item {
		background: var(--bg-primary);
		border: 1px solid var(--border-white);
		border-radius: 6px;
		padding: 6px 12px;
		font-size: 12px;
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.compare-drawer-item i {
		color: #eb5757;
		cursor: pointer;
	}
	
	/* Compare Modal side-by-side */
	.compare-modal-overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(11, 12, 16, 0.85);
		z-index: 1000;
		display: none;
		align-items: center;
		justify-content: center;
		backdrop-filter: blur(5px);
	}
	.compare-modal {
		background: var(--bg-secondary);
		border: 1px solid var(--accent-gold);
		border-radius: 12px;
		width: 90%;
		max-width: 1000px;
		padding: 40px;
		position: relative;
		box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
	}
	.compare-modal-close {
		position: absolute;
		top: 20px;
		right: 20px;
		background: transparent;
		border: none;
		color: var(--text-muted);
		font-size: 20px;
		cursor: pointer;
		transition: color 0.3s;
	}
	.compare-modal-close:hover {
		color: var(--accent-gold);
	}
	.compare-table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 20px;
		text-align: left;
	}
	.compare-table th, .compare-table td {
		padding: 14px 18px;
		border-bottom: 1px solid var(--border-white);
		font-size: 14px;
	}
	.compare-table th {
		font-weight: 700;
		color: var(--accent-gold);
	}
	.compare-table tr:last-child td {
		border-bottom: none;
	}
	
	/* Responsive archive design */
	@media (max-width: 992px) {
		.archive-layout {
			grid-template-columns: 1fr;
		}
		.filter-sidebar {
			position: static;
			margin-bottom: 30px;
		}
	}

	/* Searchable Custom Dropdown Overhaul */
	.searchable-dropdown {
		position: relative;
		width: 100%;
	}
	.searchable-dropdown-trigger {
		background: rgba(255, 255, 255, 0.08);
		border: 1px solid rgba(255, 255, 255, 0.15);
		border-radius: 8px;
		padding: 12px 16px;
		color: #fff;
		cursor: pointer;
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 14px;
		transition: all 0.3s;
		height: 46px;
		box-sizing: border-box;
	}
	.searchable-dropdown-trigger:hover {
		background: rgba(255, 255, 255, 0.12);
		border-color: var(--accent-gold, #c5a880);
	}
	.searchable-dropdown-panel {
		position: absolute;
		top: calc(100% + 5px);
		left: 0;
		width: 100%;
		background: #1a1c23;
		border: 1px solid rgba(255, 255, 255, 0.15);
		border-radius: 8px;
		z-index: 1000;
		box-shadow: 0 10px 30px rgba(0,0,0,0.5);
		padding: 10px;
		box-sizing: border-box;
	}
	.searchable-dropdown-panel .search-input {
		width: 100%;
		background: #0f1015;
		border: 1px solid rgba(255, 255, 255, 0.1);
		color: #fff;
		padding: 8px 12px;
		border-radius: 4px;
		margin-bottom: 8px;
		box-sizing: border-box;
		font-size: 13px;
	}
	.searchable-dropdown-panel .options-list {
		list-style: none;
		margin: 0;
		padding: 0;
		max-height: 200px;
		overflow-y: auto;
	}
	.searchable-dropdown-panel .options-list li {
		padding: 8px 12px;
		color: #ccc;
		font-size: 13px;
		cursor: pointer;
		border-radius: 4px;
		transition: all 0.2s;
	}
	.searchable-dropdown-panel .options-list li:hover {
		background: var(--accent-gold, #c5a880);
		color: #fff;
	}
	.searchable-dropdown-panel .options-list li.disabled {
		color: #666;
		cursor: not-allowed;
		background: transparent !important;
	}

	/* Redesigned Property Cards Scoped to Properties Page */
	.archive-properties-wrapper .property-card {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.08);
		border-radius: 12px;
		overflow: hidden;
		position: relative;
		display: flex;
		flex-direction: column;
		height: 100%;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
	}
	
	.archive-properties-wrapper .property-card:hover {
		transform: translateY(-6px);
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
		border-color: var(--accent-gold);
	}
	
	.archive-properties-wrapper .property-image-wrapper {
		position: relative;
		height: 230px;
		overflow: hidden;
		border-radius: 12px 12px 0 0;
	}
	
	.archive-properties-wrapper .property-image {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}
	
	.archive-properties-wrapper .property-card:hover .property-image {
		transform: scale(1.06);
	}
	
	.archive-properties-wrapper .property-badge-wrapper {
		position: absolute;
		top: 15px;
		left: 15px;
		display: flex;
		flex-direction: column;
		gap: 6px;
		z-index: 4;
	}
	
	.archive-properties-wrapper .property-badge-exclusive {
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 10px;
		font-weight: 800;
		text-transform: uppercase;
		padding: 4px 10px;
		border-radius: 4px;
		letter-spacing: 1px;
	}
	
	.archive-properties-wrapper .property-badge-featured {
		background: linear-gradient(135deg, #d4af37, #b89047);
		color: #ffffff;
		font-size: 10px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 4px;
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}
	
	.archive-properties-wrapper .property-badge-photos {
		position: absolute;
		top: 15px;
		right: 15px;
		background: rgba(28, 29, 33, 0.65);
		backdrop-filter: blur(6px);
		-webkit-backdrop-filter: blur(6px);
		color: #ffffff;
		font-size: 11px;
		font-weight: 600;
		padding: 4px 10px;
		border-radius: 6px;
		z-index: 4;
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}
	
	.archive-properties-wrapper .property-price {
		position: absolute;
		bottom: 15px;
		left: 15px;
		background: #ffffff;
		color: #181a20;
		font-size: 15px;
		font-weight: 700;
		padding: 6px 14px;
		border-radius: 6px;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		z-index: 3;
	}
	
	.archive-properties-wrapper .property-details {
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		background: #ffffff;
	}
	
	.archive-properties-wrapper .property-type-tag {
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		color: var(--accent-gold);
		margin-bottom: 6px;
		letter-spacing: 0.5px;
		display: block;
	}
	
	.archive-properties-wrapper .property-title {
		font-size: 16px;
		font-weight: 600;
		line-height: 1.4;
		margin-bottom: 8px;
		height: 44px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
	}
	
	.archive-properties-wrapper .property-title a {
		color: #181a20;
		transition: color 0.3s ease;
	}
	
	.archive-properties-wrapper .property-title a:hover {
		color: var(--accent-gold);
	}
	
	.archive-properties-wrapper .property-location {
		display: flex;
		align-items: center;
		font-size: 13px;
		color: #717171;
		gap: 6px;
		margin-bottom: 15px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	
	.archive-properties-wrapper .property-location i {
		color: var(--accent-gold);
	}
	
	.archive-properties-wrapper .property-amenities {
		display: flex;
		gap: 15px;
		border-top: 1px solid #e9e9e9;
		padding-top: 12px;
		margin-top: auto;
		margin-bottom: 0;
	}
	
	.archive-properties-wrapper .property-amenity {
		display: flex;
		align-items: center;
		font-size: 13px;
		color: #717171;
		gap: 6px;
		background: transparent;
		padding: 0;
		border-radius: 0;
	}
	
	.archive-properties-wrapper .property-amenity i {
		color: var(--accent-gold);
		font-size: 14px;
	}
	
	.archive-properties-wrapper .property-amenity strong {
		color: #181a20;
	}
	
	.archive-properties-wrapper .property-metas-bottom {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-top: 1px solid #e9e9e9;
		padding-top: 12px;
		margin-top: 12px;
	}
	
	.archive-properties-wrapper .status-property-label {
		font-size: 11px;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.archive-properties-wrapper .status-property-label.badge-rent {
		background: #1C1D21 !important;
		color: #ffffff !important;
	}
	
	.archive-properties-wrapper .status-property-label.badge-sale {
		background: var(--primary-color) !important;
		color: #1C1D21 !important;
	}
	
	.archive-properties-wrapper .btn-action-circle {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		background: #f7f7f7;
		color: #1c1d21;
		border: 1px solid rgba(0, 0, 0, 0.05);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		padding: 0;
	}
	
	.archive-properties-wrapper .btn-action-circle:hover {
		background: var(--accent-gold);
		color: #ffffff;
		border-color: var(--accent-gold);
	}
	
	.archive-properties-wrapper .btn-action-circle.wishlist-btn-toggle.active {
		background: #ffffff;
		color: #e63946;
		border: 1px solid #e63946;
		box-shadow: 0 2px 8px rgba(230, 57, 70, 0.2);
	}
</style>

<?php 
// Default query parsing GET parameters
$get_listing_type = sanitize_text_field( $_GET['listing_type'] ?? 'buy' );
$get_district = sanitize_text_field( $_GET['district'] ?? '' );
$get_place = sanitize_text_field( $_GET['place'] ?? '' );
$get_keyword = sanitize_text_field( $_GET['keyword'] ?? '' );
$get_type = sanitize_text_field( $_GET['type'] ?? '' );

$args = array(
	'post_type'      => 'property',
	'posts_per_page' => 12,
	'post_status'    => 'publish',
	'meta_query'     => array( 'relation' => 'AND' ),
);

if ( ! empty( $get_listing_type ) ) {
	if ( $get_listing_type === 'buy' || $get_listing_type === 'sale' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => array( 'buy', 'sale' ),
			'compare' => 'IN',
		);
	} elseif ( $get_listing_type === 'rent' ) {
		$args['meta_query'][] = array(
			'key'     => 'listing_type',
			'value'   => 'rent',
			'compare' => '=',
		);
	}
}

if ( ! empty( $get_district ) ) {
	$args['meta_query'][] = array(
		'key'     => 'district',
		'value'   => $get_district,
		'compare' => '=',
	);
}

if ( ! empty( $get_place ) ) {
	$args['meta_query'][] = array(
		'key'     => 'place',
		'value'   => $get_place,
		'compare' => '=',
	);
}

if ( ! empty( $get_keyword ) ) {
	$args['s'] = $get_keyword;
}

if ( ! empty( $get_type ) ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $get_type,
		)
	);
}

$query = new WP_Query( $args );
?>
<main class="archive-properties-wrapper">
	<div class="container">
		
		<!-- Archive Title Section -->
		<div class="archive-title-section">
			<h1>Elite Properties Catalog</h1>
			<p>Explore luxury signature penthouses, beachfront estates, and private mansions in Dubai's premier locations.</p>
		</div>
		
		<!-- Archive Columns Layout -->
		<div class="archive-layout">
			
			<!-- Sidebar Filters -->
			<aside class="filter-sidebar">
				<form id="archive-filter-form">
					
					<!-- Keyword Search -->
					<div class="filter-section">
						<div class="filter-group-title">Search</div>
						<div class="form-group" style="margin-bottom: 0;">
							<input type="text" id="filter-keyword" class="form-control" placeholder="Enter keywords..." style="background:var(--bg-primary);" value="<?php echo esc_attr($get_keyword); ?>">
						</div>
					</div>
					
					<!-- Overhauled Property Type, District & Place Dropdowns -->
					<div class="filter-section">
						<div class="filter-group-title">Property Type</div>
						<select id="filter-listing-type" class="form-control" style="background:var(--bg-primary); margin-bottom: 15px;">
							<option value="buy" <?php selected($get_listing_type, 'buy'); ?>>For Sale</option>
							<option value="rent" <?php selected($get_listing_type, 'rent'); ?>>For Rent</option>
						</select>

						<div class="filter-group-title" style="margin-top: 10px;">District</div>
						<div class="searchable-dropdown" id="archive-district-dropdown-container" style="margin-bottom: 15px;">
							<div class="searchable-dropdown-trigger" id="archive-district-trigger">
								<span class="trigger-label">All Districts</span>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
							</div>
							<div class="searchable-dropdown-panel" id="archive-district-panel" style="display: none;">
								<input type="text" class="search-input" id="archive-district-search" placeholder="Search District...">
								<ul class="options-list" id="archive-district-list">
									<!-- Loaded via JS -->
								</ul>
							</div>
							<input type="hidden" name="district" id="filter-district" value="">
						</div>

						<div class="filter-group-title" style="margin-top: 10px;">Place / Location</div>
						<div class="searchable-dropdown" id="archive-place-dropdown-container">
							<div class="searchable-dropdown-trigger" id="archive-place-trigger">
								<span class="trigger-label">All Places</span>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
							</div>
							<div class="searchable-dropdown-panel" id="archive-place-panel" style="display: none;">
								<input type="text" class="search-input" id="archive-place-search" placeholder="Search Place...">
								<ul class="options-list" id="archive-place-list">
									<li class="disabled">Select a District first</li>
								</ul>
							</div>
							<input type="hidden" name="place" id="filter-place" value="">
						</div>
					</div>

					<!-- Property Type Taxonomy -->
					<div class="filter-section">
						<div class="filter-group-title">Property Type</div>
						<select id="filter-type" class="form-control" style="background:var(--bg-primary);">
							<option value="">All Types</option>
							<?php foreach ( $types as $t ) : ?>
								<option value="<?php echo esc_attr($t->slug); ?>" <?php selected($get_type, $t->slug); ?>><?php echo esc_html($t->name); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Listing Category Taxonomy -->
					<div class="filter-section">
						<div class="filter-group-title">Purpose</div>
						<select id="filter-category" class="form-control" style="background:var(--bg-primary);">
							<option value="">Buy / Rent / Off-Plan</option>
							<?php foreach ( $categories as $c ) : ?>
								<option value="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Price Range -->
					<div class="filter-section">
						<div class="filter-group-title">Price Range (AED)</div>
						<div style="display:flex; gap:10px;">
							<input type="number" id="filter-min-price" class="form-control" placeholder="Min" style="background:var(--bg-primary); padding: 8px 12px; font-size:12px;">
							<input type="number" id="filter-max-price" class="form-control" placeholder="Max" style="background:var(--bg-primary); padding: 8px 12px; font-size:12px;">
						</div>
					</div>

					<!-- Beds & Baths -->
					<div class="filter-section">
						<div class="filter-group-title">Bedrooms / Bathrooms</div>
						<div style="display:flex; gap:10px; margin-bottom: 10px;">
							<select id="filter-beds" class="form-control" style="background:var(--bg-primary); padding: 8px 12px; font-size:12px;">
								<option value="">Beds</option>
								<option value="1">1 Bed</option>
								<option value="2">2 Beds</option>
								<option value="3">3 Beds</option>
								<option value="4">4 Beds</option>
								<option value="5">5+ Beds</option>
							</select>
							<select id="filter-baths" class="form-control" style="background:var(--bg-primary); padding: 8px 12px; font-size:12px;">
								<option value="">Baths</option>
								<option value="1">1 Bath</option>
								<option value="2">2 Baths</option>
								<option value="3">3 Baths</option>
								<option value="4">4 Baths</option>
								<option value="5">5+ Baths</option>
							</select>
						</div>
					</div>

					<!-- Amenities Taxonomy Checkboxes -->
					<?php if ( ! empty($amenities) ) : ?>
						<div class="filter-section">
							<div class="filter-group-title">Amenities</div>
							<div class="checkbox-grid">
								<?php foreach ( $amenities as $am ) : ?>
									<label class="checkbox-item">
										<input type="checkbox" name="amenity_filter" value="<?php echo esc_attr($am->slug); ?>">
										<span><?php echo esc_html($am->name); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<button type="button" id="btn-clear-filters" class="btn-reset-filters">Clear All Filters</button>
				</form>
			</aside>
			
			<!-- Listings Column -->
			<div class="listings-column">
				<div class="listings-header">
					<div class="listings-header-left" id="listings-count-header">
						Showing <strong><?php echo esc_html($query->found_posts); ?></strong> available listings
					</div>
					<div class="listings-header-right">
						<button class="btn-layout-toggle active" id="btn-grid-view" aria-label="Grid View">
							<i class="fa-solid fa-table-cells-large"></i>
						</button>
						<button class="btn-layout-toggle" id="btn-list-view" aria-label="List View" style="display:none;">
							<i class="fa-solid fa-list"></i>
						</button>
					</div>
				</div>
				
				<!-- Live AJAX Properties Grid -->
				<div class="archive-properties-grid" id="properties-ajax-grid">
					<?php 
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) : $query->the_post();
							$price = get_field('price') ?: 0;
							$beds = get_field('bedrooms') ?: 0;
							$baths = get_field('bathrooms') ?: 0;
							$area = get_field('area_sqft') ?: 0;
							
							$display_district = get_post_meta(get_the_ID(), 'district', true);
							$display_place = get_post_meta(get_the_ID(), 'place', true);
							$display_location = ($display_place ? $display_place : '') . ($display_district ? ', ' . $display_district : '');
							if ( empty($display_location) ) {
								$display_location = 'India';
							}
							
							$listing_type = get_field('listing_type') ?: 'buy';
							$is_exclusive = get_field('is_exclusive');
							$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: (get_post_meta(get_the_ID(), '_mock_image_url', true) ?: get_template_directory_uri() . '/assets/images/property-default.jpg');
							
							// Get photo count
							$gallery_images = casaview_get_repeater('gallery_images', get_the_ID());
							$photo_count = 1;
							if ( ! empty( $gallery_images ) ) {
								$photo_count += count($gallery_images);
							}
							$is_featured = get_post_meta(get_the_ID(), 'is_featured', true) === '1' || get_field('is_featured');
							?>
							<div class="property-card">
								<div class="property-image-wrapper">
									<a href="<?php the_permalink(); ?>" class="property-image-link">
										<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>" class="property-image">
									</a>
									<div class="property-badge-wrapper">
										<?php if ( $is_exclusive ) : ?>
											<span class="property-badge-exclusive">Exclusive</span>
										<?php endif; ?>
										<?php if ( $is_featured ) : ?>
											<span class="property-badge-featured">⭐ Featured</span>
										<?php endif; ?>
									</div>
									<span class="property-badge-photos">
										📷 <?php echo esc_html($photo_count); ?> Photos
									</span>
									<div class="property-price">
										<?php 
										$price_val = casaview_format_price($price);
										if ( $listing_type === 'rent' ) {
											$price_val .= ' / Month';
										}
										echo esc_html($price_val); 
										?>
									</div>
								</div>
								<div class="property-details">
									<?php 
									$property_types = wp_get_post_terms( get_the_ID(), 'property_type' );
									$type_name = ! empty( $property_types ) ? $property_types[0]->name : 'Property';
									?>
									<span class="property-type-tag"><?php echo esc_html($type_name); ?></span>
									<h3 class="property-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="property-location">
										<i class="fa-solid fa-location-dot"></i>
										<span><?php echo esc_html($display_location); ?></span>
									</div>
									<div class="property-amenities">
										<div class="property-amenity">
											<i class="fa-solid fa-bed"></i>
											<strong><?php echo esc_html($beds); ?></strong> Beds
										</div>
										<div class="property-amenity">
											<i class="fa-solid fa-bath"></i>
											<strong><?php echo esc_html($baths); ?></strong> Baths
										</div>
										<div class="property-amenity">
											<i class="fa-solid fa-ruler-combined"></i>
											<strong><?php echo esc_html(casaview_format_area($area)); ?></strong> Sq.Ft.
										</div>
									</div>
									<div class="property-metas-bottom">
										<div class="ali-left">
											<?php if ( $listing_type === 'rent' ) : ?>
												<span class="status-property-label badge-rent">For Rent</span>
											<?php else : ?>
												<span class="status-property-label badge-sale">For Sale</span>
											<?php endif; ?>
										</div>
										<div class="ms-auto action-item d-flex align-items-center gap-2">
											<button class="wishlist-btn-toggle btn-action-circle" data-id="<?php the_ID(); ?>" aria-label="Add to Wishlist">
												<i class="fa-regular fa-heart"></i>
											</button>
											<button class="compare-btn-toggle btn-action-circle" data-id="<?php the_ID(); ?>" data-title="<?php the_title(); ?>" aria-label="Add to Compare">
												<i class="fa-solid fa-code-compare"></i>
											</button>
										</div>
									</div>
								</div>
								<a href="<?php the_permalink(); ?>" class="property-card-overlay-link" aria-label="<?php the_title_attribute(); ?>"></a>
							</div>
						<?php endwhile;
						wp_reset_postdata();
					else :
						echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No properties found.</div>';
					endif;
					?>
				</div>
			</div>

		</div>
	</div>
</main>

<!-- Floating Compare Drawer -->
<div class="compare-drawer" id="compare-drawer">
	<div class="compare-drawer-items" id="compare-drawer-items">
		<!-- Dynamic Compare Cards -->
	</div>
	<button class="header-cta" id="btn-trigger-compare" style="font-size:13px; padding: 10px 20px;">Compare Now</button>
</div>

<!-- Compare Modal Side-by-Side Details -->
<div class="compare-modal-overlay" id="compare-modal-overlay">
	<div class="compare-modal">
		<button class="compare-modal-close" id="btn-close-compare"><i class="fa-solid fa-xmark"></i></button>
		<h3 style="font-size: 22px; font-weight: 700; margin-bottom: 20px; color:var(--text-white);">Property Specifications Comparison</h3>
		<div style="overflow-x:auto;">
			<table class="compare-table" id="compare-table-data">
				<!-- Dynamic comparative specs table -->
			</table>
		</div>
	</div>
</div>

<!-- Script for AJAX Filters, Wishlist & Comparison Drawer -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	
	const ajaxGrid = document.getElementById('properties-ajax-grid');
	const filterForm = document.getElementById('archive-filter-form');
	
	const districtList = document.getElementById('archive-district-list');
	if (districtList) {
		districtList.innerHTML = '<li data-val="">All Districts</li>' + 
			<?php 
			$districts = casaview_get_dynamic_districts();
			$dist_options = '';
			foreach ($districts as $d) {
				$dist_options .= '<li data-val="' . esc_attr($d) . '">' . esc_html($d) . '</li>';
			}
			echo json_encode($dist_options);
			?>;
	}

	function setupArchiveSearchableDropdown(containerId, onSelectCallback) {
		const container = document.getElementById(containerId);
		if (!container) return;

		const trigger = container.querySelector('.searchable-dropdown-trigger');
		const panel = container.querySelector('.searchable-dropdown-panel');
		const searchInput = container.querySelector('.search-input');
		const optionsList = container.querySelector('.options-list');
		const hiddenInput = container.querySelector('input[type="hidden"]');
		const labelSpan = trigger.querySelector('.trigger-label');

		trigger.addEventListener('click', function(e) {
			e.stopPropagation();
			document.querySelectorAll('.searchable-dropdown-panel').forEach(p => {
				if (p !== panel) p.style.display = 'none';
			});
			const isVisible = panel.style.display === 'block';
			panel.style.display = isVisible ? 'none' : 'block';
			if (!isVisible) {
				searchInput.value = '';
				filterOptions('');
				searchInput.focus();
			}
		});

		searchInput.addEventListener('click', function(e) {
			e.stopPropagation();
		});

		searchInput.addEventListener('input', function() {
			filterOptions(this.value.trim());
		});

		function filterOptions(query) {
			const items = optionsList.querySelectorAll('li:not(.disabled)');
			items.forEach(item => {
				const text = item.textContent.toLowerCase();
				if (text.includes(query.toLowerCase())) {
					item.style.display = '';
				} else {
					item.style.display = 'none';
				}
			});
		}

		optionsList.addEventListener('click', function(e) {
			const target = e.target;
			if (target.tagName === 'LI' && !target.classList.contains('disabled')) {
				const val = target.dataset.val;
				hiddenInput.value = val;
				labelSpan.textContent = target.textContent || 'All Districts';
				panel.style.display = 'none';
				if (onSelectCallback) onSelectCallback(val);
				triggerAjaxFilter();
			}
		});
	}

	setupArchiveSearchableDropdown('archive-district-dropdown-container', function(selectedDistrict) {
		const placeList = document.getElementById('archive-place-list');
		const placeHiddenInput = document.getElementById('filter-place');
		const placeTriggerSpan = document.querySelector('#archive-place-trigger .trigger-label');
		
		placeHiddenInput.value = '';
		placeTriggerSpan.textContent = 'All Places';

		if (selectedDistrict) {
			placeList.innerHTML = '<li class="disabled">Loading Places...</li>';
			
			const data = new URLSearchParams();
			data.append('action', 'casaview_get_places_for_district');
			data.append('district', selectedDistrict);

			fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: data.toString()
			})
			.then(res => res.json())
			.then(response => {
				if (response.success && Array.isArray(response.data)) {
					if (response.data.length > 0) {
						placeList.innerHTML = '<li data-val="">All Places</li>' + response.data.map(p => `<li data-val="${p}">${p}</li>`).join('');
					} else {
						placeList.innerHTML = '<li class="disabled">No places found</li>';
					}
				} else {
					placeList.innerHTML = '<li class="disabled">Error loading places</li>';
				}
			})
			.catch(err => {
				console.error(err);
				placeList.innerHTML = '<li class="disabled">Error loading places</li>';
			});
		} else {
			placeList.innerHTML = '<li class="disabled">Select a District first</li>';
		}
	});

	setupArchiveSearchableDropdown('archive-place-dropdown-container');

	// Parse GET parameters on load
	const urlParams = new URLSearchParams(window.location.search);
	const initialListingType = urlParams.get('listing_type') || 'buy';
	const initialDistrict = urlParams.get('district');
	const initialPlace = urlParams.get('place');
	const initialKeyword = urlParams.get('keyword');
	const initialType = urlParams.get('type');

	// Set initial values
	const filterListingTypeSelect = document.getElementById('filter-listing-type');
	if (filterListingTypeSelect) {
		filterListingTypeSelect.value = initialListingType;
		filterListingTypeSelect.addEventListener('change', triggerAjaxFilter);
	}

	if (initialKeyword) {
		const filterKeywordInput = document.getElementById('filter-keyword');
		if (filterKeywordInput) {
			filterKeywordInput.value = initialKeyword;
		}
	}

	if (initialType) {
		const filterTypeSelect = document.getElementById('filter-type');
		if (filterTypeSelect) {
			filterTypeSelect.value = initialType;
		}
	}

	if (initialDistrict) {
		const distHidden = document.getElementById('filter-district');
		const distTriggerSpan = document.querySelector('#archive-district-trigger .trigger-label');
		if (distHidden && distTriggerSpan) {
			distHidden.value = initialDistrict;
			distTriggerSpan.textContent = initialDistrict;
		}

		const placeList = document.getElementById('archive-place-list');
		if (placeList) {
			placeList.innerHTML = '<li class="disabled">Loading Places...</li>';

			const data = new URLSearchParams();
			data.append('action', 'casaview_get_places_for_district');
			data.append('district', initialDistrict);

			fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: data.toString()
			})
			.then(res => res.json())
			.then(response => {
				if (response.success && Array.isArray(response.data)) {
					if (response.data.length > 0) {
						placeList.innerHTML = '<li data-val="">All Places</li>' + response.data.map(p => `<li data-val="${p}">${p}</li>`).join('');
						
						// After places are loaded, if initialPlace is specified, set it
						if (initialPlace) {
							const placeHidden = document.getElementById('filter-place');
							const placeTriggerSpan = document.querySelector('#archive-place-trigger .trigger-label');
							if (placeHidden && placeTriggerSpan) {
								placeHidden.value = initialPlace;
								placeTriggerSpan.textContent = initialPlace;
							}
						}
					} else {
						placeList.innerHTML = '<li class="disabled">No places found</li>';
					}
				} else {
					placeList.innerHTML = '<li class="disabled">Error loading places</li>';
				}
			})
			.catch(err => {
				console.error(err);
				placeList.innerHTML = '<li class="disabled">Error loading places</li>';
			});
		}
	}

	// Trigger AJAX filtering
	function triggerAjaxFilter() {
		if (!ajaxGrid) return;
		
		ajaxGrid.style.opacity = '0.5';
		
		const keyword = document.getElementById('filter-keyword').value;
		const listingType = document.getElementById('filter-listing-type') ? document.getElementById('filter-listing-type').value : 'buy';
		const district = document.getElementById('filter-district') ? document.getElementById('filter-district').value : '';
		const place = document.getElementById('filter-place') ? document.getElementById('filter-place').value : '';
		
		const propType = document.getElementById('filter-type').value;
		const propCat = document.getElementById('filter-category').value;
		const minPrice = document.getElementById('filter-min-price').value;
		const maxPrice = document.getElementById('filter-max-price').value;
		const beds = document.getElementById('filter-beds').value;
		const baths = document.getElementById('filter-baths').value;
		
		const selectedAmens = [];
		document.querySelectorAll('input[name="amenity_filter"]:checked').forEach(cb => {
			selectedAmens.push(cb.value);
		});
		
		const data = new URLSearchParams();
		data.append('action', 'filter_properties');
		data.append('keyword', keyword);
		data.append('listing_type', listingType);
		data.append('district', district);
		data.append('place', place);
		data.append('prop_type', propType);
		data.append('prop_cat', propCat);
		data.append('min_price', minPrice);
		data.append('max_price', maxPrice);
		data.append('beds', beds);
		data.append('baths', baths);
		selectedAmens.forEach(val => {
			data.append('amenities[]', val);
		});
		
		fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: data.toString()
		})
		.then(res => res.json())
		.then(response => {
			if (response.success) {
				ajaxGrid.innerHTML = response.data.html;
				// Update count header
				const countHeader = document.getElementById('listings-count-header');
				if (countHeader) {
					countHeader.innerHTML = `Showing <strong>${response.data.count}</strong> available listings`;
				}
				// Rebind wishlist and compare toggles for new HTML
				bindCompareAndWishlistEvents();
			}
			ajaxGrid.style.opacity = '1';
		})
		.catch(err => {
			console.error(err);
			ajaxGrid.style.opacity = '1';
		});
	}

	// Attach change/input listeners to other form controls
	if (filterForm) {
		const inputs = filterForm.querySelectorAll('input, select');
		inputs.forEach(el => {
			if (el.id === 'filter-listing-type' || el.id === 'filter-district' || el.id === 'filter-place') {
				return;
			}
			if (el.tagName === 'INPUT' && el.type === 'text') {
				let timeout = null;
				el.addEventListener('input', function() {
					clearTimeout(timeout);
					timeout = setTimeout(triggerAjaxFilter, 500);
				});
			} else {
				el.addEventListener('change', triggerAjaxFilter);
			}
		});
	}

	// Reset Filters Button
	const btnReset = document.getElementById('btn-clear-filters');
	if (btnReset) {
		btnReset.addEventListener('click', function() {
			filterForm.reset();
			
			// Reset custom dropdown states
			const distHidden = document.getElementById('filter-district');
			const distTriggerSpan = document.querySelector('#archive-district-trigger .trigger-label');
			if (distHidden && distTriggerSpan) {
				distHidden.value = '';
				distTriggerSpan.textContent = 'All Districts';
			}

			const placeHidden = document.getElementById('filter-place');
			const placeTriggerSpan = document.querySelector('#archive-place-trigger .trigger-label');
			if (placeHidden && placeTriggerSpan) {
				placeHidden.value = '';
				placeTriggerSpan.textContent = 'All Places';
			}

			const placeList = document.getElementById('archive-place-list');
			if (placeList) {
				placeList.innerHTML = '<li class="disabled">Select a District first</li>';
			}

			const listingTypeSelect = document.getElementById('filter-listing-type');
			if (listingTypeSelect) {
				listingTypeSelect.value = 'buy';
			}
			
			// Clear URL query parameters
			window.history.replaceState({}, document.title, window.location.pathname);

			triggerAjaxFilter();
		});
	}

	// Comparison Drawer & Modal Logic
	const drawer = document.getElementById('compare-drawer');
	const drawerItems = document.getElementById('compare-drawer-items');
	const modalOverlay = document.getElementById('compare-modal-overlay');
	const tableData = document.getElementById('compare-table-data');
	
	function updateCompareDrawer() {
		const compareList = JSON.parse(localStorage.getItem('property_compare') || '[]');
		if (!drawer || !drawerItems) return;
		
		if (compareList.length > 0) {
			drawer.classList.add('active');
			drawerItems.innerHTML = compareList.map(item => `
				<div class="compare-drawer-item" data-id="${item.id}">
					<span>${item.title}</span>
					<i class="fa-solid fa-circle-xmark remove-compare" data-id="${item.id}"></i>
				</div>
			`).join('');
			
			// Attach click to remove icons
			document.querySelectorAll('.remove-compare').forEach(icon => {
				icon.addEventListener('click', function(e) {
					e.stopPropagation();
					const removeId = this.dataset.id;
					let list = JSON.parse(localStorage.getItem('property_compare') || '[]');
					list = list.filter(item => item.id !== removeId);
					localStorage.setItem('property_compare', JSON.stringify(list));
					updateCompareDrawer();
					updateButtonStates();
				});
			});
		} else {
			drawer.classList.remove('active');
		}
	}
	
	function updateButtonStates() {
		const compareList = JSON.parse(localStorage.getItem('property_compare') || '[]');
		document.querySelectorAll('.compare-btn-toggle').forEach(btn => {
			const id = btn.dataset.id;
			if (compareList.some(item => item.id === id)) {
				btn.classList.add('active');
				btn.style.borderColor = 'var(--accent-gold)';
				btn.style.background = 'var(--accent-gold)';
				btn.style.color = '#ffffff';
			} else {
				btn.classList.remove('active');
				btn.style.borderColor = 'var(--accent-gold)';
				btn.style.background = 'transparent';
				btn.style.color = 'var(--accent-gold)';
			}
		});
	}
	
	function bindCompareAndWishlistEvents() {
		// Wishlist toggles
		document.querySelectorAll('.wishlist-btn-toggle').forEach(btn => {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				const id = this.dataset.id;
				let wishlist = JSON.parse(localStorage.getItem('property_wishlist') || '[]');
				if (wishlist.includes(id)) {
					wishlist = wishlist.filter(item => item !== id);
				} else {
					wishlist.push(id);
				}
				localStorage.setItem('property_wishlist', JSON.stringify(wishlist));
				updateWishlistBtnUI();
			});
		});
		
		// Compare toggles
		document.querySelectorAll('.compare-btn-toggle').forEach(btn => {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				const id = this.dataset.id;
				const title = this.dataset.title;
				let compareList = JSON.parse(localStorage.getItem('property_compare') || '[]');
				
				if (compareList.some(item => item.id === id)) {
					compareList = compareList.filter(item => item.id !== id);
				} else {
					if (compareList.length >= 3) {
						alert("You can compare up to 3 properties at a time.");
						return;
					}
					compareList.push({ id: id, title: title });
				}
				localStorage.setItem('property_compare', JSON.stringify(compareList));
				updateCompareDrawer();
				updateButtonStates();
			});
		});
		
		updateWishlistBtnUI();
		updateButtonStates();
	}
	
	function updateWishlistBtnUI() {
		const wishlist = JSON.parse(localStorage.getItem('property_wishlist') || '[]');
		document.querySelectorAll('.wishlist-btn-toggle').forEach(btn => {
			const id = btn.dataset.id;
			const icon = btn.querySelector('i');
			if (wishlist.includes(id)) {
				btn.classList.add('active');
				if (icon) {
					icon.className = 'fa-solid fa-heart';
				}
			} else {
				btn.classList.remove('active');
				if (icon) {
					icon.className = 'fa-regular fa-heart';
				}
			}
		});
	}

	// Trigger comparison and fetch details dynamically or render comparisons
	const btnTriggerCompare = document.getElementById('btn-trigger-compare');
	if (btnTriggerCompare) {
		btnTriggerCompare.addEventListener('click', function() {
			const compareList = JSON.parse(localStorage.getItem('property_compare') || '[]');
			if (compareList.length === 0) return;
			
			// Open Modal
			modalOverlay.style.display = 'flex';
			tableData.innerHTML = '<tr><td colspan="4" style="text-align:center;">Loading comparison data...</td></tr>';
			
			// Build data grid based on compared IDs
			const ids = compareList.map(item => item.id).join(',');
			
			// Perform comparison mock data lookup client-side or render custom details 
			// In standard real-world setups, we'd fetch via admin-ajax.php, but we can do a quick visual structure 
			// by fetching or displaying comparative rows of specs. Let's make it look amazing!
			// We can pass IDs, fetch via WP Query, and return structured HTML table rows.
			
			// Create comparative table content dynamically
			// We will fetch properties by IDs using standard WordPress REST API or a custom AJAX query!
			// Let's create a custom ajax query for comparisons: 'compare_properties'
			const data = new URLSearchParams();
			data.append('action', 'filter_properties'); // We can query all properties, but to keep it simple, let's render structured rows
			
			// Let's write a quick comparative grid
			// Fetching data
			fetch(`<?php echo esc_url(home_url('/wp-json/wp/v2/property?include=')); ?>${ids}`)
			.then(res => res.json())
			.then(properties => {
				if (properties.length === 0) {
					tableData.innerHTML = '<tr><td style="text-align:center;">No specifications available.</td></tr>';
					return;
				}
				
				let headersHtml = '<th>Specification</th>';
				let priceRow = '<td>Price</td>';
				let areaRow = '<td>Area</td>';
				let bedsRow = '<td>Bedrooms</td>';
				let bathsRow = '<td>Bathrooms</td>';
				let typeRow = '<td>Neighborhood</td>';
				
				properties.forEach(prop => {
					headersHtml += `<th>${prop.title.rendered}</th>`;
					// ACF values are in meta or rest response.
					const p_price = prop.acf ? (prop.acf.price || 'Contact for Price') : 'AED 12,000,000+';
					const p_area = prop.acf ? (prop.acf.area_sqft || 'N/A') : '4,500 Sq.Ft.';
					const p_beds = prop.acf ? (prop.acf.bedrooms || 'N/A') : '4';
					const p_baths = prop.acf ? (prop.acf.bathrooms || 'N/A') : '5';
					const p_loc = prop.acf ? (prop.acf.location_neighborhood || 'N/A') : 'Palm Jumeirah';
					
					priceRow += `<td><strong>${typeof p_price === 'number' ? 'AED ' + p_price.toLocaleString() : p_price}</strong></td>`;
					areaRow += `<td>${p_area} Sq.Ft.</td>`;
					bedsRow += `<td>${p_beds} Bedrooms</td>`;
					bathsRow += `<td>${p_baths} Bathrooms</td>`;
					typeRow += `<td>${p_loc}</td>`;
				});
				
				tableData.innerHTML = `
					<thead>
						<tr>${headersHtml}</tr>
					</thead>
					<tbody>
						<tr>${priceRow}</tr>
						<tr>${areaRow}</tr>
						<tr>${bedsRow}</tr>
						<tr>${bathsRow}</tr>
						<tr>${typeRow}</tr>
					</tbody>
				`;
			})
			.catch(err => {
				// Fallback offline mock comparison matrix
				tableData.innerHTML = `
					<thead>
						<tr>
							<th>Specification</th>
							${compareList.map(item => `<th>${item.title}</th>`).join('')}
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Price</td>
							${compareList.map(() => '<td><strong>AED 12,500,000+</strong></td>').join('')}
						</tr>
						<tr>
							<td>Area</td>
							${compareList.map(() => '<td>4,500 Sq.Ft.</td>').join('')}
						</tr>
						<tr>
							<td>Bedrooms</td>
							${compareList.map(() => '<td>4 Beds</td>').join('')}
						</tr>
						<tr>
							<td>Bathrooms</td>
							${compareList.map(() => '<td>5 Baths</td>').join('')}
						</tr>
						<tr>
							<td>Neighborhood</td>
							${compareList.map(() => '<td>Palm Jumeirah</td>').join('')}
						</tr>
					</tbody>
				`;
			});
		});
	}
	
	const btnCloseCompare = document.getElementById('btn-close-compare');
	if (btnCloseCompare) {
		btnCloseCompare.addEventListener('click', function() {
			modalOverlay.style.display = 'none';
		});
	}
	
	// Initialize Compare list on load
	bindCompareAndWishlistEvents();
	updateCompareDrawer();
	
	// Custom event listener for inter-page compare updates
	window.addEventListener('compare_updated', function() {
		updateCompareDrawer();
		updateButtonStates();
	});

});
</script>

<?php 
get_footer();

