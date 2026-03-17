# Visily Convert WP

A complete WordPress theme and plugin project for **Visily Convert** — a conversion optimization platform built on WordPress.

---

## Project Structure

```
visily-convert-wp/
├── wp-content/
│   ├── themes/
│   │   └── visily-convert-theme/
│   │       ├── style.css                    # Theme stylesheet & metadata
│   │       ├── index.php                    # Main template file
│   │       ├── functions.php                # Theme setup, hooks, helpers
│   │       ├── header.php                   # Header template
│   │       ├── footer.php                   # Footer template
│   │       └── template-parts/
│   │           └── content.php              # Post content template part
│   └── plugins/
│       └── visily-convert-plugin/
│           └── visily-convert-plugin.php    # Main plugin file
└── README.md
```

---

## Theme: Visily Convert Theme

### Features

- **WordPress Standards Compliant** — follows official WordPress coding standards and best practices
- **Responsive Design** — fully responsive layout using CSS Grid and Flexbox
- **Accessibility Ready** — skip links, ARIA labels, screen-reader utilities, and keyboard navigation support
- **Custom Logo Support** — upload your logo from the WordPress Customizer
- **Three Navigation Menus** — Primary, Footer, and Social Links menus
- **Three Footer Widget Areas** — flexible footer layout with up to three widget columns
- **Post Thumbnail Support** — featured images with custom sizes (`visily-featured` at 800×450, `visily-thumbnail` at 400×225)
- **HTML5 Semantic Markup** — proper use of `<header>`, `<footer>`, `<main>`, `<article>`, `<nav>` elements
- **Translation Ready** — full i18n/l10n support with text domain `visily-convert-theme`
- **Block Editor (Gutenberg) Support** — wide/full alignment, block styles, responsive embeds, and editor styles
- **Comment Support** — threaded comments with reply script loading
- **Custom Background** — supports custom background color and image via Customizer
- **Pingback Support** — auto-discovery header for single posts and pages

### Theme Files

| File | Description |
|------|-------------|
| `style.css` | Theme metadata, reset styles, typography, layout, navigation, footer, post, sidebar, pagination, forms, comments, and responsive breakpoints |
| `index.php` | Main template. Loops through posts using `template-parts/content.php`, shows post navigation |
| `functions.php` | Theme setup (`after_setup_theme`), content width, widget registration, script/style enqueueing, and template tag helpers |
| `header.php` | `<!doctype html>` through the opening of site content — includes skip link, site branding, and primary navigation |
| `footer.php` | Footer widget areas, footer navigation menu, copyright/powered-by bar, closes `#page` div, calls `wp_footer()` |
| `template-parts/content.php` | Single post/archive article: entry header, meta (date, author, comments, edit link), featured image, content or excerpt, read-more link, entry footer (categories, tags) |

### Installation

1. Copy the `wp-content/themes/visily-convert-theme/` folder into your WordPress installation's `wp-content/themes/` directory.
2. In the WordPress admin, navigate to **Appearance → Themes**.
3. Activate **Visily Convert Theme**.

### Theme Customization

#### Menus
1. Go to **Appearance → Menus**.
2. Create menus and assign them to the **Primary Menu**, **Footer Menu**, or **Social Links Menu** locations.

#### Widgets
1. Go to **Appearance → Widgets**.
2. Add widgets to **Sidebar**, **Footer Widget Area 1**, **Footer Widget Area 2**, or **Footer Widget Area 3**.

#### Custom Logo
1. Go to **Appearance → Customize → Site Identity**.
2. Upload a logo image (recommended: 300×100 px).

#### Custom Background
1. Go to **Appearance → Customize → Colors** or **Background Image**.
2. Set a background color or image.

---

## Plugin: Visily Convert Plugin

### Features

- **Plugin Lifecycle Hooks** — proper activation and deactivation callbacks that set default options and flush rewrite rules
- **Text Domain Loading** — translation-ready via `visily-convert-plugin` text domain loaded on `plugins_loaded`
- **Frontend Asset Enqueueing** — enqueues `assets/css/frontend.css` and `assets/js/frontend.js` with localized data (`ajaxUrl`, `restUrl`, `nonce`, `siteUrl`, `version`)
- **Admin Asset Enqueueing** — enqueues admin CSS and JS only on plugin admin pages
- **Admin Menu** — top-level **Visily Convert** menu with **Dashboard**, **Settings**, and **Reports** subpages
- **Settings Page** — form-based settings page with nonce verification, sanitization, and `update_option` persistence
- **REST API Endpoints** — four endpoints under the `visily-convert/v1` namespace (see below)
- **Permission Callbacks** — separate callbacks for public, authenticated, and admin-only REST routes
- **Constants** — `VISILY_CONVERT_PLUGIN_VERSION`, `VISILY_CONVERT_PLUGIN_FILE`, `VISILY_CONVERT_PLUGIN_DIR`, `VISILY_CONVERT_PLUGIN_URL`, `VISILY_CONVERT_PLUGIN_BASENAME`

### REST API Endpoints

| Method | Route | Auth | Description |
|--------|-------|------|-------------|
| `GET` | `/wp-json/visily-convert/v1/status` | Public | Returns plugin status, version, and site URL |
| `GET` | `/wp-json/visily-convert/v1/conversions` | Logged-in user | Retrieves paginated conversion events (`per_page`, `page` params) |
| `POST` | `/wp-json/visily-convert/v1/conversions` | Logged-in user | Records a conversion event (`event` required, `page_id` and `meta` optional) |
| `GET` | `/wp-json/visily-convert/v1/settings` | Administrator | Returns current plugin settings |

### Plugin Installation

1. Copy the `wp-content/plugins/visily-convert-plugin/` folder into your WordPress installation's `wp-content/plugins/` directory.
2. In the WordPress admin, navigate to **Plugins → Installed Plugins**.
3. Activate **Visily Convert Plugin**.

### Plugin Usage

#### Admin Dashboard
After activation, a **Visily Convert** menu item appears in the WordPress admin sidebar. The dashboard provides:
- Plugin version and REST API namespace information
- Quick links to Settings, Reports, and the REST API endpoint

#### Settings
Navigate to **Visily Convert → Settings** to configure:
- **Enable Tracking** — toggle frontend conversion tracking on or off
- **API Endpoint** — external Visily API URL for integration

#### REST API Usage

**Check plugin status (public):**
```http
GET /wp-json/visily-convert/v1/status
```

**Record a conversion event (authenticated):**
```http
POST /wp-json/visily-convert/v1/conversions
Authorization: Bearer <token>
Content-Type: application/json

{
  "event": "signup",
  "page_id": 42,
  "meta": { "source": "landing-page" }
}
```

---

## Requirements

| Requirement | Minimum Version |
|-------------|----------------|
| WordPress | 5.8 |
| PHP | 7.4 |

---

## License

This project is licensed under the [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).