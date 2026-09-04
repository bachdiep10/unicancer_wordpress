# Production WordPress customizations

This directory tracks custom code deployed to `unicancercenter.com`.

## 2026-09-03

- Added reliable local image fallbacks for the English, Indonesian, and
  Simplified Chinese homepage carousels. The translated pages previously
  referenced protected OSS assets that returned HTTP 403.
- Deployment target: `wp-content/themes/unicancer/functions.php`.

Site content, uploads, credentials, generated caches, and WordPress core are
not stored here.

## 2026-09-04

- Migrated every OSS and theme-mirror image reference found in WordPress
  content to the native Media Library (887 content rows updated across the
  migration passes; zero OSS references remain).
- Added a safe language-preserving fallback for legacy numeric news routes,
  such as `/en/news/21/`, so translated news cards open the article instead of
  redirecting to the archive.
- Added the repeatable migration/audit utility under `production/tools/`.
