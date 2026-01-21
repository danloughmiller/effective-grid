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

All core classes use abstract base classes with typed properties (PHP 8.3):

- **Grid System:** `Grid` (abstract) → `Grids/PostGrid`
- **Element System:** `Element` (abstract) → `Elements/PostElement`
- **Filter System:** `Filter` (abstract) → `Filters/DropdownFilter` (abstract) → `TermsFilter`, `DefinedOptionsListFilter`
- **Filter System:** `Filter` (abstract) → `Filters/SearchFilter`
- **Support Classes:** `QueryBuilder`, `Constants`, `Filters` (container)

### Base Class Responsibilities

**Element** - Minimal base for grid items:
- `$id`, `getId()`, `getClasses()` - identity and CSS classes
- `abstract render(): string` - subclasses own all rendering

**Grid** - Container with pagination:
- `$id`, `$filters`, pagination settings (`$paginationWindow` controls page link range)
- `getElements(): array` and `getElementCount(): int` - abstract, implemented by subclasses
- Renders filter form, element list, and pagination

**QueryBuilder** - Encapsulates WP_Query construction:
- Fluent interface: `setPostType()`, `setPostsPerPage()`, `setPage()`, `setOrderBy()`, `mergeArgs()`
- `applyFilter(Filter $filter)` - delegates query modification to filters
- `build()` - returns final query args array with tax_query merged

**Constants** - Centralized plugin constants:
- `FILTER_PREFIX`, `DEV_MODE` - configuration
- `HOOK_*` - WordPress filter hook names
- `PARAM_*` - URL parameter names

**Filter** - Base for all filters:
- `$id`, `$title`, `$placeholder` (protected with getters)
- `abstract renderElement(): string` - the form input
- `abstract constructQuery(array &$args, array &$tax_query): void` - modifies WP_Query
- `getUrlParams(): array` - returns params to preserve filter state in pagination URLs

### Key Patterns

**Filters own their URL serialization:** Each filter implements `getUrlParams()` to return its state as URL parameters. `PostGrid::getPaginationLink()` collects these via `http_build_query()`.

**Properties are protected with getters:** Core properties like `$id` are protected. Use `getId()`, `getPlaceholder()`, `getFilters()` etc.

**No `$additional` parameter on `getClasses()`:** Subclasses override and call `array_merge(parent::getClasses(), [...])`.

### Entry Point

`plugin.php` - Main plugin class `EffectiveGrid` that:
- Hooks into `wp_enqueue_scripts` for assets
- Registers shortcodes on the `wp` hook

### URL Parameter Convention

Filters use URL params defined in `Constants`: `?egrid_page=X&egrid_filter[taxonomy]=value&egrid_search=term`

### WordPress Hooks

Custom filter hooks defined in `Constants::HOOK_*`:
- `Constants::HOOK_LABEL_FILTER` (`EFFECTIVE_GRID_LABEL_FILTER`)
- `Constants::HOOK_FILTER_LABEL_FILTER` (`EFFECTIVE_GRID_FILTER_LABEL_FILTER`)

### Assets

- CSS: `assets/css/src/` (egrid.css, filters.css, elements.css, pagination.css)
- JS: `assets/js/egrid.js` (Select2 integration only)
- Uses flexbox for responsive grid layout
