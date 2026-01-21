# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Effective Grid is a lightweight, programmer-oriented WordPress plugin for displaying filterable grids of WordPress posts. It uses PSR-4 autoloading with the `EffectiveGrid\` namespace mapping to `src/`.

**Requirements:** PHP >= 8.3, WordPress

## Commands

```bash
# Install dependencies (generates autoloader)
composer install

# Regenerate optimized autoloader after adding classes
composer dump-autoload -o
```

No build step required - CSS is pre-compiled and JavaScript is minimal.

## Architecture

### Core Class Hierarchy

All core classes use a base-derived pattern:

- **Grid System:** `Grid.php` (abstract) → `Grids/PostGrid.php`
- **Element System:** `Element.php` (abstract) → `Elements/PostElement.php`
- **Filter System:** `Filter.php` (abstract) → `Filters/` implementations

### Key Classes

- `PostGrid` - Builds WP_Query with taxonomy filters, handles pagination
- `PostElement` - Wraps WP_Post with rendering logic for thumbnails/titles
- `Filters` - Collection manager, renders filter form with reset/update buttons
- Filter types: `TermsFilter` (taxonomies), `SearchFilter` (text), `DefinedOptionsListFilter` (custom post ID lists)

### Entry Point

`plugin.php` - Main plugin class `EffectiveGrid` that:
- Hooks into `wp_enqueue_scripts` for assets
- Registers shortcodes on the `wp` hook

### URL Parameter Convention

Filters use URL params: `?egrid_page=X&egrid_filter[taxonomy]=value&egrid_search=term`

### WordPress Hooks

Custom filter hooks use prefix `EFFECTIVE_GRID_*`:
- `EFFECTIVE_GRID_LABEL_FILTER`
- `EFFECTIVE_GRID_FILTER_LABEL_FILTER`

### Assets

- CSS: `assets/css/src/` (egrid.css, filters.css, elements.css, pagination.css)
- JS: `assets/js/egrid.js` (Select2 integration only)
- Uses flexbox for responsive grid layout

## Known Dependencies

`PostElement` currently has a hard dependency on the "Fly Image Resizer" plugin for image handling (marked for removal in todo.md).
