// Sweet Dreams Bakery - View Order JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchOrderForm');
    const orderDetails = document.getElementById('orderDetails');
    const orderContent = document.getElementById('orderContent');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    const noResults = document.getElementById('noResults');
    const noResultsText = document.getElementById('noResultsText');

    // Product names for display
    const productNames = {
        'chocolate_croissant': 'Chocolate Croissant',
        'strawberry_tart': 'Strawberry Tart',
        'tiramisu': 'Tiramisu',
        'macarons': 'Macarons (6 pieces)'
    };

    // Function to format currency
    function formatCurrency(amount) {
        return '€' + parseFloat(amount).toFixed(2);
    }

    // Function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB') + ' ' + date.toLocaleTimeString('en-GB', {hour: '2-digit', minute: '2-digit'});
    }

    // Function to hide all messages
    function hideAllMessages() {
        orderDetails.style.display = 'none';
        errorMessage.style.display = 'none';
        noResults.style.display = 'none';
    }

    // Function to display order details
    function displayOrderDetails(order) {
        let productsHTML = '';
        order.products.forEach(function(product) {
            const productName = productNames[product.product] || product.product;
            productsHTML += `
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>${productName}</strong>
                    </div>
                    <div class="col-md-2 text-center">
                        <span class="badge bg-secondary">x${product.quantity}</span>
                    </div>
                    <div class="col-md-2 text-end">
                        ${formatCurrency(product.price)}
                    </div>
                    <div class="col-md-2 text-end">
                        <strong>${formatCurrency(product.total)}</strong>
                    </div>
                </div>
            `;
        });

        const orderHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-receipt me-2"></i>Order Information
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Order ID:</strong></td>
                            <td>${order.orderId}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>${formatDate(order.createdAt)}</td>
                        </tr>
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>${formatCurrency(order.subtotal)}</td>
                        </tr>
                        <tr>
                            <td><strong>VAT (21%):</strong></td>
                            <td>${formatCurrency(order.vatAmount)}</td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>Total with VAT:</strong></td>
                            <td><strong>${formatCurrency(order.totalWithVAT)}</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-user me-2"></i>Customer Information
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>${order.customerName}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><a href="mailto:${order.customerEmail}" class="text-decoration-none">${order.customerEmail}</a></td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td><a href="tel:${order.customerPhone}" class="text-decoration-none">${order.customerPhone}</a></td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>${order.customerAddress}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-12">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-birthday-cake me-2"></i>Products Ordered
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${productsHTML}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        orderContent.innerHTML = orderHTML;
        orderDetails.style.display = 'block';
    }

    // Form submission handler
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const orderId = document.getElementById('orderId').value.trim();
        
        if (!orderId) {
            hideAllMessages();
            errorText.textContent = 'Please enter an order ID.';
            errorMessage.style.display = 'block';
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
        submitBtn.disabled = true;

        // Hide previous messages
        hideAllMessages();

        // Make AJAX request
        fetch('../srv/view_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'orderId=' + encodeURIComponent(orderId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.order) {
                    displayOrderDetails(data.order);
                } else {
                    noResultsText.textContent = 'No order found with ID: ' + orderId;
                    noResults.style.display = 'block';
                }
            } else {
                errorText.textContent = data.error || 'An error occurred while searching for the order.';
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorText.textContent = 'An error occurred while searching for the order. Please try again.';
            errorMessage.style.display = 'block';
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Clear form and messages when order ID input changes
    document.getElementById('orderId').addEventListener('input', function() {
        if (this.value.trim() === '') {
            hideAllMessages();
        }
    });
});
