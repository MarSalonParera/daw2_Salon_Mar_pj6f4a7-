<?php
// Sweet Dreams Bakery - Configuration File

// Application Configuration
define('APP_NAME', 'Sweet Dreams Bakery');
define('APP_VERSION', '1.0.0');
define('APP_ENVIRONMENT', 'development'); // development, production

// Database Configuration
define('ORDERS_FILE_PATH', __DIR__ . '/onlineOrders/onlineOrders.db');
define('ORDERS_DIR', __DIR__ . '/onlineOrders/');

// VAT Configuration
define('VAT_RATE', 0.21); // 21% VAT
define('VAT_DISPLAY_RATE', '21%');

// Product Configuration
$PRODUCTS = [
    'chocolate_croissant' => [
        'name' => 'Chocolate Croissant',
        'price' => 3.50,
        'description' => 'Buttery croissant filled with rich chocolate'
    ],
    'strawberry_tart' => [
        'name' => 'Strawberry Tart',
        'price' => 4.20,
        'description' => 'Fresh strawberry tart with vanilla cream'
    ],
    'tiramisu' => [
        'name' => 'Tiramisu',
        'price' => 5.80,
        'description' => 'Classic Italian dessert with coffee and mascarpone'
    ],
    'macarons' => [
        'name' => 'Macarons (6 pieces)',
        'price' => 8.50,
        'description' => 'Assorted French macarons in various flavors'
    ]
];

// Security Configuration
define('MAX_ORDER_ID_LENGTH', 50);
define('MAX_CUSTOMER_NAME_LENGTH', 100);
define('MAX_EMAIL_LENGTH', 100);
define('MAX_PHONE_LENGTH', 20);
define('MAX_ADDRESS_LENGTH', 200);

// Error Reporting Configuration
if (APP_ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Utility Functions
function formatCurrency($amount) {
    return '€' . number_format($amount, 2);
}

function formatDate($dateString) {
    return date('d/m/Y H:i', strtotime($dateString));
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function createOrdersDirectory() {
    if (!is_dir(ORDERS_DIR)) {
        mkdir(ORDERS_DIR, 0755, true);
    }
}

function loadOrdersFromFile() {
    createOrdersDirectory();
    
    if (!file_exists(ORDERS_FILE_PATH)) {
        return [];
    }
    
    $fileContent = file_get_contents(ORDERS_FILE_PATH);
    if (empty($fileContent)) {
        return [];
    }
    
    $orders = unserialize($fileContent);
    return is_array($orders) ? $orders : [];
}

function saveOrdersToFile($orders) {
    createOrdersDirectory();
    return file_put_contents(ORDERS_FILE_PATH, serialize($orders)) !== false;
}

function findOrderById($orders, $orderId) {
    foreach ($orders as $order) {
        if (isset($order['orderId']) && $order['orderId'] === $orderId) {
            return $order;
        }
    }
    return null;
}

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
    
    $vatAmount = $subtotal * VAT_RATE;
    $totalWithVAT = $subtotal + $vatAmount;
    
    return [
        'subtotal' => $subtotal,
        'vatAmount' => $vatAmount,
        'totalWithVAT' => $totalWithVAT,
        'orderDetails' => $orderDetails
    ];
}
?>
