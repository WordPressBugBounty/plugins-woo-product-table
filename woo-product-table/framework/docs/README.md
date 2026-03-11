# CA Framework Documentation

A lightweight, reusable WordPress admin framework for displaying offers, popups, required plugins, and recommended plugins. Designed to be included (not autoloaded) in any WordPress plugin.

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Components](#components)
  - [Offers](offers.md)
  - [Popups](popups.md)
  - [Required Plugins](required-plugins.md)
  - [Recommended Plugins](recommended-plugins.md)
- [API Reference](api-reference.md)
- [Suggestions](suggestions.md)

---

## Installation

1. Copy the `framework/` folder into your plugin directory.
2. Include the framework in your main plugin file:

```php
// In your main plugin file
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';
```

3. Initialize the framework:

```php
$framework = CA_Framework::init( 'your-plugin-slug', __FILE__ );
```

> **Note:** No autoloader needed. The framework loads all required files via `require_once`.

---

## Quick Start

### Show a Date-Based Offer

```php
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';

$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$framework->create_offer( array(
    'title'          => '🎉 Summer Sale!',
    'description'    => 'Get 50% off on the Pro version. Limited time offer!',
    'highlight_text' => '50% OFF',
    'badge_text'     => 'LIMITED OFFER',
    'start_date'     => '2025-06-01',
    'end_date'       => '2025-06-30',
    'template'       => 'starter',
    'buttons'        => array(
        array(
            'text'  => 'Get Pro Now',
            'url'   => 'https://example.com/pricing/',
            'class' => 'ca-fw-btn-primary',
        ),
        array(
            'text'  => 'Learn More',
            'url'   => 'https://example.com/features/',
            'class' => 'ca-fw-btn-secondary',
        ),
    ),
) )->show();
```

### Show a Popup on Plugin Pages

```php
$framework->create_popup( array(
    'title'        => 'Upgrade to Pro!',
    'description'  => 'Unlock all premium features and priority support.',
    'badge_text'   => 'SPECIAL OFFER',
    'dismiss_type' => 'temporary',
    'reshow_after' => 7, // Show again after 7 days
    'pages'        => array( 'toplevel_page_my-plugin' ),
    'buttons'      => array(
        array(
            'text'  => 'Upgrade Now',
            'url'   => 'https://example.com/pricing/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

### Register Required Plugins

```php
$framework->required_plugins( array(
    array(
        'name'        => 'WooCommerce',
        'slug'        => 'woocommerce',
        'path'        => 'woocommerce/woocommerce.php',
        'description' => 'Required for e-commerce functionality.',
        'icon'        => 'https://ps.w.org/woocommerce/assets/icon-256x256.gif',
    ),
) )->show();
```

### Register Recommended Plugins

```php
$framework->recommended_plugins( array(
    array(
        'name'        => 'UltraAddons Elementor',
        'slug'        => 'developer-developer-developer',
        'path'        => 'developer-developer-developer/developer-developer-developer.php',
        'description' => 'Starter Elementor Addons.',
        'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.gif',
    ),
) )->show();
```

---

## File Structure

```
framework/
├── framework.php                          # Main loader
├── assets/
│   ├── css/framework.css                  # All framework styles
│   └── js/framework.js                    # All framework JavaScript
├── classes/
│   ├── class-dismiss-handler.php          # Dismiss/AJAX handler
│   ├── class-offer.php                    # Offer management
│   ├── class-popup.php                    # Popup management
│   ├── class-required-plugin.php          # Required plugins
│   └── class-recommended-plugin.php       # Recommended plugins
├── templates/
│   ├── offer-starter.php                  # Gradient banner template
│   ├── offer-developer.php                # Minimal left-border template
│   ├── offer-flash.php                    # Bold flash sale template
│   └── popup.php                          # Modal popup template
└── docs/
    ├── README.md                          # This file
    ├── offers.md                          # Offers documentation
    ├── popups.md                          # Popups documentation
    ├── required-plugins.md                # Required plugins documentation
    ├── recommended-plugins.md             # Recommended plugins documentation
    ├── api-reference.md                   # Full API reference
    └── suggestions.md                     # Future feature suggestions
```

---

## Requirements

- WordPress 5.0+
- PHP 7.0+
- jQuery (included in WordPress)

---

## License

GPLv2 or later
