---
description: Setup Bio Farm Trading Laravel 12 E‑Commerce Application
---

# Overview
This workflow guides you through creating a full‑featured Laravel 12 e‑commerce platform for **Bio Farm Trading**. It covers project scaffolding, database design, core e‑commerce features, payment integration, admin dashboard, and deployment basics. Follow each step sequentially. Commands marked with `// turbo` will be auto‑executed when safe.

## Prerequisites
- **Windows** OS with **PHP 8.2+**, **Composer**, **Node.js**, and **Git** installed.
- MySQL/MariaDB server accessible locally.
- Optional: **Docker** if you prefer containerised development.

## 1. Project Scaffold
1. Open a terminal in `C:\composer\biofarmtrading`.
2. Run Composer to create a new Laravel 12 project.
   ```bash
   composer create-project --prefer-dist laravel/laravel . "12.*"
   ```
   // turbo
3. Initialize a Git repository and make the first commit.
   ```bash
   git init
   git add .
   git commit -m "Initial Laravel 12 scaffold"
   ```
   // turbo
4. Install frontend tooling (Vite, Tailwind, etc.).
   ```bash
   npm install
   npm run build
   ```
   // turbo

## 2. Environment Configuration
1. Duplicate `.env.example` to `.env` and generate an app key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   // turbo
2. Edit `.env` to configure the database:
   ```text
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=biofarmtrading
   DB_USERNAME=your_mysql_user
   DB_PASSWORD=your_mysql_password
   ```
3. Run migrations to create default tables.
   ```bash
   php artisan migrate
   ```
   // turbo

## 3. Database Design (Migrations & Models)
Create the core tables:
- **users** (Laravel default) – add `phone`, `address` columns.
- **products** – `name`, `slug`, `description`, `price`, `stock`, `image_path`, `category_id`.
- **categories** – hierarchical product categories.
- **orders** – `user_id`, `status`, `total_amount`, `payment_method`, `delivery_address`.
- **order_items** – `order_id`, `product_id`, `quantity`, `price`.
- **shops** – store locations (Cacaveli, Hedzranawoe) with address and contact.

Run the following commands (each marked // turbo):
```bash
php artisan make:model Product -m
php artisan make:model Category -m
php artisan make:model Order -m
php artisan make:model OrderItem -m
php artisan make:model Shop -m
```
// turbo

Edit the generated migration files in `database/migrations/` to match the schema above, then run:
```bash
php artisan migrate:fresh
```
// turbo

## 4. Core E‑Commerce Features
### 4.1 Product Catalog
- Create `ProductController` with `index`, `show` methods.
- Routes: `GET /`, `GET /product/{slug}`.
- Views: `resources/views/products/index.blade.php`, `show.blade.php` using a premium UI (glassmorphism, gradient, Google Font "Inter").

### 4.2 Shopping Cart
- Use Laravel session to store cart items.
- `CartController` with `add`, `remove`, `update`, `checkout` actions.
- Blade component `components/cart.blade.php` for a floating cart drawer.

### 4.3 Checkout & Payment
- Offer **Cash on Delivery** and **Mobile Money** (Flooz, TMoney).
- Create a simple `PaymentController` that records the chosen method.
- For Mobile Money, integrate the provider’s API (POST to their endpoint with amount, phone, callback URL).
- After successful payment, create an `Order` record and send a notification (email/SMS).

### 4.4 User Account
- Extend Laravel Breeze/Jetstream for authentication.
- Add profile page showing order history.
- Blade view `resources/views/account/dashboard.blade.php`.

### 4.5 Admin Dashboard
- Protect routes with `admin` middleware.
- Use **Laravel Nova** (free tier) or build a custom admin UI with Tailwind.
- Features: product CRUD, stock management, order list, shop management, analytics cards.
- Add real‑time notifications using Laravel Echo + Pusher (or simple DB polling).

### 4.6 WhatsApp Quick Order
- Add a button on product pages that opens a pre‑filled WhatsApp URL:
  ```html
  <a href="https://wa.me/229XXXXXXXX?text=Je%20souhaite%20commander%20{{ $product->name }}" target="_blank" class="whatsapp-btn">WhatsApp</a>
  ```
- Style the button with the brand’s green gradient.

## 5. Front‑End Polish (Aesthetics)
- Install Google Font **Inter** in `resources/views/layouts/app.blade.php`.
- Create a CSS file `resources/css/app.css` with:
  - Dark‑mode support using `prefers-color-scheme`.
  - Glassmorphism cards: `backdrop-filter: blur(12px); background: rgba(255,255,255,0.15);`.
  - Subtle hover micro‑animations (`transition: transform 0.2s;`).
- Use Vite to compile assets: `npm run dev` for development, `npm run build` for production.

## 6. Testing & Quality Assurance
1. Write feature tests with **Pest** or **PHPUnit** for:
   - Product listing and detail pages.
   - Cart operations.
   - Order creation.
2. Run `php artisan test` to ensure all pass.
3. Lint CSS/JS with **ESLint** and **Stylelint**.

## 7. Deployment (Optional for the week)
- Choose a VPS or shared hosting that supports PHP 8.2 and MySQL.
- Push code to a Git repo (GitHub/GitLab).
- On the server:
  ```bash
  git clone <repo-url>
  cd biofarmtrading
  composer install --no-dev --optimize-autoloader
  cp .env.example .env
  php artisan key:generate
  # configure .env with production DB credentials
  php artisan migrate --force
  npm ci
  npm run build
  php artisan storage:link
  ```
- Set up **Supervisor** to run `php artisan queue:work` if you use queued notifications.
- Configure **NGINX** or **Apache** to point `public/` as the web root.

## 8. Timeline (One‑Week Sprint)
| Day | Goal |
|-----|------|
| 1   | Scaffold project, configure env, basic DB schema |
| 2   | Implement product catalog, categories, and UI |
| 3   | Build cart system and checkout flow (cash + mobile money) |
| 4   | User authentication & account dashboard |
| 5   | Admin dashboard with CRUD and notifications |
| 6   | WhatsApp integration, final UI polish (glassmorphism, dark mode) |
| 7   | Testing, bug‑fixing, optional deployment |

---

**Next Steps**
- Run the `composer create-project` command (step 1) to generate the Laravel base.
- Let me know once the scaffold is ready, and we’ll continue with migrations and UI components.

*Feel free to ask for deeper details on any step, or request code snippets for specific controllers, models, or Blade views.*
