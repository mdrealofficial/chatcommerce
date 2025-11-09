# Quick Start Guide - ChatCommerce

## 🚀 5-Minute Setup

### Step 1: Environment Setup (Already Completed ✅)
The project is already set up at:
```
/Applications/XAMPP/xamppfiles/htdocs/Laravel/chatcommerce
```

### Step 2: Start the Server

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Laravel/chatcommerce
php artisan serve
```

Visit: http://127.0.0.1:8000

### Step 3: Register Your Account

1. Click "Register" on the homepage
2. Create your seller account
3. Login to the dashboard

### Step 4: Add Test Products

1. Navigate to "Store" in the menu
2. Click "Add New Product"
3. Upload product images and set prices
4. Add at least 2-3 test products

### Step 5: Connect Facebook Page (For Testing)

**Option A: Skip for Now** (Test without Facebook)
- You can explore the dashboard, products, and orders management
- Inbox will be empty without Facebook connection

**Option B: Full Setup** (For Live Testing)
1. Create a Facebook App at https://developers.facebook.com
2. Add Messenger product
3. Copy App ID and App Secret to `.env`
4. Use ngrok for public URL: `ngrok http 8000`
5. Configure webhook in Facebook
6. Connect your page via "Connect Page" menu

## 📁 Project Structure Overview

```
chatcommerce/
│
├── app/
│   ├── Http/Controllers/       # All business logic
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── PageController.php (Facebook)
│   │   ├── InboxController.php (Chat)
│   │   ├── OrderController.php
│   │   └── WebhookController.php (Facebook webhooks)
│   │
│   ├── Models/                 # Database models
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Customer.php
│   │   ├── Conversation.php
│   │   └── Message.php
│   │
│   ├── Policies/               # Authorization
│   │   ├── ProductPolicy.php
│   │   └── ConversationPolicy.php
│   │
│   └── Services/               # External integrations
│       ├── MessengerService.php (Facebook API)
│       └── PiprapayService.php (Payment)
│
├── database/migrations/        # Database schema
│
├── resources/views/            # Frontend templates
│   ├── dashboard.blade.php
│   ├── products/              # Product management
│   ├── inbox/                 # Chat interface
│   ├── orders/                # Order management
│   └── pages/                 # Facebook connection
│
├── routes/web.php             # All routes
├── .env                       # Configuration
└── README.md                  # Full documentation
```

## 🎯 Key Features at a Glance

### 1. Dashboard (`/dashboard`)
- View total products, orders, and sales
- Check Facebook page connection status
- Quick access to all features

### 2. Product Management (`/products`)
- Create/Edit/Delete products
- Upload product images
- Set regular and special prices
- Track stock quantity
- Filter active/inactive products

### 3. Facebook Integration (`/pages/connect`)
- One-click OAuth connection
- Automatic webhook subscription
- Secure token management

### 4. Inbox/Chat (`/inbox`)
- View all customer conversations
- Send text messages
- Send product cards with "Buy Now" buttons
- See customer profiles and addresses
- POS panel for quick product sharing

### 5. Order Management (`/orders`)
- View all orders with filters
- Track order status
- Update status (automatic customer notifications)
- View order items and customer details
- Payment transaction history

## 🔑 Default Credentials

After registration, you'll have your own credentials.

## 📊 Database

**Database**: `chatcommerce`  
**Tables**: 9 tables (users, pages, products, customers, conversations, messages, orders, order_items, payment_transactions)

All migrations are already run ✅

## 🧪 Testing Without Facebook

You can test the following features without Facebook connection:

1. **✅ Dashboard** - View statistics
2. **✅ Products** - Full CRUD operations
3. **✅ Profile** - Update your account
4. **❌ Inbox** - Requires Facebook (will be empty)
5. **❌ Orders** - Created via Facebook Messenger (will be empty)

## 🛠️ Common Tasks

### Add a New Product
```
Navigate: /products → "Add New Product"
Fill in: Name, Description, Price, Stock, Upload Image
Click: "Create Product"
```

### View All Orders
```
Navigate: /orders
Filter by: Status (pending, confirmed, shipped, etc.)
Search: By customer name or order ID
```

### Update Order Status
```
Navigate: /orders → Click order → Select new status → "Update & Notify Customer"
(Customer receives automatic Messenger notification)
```

### Connect Facebook Page
```
Navigate: /pages/connect → "Connect Facebook Page"
Login with Facebook → Select Page → Authorize
Done! Webhook is automatically subscribed
```

## 🔐 Security Checklist

- ✅ CSRF protection enabled
- ✅ Authorization policies implemented
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ Secure password hashing (bcrypt)
- ✅ Token-based Facebook authentication

## 📝 Environment Variables

Critical `.env` settings:

```env
# App
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Database
DB_DATABASE=chatcommerce
DB_USERNAME=root
DB_PASSWORD=

# Facebook (Optional for now)
FACEBOOK_APP_ID=your_app_id
FACEBOOK_APP_SECRET=your_app_secret
FACEBOOK_WEBHOOK_VERIFY_TOKEN=your_custom_token

# Payment (Optional for now)
PIPRAPAY_API_KEY=your_api_key
PIPRAPAY_SANDBOX=true
```

## 🚨 Troubleshooting

### Server won't start
```bash
# Check if port 8000 is in use
lsof -i :8000

# Use different port
php artisan serve --port=8080
```

### Images not showing
```bash
php artisan storage:link
```

### Database connection error
```bash
# Make sure MySQL is running in XAMPP
# Check .env database credentials
# Verify database exists: chatcommerce
```

### Can't login after registration
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
```

## 📞 Support

Need help? Check:
- Full README.md for detailed documentation
- Laravel docs: https://laravel.com/docs
- Facebook Messenger Platform: https://developers.facebook.com/docs/messenger-platform

## ✅ Project Status

**All features are implemented and ready to use!**

- ✅ Authentication system
- ✅ Dashboard with statistics
- ✅ Product management (full CRUD)
- ✅ Facebook page connection
- ✅ Messenger webhook integration
- ✅ Inbox/Chat interface
- ✅ Order management
- ✅ Payment integration (Piprapay)
- ✅ Customer notifications
- ✅ Responsive design

---

**Current Version**: 1.0.0  
**Status**: Production Ready 🎉
