<?php
/**
 * Template for displaying search forms in Eco Solar Power
 *
 * @subpackage Eco Solar Power
 * @since 1.0
 * @version 0.1
 */
?>

<?php $luzuk_eco_solar_power_unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e('Search for:','eco-solar-power'); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder','eco-solar-power' ); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
	</label>
	<button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'eco-solar-power' ); ?></button>
</form>