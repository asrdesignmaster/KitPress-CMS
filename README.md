<p align="center">
  <img src="public/favicon.svg" alt="KitPress Logo" width="64" height="64" />
</p>

<h1 align="center">KitPress</h1>
<p align="center"><strong>Build Smarter. Publish Faster.</strong></p>
<p align="center">
  A lightweight, high-performance, and secure modern Content Management System (CMS) built with <strong>Laravel 11</strong>, <strong>Inertia.js</strong>, <strong>Vue 3</strong>, and <strong>Tailwind CSS</strong> — designed as a clean, modular alternative to legacy WordPress.
</p>

---

## Why KitPress? (The Modern WordPress Alternative)

Traditional WordPress sites often slow down under the weight of legacy PHP hooks (`add_action`, `add_filter`), bloated `wp_options` tables, and dozens of third-party plugins for basic features like SEO, page building, caching, and WebP images — each introducing extra attack surface and maintenance overhead.

**KitPress replaces that entire stack with one clean, unified core:**

| Feature Area | Legacy WordPress | KitPress |
|---|---|---|
| **Architecture** | Procedural PHP + global hook soup | Clean **Laravel 11 MVC + Vue 3 SPA** (via Inertia.js) |
| **Performance** | Heavy theme/plugin overhead on every request | **Lightweight core**, native WebP pipeline, route/config caching & compression |
| **Security** | Vulnerable third-party plugin ecosystem | **Laravel CSRF, Eloquent ORM, strict validation & built-in RBAC** (no plugin sprawl) |
| **Page Building** | Requires heavy external plugins (Elementor, etc.) | **Native Visual Tree Builder** + Classic TipTap Editor |
| **Theme & Design** | Fragmented Customizer + theme lock-in | **Built-in Theme Studio** (Colors, Typography, Buttons, Header & Footer Builders) |
| **SEO & Sitemaps** | Requires Yoast / RankMath plugins | **Built-in SEO Suite** + `/sitemap.xml` out of the box |

---

## Key Architecture & Features

### 1. Dual-Mode Content Editor
* **Classic TipTap Editor:**
  * Clean, distraction-free ProseMirror-based rich text editor (`@tiptap/vue-3`).
  * Instant WebP media insertion directly into content.
  * Sidebar controls for publishing status, custom permalink/slug, categories, tags, and featured images.
* **Visual Tree Builder (Elementor-Style):**
  * **Layout Hierarchy:** `Section` &rarr; `Columns` (flexible layouts: `100%`, `50/50`, `33/66`, `33/33/33`, etc.) &rarr; `Widgets`.
  * **Widget Inventory:** Headings (`H1`–`H4`), Paragraphs/Text, WebP Images, Call-to-Action Buttons, Spacers, and Dividers.
  * **Live Property Inspector:** Click any section or widget to edit colors, typography, spacing, and alignment live.
  * **Clean Storage:** Structured JSON layout trees saved cleanly in `builder_json`.

### 2. Native Theme Studio (`/admin/theme/*`)
* **General (Colors, Typography & Custom CSS):**
  * Global CSS variable color palette (`--theme-primary`, `--theme-secondary`, `--theme-bg`, `--theme-surface`, `--theme-text`, `--theme-accent`).
  * Per-element typography rules for **Paragraph (`p`)** and **Headings (`h1`–`h4`)** with Webfont selection (Bunny Fonts), color picker, font size (`px` / `em` / `rem`), weight, and line height — synced across the public site, rich editor, and live previews.
  * Sticky right-side **Live Theme Preview** and Custom CSS editor.
* **Buttons Builder:**
  * Configure global button shape (border radius, padding, font weight) and **Primary / Secondary / Outline** variants with Normal & Hover states, solid or gradient backgrounds, and theme color swatches.
* **Header Builder:**
  * Supports **Transparent Header**, **Sticky Header**, custom logo image/text, navigation links, CTA button, and **3 Header Layout Styles** (Classic, Centered Menu, Full-Width Menu Bar below logo with customizable colors).
* **Footer Builder:**
  * Default **Brand Column** (Company Logo + Rich Paragraph Editor), up to 4 custom link columns, customizable colors, and copyright notice.

### 3. Automated Media Library & WebP Pipeline
* **Server-Side Image Interception:** Uploaded `.jpg`, `.jpeg`, `.png`, and `.gif` files are processed automatically by `App\Services\MediaService` (Intervention Image + GD).
* **Automatic WebP Conversion:** Generates optimized `.webp` files in **Thumbnail (`150x150`)**, **Medium (`max 600px`)**, **Large (`max 1200px`)**, and **Original** sizes.
* **Reusable Media Modal:** Drag-and-drop batch uploads, search, alt-text editing, and instant selection across editors and Theme Builders.

### 4. Built-In SEO Suite
* **Per-Post / Per-Page SEO:** Focus keywords, custom SEO Title & Meta Description with character-count progress bars, and live Desktop/Mobile Google SERP previews.
* **Social & Indexing Controls:** Open Graph (OG) and Twitter Card fields, plus `Index/NoIndex` and `Follow/NoFollow` toggles.
* **Real-Time Content Analysis:** Checks keyword placement in title, slug, meta description, intro text, keyword density, and word count.

### 5. Global Optimization & Cache Center (`/admin/settings/optimization`)
* Configure Site Name, Tagline, and Homepage Display (Latest Posts or Static Page).
* Toggle **Route Caching**, **Config Caching**, **HTTP Compression**, and **Image Lazy Loading**.
* One-click **Database Cleanup** (purge old post revisions and orphan media) and **Cache Invalidation**.

### 6. Role-Based Access Control (RBAC) & Security
* **Admin:** Full system control, Theme Studio, Optimization Center, taxonomies, and all content.
* **Editor:** Manage all posts, pages, categories, tags, and media.
* **Author:** Create, draft, and manage their own posts and media.
* Backed by Laravel's built-in CSRF protection, encrypted sessions, password hashing, and parameterized Eloquent queries.

### 7. Modular E-Commerce Ready Foundation
* Dedicated `App\Models\Commerce\` namespace with schema support for `commerce_products`, `commerce_categories`, `commerce_variants`, `commerce_orders`, and `commerce_order_items`.

---

## Default Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@cms.test` | `password` |
| **Editor** | `editor@cms.test` | `password` |
| **Author** | `author@cms.test` | `password` |

---

## Quick Start & Development Commands

```bash
# 1. Install PHP & Node dependencies
composer install
npm install

# 2. Configure environment & database
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Start Laravel backend & Vite frontend
php artisan serve
npm run dev

# Build production frontend assets
npm run build

# Run feature test suite
php artisan test
```
