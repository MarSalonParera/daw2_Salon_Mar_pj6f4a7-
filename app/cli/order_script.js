// Sweet Dreams Bakery - Order Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Product prices
    const productPrices = {
        'chocolate_croissant': 3.50,
        'strawberry_tart': 4.20,
        'tiramisu': 5.80,
        'macarons': 8.50
    };

    // Product names for display
    const productNames = {
        'chocolate_croissant': 'Chocolate Croissant',
        'strawberry_tart': 'Strawberry Tart',
        'tiramisu': 'Tiramisu',
        'macarons': 'Macarons (6 pieces)'
    };

    // Get all product checkboxes
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    
    // Add event listeners to product checkboxes
    productCheckboxes.forEach(function(checkbox, index) {
        checkbox.addEventListener('change', function() {
            const quantityInput = document.getElementById('qty' + (index + 1));
            const quantityDiv = quantityInput.parentElement;
            
            if (this.checked) {
                quantityDiv.style.display = 'block';
            } else {
                quantityDiv.style.display = 'none';
                quantityInput.value = 1; // Reset quantity
            }
            
            updateOrderSummary();
        });
    });

    // Add event listeners to quantity inputs
    const quantityInputs = document.querySelectorAll('input[name="quantities[]"]');
    quantityInputs.forEach(function(input) {
        input.addEventListener('input', updateOrderSummary);
    });

    // Function to update order summary
    function updateOrderSummary() {
        const summaryDiv = document.getElementById('orderSummary');
        const totalDiv = document.getElementById('totalWithVAT');
        const totalAmountSpan = document.getElementById('totalAmount');
        
        let total = 0;
        let summaryHTML = '';
        
        productCheckboxes.forEach(function(checkbox, index) {
            if (checkbox.checked) {
                const productValue = checkbox.value;
                const quantity = parseInt(document.getElementById('qty' + (index + 1)).value) || 1;
                const price = productPrices[productValue];
                const subtotal = price * quantity;
                
                total += subtotal;
                
                summaryHTML += `
                    <div class="d-flex justify-content-between mb-2">
                        <span>${productNames[productValue]} x${quantity}</span>
                        <span>€${subtotal.toFixed(2)}</span>
                    </div>
                `;
            }
        });
        
        if (total > 0) {
            summaryDiv.innerHTML = summaryHTML;
            totalDiv.style.display = 'block';
            
            // Calculate VAT (21%)
            const vatAmount = total * 0.21;
            const totalWithVAT = total + vatAmount;
            
            totalAmountSpan.textContent = '€' + totalWithVAT.toFixed(2);
        } else {
            summaryDiv.innerHTML = '<p class="text-muted">Select products to see order summary</p>';
            totalDiv.style.display = 'none';
        }
    }

    // Form validation and submission
    const orderForm = document.getElementById('orderForm');
    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if at least one product is selected
        const checkedProducts = document.querySelectorAll('.product-checkbox:checked');
        if (checkedProducts.length === 0) {
            alert('Please select at least one product.');
            return;
        }
        
        // Validate quantities
        let hasValidQuantity = false;
        checkedProducts.forEach(function(checkbox, index) {
            const quantityInput = document.getElementById('qty' + (index + 1));
            if (quantityInput && parseInt(quantityInput.value) > 0) {
                hasValidQuantity = true;
            }
        });
        
        if (!hasValidQuantity) {
            alert('Please enter valid quantities for selected products.');
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Order...';
        submitBtn.disabled = true;
        
        // Submit form via AJAX
        const formData = new FormData(this);
        
        fetch('../srv/create_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Order created successfully!\nOrder ID: ' + data.orderId + '\nTotal with VAT: €' + data.totalWithVAT.toFixed(2));
                
                // Reset form
                this.reset();
                document.getElementById('orderSummary').innerHTML = '<p class="text-muted">Select products to see order summary</p>';
                document.getElementById('totalWithVAT').style.display = 'none';
                
                // Hide quantity inputs
                document.querySelectorAll('.quantity-input').forEach(function(input) {
                    input.style.display = 'none';
                });
            } else {
                alert('Error creating order: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the order. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Auto-generate order ID if empty
    const orderIdInput = document.getElementById('orderId');
    orderIdInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            const timestamp = new Date().getTime();
            this.value = 'ORD-' + timestamp;
        }
    });

    function sendData(){				
    // Creation of objects associated with interface elements
    const values = new FormData(document.getElementById("FormCalc")); //Get form values into the values variable
    const result = document.getElementById("result");
                    
    // Request construction
    const ip = "192.168.1.34"; //server IP address
    const folder = "sm6ex5"; //folder where the PHP file is located
    const phpFile = "sm6ex5.php"; //PHP file name
    const request = "http://" + ip + "/" + folder + "/" + phpFile;
    //const request = phpFile;
    
    // Send the request, wait for response and collect the response without reloading the page
    fetch(request, {
            method: 'POST',
            body: values
    }) // Send the request using POST method
    .then(response => response.json()) // Collect the response in JSON format
    .then(resultData => { //When all the sent response is collected, it's stored in 'resultData' and the code inside brackets is executed
        result.textContent = "The result is " + resultData.sum; //sum has the same name as the key name in line 11 of sm6ex5.php						
    })
    .catch(errors => { //Error handling						
        result.textContent = "Error calculating the sum";
    });
}

function clearData(){				
    document.getElementById("num1").value = "";
    document.getElementById("num2").value = "";
}

function clearResult(){				
    document.getElementById("result").textContent = "";
}
});
