# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

### Changed

### Deprecated

### Removed

### Fixed

### Security

## [0.10.0] - 2026-05-03

### Added

- **Role management** — roles can now be created, edited, and deleted from the admin panel; slug is auto-generated from the name but can be overridden; the `superadmin` role and the last remaining superadmin role are protected from deletion
- **User invitation** — admins can invite users by email from the users index page; the role is selected at invite time; non-superadmin admins only see non-superadmin roles in the picker
- **User role update** — a user's role can be changed inline from the users table; users cannot change their own role; non-superadmin admins cannot assign superadmin roles
- **`permission_role` UUID migration** — four new migrations upgrade the `permission_role` table to use the UUID-based role primary key introduced in `patrikjak/auth` 2.0; run `php artisan migrate` after upgrading
- **Superadmin bypass in `hasPermission()`** — superadmin users now bypass the permission pivot check in `User::hasPermission()`, so nav item visibility and all direct permission checks respect the `is_superadmin` flag without requiring permissions to be seeded
- **Permission labels from lang files** — permission descriptions are now resolved from `lang/en/permissions.php` and `lang/sk/permissions.php` at sync time instead of being hardcoded inline

### Changed

- `patrikjak/auth` minimum version bumped to `^2.0`; roles are now database-driven with UUID primary keys and a `slug` field — the `RoleType` enum is gone; run `php artisan pjauth:sync-roles` after upgrading
- `PermissionsDefinition` default role references updated from `RoleType` enum cases to slug strings (`'superadmin'`, `'admin'`)
- `RolePolicy::before()` no longer bypasses the superadmin check for the `delete` action — role deletion is always evaluated through the full policy
- `UserPolicy::before()` no longer bypasses the superadmin check for the `edit` action — a user can never change their own role, even as superadmin
- `InstallCommand` now calls `pjauth:install` (renamed from `install:pjauth` in auth 2.0)
- `RoleRepository` contract updated to extend `Patrikjak\Auth\Repositories\Contracts\RoleRepository` (namespace moved from `Interfaces` to `Contracts` in auth 2.0)

## [0.9.0] - 2026-04-05

### Added

- **Editor i18n** — the EditorJS editor (toolbar labels, tool names, block tunes, UI strings, placeholder) is now fully translated; English and Slovak are included out of the box; locale is driven by the `<html lang="">` attribute
- **`ContentContextRegistry`** — new extensibility point for content image upload/fetch; consuming apps register custom content types via the `pjstarter.content_contexts` config key or programmatically via `ContentContextRegistry::register()`; each context defines a storage directory, model class, and an optional disk (defaults to the app's default filesystem disk)
- **`ContentContextDefinition`** value object — holds the directory, model class, and optional disk for a content context
- **`ContentContextNotFoundException`** (HTTP 404) and **`ContentAccessDeniedException`** (HTTP 403) — typed exceptions replace `abort()` calls in the content layer, with descriptive messages indicating which context key or model class caused the error
- **`content_images` feature flag** — image upload/fetch endpoints are now gated behind `pjstarter.features.content_images`; enable it alongside `articles` to activate the built-in content image routes

### Changed

- `ContentImageService::saveImage()` and `saveImageFromUrl()` now accept a `ContentContextDefinition` instead of a plain directory string — disk and directory resolution is handled entirely within the service

## [0.8.0] - 2026-04-04

### Added

- **Collapsible navigation** — the sidebar can now be collapsed to an icon-only rail via a toggle button; state persists across page loads via `localStorage`; collapsed state is applied before first paint to avoid a flash of the expanded nav
- **Navigation tooltips** — hovering a nav item in collapsed mode shows a tooltip with the item label
- **Navigation groups** — nav items are now organised into labelled groups (`NavigationGroup` DTO); the built-in items are split into Main, Content, and Management sections
- **Navigation item icons** — `NavigationItem` now accepts an `icon` SVG string; built-in items each have a distinct Heroicon; custom items without an icon fall back to a generic grid icon
- **Dashboard stat cards** — the dashboard now shows content counts (articles, categories, authors, static pages) as stat cards when the corresponding features are enabled; includes a welcome message that adapts when no stats are available
- **Table action icons** — all inline table actions (edit, delete, manage permissions) now consistently show icons across every table

### Changed

- Navigation items are now grouped; consuming apps that inject custom items via `pjstarter.navigation` config should use the `NavigationGroup` structure
- Page heading separator changed from ` - ` to `:` (e.g. `Úprava článku: My Article`) for clearer label/value distinction
- Index page action buttons now use "New [Entity]" label pattern (e.g. "New article", "New author") instead of "Create [Entity]" or "Create"
- Secondary navigation buttons (links to related sections, e.g. "Article categories" on the articles index) are now styled as outlined/bordered buttons to distinguish them from primary create actions
- Row number column removed from all tables

### Removed

- `x-pjstarter::layout.action` component removed; use `x-pjutils::button` directly in `<x-slot:actions>` instead

## [0.7.1] - 2026-03-11

### Fixed

- Article and author image operations now use the configured default filesystem disk instead of hardcoded `public` disk, enabling R2/S3 storage for article featured images, inline editor images, and author profile pictures
- Article and author model URL methods (`getFeaturedImagePath`, `getProfilePicturePath`) now resolve URLs via `Storage::url()` so they return correct CDN URLs when using cloud storage

## [0.7.0] - 2026-03-10

### Changed

- Decoupled frontend assets from `patrikjak/utils` source — starter's pre-built JS no longer bundles utils code; utils JS is loaded at runtime from its own pre-built assets via `window.pjutils` globals, so updating utils in a consumer app requires no starter rebuild

## [0.6.0] - 2026-03-04

### Added
- JSON filtering capabilities for table component
- Custom CSS classes support for action component

### Changed
- Updated dependency versions

## [0.5.0] - 2026-02-12

### Added
- Option to disable user menu and feature (#7)

## [0.4.0] - 2026-02-08

### Added
- Extensible DTOs (#9)

## [0.3.0] - 2025-06-02

### Added
- Articles management system with Editor.js integration (#5)
- Article categories and authors support
- Comprehensive test coverage for articles (#6)

## [0.2.0] - 2025-03-30

### Added
- Metadata management for SEO (#4)
- Metadatable interface for models
- Metadata Blade components

### Fixed
- Build assets configuration (#3)

## [0.1.0] - 2025-03-15

### Added
- Initial release
- Admin panel layout and starter screens (#1)
- User management with roles and permissions
- Static pages CRUD
- Profile management
- Dashboard
- Docker development environment
- PHPUnit test suite with snapshot testing
- PHPStan static analysis (Level 6)
- Slevomat coding standard integration
- Multi-language support (English, Slovak)

### Fixed
- Content body height issue (#2)

[Unreleased]: https://github.com/patrikjak/starter/compare/v0.7.1...HEAD
[0.7.1]: https://github.com/patrikjak/starter/compare/v0.7.0...v0.7.1
[0.7.0]: https://github.com/patrikjak/starter/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/patrikjak/starter/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/patrikjak/starter/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/patrikjak/starter/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/patrikjak/starter/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/patrikjak/starter/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/patrikjak/starter/releases/tag/v0.1.0
