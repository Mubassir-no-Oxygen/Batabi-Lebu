SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `batabi_lebu`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisories`
--

CREATE TABLE `advisories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('weather_alert','pest_warning','farming_tip','market_update','govt_notice','other') NOT NULL DEFAULT 'other',
  `target_district` varchar(255) DEFAULT NULL COMMENT 'Target a specific district; NULL = all farmers',
  `severity` enum('info','warning','critical') NOT NULL DEFAULT 'info' COMMENT 'info = regular tip, warning = act soon, critical = immediate action',
  `published_by` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Inactive advisories are hidden from farmers',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Advisory auto-expires after this date',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agreements`
--

CREATE TABLE `agreements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `agreed_quantity` decimal(10,2) NOT NULL COMMENT 'Final agreed quantity from order/negotiation',
  `quantity_unit` varchar(255) NOT NULL COMMENT 'Unit of the agreed quantity (kg, ton, etc.)',
  `agreed_price_per_unit` decimal(10,2) NOT NULL COMMENT 'Final agreed price per unit in BDT',
  `total_amount` decimal(12,2) NOT NULL COMMENT 'agreed_quantity × agreed_price_per_unit',
  `bulk_discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Discount applied for bulk orders (0 = none)',
  `terms` text DEFAULT NULL COMMENT 'Custom terms, delivery conditions, etc.',
  `status` enum('draft','pending_farmer','pending_buyer','signed','cancelled') NOT NULL DEFAULT 'draft' COMMENT 'Track signing progress of both parties',
  `farmer_signed_at` timestamp NULL DEFAULT NULL,
  `buyer_signed_at` timestamp NULL DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `trade_license` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `user_id`, `company_name`, `address`, `district`, `trade_license`, `created_at`, `updated_at`) VALUES
(1, 12, 'Dhaka Traders Ltd.', '45 Karwan Bazar, Dhaka', 'Dhaka', NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(2, 13, 'Chittagong Fresh Co.', '12 Agrabad, Chittagong', 'Chittagong', NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(3, 14, 'Sylhet Agro Market', 'Zindabazar, Sylhet', 'Sylhet', NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(4, 15, NULL, 'Mirpur-10, Dhaka', 'Dhaka', NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(5, 16, 'Priya Suppliers', 'Shantinagar, Sylhet', 'Sylhet', NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(6, 17, 'Rajshahi Wholesale Hub', 'Shaheb Bazar, Rajshahi', 'Rajshahi', NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(7, 18, NULL, 'Jessore Sadar', 'Jessore', NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(8, 19, 'Green Basket Ltd.', 'Banani, Dhaka', 'Dhaka', NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(9, 20, 'Khulna Food Mart', 'Boyra, Khulna', 'Khulna', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(10, 21, NULL, 'Tangail Sadar', 'Tangail', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_review','resolved','closed') NOT NULL DEFAULT 'open',
  `admin_note` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `crop_name` varchar(255) NOT NULL,
  `category` enum('vegetable','fruit','grain','spice','other') NOT NULL DEFAULT 'other',
  `quantity` decimal(10,2) NOT NULL,
  `unit` enum('kg','ton','quintal','maund') NOT NULL DEFAULT 'kg',
  `price_per_unit` decimal(10,2) NOT NULL,
  `harvest_date` date DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `available_until` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','sold_out','upcoming') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`id`, `farmer_id`, `crop_name`, `category`, `quantity`, `unit`, `price_per_unit`, `harvest_date`, `available_from`, `available_until`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Red Tomato', 'vegetable', 500.00, 'kg', 45.00, '2026-04-17', '2026-04-10', '2026-05-17', 'Fresh organically grown red tomatoes, Grade A quality from Sylhet highlands.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(2, 2, 'Potato (Diamond)', 'vegetable', 2000.00, 'kg', 25.00, '2026-04-05', '2026-04-10', '2026-05-05', 'Diamond variety potato from Bogura — best quality in the market.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(3, 4, 'BRRI Dhan-28 Rice', 'grain', 5000.00, 'kg', 35.00, '2026-04-30', '2026-04-30', '2026-05-30', 'Boro season BRRI Dhan-28. Pre-orders welcome, ready in 3 weeks.', NULL, 'upcoming', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(4, 5, 'White Onion', 'vegetable', 800.00, 'kg', 60.00, '2026-04-10', '2026-04-10', '2026-05-10', 'Freshly harvested white onion, sun-dried and ready for bulk purchase.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(5, 6, 'Green Chili', 'vegetable', 300.00, 'kg', 90.00, '2026-04-13', '2026-04-10', '2026-05-13', 'Locally grown hot green chili, ideal for bulk processing.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(6, 7, 'Hilsa Brinjal', 'vegetable', 400.00, 'kg', 55.00, '2026-04-15', '2026-04-10', '2026-05-15', 'Large purple brinjal, disease-free. Pesticide-free cultivation.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(7, 9, 'Wheat (Shatabdi)', 'grain', 8000.00, 'kg', 28.00, '2026-03-31', '2026-04-10', '2026-04-30', 'Shatabdi variety wheat from Dinajpur, moisture content <12%.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(8, 10, 'Mango (Langra)', 'fruit', 600.00, 'kg', 120.00, '2026-05-25', '2026-05-25', '2026-06-24', 'Langra mango from Tangail — pre-book now for the season.', NULL, 'upcoming', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(9, 1, 'Lal Shak (Red Spinach)', 'vegetable', 200.00, 'kg', 30.00, '2026-04-12', '2026-04-10', '2026-05-12', 'Fresh lal shak, ready for harvest in 2 days.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(10, 2, 'Mustard Seed', 'spice', 1500.00, 'kg', 75.00, '2026-04-07', '2026-04-10', '2026-05-07', 'High oil-content mustard seed from Bogura. Cleaned & bagged.', NULL, 'available', '2026-04-10 16:08:40', '2026-04-10 16:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pickup_address` text DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `status` enum('pending','picked_up','in_transit','delivered','returned') NOT NULL DEFAULT 'pending',
  `tracking_number` varchar(255) DEFAULT NULL,
  `expected_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_requests`
--

CREATE TABLE `emergency_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('weather','pest','finance','other') NOT NULL DEFAULT 'other',
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('open','in_review','resolved') NOT NULL DEFAULT 'open',
  `admin_note` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `farm_name` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `sub_district` varchar(255) DEFAULT NULL,
  `land_size` decimal(10,2) DEFAULT NULL,
  `land_unit` enum('acre','bigha','hectare') NOT NULL DEFAULT 'bigha',
  `crops_grown` text DEFAULT NULL,
  `verification_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `user_id`, `farm_name`, `district`, `sub_district`, `land_size`, `land_unit`, `crops_grown`, `verification_status`, `rejection_reason`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'Rahim Green Farm', 'Sylhet', 'Companiganj', 15.50, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:35', 1, '2026-04-10 16:08:35', '2026-04-10 16:08:35'),
(2, 3, 'Karim Agro Farm', 'Bogura', 'Shibganj', 25.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:35', 1, '2026-04-10 16:08:35', '2026-04-10 16:08:35'),
(3, 4, 'Salam Natural Farm', 'Mymensingh', 'Trishal', 8.00, 'bigha', NULL, 'pending', NULL, NULL, NULL, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(4, 5, 'Jamal Organic Farm', 'Rajshahi', 'Paba', 20.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:36', 1, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(5, 6, 'Nurul Agro', 'Comilla', 'Debidwar', 12.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:36', 1, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(6, 7, 'Ratan Fresh Farm', 'Khulna', 'Phultala', 18.50, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:36', 1, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(7, 8, 'Faruk Paddy Farm', 'Dinajpur', 'Chirirbandar', 30.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:37', 1, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(8, 9, 'Selim Horticulture', 'Jessore', 'Bagherpara', 10.00, 'bigha', NULL, 'rejected', 'Incomplete farm documentation submitted.', NULL, NULL, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(9, 10, 'Kalam Green Fields', 'Tangail', 'Gopalpur', 22.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:37', 1, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(10, 11, 'Joynal Farm & Co', 'Rangpur', 'Mithapukur', 16.00, 'bigha', NULL, 'approved', NULL, '2026-04-10 16:08:37', 1, '2026-04-10 16:08:37', '2026-04-10 16:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `fraud_reports`
--

CREATE TABLE `fraud_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reported_user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('fake_listing','payment_fraud','non_delivery','quality_fraud','impersonation','scam','other') NOT NULL DEFAULT 'other',
  `description` text NOT NULL COMMENT 'Detailed description of the fraudulent activity',
  `evidence_path` varchar(255) DEFAULT NULL COMMENT 'Path to uploaded photo/document evidence',
  `status` enum('pending','investigating','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL COMMENT 'Admin investigation notes and resolution details',
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2024_01_01_000010_create_farmers_table', 1),
(3, '2024_01_01_000011_create_buyers_table', 1),
(4, '2024_01_01_000012_create_crops_table', 1),
(5, '2024_01_01_000013_create_orders_table', 1),
(6, '2024_01_01_000020_create_negotiations_table', 1),
(7, '2024_01_01_000021_create_payments_table', 1),
(8, '2024_01_01_000022_create_deliveries_table', 1),
(9, '2024_01_01_000023_create_reviews_table', 1),
(10, '2024_01_01_000024_create_complaints_table', 1),
(11, '2024_01_01_000025_create_emergency_requests_table', 1),
(12, '2024_01_01_000026_create_products_table', 1),
(13, '2024_01_01_000027_alter_orders_add_bulk_discount', 1),
(14, '2024_01_01_000028_create_agreements_table', 1),
(15, '2024_01_01_000029_create_fraud_reports_table', 1),
(16, '2024_01_01_000030_create_advisories_table', 1),
(17, '2024_01_01_000031_create_price_suggestions_table', 1),
(18, '2024_01_01_000032_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `negotiations`
--

CREATE TABLE `negotiations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `proposed_price` decimal(10,2) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected','countered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Dot-notation event type, e.g. order.accepted, advisory.critical',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON payload: links, IDs, action buttons, etc.' CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'NULL = unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `crop_id` bigint(20) UNSIGNED NOT NULL,
  `requested_quantity` decimal(10,2) NOT NULL,
  `offered_price` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `bulk_discount_percent` decimal(5,2) DEFAULT NULL COMMENT 'Percentage discount for bulk quantity orders',
  `note` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL COMMENT 'Admin note for disputes or moderation',
  `status` enum('pending','accepted','rejected','completed','cancelled') NOT NULL DEFAULT 'pending',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `crop_id`, `requested_quantity`, `offered_price`, `final_price`, `bulk_discount_percent`, `note`, `admin_note`, `status`, `accepted_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 200.00, 42.00, NULL, NULL, 'Need delivery by end of month.', NULL, 'pending', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(2, 2, 2, 1000.00, NULL, 25.00, NULL, 'Standard price is fine.', NULL, 'accepted', '2026-04-10 16:08:40', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(3, 3, 3, 500.00, 32.00, NULL, NULL, 'Pre-booking for next season.', NULL, 'pending', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(4, 4, 4, 400.00, 58.00, 58.00, NULL, 'Urgent — need within 5 days.', NULL, 'accepted', '2026-04-10 16:08:40', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(5, 5, 5, 150.00, NULL, NULL, NULL, 'Looking for pesticide-free only.', NULL, 'rejected', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(6, 6, 6, 200.00, 50.00, NULL, NULL, 'For restaurant supply, weekly repeat order expected.', NULL, 'pending', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(7, 7, 7, 3000.00, 26.00, 26.00, NULL, 'Price negotiable for bulk above 2 ton.', NULL, 'accepted', '2026-04-10 16:08:40', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(8, 8, 8, 100.00, 110.00, NULL, NULL, 'Pre-order for upcoming season.', NULL, 'pending', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(9, 9, 9, 80.00, NULL, 30.00, NULL, 'Delivered successfully.', NULL, 'completed', '2026-04-10 16:08:40', '2026-04-10 16:08:40', '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(10, 10, 10, 700.00, 70.00, 70.00, NULL, 'Need moisture certificate with delivery.', NULL, 'accepted', '2026-04-10 16:08:40', NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('bkash','nagad','bank','cash') DEFAULT NULL,
  `payment_status` enum('pending','paid','refunded','failed') NOT NULL DEFAULT 'pending',
  `escrow_status` enum('held','released','refunded') DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_suggestions`
--

CREATE TABLE `price_suggestions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `crop_name` varchar(255) NOT NULL,
  `category` enum('vegetable','fruit','grain','spice','other') NOT NULL DEFAULT 'other',
  `season` enum('rabi','kharif','all') NOT NULL DEFAULT 'all' COMMENT 'Season this price suggestion applies to',
  `district` varchar(255) DEFAULT NULL COMMENT 'District-specific price; NULL = nationwide average',
  `suggested_min_price` decimal(10,2) NOT NULL COMMENT 'Minimum fair price per unit (BDT)',
  `suggested_max_price` decimal(10,2) NOT NULL COMMENT 'Maximum fair price per unit (BDT)',
  `unit` varchar(255) NOT NULL DEFAULT 'kg' COMMENT 'Price unit (kg, ton, etc.)',
  `demand_level` enum('low','medium','high') NOT NULL DEFAULT 'medium' COMMENT 'Current market demand level for this crop',
  `source` enum('admin','govt_data','market_survey','system') NOT NULL DEFAULT 'admin' COMMENT 'Where this price data was sourced from',
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `reviewee_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','buyer','admin','delivery_partner','supplier') NOT NULL DEFAULT 'buyer',
  `phone` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `profile_photo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@batabi-lebu.com', NULL, '$2y$12$gmZHPHwYeuSiQDhzRO33X.9adMwU3XjI38YSNCRkVjG8uBqUSneB2', 'admin', '01700000000', NULL, NULL, '2026-04-10 16:08:35', '2026-04-10 16:08:35'),
(2, 'Rahim Uddin', 'rahim@farmer.com', NULL, '$2y$12$fRE1srkqXdpO0rSw4vWu4.Mtdg7wI6BztpNqOWnQGZd9II4RwR6eC', 'farmer', '01711000001', NULL, NULL, '2026-04-10 16:08:35', '2026-04-10 16:08:35'),
(3, 'Karim Ali', 'karim@farmer.com', NULL, '$2y$12$h9Ebt9ye72pBYnNKENnNfO4dgRU788MX47kL40cp16ghIbIBJSPxu', 'farmer', '01711000002', NULL, NULL, '2026-04-10 16:08:35', '2026-04-10 16:08:35'),
(4, 'Salam Mia', 'salam@farmer.com', NULL, '$2y$12$pSkjXDQjC9bj09Wv6MEZ1OXMf1Q4y3W8FbffoGypD7r7RQTGRzok2', 'farmer', '01711000003', NULL, NULL, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(5, 'Jamal Hossain', 'jamal@farmer.com', NULL, '$2y$12$EfiNlidjeVmyeN8nPD0bDez5.y5j6HC2q2M0TD2c3/aVXTJwOMRGO', 'farmer', '01711000004', NULL, NULL, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(6, 'Nurul Islam', 'nurul@farmer.com', NULL, '$2y$12$HRRgDApKOrfWd390fhZfI.X0NGBHtn.f/NdEc8ebew4H.w/QbsM9q', 'farmer', '01711000005', NULL, NULL, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(7, 'Ratan Kumar', 'ratan@farmer.com', NULL, '$2y$12$fb.HLDDmDaMrV.SPok5vH.WQNN3AsX5yzWpQ5AqrBzJLdKAClWUc.', 'farmer', '01711000006', NULL, NULL, '2026-04-10 16:08:36', '2026-04-10 16:08:36'),
(8, 'Faruk Ahmed', 'faruk@farmer.com', NULL, '$2y$12$S/1s470pYoHDWBejMMr8O.eyt2tPJjPD/R3ELyflr29izntEWivGS', 'farmer', '01711000007', NULL, NULL, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(9, 'Selim Molla', 'selim@farmer.com', NULL, '$2y$12$1MSa676hqIcpV2wTfXCcluUhQBWBke5vHANCkW5xboWhNSP4W87pa', 'farmer', '01711000008', NULL, NULL, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(10, 'Abul Kalam', 'abul@farmer.com', NULL, '$2y$12$lFUrbyqOtCOfpKG9xgVv2Ozvt3S.E3YLPARr2.g4MC6H/88WOx3pm', 'farmer', '01711000009', NULL, NULL, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(11, 'Joynal Abedin', 'joynal@farmer.com', NULL, '$2y$12$IhAFUfBZYHJewjsv480u4.HyRdR6e/Vx6nDULj57CnZF5m7FuNKQ.', 'farmer', '01711000010', NULL, NULL, '2026-04-10 16:08:37', '2026-04-10 16:08:37'),
(12, 'Dhaka Traders Ltd.', 'dhaka@buyer.com', NULL, '$2y$12$9LkLfsHOqKAE7Di3y6fq0eboAGO9h2b5HNW/gGcHvSuugGu8cuzLW', 'buyer', '01811000001', NULL, NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(13, 'Chittagong Fresh Co.', 'ctg@buyer.com', NULL, '$2y$12$eNjvb0.SVf2oShYuZvpL/OuRicpihPqne4OIIGV1jzDdybz4A4vF.', 'buyer', '01811000002', NULL, NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(14, 'Sylhet Agro Market', 'sylhet@buyer.com', NULL, '$2y$12$ULj6ErTmq7ocLnlSenvqc.u7tbWuyvJbUc6xmTyfYuePr65pghGg.', 'buyer', '01811000003', NULL, NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(15, 'Aman Khan', 'aman@buyer.com', NULL, '$2y$12$1UZNs03Ohg97i/w4.DxPKeKzUZB01tYd8SDAJaYTPhH4HGbC4zXjS', 'buyer', '01811000004', NULL, NULL, '2026-04-10 16:08:38', '2026-04-10 16:08:38'),
(16, 'Priya Suppliers', 'priya@buyer.com', NULL, '$2y$12$EJ0jx4CvMwhzjodQ4pJhAuaFydo1UTOBColcMZv.pELHvyFWqEiaq', 'buyer', '01811000005', NULL, NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(17, 'Rajshahi Wholesale Hub', 'rajshahi@buyer.com', NULL, '$2y$12$FyvgQnWz9pald2EzwGtrHe6iR5VjA4buBh30xVkFe6L3cxSQXmYoW', 'buyer', '01811000006', NULL, NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(18, 'Babu Mondol', 'babu@buyer.com', NULL, '$2y$12$vm3cIfqlHGHSaVQgplDrd.sHIBBiNpWa7hsEBsxJs5xi1XSqAxnyS', 'buyer', '01811000007', NULL, NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(19, 'Green Basket Ltd.', 'greenbasket@buyer.com', NULL, '$2y$12$dIWeytXP76TouVnwu0.Fme0MctcoSgLWlUHFM48hN2B3aPOaVQB4.', 'buyer', '01811000008', NULL, NULL, '2026-04-10 16:08:39', '2026-04-10 16:08:39'),
(20, 'Khulna Food Mart', 'khulna@buyer.com', NULL, '$2y$12$OCFokJsG6fOuyznAj/FRpeUMfzp4HVYECCf03Xk5b9unIs9gLJURm', 'buyer', '01811000009', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40'),
(21, 'Mofiz Uddin', 'mofiz@buyer.com', NULL, '$2y$12$o9djDRRfSPrfTp6Umji4NOjF9HfVsxk8zElJdbAl/1k1zBZcUSWru', 'buyer', '01811000010', NULL, NULL, '2026-04-10 16:08:40', '2026-04-10 16:08:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisories`
--
ALTER TABLE `advisories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advisories_published_by_foreign` (`published_by`);

--
-- Indexes for table `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agreements_order_id_unique` (`order_id`),
  ADD KEY `agreements_farmer_id_foreign` (`farmer_id`),
  ADD KEY `agreements_buyer_id_foreign` (`buyer_id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyers_user_id_foreign` (`user_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_user_id_foreign` (`user_id`),
  ADD KEY `complaints_order_id_foreign` (`order_id`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crops_farmer_id_foreign` (`farmer_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deliveries_tracking_number_unique` (`tracking_number`),
  ADD KEY `deliveries_order_id_foreign` (`order_id`),
  ADD KEY `deliveries_delivery_partner_id_foreign` (`delivery_partner_id`);

--
-- Indexes for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emergency_requests_farmer_id_foreign` (`farmer_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmers_user_id_foreign` (`user_id`),
  ADD KEY `farmers_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `fraud_reports`
--
ALTER TABLE `fraud_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fraud_reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `fraud_reports_reported_user_id_foreign` (`reported_user_id`),
  ADD KEY `fraud_reports_order_id_foreign` (`order_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negotiations`
--
ALTER TABLE `negotiations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negotiations_order_id_foreign` (`order_id`),
  ADD KEY `negotiations_sender_id_foreign` (`sender_id`),
  ADD KEY `negotiations_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_buyer_id_foreign` (`buyer_id`),
  ADD KEY `orders_crop_id_foreign` (`crop_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `price_suggestions`
--
ALTER TABLE `price_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_suggestions_created_by_foreign` (`created_by`),
  ADD KEY `price_suggestions_crop_name_category_season_district_index` (`crop_name`,`category`,`season`,`district`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_order_id_unique` (`order_id`),
  ADD KEY `reviews_reviewer_id_foreign` (`reviewer_id`),
  ADD KEY `reviews_reviewee_id_foreign` (`reviewee_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advisories`
--
ALTER TABLE `advisories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agreements`
--
ALTER TABLE `agreements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fraud_reports`
--
ALTER TABLE `fraud_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `negotiations`
--
ALTER TABLE `negotiations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_suggestions`
--
ALTER TABLE `price_suggestions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisories`
--
ALTER TABLE `advisories`
  ADD CONSTRAINT `advisories_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `agreements`
--
ALTER TABLE `agreements`
  ADD CONSTRAINT `agreements_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agreements_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agreements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_delivery_partner_id_foreign` FOREIGN KEY (`delivery_partner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_requests`
--
ALTER TABLE `emergency_requests`
  ADD CONSTRAINT `emergency_requests_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmers_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fraud_reports`
--
ALTER TABLE `fraud_reports`
  ADD CONSTRAINT `fraud_reports_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fraud_reports_reported_user_id_foreign` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fraud_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `negotiations`
--
ALTER TABLE `negotiations`
  ADD CONSTRAINT `negotiations_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `negotiations_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `negotiations_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_crop_id_foreign` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `price_suggestions`
--
ALTER TABLE `price_suggestions`
  ADD CONSTRAINT `price_suggestions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewee_id_foreign` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
