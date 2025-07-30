Triplwood Cache Optimizer
=========================

Magento 2 sends the following cache-control header on HTML responses (not static assets):

    Cache-Control: max-age=0, must-revalidate, no-cache, no-store

This essentially instructs the browser not to cache any HTML pages at all. This means the browser will always
re-request full page HTML, even on back/forward navigation.

**Why Magento Does This**

- Magento HTML often contains user-specific content (cart totals, customer name, wishlist count). Caching HTML in the browser risks showing stale data for logged-in users.
- Magento relies on server-side caching (Varnish / built-in FPC), not browser cache, for performance.
  -- Guest HTML pages are cached by FPC/Varnish Browsers should always revalidate, and FPC handles speed
- Disabling browser caching helps prevent sensitive data (e.g., customer account pages, cart contents) from being stored in browser cache, especially in shared/public machines.

**The consequences**

Magento errs on the side of caution. Even for guests, where caching HTML might be fine, it globally disables
caching to avoid complexity and edge cases. By disabling the browser cache, services like Cloudflare cannot apply their
HTML caching techniques - making Magento run even slower.

This module enables browser-caching for non-dynamic guest-pages:

- CMS pages
- product pages
- category pages


