(function($) {
    function resetColorsToDefault() {
        // Define default values for your color settings
        const defaultColors = {
            'background_color': '#ffffff',
            'solar_renewable_energy_primary_color': '#70bf4a',
            'organic_farm_top_bg_color': '#f4f9ff',
            'organic_farm_heading_color': '#222222',
            'organic_farm_text_color' :'#707070',
            'organic_farm_primary_fade': '#eaffeb',
            'organic_farm_post_bg': '#ffffff',
            'organic_farm_footer_bg': '#222222',
        };

        // Iterate over each setting and set it to its default value
        for (let settingId in defaultColors) {
            wp.customize(settingId).set(defaultColors[settingId]);
        }

        // Optionally refresh the preview
        wp.customize.previewer.refresh();
    }

    // Attach reset function to global scope
    window.resetColorsToDefault = resetColorsToDefault;

    $(document).ready(function() {
        $('.color-reset-btn').val('RESET'); // This adds the 'RESET' text inside the button
    });
})(jQuery);