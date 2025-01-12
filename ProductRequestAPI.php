<?php

require_once '/var/www/html/wp-load.php';
require_once __DIR__ . '/ProductRequestMessage.php';

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse the POST data
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['product_id'])) {
        $productId = intval($input['product_id']);

        $response = handle_product_request($productId);

        echo json_encode($response);
        exit;
    } else {
        echo json_encode(['status' => ProductRequestMessage::NOT_FOUND->name, 'msg' => ProductRequestMessage::NOT_FOUND->value]);
        exit;
    }
}

// If the request is not POST, return an error
echo json_encode(['status' => ProductRequestMessage::ERROR->name, 'msg' => ProductRequestMessage::ERROR->value]);
exit;

/**
 * Check the availability of a product.
 */
function handle_product_request(int $productId): array {
    $availableProducts = [12, 14, 15];
    $comingSoon = [13];

    if (in_array($productId, $availableProducts)) {
        $status = ProductRequestMessage::AVAILABLE;
    } elseif (in_array($productId, $comingSoon)) {
        $status = ProductRequestMessage::COMING_SOON;
    } else {
        $status = ProductRequestMessage::UNAVAILABLE;
    }

    try {
        global $wpdb;
        $res = $wpdb->insert(
            $wpdb->prefix.'product_request',
            array(
                'product_id' => $productId,
                'response' => $status->name,
                'created_at' => date('Y-m-d H:i:s'),
            ),
        );
    } catch (Exception $e) {
        return ['status' => $status->name, 'msg' => $e->getMessage()];
    }

    if($res) {
        return ['status' => $status->name, 'msg' => $status->value];
    }

    return ['status' => ProductRequestMessage::ERROR->name, 'msg' => ProductRequestMessage::ERROR->value];
}
