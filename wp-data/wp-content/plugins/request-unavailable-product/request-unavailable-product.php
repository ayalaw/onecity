<?php
/*
Plugin Name: Unavailable Product Request
Description: Allows users to request unavailable products via an API.
Version: 1.0
Author: Ayala Bergman
*/

require_once 'stats-page.php';
require_once 'migration.php';
require_once 'schedules.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//Register necessary actions
register_activation_hook(__FILE__, 'request_product_plugin_activate');
register_deactivation_hook(__FILE__, 'request_product_plugin_deactivate');

/**
 * @return void
 */
function request_product_plugin_activate(): void {
    create_product_request_table();
    create_product_request_stats_table();
    schedule_product_requests();
}

/**
 * @return void
 */
function request_product_plugin_deactivate(): void {
    drop_product_request_table();
    drop_product_request_stats_table();
    wp_clear_scheduled_hook('send_request_to_external_api');
}

function request_product_enqueue_styles(): void {
    // Enqueue the plugin's stylesheet
    wp_enqueue_style('request-product-style',
        plugin_dir_url(__FILE__) . 'css/style.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'request_product_enqueue_styles');

function request_product_enqueue_scripts(): void {
    // Enqueue your script
    wp_enqueue_script('product-request-script', plugin_dir_url(__FILE__) . 'js/request-product.js', ['jquery'], '1.0.0', true);

    // Localize the script with necessary data
    wp_localize_script('product-request-script', 'myLocalizedData', [
        'ajaxurl' => admin_url('admin-ajax.php'), // WordPress AJAX URL
        'nonce'   => wp_create_nonce('request_product_nonce'), // Nonce for security
    ]);
}
add_action('wp_enqueue_scripts', 'request_product_enqueue_scripts');

/**
 * @return void
 * Function Add a "product request" button
 */
function request_product_button(): void {
    global $product;
    if(! $product->is_in_stock() ) {
        echo '<button id="request_button" class="request-product-button" data-product-id="' . esc_attr( $product->get_id() ) . '">Request Product</button>';
    }
}

add_action( 'plugins_loaded', function() {
    if ( class_exists( 'WooCommerce' ) ) {
        add_action( 'woocommerce_single_product_summary', 'request_product_button' );
    }
});

function add_floating_message(): void {
    echo '<div id="floating_message"></div>';
}
add_action('wp_footer', 'add_floating_message');

// Function to send a direct product request via API
function send_direct_product_request(int $product_id): array {
    $url = 'http://host.docker.internal:8080/ProductRequestAPI.php';

    $response = wp_remote_post($url, array(
        'method'    => 'POST',
        'body'      => json_encode(array('product_id' => $product_id)),
        'headers'   => array('Content-Type' => 'application/json'),
    ));

    if (is_wp_error($response)) {
        return ['status' => 'error', 'message' => $response->get_error_message()];
    }

    $data = json_decode($response['body'], true);
    try {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix.'product_request',
            array(
                'product_id' => $product_id,
                'response' => $data['status'],
                'created_at' => date('Y-m-d H:i:s'),
            ),
        );
    } catch (exception $e) {
        error_log($e->getMessage());
    }

    return ['status' => $data['status'], 'message' => $data['msg']];
}

/**
 * @return void
 */
function send_request_on_button_click(): void {

    // Verify the nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'request_product_nonce')) {
        wp_send_json_error(ProductRequestMessage::SECURITY_FAILED->value);
    }

    // Validate the product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(ProductRequestMessage::MISSING_PRODUCT_ID->value);
    }

    // Sanitize the product ID
    $product_id = sanitize_text_field($_POST['product_id']);

    // Call the function to process the product request
    $response = send_direct_product_request((int)$product_id);

    // Handle the response and send it back
    if (!empty($response) && isset($response['status']) && $response['status'] > 0) {
        wp_send_json_success($response);
    } else {
        $error_message = $response['msg'] ?? 'Unknown error occurred.';
        wp_send_json_error('The Request failed: ' . $error_message);
    }

    // End execution to ensure no extra output
    wp_die();
}

// Hook the AJAX actions
add_action('wp_ajax_request_product', 'send_request_on_button_click');
add_action('wp_ajax_nopriv_request_product', 'send_request_on_button_click');




