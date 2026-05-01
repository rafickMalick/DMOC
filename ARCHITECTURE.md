# 🏗️ ARCHITECTURE TECHNIQUE DMOC

## Vue d'ensemble System Design

```
┌──────────────────────────────────────────────────────────────────┐
│                        DMOC ARCHITECTURE                         │
└──────────────────────────────────────────────────────────────────┘

                           🌍 CLIENT
                              │
                              │ HTTPS
                              ▼
                    ┌─────────────────┐
                    │   Cloudflare    │
                    │  (CDN + WAF)    │
                    └────────┬────────┘
                             │
                             │ Port 80/443
                             ▼
        ┌────────────────────────────────────────┐
        │         LOAD BALANCER (Nginx)          │
        │  • SSL Termination (TLS 1.3)           │
        │  • Request Routing                     │
        │  • Gzip Compression                    │
        └──────┬──────────────────────┬──────────┘
               │                      │
        ┌──────▼─────┐         ┌──────▼─────┐
        │ APP 1      │  ....   │ APP N      │
        │ (Laravel)  │         │ (Laravel)  │
        │ Port 9000  │         │ Port 9000  │
        └──────┬─────┘         └──────┬─────┘
               │                      │
        ┌──────┴──────────────────────┴──────┐
        │                                    │
        │    LARAVEL 11 APPLICATION LAYER   │
        │                                    │
        │  • Controllers (API + Web)         │
        │  • Services (Business Logic)       │
        │  • Middlewares (Auth, CORS, etc)   │
        │  • Jobs (Queue Processing)         │
        │  • Listeners (Events)              │
        │                                    │
        │  Frontend: Blade + Livewire        │
        │  API: JSON REST (Sanctum)          │
        │                                    │
        └──────┬──────────────────────┬──────┘
               │                      │
        ┌──────▼────────┐     ┌────────▼──────┐
        │    MYSQL 8    │     │     REDIS     │
        │  (Database)   │     │  (Cache+Q)    │
        │  Port 3306    │     │  Port 6379    │
        └───────────────┘     └───────────────┘
               │                      │
        ┌──────▼──────────────────────▼──────┐
        │        DATA STORAGE LAYER          │
        │                                    │
        │  • User Accounts                   │
        │  • Products & Categories           │
        │  • Orders & Payments               │
        │  • Deliveries & Tracking           │
        │  • Sessions & Tokens               │
        │  • Cache (Taux change, produits)  │
        │  • Job Queue (SMS, emails)         │
        │                                    │
        └────────────────────────────────────┘


INTEGRATIONS EXTERNES
═════════════════════════════════════════════════════════════════════

    ┌─────────────────┐
    │  CinetPay API   │ ◄──────┐
    │ (Mobile Money)  │        │
    └─────────────────┘        │
                               │ Paiements
    ┌─────────────────┐        │ Webhooks
    │   Stripe API    │ ◄──────┤
    │   (Cards)       │        │
    └─────────────────┘        │
                               │
    ┌─────────────────┐        │
    │   PayPal API    │ ◄──────┘
    │  (Global)       │
    └─────────────────┘

    ┌─────────────────┐
    │ Afrika's Talking│ ◄────── SMS
    │   (Twilio alt)  │        Notifications
    └─────────────────┘

    ┌─────────────────┐
    │ Telegram Bot    │ ◄────── Customer
    │    (Webhooks)   │        Support
    └─────────────────┘

    ┌─────────────────┐
    │  Google Maps    │ ◄────── Tracking
    │     API         │        & Routing
    └─────────────────┘

    ┌─────────────────┐
    │   Fixer.io      │ ◄────── Exchange
    │  (Currencies)   │        Rates
    └─────────────────┘

    ┌─────────────────┐
    │  Sentry.io      │ ◄────── Error
    │  (Monitoring)   │        Logging
    └─────────────────┘
```

---

## Architecture Couches (Layered Architecture)

```
┌─────────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER (UI)                      │
│  • Blade Templates (Server-side rendering)                      │
│  • Livewire Components (Reactive UI)                            │
│  • Alpine.js (Lightweight JS interactions)                      │
│  • Tailwind CSS (Styling)                                       │
│  • Mobile-First, PWA-ready                                      │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│                    APPLICATION LAYER                            │
│                                                                  │
│  Controllers                                                     │
│  ├─ AuthController (Login/Register/2FA)                         │
│  ├─ ProductController (CRUD products)                           │
│  ├─ OrderController (Checkout/Orders management)                │
│  ├─ PaymentController (Webhook handlers)                        │
│  ├─ DeliveryController (Livreur app API)                        │
│  ├─ AdminController (Dashboard + CRUD)                          │
│  └─ ApiController (REST endpoints)                              │
│                                                                  │
│  Middleware                                                      │
│  ├─ Auth (Sanctum JWT)                                          │
│  ├─ CheckRole (Admin/Vendor/Courier)                            │
│  ├─ RateLimiter (API throttling)                                │
│  ├─ VerifyCsrfToken                                             │
│  └─ Cors                                                         │
│                                                                  │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│                    SERVICE LAYER (Business Logic)               │
│                                                                  │
│  Services                                                        │
│  ├─ ProductService (CRUD + filtering)                           │
│  ├─ OrderService (Create + manage orders)                       │
│  ├─ PaymentService (Process payments)                           │
│  │  ├─ CinetPayService                                          │
│  │  ├─ StripeService                                            │
│  │  └─ PayPalService                                            │
│  ├─ DeliveryService (Manage deliveries)                         │
│  ├─ SmsService (Send SMS via Afrika's Talking)                  │
│  ├─ TelegramService (Bot commands + notifications)              │
│  ├─ LocalizationService (i18n + currency)                       │
│  ├─ CartService (Cart operations)                               │
│  └─ ExportService (Excel generation)                            │
│                                                                  │
│  Events / Listeners                                              │
│  ├─ OrderCreated ──► SendConfirmationSMS                        │
│  ├─ PaymentProcessed ──► CreateDelivery                         │
│  ├─ DeliveryStarted ──► SendStartedSMS                          │
│  ├─ DeliveryCompleted ──► SendCompletedSMS                      │
│  └─ StockAlertThreshold ──► NotifyAdmin                         │
│                                                                  │
│  Jobs (Queued)                                                   │
│  ├─ ProcessPaymentJob                                           │
│  ├─ SendSmsJob (Async SMS)                                      │
│  ├─ GenerateInvoiceJob                                          │
│  ├─ UpdateExchangeRatesJob (6h cron)                            │
│  └─ DailyReportJob (Admin digest)                               │
│                                                                  │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│                  REPOSITORY / MODEL LAYER                       │
│                                                                  │
│  Models (Eloquent ORM)                                           │
│  ├─ User                                                         │
│  │  ├─ HasMany: orders, cart                                    │
│  │  ├─ HasRole: admin/vendor/courier/client                     │
│  │  └─ HasPermissions                                           │
│  ├─ Product                                                      │
│  │  ├─ BelongsTo: category                                      │
│  │  ├─ HasMany: variants, images                                │
│  │  └─ HasMany: reviews                                         │
│  ├─ Order                                                        │
│  │  ├─ BelongsTo: user                                          │
│  │  ├─ HasMany: items, payments, deliveries                     │
│  │  └─ HasOne: invoice                                          │
│  ├─ Payment                                                      │
│  │  ├─ BelongsTo: order                                         │
│  │  └─ Polymorphic: payable (CinetPay, Stripe, etc)            │
│  ├─ Delivery                                                     │
│  │  ├─ BelongsTo: order, courier, zone                          │
│  │  └─ HasMany: statuses                                        │
│  ├─ Cart                                                         │
│  │  ├─ BelongsTo: user                                          │
│  │  └─ HasMany: items                                           │
│  └─ Zone                                                         │
│     ├─ HasMany: deliveries, couriers                            │
│     └─ HasMany: tariff_rules                                    │
│                                                                  │
│  Eloquent Scopes                                                 │
│  ├─ Active / Inactive                                           │
│  ├─ ByZone / ByCountry                                          │
│  ├─ ByStatus / ByPeriod                                         │
│  └─ SearchQuery                                                 │
│                                                                  │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│                    DATA LAYER (Database)                        │
│                                                                  │
│  Primary Store (MySQL 8)                                        │
│  ├─ users (auth, profile)                                       │
│  ├─ products (catalog, stock)                                   │
│  ├─ categories (hierarchy)                                      │
│  ├─ orders (transactions)                                       │
│  ├─ order_items (line items)                                    │
│  ├─ payments (transaction log)                                  │
│  ├─ carts (shopping carts)                                      │
│  ├─ deliveries (shipping tracking)                              │
│  ├─ zones (geographic areas)                                    │
│  ├─ couriers (delivery staff)                                   │
│  ├─ reviews (product ratings)                                   │
│  ├─ locales (languages/currencies)                              │
│  └─ logs (audit trail)                                          │
│                                                                  │
│  Cache Store (Redis)                                            │
│  ├─ cache:products (expire 24h)                                 │
│  ├─ cache:categories (expire 24h)                               │
│  ├─ cache:exchange_rates (expire 6h)                            │
│  ├─ cache:zones (expire 24h)                                    │
│  ├─ session (user sessions)                                     │
│  └─ queues (job processing)                                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## Flux de Données - Cas d'Usage: Checkout + Paiement

```
CLIENT BROWSER                    LARAVEL APP                  EXTERNAL SERVICES
═══════════════════════════════════════════════════════════════════════════════════

1. User adds products to cart
   ──────────────────────────► CartController@add
                              └─► Store in session + DB
                                  └─► Return JSON response ◄──────────────────
                                                                          JSON

2. User clicks "Checkout"
   ──────────────────────────► CheckoutController@show
                              └─► Fetch cart items
                                  └─► Calculate shipping fees (Zone + Weight)
                                      └─► Display checkout form ◄──────────────
                                                                        HTML/View

3. User selects payment method
   ──────────────────────────► OrderController@store
                              └─► OrderService::createOrder()
                                  ├─► Create Order record
                                  ├─► Create OrderItems
                                  ├─► Validate stock
                                  └─► Return payment form ◄────────────────
                                                                    Payment Form

4. User clicks "Pay with CinetPay"
   ──────────────────────────► PaymentController@initiate
                              └─► PaymentService::processCinetPay()
                                  ├─► Create Payment record (pending)
                                  └─► Redirect to CinetPay ────────────► CinetPay API
                                                                           │
                                                                           │ User enters credentials
                                                                           │
                                                                           ▼
5. CinetPay processes payment
   ◄──────────────────────────────────────────────────────────── Redirect back to app

   PaymentController@callback
   ├─► Verify webhook signature
   ├─► Update Payment status (success/failed)
   ├─► Event: PaymentProcessed
   │   └─► Listener: CreateDelivery
   │       └─► Emit: OrderCreated event
   │           ├─► Send SMS (Queued)
   │            └─► Send Email (Queued)
   │
   ├─► Queue Job: SendSmsJob ("Order confirmed")
   │   └─► Afrika's Talking API ───────────────► SMS Gateway
   │                                               │
   │                                               ▼ SMS sent
   │
   ├─► Queue Job: GenerateInvoiceJob
   │   └─► DomPDF generates PDF
   │       └─► Store in storage/invoices
   │
   └─► Return success response ◄─────────────────────────────────────────
                                                            Confirmation Page


REAL-TIME UPDATES
═════════════════════════════════════════════════════════════════════════════════

6. Livreur checks app
   ──────────────────────────► DeliveryController@index (API)
                              └─► Fetch assigned deliveries
                                  └─► Return JSON ◄────────────────────
                                                            Livreur App Updates

7. Livreur clicks "Départ"
   ──────────────────────────► DeliveryController@startDelivery (API)
                              ├─► Update delivery status
                              ├─► Queue Job: SendSmsJob ("Départ en cours...")
                              │   └─► Africa's Talking ───────► Customer SMS
                              └─► Return JSON ◄─────────────────────────────


8. Customer checks tracking
   ──────────────────────────► TrackingController@public (no auth needed)
                              ├─► Fetch tracking link (unique token)
                              ├─► Get delivery location (lat/lng from livreur)
                              ├─► Google Maps API ──────────────► Map JS
                              │                                    │
                              │                                    ▼
                              └─► Display map with livreur location ◄────
                                                                Google Maps
```

---

## Security Flow

```
REQUEST ENTRY POINT
═════════════════════════════════════════════════════════════════

User Request
    │
    ├─► Cloudflare WAF checks
    │   ├─ DDoS protection
    │   ├─ Bot detection
    │   └─ Rate limiting
    │
    ├─► Nginx reverse proxy
    │   ├─ SSL/TLS termination
    │   ├─ Request validation
    │   └─ Compression (Gzip)
    │
    ▼
Laravel Middleware Stack
    │
    ├─► VerifyCsrfToken
    │   └─ Check CSRF token in POST/PUT/DELETE
    │
    ├─► EncryptCookies
    │   └─ Decrypt request cookies
    │
    ├─► TrimStrings & ConvertEmptyStringsToNull
    │   └─ Sanitize input
    │
    ├─► Auth middleware (Sanctum)
    │   ├─ Validate JWT token
    │   └─ Set Auth::user()
    │
    ├─► CheckRole middleware
    │   ├─ Verify user has required role
    │   └─ Throw 403 if unauthorized
    │
    ├─► RateLimiter
    │   ├─ Track requests per IP/user
    │   └─ Return 429 if exceeded
    │
    └─► Request arrives at Controller

PROCESSING
═════════════════════════════════════════════════════════════════

Controller
    │
    ├─► Validate Input
    │   ├─ FormRequest validation
    │   └─ Throw ValidationException if invalid
    │
    ├─► Service Layer
    │   ├─ Escape all user input (Blade auto-escapes)
    │   ├─ Use Eloquent ORM (prevents SQL injection)
    │   └─ Encrypt sensitive data (AES-256)
    │
    ├─► Database Query
    │   ├─ Parameterized queries (Eloquent)
    │   └─ Can't be SQL injected
    │
    └─► Return Response (JSON or View)

RESPONSE EXIT POINT
═════════════════════════════════════════════════════════════════

Response
    │
    ├─► Middleware (Response)
    │   ├─ Set security headers
    │   │  ├─ X-Frame-Options: DENY
    │   │  ├─ X-Content-Type-Options: nosniff
    │   │  ├─ X-XSS-Protection: 1; mode=block
    │   │  └─ Strict-Transport-Security
    │   │
    │   ├─ Add CSRF token to forms
    │   └─ Compress response (Gzip)
    │
    ├─► Nginx
    │   ├─ Add security headers
    │   ├─ SSL/TLS encryption
    │   └─ Rate limiting
    │
    ├─► Cloudflare
    │   ├─ CDN cache (if GET)
    │   ├─ Additional WAF checks
    │   └─ DDoS mitigation
    │
    └─► Browser
        ├─► Receive HTTPS response
        ├─► Parse HTML/JSON safely
        └─► Execute JS in sandbox
```

---

## Database Schema Overview

```
TABLES PRINCIPALES
═════════════════════════════════════════════════════════════════

users
├─ id (PK)
├─ name
├─ email (UNIQUE)
├─ password (bcrypt)
├─ phone
├─ role (enum: admin/vendor/courier/client)
├─ two_factor_secret
├─ two_factor_confirmed_at
├─ created_at
└─ updated_at

products
├─ id (PK)
├─ name
├─ slug
├─ description
├─ price_xof (integer)
├─ category_id (FK → categories)
├─ vendor_id (FK → users) [if multi-vendor]
├─ stock (integer)
├─ is_digital (boolean)
├─ created_at
└─ updated_at

categories
├─ id (PK)
├─ name
├─ slug
├─ description
├─ parent_id (FK → categories) [hierarchical]
├─ created_at
└─ updated_at

orders
├─ id (PK)
├─ user_id (FK → users)
├─ total_xof (integer)
├─ status (enum: pending/confirmed/preparing/shipped/delivered)
├─ payment_method (enum: cinet/stripe/paypal/cod)
├─ delivery_zone_id (FK → zones)
├─ estimated_delivery (timestamp)
├─ notes (text)
├─ created_at
└─ updated_at

order_items
├─ id (PK)
├─ order_id (FK → orders)
├─ product_id (FK → products)
├─ quantity (integer)
├─ price_xof (integer) [snapshot price]
├─ created_at
└─ updated_at

payments
├─ id (PK)
├─ order_id (FK → orders)
├─ method (enum: cinet/stripe/paypal/cod)
├─ amount_xof (integer)
├─ status (enum: pending/processing/success/failed)
├─ transaction_id (string) [external ref]
├─ reference (string) [internal ref]
├─ response_data (json) [API response]
├─ created_at
└─ updated_at

zones
├─ id (PK)
├─ name (city/region)
├─ country (code)
├─ base_tariff_xof (integer)
├─ per_kg_xof (integer)
├─ polygon (geometry) [optional for map]
├─ created_at
└─ updated_at

couriers
├─ id (PK)
├─ user_id (FK → users)
├─ zone_id (FK → zones)
├─ status (enum: active/inactive/on_delivery)
├─ current_lat (decimal)
├─ current_lng (decimal)
├─ current_order_id (FK → deliveries) [current task]
├─ created_at
└─ updated_at

deliveries
├─ id (PK)
├─ order_id (FK → orders)
├─ courier_id (FK → couriers)
├─ status (enum: pending/assigned/picked/in_transit/delivered/failed)
├─ estimated_arrival (timestamp)
├─ actual_arrival (timestamp)
├─ failure_reason (text)
├─ notes (text)
├─ created_at
└─ updated_at

locales
├─ id (PK)
├─ code (unique: fr/en/pt)
├─ name (French/English/Portuguese)
├─ is_active (boolean)
├─ created_at
└─ updated_at

INDEXES (Performance)
═════════════════════════════════════════════════════════════════

users
├─ email (UNIQUE)
└─ role

products
├─ category_id
├─ vendor_id
├─ slug (UNIQUE)
└─ created_at (for sorting)

orders
├─ user_id
├─ status
├─ created_at
└─ payment_method

order_items
├─ order_id

payments
├─ order_id
├─ status
└─ created_at

deliveries
├─ order_id
├─ courier_id
└─ status
```

---

## API Endpoints Architecture

```
REST API ROUTES
═════════════════════════════════════════════════════════════════

PUBLIC ENDPOINTS (No auth)
───────────────────────────────
GET  /api/products                   List products with filters
GET  /api/products/{id}              Get product details
GET  /api/categories                 List categories
GET  /api/tracking/{token}           Public tracking link
POST /api/auth/register              User registration
POST /api/auth/login                 User login
POST /api/auth/forgot-password       Reset password

AUTHENTICATED ENDPOINTS (Auth required)
───────────────────────────────────────────
POST /api/cart/add                   Add to cart
POST /api/cart/remove                Remove from cart
GET  /api/cart                       Get cart contents
POST /api/orders                     Create order (checkout)
GET  /api/orders                     List user orders
GET  /api/orders/{id}                Get order details
GET  /api/account/profile            Get user profile
PUT  /api/account/profile            Update profile
GET  /api/account/addresses          List addresses
POST /api/account/addresses          Add address
PUT  /api/account/addresses/{id}     Update address

COURIER ENDPOINTS (Role: courier)
──────────────────────────────────
GET  /api/courier/deliveries         Assigned deliveries
GET  /api/courier/deliveries/{id}    Delivery details
PUT  /api/courier/deliveries/{id}/start        Mark as departed
PUT  /api/courier/deliveries/{id}/complete     Mark as delivered
PUT  /api/courier/deliveries/{id}/fail         Mark as failed
GET  /api/courier/location           Get current location
PUT  /api/courier/location           Update location (GPS)

ADMIN ENDPOINTS (Role: admin)
──────────────────────────────
# Products
GET    /api/admin/products           List all products
POST   /api/admin/products           Create product
PUT    /api/admin/products/{id}      Update product
DELETE /api/admin/products/{id}      Delete product
POST   /api/admin/products/import    Bulk import CSV

# Orders
GET    /api/admin/orders             List all orders
GET    /api/admin/orders/{id}        Get order details
PUT    /api/admin/orders/{id}        Update order
PUT    /api/admin/orders/{id}/status Change status
POST   /api/admin/orders/{id}/refund Refund order

# Users
GET    /api/admin/users              List users
GET    /api/admin/users/{id}         User details
PUT    /api/admin/users/{id}         Update user
DELETE /api/admin/users/{id}         Delete user

# Analytics
GET    /api/admin/analytics/overview KPI overview
GET    /api/admin/analytics/orders   Order stats
GET    /api/admin/analytics/revenue  Revenue stats
GET    /api/admin/analytics/customers Customer stats

# Exports
POST   /api/admin/export/orders      Export orders to Excel
POST   /api/admin/export/products    Export products
POST   /api/admin/export/customers   Export customers

# Configuration
GET    /api/admin/config/zones       List zones
POST   /api/admin/config/zones       Create zone
PUT    /api/admin/config/zones/{id}  Update zone

RESPONSE FORMAT
═════════════════════════════════════════════════════════════════

Success (200)
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Product Name",
    ...
  }
}

Paginated (200)
{
  "status": "success",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}

Error (400/401/404/500)
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": ["Invalid email format"]
  }
}
```

---

## Deployment Architecture

```
PRODUCTION ENVIRONMENT
═════════════════════════════════════════════════════════════════

VPS Ubuntu 22.04 (Single server, can scale)
├─ CPU: 2 cores (can upgrade)
├─ RAM: 4GB (can upgrade)
├─ Storage: 50GB SSD
└─ Bandwidth: Unlimited

SERVICES RUNNING
─────────────────

1. Nginx (Port 80/443)
   ├─ Reverse proxy
   ├─ SSL/TLS termination
   ├─ Static file serving
   └─ Request routing

2. PHP-FPM 8.3 (Port 9000)
   ├─ Laravel app processes (multiple workers)
   ├─ Connection pooling
   └─ Auto-restart on crash

3. MySQL 8 (Port 3306)
   ├─ Persisted data
   ├─ Backups (daily @ 2 AM UTC)
   ├─ Replication (optional for HA)
   └─ Read replicas (optional)

4. Redis (Port 6379)
   ├─ Session store
   ├─ Cache layer
   ├─ Job queue
   ├─ Rate limiting
   └─ Locks

5. Supervisor (Process manager)
   ├─ Laravel Horizon (queue workers)
   ├─ Job processing (SMS, emails, PDFs)
   ├─ Auto-restart failed processes
   └─ Monitoring

6. Cron Jobs
   ├─ Update exchange rates (every 6 hours)
   ├─ Send daily admin report (08:00 UTC)
   ├─ Backup database (02:00 UTC daily)
   ├─ Clean old logs (weekly)
   └─ Health check (every 5 min)

CDN & SECURITY
───────────────

Cloudflare (Free tier)
├─ Global CDN
├─ DDoS protection
├─ Web Application Firewall (WAF)
├─ SSL/TLS management
├─ Cache static assets
└─ DNS management

DNS
├─ A record → VPS IP
├─ CNAME → Cloudflare
└─ MX records (for email)

Backups
├─ Database dump (daily)
├─ Files backup (weekly)
├─ Storage: AWS S3 or similar
└─ Retention: 30 days

Monitoring
├─ Sentry (error tracking)
├─ Laravel Horizon (queue monitoring)
├─ Nginx error logs
├─ MySQL slow query log
├─ Email alerts on critical errors
└─ Uptime monitoring (UptimeRobot)

DEPLOYMENT FLOW
───────────────

1. Developer pushes to GitHub (main branch)
2. GitHub Actions triggers CI pipeline
   ├─ Run tests
   ├─ Run linting
   ├─ Build assets
   └─ If pass: trigger deploy
3. SSH to VPS
4. Pull latest code
5. Run migrations
6. Clear caches
7. Restart PHP-FPM
8. Smoke test endpoints
9. Done! (5-10 min downtime, or zero with async)

SCALING STRATEGY (Future)
──────────────────────────

Phase 1: Current (Single VPS) ← We are here (Phase 4)
Phase 2: Read replicas (MySQL)
Phase 3: Load balancer + multiple app servers
Phase 4: Separate queue server (Redis cluster)
Phase 5: CDN for media (Cloudinary/AWS CloudFront)
Phase 6: Microservices architecture (if needed)
```

---

**Document** : Technical Architecture | **Version** : 1.0 | **Updated** : Mai 2026
