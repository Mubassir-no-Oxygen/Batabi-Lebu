# Batabi Lebu — Complete Database Schema Reference

**Database:** `batabi_lebu` | **Engine:** MySQL 8+ | **Charset:** `utf8mb4_unicode_ci`

> This document is the single source of truth for all teammates.
> Import `database/schema.sql` to get the full schema instantly.

---

## Quick Setup (for teammates)

```bash
# 1. Clone the repo and install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Set your DB credentials in .env
DB_DATABASE=batabi_lebu
DB_USERNAME=root
DB_PASSWORD=

# 4. Create the database in MySQL
mysql -u root -e "CREATE DATABASE batabi_lebu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Run all migrations
php artisan migrate

# 6. Seed demo data (optional)
php artisan db:seed

# 7. Generate app key
php artisan key:generate

# 8. Create storage symlink
php artisan storage:link
```

---

## Full Table Reference (17 Tables)

### ──────────────────────────────────────────────
### CORE TABLES (Implemented — Features 1–4)
### ──────────────────────────────────────────────

#### 1. `users`
Base authentication table. All roles share this table.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | Auto-increment |
| `name` | VARCHAR(255) | Full name |
| `email` | VARCHAR(255) UNIQUE | Login email |
| `email_verified_at` | TIMESTAMP NULL | — |
| `password` | VARCHAR(255) | Hashed (bcrypt) |
| `role` | ENUM | `farmer`, `buyer`, `admin`, `delivery_partner`, `supplier` |
| `phone` | VARCHAR(255) NULL | Contact phone |
| `profile_photo` | VARCHAR(255) NULL | Relative path in `storage/` |
| `remember_token` | VARCHAR(100) NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 2. `farmers`
Role-specific profile for users with `role = farmer`.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `user_id` | FK → `users.id` | CASCADE delete |
| `farm_name` | VARCHAR(255) | — |
| `district` | VARCHAR(255) | — |
| `sub_district` | VARCHAR(255) NULL | — |
| `land_size` | DECIMAL(10,2) NULL | — |
| `land_unit` | ENUM | `acre`, `bigha`, `hectare` |
| `crops_grown` | TEXT NULL | Comma-separated or descriptive |
| `verification_status` | ENUM | `pending`, `approved`, `rejected` |
| `rejection_reason` | TEXT NULL | Filled by admin on rejection |
| `verified_at` | TIMESTAMP NULL | When admin approved |
| `verified_by` | FK → `users.id` NULL | Admin who verified |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 3. `buyers`
Role-specific profile for users with `role = buyer`.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `user_id` | FK → `users.id` | CASCADE delete |
| `company_name` | VARCHAR(255) NULL | Optional for business buyers |
| `address` | TEXT NULL | Delivery address |
| `district` | VARCHAR(255) NULL | — |
| `trade_license` | VARCHAR(255) NULL | For future business verification |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 4. `crops`
Crop listings by verified farmers.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `farmer_id` | FK → `farmers.id` | CASCADE delete |
| `crop_name` | VARCHAR(255) | — |
| `category` | ENUM | `vegetable`, `fruit`, `grain`, `spice`, `other` |
| `quantity` | DECIMAL(10,2) | Available quantity |
| `unit` | ENUM | `kg`, `ton`, `quintal`, `maund` |
| `price_per_unit` | DECIMAL(10,2) | In BDT |
| `harvest_date` | DATE NULL | — |
| `available_from` | DATE NULL | — |
| `available_until` | DATE NULL | — |
| `description` | TEXT NULL | — |
| `image` | VARCHAR(255) NULL | Path in `storage/public/crops/` |
| `status` | ENUM | `available`, `sold_out`, `upcoming` |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 5. `orders`
Trade requests from buyers to farmers.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `buyer_id` | FK → `buyers.id` | CASCADE delete |
| `crop_id` | FK → `crops.id` | CASCADE delete |
| `requested_quantity` | DECIMAL(10,2) | — |
| `offered_price` | DECIMAL(10,2) NULL | Buyer's counter-price offer |
| `final_price` | DECIMAL(10,2) NULL | Set after negotiation/acceptance |
| `bulk_discount_percent` | DECIMAL(5,2) NULL | **Req 4** — Discount for large orders |
| `note` | TEXT NULL | Buyer note to farmer |
| `admin_note` | TEXT NULL | Admin moderation note |
| `status` | ENUM | `pending`, `accepted`, `rejected`, `completed`, `cancelled` |
| `accepted_at` | TIMESTAMP NULL | — |
| `completed_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

### ──────────────────────────────────────────────
### FEATURE TABLES (Future Sprints)
### ──────────────────────────────────────────────

#### 6. `negotiations` — **Requirement 5**
Price negotiation thread between buyer and farmer.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `order_id` | FK → `orders.id` | — |
| `sender_id` | FK → `users.id` | Who sent this bid |
| `receiver_id` | FK → `users.id` | Who receives it |
| `proposed_price` | DECIMAL(10,2) | — |
| `message` | TEXT NULL | — |
| `status` | ENUM | `pending`, `accepted`, `rejected`, `countered` |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 7. `agreements` — **Requirement 8**
Digital trade agreement, auto-generated after order confirmation.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `order_id` | FK → `orders.id` UNIQUE | One per order |
| `farmer_id` | FK → `farmers.id` | — |
| `buyer_id` | FK → `buyers.id` | — |
| `agreed_quantity` | DECIMAL(10,2) | — |
| `quantity_unit` | VARCHAR(255) | — |
| `agreed_price_per_unit` | DECIMAL(10,2) | — |
| `total_amount` | DECIMAL(12,2) | — |
| `bulk_discount_percent` | DECIMAL(5,2) | Default `0` |
| `terms` | TEXT NULL | Delivery conditions, custom terms |
| `status` | ENUM | `draft`, `pending_farmer`, `pending_buyer`, `signed`, `cancelled` |
| `farmer_signed_at` | TIMESTAMP NULL | — |
| `buyer_signed_at` | TIMESTAMP NULL | — |
| `document_path` | VARCHAR(255) NULL | Generated PDF path |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 8. `payments` — **Requirement 9**
Payment processing with escrow support.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `order_id` | FK → `orders.id` | — |
| `amount` | DECIMAL(12,2) | — |
| `payment_method` | ENUM NULL | `bkash`, `nagad`, `bank`, `cash` |
| `payment_status` | ENUM | `pending`, `paid`, `refunded`, `failed` |
| `escrow_status` | ENUM NULL | `held`, `released`, `refunded` |
| `transaction_id` | VARCHAR(255) NULL UNIQUE | Gateway tx ID |
| `paid_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 9. `deliveries` — **Requirement 7**
Delivery partner assignment and tracking.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `order_id` | FK → `orders.id` | — |
| `delivery_partner_id` | FK → `users.id` NULL | User with `role = delivery_partner` |
| `pickup_address` | TEXT NULL | Farmer's location |
| `delivery_address` | TEXT NULL | Buyer's address |
| `status` | ENUM | `pending`, `picked_up`, `in_transit`, `delivered`, `returned` |
| `tracking_number` | VARCHAR(255) NULL UNIQUE | — |
| `expected_at` | TIMESTAMP NULL | ETA |
| `delivered_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 10. `reviews` — **Requirement 15**
Ratings and feedback after completed orders.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `order_id` | FK → `orders.id` UNIQUE | One review per order |
| `reviewer_id` | FK → `users.id` | Who wrote the review |
| `reviewee_id` | FK → `users.id` | Who is being reviewed |
| `rating` | TINYINT | 1–5 stars |
| `comment` | TEXT NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 11. `fraud_reports` — **Requirement 10**
Fraud and suspicious activity reporting.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `reporter_id` | FK → `users.id` | Who filed the report |
| `reported_user_id` | FK → `users.id` | Who is accused |
| `order_id` | FK → `orders.id` NULL | Related order if any |
| `type` | ENUM | `fake_listing`, `payment_fraud`, `non_delivery`, `quality_fraud`, `impersonation`, `scam`, `other` |
| `description` | TEXT | — |
| `evidence_path` | VARCHAR(255) NULL | Uploaded photo/document |
| `status` | ENUM | `pending`, `investigating`, `resolved`, `dismissed` |
| `admin_note` | TEXT NULL | Investigation notes |
| `resolved_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 12. `complaints` — **Requirement 14**
General complaints and resolution tracking.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `user_id` | FK → `users.id` | — |
| `order_id` | FK → `orders.id` NULL | — |
| `subject` | VARCHAR(255) | — |
| `description` | TEXT | — |
| `status` | ENUM | `open`, `in_review`, `resolved`, `closed` |
| `admin_note` | TEXT NULL | — |
| `resolved_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 13. `emergency_requests` — **Requirement 11**
Farmer emergency support requests (flood, pest damage, etc.).

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `farmer_id` | FK → `farmers.id` | — |
| `type` | ENUM | `weather`, `pest`, `finance`, `other` |
| `description` | TEXT | — |
| `location` | VARCHAR(255) NULL | Specific location/village |
| `status` | ENUM | `open`, `in_review`, `resolved` |
| `admin_note` | TEXT NULL | — |
| `resolved_at` | TIMESTAMP NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 14. `advisories` — **Requirement 13**
Weather alerts and farming advisories from admin.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `title` | VARCHAR(255) | — |
| `content` | TEXT | — |
| `type` | ENUM | `weather_alert`, `pest_warning`, `farming_tip`, `market_update`, `govt_notice`, `other` |
| `target_district` | VARCHAR(255) NULL | **NULL = broadcast nationwide** |
| `severity` | ENUM | `info`, `warning`, `critical` |
| `published_by` | FK → `users.id` | Admin who created it |
| `is_active` | BOOLEAN | Default `true` |
| `expires_at` | TIMESTAMP NULL | Auto-hides after this date |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 15. `price_suggestions` — **Requirement 6**
Fair market price ranges for crop listings.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `crop_name` | VARCHAR(255) | Matches `crops.crop_name` |
| `category` | ENUM | Same as `crops.category` |
| `season` | ENUM | `rabi` (Nov–Feb), `kharif` (Mar–Oct), `all` |
| `district` | VARCHAR(255) NULL | **NULL = nationwide average** |
| `suggested_min_price` | DECIMAL(10,2) | — |
| `suggested_max_price` | DECIMAL(10,2) | — |
| `unit` | VARCHAR(255) | Default `kg` |
| `demand_level` | ENUM | `low`, `medium`, `high` |
| `source` | ENUM | `admin`, `govt_data`, `market_survey`, `system` |
| `notes` | TEXT NULL | — |
| `is_active` | BOOLEAN | — |
| `valid_from` | DATE NULL | — |
| `valid_until` | DATE NULL | — |
| `created_by` | FK → `users.id` NULL | — |
| `created_at`, `updated_at` | TIMESTAMP | — |
| *Index* | `(crop_name, category, season, district)` | Fast lookup |

---

#### 16. `products` — **Requirement 12**
Supplier marketplace: seeds, fertilizers, farming tools.

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT PK | — |
| `supplier_id` | FK → `users.id` | User with `role = supplier` |
| `product_name` | VARCHAR(255) | — |
| `category` | VARCHAR(255) NULL | e.g. seed, fertilizer, pesticide |
| `description` | TEXT NULL | — |
| `price` | DECIMAL(10,2) | — |
| `unit` | VARCHAR(255) NULL | kg, liter, packet |
| `stock_quantity` | INT | — |
| `image` | VARCHAR(255) NULL | — |
| `status` | ENUM | `active`, `inactive` |
| `created_at`, `updated_at` | TIMESTAMP | — |

---

#### 17. `notifications`
In-app notification hub for all platform events.

| Column | Type | Notes |
|---|---|---|
| `id` | UUID PK | Non-incrementing |
| `user_id` | FK → `users.id` | Recipient |
| `type` | VARCHAR(255) | e.g. `order.accepted`, `advisory.critical` |
| `title` | VARCHAR(255) | — |
| `message` | TEXT | — |
| `data` | JSON NULL | Links, IDs, action buttons |
| `read_at` | TIMESTAMP NULL | **NULL = unread** |
| `created_at`, `updated_at` | TIMESTAMP | — |
| *Index* | `(user_id, read_at)` | Fast unread count |

---

## Entity Relationship Summary

```
users ──────────────────┬── farmers ──┬── crops ──── orders ──┬── negotiations
                        │             │                        ├── payments
                        └── buyers ───┘                        ├── deliveries
                                                               ├── agreements
                                                               ├── reviews
                                                               ├── complaints
                                                               └── fraud_reports

farmers ─── emergency_requests
users   ─── notifications
users   ─── advisories (published_by)
users   ─── price_suggestions (created_by)
users   ─── products (supplier)
```

---

## Requirement → Table Mapping

| # | Requirement | Tables Involved |
|---|---|---|
| 1 | Farmer registration & verification | `users`, `farmers` |
| 2 | Buyer search & filter crops | `crops`, `farmers` |
| 3 | Farmer crop listings | `crops` |
| 4 | Bulk order requests | `orders` (`bulk_discount_percent`) |
| 5 | Price negotiation | `negotiations`, `orders` |
| 6 | Market price suggestions | `price_suggestions` |
| 7 | Delivery partner assignment | `deliveries`, `users` |
| 8 | Digital agreements | `agreements`, `orders` |
| 9 | Payment escrow | `payments` |
| 10 | Fraud/scam reporting | `fraud_reports` |
| 11 | Emergency support | `emergency_requests` |
| 12 | Seeds/fertilizer marketplace | `products`, `users` |
| 13 | Weather alerts & advisories | `advisories`, `notifications` |
| 14 | Complaints & tracking | `complaints` |
| 15 | Ratings & feedback | `reviews` |

---

## Models Available

| Model | File | Status |
|---|---|---|
| `User` | `app/Models/User.php` | ✅ Implemented |
| `Farmer` | `app/Models/Farmer.php` | ✅ Implemented |
| `Buyer` | `app/Models/Buyer.php` | ✅ Implemented |
| `Crop` | `app/Models/Crop.php` | ✅ Implemented |
| `Order` | `app/Models/Order.php` | ✅ Implemented |
| `Negotiation` | `app/Models/Negotiation.php` | 🔶 Stub ready |
| `Agreement` | `app/Models/Agreement.php` | 🔶 Stub ready |
| `Payment` | `app/Models/Payment.php` | 🔶 Stub ready |
| `Delivery` | `app/Models/Delivery.php` | 🔶 Stub ready |
| `Review` | `app/Models/Review.php` | 🔶 Stub ready |
| `FraudReport` | `app/Models/FraudReport.php` | 🔶 Stub ready |
| `Complaint` | `app/Models/Complaint.php` | 🔶 Stub ready |
| `EmergencyRequest` | `app/Models/EmergencyRequest.php` | 🔶 Stub ready |
| `Advisory` | `app/Models/Advisory.php` | 🔶 Stub ready |
| `PriceSuggestion` | `app/Models/PriceSuggestion.php` | 🔶 Stub ready |
| `Product` | `app/Models/Product.php` | 🔶 Stub ready |
| `Notification` | `app/Models/Notification.php` | 🔶 Stub ready |
