<?php
// Sweet Dreams Bakery - Order Creation Backend
// This script handles order creation with VAT calculation and file storage

// Set content type to JSON for API response
header('Content-Type: application/json');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Product prices array
$productPrices = [
    'chocolate_croissant' => 3.50,
    'strawberry_tart' => 4.20,
    'tiramisu' => 5.80,
    'macarons' => 8.50
];

// Product names for display
$productNames = [
    'chocolate_croissant' => 'Chocolate Croissant',
    'strawberry_tart' => 'Strawberry Tart',
    'tiramisu' => 'Tiramisu',
    'macarons' => 'Macarons (6 pieces)'
];

// Function to calculate order total with VAT
function calculateOrderTotal($products, $quantities, $productPrices) {
    $subtotal = 0;
    $orderDetails = [];
    
    for ($i = 0; $i < count($products); $i++) {
        if (isset($products[$i]) && isset($quantities[$i])) {
            $product = $products[$i];
            $quantity = intval($quantities[$i]);
            
            if (isset($productPrices[$product]) && $quantity > 0) {
                $price = $productPrices[$product];
                $itemTotal = $price * $quantity;
                $subtotal += $itemTotal;
                
                $orderDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal
                ];
            }
        }
    }
    
    $vatRate = 0.21; // 21% VAT
    $vatAmount = $subtotal * $vatRate;
    $totalWithVAT = $subtotal + $vatAmount;
    
    return [
        'subtotal' => $subtotal,
        'vatAmount' => $vatAmount,
        'totalWithVAT' => $totalWithVAT,
        'orderDetails' => $orderDetails
    ];
}

// Function to save order to binary file
function saveOrderToFile($orderData, $filePath) {
    // Create orders directory if it doesn't exist
    $ordersDir = dirname($filePath);
    if (!is_dir($ordersDir)) {
        mkdir($ordersDir, 0755, true);
    }
    
    // Read existing orders
    $orders = [];
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);
        if (!empty($fileContent)) {
            $orders = unserialize($fileContent);
        }
    }
    
    // Add new order
    $orders[] = $orderData;
    
    // Save back to file
    $result = file_put_contents($filePath, serialize($orders));
    return $result !== false;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

try {
    // Validate required fields
    $requiredFields = ['orderId', 'customerName', 'customerEmail', 'customerPhone', 'customerAddress', 'products', 'quantities'];
    
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Required field '$field' is missing or empty.");
        }
    }
    
    // Sanitize input data
    $orderId = trim($_POST['orderId']);
    $customerName = trim($_POST['customerName']);
    $customerEmail = trim($_POST['customerEmail']);
    $customerPhone = trim($_POST['customerPhone']);
    $customerAddress = trim($_POST['customerAddress']);
    $products = $_POST['products'];
    $quantities = $_POST['quantities'];
    
    // Validate email format
    if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }
    
    // Calculate order total
    $orderCalculation = calculateOrderTotal($products, $quantities, $productPrices);
    
    if (empty($orderCalculation['orderDetails'])) {
        throw new Exception("No valid products selected.");
    }
    
    // Prepare order data
    $orderData = [
        'orderId' => $orderId,
        'customerName' => $customerName,
        'customerEmail' => $customerEmail,
        'customerPhone' => $customerPhone,
        'customerAddress' => $customerAddress,
        'products' => $orderCalculation['orderDetails'],
        'subtotal' => $orderCalculation['subtotal'],
        'vatAmount' => $orderCalculation['vatAmount'],
        'totalWithVAT' => $orderCalculation['totalWithVAT'],
        'createdAt' => date('Y-m-d H:i:s')
    ];
    
    // Save order to file
    $filePath = '../../onlineOrders/onlineOrders.db';
    $saveResult = saveOrderToFile($orderData, $filePath);
    
    if (!$saveResult) {
        throw new Exception("Failed to save order to file.");
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Order created successfully!',
        'orderId' => $orderId,
        'totalWithVAT' => $orderCalculation['totalWithVAT'],
        'orderDetails' => $orderCalculation['orderDetails']
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>

