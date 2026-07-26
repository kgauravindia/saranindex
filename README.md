# Saran Index – Connecting Saran Digitally 🚀

[![OfferPlant Incorporation](https://img.shields.io/badge/Initiative-OfferPlant%20Technologies-yellow.svg)](http://offerplant.com)
[![Launch Date](https://img.shields.io/badge/Launch-26%20July%202026-blue.svg)](#)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4.svg?logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1.svg?logo=mysql)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3.svg?logo=bootstrap)](https://getbootstrap.com)

**Saran Index** (`saranindex.com`) is the comprehensive digital directory platform for **Saran District (Chapra, Bihar, India)**. Launched on the **9th Incorporation Day of OfferPlant Technologies Private Limited (26 July 2017 – 26 July 2026)**, it connects citizens, businesses, healthcare providers, educational institutions, advocates, government offices, and emergency services across all **20 Blocks** of Saran District.

---

## 🌟 Key Features

- 🏙️ **All 20 Saran District Blocks Covered**: Chapra Sadar, Marhaura, Sonepur, Revelganj, Garkha, Parsa, Dighwara, Amanour, Baniapur, Ekma, Taraiya, Ishuapur, Maker, Nagra, Manjhi, Jalalpur, Lahladpur, Dariapur, Mashrakh, and Panapur.
- 🗂️ **13 Categories & 121 Subcategories**: Deep taxonomy including Education (Universities, Degree Colleges, +2 Schools, Private Schools, ITI, Coaching), Healthcare (Hospitals, Doctors, Pathology Labs), Legal Hubs (Advocates, Notary), Govt Offices, Emergency Services, and Local Businesses.
- 🌐 **Bilingual Support (Hindi & English)**: Complete bilingual interfaces across free listing registration, search, directory profiles, and category navigation.
- ⚡ **SEO-Friendly Clean URLs**: Rewritten clean URL structures (e.g. `/category/schools-education/university`, `/listing/sadar-hospital-chapra`, `/blocks`, `/about`, `/contact`).
- 🚨 **24x7 Emergency Services Directory**: Instant access to police stations, Sadar Hospital, blood banks, fire brigade, and official helpline numbers.
- 🛡️ **Admin Control Panel**: Full backend admin portal (`/admin`) for listing verification, category & subcategory management, block administration, reviews, and messaging.
- 📱 **Responsive & Web App Manifest**: PWA-ready layout, custom favicons, and mobile-first design.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.3 (Object-Oriented & Procedural Helpers, PDO Database Abstraction)
- **Database**: MySQL / MariaDB (Prepared Statements, Foreign Keys with Cascade)
- **Frontend**: HTML5, Vanilla CSS Design System, Bootstrap 5.3, Bootstrap Icons 1.11
- **URL Rewriting**: Apache `.htaccess` Rewrite Engine
- **Social Integration**: Prime handles (`@saranindex`) for Facebook, Instagram, Twitter/X, Threads, YouTube, and WhatsApp Channel.

---

## 📦 Local Installation & Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/kgauravindia/saranindex.git
   cd saranindex
   ```

2. **Database Setup**:
   - Create a MySQL database named `saranindex`.
   - Import the complete schema & seed data located at `database/schema.sql`.
   ```bash
   mysql -u root -p saranindex < database/schema.sql
   ```

3. **Environment Configuration**:
   - Copy `config/config.example.php` to `config/config.php` if custom database credentials are required:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   define('DB_NAME', 'saranindex');
   ```

4. **Default Admin Credentials**:
   - **Admin Portal**: `http://localhost/saranindex/admin`
   - **Username**: `admin`
   - **Password**: `admin123`

---

## 📄 License & Legal

- Designed & Maintained by **[OfferPlant Technologies Private Limited](http://offerplant.com)**.
- Copyright © 2026 **Saran Index**. All Rights Reserved.
