# Offers Documentation

The Offer system allows you to display date-based promotional banners in the WordPress admin area with modern designs, multiple templates, custom buttons, countdown timer, and dismiss functionality.

---

## Table of Contents

- [Basic Usage](#basic-usage)
- [Configuration Options](#configuration-options)
- [Templates](#templates)
- [Button Configuration](#button-configuration)
- [Countdown Timer](#countdown-timer)
- [Date-Based Display](#date-based-display)
- [Dismiss Options](#dismiss-options)
- [Reshow Unit (Days / Hours)](#reshow-unit)
- [Page Restriction](#page-restriction)
- [Show on Hook (No Dismiss)](#show-on-hook)
- [Complete Examples](#complete-examples)

---

## Basic Usage

```php
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$framework->create_offer( array(
    'title'       => 'Special Offer!',
    'description' => 'Get premium features at a discounted price.',
    'template'    => 'starter',
    'buttons'     => array(
        array(
            'text' => 'Get It Now',
            'url'  => 'https://example.com/pricing/',
        ),
    ),
) )->show();
```

---

## Configuration Options

| Parameter        | Type     | Default          | Description                                           |
|------------------|----------|------------------|-------------------------------------------------------|
| `id`             | string   | auto-generated   | Unique identifier for the offer                       |
| `title`          | string   | `''`             | Offer title text                                      |
| `description`    | string   | `''`             | Offer description (supports HTML via `wp_kses_post`)  |
| `start_date`     | string   | `''`             | Start date (e.g., `'2025-01-01'`)                     |
| `end_date`       | string   | `''`             | End date (e.g., `'2025-12-31'`)                       |
| `template`       | string   | `'starter'`      | Template name: `starter`, `developer`, `flash`        |
| `buttons`        | array    | `array()`        | Array of button configurations                        |
| `badge_text`     | string   | `''`             | Badge/label text (e.g., `'LIMITED OFFER'`)            |
| `highlight_text` | string   | `''`             | Large highlight text (e.g., `'50% OFF'`)              |
| `image_url`      | string   | `''`             | URL to an image/logo                                  |
| `show_countdown` | bool     | `false`          | Show live countdown timer (requires `end_date`)       |
| `dismiss_type`   | string   | `'permanent'`    | `'permanent'` or `'temporary'`                        |
| `reshow_after`   | int      | `0`              | Time after which to re-show (for `temporary` dismiss) |
| `reshow_unit`    | string   | `'days'`         | Unit for `reshow_after`: `'days'` or `'hours', 'seconds', 'minutes'`        |
| `hook`           | string   | `'admin_notices'` | WordPress hook to display the offer                  |
| `priority`       | int      | `10`             | Hook priority                                         |
| `pages`          | array    | `array()`        | Restrict to specific admin screen IDs                 |
| `capability`     | string   | `'manage_options'`| Required user capability                             |
| `randomize`      | int      | `100`            | Percentage chance (0-100) to show the offer           |

---

## Templates

### 1. Starter Template (`'starter'`)

A vibrant gradient banner with purple-to-violet colors. Best for eye-catching promotional offers.

```php
$framework->create_offer( array(
    'title'          => '🎉 Black Friday Sale!',
    'description'    => 'The biggest sale of the year. Don\'t miss out!',
    'highlight_text' => '70% OFF',
    'badge_text'     => 'BLACK FRIDAY',
    'template'       => 'starter',
    'buttons'        => array(
        array(
            'text'  => 'Shop Now',
            'url'   => 'https://example.com/sale/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

### 2. Developer Template (`'developer'`)

A minimal, clean template with a left accent border. Best for informational or update notices.

```php
$framework->create_offer( array(
    'title'       => 'New Version Available',
    'description' => 'Version 2.0 is here with exciting new features.',
    'badge_text'  => 'UPDATE',
    'template'    => 'developer',
    'buttons'     => array(
        array(
            'text'  => 'View Changelog',
            'url'   => 'https://example.com/changelog/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

### 3. Flash Template (`'flash'`)

A bold, dark-themed template with animated elements. Best for urgent, time-limited flash sales.

```php
$framework->create_offer( array(
    'title'          => '⚡ Flash Sale!',
    'description'    => 'Only 24 hours left! Grab the deal now.',
    'highlight_text' => 'UP TO 80% OFF',
    'badge_text'     => 'FLASH SALE',
    'template'       => 'flash',
    'start_date'     => '2025-03-01',
    'end_date'       => '2025-03-02',
    'buttons'        => array(
        array(
            'text'  => '🔥 Grab the Deal',
            'url'   => 'https://example.com/flash-sale/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

---

## Button Configuration

Each button in the `buttons` array accepts:

| Parameter | Type   | Default              | Description                |
|-----------|--------|----------------------|----------------------------|
| `text`    | string | `''`                 | Button text                |
| `url`     | string | `'#'`                | Button URL                 |
| `class`   | string | `'ca-fw-btn-primary'`| CSS class for styling      |
| `target`  | string | `'_blank'`           | Link target attribute      |
| `icon`    | string | `''`                 | Dashicons class (optional) |

### Available Button Styles

```php
// Primary button (filled, purple)
array( 'text' => 'Primary', 'url' => '#', 'class' => 'ca-fw-btn-primary' )

// Success button (filled, green)
array( 'text' => 'Success', 'url' => '#', 'class' => 'ca-fw-btn-success' )

// Outline button (bordered)
array( 'text' => 'Outline', 'url' => '#', 'class' => 'ca-fw-btn-outline' )

// Secondary button (light gray)
array( 'text' => 'Secondary', 'url' => '#', 'class' => 'ca-fw-btn-secondary' )

// With dashicon
array( 'text' => 'Download', 'url' => '#', 'class' => 'ca-fw-btn-primary', 'icon' => 'dashicons-download' )
```

### Multiple Buttons Example

```php
'buttons' => array(
    array(
        'text'  => 'Upgrade to Pro',
        'url'   => 'https://example.com/pro/',
        'class' => 'ca-fw-btn-primary',
        'icon'  => 'dashicons-star-filled',
    ),
    array(
        'text'   => 'View Features',
        'url'    => 'https://example.com/features/',
        'class'  => 'ca-fw-btn-secondary',
        'target' => '_blank',
    ),
    array(
        'text'  => 'Documentation',
        'url'   => 'https://example.com/docs/',
        'class' => 'ca-fw-btn-outline',
    ),
),
```

---

## Date-Based Display

Control when an offer is visible using `start_date` and `end_date`:

```php
// Show only during December 2025
$framework->create_offer( array(
    'title'      => 'Holiday Special!',
    'start_date' => '2025-12-01',
    'end_date'   => '2025-12-31',
    'template'   => 'flash',
    // ...
) )->show();

// Show from a specific date (no end date)
$framework->create_offer( array(
    'title'      => 'New Feature Available',
    'start_date' => '2025-06-15',
    'template'   => 'developer',
    // ...
) )->show();

// Show until a specific date (no start date)
$framework->create_offer( array(
    'title'    => 'Early Bird Offer',
    'end_date' => '2025-03-31',
    'template' => 'starter',
    // ...
) )->show();
```

---

## Dismiss Options

### Permanent Dismiss

Once dismissed, the offer never shows again for that user:

```php
$framework->create_offer( array(
    'dismiss_type' => 'permanent',
    // ...
) )->show();
```

### Temporary Dismiss

After dismissing, the offer re-appears after a specified number of days:

```php
$framework->create_offer( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 7, // Re-show after 7 days
    // ...
) )->show();
```

---

## Reshow Unit

By default, `reshow_after` counts in **days**. You can change it to **hours** using the `reshow_unit` parameter:

### Reshow After Hours

```php
$framework->create_offer( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 6,       // Re-show after 6 hours
    'reshow_unit'  => 'hours', // Use hours instead of days, can be 'seconds', 'minutes', 'hours', or 'days'
    // ...
) )->show();
```

### Reshow After Days (Default)

```php
$framework->create_offer( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 3,      // Re-show after 3 days
    'reshow_unit'  => 'days', // Default, can be omitted
    // ...
) )->show();
```

---

## Countdown Timer

Show a live countdown timer that counts down to the `end_date`. The timer automatically updates every second.

### Basic Countdown

```php
$framework->create_offer( array(
    'title'          => '⚡ Flash Sale Ending Soon!',
    'end_date'       => '2025-06-15 23:59:59',
    'show_countdown' => true,
    'template'       => 'flash',
    'buttons'        => array(
        array( 'text' => 'Grab the Deal', 'url' => 'https://example.com/sale/' ),
    ),
) )->show();
```

### Countdown with Starter Template

```php
$framework->create_offer( array(
    'title'          => '🎉 Summer Sale!',
    'description'    => 'Hurry, this deal won\'t last long!',
    'highlight_text' => '50% OFF',
    'badge_text'     => 'LIMITED TIME',
    'end_date'       => '2025-07-01',
    'show_countdown' => true,
    'template'       => 'starter',
    'buttons'        => array(
        array( 'text' => 'Buy Now', 'url' => 'https://example.com/' ),
    ),
) )->show();
```

> **Note:** The countdown requires `end_date` to be set. If `end_date` is empty, the countdown will not render even if `show_countdown` is `true`.

---

## Page Restriction

Show an offer only on specific admin pages:

```php
// Only on the plugin's main settings page
$framework->create_offer( array(
    'pages' => array( 'toplevel_page_my-plugin' ),
    // ...
) )->show();

// On multiple pages
$framework->create_offer( array(
    'pages' => array(
        'toplevel_page_my-plugin',
        'my-plugin_page_settings',
        'plugins',
    ),
    // ...
) )->show();
```

> **Tip:** Use `get_current_screen()->id` to find the screen ID of any admin page.

---

## Show on Hook

Use `show_on_hook()` to render the offer on a specific WordPress action hook **without a dismiss button**. This is useful when you want the offer to always appear at a specific location (e.g., inside a settings page).

### Basic Usage

```php
$framework->create_offer( array(
    'title'       => 'Upgrade to Pro!',
    'description' => 'Get advanced features with the Pro version.',
    'template'    => 'developer',
    'buttons'     => array(
        array( 'text' => 'Upgrade Now', 'url' => 'https://example.com/pro/' ),
    ),
) )->show_on_hook( 'my_plugin_settings_top' );
```

### Chaining Multiple Hooks

You can chain `show_on_hook()` to display on multiple hooks:

```php
$framework->create_offer( array(
    'title'       => 'Pro Features Available',
    'description' => 'Unlock the full potential of your plugin.',
    'template'    => 'starter',
    'buttons'     => array(
        array( 'text' => 'Learn More', 'url' => 'https://example.com/features/' ),
    ),
) )->show_on_hook( 'my_plugin_dashboard_top' )
   ->show_on_hook( 'my_plugin_settings_sidebar' );
```

### Combining show() and show_on_hook()

You can use both `show()` and `show_on_hook()` on the same offer. The `show()` version will have a dismiss button, while `show_on_hook()` will not:

```php
$offer = $framework->create_offer( array(
    'title'        => 'Holiday Sale — 40% OFF!',
    'template'     => 'flash',
    'start_date'   => '2025-12-20',
    'end_date'     => '2025-12-31',
    'dismiss_type' => 'temporary',
    'reshow_after' => 2,
    'buttons'      => array(
        array( 'text' => 'Get Discount', 'url' => 'https://example.com/sale/' ),
    ),
) );

// Dismissible version in admin notices
$offer->show();

// Non-dismissible version on specific hooks
$offer->show_on_hook( 'my_plugin_before_content' );
```

### With Hook Priority

```php
$framework->create_offer( array(
    'title'    => 'Pro Tip',
    'template' => 'developer',
    // ...
) )->show_on_hook( 'my_plugin_after_settings', 20 ); // priority 20
```

> **Important:** Offers rendered via `show_on_hook()` do NOT have dismiss functionality. They will always display as long as the date range and page conditions are met. Dismiss checks are also skipped.

---

## Complete Examples

### Example 1: Eid Sale with Image

```php
$framework->create_offer( array(
    'id'             => 'eid-sale-2025',
    'title'          => '🌙 Eid Mubarak Sale!',
    'description'    => 'Celebrate Eid with massive discounts on all premium plans.',
    'highlight_text' => '40% OFF',
    'badge_text'     => 'EID SPECIAL',
    'image_url'      => plugin_dir_url( __FILE__ ) . 'assets/images/eid-sale.png',
    'start_date'     => '2025-03-28',
    'end_date'       => '2025-04-05',
    'template'       => 'starter',
    'dismiss_type'   => 'temporary',
    'reshow_after'   => 3,
    'buttons'        => array(
        array(
            'text'  => 'Claim Discount',
            'url'   => 'https://example.com/eid-sale/',
            'class' => 'ca-fw-btn-primary',
        ),
        array(
            'text'  => 'No, Thanks',
            'url'   => '#',
            'class' => 'ca-fw-btn-secondary',
        ),
    ),
) )->show();
```

### Example 2: Plugin Update Notification

```php
$framework->create_offer( array(
    'id'          => 'update-notice-v3',
    'title'       => 'Update to Version 3.0',
    'description' => 'A major update is available with performance improvements and new features.',
    'badge_text'  => 'IMPORTANT',
    'template'    => 'developer',
    'buttons'     => array(
        array(
            'text'  => 'Update Now',
            'url'   => admin_url( 'update-core.php' ),
            'class' => 'ca-fw-btn-primary',
            'icon'  => 'dashicons-update',
        ),
        array(
            'text'   => 'View Changelog',
            'url'    => 'https://example.com/changelog/',
            'class'  => 'ca-fw-btn-outline',
            'target' => '_blank',
        ),
    ),
) )->show();
```

### Example 3: Time-Limited Flash Sale

```php
$framework->create_offer( array(
    'id'             => 'flash-sale-march',
    'title'          => '⚡ 24-Hour Flash Sale!',
    'description'    => 'This deal won\'t last long. Act now!',
    'highlight_text' => 'SAVE $99',
    'badge_text'     => '🔥 ENDING SOON',
    'template'       => 'flash',
    'start_date'     => '2025-03-15 00:00:00',
    'end_date'       => '2025-03-16 00:00:00',
    'dismiss_type'   => 'temporary',
    'reshow_after'   => 1,
    'buttons'        => array(
        array(
            'text'  => '🔥 Grab the Deal!',
            'url'   => 'https://example.com/flash-sale/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```
