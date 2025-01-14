<?php
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__);
}
require_once ABSPATH . '/ProductRequestMessage.php';

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
    $availableProducts = [12, 14, 16, 85];
    $comingSoon = [13, 84];

    if (in_array($productId, $availableProducts)) {
        $status = ProductRequestMessage::AVAILABLE;
    } elseif (in_array($productId, $comingSoon)) {
        $status = ProductRequestMessage::COMING_SOON;
    } else {
        $status = ProductRequestMessage::UNAVAILABLE;
    }

    if($status) {
        return ['status' => $status->name, 'msg' => $status->value];
    }

    return ['status' => ProductRequestMessage::MISSING_PRODUCT_ID->name, 'msg' => ProductRequestMessage::MISSING_PRODUCT_ID->value];
}