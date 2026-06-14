jQuery(document).ready(function ($) {
    // Attach click event to the dismiss button
    $(document).on('click', '.notice[data-notice="get-start"] button.notice-dismiss', function () {
        // Dismiss the notice via AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'packers_logistic_dismissed_notice',
            },
            success: function () {
                // Remove the notice on success
                $('.notice[data-notice="get-start"]').remove();
            }
        });
    });
});

// Plugin – AI Content Writer plugin activation
document.addEventListener('DOMContentLoaded', function () {
    const packers_logistic_button = document.getElementById('install-activate-button');
    const packers_logistic_config = window.installPluginData || window.pluginInstallerData;

    if (!packers_logistic_button || !packers_logistic_config) return;

    packers_logistic_button.addEventListener('click', function (e) {
        e.preventDefault();

        const packers_logistic_redirectUrl = packers_logistic_button.getAttribute('data-redirect') || packers_logistic_config.redirectUrl;

        packers_logistic_button.textContent = 'Setting up plugins & demo...';

        const packers_logistic_installData = new FormData();
        packers_logistic_installData.append('action', 'install_and_activate_required_plugin');
        packers_logistic_installData.append('nonce', packers_logistic_config.nonce);

        fetch(packers_logistic_config.ajaxurl, {
            method: 'POST',
            body: packers_logistic_installData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                window.location.href = packers_logistic_redirectUrl;
            } else {
                alert('Activation error: ' + (res.data?.message || 'Unknown error'));
                packers_logistic_button.textContent = 'Try Again';
            }
        })
        .catch(error => {
            alert('Request failed: ' + error.message);
            packers_logistic_button.textContent = 'Try Again';
        });
    });
});