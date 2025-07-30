Triplewood Cache Optimizer
==========================

Magento 2 typically sends the following Cache-Control header on HTML responses (excluding static assets):

    Cache-Control: max-age=0, must-revalidate, no-cache, no-store

This instructs the browser not to cache any HTML pages. As a result, the browser re-requests the full-page HTML
on every navigation, even for back and forward actions, reducing perceived performance.

> **Caution**: This module is recommended only if you run Hyvä on your Magento instance and fully understand the
> associated security implications. Additionally, this is **NOT** thoroughly tested yet. It may not cover all
> security related aspects of your project!

To safely use this module:

- Do not show any user-specific data on the initial page load for non-logged-in users.
- Load sensitive data dynamically via AJAX after the page has rendered.
- Check your varnish-config if you are overriding `resp.http.Cache-Control`. If so, this extension will not do anything.
 
Why Magento Does This
---------------------

- Magento caches CSS, fonts, JavaScript, and images in the browser, but not HTML pages.
- This is a safety measure: HTML responses may contain user-specific data (e.g., cart totals, customer name, wishlist count, or prices).
- If browser caching is enabled and HTML is reused improperly, it may display stale or incorrect data to logged-in users.
- Magento therefore relies on server-side caching (Varnish or built-in FPC), not browser caching.

The consequences
----------------

While cautious, this strategy comes at a cost:

- Even guest users receive uncached HTML, despite often seeing the same content.
- This prevents CDNs like Cloudflare from applying their own HTML caching optimizations.
- Browser features like back/forward caching (bfcache) are disabled, making Magento feel slower than necessary.

What This Module Does
---------------------

It enables browser caching for **non-dynamic guest pages**, such as:

- CMS pages
- Product detail pages
- Category pages

The following routes are explicitly excluded from caching:

- Checkout
- Customer account pages

## Installation details

In your Magento `composer.json` add the following lines under the "repositories" section:

    "repositories": {
        // ..
        "triplewood-de/module-cache-optimizer": {
            "type": "vcs",
            "url": "https://github.com/triplewood-de/magento2-cache-optimizer"
        }
    },

You can install this module via composer

    composer require triplewood-de/module-cache-optimizer
    bin/magento module:enable Triplewood_CacheOptimizer
    bin/magento setup:upgrade
