<?php

$organic_farm_custom_style= "";


//colors
$color = get_theme_mod('solar_renewable_energy_primary_color', '#70bf4a');

$organic_farm_custom_style .= ":root {";
    $organic_farm_custom_style .= "--theme-primary-color: {$color};";
$organic_farm_custom_style .= "}";


$solar_renewable_energy_slider_opacity = get_theme_mod( 'solar_renewable_energy_slider_opacity','0.5');

if($solar_renewable_energy_slider_opacity == '0'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.1'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.1';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.2'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.2';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.3'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.3';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.4'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.4';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.5'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.5';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.6'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.6';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.7'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.7';
$organic_farm_custom_style .='}';

}else if($solar_renewable_energy_slider_opacity == '0.8'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.8';
$organic_farm_custom_style .='}';

}
else if($solar_renewable_energy_slider_opacity == '0.9'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 0.9';
$organic_farm_custom_style .='}';

}
else if($solar_renewable_energy_slider_opacity == '1'){
$organic_farm_custom_style .='#slider img {';
    $organic_farm_custom_style .='opacity: 1';
$organic_farm_custom_style .='}';

}

$solar_renewable_energy_slider_heading_color = get_theme_mod( 'solar_renewable_energy_slider_heading_color','#ffffff');
$organic_farm_custom_style .='#slider .carousel-caption h2 {';
$organic_farm_custom_style .='color: '.esc_attr($solar_renewable_energy_slider_heading_color).';';
$organic_farm_custom_style .='}';

$solar_renewable_energy_slider_excerpt_color = get_theme_mod( 'solar_renewable_energy_slider_excerpt_color','#ffffff');
$organic_farm_custom_style .='#slider .slider-excerpt {';
$organic_farm_custom_style .='color: '.esc_attr($solar_renewable_energy_slider_excerpt_color).';';
$organic_farm_custom_style .='}';