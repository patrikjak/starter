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
