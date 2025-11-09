# ChatCommerce - Quick Setup Guide

## 🚀 Getting Started

### 1. Access the Application

The Laravel development server is already running at:
**http://127.0.0.1:8000**

### 2. Register Your Seller Account

1. Open http://127.0.0.1:8000 in your browser
2. Click "Register"
3. Fill in:
   - Name
   - Email
   - Password
4. Click "Register"

### 3. Login to Dashboard

After registration, you'll be redirected to the dashboard where you can see:
- Total Products: 0
- Total Orders: 0
- Today's Sales: ৳0.00
- Facebook Page: Not Connected
- Recent Chats: Empty

### 4. Navigation Menu

The top navigation has these sections:
- **Dashboard** - Overview and statistics
- **Connect Page** - Facebook page connection (to be implemented)
- **Store** - Product management (to be implemented)
- **Inbox** - Customer chat interface (to be implemented)
- **Orders** - Order tracking (to be implemented)

---

## 📊 What Works Now

✅ **User Registration & Login**
- Register new seller account
- Login with email/password
- Password reset functionality
- Profile editing

✅ **Dashboard**
- Beautiful stats cards
- Real-time data
- Responsive design

✅ **Database**
- All tables created
- Models with relationships ready
- Sample data can be added

---

## 🔧 What Needs to Be Built Next

### Priority 1: Product Management
Create the ability to add products so sellers have something to sell.

**Tasks:**
1. Create product form view
2. Implement image upload
3. Add product listing page
4. Add edit/delete functionality

### Priority 2: Facebook Page Connection
Connect to Facebook so we can receive and send messages.

**Tasks:**
1. Facebook OAuth flow
2. Store page access tokens
3. Subscribe to webhooks
4. Test message receiving

### Priority 3: Inbox & Chat
Build the interface for chatting with customers.

**Tasks:**
1. Conversation list view
2. Message thread view
3. Send message functionality
4. POS panel for sending products

### Priority 4: Webhook Handler
Process incoming messages from Facebook.

**Tasks:**
1. Webhook verification
2. Message processing
3. Store conversations and messages
4. Handle postback events

### Priority 5: Bot Automation
Automate order confirmation and collection.

**Tasks:**
1. Order confirmation flow
2. Address collection
3. Payment link generation
4. Status notifications

### Priority 6: Payment Integration
Integrate Piprapay for payment processing.

**Tasks:**
1. Payment link generation
2. Webhook handling
3. Transaction logging
4. Order status updates

### Priority 7: Order Management
Track and update order statuses.

**Tasks:**
1. Order listing page
2. Order detail view
3. Status update interface
4. Auto notifications on status change

---

## 🗄️ Database Schema Quick Reference

### Users (Sellers)
- id, name, email, password, phone, business_name, is_active

### Pages (Connected Facebook Pages)
- id, user_id, page_id, page_access_token, page_name, page_profile_image, is_connected

### Products
- id, user_id, name, description, price, special_price, image, stock_quantity, is_active

### Customers (Messenger Users)
- id, user_id, psid, name, profile_pic, phone, address

### Conversations
- id, user_id, customer_id, page_id, last_message_at, is_read

### Messages
- id, conversation_id, message_id, sender_type (customer/seller/bot), message, attachments

### Orders
- id, order_number, user_id, customer_id, conversation_id, subtotal, total, delivery_address, status, tracking_id

### Order Items
- id, order_id, product_id, product_name, price, quantity, total

### Payment Transactions
- id, order_id, transaction_id, payment_method, amount, status, payment_url, paid_at

---

## 🔐 Facebook App Setup (When Ready)

1. **Create Facebook App:**
   - Go to https://developers.facebook.com
   - Create new app (Business type)
   - Add Messenger product

2. **Configure App:**
   - Get App ID and App Secret
   - Add to `.env` file
   - Set up webhook URL

3. **Webhook Setup:**
   ```
   Webhook URL: https://your-domain.com/webhook
   Verify Token: (create a random string, add to .env)
   Subscribe to: messages, messaging_postbacks
   ```

4. **Page Access Token:**
   - Generate long-lived page access token
   - Store in database via page connection flow

5. **Permissions Needed:**
   - pages_messaging
   - pages_show_list
   - pages_manage_metadata
   - pages_manage_engagement
   - pages_read_engagement

---

## 💳 Piprapay Setup (When Ready)

1. **Register at Piprapay:**
   - Sign up at Piprapay
   - Get API credentials

2. **Add to .env:**
   ```env
   PIPRAPAY_API_KEY=your_api_key
   PIPRAPAY_SECRET_KEY=your_secret_key
   PIPRAPAY_WEBHOOK_SECRET=your_webhook_secret
   PIPRAPAY_SANDBOX=true  # false for production
   ```

3. **Webhook Configuration:**
   ```
   Webhook URL: https://your-domain.com/payment/callback
   ```

---

## 🧪 Testing Without Facebook (Local Development)

While building features, you can:

1. **Create test data manually:**
   ```php
   php artisan tinker

   // Create a test customer
   $customer = \App\Models\Customer::create([
       'user_id' => 1,
       'psid' => 'test_psid_123',
       'name' => 'Test Customer',
   ]);

   // Create a test product
   $product = \App\Models\Product::create([
       'user_id' => 1,
       'name' => 'Sample Product',
       'price' => 500,
       'stock_quantity' => 10,
       'is_active' => true,
   ]);
   ```

2. **Test the Messenger service:**
   ```php
   $messenger = new \App\Services\MessengerService();
   // Test methods when you have page tokens
   ```

---

## 📁 File Structure

```
chatcommerce/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php ✅ (working)
│   │   ├── PageController.php (needs methods)
│   │   ├── ProductController.php (needs methods)
│   │   ├── InboxController.php (needs methods)
│   │   ├── OrderController.php (needs methods)
│   │   └── WebhookController.php (needs methods)
│   ├── Models/ ✅ (all complete)
│   └── Services/
│       └── MessengerService.php ✅ (complete)
├── resources/views/
│   ├── dashboard.blade.php ✅ (complete)
│   └── layouts/
│       ├── app.blade.php ✅ (with navigation)
│       └── navigation.blade.php ✅ (menu added)
└── routes/web.php ✅ (all routes defined)
```

---

## 🐛 Common Issues & Solutions

### Database Connection Error
```bash
# Make sure XAMPP MySQL is running
# Check .env file has correct database credentials
php artisan config:clear
```

### Migrations Failed
```bash
# Reset and run again
php artisan migrate:fresh
```

### Server Not Starting
```bash
# Stop existing server first (Ctrl+C)
# Then restart
cd /Applications/XAMPP/xamppfiles/htdocs/Laravel/chatcommerce
php artisan serve --port=8000
```

### CSS Not Loading
```bash
# Build assets
npm run build
```

---

## 📝 Development Workflow

1. **Start XAMPP** (for MySQL)
2. **Start Laravel server:** `php artisan serve`
3. **Access app:** http://127.0.0.1:8000
4. **Make changes**
5. **Refresh browser** to see updates

---

## 🎯 Recommended Build Order

1. **Products Module** (2-3 hours)
   - Create form, list, edit, delete
   - Image upload
   - Stock management

2. **Page Connection** (1-2 hours)
   - Facebook OAuth
   - Token storage
   - Webhook subscription

3. **Inbox UI** (2-3 hours)
   - Conversation list
   - Message thread
   - Send message
   - POS panel

4. **Webhook Handler** (2-3 hours)
   - Verify webhook
   - Process messages
   - Store data
   - Handle postbacks

5. **Bot Logic** (3-4 hours)
   - Conversation states
   - Order confirmation
   - Address collection
   - Payment link sending

6. **Piprapay Integration** (2-3 hours)
   - Payment API
   - Link generation
   - Webhook handling

7. **Order Management** (2-3 hours)
   - List orders
   - Update status
   - Send notifications

**Total Estimated Time:** 14-19 hours to complete full MVP

---

## 🎉 You're Ready!

The foundation is solid. Now it's time to build the features one by one. Start with the Products module since sellers need products to sell!

**Happy Coding! 🚀**
