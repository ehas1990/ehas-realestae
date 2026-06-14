/**
 * Frontend JavaScript for Real Estate Management System
 */
(function($) {
	'use strict';

	var map = null;
	var markerGroup = null;

	$(document).ready(function() {
		initSingleGallery();
		initSingleMap();
		initListingMap();
		initLocationCascades();
		initFilters();
		initReadMore();
		initEnquiryForm();
		initHeroSlider();
		initAgentDashboard();
		initFrontendNotifications();
	});

	/**
	 * Single Property Media Gallery: switch main image on thumbnail click
	 */
	function initSingleGallery() {
		$('.rem-gallery-thumb').on('click', function() {
			var $thumb = $(this);
			var fullUrl = $thumb.data('full-url');

			if (!fullUrl) return;

			// Active classes
			$('.rem-gallery-thumb').removeClass('active');
			$thumb.addClass('active');

			// Fade out main image, change src, and fade back in
			var $mainImg = $('#rem-main-gallery-image');
			$mainImg.fadeOut(150, function() {
				$mainImg.attr('src', fullUrl).fadeIn(150);
			});
		});
	}

	/**
	 * Initialize Leaflet Map on Single Property Page
	 */
	function initSingleMap() {
		if (typeof L === 'undefined') return;
		var $mapEl = $('#rem-single-property-map');
		if (!$mapEl.length) return;

		var lat = parseFloat($mapEl.data('lat'));
		var lng = parseFloat($mapEl.data('lng'));
		var title = $mapEl.data('title');

		if (isNaN(lat) || isNaN(lng)) return;

		// Initialize Leaflet map
		var singleMap = L.map('rem-single-property-map').setView([lat, lng], 14);

		// Add OpenStreetMap tiles
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(singleMap);

		// Custom icon (optional, default works fine)
		var marker = L.marker([lat, lng]).addTo(singleMap);
		marker.bindPopup('<strong>' + title + '</strong>').openPopup();

		// Reflow map size on load to prevent grey boxes
		setTimeout(function() {
			singleMap.invalidateSize();
		}, 400);
	}

	/**
	 * Initialize Leaflet Map on Archive Listing Page
	 */
	function initListingMap() {
		if (typeof L === 'undefined') return;
		var $mapEl = $('#rem-properties-map');
		if (!$mapEl.length) return;

		// Initialize Leaflet map centered at a default coordinate (e.g. Kerala center, approx 10.8505, 76.2711)
		map = L.map('rem-properties-map').setView([10.8505, 76.2711], 7);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		markerGroup = L.layerGroup().addTo(map);

		// Collect initial page markers from the property cards
		updateListingMapFromCards();
	}

	/**
	 * Read current property cards on the page and place markers on the map
	 */
	function updateListingMapFromCards() {
		if (!map || !markerGroup) return;

		markerGroup.clearLayers();
		var bounds = [];

		$('.rem-property-card').each(function() {
			var $card = $(this);
			var postId = $card.data('post-id');
			
			// We can fetch lat/lng from AJAX data, or if initial render, query them.
			// To keep it simple and robust, we fetch map data through AJAX updates.
		});
	}

	/**
	 * Update Map markers dynamically using AJAX returned markers list
	 */
	function updateListingMap(markersData) {
		if (typeof L === 'undefined') return;
		if (!map || !markerGroup) return;

		markerGroup.clearLayers();
		if (!markersData || !markersData.length) {
			return;
		}

		var bounds = [];

		markersData.forEach(function(marker) {
			var lat = parseFloat(marker.lat);
			var lng = parseFloat(marker.lng);

			if (isNaN(lat) || isNaN(lng)) return;

			var latLng = [lat, lng];
			bounds.push(latLng);

			var customMarker = L.marker(latLng);

			// Pop up content with styling
			var popupContent = 
				'<div class="rem-map-popup">' +
					'<img src="' + marker.image + '" style="width: 100%; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 5px;">' +
					'<span style="font-size: 10px; text-transform: uppercase; color: var(--rem-accent); font-weight: bold;">' + marker.status + '</span>' +
					'<h4 style="margin: 2px 0; font-size: 13px; line-height: 1.2;"><a href="' + marker.permalink + '" style="text-decoration: none; color: #1e293b;">' + marker.title + '</a></h4>' +
					'<strong style="color: var(--rem-primary); font-size: 12px;">' + marker.price + '</strong>' +
				'</div>';

			customMarker.bindPopup(popupContent);
			markerGroup.addLayer(customMarker);
		});

		// Auto fit bounds to show all markers
		if (bounds.length > 0) {
			map.fitBounds(bounds, { padding: [40, 40] });
		}

		setTimeout(function() {
			map.invalidateSize();
		}, 200);
	}

	/**
	 * AJAX cascading location selection (State -> District -> Taluk)
	 */
	function initLocationCascades() {
		// When State changes: Load Districts (only on property submit form)
		$('#prop-state').on('change', function() {
			var stateId = $(this).val();
			var $district = $('#prop-district');
			var $taluk = $('#prop-taluk');
			var $place = $('#prop-place');

			resetSelect($district, 'Select District');
			resetSelect($taluk, 'Select Taluk');
			resetSelect($place, 'Select Location');

			if (!stateId) return;

			loadLocationOptions(stateId, 'district', $district, 'Select District');
		});

		// When District changes: Load Taluks (handles filters, mobile filters, and property submit form)
		$('#rem-search-district, #rem-mob-district, #prop-district').on('change', function() {
			var id = $(this).attr('id');
			var districtId = $(this).val();
			var $taluk;
			var $place;

			if (id === 'rem-search-district') {
				$taluk = $('#rem-search-taluk');
				$place = $('#rem-search-location');
			} else if (id === 'rem-mob-district') {
				$taluk = $('#rem-mob-taluk');
				$place = $('#rem-mob-location');
			} else {
				$taluk = $('#prop-taluk');
				$place = $('#prop-place');
			}

			resetSelect($taluk, 'Select Taluk');
			resetSelect($place, 'Select Location');

			if (!districtId) return;

			loadLocationOptions(districtId, 'taluk', $taluk, 'Select Taluk');
		});

		// When Taluk changes: Load Locations (handles filters, mobile filters, and property submit form)
		$('#rem-search-taluk, #rem-mob-taluk, #prop-taluk').on('change', function() {
			var id = $(this).attr('id');
			var talukId = $(this).val();
			var $place;

			if (id === 'rem-search-taluk') {
				$place = $('#rem-search-location');
			} else if (id === 'rem-mob-taluk') {
				$place = $('#rem-mob-location');
			} else {
				$place = $('#prop-place');
			}

			resetSelect($place, 'Select Location');

			if (!talukId) return;

			loadLocationOptions(talukId, 'location_place', $place, 'Select Location');
		});
	}

	/**
	 * Helper function to clean and disable selects
	 */
	function resetSelect($select, placeholderText) {
		$select.html('<option value="">' + placeholderText + '</option>').prop('disabled', true);
	}

	/**
	 * Query child location options via AJAX
	 */
	function loadLocationOptions(parentId, targetType, $targetSelect, placeholderText) {
		$targetSelect.parent().addClass('rem-loading');

		$.ajax({
			url: rem_params.ajax_url,
			type: 'POST',
			data: {
				action: 'rem_get_location_children',
				nonce: rem_params.nonce,
				parent_id: parentId,
				target_type: targetType
			},
			success: function(response) {
				$targetSelect.parent().removeClass('rem-loading');
				if (response.success && response.data.options) {
					var optionsHtml = '<option value="">' + placeholderText + '</option>';
					response.data.options.forEach(function(opt) {
						optionsHtml += '<option value="' + opt.id + '">' + opt.title + '</option>';
					});
					$targetSelect.html(optionsHtml).prop('disabled', false);
				}
			},
			error: function() {
				$targetSelect.parent().removeClass('rem-loading');
			}
		});
	}

	/**
	 * Handles AJAX-based frontend filters
	 */
	function initFilters() {
		// Legacy elements fallback
		var $form = $('#rem-filters-form');
		var hasAdvForm = $('#rem-adv-search-form').length > 0;

		if (!$form.length && !hasAdvForm) return;

		if (hasAdvForm) {
			setupAdvancedFilters();
		} else {
			setupLegacyFilters($form);
		}
	}

	/**
	 * Setup event listeners for the advanced search bar
	 */
	function setupAdvancedFilters() {
		var $advForm = $('#rem-adv-search-form');
		var $mobForm = $('#rem-mobile-filter-form');

		// Toggle Desktop filter panel
		$('#rem-adv-toggle-btn').on('click', function(e) {
			e.preventDefault();
			$('#rem-adv-filter-panel').slideToggle(250);
			$(this).toggleClass('active');
		});

		// Open/Close Mobile Filter Panel overlay
		$('#rem-mobile-filter-trigger-btn').on('click', function() {
			$('#rem-mobile-filter-panel').addClass('active');
			$('body').addClass('rem-modal-open');
		});

		$('#rem-mobile-filter-close').on('click', function() {
			$('#rem-mobile-filter-panel').removeClass('active');
			$('body').removeClass('rem-modal-open');
		});

		// Apply filters on mobile
		$('#rem-mobile-apply-btn').on('click', function() {
			$('#rem-mobile-filter-panel').removeClass('active');
			$('body').removeClass('rem-modal-open');
			triggerFilterQuery(1);
		});

		// Helper to sync text, number inputs and selects
		function syncValue(name, val, $targetForm) {
			var $target = $targetForm.find('[name="' + name + '"]');
			if ($target.length && $target.val() !== val) {
				$target.val(val);
				if ($target.is('select') && (name === 'district_id' || name === 'taluk_id')) {
					// We need to trigger change but avoid infinite loops
					// Triggering change allows cascading select to query next child
					$target.trigger('change');
				}
			}
		}

		// Sync text inputs & selects
		$advForm.find('input[type="text"], input[type="number"], select').on('input change', function() {
			syncValue($(this).attr('name'), $(this).val(), $mobForm);
		});
		$mobForm.find('input[type="text"], input[type="number"], select').on('input change', function() {
			syncValue($(this).attr('name'), $(this).val(), $advForm);
		});

		// Sync checkboxes & radios
		$advForm.find('input[type="checkbox"], input[type="radio"]').on('change', function() {
			var name = $(this).attr('name');
			var val = $(this).val();
			var type = $(this).attr('type');
			if (type === 'checkbox') {
				var checked = $(this).prop('checked');
				var $target = $mobForm.find('[name="' + name + '"][value="' + val + '"]');
				$target.prop('checked', checked);
				$(this).parent().toggleClass('active', checked);
				$target.parent().toggleClass('active', checked);
			} else {
				// Radio
				var $target = $mobForm.find('[name="' + name + '"][value="' + val + '"]');
				$target.prop('checked', true);
				$(this).parent().addClass('active').siblings().removeClass('active');
				$target.parent().addClass('active').siblings().removeClass('active');
			}
		});

		$mobForm.find('input[type="checkbox"], input[type="radio"]').on('change', function() {
			var name = $(this).attr('name');
			var val = $(this).val();
			var type = $(this).attr('type');
			if (type === 'checkbox') {
				var checked = $(this).prop('checked');
				var $target = $advForm.find('[name="' + name + '"][value="' + val + '"]');
				$target.prop('checked', checked);
				$(this).parent().toggleClass('active', checked);
				$target.parent().toggleClass('active', checked);
			} else {
				// Radio
				var $target = $advForm.find('[name="' + name + '"][value="' + val + '"]');
				$target.prop('checked', true);
				$(this).parent().addClass('active').siblings().removeClass('active');
				$target.parent().addClass('active').siblings().removeClass('active');
			}
		});

		// Instant trigger on desktop selection changes
		$advForm.find('input[type="checkbox"], input[type="radio"], select').on('change', function() {
			if ($(window).width() > 768) {
				triggerFilterQuery(1);
			}
		});

		// Debounced trigger on desktop typing inputs
		var advDebounceTimer = null;
		$advForm.find('input[type="text"], input[type="number"]').on('keyup input', function() {
			if ($(window).width() > 768) {
				clearTimeout(advDebounceTimer);
				advDebounceTimer = setTimeout(function() {
					triggerFilterQuery(1);
				}, 500);
			}
		});

		// Handle Form submit (Search button/Enter key)
		$advForm.on('submit', function(e) {
			e.preventDefault();
			triggerFilterQuery(1);
		});

		// Desktop Clear Filters Action
		$('#rem-adv-reset-btn').on('click', function() {
			$advForm[0].reset();
			$advForm.find('.active').removeClass('active');
			resetSelect($('#rem-search-taluk'), 'Select Taluk');
			resetSelect($('#rem-search-location'), 'Select Location');

			$mobForm[0].reset();
			$mobForm.find('.active').removeClass('active');
			resetSelect($('#rem-mob-taluk'), 'Select Taluk');
			resetSelect($('#rem-mob-location'), 'Select Location');

			triggerFilterQuery(1);
		});

		// Mobile Clear Filters Action
		$('#rem-mobile-reset-btn').on('click', function() {
			$mobForm[0].reset();
			$mobForm.find('.active').removeClass('active');
			resetSelect($('#rem-mob-taluk'), 'Select Taluk');
			resetSelect($('#rem-mob-location'), 'Select Location');

			$advForm[0].reset();
			$advForm.find('.active').removeClass('active');
			resetSelect($('#rem-search-taluk'), 'Select Taluk');
			resetSelect($('#rem-search-location'), 'Select Location');

			triggerFilterQuery(1);
		});

		// Pagination click delegation
		setupPagination();
	}

	/**
	 * Setup legacy filters event bindings
	 */
	function setupLegacyFilters($form) {
		var debounceTimer = null;

		// 1. Text Search & Place Keyup (Debounced 400ms)
		$('#rem-search, #rem-place').on('keyup input', function() {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				triggerFilterQuery(1);
			}, 400);
		});

		// 2. Select Option Changes
		$form.find('select').on('change', function() {
			triggerFilterQuery(1);
		});

		// 3. Price inputs (debounced on keyup/change)
		$('#rem-min-price, #rem-max-price').on('keyup change input', function() {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				triggerFilterQuery(1);
			}, 500);
		});

		// 4. Clear Filters Action
		$('#rem-reset-filters').on('click', function() {
			$form[0].reset();
			resetSelect($('#rem-district'), 'Select District');
			resetSelect($('#rem-taluk'), 'Select Taluk');
			triggerFilterQuery(1);
		});

		// 5. Pagination Click Delegation
		setupPagination();
	}

	/**
	 * Paginate listings click delegation handler
	 */
	function setupPagination() {
		$(document).off('click', '.rem-property-pagination a').on('click', '.rem-property-pagination a', function(e) {
			e.preventDefault();
			
			// Find page number from href (e.g. /page/2/ or ?paged=2)
			var href = $(this).attr('href');
			var pageNum = 1;
			var match = href.match(/paged?=(\d+)/);
			if (match) {
				pageNum = parseInt(match[1]);
			} else {
				var slashMatch = href.match(/\/page\/(\d+)/);
				if (slashMatch) {
					pageNum = parseInt(slashMatch[1]);
				}
			}

			triggerFilterQuery(pageNum);

			// Smooth scroll back to listings top
			$('html, body').animate({
				scrollTop: $('.rem-results-header').offset().top - 100
			}, 400);
		});
	}

	/**
	 * Performs AJAX request to update properties and map markers
	 */
	function triggerFilterQuery(page) {
		var $wrapper = $('.rem-listing-wrapper');
		var $grid = $('.rem-property-grid');
		var $pagination = $('.rem-property-pagination');
		var $count = $('.rem-count-number');
		var $loader = $('.rem-loader-overlay');

		if (!$wrapper.length) return;

		$loader.fadeIn(150);

		// Read filter parameters
		var data = {
			action: 'rem_filter_properties',
			nonce: rem_params.nonce,
			paged: page
		};

		var $advForm = $('#rem-adv-search-form');
		if ($advForm.length) {
			data.search = $advForm.find('[name="search"]').val();
			data.district_id = $advForm.find('[name="district_id"]').val();
			data.taluk_id = $advForm.find('[name="taluk_id"]').val();
			data.place_id = $advForm.find('[name="place_id"]').val();

			var propertyTypes = [];
			$advForm.find('[name="property_type[]"]:checked').each(function() {
				propertyTypes.push($(this).val());
			});
			data.property_type = propertyTypes;

			var statuses = [];
			$advForm.find('[name="status[]"]:checked').each(function() {
				statuses.push($(this).val());
			});
			data.status = statuses;

			data.min_price = $advForm.find('[name="min_price"]').val();
			data.max_price = $advForm.find('[name="max_price"]').val();
			data.min_area = $advForm.find('[name="min_area"]').val();
			data.max_area = $advForm.find('[name="max_area"]').val();
			data.bedrooms = $advForm.find('[name="bedrooms"]:checked').val() || '';
			data.bathrooms = $advForm.find('[name="bathrooms"]:checked').val() || '';

			var amenities = [];
			$advForm.find('[name="amenities[]"]:checked').each(function() {
				amenities.push($(this).val());
			});
			data.amenities = amenities;
		} else {
			data.search = $('#rem-search').val();
			data.state_id = $('#rem-state').val();
			data.district_id = $('#rem-district').val();
			data.taluk_id = $('#rem-taluk').val();
			data.place_name = $('#rem-place').val();
			data.property_type = $('#rem-type').val();
			data.status = $('#rem-status').val();
			data.min_price = $('#rem-min-price').val();
			data.max_price = $('#rem-max-price').val();
		}

		$.ajax({
			url: rem_params.ajax_url,
			type: 'POST',
			data: data,
			success: function(response) {
				$loader.fadeOut(150);

				if (response.success) {
					// Update DOM
					$grid.html(response.data.cards);
					$pagination.html(response.data.pagination);
					$count.text(response.data.count);

					// Update Listing Map Markers
					updateListingMap(response.data.map_markers);
				}
			},
			error: function() {
				$loader.fadeOut(150);
			}
		});
	}

	function initReadMore() {
		var $desc = $('.rem-property-description');
		var $btn = $('#rem-read-more-btn');
		if (!$desc.length || !$btn.length) return;

		if ($desc[0].scrollHeight <= 180) {
			$btn.hide();
			$desc.css('max-height', 'none');
			return;
		}

		$btn.on('click', function(e) {
			e.preventDefault();
			if ($desc.hasClass('expanded')) {
				$desc.removeClass('expanded').animate({ 'max-height': '160px' }, 300);
				$btn.text('Read More');
			} else {
				$desc.addClass('expanded').animate({ 'max-height': $desc[0].scrollHeight + 'px' }, 300, function() {
					$desc.css('max-height', 'none');
				});
				$btn.text('Read Less');
			}
		});
	}

	function initEnquiryForm() {
		var $form = $('#rem-enquiry-form');
		if (!$form.length) return;

		$form.on('submit', function(e) {
			e.preventDefault();
			var $submitBtn = $form.find('button[type="submit"]');
			var $responseDiv = $form.find('.rem-enquiry-response');
			
			$submitBtn.prop('disabled', true).addClass('rem-loading');
			$responseDiv.removeClass('success error').html('Submitting enquiry...');

			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_submit_enquiry',
					nonce: rem_params.nonce,
					name: $('#enquiry-name').val(),
					phone: $('#enquiry-phone').val(),
					email: $('#enquiry-email').val(),
					message: $('#enquiry-message').val(),
					property_title: $form.find('input[name="property_title"]').val()
				},
				success: function(response) {
					$submitBtn.prop('disabled', false).removeClass('rem-loading');
					if (response.success) {
						$responseDiv.addClass('success').html('<span class="dashicons dashicons-yes"></span> ' + response.data.message);
						$form[0].reset();
					} else {
						$responseDiv.addClass('error').html('<span class="dashicons dashicons-warning"></span> ' + response.data.message);
					}
				},
				error: function() {
					$submitBtn.prop('disabled', false).removeClass('rem-loading');
					$responseDiv.addClass('error').html('<span class="dashicons dashicons-warning"></span> Enquiry failed (network error).');
				}
			});
		});
	}

	function initHeroSlider() {
		var $slider = $('.rem-hero-slider');
		if (!$slider.length) return;

		var $slides = $slider.find('.rem-slide');
		var totalSlides = $slides.length;
		if (totalSlides <= 1) return;

		var currentIndex = 0;

		function showSlide(index) {
			$slides.removeClass('active').css('opacity', 0);
			$slides.eq(index).addClass('active').animate({ 'opacity': 1 }, 400);
		}

		$slider.find('.rem-slider-prev').on('click', function(e) {
			e.preventDefault();
			currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
			showSlide(currentIndex);
		});

		$slider.find('.rem-slider-next').on('click', function(e) {
			e.preventDefault();
			currentIndex = (currentIndex + 1) % totalSlides;
			showSlide(currentIndex);
		});

		var slideInterval = setInterval(function() {
			currentIndex = (currentIndex + 1) % totalSlides;
			showSlide(currentIndex);
		}, 5000);

		$slider.on('mouseenter', function() {
			clearInterval(slideInterval);
		}).on('mouseleave', function() {
			slideInterval = setInterval(function() {
				currentIndex = (currentIndex + 1) % totalSlides;
				showSlide(currentIndex);
			}, 5000);
		});
	}

	/**
	 * Agent Dashboard Front-End Logic
	 */
	function initAgentDashboard() {
		// 1. Auth Tabs Toggle
		$('.rem-auth-tab-btn').on('click', function(e) {
			e.preventDefault();
			var target = $(this).data('target');
			
			$('.rem-auth-tab-btn').removeClass('active');
			$(this).addClass('active');
			
			$('.rem-auth-tab-content').removeClass('active');
			$('#' + target).addClass('active');
		});

		// 2. Property Submission Form Tab Navigation
		var tabs = ['tab-basic', 'tab-location', 'tab-specs', 'tab-amenities', 'tab-media', 'tab-contact'];
		var currentTabIdx = 0;

		function showTab(index) {
			currentTabIdx = index;
			var tabId = tabs[index];
			
			$('.rem-form-tab-btn').removeClass('active');
			$('.rem-form-tab-btn[data-tab="' + tabId + '"]').addClass('active');
			
			$('.rem-form-tab-panel').removeClass('active');
			$('#' + tabId).addClass('active');
			
			if (index === 0) {
				$('.prev-tab-btn').hide();
			} else {
				$('.prev-tab-btn').show();
			}
			
			if (index === tabs.length - 1) {
				$('.next-tab-btn').hide();
				$('.submit-form-btn').show();
			} else {
				$('.next-tab-btn').show();
				$('.submit-form-btn').hide();
			}
		}

		// Direct Tab Button Clicks
		$('.rem-form-tab-btn').on('click', function(e) {
			e.preventDefault();
			var tabId = $(this).data('tab');
			var idx = tabs.indexOf(tabId);
			if (idx !== -1) {
				// Validate current tab first if moving forward
				if (idx > currentTabIdx) {
					if (!validateCurrentTab()) {
						return;
					}
				}
				showTab(idx);
			}
		});

		// Next Button Click
		$('.next-tab-btn').on('click', function(e) {
			e.preventDefault();
			if (validateCurrentTab()) {
				if (currentTabIdx < tabs.length - 1) {
					showTab(currentTabIdx + 1);
				}
			}
		});

		// Back Button Click
		$('.prev-tab-btn').on('click', function(e) {
			e.preventDefault();
			if (currentTabIdx > 0) {
				showTab(currentTabIdx - 1);
			}
		});

		// Validate Fields on Current Active Tab
		function validateCurrentTab() {
			var tabId = tabs[currentTabIdx];
			var $panel = $('#' + tabId);
			var isValid = true;
			
			$panel.find('input, select, textarea').each(function() {
				if ($(this).is(':visible') && !this.checkValidity()) {
					isValid = false;
					this.reportValidity();
					return false; // break loop
				}
			});
			
			if (!isValid) return false;
			
			// Custom field format validations
			if (tabId === 'tab-location') {
				var pincodeVal = $('#prop-pincode').val();
				if (pincodeVal && !/^\d{6}$/.test(pincodeVal)) {
					alert('Please enter a valid 6-digit Pincode.');
					$('#prop-pincode').focus();
					return false;
				}
			}
			
			if (tabId === 'tab-contact') {
				var phoneVal = $('#agent-phone').val();
				if (phoneVal && !/^\+?[0-9\s\-]{10,15}$/.test(phoneVal)) {
					alert('Please enter a valid Phone Number (10 to 15 digits).');
					$('#agent-phone').focus();
					return false;
				}
				var whatsappVal = $('#agent-whatsapp').val();
				if (whatsappVal && !/^\+?[0-9\s\-]{10,15}$/.test(whatsappVal)) {
					alert('Please enter a valid WhatsApp Number (10 to 15 digits).');
					$('#agent-whatsapp').focus();
					return false;
				}
			}
			
			return true;
		}

		// Property Form Submit Validation
		$('#rem-property-form').on('submit', function(e) {
			for (var i = 0; i < tabs.length; i++) {
				currentTabIdx = i;
				if (!validateCurrentTab()) {
					showTab(i);
					e.preventDefault();
					return false;
				}
			}
		});

		// Registration Form Submit Validation
		$('#rem-register-form form').on('submit', function(e) {
			var phoneVal = $('#reg-phone').val();
			if (phoneVal && !/^\+?[0-9\s\-]{10,15}$/.test(phoneVal)) {
				alert('Please enter a valid Mobile Number (10 to 15 digits).');
				$('#reg-phone').focus();
				e.preventDefault();
				return false;
			}
			var whatsappVal = $('#reg-whatsapp').val();
			if (whatsappVal && !/^\+?[0-9\s\-]{10,15}$/.test(whatsappVal)) {
				alert('Please enter a valid WhatsApp Number (10 to 15 digits).');
				$('#reg-whatsapp').focus();
				e.preventDefault();
				return false;
			}
		});

		// 3. Parking Options Toggle
		$('#prop-parking').on('change', function() {
			if ($(this).val() === 'yes') {
				$('#parking-count-group').slideDown(200);
			} else {
				$('#parking-count-group').slideUp(200);
				$('#prop-parking-count').val('');
			}
		});

		// 4. Quick Delete Confirmation
		$(document).on('click', '.rem-delete-confirm', function(e) {
			if (!confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
				e.preventDefault();
				return false;
			}
		});

		// 5. Admin Approve Confirmation
		$(document).on('click', '.rem-admin-approve', function(e) {
			if (!confirm('Are you sure you want to approve this property? It will be published on the website.')) {
				e.preventDefault();
				return false;
			}
		});

		// 6. Admin Reject Reason Prompt
		$(document).on('click', '.rem-admin-reject', function(e) {
			e.preventDefault();
			var href = $(this).attr('href');
			var reason = prompt('Please enter the rejection reason for the Agent:');
			if (reason === null) {
				return false; // Cancelled
			}
			reason = $.trim(reason);
			if (reason === '') {
				alert('Rejection reason is required.');
				return false;
			}
			window.location.href = href + '&rejection_reason=' + encodeURIComponent(reason);
		});

		// 7. Location Auto-save on Change (only in Edit mode)
		$('#prop-state, #prop-district, #prop-taluk').on('change', function() {
			var $form = $('#rem-property-form');
			if (!$form.length) return;

			var $postIdInput = $form.find('input[name="post_id"]');
			if (!$postIdInput.length) {
				return; // Not in edit mode, don't auto-save
			}

			var postId = $postIdInput.val();
			if (!postId) return;

			var stateId = $('#prop-state').val();
			var districtId = $('#prop-district').val();
			var talukId = $('#prop-taluk').val();

			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_autosave_location',
					nonce: rem_params.nonce,
					post_id: postId,
					state_id: stateId,
					district_id: districtId,
					taluk_id: talukId
				},
				success: function(response) {
					if (response.success) {
						console.log('Location auto-saved automatically.');
					}
				}
			});
		});
	}

	function initFrontendNotifications() {
		if (typeof rem_params === 'undefined' || !$('.rem-db-bell-wrapper').length) {
			return;
		}

		var pollInterval = 30000; // 30 seconds

		function pollNotifications() {
			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_poll_notifications',
					nonce: rem_params.nonce
				},
				success: function(response) {
					if (response.success) {
						updateNotificationsUI(response.data);
					}
				}
			});
		}

		function updateNotificationsUI(data) {
			// Update bell count badge
			var badge = $('.rem-db-bell-badge');
			if (data.unread_count > 0) {
				badge.text(data.unread_count).show();
			} else {
				badge.hide();
			}

			// Update dashboard widgets counts
			if (data.widgets) {
				$('#rem-agent-db-total-count').text(data.widgets.total);
				$('#rem-agent-db-pending-count').text(data.widgets.pending);
				$('#rem-agent-db-approved-count').text(data.widgets.approved);
				$('#rem-agent-db-rejected-count').text(data.widgets.rejected);
				$('#rem-agent-db-unread-notifications-count').text(data.widgets.notifications);
			}

			// Update dropdown items list
			var dropdownList = $('.rem-db-notifications-dropdown .rem-notifications-list');
			if (dropdownList.length) {
				dropdownList.html(data.list_html);
			}

			// Trigger popup modal if a new alert exists
			if (data.popup) {
				showNotificationModal(data.popup);
			}
		}

		function showNotificationModal(popup) {
			var modal = $('#rem-notification-modal');
			if (modal.length) {
				modal.find('.rem-modal-body').html(popup.html);
				modal.css('display', 'flex').hide().fadeIn(300);
				modal.find('.rem-modal-content').css('transform', 'scale(0.9)').animate({
					transform: 'scale(1)'
				}, {
					step: function(now, fx) {
						$(this).css('transform', 'scale(' + (0.9 + (now * 0.1)) + ')');
					},
					duration: 200
				});
			}
		}

		// Toggle dropdown list visibility when bell trigger is clicked
		$(document).on('click', '.rem-db-bell-trigger', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var dropdown = $('.rem-db-notifications-dropdown');
			if (dropdown.is(':visible')) {
				dropdown.fadeOut(200);
			} else {
				dropdown.fadeIn(200);
				pollNotifications(); // Fetch immediately when opening
			}
		});

		// Close dropdown when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.rem-db-bell-wrapper, .rem-db-notifications-dropdown').length) {
				$('.rem-db-notifications-dropdown').fadeOut(200);
			}
		});

		// Close modal
		$(document).on('click', '#rem-notification-modal .rem-modal-close, #rem-notification-modal .rem-popup-close-btn', function() {
			var notifId = $(this).data('notif-id');
			$('#rem-notification-modal').fadeOut(200);
			if (notifId) {
				markNotificationRead(notifId);
			}
		});

		function markNotificationRead(id) {
			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_mark_notification_read',
					nonce: rem_params.nonce,
					id: id
				},
				success: function(response) {
					if (response.success) {
						pollNotifications();
					}
				}
			});
		}

		// Mark single read from list click
		$(document).on('click', '.rem-db-notifications-dropdown .rem-mark-read-btn', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var id = $(this).data('id');
			markNotificationRead(id);
		});

		// Mark all read from header click
		$(document).on('click', '.rem-db-notifications-dropdown .rem-mark-all-read-btn', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_mark_notification_read',
					nonce: rem_params.nonce,
					mark_all: 1
				},
				success: function(response) {
					if (response.success) {
						pollNotifications();
					}
				}
			});
		});

		// Approve property from Modal click (for Admin viewing dashboard on frontend)
		$(document).on('click', '#rem-notification-modal .rem-popup-approve', function() {
			var propId = $(this).data('id');
			var notifId = $(this).data('notif-id');
			if (!confirm('Are you sure you want to approve this property?')) {
				return;
			}
			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_approve_property_ajax',
					nonce: rem_params.nonce,
					property_id: propId,
					notif_id: notifId
				},
				success: function(response) {
					if (response.success) {
						$('#rem-notification-modal').fadeOut(200);
						pollNotifications();
						alert(response.data.message);
					}
				}
			});
		});

		// Reject property from Modal click (for Admin viewing dashboard on frontend)
		$(document).on('click', '#rem-notification-modal .rem-popup-reject', function() {
			var propId = $(this).data('id');
			var notifId = $(this).data('notif-id');
			var reason = prompt('Please enter the rejection reason for the Agent:');
			if (reason === null) {
				return;
			}
			reason = $.trim(reason);
			if (reason === '') {
				alert('Rejection reason is required.');
				return;
			}
			$.ajax({
				url: rem_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_reject_property_ajax',
					nonce: rem_params.nonce,
					property_id: propId,
					notif_id: notifId,
					reason: reason
				},
				success: function(response) {
					if (response.success) {
						$('#rem-notification-modal').fadeOut(200);
						pollNotifications();
						alert(response.data.message);
					}
				}
			});
		});

		// Start polling
		pollNotifications(); // First run
		setInterval(pollNotifications, pollInterval);
	}

})(jQuery);
