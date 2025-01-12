<?php

// Call the function in scheduled task (daily schedule)
function schedule_update_stats(): void {
    global $wpdb;

    $current_month = date('Y-m-01');
    $validResponses = implode("', '", ProductRequestMessage::ValidResponse());
    $table_name_inserted = $wpdb->prefix . 'product_request_stats';
    $table_name_fetch = $wpdb->prefix . 'product_request';
    $validResponse = $wpdb->prepare("
        INSERT INTO $table_name_inserted (product_id, month, is_valid, total)
        SELECT 
            product_id, 
            '$current_month',
            CASE 
                WHEN response IN ('$validResponses') THEN 1 
                ELSE 0 
            END AS is_valid,
            COUNT(*) AS cnt
        FROM $table_name_fetch
        WHERE created_at >= %s
        GROUP BY product_id, is_valid
        ON DUPLICATE KEY UPDATE total = VALUES(total), is_valid = VALUES(is_valid);
    ", $current_month);
    try {
        error_log($wpdb->get_var($validResponse));
        error_log('stats for valid response completed at ' . date('Y-m-d H:i:s'));
        $wpdb->query($validResponse);
    } catch (Exception $ex) {
        error_log('stats for valid response failed at ' . date('Y-m-d H:i:s'));
        error_log($ex->getMessage());
    }
}
add_action('schedule_update_stats', 'schedule_update_stats');


//// Schedule the task with WP-Cron
function schedule_product_requests(): void {
    if (!wp_next_scheduled('schedule_update_stats')) {
        wp_schedule_event(time(), 'daily', 'schedule_update_stats'); // Adjust interval as needed
    }
}
add_action('wp', 'schedule_product_requests');
//do_action('schedule_update_stats');