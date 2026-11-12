# Modern Custom Laravel + Vue 3 CMS Platform

A high-performance, lightweight, and modern Content Management System (CMS) built with **Laravel 11**, **Inertia.js**, and **Vue 3**. It replicates the clean administrative UX and workflow of WordPress while eliminating legacy bottlenecks such as dynamic hook pollution (`add_action`, `add_filter`), complex global state, and heavy plugin overhead.

---

## 🌟 Key Architecture & Features

### 1. Dual-Mode Content Editor
* **Mode 1: Classic TipTap Editor:**
  * Clean distraction-free ProseMirror-based rich text editor (`@tiptap/vue-3`).
  * Instant WebP media insertion directly into text.
  * Right-hand sidebar meta boxes for status, custom permalink/slug, categories, tags, and featured images.
* **Mode 2: Elementor Pro-Style Visual Tree Builder:**
  * **Layout Hierarchy:** `Section` &rarr; `Columns` (flexible percentage widths: 100%, 50/50, 33/66, 33/33/33) &rarr; `Widgets`.
  * **Widget Inventory:** Headings (H1–H4), Paragraphs/Text, WebP Images, Call-to-Action Buttons, Spacers, Dividers.
  * **Live Property Inspector:** Clicking any canvas widget or section instantly opens its reactive inspector panel in the sidebar for live style changes (colors, typography, margins, alignments, attributes).
  * **Storage:** Clean hierarchical JSON layout trees stored directly in the `builder_json` column.

### 2. Automated Media Library & WebP Optimization
* **Backend Image Interception:** All uploaded images (`.jpg`, `.jpeg`, `.png`, `.gif`) are intercepted by `App\Services\MediaService` (using Intervention Image with GD driver).
* **Automatic WebP Conversion:** Images are compressed and converted into `.webp` files.
* **Responsive Resolutions:** Generates Thumbnail (`150x150`), Medium (`max 600px`), Large (`max 1200px`), and Original WebP sets.
* **Searchable Media Grid & Modal:** Drag-and-drop batch upload, file metadata inspection, alt-text editor, and reusable modal picker across both Classic and Visual Builder editors.

### 3. Built-In SEO Suite (Yoast Pro Style)
* **Per-Page SEO Controls:**
  * Focus keyword input field.
  * SEO Title and Meta Description custom editors with live character-count progress bars.
  * Google SERP desktop and mobile snippet live preview.
  * Open Graph (OG) and Twitter Card settings (custom title, description, and social share image).
  * XML Sitemap & Robots Index control toggles (`Index` / `NoIndex`, `Follow` / `NoFollow`).
* **Real-Time SEO Content Analysis Checklist:**
  * Keyword in SEO Title & starting position.
  * Keyword in Page URL slug.
  * Keyword in Meta Description.
  * Keyword in introductory content (first 100 words).
  * Real-time keyword density calculation (% and count).
  * Content word count evaluation.

### 4. Global Site Optimization & Cache Center
* Located at **Admin &rarr; Optimization Panel** (`/admin/settings/optimization`):
  * **Asset Caching & Compression:** Route caching toggle, config caching toggle, and HTTP response compression.
  * **Image Lazy Loading:** Global toggle to enforce native lazy loading across all WebP images.
  * **Database Cleanup:** One-click tools to purge post revisions, orphan media, and transient debug logs.
  * **Instant Cache Invalidation:** One-click clear for application cache, view templates, and routing lookup tables.

### 5. Role-Based Access Control (RBAC)
* Explicit middleware and policies for:
  * **Admin:** Full system control, optimization panel, taxonomies, all content.
  * **Editor:** Manages all posts, pages, categories, tags, and media library.
  * **Author:** Creates, drafts, and updates their own posts and media.

### 6. Future-Proof E-Commerce Architecture
* Dedicated modular namespace `App\Models\Commerce\` with ready migrations for:
  * `commerce_products` (SKU, price, sale price, inventory, builder layout)
  * `commerce_categories` (hierarchical taxonomy)
  * `commerce_variants` (SKU, pricing, attributes JSON)
  * `commerce_orders` & `commerce_order_items`

---

## 🚀 Default Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@cms.test` | `password` |
| **Editor** | `editor@cms.test` | `password` |
| **Author** | `author@cms.test` | `password` |

---

## 🛠️ Development & Running Commands

```bash
# Start Laravel Backend Development Server
php artisan serve

# Build frontend assets with Vite
npm run build

# Or run Vite HMR dev server
npm run dev

# Run Feature Test Suite
php artisan test
```
