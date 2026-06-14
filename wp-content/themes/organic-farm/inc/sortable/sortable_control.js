// sortable control
wp.customize.controlConstructor['organic-farm-sortable'] = wp.customize.Control.extend({
    ready: function () {
        'use strict';
        var control = this;

        // Set the sortable container.
        control.sortableContainer = control.container.find('ul.sortable').first();

        // Init sortable.
        control.sortableContainer.sortable({
            // Update value when we stop sorting.
            stop: function () {
                control.updateValue();
            }
        }).disableSelection().find('li').each(function () {
            // Enable/disable options when we click on the eye icon.
            jQuery(this).find('i.visibility').click(function () {
                jQuery(this).toggleClass('dashicons-hidden').toggleClass('dashicons-visibility').parents('li:eq(0)').toggleClass('invisible');
                control.updateValue();
            });
        }).click(function () {
            // Update value on click.
            control.updateValue();
        });
    },

    /**
     * Updates the sorting list
     */
    updateValue: function () {
        'use strict';
        var control = this,
            newValue = [];

        this.sortableContainer.find('li').each(function () {
            if (!jQuery(this).is('.invisible')) {
                newValue.push(jQuery(this).data('value'));
            }
        });

        control.setting.set(newValue);

        // Toggle classes for hidden items.
        this.sortableContainer.find('li').each(function () {
            if (jQuery(this).is('.invisible')) {
                jQuery(this).find('i.visibility').removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                jQuery(this).find('i.visibility').removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        });
    }
});