<?php
/**
 * Template Name: All Properties Page
 *
 * Displays all published properties with a full-width interactive map and floating search filters.
 */

get_header();
?>

<style>
	/* All Properties Page Container & Wrapper */
	.all-properties-wrapper {
		background-color: var(--bg-primary, #0b0c10);
		color: var(--text-white, #ffffff);
		font-family: var(--font-en, 'Manrope', sans-serif);
		min-height: 100vh;
		position: relative;
	}

	/* Map Section Layout */
	.all-properties-map-section {
		position: relative;
		width: 100%;
		height: 380px; /* Desktop: 380px */
		margin-top: 0px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		background: #000;
	}
	#all-properties-map {
		width: 100%;
		height: 100%;
		z-index: 1;
	}

	/* Floating Search Panel styling */
	.floating-search-panel {
		position: absolute;
		bottom: -40px; /* Overlaps bottom edge of the map */
		left: 50%;
		transform: translateX(-50%);
		z-index: 999;
		width: 90%;
		max-width: 1100px;
		box-sizing: border-box;
		background: #ffffff;
		border-radius: 16px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
		padding: 20px 24px;
	}

	.floating-search-panel .modern-tabs {
		display: flex;
		gap: 20px;
		margin-bottom: 15px;
		border-bottom: 1px solid #f0f0f2;
		padding-bottom: 5px;
	}
	.floating-search-panel .modern-tab {
		background: transparent !important;
		border: none !important;
		padding: 10px 20px 14px 20px !important;
		font-size: 15px !important;
		font-weight: 600 !important;
		color: rgb(96 96 96 / 75%) !important;
		cursor: pointer !important;
		position: relative !important;
		transition: color 0.3s ease !important;
		font-family: var(--font-en) !important;
	}
	.floating-search-panel .modern-tab.active,
	.floating-search-panel .modern-tab:hover {
		color: #181a20;
	}
	.floating-search-panel .modern-tab.active::after {
		content: '';
		position: absolute;
		bottom: -6px;
		left: 0;
		width: 100%;
		height: 3px;
		background-color: #181a20;
		border-radius: 2px;
	}

	.floating-search-panel .hero-filter-bar {
		background: transparent !important;
		border-radius: 0 !important;
		padding: 0 !important;
		box-shadow: none !important;
		position: relative;
		z-index: 1;
	}
	
	.floating-search-panel .filter-inputs-row {
		display: flex;
		align-items: center;
		gap: 12px;
		width: 100%;
	}

	.floating-search-panel .keyword-input-wrapper {
		position: relative;
		flex: 1;
		background-color: #f7f7f9;
		border-radius: 10px;
		height: 48px;
		display: flex;
		align-items: center;
		padding: 0 16px;
		box-sizing: border-box;
	}
	.floating-search-panel .keyword-input-wrapper .search-icon-left {
		color: #888888;
		font-size: 16px;
		pointer-events: none;
	}
	.floating-search-panel .hero-filter-input {
		color: #1c1d21 !important;
		font-size: 15px !important;
		font-weight: 500 !important;
		background: transparent !important;
		border: none !important;
		padding: 0 0 0 10px !important;
		font-size: 14px !important;
		outline: none;
		height: 100% !important;
		width: 100%;
		box-sizing: border-box;
	}
	.floating-search-panel .hero-filter-select {
		color: #1c1d21 !important;
		border: none !important;
		background-color: #f7f7f9 !important;
		border-radius: 10px !important;
		font-size: 14px !important;
		font-weight: 600 !important;
		height: 48px !important;
		padding: 0 18px !important;
		width: 240px;
		cursor: pointer;
		outline: none;
		box-sizing: border-box;
	}
	.floating-search-panel .hero-filter-select:disabled {
		opacity: 0.6;
		cursor: not-allowed;
	}

	.floating-search-panel .hero-filter-btn-group {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.floating-search-panel .hero-filter-advanced {
		background: transparent;
		border: none;
		color: #181a20;
		width: 48px;
		height: 48px;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		transition: all 0.3s;
		padding: 0;
	}
	.floating-search-panel .hero-filter-advanced:hover,
	.floating-search-panel .hero-filter-advanced.active {
		color: var(--accent-gold, #c5a880);
	}

	.floating-search-panel .hero-filter-search {
		background: #f25b38;
		color: #fff;
		border: none;
		width: 48px;
		height: 48px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.3s;
		padding: 0;
	}
	.floating-search-panel .hero-filter-search:hover {
		background: #e04f2f;
		transform: scale(1.03);
	}

	/* Advanced Collapsible Panel Styling */
	.floating-search-panel .modern-advanced-panel {
		position: absolute;
		top: 100%;
		left: 0;
		right: 0;
		background: rgba(255, 255, 255, 0.98);
		backdrop-filter: blur(20px);
		-webkit-backdrop-filter: blur(20px);
		border: 1px solid rgba(0, 0, 0, 0.05);
		border-radius: 16px;
		padding: 24px;
		margin-top: 10px;
		box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
		z-index: 1000;
		opacity: 0;
		visibility: hidden;
		transform: translateY(-10px);
		transition: all 0.3s ease;
	}
	.floating-search-panel .modern-advanced-panel.show-panel {
		opacity: 1;
		visibility: visible;
		transform: translateY(0);
	}
	.floating-search-panel .advanced-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 16px;
	}
	.floating-search-panel .advanced-field-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}
	.floating-search-panel .advanced-field-group label {
		font-family: var(--font-en) !important;
		font-size: 11px !important;
		font-weight: 700 !important;
		letter-spacing: 1.5px !important;
		color: rgb(74 74 74) !important;
		text-transform: uppercase !important;
		margin-bottom: 8px !important;
	}
	.floating-search-panel .modern-select,
	.floating-search-panel .modern-text-input {
		width: 100% !important;
		background: rgba(255, 255, 255, 0.05) !important;
		border: 1px solid #c5c5c5 !important;
		border-radius: 14px !important;
		color: #707070 !important;
		padding: 15px 20px !important;
		font-size: 14px !important;
		font-weight: 500 !important;
		outline: none !important;
		cursor: pointer !important;
		transition: all 0.3s !important;
		font-family: var(--font-en) !important;
		height: 56px !important;
		box-sizing: border-box !important;
		appearance: none !important;
		-webkit-appearance: none !important;
		background-repeat: no-repeat !important;
		background-position: right 16px center !important;
		background-size: 16px !important;
		padding-right: 40px !important;
	}
	.floating-search-panel .modern-select {
		background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23C9A96E' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>") !important;
	}
	.floating-search-panel .modern-select option {
		background: #1c1d21;
		color: #fff;
	}
	.floating-search-panel .modern-select:focus,
	.floating-search-panel .modern-text-input:focus {
		border-color: var(--accent-gold, #c5a880);
	}

	/* Content Grid & Layout styling */
	.all-properties-content-wrapper {
		padding: 60px 0 80px 0;
	}

	/* Responsive: Mobile & Tablet only */
	@media (max-width: 1024px) {
		.all-properties-content-wrapper {
			padding: 320px 0 80px 0;
		}
	}
	.all-properties-content-wrapper .container {
		max-width: 94%;
		margin: 0 auto;
		padding: 0 15px;
		box-sizing: border-box;
	}

	.all-properties-results-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1px solid rgba(255, 255, 255, 0.08);
		padding-bottom: 20px;
		margin-bottom: 40px;
	}
	.all-properties-results-header .results-count {
		font-size: 16px;
		font-weight: 600;
		color: var(--text-white, #ffffff);
	}
	.all-properties-results-header .results-sorting {
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.all-properties-results-header .results-sorting label {
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		color: var(--text-muted, #888888);
	}
	.all-properties-results-header .sort-select {
		background: rgba(255, 255, 255, 0.05);
		border: 1px solid rgb(205 205 205);
		color: #949ba5;
		font-size: 14px;
		font-weight: 600;
		padding: 8px 16px;
		border-radius: 8px;
		cursor: pointer;
		outline: none;
	}
	.all-properties-results-header .sort-select option {
		background: #1c1d21;
		color: #fff;
	}

	.archive-properties-grid {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 30px;
	}
	@media (max-width: 1024px) {
		.archive-properties-grid {
			grid-template-columns: repeat(2, 1fr);
		}

		/* Results header responsive overrides */
		.all-properties-results-header {
			display: flex;
			gap: 15px;
			align-items: center;
		}
		.all-properties-results-header .results-count {
			font-size: 12px;
			font-weight: 600;
			color: var(--text-white, #ffffff);
		}
		.all-properties-results-header .results-sorting label {
			font-size: 12px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			color: var(--text-muted, #888888);
		}
	}
	@media (max-width: 768px) {
		.archive-properties-grid {
			grid-template-columns: 1fr;
		}
	}

	/* Card layouts inside Content Grid */
	.all-properties-wrapper .property-card {
		background: #ffffff;
		border: 1px solid rgba(0, 0, 0, 0.05);
		border-radius: 24px;
		overflow: hidden;
		position: relative;
		display: flex;
		flex-direction: column;
		height: 100%;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
		transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
	}
	.all-properties-wrapper .property-card:hover {
		transform: translateY(-6px);
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
		border-color: var(--accent-gold, #c5a880);
	}
	.all-properties-wrapper .property-image-wrapper {
		position: relative;
		height: 250px;
		overflow: hidden;
		border-radius: 24px 24px 0 0;
	}
	.all-properties-wrapper .property-image {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}
	.all-properties-wrapper .property-card:hover .property-image {
		transform: scale(1.05);
	}
	
	.all-properties-wrapper .property-status-badge {
		position: relative;
		top: 18px;
		left: 18px;
		z-index: 3;
		background: var(--primary-color)  !important;
		color: #ffffff !important;
		padding: 6px 12px !important;
		font-size: 11px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		border-radius: 4px !important;
		letter-spacing: 0.5px !important;
		box-shadow: none !important;
	}

	.all-properties-wrapper .property-image-actions {
		position: absolute;
		bottom: 18px;
		right: 18px;
		z-index: 3;
	}
	.all-properties-wrapper .btn-image-action {
		width: 42px;
		height: 42px;
		border-radius: 10px;
		background: #ffffff;
		color: #181a20;
		display: flex;
		align-items: center;
		justify-content: center;
		text-decoration: none;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		transition: all 0.2s ease;
	}
	.all-properties-wrapper .btn-image-action:hover {
		background: var(--accent-gold, #c5a880);
		color: #ffffff;
	}

	.all-properties-wrapper .property-details {
		padding: 24px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		background: #ffffff;
	}
	.all-properties-wrapper .property-title {
		font-size: 18px;
		font-weight: 700;
		line-height: 1.4;
		margin-bottom: 10px;
		height: 50px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
	}
	.all-properties-wrapper .property-title a {
		color: #181a20;
		text-decoration: none;
		transition: color 0.3s ease;
	}
	.all-properties-wrapper .property-title a:hover {
		color: var(--accent-gold, #c5a880);
	}
	
	.all-properties-wrapper .property-price-row {
		font-size: 20px;
		font-weight: 800;
		color: var(--accent-gold, #c5a880);
		margin-bottom: 12px;
	}

	.all-properties-wrapper .property-location {
		display: flex;
		align-items: center;
		font-size: 14px;
		color: #717171;
		gap: 6px;
		margin-bottom: 20px;
		padding-bottom: 20px;
		border-bottom: 1px solid #f0f0f2;
	}
	.all-properties-wrapper .property-location i {
		color: var(--accent-gold, #c5a880);
	}

	.all-properties-wrapper .property-amenities-boxes {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 10px;
		margin-bottom: 24px;
	}
	.all-properties-wrapper .amenity-box {
		background: #f7f7f9;
		border-radius: 8px;
		padding: 12px 8px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 6px;
		text-align: center;
	}
	.all-properties-wrapper .amenity-box i {
		color: var(--accent-gold, #c5a880);
		font-size: 16px;
	}
	.all-properties-wrapper .amenity-text {
		font-size: 11px;
		color: #717171;
		text-transform: uppercase;
		font-weight: 600;
		display: flex;
		gap: 5px;
	}
	.all-properties-wrapper .amenity-text strong {
		color: #181a20;
		font-size: 12px;
		display: block;
		margin-bottom: 2px;
	}

	.all-properties-wrapper .property-card-bottom {
		margin-top: auto;
		display: flex;
		align-items: center;
		justify-content: flex-start;
	}
	.all-properties-wrapper .btn-view-details {
		display: inline-block;
		border: 1px solid var(--accent-gold, #c5a880);
		color: var(--accent-gold, #c5a880);
		font-size: 14px;
		font-weight: 700;
		padding: 10px 24px;
		border-radius: 8px;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	.all-properties-wrapper .btn-view-details:hover {
		background: var(--accent-gold, #c5a880);
		color: #ffffff;
	}
	.all-properties-wrapper .btn-action-circle {
		width: 42px;
		height: 42px;
		border-radius: 10px;
		background: #f7f7f9;
		color: #1c1d21;
		border: none;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		padding: 0;
	}
	.all-properties-wrapper .btn-action-circle:hover {
		background: #f25b38;
		color: #ffffff;
	}

	/* Pagination Styles */
	.pagination-wrapper {
		display: flex;
		justify-content: center;
		gap: 8px;
		margin-top: 50px;
	}
	.pagination-wrapper .page-numbers {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 40px;
		height: 40px;
		padding: 0 6px;
		border-radius: 8px;
		border: 1px solid rgba(255, 255, 255, 0.15);
		color: #fff;
		text-decoration: none;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s;
		background: rgba(255, 255, 255, 0.05);
	}
	.pagination-wrapper .page-numbers:hover {
		border-color: var(--accent-gold, #c5a880);
		color: var(--accent-gold, #c5a880);
		background: rgba(255, 255, 255, 0.08);
	}
	.pagination-wrapper .page-numbers.current {
		background: var(--accent-gold, #c5a880);
		border-color: var(--accent-gold, #c5a880);
		color: #fff;
	}
	.pagination-wrapper .page-numbers.dots {
		background: transparent;
		border: none;
		color: #888;
	}

	/* Leaflet Custom Map Popups style */
	.all-properties-map-section .leaflet-popup-content-wrapper {
		border-radius: 12px !important;
		padding: 0 !important;
		overflow: hidden !important;
		background: #1a1b1f !important;
		color: #fff !important;
		border: 1px solid rgba(255, 255, 255, 0.1) !important;
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
	}
	.all-properties-map-section .leaflet-popup-content {
		margin: 0 !important;
		width: 250px !important;
	}
	.leaflet-popup-card {
		display: flex;
		flex-direction: column;
		font-family: 'Manrope', sans-serif !important;
	}
	.leaflet-popup-card img {
		width: 100%;
		height: 140px;
		object-fit: cover;
		display: block;
	}
	.leaflet-popup-card-details {
		padding: 14px;
	}
	.leaflet-popup-card-title {
		font-size: 14px;
		font-weight: 700;
		color: #fff;
		margin: 0 0 6px 0 !important;
		line-height: 1.4;
	}
	.leaflet-popup-card-title a {
		color: #fff;
		text-decoration: none;
		transition: color 0.3s;
	}
	.leaflet-popup-card-title a:hover {
		color: var(--accent-gold, #c5a880);
	}
	.leaflet-popup-card-price {
		font-size: 16px;
		font-weight: 800;
		color: var(--accent-gold, #c5a880);
		margin-bottom: 6px;
	}
	.leaflet-popup-card-location {
		font-size: 11px;
		color: #888;
		display: flex;
		align-items: center;
		gap: 5px;
	}
	.leaflet-popup-card-location i {
		color: var(--accent-gold, #c5a880);
	}
	.leaflet-popup-card-btn {
		display: block;
		text-align: center;
		background: var(--accent-gold, #c5a880) !important;
		color: #1a1b1f !important;
		font-weight: 700 !important;
		font-size: 11px !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px;
		padding: 8px 12px !important;
		border-radius: 6px !important;
		text-decoration: none !important;
		margin-top: 12px !important;
		transition: background 0.3s, transform 0.2s;
	}
	.leaflet-popup-card-btn:hover {
		background: #d4b88f !important;
		transform: translateY(-1px);
	}

	.leaflet-control-zoom.leaflet-bar.leaflet-control {
		position: absolute;
		top: 64px;
	}

	/* Loader Overlay */
	.grid-loading-overlay {
		position: relative;
	}
	.grid-loading-overlay::after {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(11, 12, 16, 0.7);
		backdrop-filter: blur(2px);
		z-index: 10;
		border-radius: 12px;
	}
	.grid-loading-spinner {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		width: 50px;
		height: 50px;
		border: 4px solid rgba(255, 255, 255, 0.1);
		border-top-color: var(--accent-gold, #c5a880);
		border-radius: 50%;
		animation: spin 1s linear infinite;
		z-index: 11;
	}
	@keyframes spin {
		to { transform: translate(-50%, -50%) rotate(360deg); }
	}

	/* Responsive Breakpoints */
	@media (max-width: 991px) {
		.all-properties-map-section {
			height: 320px; /* Tablet: 320px */
		}
		.floating-search-panel .filter-inputs-row {
			flex-wrap: wrap;
		}
		.floating-search-panel .keyword-input-wrapper {
			flex: 1 1 100% !important;
		}
		.floating-search-panel .hero-filter-select {
			flex: 1 1 calc(50% - 6px) !important;
			width: auto !important;
		}
		.floating-search-panel .hero-filter-btn-group {
			flex: 1 1 100% !important;
			justify-content: space-between;
			margin-left: 0;
		}
		.floating-search-panel .advanced-grid {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 767px) {
		.all-properties-map-section {
			height: 260px; /* Mobile: 260px */
		}
		.floating-search-panel {
			position: relative;
			transform: none;
			left: 0;
			width: calc(100% - 30px);
			margin: -130px auto 30px auto;
			padding: 24px 20px;
			box-sizing: border-box;
			border-radius: 20px;
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
			border: 1px solid rgba(255, 255, 255, 0.08);
			background: linear-gradient(145deg, #22242a, #1a1b1f);
			z-index: 10 !important;
		}
		.floating-search-panel .modern-tabs {
			justify-content: center;
			margin-bottom: 20px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		}
		.floating-search-panel .modern-tab {
			color: #a0a0a0 !important;
			font-size: 14px !important;
			padding: 10px 15px 12px 15px !important;
		}
		.floating-search-panel .modern-tab.active {
			color: #ffffff !important;
		}
		.floating-search-panel .modern-tab.active::after {
			background-color: var(--accent-gold, #c5a880);
			height: 2px;
			bottom: -1px;
		}
		.floating-search-panel .filter-inputs-row {
			flex-direction: column !important;
			align-items: stretch !important;
			gap: 16px;
			border: none;
			background: transparent;
			padding: 0;
		}
		.floating-search-panel .keyword-input-wrapper {
			border-right: none;
			padding: 0;
			background: #ffffff;
			border-radius: 12px;
			height: 56px;
			box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
			position: relative;
		}
		.floating-search-panel .keyword-input-wrapper .search-icon-left {
			position: absolute;
			left: 18px;
			top: 50%;
			transform: translateY(-50%);
			color: #1a1b1f;
			font-size: 18px;
			z-index: 2;
		}
		.floating-search-panel .hero-filter-input {
			height: 56px !important;
			border: none !important;
			padding: 0 20px 0 48px !important;
			border-radius: 12px;
			background: transparent !important;
			color: #1a1b1f !important;
			font-size: 15px !important;
			font-weight: 500 !important;
			position: relative;
			z-index: 1;
		}
		.floating-search-panel .hero-filter-input::placeholder {
			color: #888888;
		}
		.floating-search-panel .keyword-input-wrapper,
		.floating-search-panel .hero-filter-select,
		.floating-search-panel .hero-filter-btn-group {
			width: 100% !important;
			flex: none !important;
		}
		.floating-search-panel .hero-filter-select {
			height: 56px !important;
			border: none !important;
			border-radius: 12px !important;
			background: #ffffff !important;
			color: #1a1b1f !important;
			padding: 0 20px !important;
			font-size: 15px !important;
			font-weight: 500 !important;
			box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
			appearance: none;
			-webkit-appearance: none;
			background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%231a1b1f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>") !important;
			background-repeat: no-repeat !important;
			background-position: right 16px center !important;
			background-size: 16px !important;
		}
		.floating-search-panel .hero-filter-btn-group {
			display: flex;
			flex-direction: row;
			gap: 12px;
			margin-top: 4px;
			padding: 0;
			border-left: none;
		}
		.floating-search-panel .hero-filter-advanced {
			width: 56px !important;
			flex: 0 0 56px !important;
			height: 56px !important;
			border-radius: 12px;
			background: rgba(255, 255, 255, 0.06);
			border: 1px solid rgba(255, 255, 255, 0.12);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #ffffff;
			transition: all 0.3s ease;
		}
		.floating-search-panel .hero-filter-advanced i {
			color: var(--accent-gold, #c5a880);
			font-size: 18px;
		}
		.floating-search-panel .hero-filter-advanced::after {
			display: none;
		}
		.floating-search-panel .hero-filter-search {
			flex: 1 !important;
			width: auto !important;
			height: 56px !important;
			border-radius: 12px;
			font-size: 16px;
			font-weight: 700;
			letter-spacing: 0.5px;
			background: var(--accent-gold, #c5a880);
			color: #1a1b1f;
			box-shadow: 0 8px 24px rgba(197, 168, 128, 0.25);
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}
		.floating-search-panel .modern-advanced-panel {
			position: relative;
			top: auto;
			left: auto;
			right: auto;
			margin-top: 20px;
			width: 100%;
			box-sizing: border-box;
			background: rgba(255, 255, 255, 0.03);
			border-radius: 16px;
			padding: 20px;
			border: 1px solid rgba(255, 255, 255, 0.05);
		}
		.floating-search-panel .advanced-grid {
			grid-template-columns: 1fr !important;
			gap: 16px;
			margin-top: 0;
			padding-top: 0;
			border-top: none;
		}
		.floating-search-panel .advanced-field-group label {
			color: #cccccc !important;
			font-size: 11px !important;
			margin-bottom: 8px !important;
		}
		.floating-search-panel .modern-select,
		.floating-search-panel .modern-text-input {
			height: 56px !important;
			border-radius: 12px !important;
			border: 1px solid rgba(255, 255, 255, 0.1) !important;
			background: rgba(255, 255, 255, 0.06) !important;
			color: #ffffff !important;
			font-size: 15px !important;
			padding: 0 40px 0 20px !important;
			box-sizing: border-box !important;
		}
		.floating-search-panel .modern-select {
			background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>") !important;
			background-repeat: no-repeat !important;
			background-position: right 16px center !important;
			background-size: 16px !important;
			appearance: none !important;
			-webkit-appearance: none !important;
		}
		.all-properties-results-header {
			flex-direction: column;
			gap: 15px;
			align-items: flex-start;
		}
	}


	/* Compare Floating Bar */
	.compare-floating-bar {
		position: fixed;
		bottom: 30px;
		left: 50%;
		transform: translateX(-50%) translateY(120px);
		background: rgba(26, 27, 31, 0.95);
		backdrop-filter: blur(15px);
		-webkit-backdrop-filter: blur(15px);
		border: 1px solid rgba(197, 168, 128, 0.2);
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
		border-radius: 100px;
		padding: 12px 24px;
		z-index: 9999;
		width: 90%;
		max-width: 500px;
		box-sizing: border-box;
		transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
		opacity: 0;
	}
	.compare-floating-bar.show {
		transform: translateX(-50%) translateY(0);
		opacity: 1;
	}
	.compare-bar-content {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 15px;
	}
	.compare-bar-info {
		display: flex;
		align-items: center;
		gap: 12px;
		color: #ffffff;
		font-family: var(--font-en);
	}
	.compare-bar-info .bar-icon {
		color: var(--accent-gold, #c5a880);
		font-size: 18px;
	}
	.compare-count-text {
		font-size: 14px;
		font-weight: 500;
	}
	.compare-count-text strong {
		color: var(--accent-gold, #c5a880);
		font-size: 16px;
	}
	.compare-bar-actions {
		display: flex;
		align-items: center;
		gap: 16px;
	}
	.btn-clear-compare {
		background: transparent;
		border: none;
		color: rgba(255, 255, 255, 0.6);
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		transition: color 0.2s;
		padding: 0;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.btn-clear-compare:hover {
		color: #ff6b6b;
	}
	.btn-trigger-compare {
		background: var(--accent-gold, #c5a880);
		color: #1a1b1f;
		border: none;
		border-radius: 50px;
		padding: 10px 24px;
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;
		transition: all 0.3s;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		box-shadow: 0 4px 15px rgba(197, 168, 128, 0.25);
	}
	.btn-trigger-compare:hover {
		background: #d4b88f;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(197, 168, 128, 0.4);
	}

	/* Compare Modal Overlay */
	.compare-modal-overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(11, 12, 16, 0.9);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
		z-index: 100000;
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 0;
		transition: opacity 0.3s ease;
	}
	.compare-modal-overlay.show {
		opacity: 1;
	}
	.compare-modal-container {
		background: #1a1b1f;
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 24px;
		width: 95%;
		max-width: 1200px;
		max-height: 90vh;
		overflow-y: auto;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
		display: flex;
		flex-direction: column;
		transform: scale(0.9);
		transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	.compare-modal-overlay.show .compare-modal-container {
		transform: scale(1);
	}
	.compare-modal-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 24px 30px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	}
	.compare-modal-header h3 {
		font-family: var(--font-en);
		font-size: 20px;
		font-weight: 700;
		color: #ffffff;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.compare-modal-header h3 i {
		color: var(--accent-gold, #c5a880);
	}
	.compare-modal-close {
		background: transparent;
		border: none;
		color: #ffffff;
		font-size: 24px;
		cursor: pointer;
		transition: color 0.2s;
		padding: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 0.7;
	}
	.compare-modal-close:hover {
		color: var(--accent-gold, #c5a880);
		opacity: 1;
	}
	.compare-modal-body {
		padding: 30px;
		overflow-x: auto;
	}

	/* Compare Table */
	.compare-table {
		width: 100%;
		border-collapse: collapse;
		text-align: left;
		color: #ffffff;
		min-width: 600px;
	}
	.compare-table th, .compare-table td {
		padding: 16px 20px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		vertical-align: middle;
	}
	.compare-table th {
		font-family: var(--font-en);
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 1px;
		color: var(--accent-gold, #c5a880);
		width: 20%;
		background: rgba(255, 255, 255, 0.01);
	}
	.compare-table td {
		font-size: 15px;
		width: 20%;
	}
	.compare-row-image td {
		padding-top: 0;
		padding-bottom: 20px;
	}
	.compare-image-card {
		position: relative;
		border-radius: 12px;
		overflow: hidden;
		aspect-ratio: 4/3;
		background: #000;
	}
	.compare-image-card img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	.compare-remove-item {
		position: absolute;
		top: 8px;
		right: 8px;
		background: rgba(0, 0, 0, 0.7);
		color: #ffffff;
		border: none;
		border-radius: 50%;
		width: 24px;
		height: 24px;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		font-size: 12px;
		transition: background 0.2s;
		z-index: 5;
	}
	.compare-remove-item:hover {
		background: #ff6b6b;
	}
	.compare-prop-title {
		font-family: var(--font-en);
		font-weight: 700;
		font-size: 15px;
		margin: 0;
		line-height: 1.4;
	}
	.compare-prop-title a {
		color: #ffffff;
		text-decoration: none;
		transition: color 0.2s;
	}
	.compare-prop-title a:hover {
		color: var(--accent-gold, #c5a880);
	}
	.compare-prop-price {
		font-size: 16px;
		font-weight: 800;
		color: var(--accent-gold, #c5a880);
	}
	.compare-prop-location {
		font-size: 13px;
		color: #b0b0b0;
		display: flex;
		align-items: center;
		gap: 6px;
	}
	.compare-prop-location i {
		color: var(--accent-gold, #c5a880);
	}
	.compare-table-btn {
		display: inline-block;
		background: transparent;
		border: 1px solid var(--accent-gold, #c5a880);
		color: var(--accent-gold, #c5a880);
		padding: 8px 16px;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 700;
		text-decoration: none;
		transition: all 0.2s;
		text-align: center;
	}
	.compare-table-btn:hover {
		background: var(--accent-gold, #c5a880);
		color: #1a1b1f;
	}
	.compare-amenities-list {
		list-style: none;
		padding: 0;
		margin: 0;
		display: flex;
		flex-direction: column;
		gap: 6px;
	}
	.compare-amenities-list li {
		font-size: 13px;
		color: #cccccc;
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.compare-amenities-list li i {
		color: var(--accent-gold, #c5a880);
		font-size: 12px;
	}
</style>

<div class="all-properties-wrapper">
	<!-- 1. Hero Map Section -->
	<div class="all-properties-map-section">
		<div id="all-properties-map"></div>

		<!-- 2. Floating Search Panel -->
		<?php
		if ( isset( $lock_listing_type ) ) {
			$selected_listing_type = $lock_listing_type;
		} else {
			$selected_listing_type = isset($_GET['listing_type']) ? sanitize_text_field($_GET['listing_type']) : 'all';
		}
		$selected_state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
		$selected_district = isset($_GET['district']) ? sanitize_text_field($_GET['district']) : '';
		$selected_prop_type = isset($_GET['prop_type']) ? sanitize_text_field($_GET['prop_type']) : '';
		
		$selected_prop_cat = isset($_GET['prop_cat']) ? sanitize_text_field($_GET['prop_cat']) : '';
		$selected_min_price = isset($_GET['min_price']) ? sanitize_text_field($_GET['min_price']) : '';
		$selected_max_price = isset($_GET['max_price']) ? sanitize_text_field($_GET['max_price']) : '';
		$selected_beds = isset($_GET['beds']) ? sanitize_text_field($_GET['beds']) : '';
		$selected_baths = isset($_GET['baths']) ? sanitize_text_field($_GET['baths']) : '';
		$selected_area_size = isset($_GET['area_size']) ? sanitize_text_field($_GET['area_size']) : '';

		$has_advanced_values = !empty($selected_state) || !empty($selected_district) || !empty($selected_prop_cat) || !empty($selected_min_price) || !empty($selected_max_price) || !empty($selected_beds) || !empty($selected_baths) || !empty($selected_area_size);
		?>
		<div class="floating-search-panel">
			<div class="modern-tabs">
				<?php if ( ! isset( $lock_listing_type ) ) : ?>
					<button class="modern-tab<?php echo $selected_listing_type === 'all' ? ' active' : ''; ?>" type="button" data-type="all">All</button>
					<button class="modern-tab<?php echo $selected_listing_type === 'buy' || $selected_listing_type === 'sale' ? ' active' : ''; ?>" type="button" data-type="buy">For Sale</button>
					<button class="modern-tab<?php echo $selected_listing_type === 'rent' ? ' active' : ''; ?>" type="button" data-type="rent">For Rent</button>
				<?php else : ?>
					<button class="modern-tab<?php echo $selected_listing_type === 'all' ? ' active' : ''; ?>" type="button" data-type="all" style="display:none;">All</button>
					<button class="modern-tab<?php echo $selected_listing_type === 'buy' || $selected_listing_type === 'sale' ? ' active' : ''; ?>" type="button" data-type="buy" style="<?php echo $lock_listing_type === 'buy' ? '' : 'display:none;'; ?>">For Sale</button>
					<button class="modern-tab<?php echo $selected_listing_type === 'rent' ? ' active' : ''; ?>" type="button" data-type="rent" style="<?php echo $lock_listing_type === 'rent' ? '' : 'display:none;'; ?>">For Rent</button>
				<?php endif; ?>
			</div>

			<form id="all-properties-filter-form" class="hero-filter-bar">
				<input type="hidden" name="listing_type" id="filter-listing-type" value="<?php echo esc_attr($selected_listing_type); ?>">
				<input type="hidden" name="paged" id="filter-paged" value="<?php echo isset($_GET['paged']) ? intval($_GET['paged']) : 1; ?>">

				<div class="filter-inputs-row">
					<!-- Keyword -->
					<div class="keyword-input-wrapper">
						<i class="fa-solid fa-magnifying-glass search-icon-left"></i>
						<input type="text" name="keyword" id="filter-keyword" class="hero-filter-input" placeholder="Enter the address" value="<?php echo isset($_GET['keyword']) ? esc_attr(sanitize_text_field($_GET['keyword'])) : ''; ?>">
					</div>

					<!-- Category (Property Type) -->
					<select name="prop_type" id="filter-prop-type" class="hero-filter-select">
						<option value="">Category</option>
						<?php
						$types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
						foreach ( $types as $t ) {
							$selected = ($t->slug === $selected_prop_type) ? 'selected' : '';
							echo '<option value="' . esc_attr($t->slug) . '" ' . $selected . '>' . esc_html($t->name) . '</option>';
						}
						?>
					</select>

					<!-- Action Buttons -->
					<div class="hero-filter-btn-group">
						<button type="button" class="hero-filter-advanced<?php echo $has_advanced_values ? ' active' : ''; ?>" id="filter-advanced-toggle">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="advanced-icon">
								<line x1="4" y1="21" x2="4" y2="14"></line>
								<line x1="4" y1="10" x2="4" y2="3"></line>
								<line x1="12" y1="21" x2="12" y2="12"></line>
								<line x1="12" y1="8" x2="12" y2="3"></line>
								<line x1="20" y1="21" x2="20" y2="16"></line>
								<line x1="20" y1="12" x2="20" y2="3"></line>
								<line x1="1" y1="14" x2="7" y2="14"></line>
								<line x1="9" y1="8" x2="15" y2="8"></line>
								<line x1="17" y1="16" x2="23" y2="16"></line>
							</svg>
						</button>

						<button type="submit" class="hero-filter-search">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
						</button>
					</div>
				</div>

				<!-- Advanced Fields Panel -->
				<div class="modern-advanced-panel" id="filter-advanced-panel" style="<?php echo $has_advanced_values ? 'display: block;' : 'display: none;'; ?>">
					<div class="advanced-grid">
						<!-- State -->
						<div class="advanced-field-group">
							<label>State</label>
							<select name="state" id="filter-state" class="modern-select">
								<option value="">All State</option>
								<?php
								$indian_states = array(
									'Kerala', 'Tamil Nadu', 'Karnataka', 'Maharashtra', 'Delhi', 'Goa',
									'Andhra Pradesh', 'Telangana', 'Gujarat', 'Rajasthan', 'Uttar Pradesh', 'West Bengal'
								);
								foreach ( $indian_states as $s ) {
									$selected = ($s === $selected_state) ? 'selected' : '';
									echo '<option value="' . esc_attr($s) . '" ' . $selected . '>' . esc_html($s) . '</option>';
								}
								?>
							</select>
						</div>

						<!-- District (Dependent) -->
						<div class="advanced-field-group">
							<label>District</label>
							<select name="district" id="filter-district" class="modern-select" <?php echo empty($selected_state) ? 'disabled' : ''; ?>>
								<option value="">Select District...</option>
								<?php
								if ( ! empty( $selected_state ) ) {
									$districts_list = casaview_get_districts_list_by_state( $selected_state );
									foreach ( $districts_list as $d ) {
										$selected = ($d === $selected_district) ? 'selected' : '';
										echo '<option value="' . esc_attr($d) . '" ' . $selected . '>' . esc_html($d) . '</option>';
									}
								}
								?>
							</select>
						</div>

						<!-- Category -->
						<div class="advanced-field-group">
							<label>Property Category</label>
							<select name="prop_cat" id="filter-prop-cat" class="modern-select">
								<option value="">All Categories</option>
								<?php
								if ( isset( $lock_listing_type ) ) {
									$categories = casaview_get_categories_by_listing_type( $lock_listing_type );
								} else {
									$categories = get_terms( array( 'taxonomy' => 'property_category', 'hide_empty' => false ) );
								}
								foreach ( $categories as $c ) {
									$selected = ($c->slug === $selected_prop_cat) ? 'selected' : '';
									echo '<option value="' . esc_attr($c->slug) . '" ' . $selected . '>' . esc_html($c->name) . '</option>';
								}
								?>
							</select>
						</div>

						<!-- Min Price -->
						<div class="advanced-field-group">
							<label>Min Price (₹)</label>
							<input type="number" name="min_price" id="filter-min-price" class="modern-text-input" placeholder="e.g. 500000" value="<?php echo esc_attr($selected_min_price); ?>">
						</div>

						<!-- Max Price -->
						<div class="advanced-field-group">
							<label>Max Price (₹)</label>
							<input type="number" name="max_price" id="filter-max-price" class="modern-text-input" placeholder="e.g. 10000000" value="<?php echo esc_attr($selected_max_price); ?>">
						</div>

						<!-- Bedrooms -->
						<div class="advanced-field-group">
							<label>Bedrooms</label>
							<select name="beds" id="filter-beds" class="modern-select">
								<option value="">Any Beds</option>
								<option value="1" <?php selected($selected_beds, '1'); ?>>1 Bed</option>
								<option value="2" <?php selected($selected_beds, '2'); ?>>2 Beds</option>
								<option value="3" <?php selected($selected_beds, '3'); ?>>3 Beds</option>
								<option value="4" <?php selected($selected_beds, '4'); ?>>4 Beds</option>
								<option value="5" <?php selected($selected_beds, '5'); ?>>5+ Beds</option>
							</select>
						</div>

						<!-- Bathrooms -->
						<div class="advanced-field-group">
							<label>Bathrooms</label>
							<select name="baths" id="filter-baths" class="modern-select">
								<option value="">Any Baths</option>
								<option value="1" <?php selected($selected_baths, '1'); ?>>1 Bath</option>
								<option value="2" <?php selected($selected_baths, '2'); ?>>2 Baths</option>
								<option value="3" <?php selected($selected_baths, '3'); ?>>3 Baths</option>
								<option value="4" <?php selected($selected_baths, '4'); ?>>4 Baths</option>
								<option value="5" <?php selected($selected_baths, '5'); ?>>5+ Baths</option>
							</select>
						</div>

						<!-- Min Area Size -->
						<div class="advanced-field-group">
							<label>Area Size (Min Sq.Ft.)</label>
							<input type="number" name="area_size" id="filter-area-size" class="modern-text-input" placeholder="e.g. 1500" value="<?php echo esc_attr($selected_area_size); ?>">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- 3. Search Results & Grid Section -->
	<div class="all-properties-content-wrapper">
		<div class="container">
			<div class="all-properties-results-header">
				<div class="results-count" id="all-properties-results-count">
					Showing 0–0 of 0 results
				</div>
				<div class="results-sorting">
					<label for="all-properties-sort">Sort By</label>
					<?php $selected_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : ''; ?>
					<select id="all-properties-sort" class="sort-select">
						<option value="default" <?php selected($selected_sort, 'default'); ?>>Default</option>
						<option value="newest" <?php selected($selected_sort, 'newest'); ?>>Newest</option>
						<option value="oldest" <?php selected($selected_sort, 'oldest'); ?>>Oldest</option>
						<option value="lowest_price" <?php selected($selected_sort, 'lowest_price'); ?>>Lowest Price</option>
						<option value="highest_price" <?php selected($selected_sort, 'highest_price'); ?>>Highest Price</option>
					</select>
				</div>
			</div>

			<!-- Loading Overlay Container -->
			<div class="grid-loading-overlay-wrapper" id="grid-loading-wrapper">
				<!-- Grid Container -->
				<div class="archive-properties-grid" id="all-properties-grid-container">
					<!-- Loaded dynamically via AJAX -->
				</div>
			</div>

			<!-- Pagination -->
			<div class="pagination-wrapper" id="all-properties-pagination">
				<!-- Loaded dynamically via AJAX -->
			</div>
		</div>
	</div>
</div>

<!-- Compare Floating Bar -->
<div class="compare-floating-bar" id="compare-floating-bar" style="display: none;">
	<div class="compare-bar-content">
		<div class="compare-bar-info">
			<i class="fa-solid fa-code-compare bar-icon"></i>
			<span class="compare-count-text"><strong id="compare-selected-count">0</strong> Properties Selected</span>
		</div>
		<div class="compare-bar-actions">
			<button type="button" class="btn-clear-compare" id="btn-clear-compare">Clear All</button>
			<button type="button" class="btn-trigger-compare" id="btn-trigger-compare">Compare Now</button>
		</div>
	</div>
</div>

<!-- Compare Modal Overlay -->
<div class="compare-modal-overlay" id="compare-modal-overlay" style="display: none;">
	<div class="compare-modal-container">
		<div class="compare-modal-header">
			<h3><i class="fa-solid fa-code-compare"></i> Compare Properties</h3>
			<button class="compare-modal-close" id="compare-modal-close" aria-label="Close Comparison">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="compare-modal-body" id="compare-modal-body">
			<!-- Loaded via AJAX -->
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Initialize Leaflet Map
	const mapContainer = document.getElementById('all-properties-map');
	let map = null;
	let mapMarkers = [];
	let markerCluster = null;

	if (mapContainer && typeof L !== 'undefined') {
		// Default view center on Kerala coordinates
		map = L.map('all-properties-map', { scrollWheelZoom: false }).setView([10.8505, 76.2711], 8);
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap'
		}).addTo(map);

		// Initialize Marker Cluster Group
		if (typeof L.markerClusterGroup !== 'undefined') {
			markerCluster = L.markerClusterGroup({
				showCoverageOnHover: false,
				zoomToBoundsOnClick: true,
				maxClusterRadius: 40,
				spiderfyOnMaxZoom: true
			});
			map.addLayer(markerCluster);
		}
	}

	function clearMapMarkers() {
		if (!map) return;
		if (markerCluster) {
			markerCluster.clearLayers();
		}
		mapMarkers.forEach(m => map.removeLayer(m));
		mapMarkers = [];
	}

	function updateMapMarkers(markersData) {
		if (!map) return;
		clearMapMarkers();
		if (!markersData || markersData.length === 0) return;

		const bounds = [];
		markersData.forEach(m => {
			const marker = L.marker([m.lat, m.lng]);
			const popupHtml = `
				<div class="leaflet-popup-card">
					<a href="${m.url}"><img src="${m.img}" alt="${m.title}"></a>
					<div class="leaflet-popup-card-details">
						<div class="leaflet-popup-card-price">${m.price}</div>
						<h4 class="leaflet-popup-card-title"><a href="${m.url}">${m.title}</a></h4>
						<div class="leaflet-popup-card-location">
							<i class="fa-solid fa-location-dot"></i>
							<span>${m.loc}</span>
						</div>
						<a href="${m.url}" class="leaflet-popup-card-btn">View Property</a>
					</div>
				</div>
			`;
			marker.bindPopup(popupHtml);

			if (markerCluster) {
				markerCluster.addLayer(marker);
			} else {
				marker.addTo(map);
			}
			mapMarkers.push(marker);
			bounds.push([m.lat, m.lng]);
		});

		if (bounds.length > 0) {
			map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
		}
	}

	// State -> District Dropdown Cascading
	const stateSelect = document.getElementById('filter-state');
	const districtSelect = document.getElementById('filter-district');

	if (stateSelect && districtSelect) {
		stateSelect.addEventListener('change', function() {
			const state = this.value;
			districtSelect.innerHTML = '<option value="">Select District...</option>';
			if (!state) {
				districtSelect.disabled = true;
				triggerSearch();
				return;
			}

			const data = new URLSearchParams();
			data.append('action', 'casaview_get_districts_for_state');
			data.append('state', state);

			fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: data.toString()
			})
			.then(res => res.json())
			.then(response => {
				if (response.success && Array.isArray(response.data)) {
					response.data.forEach(d => {
						districtSelect.innerHTML += `<option value="${d}">${d}</option>`;
					});
					districtSelect.disabled = false;
				} else {
					districtSelect.disabled = true;
				}
				triggerSearch();
			})
			.catch(err => {
				console.error(err);
				districtSelect.disabled = true;
				triggerSearch();
			});
		});
	}

	// Listing Type Tabs Switcher
	const tabs = document.querySelectorAll('.floating-search-panel .modern-tab');
	const listingTypeInput = document.getElementById('filter-listing-type');
	if (tabs && listingTypeInput) {
		tabs.forEach(tab => {
			tab.addEventListener('click', function(e) {
				e.preventDefault();
				tabs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');
				listingTypeInput.value = this.dataset.type;
				triggerSearch();
			});
		});
	}

	// Advanced Toggle
	const advToggle = document.getElementById('filter-advanced-toggle');
	const advPanel = document.getElementById('filter-advanced-panel');
	if (advToggle && advPanel) {
		// Initialize state based on PHP
		if (advPanel.style.display === 'block') {
			advPanel.classList.add('show-panel');
		}

		advToggle.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			const isVisible = advPanel.classList.contains('show-panel');
			if (isVisible) {
				advPanel.classList.remove('show-panel');
				this.classList.remove('active');
			} else {
				advPanel.style.display = 'block';
				setTimeout(() => {
					advPanel.classList.add('show-panel');
				}, 10);
				this.classList.add('active');
			}
		});

		// Close when clicking outside
		document.addEventListener('click', function(e) {
			if (advPanel.classList.contains('show-panel')) {
				if (!advPanel.contains(e.target) && !advToggle.contains(e.target)) {
					advPanel.classList.remove('show-panel');
					advToggle.classList.remove('active');
				}
			}
		});

		// Listen for transition end to set display none if needed
		advPanel.addEventListener('transitionend', function(e) {
			if (!advPanel.classList.contains('show-panel') && e.propertyName === 'opacity') {
				advPanel.style.display = 'none';
			}
		});
	}

	// AJAX Filtering execution logic
	const filterForm = document.getElementById('all-properties-filter-form');
	const gridContainer = document.getElementById('all-properties-grid-container');
	const loadingWrapper = document.getElementById('grid-loading-wrapper');
	const paginationWrapper = document.getElementById('all-properties-pagination');
	const countTextEl = document.getElementById('all-properties-results-count');
	const sortSelect = document.getElementById('all-properties-sort');
	const pagedInput = document.getElementById('filter-paged');

	function triggerSearch(page = 1) {
		if (pagedInput) pagedInput.value = page;
		if (loadingWrapper) {
			loadingWrapper.classList.add('grid-loading-overlay');
			let spinner = document.getElementById('loading-spinner-element');
			if (!spinner) {
				spinner = document.createElement('div');
				spinner.className = 'grid-loading-spinner';
				spinner.id = 'loading-spinner-element';
				loadingWrapper.appendChild(spinner);
			}
		}

		const formData = new FormData(filterForm);
		formData.append('action', 'casaview_filter_properties');
		if (sortSelect) {
			formData.append('sort_by', sortSelect.value);
		}

		const params = new URLSearchParams();
		for (const pair of formData.entries()) {
			params.append(pair[0], pair[1]);
		}

		// Update browser URL query parameters dynamically
		const urlParams = new URLSearchParams();
		
		const kw = document.getElementById('filter-keyword').value;
		if (kw) urlParams.set('keyword', kw);
		
		const st = document.getElementById('filter-state').value;
		if (st) urlParams.set('state', st);
		
		const dst = document.getElementById('filter-district').value;
		if (dst && !document.getElementById('filter-district').disabled) urlParams.set('district', dst);
		
		const ptype = document.getElementById('filter-prop-type').value;
		if (ptype) urlParams.set('prop_type', ptype);
		
		const ltype = document.getElementById('filter-listing-type').value;
		if (ltype && ltype !== 'all') urlParams.set('listing_type', ltype);
		
		// Advanced filters
		const pcat = document.getElementById('filter-prop-cat').value;
		if (pcat) urlParams.set('prop_cat', pcat);
		
		const minp = document.getElementById('filter-min-price').value;
		if (minp) urlParams.set('min_price', minp);
		
		const maxp = document.getElementById('filter-max-price').value;
		if (maxp) urlParams.set('max_price', maxp);
		
		const bd = document.getElementById('filter-beds').value;
		if (bd) urlParams.set('beds', bd);
		
		const bt = document.getElementById('filter-baths').value;
		if (bt) urlParams.set('baths', bt);
		
		const sz = document.getElementById('filter-area-size').value;
		if (sz) urlParams.set('area_size', sz);
		
		if (sortSelect && sortSelect.value && sortSelect.value !== 'default') {
			urlParams.set('sort', sortSelect.value);
		}
		
		if (page > 1) {
			urlParams.set('paged', page);
		}
		
		const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
		window.history.replaceState(null, '', newUrl);

		fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: params.toString()
		})
		.then(res => res.json())
		.then(response => {
			if (loadingWrapper) {
				loadingWrapper.classList.remove('grid-loading-overlay');
				const spinner = document.getElementById('loading-spinner-element');
				if (spinner) spinner.remove();
			}

			if (response.success) {
				if (gridContainer) gridContainer.innerHTML = response.data.html;
				if (paginationWrapper) paginationWrapper.innerHTML = response.data.pagination;
				if (countTextEl) countTextEl.textContent = response.data.count_text;
				
				// Update Map markers dynamically
				updateMapMarkers(response.data.markers);
				
				// Re-initialize wishlist click event listeners
				bindWishlistToggles();
				bindCompareToggles();
			}
		})
		.catch(err => {
			console.error(err);
			if (loadingWrapper) {
				loadingWrapper.classList.remove('grid-loading-overlay');
				const spinner = document.getElementById('loading-spinner-element');
				if (spinner) spinner.remove();
			}
		});
	}

	if (filterForm) {
		filterForm.addEventListener('submit', function(e) {
			e.preventDefault();
			triggerSearch(1);
		});

		// Trigger automatically on select changes
		const selectFilters = filterForm.querySelectorAll('select:not(#filter-district)');
		selectFilters.forEach(sel => {
			sel.addEventListener('change', function() {
				triggerSearch(1);
			});
		});
		
		districtSelect.addEventListener('change', function() {
			triggerSearch(1);
		});
	}

	if (sortSelect) {
		sortSelect.addEventListener('change', function() {
			triggerSearch(1);
		});
	}

	// Intercept Pagination Clicks
	if (paginationWrapper) {
		paginationWrapper.addEventListener('click', function(e) {
			const link = e.target.closest('a');
			if (link) {
				e.preventDefault();
				const href = link.getAttribute('href');
				if (!href) return;

				let page = 1;
				const match = href.match(/\/page\/([0-9]+)/);
				if (match) {
					page = parseInt(match[1]);
				} else {
					const urlParams = new URLSearchParams(href.split('?')[1]);
					page = parseInt(urlParams.get('paged')) || 1;
				}

				triggerSearch(page);
				window.scrollTo({
					top: mapContainer.offsetTop + mapContainer.offsetHeight - 50,
					behavior: 'smooth'
				});
			}
		});
	}

	// Wishlist Binding Logic
	function bindWishlistToggles() {
		const wishlistToggles = document.querySelectorAll('.all-properties-wrapper .wishlist-btn-toggle');
		const wishlist = JSON.parse(localStorage.getItem('property_wishlist') || '[]');

		wishlistToggles.forEach(btn => {
			const id = btn.dataset.id;
			const icon = btn.querySelector('i');
			if (wishlist.includes(id)) {
				btn.classList.add('active');
				if (icon) {
					icon.className = 'fa-solid fa-heart';
					icon.style.color = '#e74c3c';
				}
			} else {
				btn.classList.remove('active');
				if (icon) {
					icon.className = 'fa-regular fa-heart';
					icon.style.color = '';
				}
			}

			// Add fresh listener
			btn.onclick = function(e) {
				e.preventDefault();
				e.stopPropagation();
				let localList = JSON.parse(localStorage.getItem('property_wishlist') || '[]');
				if (localList.includes(id)) {
					localList = localList.filter(item => item !== id);
					this.classList.remove('active');
					if (icon) {
						icon.className = 'fa-regular fa-heart';
						icon.style.color = '';
					}
				} else {
					localList.push(id);
					this.classList.add('active');
					if (icon) {
						icon.className = 'fa-solid fa-heart';
						icon.style.color = '#e74c3c';
					}
				}
				localStorage.setItem('property_wishlist', JSON.stringify(localList));
			};
		});
	}

	// Compare Functionality Logic
	function bindCompareToggles() {
		const compareToggles = document.querySelectorAll('.all-properties-wrapper .compare-btn-toggle');
		const pageType = (document.getElementById('filter-listing-type')?.value === 'rent') ? 'rent' : 'buy';
		const storageKey = 'casaview_compare_' + pageType;
		const compareList = JSON.parse(localStorage.getItem(storageKey) || '[]');

		compareToggles.forEach(btn => {
			const id = btn.dataset.id;
			if (compareList.includes(id)) {
				btn.classList.add('active');
				btn.style.backgroundColor = 'var(--accent-gold, #c5a880)';
				btn.style.color = '#ffffff';
				btn.style.borderColor = 'var(--accent-gold, #c5a880)';
			} else {
				btn.classList.remove('active');
				btn.style.backgroundColor = '';
				btn.style.color = '';
				btn.style.borderColor = '';
			}

			// Add click handler
			btn.onclick = function(e) {
				e.preventDefault();
				e.stopPropagation();
				let localList = JSON.parse(localStorage.getItem(storageKey) || '[]');
				if (localList.includes(id)) {
					localList = localList.filter(item => item !== id);
					this.classList.remove('active');
					this.style.backgroundColor = '';
					this.style.color = '';
					this.style.borderColor = '';
				} else {
					if (localList.length >= 4) {
						alert('You can compare up to 4 properties.');
						return;
					}
					localList.push(id);
					this.classList.add('active');
					this.style.backgroundColor = 'var(--accent-gold, #c5a880)';
					this.style.color = '#ffffff';
					this.style.borderColor = 'var(--accent-gold, #c5a880)';
				}
				localStorage.setItem(storageKey, JSON.stringify(localList));
				updateCompareBar();
			};
		});
	}

	function updateCompareBar() {
		const bar = document.getElementById('compare-floating-bar');
		const countEl = document.getElementById('compare-selected-count');
		if (!bar || !countEl) return;

		const pageType = (document.getElementById('filter-listing-type')?.value === 'rent') ? 'rent' : 'buy';
		const storageKey = 'casaview_compare_' + pageType;
		const compareList = JSON.parse(localStorage.getItem(storageKey) || '[]');

		if (compareList.length > 0) {
			countEl.textContent = compareList.length;
			bar.style.display = 'block';
			setTimeout(() => {
				bar.classList.add('show');
			}, 10);
		} else {
			bar.classList.remove('show');
			setTimeout(() => {
				if (!bar.classList.contains('show')) {
					bar.style.display = 'none';
				}
			}, 300);
		}
	}

	function loadCompareData() {
		const pageType = (document.getElementById('filter-listing-type')?.value === 'rent') ? 'rent' : 'buy';
		const storageKey = 'casaview_compare_' + pageType;
		const compareList = JSON.parse(localStorage.getItem(storageKey) || '[]');
		const modalBody = document.getElementById('compare-modal-body');

		if (!modalBody) return;

		if (compareList.length === 0) {
			modalBody.innerHTML = '<p style="text-align:center; color:#888; padding: 40px 0;">No properties selected to compare.</p>';
			return;
		}

		modalBody.innerHTML = `
			<div class="compare-loading-state" style="text-align:center; padding: 40px 0;">
				<div class="grid-loading-spinner" style="position:static; transform:none; margin: 0 auto 20px auto;"></div>
				<p style="color: #888;">Fetching property details...</p>
			</div>
		`;

		const params = new URLSearchParams();
		params.append('action', 'casaview_get_compare_properties');
		compareList.forEach(id => params.append('ids[]', id));

		fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: params.toString()
		})
		.then(res => res.json())
		.then(response => {
			if (response.success && response.data.html) {
				modalBody.innerHTML = response.data.html;
				bindCompareTableRemoveButtons();
			} else {
				modalBody.innerHTML = '<p style="text-align:center; color:#888; padding: 40px 0;">Failed to load comparison data. Please try again.</p>';
			}
		})
		.catch(err => {
			console.error(err);
			modalBody.innerHTML = '<p style="text-align:center; color:#888; padding: 40px 0;">An error occurred. Please try again.</p>';
		});
	}

	function bindCompareTableRemoveButtons() {
		const modalBody = document.getElementById('compare-modal-body');
		if (!modalBody) return;
		const removeBtns = modalBody.querySelectorAll('.compare-remove-item');
		removeBtns.forEach(btn => {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				const id = this.dataset.id;
				const pageType = (document.getElementById('filter-listing-type')?.value === 'rent') ? 'rent' : 'buy';
				const storageKey = 'casaview_compare_' + pageType;
				let localList = JSON.parse(localStorage.getItem(storageKey) || '[]');
				localList = localList.filter(item => item !== id);
				localStorage.setItem(storageKey, JSON.stringify(localList));
				
				loadCompareData();
				updateCompareBar();
				bindCompareToggles();
			});
		});
	}

	// Initialize UI elements and events
	const clearBtn = document.getElementById('btn-clear-compare');
	if (clearBtn) {
		clearBtn.addEventListener('click', function() {
			const pageType = (document.getElementById('filter-listing-type')?.value === 'rent') ? 'rent' : 'buy';
			const storageKey = 'casaview_compare_' + pageType;
			localStorage.setItem(storageKey, '[]');
			updateCompareBar();
			bindCompareToggles();
		});
	}

	const triggerBtn = document.getElementById('btn-trigger-compare');
	const modalOverlay = document.getElementById('compare-modal-overlay');
	const modalClose = document.getElementById('compare-modal-close');

	if (triggerBtn && modalOverlay) {
		triggerBtn.addEventListener('click', function() {
			modalOverlay.style.display = 'flex';
			setTimeout(() => {
				modalOverlay.classList.add('show');
			}, 10);
			loadCompareData();
		});
	}

	if (modalClose && modalOverlay) {
		modalClose.addEventListener('click', function() {
			modalOverlay.classList.remove('show');
			setTimeout(() => {
				modalOverlay.style.display = 'none';
			}, 300);
		});
		
		modalOverlay.addEventListener('click', function(e) {
			if (e.target === modalOverlay) {
				modalClose.click();
			}
		});
	}

	// Load Initial Data on Page Load
	const initialPage = <?php echo isset($_GET['paged']) ? intval($_GET['paged']) : 1; ?>;
	triggerSearch(initialPage);
	updateCompareBar();
});
</script>

<?php
get_footer();

