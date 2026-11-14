# KitPress CMS — Change Log

All project changes are recorded here (newest first).

## Change Log

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
