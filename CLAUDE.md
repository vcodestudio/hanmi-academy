# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development (webpack watch + Playwright browser auto-reload)
npm run dev

# Production build
npm run build

# Deploy to test server (build → git commit → push → ssh pull)
npm run deploy:test
```

`npm run dev` requires a `.env` file with `DEV_URL` (local dev URL) and `SITE_URL` variables. It launches a Playwright Chromium browser that auto-reloads on PHP/JS/LESS/CSS changes.

## Architecture Overview

### Build System
- **Webpack 5** bundles `src/js/index.js` → `src/js/dist/app.js` + `src/js/dist/app.css`
- **LESS** is the primary stylesheet language (imported into JS entry point)
- **Tailwind CSS v3** with Preflight disabled; custom breakpoint `m: { max: "765px" }` for mobile, custom color `ascent: #5fb800`
- **Vue 2.7** and **jQuery** are available globally

### Template Hierarchy
WordPress template files are split across three directories:
- `pages/` — custom page templates (50+ files, named `page-*.php`)
- `singles/` — custom single post templates (`single-*.php`)
- `archives/` — archive templates

### Component Library (`src/hanmi-components/`)
The core reusable system. `functions.php` includes `src/hanmi-components/php/precomp.php` as its first action, which bootstraps the entire component layer:
- `components/` — 49 PHP snippet files (forms, filters, gallery items, pagination, etc.) rendered via a precompiler
- `templates/` — complex full-page partials (account management, portfolio, login)
- `js/src/` — shared JS: `header.js` (nav init), `common-filter.js` (filtering logic)
- `css/` — component-level styles
- `imgs/` — SVG icon system organized under `icons/{chevron,arrow,chevron_s,chevron_w}/`

### PHP Source (`src/php/`)
Heavy logic files kept separate from templates:
- `acf_fields.php` (87KB) — all ACF field group definitions
- `ajax.php` (72KB) — all AJAX handlers (nonces required)
- `post_types.php` / `taxonomies.php` — CPT and taxonomy registration
- `refund_calculator.php`, `bulk.php`, `join_check.php`

### Payment (`src/mainpay/`)
Mainpay PG integration: `config.php`, `call_api.php`, `utils.php`. Documented in `docs/mainpay-api-integration.md`.

### WooCommerce & Ultimate Member
Overrides live in `woocommerce/` and `ultimate-member/` respectively, following standard WordPress override conventions.

### Admin
`admin/program-applicants.php` — standalone admin panel for program applicant management.

## Key Conventions

- **PHP**: WordPress coding standards, PHP 8.4, `declare(strict_types=1)` in new files, always escape output (`esc_html()`, `esc_attr()`), nonces on all forms and AJAX
- **ACF**: Use `get_field()` for all custom fields — ACF plugin is required
- **Naming**: `snake_case` PHP functions/vars, `kebab-case` CSS classes and filenames
- **Multisite**: This theme runs in a WordPress Multisite environment — be aware of network vs. site-level settings
- **SSH keys** live in `keys/` (gitignored); deploy target is `bitnami@3.37.141.188`, theme path `/opt/bitnami/wordpress/wp-content/themes/hanmi-academy`
