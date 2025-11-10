# 🍕 Taste Transport - Online Food Delivery System

A comprehensive web-based food delivery platform built with **PHP**, **MySQL**, and **Bootstrap**. Taste Transport enables users to browse restaurants, order food, manage bookings, and leave reviews, while providing administrators with complete control over restaurants, menus, orders, and discounts.

## 📋 Table of Contents
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [User Roles](#user-roles)
- [API Endpoints](#api-endpoints)
- [Screenshots](#screenshots)
- [Future Enhancements](#future-enhancements)

---

## ✨ Features

### User Features
- **User Authentication**: Secure registration and login system
- **Browse Restaurants**: View all available restaurants with filters and ratings
- **Restaurant Details**: Check menus, opening hours, discounts, and reviews
- **Food Ordering**: Add items to cart and complete checkout
- **Order Tracking**: Monitor order status and delivery details
- **Order History**: View past orders and manage them
- **Table Reservations**: Book tables at restaurants
- **Reviews & Ratings**: Submit ratings and reviews for completed orders
- **User Profile**: Update personal information and address

### Admin Features
- **Dashboard**: Overview of key metrics and statistics
- **Restaurant Management**: Add, edit, and delete restaurants
- **Menu Management**: Manage restaurant categories and dishes
- **Order Management**: View and update order statuses
- **Discount Management**: Create and manage restaurant and dish discounts
- **User Management**: View and manage registered users
- **Booking Management**: Review and confirm table reservations
- **Review Monitoring**: View and moderate customer reviews

---

## 💻 Technology Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3, Bootstrap 4, JavaScript, jQuery |
| **Backend** | PHP 8.1+ |
| **Database** | MySQL 8.0+ |
| **Server** | Apache/Nginx |
| **Additional** | Font Awesome, Data Tables, Chart.js |

---

## 📁 Project Structure

```
taste_transport/
├── index.php                 # Homepage
├── login.php                 # User login
├── registration.php          # User registration
├── restaurants.php           # Browse restaurants
├── dishes.php                # View menu items
├── book_table.php            # Table booking
├── my_profile.php            # User profile
├── my_bookings.php           # User bookings
├── your_orders.php           # Order history
├── checkout.php              # Checkout process
├── submit_review.php         # Review submission
├── delete_orders.php         # Order management
├── logout.php                # Logout
│
├── admin/                    # Admin panel
│   ├── dashboard.php         # Admin dashboard
│   ├── add_restaurant.php    # Add restaurants
│   ├── add_menu.php          # Add menu items
│   ├── add_category.php      # Add categories
│   ├── all_orders.php        # View all orders
│   ├── all_users.php         # User management
│   ├── manage_bookings.php   # Table bookings
│   ├── manage_discounts.php  # Discount management
│   ├── admin_reviews.php     # Review moderation
│   └── css/, js/, images/    # Admin assets
│
├── connection/
│   └── connect.php           # Database connection
│
├── include/
│   ├── header.php            # Header template
│   └── footer.php            # Footer template
│
├── css/                      # Stylesheets
├── js/                       # JavaScript files
├── images/                   # Image assets
│
└── DATABASE FILE/            # SQL dump files
    ├── code_camp_bd_fos.sql
    ├── booking_table.sql
    ├── reviews.sql
    └── dish_discounts.sql
```

---

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Apache or Nginx web server
- Composer (optional)

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/SinghaniaAbhishek/Online_Food_Delivery.git
   cd taste_transport
   ```

2. **Move to Web Root**
   ```bash
   # For Apache (XAMPP/WAMP)
   cp -r taste_transport C:\xampp\htdocs\
   # Or for Linux
   sudo cp -r taste_transport /var/www/html/
   ```

3. **Create Database**
   - Open phpMyAdmin
   - Create a new database named `taste_transport`

4. **Import Database Schema**
   ```bash
   # Navigate to DATABASE FILE folder
   # Import all SQL files in the following order:
   1. code_camp_bd_fos.sql
   2. booking_table.sql
   3. reviews.sql
   4. dish_discounts.sql
   ```

5. **Configure Database Connection**
   - Edit `connection/connect.php`
   - Update credentials:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "taste_transport";
   ```

6. **Start Server**
   - Start Apache and MySQL
   - Navigate to `http://localhost/taste_transport`

---

## 🗄️ Database Setup

### Main Tables

**Users**
```sql
- u_id (Primary Key)
- username
- password (MD5 hashed)
- f_name, l_name
- email
- phone
- address
- date (registration timestamp)
```

**Restaurants**
```sql
- rs_id (Primary Key)
- c_id (Category FK)
- title
- email, phone
- address
- opening_hours, closing_hours
- image
- discount, discount_valid_until
```

**Dishes**
```sql
- d_id (Primary Key)
- rs_id (Restaurant FK)
- title
- price
- image
```

**Orders**
```sql
- o_id (Primary Key)
- u_id (User FK)
- title
- quantity, price
- status
- date
```

**Bookings**
```sql
- booking_id (Primary Key)
- user_id (User FK)
- restaurant_id (Restaurant FK)
- booking_date, booking_time
- number_of_seats
- status
```

**Reviews**
```sql
- review_id (Primary Key)
- user_id (User FK)
- order_id (Order FK)
- rating (1-5)
- comment
- date
```

---

## ⚙️ Configuration

### Admin Credentials (Default)
```
Username: admin
Password: admin (MD5: 0d89ec971a7bcfe26d68c177a9d53334)
Email: admin@tastetransport.com
```

### Important Configuration Files
- **Database**: `connection/connect.php`
- **Session Management**: Implemented in all pages requiring authentication
- **Security**: MD5 password hashing (consider upgrading to bcrypt/Argon2)

---

## 👥 User Roles

### Regular User
- Browse restaurants and dishes
- Place food orders
- Book tables
- Submit reviews
- Manage profile and orders

### Administrator
- Full system access
- Manage restaurants and menus
- Monitor and update orders
- Create promotions and discounts
- Manage user accounts
- View analytics and reports

---

## 📊 Key Features in Detail

### Order Management
- Users can view real-time order status
- Admins can update order progress (pending → confirmed → delivered)
- Order history with timestamps

### Discount System
- **Restaurant Discounts**: Apply discount percentage to entire restaurant
- **Dish Discounts**: Apply discount to specific dishes
- **Validity Period**: Set start and end dates for discounts
- Automatic discount calculation at checkout

### Table Booking System
- Reserve tables for specific dates and times
- Track booking status (pending → confirmed → cancelled)
- Manage seat capacity per restaurant
- View booking history

### Review System
- Rate orders from 1-5 stars
- Submit detailed review comments
- Admin moderation capability
- Display reviews on restaurant pages

---

## 🔌 API Endpoints

### User Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/login.php` | User login |
| POST | `/registration.php` | User registration |
| GET | `/my_profile.php` | Get user profile |
| POST | `/my_profile.php` | Update profile |
| GET | `/your_orders.php` | Get order history |

### Restaurant Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/restaurants.php` | List all restaurants |
| GET | `/dishes.php?rs_id=X` | Get restaurant menu |

### Order Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/product-action.php` | Add to cart |
| POST | `/checkout.php` | Place order |
| POST | `/delete_orders.php` | Cancel order |

### Admin Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/dashboard.php` | Admin dashboard |
| POST | `/admin/add_restaurant.php` | Add restaurant |
| GET | `/admin/all_orders.php` | View all orders |

---

## 🎨 Frontend Preview

### User Interface
![Frontend 1](https://user-images.githubusercontent.com/78216965/220661697-31902983-6bcc-4804-864c-37bca11a9cb1.png)
*Homepage - Featured Restaurants*

![Frontend 2](https://user-images.githubusercontent.com/78216965/220664145-7817eed5-e56a-464a-b3aa-c81e837dd943.png)
*Restaurants Listing Page*

![Frontend 3](https://user-images.githubusercontent.com/78216965/220664347-8cbb77a8-8c64-4b4d-9f86-909286401a9e.png)
*Login Page*

![Frontend 4](https://user-images.githubusercontent.com/78216965/220664419-cc79fae6-e834-426f-9950-93cd3ec77a26.png)
*Registration Page*

![Frontend 5](https://user-images.githubusercontent.com/78216965/220664502-693d905d-928e-4867-ac70-587f424c5d85.png)
*Order History*

---

## 🛠️ Backend Preview

### Admin Dashboard
![Backend 1](https://user-images.githubusercontent.com/78216965/220664885-02d9d951-6249-4fdf-bf14-4c9c6c060725.png)

![Backend 2](https://user-images.githubusercontent.com/78216965/220664929-6fcaee2a-a3ad-44cd-8e23-0fd7dd66a990.png)

![Backend 3](https://user-images.githubusercontent.com/78216965/220664963-41a8fd30-b6b9-4bb6-8849-51a4983d6c35.png)

![Backend 4](https://user-images.githubusercontent.com/78216965/220665094-cf456075-395b-4f1c-97f9-19435e6820bb.png)

![Backend 5](https://user-images.githubusercontent.com/78216965/220665181-3d522f74-7b54-4953-9149-5fe0168fc321.png)

![Backend 6](https://user-images.githubusercontent.com/78216965/220665234-0156a808-4dc7-47b2-b48e-499ea64912d3.png)

![Backend 7](https://user-images.githubusercontent.com/78216965/220665318-818c7bff-ad34-4d6f-95ce-36eb34565047.png)

![Backend 8](https://user-images.githubusercontent.com/78216965/220665496-113b0bc2-009d-41a7-a70c-46e4fefc2682.png)

---

## 🔐 Security Considerations

- ⚠️ **Note**: Current implementation uses MD5 for password hashing
- **Recommendation**: Migrate to `password_hash()` with bcrypt/Argon2
- Implement prepared statements to prevent SQL injection
- Add CSRF token validation for forms
- Implement rate limiting for login attempts
- Use HTTPS in production

---

## 🚦 Future Enhancements

- [ ] Payment Gateway Integration (Stripe, PayPal)
- [ ] Real-time Order Tracking with Google Maps
- [ ] Email Notifications for Orders
- [ ] SMS Notifications via Twilio
- [ ] Mobile App (React Native/Flutter)
- [ ] Advanced Analytics Dashboard
- [ ] Restaurant Analytics Portal
- [ ] Multi-language Support (i18n)
- [ ] Rating System for Restaurants
- [ ] Loyalty Points Program
- [ ] Push Notifications
- [ ] Dark Mode UI
- [ ] API Rate Limiting
- [ ] Two-Factor Authentication

---

## 📝 License

This project is open source and available under the MIT License.

---

## 👨‍💻 Author

**Abhishek Singhania**
- GitHub: [@SinghaniaAbhishek](https://github.com/SinghaniaAbhishek)
- Repository: [Online_Food_Delivery](https://github.com/SinghaniaAbhishek/Online_Food_Delivery)

---

## 📞 Support & Contact

For issues, feature requests, or contributions, please create an issue on the [GitHub repository](https://github.com/SinghaniaAbhishek/Online_Food_Delivery/issues).

---

**Last Updated**: November 2025  
**Version**: 1.0.0


## Frontend Preview

![screencapture-localhost-OnlineFood-PHP-2023-02-22-20_44_35](https://user-images.githubusercontent.com/78216965/220661697-31902983-6bcc-4804-864c-37bca11a9cb1.png)

![screencapture-localhost-OnlineFood-PHP-restaurants-php-2023-02-22-20_44_47](https://user-images.githubusercontent.com/78216965/220664145-7817eed5-e56a-464a-b3aa-c81e837dd943.png)

![screencapture-localhost-OnlineFood-PHP-login-php-2023-02-22-20_44_59](https://user-images.githubusercontent.com/78216965/220664347-8cbb77a8-8c64-4b4d-9f86-909286401a9e.png)

![screencapture-localhost-OnlineFood-PHP-registration-php-2023-02-22-20_45_09](https://user-images.githubusercontent.com/78216965/220664419-cc79fae6-e834-426f-9950-93cd3ec77a26.png)

![screencapture-localhost-OnlineFood-PHP-your-orders-php-2023-02-22-20_46_27](https://user-images.githubusercontent.com/78216965/220664502-693d905d-928e-4867-ac70-587f424c5d85.png)

## Backend Preview

![01](https://user-images.githubusercontent.com/78216965/220664885-02d9d951-6249-4fdf-bf14-4c9c6c060725.png)

![02](https://user-images.githubusercontent.com/78216965/220664929-6fcaee2a-a3ad-44cd-8e23-0fd7dd66a990.png)

![03](https://user-images.githubusercontent.com/78216965/220664963-41a8fd30-b6b9-4bb6-8849-51a4983d6c35.png)

![04](https://user-images.githubusercontent.com/78216965/220665094-cf456075-395b-4f1c-97f9-19435e6820bb.png)

![05](https://user-images.githubusercontent.com/78216965/220665181-3d522f74-7b54-4953-9149-5fe0168fc321.png)

![06](https://user-images.githubusercontent.com/78216965/220665234-0156a808-4dc7-47b2-b48e-499ea64912d3.png)

![07](https://user-images.githubusercontent.com/78216965/220665318-818c7bff-ad34-4d6f-95ce-36eb34565047.png)

![08](https://user-images.githubusercontent.com/78216965/220665496-113b0bc2-009d-41a7-a70c-46e4fefc2682.png)
