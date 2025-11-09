# ChatCommerce - Conversational Commerce Platform

**"Sell directly from Messenger — no website needed."**

A Laravel-based conversational commerce platform that allows sellers to manage products, chat with customers in Facebook Messenger, and process orders entirely through chat automation.

---

## 🎯 Project Overview

ChatCommerce enables sellers to:
- Connect their Facebook Page
- Manage products with pricing and inventory
- Chat with customers through an integrated inbox
- Send product cards directly in Messenger
- Automate order confirmation and payment collection
- Track order status with automatic customer notifications

---

## ✅ What Has Been Completed

### 1. **Laravel Installation & Setup**
- ✅ Fresh Laravel 12.x installation
- ✅ MySQL database configured (`chatcommerce`)
- ✅ Environment variables set up for Facebook and Piprapay

### 2. **Database Architecture**
All migrations created and running successfully:

| Table | Purpose |
|-------|---------|
| `users` | Seller accounts with business info |
| `pages` | Connected Facebook pages (Page ID, tokens, profile) |
| `products` | Product catalog with pricing, images, stock |
| `customers` | Messenger users (PSID, name, profile pic) |
| `conversations` | Chat sessions between seller and customer |
| `messages` | Individual messages in conversations |
| `orders` | Order master (status: pending → delivered) |
| `order_items` | Products within each order |
| `payment_transactions` | Payment tracking and logs |

### 3. **Eloquent Models**
All models created with:
- ✅ Proper fillable fields
- ✅ Type casting (decimals, booleans, JSON)
- ✅ Relationships (hasMany, belongsTo)
- ✅ Auto-generate order numbers

### 4. **Authentication System**
- ✅ Laravel Breeze installed
- ✅ Login, Register, Password Reset
- ✅ User profile management

### 5. **Dashboard**
Beautiful, functional dashboard showing:
- ✅ Total Products count
- ✅ Total Orders (Pending/Confirmed/Delivered)
- ✅ Today's Sales (in BDT ৳)
- ✅ Facebook Page connection status
- ✅ Last 5 recent chats with customer info

### 6. **Navigation Menu**
- ✅ Dashboard
- ✅ Connect Page
- ✅ Store (Products)
- ✅ Inbox
- ✅ Orders

### 7. **Controllers Created**
- ✅ `DashboardController` - Homepage stats
- ✅ `PageController` - Facebook connection
- ✅ `ProductController` - Product CRUD
- ✅ `InboxController` - Chat interface
- ✅ `OrderController` - Order management
- ✅ `WebhookController` - Facebook webhook handler

### 8. **Routing**
All routes configured:
- ✅ Authentication routes
- ✅ Dashboard
- ✅ Page connection & callback
- ✅ Product resource routes
- ✅ Inbox with conversation view
- ✅ Order management
- ✅ Webhook verification and handling
- ✅ Payment callback

### 9. **Facebook Messenger Service**
Complete service class (`app/Services/MessengerService.php`) with:
- ✅ `sendMessage()` - Send text messages
- ✅ `sendProductCard()` - Send product with image and buttons
- ✅ `sendQuickReply()` - Send quick reply options
- ✅ `sendButtonMessage()` - Send buttons (like payment links)
- ✅ `getUserProfile()` - Fetch customer details
- ✅ `subscribeToPage()` - Subscribe webhooks
- ✅ Error handling and logging

### 10. **Configuration**
- ✅ Facebook API settings in `config/services.php`
- ✅ Piprapay payment gateway config
- ✅ Environment variables documented

---

## 🚀 Application is Running!

**Server URL:** http://127.0.0.1:8000

You can now:
1. Register a new seller account
2. Login to the dashboard
3. See the beautiful UI with stats cards

---

## 📋 What's Next (To Be Implemented)

### 7. Facebook Page Connection
**Files to create:**
- `PageController@connect` - Redirect to Facebook OAuth
- `PageController@callback` - Handle OAuth callback, store tokens
- `PageController@disconnect` - Remove page connection
- View: `resources/views/pages/connect.blade.php`

**What it does:**
- Login with Facebook button
- Request permissions: `pages_messaging`, `pages_show_list`, etc.
- Store Page ID, Access Token, Page Name, Profile Image
- Subscribe to webhooks (messages, postbacks)

---

### 8. Store Management (Products CRUD)
**Files to complete:**
- `ProductController@index` - List all products
- `ProductController@create` - Show create form
- `ProductController@store` - Save new product
- `ProductController@edit` - Edit form
- `ProductController@update` - Update product
- `ProductController@destroy` - Delete product
- Views in `resources/views/products/`

**Features:**
- Upload product images (use Laravel Storage)
- Set price and special price
- Manage stock quantity
- Mark active/inactive

---

### 9. Messenger Inbox Interface
**Files to create:**
- `InboxController@index` - List all conversations
- `InboxController@show` - Display single conversation with messages
- `InboxController@send` - Send message to customer
- `InboxController@sendProduct` - Send product card
- Views: `resources/views/inbox/index.blade.php`, `show.blade.php`

**Features:**
- Real-time chat interface
- Display customer profile pic and name
- Show message history
- Reply with text or emojis
- POS panel (sidebar with product grid)
- "Send to Chat" button for each product

---

### 10. POS Panel
**Implementation:**
- Add to inbox sidebar
- Display products as grid with image, price, stock
- "Send to Chat" button
- On click → send product card with ✅ Confirm / ❌ Cancel buttons

---

### 11. Facebook Webhook Handler
**Files to complete:**
- `WebhookController@verify` - Verify webhook subscription
- `WebhookController@handle` - Process incoming messages and postbacks

**What it handles:**
1. **Incoming Message:**
   - Store customer if new (PSID, name, profile pic)
   - Create/update conversation
   - Save message to database
   
2. **Postback (Button Click):**
   - "Confirm Order" → Ask for delivery address
   - Address received → Create order → Send payment link
   - "Cancel" → Send "Order cancelled" message

---

### 12. Bot Conversation Flow
**Logic to implement:**

```
User clicks "Confirm Order" 
  ↓
Bot: "Please provide your delivery address."
  ↓
User sends address
  ↓
System: Create Order (status: pending)
        Store address in order
  ↓
Bot: Send payment link button
  ↓
User pays
  ↓
Payment webhook → Update transaction → Order status: confirmed
  ↓
Bot: "✅ Payment Received! Your order is confirmed. Thank you!"
```

**Files to create:**
- `app/Services/BotService.php` - Handle conversation states
- Store conversation state (waiting_for_address, waiting_for_payment, etc.)

---

### 13. Piprapay Payment Integration
**Files to create:**
- `app/Services/PiprapayService.php`
- Payment initiation
- Generate payment URL
- Handle payment callback/webhook

**Flow:**
1. Create order → Generate payment transaction
2. Call Piprapay API to create payment link
3. Send link to customer via Messenger button
4. Customer pays → Piprapay webhook → Update status
5. Send confirmation message

**Piprapay API Documentation:** https://piprapay.readme.io/reference/overview

---

### 14. Order Management System
**Files to complete:**
- `OrderController@index` - List all orders
- `OrderController@show` - View order details
- `OrderController@updateStatus` - Change order status
- Views in `resources/views/orders/`

**Features:**
- Order status flow: `pending → confirmed → packed → shipped → delivered → completed`
- Add tracking ID
- On status update → Send Messenger notification

**Example notification:**
```
Your order has been shipped 🚚
Tracking ID: 908234234
```

---

### 15. Automated Messenger Notifications
**Use Cases:**
- Order confirmed
- Order shipped (with tracking)
- Order delivered
- Payment received

**Implementation:**
- Use Messenger Send API with "post_purchase_update" tag
- Call from `Order` model events (status updated)

---

## 🗂️ Project Structure

```
chatcommerce/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php ✅
│   │   ├── PageController.php (needs implementation)
│   │   ├── ProductController.php (needs implementation)
│   │   ├── InboxController.php (needs implementation)
│   │   ├── OrderController.php (needs implementation)
│   │   └── WebhookController.php (needs implementation)
│   ├── Models/
│   │   ├── User.php ✅
│   │   ├── Page.php ✅
│   │   ├── Product.php ✅
│   │   ├── Customer.php ✅
│   │   ├── Conversation.php ✅
│   │   ├── Message.php ✅
│   │   ├── Order.php ✅
│   │   ├── OrderItem.php ✅
│   │   └── PaymentTransaction.php ✅
│   └── Services/
│       ├── MessengerService.php ✅
│       ├── BotService.php (needs creation)
│       └── PiprapayService.php (needs creation)
├── database/migrations/ ✅ All done
├── resources/views/
│   ├── dashboard.blade.php ✅
│   ├── pages/ (needs creation)
│   ├── products/ (needs creation)
│   ├── inbox/ (needs creation)
│   └── orders/ (needs creation)
└── routes/web.php ✅
```

---

## 🔑 Environment Variables

Already configured in `.env`:

```env
# App
APP_NAME=ChatCommerce
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_DATABASE=chatcommerce
DB_USERNAME=root
DB_PASSWORD=

# Facebook Configuration
FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_WEBHOOK_VERIFY_TOKEN=
FACEBOOK_GRAPH_VERSION=v21.0

# Piprapay Configuration
PIPRAPAY_API_KEY=
PIPRAPAY_SECRET_KEY=
PIPRAPAY_WEBHOOK_SECRET=
PIPRAPAY_SANDBOX=true
```

**You need to fill in:**
1. Facebook App ID and Secret (from https://developers.facebook.com)
2. Webhook verify token (any random string)
3. Piprapay API credentials (from Piprapay dashboard)

---

## 📖 How to Set Up Facebook App

1. Go to https://developers.facebook.com
2. Create a new app → Business Type
3. Add Messenger product
4. Get App ID and App Secret → Add to `.env`
5. Set up webhook:
   - URL: `https://your-domain.com/webhook`
   - Verify Token: (same as in `.env`)
   - Subscribe to: `messages`, `messaging_postbacks`
6. Generate Page Access Token
7. Subscribe app to your Facebook Page

---

## 🎨 Technology Stack

- **Backend:** Laravel 12.x
- **Frontend:** Blade Templates with Tailwind CSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Messenger API:** Facebook Graph API v21.0
- **Payment Gateway:** Piprapay
- **Storage:** Local Storage (for product images)

---

## 🛠️ Development Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Create new controller
php artisan make:controller ControllerName

# Create new model with migration
php artisan make:model ModelName -m

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📱 Core Flow Summary

```
┌─────────────────────────────────────────────────────────────┐
│  Seller Login → Connect FB Page → Add Products             │
│         ↓                              ↓                    │
│  Receive Inbox Messages ← Customer Messages on Messenger   │
│         ↓                                                   │
│  POS → Select Customer → Send Product Card                 │
│         ↓                                                   │
│  Customer Confirms → Bot Asks Address → Store Address      │
│         ↓                                                   │
│  Send Payment Link → Customer Pays → Payment Success       │
│         ↓                                                   │
│  Order Created → Status Updates → Auto Notifications       │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Next Steps to Complete the Project

1. **Implement PageController** - Facebook OAuth flow
2. **Build Product Management** - CRUD with image upload
3. **Create Inbox Interface** - Chat UI with POS panel
4. **Webhook Handler** - Process incoming messages
5. **Bot Logic** - Conversation state management
6. **Piprapay Integration** - Payment processing
7. **Order Management** - Status tracking and notifications
8. **Testing** - Test full flow from product send to payment

---

## 📝 Notes

- Product images will be stored in `storage/app/public/products/`
- Run `php artisan storage:link` to create symbolic link
- Use queue for sending messages in production (`php artisan queue:work`)
- Implement rate limiting for webhook endpoints
- Add validation and error handling for all forms
- Consider using Laravel Echo + Pusher for real-time chat updates

---

## 🎉 Current Status

**Foundation Complete! ✅**

The core architecture, database, models, authentication, and dashboard are fully functional. The Messenger service is ready to use. Now we need to build the remaining features one by one.

You can start by registering a seller account and exploring the dashboard!

---

**Built with ❤️ using Laravel**
