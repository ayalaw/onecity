<?php
// Enqueue the parent theme stylesheet
function storefront_child_enqueue_styles()
{
    // Enqueue the parent theme's style
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Enqueue the child theme's style
    wp_enqueue_style('storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array('storefront-style'));
}

add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');

function storefront_child_enqueue_scripts() {
    wp_enqueue_script( 'child-custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), null, true );

    // Localize the ajaxurl for frontend JS and pass other variables if needed
    wp_localize_script('child-custom-script', 'myLocalizedData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('my_nonce') // Optional: create a nonce for security
    ));
}
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_scripts' );

function add_request_button_to_out_of_stock() {
    global $product;
    if ( !$product->is_in_stock() ) {
        echo '<button id="request_button" class="request-button" data-product-id="'.$product->id.'">שלח בקשה</button>';
    }
}
add_action( 'woocommerce_single_product_summary', 'add_request_button_to_out_of_stock', 20 );

//daily schedule
function schedule_api_request() {
    if ( ! wp_next_scheduled( 'send_request_to_external_api' ) ) {
        wp_schedule_event( time(), 'daily', 'send_request_to_external_api' );
    }
}
add_action( 'wp', 'schedule_api_request' );

//call to API (daily schedule)
function send_request_to_external_api() {
    $url = 'https://external-api.com/request';
    $product_id = 123;
    $response = wp_remote_post( $url, array(
        'method'    => 'POST',
        'body'      => json_encode( array('product_id' => $product_id) ),
        'headers'   => array( 'Content-Type' => 'application/json' )
    ));
    if ( is_wp_error( $response ) ) {
        wp_send_json_success('Request failed for product ID: ' . $product_id);
    } else {
        // טיפול בתגובה (לדוגמה, שמירת מספר אישור או הודעת שגיאה)
        $data = json_decode( wp_remote_retrieve_body( $response ), true );
    }
}
add_action( 'send_request_to_external_api', 'send_request_to_external_api' );

function send_request_on_button_click() {
    if ( isset($_POST['product_id']) ) {
        $product_id = $_POST['product_id'];
        $response = wp_remote_post( 'https://external-api.com/request', array(
            'body' => json_encode( array( 'product_id' => $product_id ) ),
            'headers' => array( 'Content-Type' => 'application/json' ),
        ));

        if ( is_wp_error( $response ) ) {
            wp_send_json_success('Request failed for product ID: ' . $product_id);
        } else {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( isset( $data['status'] ) && $data['status'] === 'success' ) {
                wp_send_json_success('Request successful for product ID: ' . $product_id);
            } else {
                wp_send_json_success('Request failed for product ID: ' . $product_id);
            }
        }
    } else {
        wp_send_json_error("Missing product ID.");
    }
    wp_die();
}
add_action( 'wp_ajax_request_product', 'send_request_on_button_click' );
add_action( 'wp_ajax_nopriv_request_product', 'send_request_on_button_click' );

