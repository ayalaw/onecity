<?php

require_once '/var/www/html/ProductRequestMessage.php';

// Hook to add the stats page to the admin menu
function request_product_plugin_stats_page(): void {
    add_menu_page(
        'Product Request Statistics',
        'Product Requests',
        'manage_options',
        'product-request-stats',
        'render_product_request_stats',
        'dashicons-chart-bar',
        30
    );
}
add_action('admin_menu', 'request_product_plugin_stats_page');

function custom_admin_scripts($hook): void {
    // Load scripts only on the stats page.
    if ($hook !== 'toplevel_page_product-request-stats') {
        return;
    }

    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'custom-tabs-js',
        plugin_dir_url(__FILE__) . 'js/stats-page.js',
        array('jquery'),
        false,
        true // Load in footer
    );

    wp_enqueue_style(
        'custom-tabs-css',
        plugin_dir_url(__FILE__) . 'css/stats-page.css',
    );
}
add_action('admin_enqueue_scripts', 'custom_admin_scripts');

// Function to render the stats page
function render_product_request_stats(): void {
    $free_search = isset($_GET['free_search']) ? sanitize_text_field($_GET['free_search']) : '';

    $statsObj = new ProductRequestStats();
    $results = $statsObj->getItems($free_search);
    $stats = $statsObj->getStats();
    $mainStats = $statsObj->getTopStats();
    $current = $mainStats['current'];
    $last = $mainStats['last'];

    echo '<div class="wrap">';
    echo '<h1>Product Request Statistics</h1>';
    echo '<div class="">';

    if(!empty($current)){
        echo '<h4>Most requested plugin this month:  
            <span>' .  $current['post_title'] . ' ('. $current['total'] . ' requests)</span>
        </h4>';
    }

    if(!empty($last)) {
        echo '<h4>Most requested plugin last month:  
            <span>' .  $last['post_title'] . ' ('. $last['total'] . ' requests)</span>
        </h4>';
    }

    echo '</div>';
    echo '<h2 class="nav-tab-wrapper">
            <a href="#overview" class="nav-tab nav-tab-active">Overview</a>
            <a href="#items" class="nav-tab">Items</a>
        </h2>';
    echo '<div id="overview" class="tab-content" style="display: block;">';
    // Display statistics in a table format
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>Product Title</th>
                <th>Month</th>
                <th>Is Valid</th>
                <th>Total</th>
            </tr>
        </thead><tbody>';
    foreach ($stats as $row) {
        echo '<tr>
                <td>' . esc_html($row->post_title) . ' (' . esc_html($row->product_id) . ')</td>
                <td>' . date('m/Y', strtotime($row->month)) . '</td>
                <td>' . ($row->is_valid == 1 ? 'Yes' : 'No') . '</td>
                <td>' . $row->total . '</td>
            </tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
    echo '<div id="items" class="tab-content" style="display: none;">';
    echo '<form method="get" action="" id="form_search">';
    echo '<input type="hidden" name="page" value="product-request-stats">';
    echo '<label for="free_search">Search plugin:</label>';
    echo '<input type="text" name="search" id="free_search" value="' . esc_attr($free_search) . '">';
    echo '<button type="submit" class="button button-primary">Search</button>';
    echo '</form>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>Product Title</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead><tbody>';
    foreach ($results as $row) {
        echo '<tr>
                <td>' . esc_html($row->post_title) . ' (' . $row->product_id . ')</td>
                <td>' . ProductRequestMessage::GetEnumValue($row->response) . '</td>
                <td>' . date('d/m/Y H:i:s', strtotime($row->created_at)) . '</td>
            </tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}


class ProductRequestStats
{
    /**
     * @return array
     */
    public function getItems(string $freeSearch = null): array {
        global $wpdb;

        $table_name = esc_sql($wpdb->prefix . 'product_request');
        $post_table = esc_sql($wpdb->prefix . 'posts');

        try {
            $sql = "
                SELECT pr.product_id, post_title, response, pr.created_at 
                FROM $table_name pr
                INNER JOIN $post_table p 
                ON pr.product_id = p.id 
                       AND post_type = 'product' 
                WHERE p.post_status = 'publish'";
            if(!empty($freeSearch)){
                $sql .= $wpdb->prepare(" AND p.post_title LIKE %s", '%'.$freeSearch.'%');
            }

            return $wpdb->get_results($sql);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getStats(): array {
        global $wpdb;

        $table_name = esc_sql($wpdb->prefix . 'product_request_stats');
        $post_table = esc_sql($wpdb->prefix . 'posts');
        try {
            return $wpdb->get_results(
                "SELECT pr.product_id, post_title, count(*) AS count, 
                    month, is_valid, total
                FROM $table_name pr
                INNER JOIN $post_table p 
                ON pr.product_id = p.id 
                       AND post_type = 'product' 
                WHERE p.post_status = 'publish'
                GROUP BY product_id, is_valid, month");
        } catch (Exception $e) {
            error_log('Database error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @return null[]
     */
    public function getTopStats(): array {
        global $wpdb;

        $table_name = esc_sql($wpdb->prefix . 'product_request_stats');
        $post_table = esc_sql($wpdb->prefix . 'posts');

        $currentMonth = date('Y-m-01');
        $lastMonth = date('Y-m-01', strtotime('-1 month'));

        $sql = "
            SELECT pr.product_id, p.post_title, SUM(pr.total) AS total
            FROM $table_name pr
            INNER JOIN $post_table p 
            ON pr.product_id = p.id 
                AND p.post_type = 'product' 
            WHERE p.post_status = 'publish'
                AND pr.month = %s
            GROUP BY pr.product_id, p.post_title
            ORDER BY total DESC
            LIMIT 1
        ";

        $current = $wpdb->get_row($wpdb->prepare($sql, $currentMonth), ARRAY_A);
        $last = $wpdb->get_row($wpdb->prepare($sql, $lastMonth), ARRAY_A);

        return [
            'current' => $current ?: null,
            'last' => $last ?: null,
        ];
    }
}



