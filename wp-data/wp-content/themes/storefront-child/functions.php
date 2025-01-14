<?php
// Enqueue the parent theme stylesheet
use JetBrains\PhpStorm\NoReturn;

// Enqueue the parent theme stylesheet
function storefront_child_enqueue_styles(): void {
    // Enqueue the parent theme's style
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Enqueue the child theme's style
    wp_enqueue_style('storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array('storefront-style'));
}

add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');


function storefront_child_enqueue_scripts(): void {
    wp_enqueue_script( 'child-custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_scripts' );

function set_default_role_customer($user_id): void {
    $user = get_userdata($user_id);
    if (in_array('subscriber', $user->roles)) {
        $user->set_role('customer');
    }
}
add_action('user_register', 'set_default_role_customer');

function custom_out_of_stock_text($availability, $product) {
    if (!$product->is_in_stock()) {
        return 'Unavailable';
    }
    return $availability;
}

add_filter('woocommerce_get_availability_text', 'custom_out_of_stock_text', 10, 2);