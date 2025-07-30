Triplewood Cache Optimizer
==========================

Magento 2 sends the following cache-control header on HTML responses (not static assets):

    Cache-Control: max-age=0, must-revalidate, no-cache, no-store

This essentially instructs the browser not to cache any HTML pages at all. This means the browser will always
re-request full page HTML, even on back/forward navigation. 

This extension is only recommended if you run hyvä on your Magento instance and understand possible security risks.
When using this extension you must not show any user-specific data on initial page load for non-logged-in users. 
You can, however, load specific data via ajax after initial page load. 

**Why Magento Does This**

- Magento only puts CSS, Fonts, JS and image files into the browser cache. HTML-responses are not cached.
- Magento HTML may contain user-specific content (cart totals, customer name, wishlist count, prices). Caching HTML in the browser risks showing stale data for logged-in users if a developer screws up implementation.
- Magento relies on server-side caching (Varnish / built-in FPC), not browser cache, for performance.

**The consequences**

Magento errs on the side of caution. Even for guests, where caching HTML might be fine, it globally disables
caching to avoid complexity and edge cases. By disabling the browser cache, services like Cloudflare cannot apply their
HTML caching techniques, making Magento run slower than necessary. Also back/forward caching techniques won't work
and Magento feels slower than need be.

This module enables browser-caching for non-dynamic guest-pages:

- CMS pages
- product pages
- category pages

The following pages are explicitly excluded:

- checkout pages
- customer pages

## Installation details

You can install this module via composer

    composer install triplewood-de/module-cache-optimizer
    bin/magento module:enable Triplewood_CacheOptimizer
    bin/magento setup:upgrade
