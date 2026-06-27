# ERP-Arabic-Pro

نظام ERP احترافي مبني بإستخدام Laravel - A Professional ERP System Built with Laravel

## نظرة عامة | Overview

ERP-Arabic-Pro هو نظام إدارة موارد مؤسسية شامل مصمم خصيصاً للشركات والمؤسسات العربية، ويدعم اللغة العربية بشكل كامل مع واجهات استخدام حديثة وسهلة الاستخدام.

A comprehensive Enterprise Resource Planning system designed for Arabic businesses and organizations, with full Arabic language support and modern, user-friendly interfaces.

## المميزات | Features

- ✅ دعم كامل للغة العربية | Full Arabic Language Support
- ✅ إدارة المبيعات والفواتير | Sales & Invoicing Management
- ✅ إدارة المخزون | Inventory Management
- ✅ إدارة الحسابات والمالية | Accounting & Finance
- ✅ إدارة الموارد البشرية | HR Management
- ✅ التقارير المتقدمة | Advanced Reporting
- ✅ لوحة تحكم قابلة للتخصيص | Customizable Dashboard

## المتطلبات | Requirements

- PHP >= 8.1
- Composer
- Laravel 11.x
- MySQL/MariaDB
- Node.js >= 16

## التثبيت | Installation

```bash
# Clone the repository
git clone https://github.com/alhsbani/ERP-Arabic-Pro.git
cd ERP-Arabic-Pro

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Build assets
npm run build

# Start the application
php artisan serve
```

التطبيق سيكون متاحاً على: http://localhost:8000

## البنية | Project Structure

```
ERP-Arabic-Pro/
├── app/                    # تطبيق Laravel الأساسي
│   ├── Http/              # Controllers و Requests
│   ├── Models/            # نماذج قاعدة البيانات
│   ├── Services/          # خدمات الأعمال
│   └── Traits/            # Traits مشتركة
├── resources/             # الموارد (Views, CSS, JS)
│   ├── views/            # شاشات المستخدم
│   ├── css/              # ملفات CSS
│   └── js/               # ملفات JavaScript
├── routes/               # تعريفات المسارات
├── database/             # الهجرات والبذور
├── config/               # ملفات الإعدادات
├── tests/                # اختبارات الوحدة والتكامل
└── public/               # الملفات العامة
```

## الإعدادات الأساسية | Configuration

عدّل ملف `.env` بناءً على بيئتك:

```env
APP_NAME=ERP-Arabic-Pro
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_arabic_pro
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
```

## المساهمة | Contributing

نرحب بالمساهمات! يرجى:
1. عمل Fork للمستودع
2. إنشاء فرع للميزة الجديدة (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push للفرع (`git push origin feature/AmazingFeature`)
5. فتح Pull Request

## الترخيص | License

هذا المشروع مرخص تحت [MIT License](LICENSE)

## التواصل | Contact

📧 البريد الإلكتروني | Email: [your-email@example.com](mailto:your-email@example.com)

💼 موقع GitHub | GitHub: [@alhsbani](https://github.com/alhsbani)

---

**شكراً لاستخدامك ERP-Arabic-Pro** ❤️
