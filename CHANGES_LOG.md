# KitPress CMS — Change Log

All project changes are recorded here (newest first).

## Change Log

### 2026-11-14 13:27 — Elementor-Style Rich Text Editor for Visual Builder Text Widget
- **Summary**: Replaced the plain `<textarea>` in the Visual Builder Text Editor widget inspector with an Elementor-style rich text editor (`Visual` / `Text` tabs, block format dropdown, Bold/Italic/Underline/Strike, lists, blockquote, link, text color, clear formatting, undo/redo, fullscreen) and updated `BuilderWidgetRenderer.vue` to render rich HTML output.
- **Files**:
  - `resources/js/Components/RichParagraphEditor.vue` — added `Visual`/`Text` mode bar, Strikethrough, Blockquote, Text Color mark, Clear Formatting, Undo, and Redo controls
  - `resources/js/Components/Builder/WidgetInspector.vue` — replaced `textarea` with `RichParagraphEditor` for `selectedWidget.type === 'text'`
  - `resources/js/Components/Builder/BuilderWidgetRenderer.vue` — rendered `text` widget via `v-html` with `.builder-rich-text` typography/list/blockquote/link styling
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 13:20 — Applied Theme Colors & Typography to Blog Frontend
- **Summary**: Updated all 3 Blog layout styles and pagination in `Public/Home.vue` to use the active Theme General CSS variables (`--theme-primary`, `--theme-secondary`, `--theme-surface`, `--theme-text`, `--theme-accent`, `--theme-font-heading`) and `.theme-btn-primary`.
- **Files**:
  - `resources/js/Pages/Public/Home.vue` — replaced hardcoded indigo/slate/amber colors with theme CSS variables and button classes across Style 1, Style 2, Style 3, and pagination
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 13:18 — Uncheck Transparent Header When Blog Homepage Mode Is Selected
- **Summary**: Automatically disabled `theme_header.transparent` in Settings when `homepage_display` is set to `latest_posts` (Blog), and guarded `PublicLayout.vue` so the transparent header never activates on Blog mode.
- **Files**:
  - `app/Http/Controllers/Admin/OptimizationController.php` — set `theme_header.transparent = false` when `homepage_display === 'latest_posts'`
  - `resources/js/Layouts/PublicLayout.vue` — added `page.props.settings?.homepage_display !== 'latest_posts'` guard to `isTransparentHeader`
- **Verification**: `php -l` passed on `OptimizationController.php` (Vite dev server active).

### 2026-11-14 13:16 — Removed Redundant Hero Header from Blog Homepage
- **Summary**: Removed the extra Hero Header banner from `Public/Home.vue` since `PublicLayout` already provides the site header and footer.
- **Files**:
  - `resources/js/Pages/Public/Home.vue` — removed the redundant `Hero Header` `<section>` above `<main>`
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 13:15 — Added Public `/blog` Route & Renamed Sidebar "Optimization Panel" to "Settings"
- **Summary**: Added an explicit `/blog` public route (`PublicController@blog`) so visiting `/blog` renders the blog feed instead of 404, cleared Laravel route cache, and renamed "Optimization Panel" to "Settings" in the admin sidebar.
- **Files**:
  - `routes/web.php` — added `GET /blog` (`blog`) route before dynamic slug catch-all
  - `app/Http/Controllers/PublicController.php` — extracted `renderBlogFeed()` and added `blog()` method
  - `resources/js/Layouts/AdminLayout.vue` — renamed `Optimization Panel` sidebar label/title to `Settings`
- **Verification**: `php -l` passed on `PublicController.php` and `routes/web.php`; `php artisan route:clear` succeeded.

### 2026-11-14 13:12 — Added Conditional Theme Blog Submenu & 3 Blog Layout Styles
- **Summary**: Displayed a conditional "Blog" submenu item under Theme (after Buttons) when Blog homepage mode is active, added the Theme › Blog style selector page (`style_1`, `style_2`, `style_3`), and updated the public blog homepage to render all 3 styles with pagination.
- **Files**:
  - `app/Http/Middleware/HandleInertiaRequests.php` — shared `homepage_display` in Inertia `settings` props
  - `routes/web.php` — added `theme.blog` (`GET /admin/theme/blog`) and `theme.blog.update` (`POST /admin/theme/blog`) routes
  - `app/Http/Controllers/Admin/ThemeController.php` — added `defaultBlog()`, `blog()`, `updateBlog()`, and included `blog` in `getThemeConfig()`
  - `resources/js/Layouts/AdminLayout.vue` — added conditional `Blog` link after `Buttons` when `homepage_display === 'latest_posts'`
  - `resources/js/Pages/Admin/Theme/Blog.vue` — created Theme › Blog style selector page with wireframe previews for Style 1, Style 2, and Style 3
  - `resources/js/Pages/Public/Home.vue` — implemented Style 1 (Hero Post, 4 Posts, Highlighted Post, Pagination), Style 2 (2-Column Grid + Sidebar + Pagination), and Style 3 (Minimalist Horizontal List + Pagination)
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 13:06 — Renamed Homepage Reading Option Label to "Blog"
- **Summary**: Updated the default homepage display radio option label from "Your latest posts" to "Blog" in Settings.
- **Files**:
  - `resources/js/Pages/Admin/Settings/Optimization.vue` — changed label text to `Blog`
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 13:04 — Show Home Page Badge on Selected Homepage in Pages/Posts List
- **Summary**: Added a "Home Page" badge next to the title in the admin Pages/Posts table when a static page or post is selected as the site homepage in Settings.
- **Files**:
  - `app/Http/Controllers/Admin/PostController.php` — passed `homepagePostId` to `Admin/Posts/Index` when `homepage_display` is `static_page`
  - `resources/js/Pages/Admin/Posts/Index.vue` — rendered `Home Page` badge beside the matching post/page title
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 12:11 — Default First Widget Category Open, Others Collapsed
- **Summary**: Updated Visual Builder widget categories so only the first category (`Basic`) is expanded by default while all other categories start collapsed.
- **Files**:
  - `resources/js/Components/Builder/VisualBuilder.vue` — initialized `collapsedCategories` with `idx !== 0`
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 12:02 — Collapsible Widget Categories in Visual Builder
- **Summary**: Made all widget category groups (`Basic`, `Pro Elements`, `Interactive`, `Social & Media`) collapsible in the Visual Builder sidebar with widget count badges and auto-expand during search.
- **Files**:
  - `resources/js/Components/Builder/VisualBuilder.vue` — added collapsible category headers, `collapsedCategories` state, `isCategoryCollapsed()`, and `toggleCategory()`
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:51 — Default Collapsed Right Panel in Visual Builder Mode
- **Summary**: Configured the post/page editor so the right sidebar panel is collapsed by default whenever Visual Builder mode is active or selected, while preserving the user's Classic TipTap sidebar preference.
- **Files**:
  - `resources/js/Pages/Admin/Posts/Editor.vue` — defaulted `rightSidebarCollapsed` to `true` for `editor_type === 'builder'` and added a watcher on `form.editor_type`
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:49 — Visual Builder Button Alignment Icons & Theme Button Variants
- **Summary**: Replaced `L / C / R` alignment labels with alignment icons in Widget Inspector and added `Primary`, `Secondary`, and `Custom` button style options linked to Theme › Buttons.
- **Files**:
  - `resources/js/Components/Builder/widgetRegistry.js` — added default `variant: 'primary'` to the Button widget
  - `resources/js/Components/Builder/WidgetInspector.vue` — replaced `L / C / R` with left/center/right alignment SVG icons and added `Button Style` (`Primary` / `Secondary` / `Custom`) selector
  - `resources/js/Components/Builder/BuilderWidgetRenderer.vue` — rendered `theme-btn-primary` / `theme-btn-secondary` on frontend and live-matched Theme Buttons styles inside the editor
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:44 — Added 6 Preset Color Palettes to Theme General Page
- **Summary**: Added 6 curated one-click color palettes (`Indigo Modern`, `Emerald Editorial`, `Midnight Slate`, `Warm Terracotta`, `Oceanic Teal`, `Royal Plum`) with live swatch previews and active state indicator to the Colors tab on the Theme General page.
- **Files**:
  - `resources/js/Pages/Admin/Theme/General.vue` — added `colorPalettes`, `applyPalette()`, `isPaletteActive()`, and preset palette cards UI
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:37 — Header Builder Left-Sidebar Tabs Layout
- **Summary**: Reorganized Header Builder below the Live Header Preview into a left-sidebar tabbed layout (`Branding & Appearance`, `Navigation Menu`, `Action Button`, `Header Styles`) matching Settings and Footer Builder.
- **Files**:
  - `resources/js/Pages/Admin/Theme/HeaderBuilder.vue` — added `lg:grid-cols-12` left vertical tabs sidebar, tabbed form sections, and compact Style 3 menu bar color pickers
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:34 — Added Powered By & Version Footer to Admin Dashboard Layout
- **Summary**: Added a persistent bottom footer to the admin dashboard layout displaying "Powered by KitPress CMS" and "Version 0.1.0".
- **Files**:
  - `resources/js/Layouts/AdminLayout.vue` — converted `<main>` to `flex flex-col` and added the admin footer bar
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:32 — Embedded KitPress CMS Signature Across App
- **Summary**: Added KitPress CMS branding and signature across HTML meta generator tag, HTTP `X-Powered-By` response header, Composer/NPM package metadata, Admin sidebar footer, and toggleable public footer attribution.
- **Files**:
  - `app/Http/Middleware/KitPressSignature.php` — added middleware setting `X-Powered-By: KitPress CMS`
  - `bootstrap/app.php` — registered `KitPressSignature` in the `web` middleware group
  - `resources/views/app.blade.php` — added `<meta name="generator" content="KitPress CMS 0.1.0">`
  - `composer.json` — updated package name, description, keywords, homepage, and author metadata
  - `package.json` — added package name, version `0.1.0`, description, author, license, and homepage
  - `resources/js/Layouts/AdminLayout.vue` — added `KitPress CMS v0.1.0` to the admin sidebar footer
  - `app/Http/Controllers/Admin/ThemeController.php` — added `show_powered_by` default and validation to footer config
  - `resources/js/Pages/Admin/Theme/FooterBuilder.vue` — added `Show "Powered by KitPress" attribution` toggle in Copyright tab and live preview
  - `resources/js/Layouts/PublicLayout.vue` — rendered optional `Powered by KitPress` link in the public footer copyright bar
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 11:29 — Added Theme Metadata Header to app.css
- **Summary**: Added KitPress CMS theme/project metadata header block (Name, URI, Author, Description, Version, License, Tags) at the top of `resources/css/app.css`.
- **Files**:
  - `resources/css/app.css` — prepended KitPress CMS theme metadata comment block
- **Verification**: Not run (comment-only addition).

### 2026-11-14 11:14 — Footer Builder Left-Sidebar Tabs & Contact/Social Icons Section
- **Summary**: Reorganized Footer Builder below the Live Footer Preview into a left-sidebar tabbed layout (`Brand column`, `Links column`, `Contact & social icons`, `Copyright`, `Style`) matching the Settings page, and added full Contact & Social Icons support to the backend, live preview, and frontend footer.
- **Files**:
  - `resources/js/Components/SocialIcon.vue` — created reusable SVG social icon component (Facebook, X/Twitter, Instagram, LinkedIn, YouTube, GitHub)
  - `app/Http/Controllers/Admin/ThemeController.php` — added `contact_enabled`, `contact_title`, `contact_email`, `contact_phone`, `contact_address`, and `social_links` defaults & validation
  - `resources/js/Pages/Admin/Theme/FooterBuilder.vue` — added left-side vertical tabs sidebar below Live Footer Preview, Contact & Social Icons editor, compact `ThemeColorPicker`s in Style tab, and live preview rendering
  - `resources/js/Layouts/PublicLayout.vue` — rendered Contact & Social Icons column in the public footer
- **Verification**: Not run (Vite dev server active).

### 2026-11-14 05:27 — Added Project Change Log Skill, Rule, and Log File
- **Summary**: Created a workspace skill and always-on rule to ensure `CHANGES_LOG.md` is updated after every change, and initialized `CHANGES_LOG.md` with recent session changes.
- **Files**:
  - `.agents/skills/change-log/SKILL.md` — added `change-log` skill instructions and entry format
  - `.agents/rules/change-log.md` — added `always_on` workspace rule referencing `CHANGES_LOG.md`
  - `CHANGES_LOG.md` — initialized project change log

### 2026-11-14 05:24 — Compact Color Pickers UI/UX on Buttons Page
- **Summary**: Updated Theme Buttons page color pickers to use compact circular swatches and 2-column grids for gradient and text/border colors.
- **Files**:
  - `resources/js/Pages/Admin/Theme/Buttons.vue` — applied `compact` mode on `ThemeColorPicker` instances and 2-column grids

### 2026-11-14 05:20 — Separate Transparent Header Colors & Home-Only Transparent Header
- **Summary**: Restricted transparent header behavior to the Home page only, fixed first-section top padding on builder homepages so the hero background starts behind the transparent header, and added separate transparent logo/menu link color controls.
- **Files**:
  - `app/Http/Controllers/PublicController.php` — passed `isHome` Inertia prop from `home()` and `show()`
  - `app/Http/Controllers/Admin/ThemeController.php` — added `transparent_logo_text_color` and `transparent_nav_link_color` defaults and validation
  - `resources/js/Pages/Admin/Theme/HeaderBuilder.vue` — added `Transparent Header Colors (Home Page Only)` section and updated live preview
  - `resources/js/Layouts/PublicLayout.vue` — applied `isTransparentHeader` logic and separate transparent colors
  - `resources/js/Pages/Public/Show.vue` — added header height offset to the first builder section's `paddingTop` on transparent homepages

### 2026-11-14 05:10 — Header Builder Menu Link Colors & Branding UI/UX Cleanup
- **Summary**: Added Logo Text Color and Menu Link Normal/Hover/Active color pickers, and organized Branding & Appearance into compact grouped cards.
- **Files**:
  - `app/Http/Controllers/Admin/ThemeController.php` — added `logo_text_color`, `nav_link_color`, `nav_link_hover_color`, and `nav_link_active_color`
  - `resources/js/Components/ThemeColorPicker.vue` — added `compact` prop for circular swatch dots
  - `resources/js/Pages/Admin/Theme/HeaderBuilder.vue` — grouped Header/Logo and Menu Link color pickers into clean 3-column cards and updated live preview
  - `resources/js/Layouts/PublicLayout.vue` — applied logo color, CSS variables, and active link detection

### 2026-11-14 05:03 — Admin Sidebar Hover-Expand & Editor Top Gap Fix
- **Summary**: Fixed the top gap above the sticky toolbar in Post/Page Editor and added hover-to-expand overlay behavior for the collapsed admin sidebar.
- **Files**:
  - `resources/js/Pages/Admin/Posts/Editor.vue` — added `-mt-6` to top sticky action bar
  - `resources/js/Layouts/AdminLayout.vue` — changed `<main>` `overflow-x-hidden` to `overflow-x-clip` and added `isSidebarHovered` / `isSidebarCompact` hover-expand behavior

### 2026-11-14 04:39 — Settings Page Left Sidebar Tabs
- **Summary**: Split Settings page into `General` and `Site Optimization` sections using a left-side vertical tabs sidebar.
- **Files**:
  - `resources/js/Pages/Admin/Settings/Optimization.vue` — added `lg:grid-cols-12` left vertical tab sidebar and separated General vs Site Optimization sections
- **Verification**: `npm run build` passed (`716 modules transformed`).

### 2026-11-14 04:31 — Fixed Vite Dependency Scan Error
- **Summary**: Removed duplicate `defineProps` declaration in `FooterBuilder.vue` that broke `npm run dev` dependency scanning and `npm run build`.
- **Files**:
  - `resources/js/Pages/Admin/Theme/FooterBuilder.vue` — removed duplicate `const props = defineProps(...)` block
- **Verification**: `npm run build` and `npx vite --force` passed.
