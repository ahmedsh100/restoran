# Restoran - Restaurant Management System

A full-featured restaurant management web application built with **Laravel 10**, offering a seamless experience for customers to browse menus, place orders, and manage their dining experience — alongside a powerful admin panel for restaurant staff.

---

## Tech Stack

| Layer        | Technology          |
|--------------|---------------------|
| **Backend**  | PHP 8.1+, Laravel 10 |
| **Database** | MySQL               |
| **Frontend** | Bootstrap, Blade Templates, Vite |
| **Payments** | Stripe API          |
| **Auth**     | Laravel UI (Bootstrap scaffolding) |
| **Testing**  | PHPUnit, Faker      |

---

## Key Features

### Customer Features
- **User Authentication** — Registration, login, password reset, and email verification
- **Food Menu Browsing** — Browse dishes by category with images, descriptions, and pricing
- **Food Details Page** — View detailed information, reviews, and ratings for each dish
- **Shopping Cart** — Add, update, and remove items with real-time subtotal calculation
- **Checkout & Orders** — Place orders with address and phone details
- **Stripe Payment Integration** — Secure online payments with Stripe Checkout
- **Order History** — Track past orders and their statuses
- **Reviews & Ratings** — Leave feedback and star ratings on purchased dishes
- **Coupon System** — Apply discount codes (fixed or percentage-based) at checkout
- **Contact Form** — Submit inquiries through the contact page

### Admin Features
- **Admin Dashboard** — Overview of orders, foods, reviews, and coupons
- **Food Management** — Create, edit, delete, and toggle availability of menu items
- **Order Management** — View all orders and update their status (pending, processing, completed, cancelled)
- **Review Moderation** — Approve, reject, or delete customer reviews
- **Coupon Management** — Create and manage promotional discount codes with usage limits and expiry dates

---

## Database Schema

### Core Tables

| Table         | Description                              |
|---------------|------------------------------------------|
| `users`       | User accounts (customers & admins) — `id, name, email, password, is_admin, email_verified_at, timestamps` |
| `foods`       | Menu items — `id, name, price, category, description, image, is_available, timestamps` |
| `cart_items`  | User shopping cart entries — `id, user_id (FK), food_id (FK), quantity, price, timestamps` |
| `orders`      | Customer orders — `id, user_id (FK), total_price, coupon_id (FK), discount_amount, payment_method, payment_status, stripe_session_id, stripe_payment_intent, status, address, phone, timestamps` |
| `order_items` | Individual items within an order — `id, order_id (FK), food_id (FK), quantity, price, timestamps` |
| `reviews`     | Customer reviews — `id, user_id (FK), food_id (FK), rating, comment, is_approved, timestamps` |
| `coupons`     | Discount codes — `id, code (unique), type (fixed/percentage), value, expiry_date, usage_limit, used_count, is_active, timestamps` |

### Relationships
- **User** has many **CartItems**, **Orders**, and **Reviews**
- **Food** has many **CartItems**, **Reviews**, and **OrderItems**
- **Order** belongs to **User**, has many **OrderItems**, and optionally belongs to **Coupon**
- **Review** belongs to **User** and **Food** (unique constraint per user-food pair)

---

## Installation Guide

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (for asset compilation)

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd restoran
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies & build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Set up environment variables**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restoran
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations & seed the database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Configure Stripe** (for payment processing) in `.env`:
   ```env
   STRIPE_KEY=your_stripe_public_key
   STRIPE_SECRET=your_stripe_secret_key
   STRIPE_WEBHOOK_SECRET=your_webhook_secret
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   - Frontend: `http://localhost:8000`
   - Admin Panel: `http://localhost:8000/admin/dashboard` (requires `is_admin = true`)

---

## Project Structure

```
restoran/
├── app/
│   ├── Helpers/              # Global helper functions
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   │   ├── AdminController.php
│   │   │   ├── CartController.php
│   │   │   ├── FoodController.php
│   │   │   ├── HomeController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PageController.php
│   │   │   ├── ReviewController.php
│   │   │   └── StripeWebhookController.php
│   │   ├── Middleware/       # Custom middleware (Admin, Auth, etc.)
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers
│   ├── Repositories/         # Data access layer (Repository pattern)
│   ├── Services/             # Business logic services
│   └── Traits/               # Reusable traits
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── factories/            # Model factories for testing
├── resources/
│   └── views/                # Blade templates
│       ├── admin/            # Admin panel views
│       ├── auth/             # Authentication views
│       └── layouts/          # Shared layout files
├── routes/
│   └── web.php               # Web route definitions
└── public/                   # Publicly accessible files
```

---

## Current Status

| Feature              | Status        |
|----------------------|---------------|
| Authentication       | Completed     |
| Food Menu & Catalog  | Completed     |
| Cart System          | Completed     |
| Checkout & Payments  | Completed     |
| Order Management     | Completed     |
| Reviews & Ratings    | Completed     |
| Coupon System        | Completed     |
| Admin Dashboard      | Completed     |
| Food Details Page    | Completed     |
| Reservations System  | Under Development |

### Upcoming Priorities

1. **Reservations System** (Priority #3) — Allow customers to book tables with date, time, party size, and special requests. Includes admin management for viewing and confirming reservations.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
