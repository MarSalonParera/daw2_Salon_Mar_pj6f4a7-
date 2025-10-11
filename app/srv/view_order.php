<?php
// Sweet Dreams Bakery - View Specific Order Backend
// This script searches for and returns a specific order by ID

// Set content type to JSON for API response
header('Content-Type: application/json');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to load orders from binary file
function loadOrdersFromFile($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    
    $fileContent = file_get_contents($filePath);
    if (empty($fileContent)) {
        return [];
    }
    
    $orders = unserialize($fileContent);
    return is_array($orders) ? $orders : [];
}

// Function to find order by ID
function findOrderById($orders, $orderId) {
    foreach ($orders as $order) {
        if (isset($order['orderId']) && $order['orderId'] === $orderId) {
            return $order;
        }
    }
    return null;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Use POST.']);
    exit;
}

try {
    // Validate required fields
    if (!isset($_POST['orderId']) || empty(trim($_POST['orderId']))) {
        throw new Exception("Order ID is required.");
    }
    
    $orderId = trim($_POST['orderId']);
    
    // Load orders from file
    $filePath = '../../onlineOrders/onlineOrders.db';
    $orders = loadOrdersFromFile($filePath);
    
    if (empty($orders)) {
        echo json_encode([
            'success' => true,
            'order' => null,
            'message' => 'No orders found in the system.'
        ]);
        exit;
    }
    
    // Search for the order
    $foundOrder = findOrderById($orders, $orderId);
    
    if ($foundOrder) {
        // Return the found order
        echo json_encode([
            'success' => true,
            'order' => $foundOrder,
            'message' => 'Order found successfully.'
        ]);
    } else {
        // Order not found
        echo json_encode([
            'success' => true,
            'order' => null,
            'message' => 'Order not found with ID: ' . $orderId
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
