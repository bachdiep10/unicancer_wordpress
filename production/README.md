# Production WordPress customizations

This directory tracks custom code deployed to `unicancercenter.com`.

## 2026-09-03

- Added reliable local image fallbacks for the English, Indonesian, and
  Simplified Chinese homepage carousels. The translated pages previously
  referenced protected OSS assets that returned HTTP 403.
- Deployment target: `wp-content/themes/unicancer/functions.php`.

Site content, uploads, credentials, generated caches, and WordPress core are
not stored here.
