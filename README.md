# Multan Mango Treasures 🥭 (Mango Marketplace)

A professional E-commerce web application built with **Laravel** designed to connect the local mango growers of Multan directly with retail buyers and customers. The platform streamlines order placement, voucher discounts, administrative management, and grower verification.

---

## 🚀 Key Features

### 🛒 Customer & Checkout Experience
* **Dynamic Product Catalog:** Showcases premium Multan mango varieties (e.g., Organic Chaunsa) direct from the orchard with weight-batch packaging.
* **Smart Order Summary:** Real-time billing details including Subtotal, Cargo Care Fee, and Voucher Discounts.
* **Voucher Coupon System:** Fully functional promotional discount logic (e.g., Applying `MANGO10` instantly recalculates the total amount).
* **Location Pinning:** Integrated map functionality allowing customers to automatically detect or manually pin their exact coordinates for fast delivery riders.

### 🛡️ Administrative Dashboard (`AdminController`)
* **Financial Analytics:** Tracks total revenue earned through marketplace sales.
* **Grower Management:** Monitors and approves active grower registrations.
* **Secure Payment Verification:** Back-end support for verifying grower setup and manual/digital payment milestones via JazzCash.

---

## 🛠️ Technical Stack

* **Backend:** PHP (Laravel Framework)
* **Frontend:** Blade Templating Engine, Tailwind CSS / Custom CSS, JavaScript (Vanilla JS for interactive elements)
* **Database:** MySQL / SQL (Relational schema for managing users, farms, products, vouchers, and orders)

---

## 📦 Database Architecture Highlights

The application relies on a structured relational database layout managing essential entities:
* **Growers & Farms:** Tracks verified farm listings in Multan.
* **Products:** Handles mango categories, pricing per KG, and inventory availability.
* **Orders & Transactions:** Manages checkout details, customer addresses, WhatsApp contact info, and pinned geolocations.

---

--

## 🔧 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/MuteebaKhan/multan_mango_marketplace.git](https://github.com/MuteebaKhan/multan_mango_marketplace.git)
2. Install dependancies
   composer install
   npm install && npm run dev
3. Environment Setup:

    Duplicate .env.example to .env
    
    Configure your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    
    Generate application key:
    php artisan key:generate
4. Run Migrations & Seeders:
    php artisan migrate --seed
5. Start the local server:

Bash
php artisan serve
---

## 📝 Demo & Contributions

For visual documentation and interface walk-throughs, please refer to the screenshots and dashboard logs available in the [Issues Section](https://github.com/MuteebaKhan/multan_mango_marketplace/issues/1).
