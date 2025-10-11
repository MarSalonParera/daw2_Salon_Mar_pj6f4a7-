<?php
// Sweet Dreams Bakery - View All Orders
// This script displays all orders stored in the system

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

// Function to format currency
function formatCurrency($amount) {
    return '€' . number_format($amount, 2);
}

// Function to format date
function formatDate($dateString) {
    return date('d/m/Y H:i', strtotime($dateString));
}

// Load orders
$filePath = '../../onlineOrders/onlineOrders.db';
$orders = loadOrdersFromFile($filePath);

// Product names for display
$productNames = [
    'chocolate_croissant' => 'Chocolate Croissant',
    'strawberry_tart' => 'Strawberry Tart',
    'tiramisu' => 'Tiramisu',
    'macarons' => 'Macarons (6 pieces)'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Dreams Bakery - All Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <nav class="breadcrumb">
                    <a href="../cli/index.html" class="breadcrumb-item">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                    <a href="../cli/operation.html" class="breadcrumb-item">Operations Menu</a>
                    <span class="breadcrumb-item active">All Orders</span>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h1 class="h3 mb-0">
                            <i class="fas fa-list me-2"></i>Sweet Dreams Bakery - All Orders
                        </h1>
                    </div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h4>No Orders Found</h4>
                                <p class="mb-0">There are no orders in the system yet. Create your first order to get started!</p>
                            </div>
                        <?php else: ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted">Total Orders: <span class="badge bg-primary"><?php echo count($orders); ?></span></h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">Last updated: <?php echo date('d/m/Y H:i'); ?></small>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Products</th>
                                            <th>Total with VAT</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($order['orderId']); ?></strong>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($order['customerName']); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($order['customerPhone']); ?></small>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($order['customerEmail']); ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($order['customerEmail']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php foreach ($order['products'] as $product): ?>
                                                        <span class="badge bg-secondary me-1">
                                                            <?php 
                                                            $productName = isset($productNames[$product['product']]) ? $productNames[$product['product']] : $product['product'];
                                                            echo htmlspecialchars($productName) . ' x' . $product['quantity'];
                                                            ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <strong class="text-success"><?php echo formatCurrency($order['totalWithVAT']); ?></strong>
                                                </td>
                                                <td>
                                                    <?php echo formatDate($order['createdAt']); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary Statistics -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">
                                                <i class="fas fa-receipt me-2"></i>Total Orders
                                            </h5>
                                            <h3 class="text-primary"><?php echo count($orders); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-success">
                                                <i class="fas fa-euro-sign me-2"></i>Total Revenue
                                            </h5>
                                            <h3 class="text-success">
                                                <?php 
                                                $totalRevenue = array_sum(array_column($orders, 'totalWithVAT'));
                                                echo formatCurrency($totalRevenue);
                                                ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-info">
                                                <i class="fas fa-chart-line me-2"></i>Average Order
                                            </h5>
                                            <h3 class="text-info">
                                                <?php 
                                                $avgOrder = count($orders) > 0 ? $totalRevenue / count($orders) : 0;
                                                echo formatCurrency($avgOrder);
                                                ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="../cli/index.html" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                    <a href="../cli/operation.html" class="btn btn-outline-primary">
                        <i class="fas fa-cogs me-2"></i>Operations Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

