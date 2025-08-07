# SwiftShop Development Documentation - Day 2

## Project Setup and Database Migration Process

### Overview
This document provides a comprehensive record of the SwiftShop marketplace development process, including installation procedures, encountered errors, and their resolutions. SwiftShop is a multi-vendor e-commerce marketplace built with Laravel 11, designed to support multiple sellers with role-based access control.

---

## System Requirements and Initial Setup

### Prerequisites
- PHP 8.4.8
- Composer 2.8.9 
- XAMPP (Apache and MySQL)
- Node.js (for future frontend assets)

### Laravel Installation Process

#### Step 1: Project Creation
```bash
composer create-project laravel/laravel swiftshop
cd swiftshop
```

This command successfully downloaded Laravel 11 and created the complete project structure with all required dependencies.

#### Step 2: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

The .env file was configured with the following database settings:


#### Step 3: Database Setup Challenges

**Initial Port Confusion:**
The developer initially had databases on multiple ports:
- Port 3306: Contained an unfinished project (test database)
- Port 3307: Contained a finished project (innovations_company database)

**Resolution:**
- Identified existing databases through phpMyAdmin
- Safely removed the test database from port 3306
- Decided to use port 3307 for SwiftShop to maintain consistency
- Updated .env configuration to use port 3307

**Database Creation:**
```sql
DROP DATABASE test;
CREATE DATABASE swiftshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Step 4: Initial Migration Test
```bash
php artisan migrate
```

**First Issue Encountered:**
Warning message: "The database 'swiftshop' does not exist on the 'mysql' connection."

**Cause:** Laravel couldn't find the database despite it being created in phpMyAdmin.

**Resolution:** Allowed Laravel to create the database automatically by responding "Yes" to the prompt.

**Successful Output:**
```
Creating migration table ... DONE
Running migrations:
0001_01_01_000000_create_users_table ... DONE
0001_01_01_000001_create_cache_table ... DONE  
0001_01_01_000002_create_jobs_table ... DONE
```

---

## Database Schema Design

### Requirements Analysis
Based on the SwiftShop wireframes and multi-vendor marketplace requirements, the following entities were identified:

1. **Users** - Customers, vendors, and administrators
2. **Vendors** - Business information for sellers
3. **Categories** - Product classification (Electronics, Fashion, etc.)
4. **Products** - Marketplace items
5. **Orders** - Purchase records
6. **Order Items** - Individual products within orders
7. **Addresses** - Shipping information
8. **Cart Items** - Shopping cart functionality
9. **Wishlist Items** - Saved products

### Migration File Creation
```bash
php artisan make:migration create_vendors_table
php artisan make:migration create_categories_table  
php artisan make:migration create_products_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_addresses_table
php artisan make:migration create_cart_items_table
php artisan make:migration create_wishlist_items_table
```

### Table Structure Details

#### Vendors Table
```php
Schema::create('vendors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('business_name');
    $table->string('business_email');
    $table->string('business_phone');
    $table->text('business_address');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamp('applied_at')->useCurrent();
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
});
```

#### Categories Table
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug');
    $table->text('description');
    $table->string('image')->nullable();
    $table->timestamps();
});
```

#### Products Table
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->text('description');
    $table->decimal('price', 8, 2);
    $table->integer('stock_quantity');
    $table->json('images');
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
```

#### Orders Table
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->decimal('total_amount', 10, 2);
    $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
    $table->text('shipping_address');
    $table->string('payment_method')->default('COD');
    $table->timestamp('order_date')->useCurrent();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
```

#### Order Items Table
```php
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->onDelete('cascade');     
    $table->foreignId('product_id')->constrained()->onDelete('cascade');     
    $table->foreignId('vendor_id')->constrained()->onDelete('cascade');     
    $table->integer('quantity');
    $table->decimal('price_per_item', 8, 2);
    $table->decimal('total_price', 10, 2);
    $table->timestamps();
});
```

#### Addresses Table
```php
Schema::create('addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->enum('type', ['home', 'office'])->default('home');
    $table->string('full_name');
    $table->string('phone');
    $table->string('address_line_1');
    $table->string('address_line_2')->nullable();
    $table->string('city');
    $table->string('postal_code');
    $table->boolean('is_default')->default(false);
    $table->timestamps();
});
```

#### Cart Items Table
```php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->timestamps();
});
```

#### Wishlist Items Table
```php
Schema::create('wishlist_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

---

## Migration Execution and Error Resolution

### Critical Issue: Foreign Key Constraint Errors

#### Problem Description
Multiple attempts to run migrations resulted in foreign key constraint errors:

**Error 1:**
```
SQLSTATE[HY000]: General error: 1005 Can't create table `swiftshop`.`products` (errno: 150 "Foreign key constraint is incorrectly formed")
SQL: alter table `products` add constraint `products_vendor_id_foreign` foreign key (`vendor_id`) references `vendors` (`id`) on delete cascade
```

**Error 2:**
```
SQLSTATE[HY000]: General error: 1005 Can't create table `swiftshop`.`order_items` (errno: 150 "Foreign key constraint is incorrectly formed")
SQL: alter table `order_items` add constraint `order_items_order_id_foreign` foreign key (`order_id`) references `orders` (`id`) on delete cascade
```

#### Root Cause Analysis
The errors occurred due to **migration execution order issues**. Laravel executes migrations alphabetically by filename timestamp. When multiple migration files have identical timestamps, the execution order becomes unpredictable.

**Problematic Timestamp Sequence:**
- `2025_07_04_060542_create_categories_table.php`
- `2025_07_04_060542_create_products_table.php` (tried to reference vendors)
- `2025_07_04_060542_create_vendors_table.php` (hadn't run yet)

**Dependency Chain:**
```
users (base)
  ↓
vendors (requires users.id)
  ↓  
products (requires vendors.id + categories.id)
  ↓
orders (requires users.id)
  ↓
order_items (requires orders.id + products.id + vendors.id)
```

#### Resolution Strategy

**Step 1: Identify the Dependency Order**
Analyzed all foreign key relationships to determine the correct migration sequence.

**Step 2: Rename Migration Files**
Modified timestamps to enforce proper execution order:

- `2025_07_04_060541_create_vendors_table.php` (first)
- `2025_07_04_060542_create_categories_table.php`
- `2025_07_04_060542_create_products_table.php`
- `2025_07_04_060543_create_addresses_table.php`
- `2025_07_04_060543_create_cart_items_table.php`
- `2025_07_04_060543_create_wishlist_items_table.php`
- `2025_07_04_060544_create_orders_table.php`
- `2025_07_04_060545_create_order_items_table.php`

**Step 3: Clean Migration**
```bash
php artisan migrate:fresh
```

#### Successful Migration Output
```
Dropping all tables ... DONE
Creating migration table ... DONE
Running migrations:
0001_01_01_000000_create_users_table ... DONE
0001_01_01_000001_create_cache_table ... DONE
0001_01_01_000002_create_jobs_table ... DONE
2025_07_04_060541_create_vendors_table ... DONE
2025_07_04_060542_create_categories_table ... DONE
2025_07_04_060542_create_products_table ... DONE
2025_07_04_060543_create_addresses_table ... DONE
2025_07_04_060543_create_cart_items_table ... DONE
2025_07_04_060543_create_wishlist_items_table ... DONE
2025_07_04_060544_create_orders_table ... DONE
2025_07_04_060545_create_order_items_table ... DONE
```

---

## Final Database Structure

### Tables Created
The SwiftShop database now contains 11 tables:

**Laravel Default Tables:**
- users
- cache  
- jobs

**SwiftShop Custom Tables:**
- vendors
- categories
- products
- orders
- order_items
- addresses
- cart_items
- wishlist_items

### Foreign Key Relationships
- vendors.user_id → users.id
- products.vendor_id → vendors.id
- products.category_id → categories.id
- orders.user_id → users.id
- order_items.order_id → orders.id
- order_items.product_id → products.id
- order_items.vendor_id → vendors.id
- addresses.user_id → users.id
- cart_items.user_id → users.id
- cart_items.product_id → products.id
- wishlist_items.user_id → users.id
- wishlist_items.product_id → products.id

---

## Lessons Learned

### Migration Best Practices
1. **Plan Dependencies First**: Always map out foreign key relationships before creating migrations
2. **Use Descriptive Timestamps**: Ensure migration files have unique, sequential timestamps
3. **Test Migration Order**: Consider the execution sequence when designing table relationships
4. **Use Fresh Migrations**: When encountering constraint errors, use `migrate:fresh` for clean slate testing

### Laravel Foreign Key Constraints
1. **Referenced Table Must Exist**: Foreign keys can only reference existing tables
2. **Column Types Must Match**: Foreign key columns must have the same data type as referenced columns
3. **Cascade Deletion**: Use `onDelete('cascade')` carefully to maintain referential integrity

### Database Design Considerations
1. **Separation of Concerns**: User authentication data separated from business logic (vendors table)
2. **Flexibility**: JSON columns for images allow multiple file storage
3. **Audit Trail**: Timestamps track creation and modification dates
4. **Status Management**: Enum fields provide controlled state management

---

## Current Project Status

### Completed Components
- Laravel 11 framework installation
- Environment configuration
- Database connection establishment
- Complete database schema design
- All migration files created and executed successfully
- Multi-vendor marketplace foundation established

### Ready for Next Phase
The project is now prepared for:
- Routing implementation
- Controller development
- Model relationship establishment
- View creation
- Authentication system integration

### Development Environment
- Framework: Laravel 11
- Database: MySQL (port 3307)
- Server: XAMPP
- Development Server: php artisan serve (localhost:8000)

This completes the foundational setup for the SwiftShop multi-vendor marketplace platform.
