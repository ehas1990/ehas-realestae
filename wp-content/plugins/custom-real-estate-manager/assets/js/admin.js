/**
 * Admin Real Estate Manager scripts
 * Handles dynamic cascading Select2 filtering and custom AJAX background auto-save.
 */
(function($) {
	// --- Admin List Screen Confirm Actions ---
	$(document).ready(function() {
		// Confirm Approve click
		$(document).on('click', '.row-actions .approve a, .rem-admin-approve', function(e) {
			if (!confirm('Are you sure you want to approve this property? It will be published on the website.')) {
				e.preventDefault();
				return false;
			}
		});

		// Reject click: prompt for reason
		$(document).on('click', '.row-actions .reject a, .rem-admin-reject', function(e) {
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
	});

	if (typeof acf !== 'undefined') {
		$(document).ready(function() {
		// --- 1. Cascading Dynamic Dropdowns ---

		// When State field changes, reset District, Taluk, and Place fields.
		acf.addAction('new_field/key=field_property_state', function(field) {
			field.on('change', function() {
				var district_field = acf.getField('field_property_district');
				if (district_field) {
					district_field.val('');
					district_field.trigger('change');
				}
			});
		});

		// When District field changes, reset Taluk and Place fields.
		acf.addAction('new_field/key=field_property_district', function(field) {
			field.on('change', function() {
				var taluk_field = acf.getField('field_property_taluk');
				if (taluk_field) {
					taluk_field.val('');
					taluk_field.trigger('change');
				}
			});
		});

		// Intercept ACF Select2 AJAX requests to inject parent location IDs.
		acf.addFilter('select2_ajax_data', function(data, action, $el, field, spinner) {
			var fieldKey = field.get('key');

			if (fieldKey === 'field_property_district') {
				var stateField = acf.getField('field_property_state');
				data.state_id = stateField ? stateField.val() : '';
			} else if (fieldKey === 'field_property_taluk') {
				var districtField = acf.getField('field_property_district');
				data.district_id = districtField ? districtField.val() : '';
			}

			return data;
		});

		// --- 2. Custom Background Auto-Save ---

		if (typeof rem_admin_params !== 'undefined') {
			var isDirty = false;

			// Listen to ACF field changes to mark form as dirty
			acf.addAction('change', function($el) {
				isDirty = true;
			});

			// Show auto-save status bar
			function showAutosaveStatus(message, type) {
				var statusDiv = $('#rem-autosave-status');
				if (!statusDiv.length) {
					var header = $('#acf-group_property_details .postbox-header');
					if (header.length) {
						header.append('<div id="rem-autosave-status"></div>');
						statusDiv = $('#rem-autosave-status');
					} else {
						// Fallback to top of form
						$('#acf-group_property_details').prepend('<div id="rem-autosave-status"></div>');
						statusDiv = $('#rem-autosave-status');
					}
				}

				statusDiv.removeClass('success error saving').addClass(type);
				
				var iconHtml = '';
				if (type === 'saving') {
					iconHtml = '<span class="rem-spinner-icon"></span>';
				} else if (type === 'success') {
					iconHtml = '<span class="dashicons dashicons-yes"></span>';
				} else {
					iconHtml = '<span class="dashicons dashicons-warning"></span>';
				}

				statusDiv.html(iconHtml + '<span class="rem-autosave-text">' + message + '</span>');
				statusDiv.css('display', 'inline-flex').fadeIn(300);

				// Auto-fade success message
				if (type === 'success') {
					setTimeout(function() {
						statusDiv.fadeOut(1000);
					}, 4000);
				}
			}

			// Background auto-save interval
			setInterval(function() {
				var postStatus = $('#original_post_status').val() || $('#post_status').val();
				
				// Only auto-save if form is dirty and post is a draft/auto-draft
				if (isDirty && (postStatus === 'draft' || postStatus === 'auto-draft')) {
					showAutosaveStatus('Auto-saving draft...', 'saving');

					// Serialize the form
					var formData = $('#post').serialize();

					$.ajax({
						url: rem_admin_params.ajax_url,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'rem_autosave_property',
							nonce: rem_admin_params.nonce,
							post_id: rem_admin_params.post_id,
							form_data: formData
						},
						success: function(response) {
							if (response.success) {
								isDirty = false;
								showAutosaveStatus('Draft auto-saved at ' + response.data.time, 'success');
							} else {
								showAutosaveStatus('Auto-save failed: ' + response.data.message, 'error');
							}
						},
						error: function() {
							showAutosaveStatus('Auto-save failed (network error).', 'error');
						}
					});
				}
			}, 45000); // Save every 45 seconds

			// Highlight tabs with validation errors and switch to the first invalid tab
			acf.addFilter('validation_complete', function(json, $form) {
				// Clear any previous error indicators from all tabs
				$form.find('.acf-tab-group li').removeClass('rem-tab-error');
				$form.find('.acf-tab-group li a .rem-error-dot').remove();

				if (json.errors && json.errors.length) {
					var firstTabToSelect = null;

					json.errors.forEach(function(error) {
						// Look for field element matching either name/key or within field wrappers
						var $field = $form.find('.acf-field[data-name="' + error.input + '"], .acf-field[data-key="' + error.input + '"]');
						if (!$field.length) {
							var $input = $form.find('[name="' + error.input + '"], [name*="[' + error.input + ']"]');
							if ($input.length) {
								$field = $input.closest('.acf-field');
							}
						}

						if ($field.length) {
							// Find the preceding tab field sibling
							var $tabField = $field.prevAll('.acf-field-tab').first();
							if ($tabField.length) {
								var tabKey = $tabField.attr('data-key');
								if (tabKey) {
									var $tabLi = $form.find('.acf-tab-group li a[data-key="' + tabKey + '"]').parent();
									if ($tabLi.length && !$tabLi.hasClass('rem-tab-error')) {
										$tabLi.addClass('rem-tab-error');
										$tabLi.find('a').append('<span class="rem-error-dot">●</span>');
									}

									// Track first invalid tab to select
									if (!firstTabToSelect) {
										firstTabToSelect = tabKey;
									}
								}
							}
						}
					});

					// Switch view to the first invalid tab so user immediately sees the error field
					if (firstTabToSelect) {
						$form.find('.acf-tab-group li a[data-key="' + firstTabToSelect + '"]').trigger('click');
					}
				}
				return json;
			});
		}
	});
	}

	// --- Notifications Polling and Modal Actions ---
	$(ready_or_not);
	function ready_or_not() {
		if (typeof rem_admin_params === 'undefined') {
			return;
		}

		var pollInterval = 30000; // 30 seconds

		function pollNotifications() {
			$.ajax({
				url: rem_admin_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_poll_notifications',
					nonce: rem_admin_params.nonce
				},
				success: function(response) {
					if (response.success) {
						updateNotificationsUI(response.data);
					}
				}
			});
		}

		function updateNotificationsUI(data) {
			// Update admin bar bell count badge
			var badge = $('.rem-admin-bell-badge');
			if (data.unread_count > 0) {
				badge.text(data.unread_count).show();
			} else {
				badge.hide();
			}

			// Update dashboard widget counts
			if (data.widgets) {
				$('#rem-admin-db-total-count').text(data.widgets.total);
				$('#rem-admin-db-pending-count').text(data.widgets.pending);
				$('#rem-admin-db-approved-count').text(data.widgets.approved);
				$('#rem-admin-db-rejected-count').text(data.widgets.rejected);
				$('#rem-admin-db-unread-notifications-count').text(data.widgets.notifications);
			}

			// Update dropdown items list
			var dropdownList = $('#rem-admin-notifications-dropdown .rem-notifications-list');
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

		// Toggle dropdown list visibility when bell in admin bar is clicked
		$(document).on('click', '#wp-admin-bar-rem-admin-notifications a', function(e) {
			e.preventDefault();
			var dropdown = $('#rem-admin-notifications-dropdown');
			if (dropdown.is(':visible')) {
				dropdown.fadeOut(200);
			} else {
				// Position dropdown underneath the admin bar bell icon
				var bellOffset = $(this).offset();
				dropdown.css({
					top: '32px',
					right: '15px'
				}).fadeIn(200);
				pollNotifications(); // Fetch immediately when opening
			}
		});

		// Close dropdown when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('#wp-admin-bar-rem-admin-notifications, #rem-admin-notifications-dropdown').length) {
				$('#rem-admin-notifications-dropdown').fadeOut(200);
			}
		});

		// Close modal
		$(document).on('click', '.rem-modal-close, .rem-popup-close-btn', function() {
			var notifId = $(this).data('notif-id');
			$('#rem-notification-modal').fadeOut(200);
			if (notifId) {
				markNotificationRead(notifId);
			}
		});

		function markNotificationRead(id) {
			$.ajax({
				url: rem_admin_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_mark_notification_read',
					nonce: rem_admin_params.nonce,
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
		$(document).on('click', '#rem-admin-notifications-dropdown .rem-mark-read-btn', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var id = $(this).data('id');
			markNotificationRead(id);
		});

		// Mark all read from header click
		$(document).on('click', '#rem-admin-notifications-dropdown .rem-mark-all-read-btn', function(e) {
			e.preventDefault();
			$.ajax({
				url: rem_admin_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_mark_notification_read',
					nonce: rem_admin_params.nonce,
					mark_all: 1
				},
				success: function(response) {
					if (response.success) {
						pollNotifications();
					}
				}
			});
		});

		// Approve property from Modal click
		$(document).on('click', '#rem-notification-modal .rem-popup-approve', function() {
			var propId = $(this).data('id');
			var notifId = $(this).data('notif-id');
			if (!confirm('Are you sure you want to approve this property?')) {
				return;
			}
			$.ajax({
				url: rem_admin_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_approve_property_ajax',
					nonce: rem_admin_params.nonce,
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

		// Reject property from Modal click
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
				url: rem_admin_params.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'rem_reject_property_ajax',
					nonce: rem_admin_params.nonce,
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
