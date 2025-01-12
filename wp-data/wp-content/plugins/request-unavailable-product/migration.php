<?php

/**
 * @return void
 */
function create_product_request_table(): void {
    global $wpdb;

    $table_name = $wpdb->prefix . 'product_request';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id BIGINT(20) UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        response VARCHAR(50) NOT NULL,
        PRIMARY KEY (id),
        KEY idx_product_id (product_id), 
        KEY idx_created_at (created_at), 
        KEY idx_month_valid_product (month, is_valid, product_id) 
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * @return void
 */
function create_product_request_stats_table(): void {
    global $wpdb;

    $table_name = $wpdb->prefix . 'product_request_stats';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id BIGINT(20) UNSIGNED NOT NULL,
        month DATE NOT NULL,
        is_valid TINYINT(1) NOT NULL,
        total INT NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY unique_month_valid_product (product_id, month, is_valid),
        KEY idx_product_id (product_id),  
        KEY idx_month (month),  
        KEY idx_is_valid (is_valid),  
        KEY idx_product_month_valid (month, is_valid, product_id) 
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


/**
 * @return void
 */
function drop_product_request_table(): void {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_request';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "DROP TABLE $table_name;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * @return void
 */
function drop_product_request_stats_table(): void {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_request_stats';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "DROP TABLE $table_name;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}