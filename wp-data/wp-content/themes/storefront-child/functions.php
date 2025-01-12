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

//function add_request_button_to_out_of_stock() {
//    global $product;
//    if ( !$product->is_in_stock() ) {
//        echo '<button id="request_button" class="request-button" data-product-id="'.$product->id.'">שלח בקשה</button>';
//    }
//}
//add_action( 'woocommerce_single_product_summary', 'add_request_button_to_out_of_stock', 20 );
//
////daily schedule
//function schedule_api_request(): void
//{
//    if ( ! wp_next_scheduled( 'send_request_to_external_api' ) ) {
//        wp_schedule_event( time(), 'daily', 'send_request_to_external_api' );
//    }
//}
//add_action( 'wp', 'schedule_api_request' );
//
//// Function to send a direct product request via API
//function send_direct_product_request(int $product_id): array {
//    $url = home_url('/ProductAPI.php');
//
//    $response = wp_remote_post($url, array(
//        'method'    => 'POST',
//        'body'      => json_encode(array('product_id' => $product_id)),
//        'headers'   => array('Content-Type' => 'application/json'),
//    ));
//
//    if (is_wp_error($response)) {
//        return array('status' => 'error', 'message' => $response->get_error_message());
//    }
//
//    $data = json_decode(wp_remote_retrieve_body($response), true);
//    return array('status' => 'success', 'message' => $data['msg']);
//}
//
//// Call the function in scheduled task (daily schedule)
//function send_request_to_external_api(): void {
//    $product_id = 123; // Example product ID
//    $response = send_direct_product_request($product_id);
//
//    if ($response['status'] === 'success') {
//        error_log('Request successful for product ID: ' . $product_id);
//    } else {
//        error_log('Request failed: ' . $response['message']);
//    }
//}
//add_action('send_request_to_external_api', 'send_request_to_external_api');
//
//// AJAX call to handle product request via button click
//#[NoReturn] function send_request_on_button_click(): void {
//    if (!isset($_POST['product_id'])) {
//        wp_send_json_error("Missing product ID.");
//    }
//
//    $product_id = sanitize_text_field($_POST['product_id']);
//    $response = send_direct_product_request($product_id);
//
//    if ($response['status'] === 'success') {
//        wp_send_json_success('Request successful for product ID: ' . $product_id);
//    } else {
//        wp_send_json_error('Request failed: ' . $response['message']);
//    }
//
//    wp_die();
//}
//add_action('wp_ajax_request_product', 'send_request_on_button_click');
//add_action('wp_ajax_nopriv_request_product', 'send_request_on_button_click');
//
//// Schedule the task with WP-Cron
//function schedule_product_requests() {
//    if (!wp_next_scheduled('send_request_to_external_api')) {
//        wp_schedule_event(time(), 'hourly', 'send_request_to_external_api'); // Adjust interval as needed
//    }
//}
//add_action('wp', 'schedule_product_requests');
