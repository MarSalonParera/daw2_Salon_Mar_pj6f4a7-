# Sweet Dreams Bakery - E-commerce System

A comprehensive order management application designed for pastry shops to efficiently handle customer orders through an online platform.

## 🍰 Features

- **Order Creation**: Create new customer orders with complete client information and product selection
- **Order Management**: View all orders and search for specific orders by ID
- **Automatic VAT Calculation**: 21% VAT automatically calculated on all orders
- **Secure Data Storage**: Orders stored in binary file format for data persistence
- **Responsive Design**: Modern, mobile-friendly interface using Bootstrap 5
- **Real-time Updates**: AJAX-powered forms for seamless user experience

## 🛠️ Technologies Used

### Frontend
- **HTML5** - Semantic markup structure
- **CSS3** - Custom styling with Bootstrap 5 framework
- **JavaScript (ES6+)** - Client-side interactions and form handling
- **Bootstrap 5** - Responsive UI components
- **Font Awesome** - Icons and visual elements

### Backend
- **PHP 7.4+** - Server-side processing and business logic
- **Binary File Storage** - Secure data persistence
- **JSON API** - RESTful communication between frontend and backend

## 📁 Project Structure

```
eCommerce/
├── app/
│   ├── cli/                    # Client-side files
│   │   ├── index.html         # Main application interface
│   │   ├── documentation.html # Application documentation
│   │   ├── operation.html     # Operations menu
│   │   ├── create_order.html  # Order creation form
│   │   ├── view_order.html    # Order lookup form
│   │   ├── order_script.js    # Order form JavaScript
│   │   ├── view_order_script.js # Order lookup JavaScript
│   │   ├── styles.css         # Custom CSS styles
│   │   └── img/
│   │       └── logo.jpg       # Application logo
│   └── srv/                   # Server-side files
│       ├── create_order.php   # Order creation backend
│       ├── view_all_orders.php # Display all orders
│       └── view_order.php     # Order lookup backend
├── onlineOrders/              # Orders storage directory
│   └── onlineOrders.db        # Binary orders database
├── .gitignore                 # Git ignore file
└── README.md                  # Project documentation
```

## 🚀 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd eCommerce
   ```

2. **Configure Web Server**
   - Ensure Apache web server is running
   - Configure document root to point to the project directory
   - Enable PHP support

3. **Set Permissions**
   ```bash
   chmod 755 onlineOrders/
   chmod 644 app/srv/*.php
   ```

4. **Access the Application**
   - Open your web browser
   - Navigate to `http://localhost/eCommerce/app/cli/index.html`
   - For HTTPS: `https://localhost/eCommerce/app/cli/index.html`

## 📋 Usage

### 1. Application Overview
- **Home Page**: Welcome interface with navigation to documentation and operations
- **Documentation**: Comprehensive guide explaining all system features
- **Operations Menu**: Central hub for all order management functions

### 2. Creating Orders
1. Navigate to "Create New Order" from the operations menu
2. Fill in order ID (auto-generated if left empty)
3. Enter complete customer information
4. Select products from the pastry menu
5. Specify quantities for selected items
6. Review order summary with VAT calculation
7. Submit to create and save the order

### 3. Viewing Orders
- **All Orders**: View complete list of all orders with summary statistics
- **Specific Order**: Search for individual orders using order ID

## 🎯 Available Products

| Product | Price | Description |
|---------|-------|-------------|
| Chocolate Croissant | €3.50 | Buttery croissant filled with rich chocolate |
| Strawberry Tart | €4.20 | Fresh strawberry tart with vanilla cream |
| Tiramisu | €5.80 | Classic Italian dessert with coffee and mascarpone |
| Macarons (6 pieces) | €8.50 | Assorted French macarons in various flavors |

## 🔧 Technical Specifications

### HTTP Methods
- **GET**: Used for reading operations (viewing orders)
- **POST**: Used for writing operations (creating orders)

### Data Storage
- Orders stored in binary format using PHP serialization
- File location: `onlineOrders/onlineOrders.db`
- Automatic file creation on first order

### VAT Calculation
- Standard VAT rate: 21%
- Applied to all product subtotals
- Displayed separately in order summaries

### Security Features
- Input validation and sanitization
- Email format validation
- Required field validation
- Error handling and user feedback

## 🎨 Design Features

- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Modern UI**: Clean, professional interface with smooth animations
- **Color Scheme**: Purple/blue gradient theme with green accents
- **Typography**: Clear, readable fonts with proper hierarchy
- **Icons**: Font Awesome icons for better visual communication

## 🔒 Security Considerations

- All user inputs are validated and sanitized
- Email addresses are validated using PHP filters
- File permissions are properly configured
- Sensitive data is stored securely in binary format
- HTTPS recommended for production deployment

## 📱 Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is developed for educational purposes as part of the CFGS Desenvolupament d'Aplicacions Web curriculum.

## 👥 Development Team

- **Project**: PJ6 Back-End amb tecnologia PHP i gestors de continguts
- **Course**: CFGS Desenvolupament d'Aplicacions Web
- **Institution**: Escola del Clot
- **Academic Year**: 2025-26

## 📞 Support

For technical support or questions about this application, please refer to the documentation section within the application or contact the development team.

---

**Sweet Dreams Bakery** - *Delicious Pastries & Sweet Treats* 🍰
