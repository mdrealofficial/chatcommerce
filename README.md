# ChatCommerce - Conversational Commerce Platform<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



A complete conversational commerce platform that enables sellers to sell products directly through Facebook Messenger without needing a website.<p align="center">

<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

## Features<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>

<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>

### ✅ Completed Features<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>

</p>

1. **Authentication System**

   - User registration and login (Laravel Breeze)## About Laravel

   - Profile management

   - Password resetLaravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:



2. **Dashboard**- [Simple, fast routing engine](https://laravel.com/docs/routing).

   - Total products overview- [Powerful dependency injection container](https://laravel.com/docs/container).

   - Total orders tracking- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.

   - Total sales statistics- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).

   - Facebook page connection status- Database agnostic [schema migrations](https://laravel.com/docs/migrations).

- [Robust background job processing](https://laravel.com/docs/queues).

3. **Facebook Page Integration**- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

   - OAuth2 authentication flow

   - Page connection and disconnectionLaravel is accessible, powerful, and provides tools required for large, robust applications.

   - Automatic webhook subscription

   - Access token management## Learning Laravel



4. **Product Management**Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

   - Full CRUD operations (Create, Read, Update, Delete)

   - Image upload and managementIf you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

   - Stock quantity tracking

   - Regular and special pricing## Laravel Sponsors

   - Active/inactive status

   - Search and filter functionalityWe would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

   - Low stock warnings

### Premium Partners

5. **Messenger Integration**

   - Facebook webhook verification- **[Vehikl](https://vehikl.com)**

   - Incoming message handling- **[Tighten Co.](https://tighten.co)**

   - Outgoing message sending- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**

   - Product card messages with images- **[64 Robots](https://64robots.com)**

   - Quick reply buttons- **[Curotec](https://www.curotec.com/services/technologies/laravel)**

   - Button templates- **[DevSquad](https://devsquad.com/hire-laravel-developers)**

   - User profile fetching- **[Redberry](https://redberry.international/laravel-development)**

- **[Active Logic](https://activelogic.com)**

6. **Inbox/Chat Interface**

   - Conversation list with customer avatars## Contributing

   - Unread message indicators

   - Last message previewThank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

   - Message thread display

   - Real-time message sending## Code of Conduct

   - POS panel for sending products

   - Customer information sidebarIn order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).



7. **Order Management**## Security Vulnerabilities

   - Automated order creation from conversations

   - Order listing with filtersIf you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

   - Order detail view

   - Status management (pending, confirmed, processing, shipped, delivered, cancelled)## License

   - Automatic customer notifications via Messenger

   - Order items with product detailsThe Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

   - Delivery information tracking

8. **Payment Integration (Piprapay)**
   - Payment link generation
   - Webhook handling
   - Payment verification
   - Transaction tracking
   - Success/failure pages
   - Automatic order confirmation on successful payment

9. **Customer Management**
   - Automatic customer profile creation from Facebook
   - Profile picture syncing
   - Contact information storage
   - Address collection
   - Conversation history

## Technology Stack

- **Framework**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Database**: MySQL
- **External APIs**:
  - Facebook Graph API v21.0
  - Facebook Messenger Platform
  - Piprapay Payment Gateway
- **Dependencies**:
  - facebook/graph-sdk (5.1.4)

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & NPM
- XAMPP/WAMP/LAMP or similar local server

### Setup Steps

1. **Clone the repository**
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/Laravel
   # Project is already in the chatcommerce directory
   ```

2. **Install PHP dependencies**
   ```bash
   cd chatcommerce
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database in .env**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=chatcommerce
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

## Facebook App Configuration

### 1. Create Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app (Business type)
3. Add "Messenger" product
4. Note your App ID and App Secret

### 2. Configure .env

```env
FACEBOOK_APP_ID=your_app_id_here
FACEBOOK_APP_SECRET=your_app_secret_here
FACEBOOK_WEBHOOK_VERIFY_TOKEN=your_custom_verify_token
FACEBOOK_GRAPH_VERSION=v21.0
```

### 3. Setup Webhook

1. In Facebook App Dashboard, go to Messenger > Settings
2. Set Callback URL: `https://yourdomain.com/webhook`
3. Set Verify Token: Same as `FACEBOOK_WEBHOOK_VERIFY_TOKEN` in .env
4. Subscribe to events:
   - messages
   - messaging_postbacks
   - messaging_optins

### 4. Required Permissions

- pages_show_list
- pages_messaging
- pages_read_engagement
- pages_manage_metadata

### 5. For Development

Use ngrok to create a public URL:
```bash
ngrok http 8000
```
Then update Facebook webhook URL with the ngrok URL.

## Piprapay Configuration

### 1. Get API Credentials

1. Sign up at [Piprapay](https://piprapay.com)
2. Get your API Key and Secret Key from dashboard
3. Note your webhook secret

### 2. Configure .env

```env
PIPRAPAY_API_KEY=your_api_key_here
PIPRAPAY_SECRET_KEY=your_secret_key_here
PIPRAPAY_WEBHOOK_SECRET=your_webhook_secret_here
PIPRAPAY_SANDBOX=true  # Set to false in production
```

### 3. Setup Webhook

In Piprapay dashboard, set webhook URL to:
```
https://yourdomain.com/payment/callback
```

## Database Schema

### Tables

1. **users** - Seller accounts
2. **pages** - Connected Facebook pages
3. **products** - Product catalog
4. **customers** - Facebook Messenger customers
5. **conversations** - Chat conversations
6. **messages** - Individual messages
7. **orders** - Customer orders
8. **order_items** - Order line items
9. **payment_transactions** - Payment records

## Usage Workflow

### For Sellers

1. **Register** an account on the platform
2. **Connect** your Facebook Page via OAuth
3. **Add Products** to your catalog with images and prices
4. **Manage Conversations** in the Inbox
5. **Send Products** to customers via POS panel
6. **Track Orders** and update statuses
7. **Receive Payments** through Piprapay

### For Customers

1. **Message** the connected Facebook Page
2. **Browse** products sent by the seller
3. **Click** "Confirm Order" button on desired product
4. **Provide** delivery address when asked
5. **Click** payment link to complete payment
6. **Receive** order status updates via Messenger

## API Endpoints

### Webhook Endpoints

```
GET  /webhook                    - Webhook verification
POST /webhook                    - Webhook event handler
GET  /payment/callback          - Payment redirect
POST /payment/callback          - Payment webhook
```

### Authenticated Routes

```
GET    /dashboard               - Dashboard overview
GET    /pages/connect          - Connect Facebook page
GET    /pages/callback         - OAuth callback
POST   /pages/disconnect       - Disconnect page

GET    /products               - List products
POST   /products               - Create product
GET    /products/{id}/edit     - Edit product form
PUT    /products/{id}          - Update product
DELETE /products/{id}          - Delete product

GET    /inbox                  - List conversations
GET    /inbox/{id}             - View conversation
POST   /inbox/{id}/send        - Send message
POST   /inbox/{id}/send-product - Send product card

GET    /orders                 - List orders
GET    /orders/{id}            - View order details
PATCH  /orders/{id}/status     - Update order status
```

## File Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── PageController.php
│   ├── ProductController.php
│   ├── InboxController.php
│   ├── OrderController.php
│   └── WebhookController.php
├── Models/
│   ├── User.php
│   ├── Page.php
│   ├── Product.php
│   ├── Customer.php
│   ├── Conversation.php
│   ├── Message.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── PaymentTransaction.php
├── Policies/
│   ├── ProductPolicy.php
│   └── ConversationPolicy.php
└── Services/
    ├── MessengerService.php
    └── PiprapayService.php

resources/views/
├── dashboard.blade.php
├── pages/connect.blade.php
├── products/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── inbox/
│   ├── index.blade.php
│   └── show.blade.php
├── orders/
│   ├── index.blade.php
│   └── show.blade.php
└── payment/
    ├── success.blade.php
    └── failed.blade.php
```

## Security Features

- CSRF Protection on all forms
- Authorization policies for products and conversations
- Webhook signature verification
- Secure token storage
- XSS protection via Blade templates
- SQL injection protection via Eloquent ORM

## Testing

### Test the Messenger Flow

1. Connect a Facebook Page
2. Add test products
3. Message the page from a test Facebook account
4. Seller should see the message in Inbox
5. Send a product card to the customer
6. Customer confirms order
7. Customer provides address
8. Payment link is sent
9. Update order status and verify customer receives notification

## Troubleshooting

### Webhook not receiving messages

- Check if webhook URL is accessible publicly
- Verify webhook token matches in Facebook and .env
- Ensure webhook subscriptions are active
- Check Laravel logs: `storage/logs/laravel.log`

### Facebook OAuth fails

- Verify App ID and Secret are correct
- Check redirect URI matches exactly
- Ensure app is not in development mode (or test user is added)
- Clear browser cache and cookies

### Payment not working

- Verify Piprapay credentials
- Check if sandbox mode is appropriate
- Review payment logs
- Ensure webhook URL is configured in Piprapay

### Images not showing

- Run `php artisan storage:link`
- Check file permissions on storage directory
- Verify image path in database

## Production Deployment Checklist

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Configure production database
- [ ] Set up proper domain with SSL certificate
- [ ] Update Facebook app settings with production URL
- [ ] Update Piprapay webhook URL
- [ ] Set `PIPRAPAY_SANDBOX=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up automated backups
- [ ] Configure queue worker for background jobs
- [ ] Set up monitoring and logging

## Support

For issues or questions:
- Check Laravel documentation: https://laravel.com/docs
- Facebook Messenger Platform docs: https://developers.facebook.com/docs/messenger-platform
- Piprapay API docs: https://docs.piprapay.com

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

Built with Laravel 12.x and Facebook Messenger Platform API.

---

**Version**: 1.0.0  
**Last Updated**: 2024  
**Status**: Production Ready ✅
