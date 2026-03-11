# Popups Documentation

The Popup system displays modal overlays on specific admin pages with dismiss, re-show, and countdown functionality. Perfect for upgrade prompts, announcements, or important messages.

---

## Table of Contents

- [Basic Usage](#basic-usage)
- [Configuration Options](#configuration-options)
- [Page Targeting](#page-targeting)
- [Dismiss & Re-show](#dismiss--re-show)
- [Reshow Unit (Days / Hours)](#reshow-unit)
- [Countdown Timer in Popup](#countdown-timer-in-popup)
- [Complete Examples](#complete-examples)

---

## Basic Usage

```php
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$framework->create_popup( array(
    'title'       => 'Welcome to Our Plugin!',
    'description' => 'Thank you for installing. Check out the premium features.',
    'buttons'     => array(
        array(
            'text' => 'View Premium',
            'url'  => 'https://example.com/premium/',
        ),
    ),
) )->show();
```

---

## Configuration Options

| Parameter        | Type   | Default          | Description                                           |
|------------------|--------|------------------|-------------------------------------------------------|
| `id`             | string | auto-generated   | Unique identifier for the popup                       |
| `title`          | string | `''`             | Popup title text                                      |
| `description`    | string | `''`             | Popup content (supports HTML via `wp_kses_post`)      |
| `start_date`     | string | `''`             | Start date (e.g., `'2025-01-01'`)                     |
| `end_date`       | string | `''`             | End date (e.g., `'2025-12-31'`)                       |
| `buttons`        | array  | `array()`        | Array of button configurations                        |
| `badge_text`     | string | `''`             | Badge text displayed above the title                  |
| `image_url`      | string | `''`             | Image displayed at the top of the popup               |
| `show_countdown` | bool   | `false`          | Show live countdown timer (requires `end_date`)       |
| `dismiss_type`   | string | `'temporary'`    | `'permanent'` or `'temporary'`                        |
| `reshow_after`   | int    | `7`              | Time after which to re-show (for `temporary`)         |
| `reshow_unit`    | string | `'days'`         | Unit for `reshow_after`: `'days'` or `'hours'`        |
| `pages`          | array  | `array()`        | Admin screen IDs where the popup will appear          |
| `capability`     | string | `'manage_options'`| Required user capability                             |
| `width`          | string | `'520px'`        | Maximum width of the popup                            |
| `overlay`        | bool   | `true`           | Whether to show the dark overlay                      |
| `randomize`      | int    | `100`            | Percentage chance (0-100) to show the popup           |

---

## Page Targeting

Popups can be restricted to show only on specific admin pages:

```php
// Show only on the main plugin page
$framework->create_popup( array(
    'pages' => array( 'toplevel_page_my-plugin' ),
    'title' => 'Plugin Page Popup',
    // ...
) )->show();
```

### Finding Screen IDs

Add this temporary code to find the screen ID of any admin page:

```php
add_action( 'admin_notices', function() {
    $screen = get_current_screen();
    echo '<div class="notice"><p>Screen ID: ' . esc_html( $screen->id ) . '</p></div>';
} );
```

### Common Screen IDs

| Page                | Screen ID                        |
|---------------------|----------------------------------|
| Dashboard           | `dashboard`                      |
| All Posts           | `edit-post`                      |
| All Pages           | `edit-page`                      |
| All Products        | `edit-product`                   |
| Plugins             | `plugins`                        |
| WooCommerce         | `woocommerce_page_wc-settings`   |
| Custom top-level    | `toplevel_page_{menu-slug}`      |
| Custom sub-page     | `{parent-slug}_page_{menu-slug}` |

---

## Dismiss & Re-show

### Temporary Dismiss (Re-shows After N Days)

```php
$framework->create_popup( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 7, // Show again after 7 days
    'title'        => 'Check Out Premium!',
    'randomize'   => 30,
    // ...
) )->show();
```

### Permanent Dismiss (Never Shows Again)

```php
$framework->create_popup( array(
    'dismiss_type' => 'permanent',
    'title'        => 'One-Time Announcement',
    // ...
) )->show();
```

### How It Works

1. **User closes the popup** → An AJAX request saves the dismiss preference.
2. **Temporary dismiss** → Stores a timestamp. After the specified time, the popup re-appears.
3. **Permanent dismiss** → Stores `'permanent'` in user meta. Never shows again.
4. **Per-user basis** → Each user's dismiss is tracked independently.

---

## Reshow Unit

By default, `reshow_after` counts in **days**. You can change it to **hours** using `reshow_unit`:

### Reshow After Hours

```php
$framework->create_popup( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 6,       // Re-show after 6 hours
    'reshow_unit'  => 'hours', // Use hours instead of days, can be 'seconds', 'minutes', 'hours', or 'days'
    'title'        => 'Quick Reminder',
    // ...
) )->show();
```

### Reshow After Days (Default)

```php
$framework->create_popup( array(
    'dismiss_type' => 'temporary',
    'reshow_after' => 14,     // Re-show after 14 days
    'reshow_unit'  => 'days', // Default, can be omitted
    'title'        => 'Check Out Premium!',
    // ...
) )->show();
```

---

## Countdown Timer in Popup

You can show a live countdown timer inside the popup by setting `show_countdown` to `true`. Requires `end_date` to be set.

### Basic Countdown Popup

```php
$framework->create_popup( array(
    'title'          => '🔥 Flash Sale Ending Soon!',
    'description'    => '<p>Don\'t miss out on this limited-time offer!</p>',
    'badge_text'     => 'FLASH SALE',
    'end_date'       => '2025-06-15 23:59:59',
    'show_countdown' => true,
    'pages'          => array( 'toplevel_page_my-plugin' ),
    'buttons'        => array(
        array(
            'text' => 'Get 50% OFF',
            'url'  => 'https://example.com/sale/',
        ),
    ),
) )->show();
```

### Seasonal Countdown Popup

```php
$framework->create_popup( array(
    'id'             => 'eid-sale-popup',
    'title'          => '🌙 Eid Sale — Ends Soon!',
    'description'    => 'Massive discounts on all premium plans.',
    'badge_text'     => 'EID MUBARAK',
    'image_url'      => plugin_dir_url( __FILE__ ) . 'assets/images/eid-banner.png',
    'start_date'     => '2025-03-28',
    'end_date'       => '2025-04-05 23:59:59',
    'show_countdown' => true,
    'dismiss_type'   => 'temporary',
    'reshow_after'   => 12,
    'reshow_unit'    => 'hours',
    'pages'          => array( 'toplevel_page_my-plugin' ),
    'width'          => '550px',
    'buttons'        => array(
        array(
            'text'  => '🎁 Claim 40% OFF',
            'url'   => 'https://example.com/eid-sale/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

> **Note:** The countdown timer in the popup counts down in real-time (updates every second). When the countdown reaches zero, it displays `00:00:00:00`.

---

## Complete Examples

### Example 1: Upgrade Popup on Plugin Page

```php
$framework->create_popup( array(
    'id'           => 'upgrade-popup',
    'title'        => '🚀 Unlock Premium Features',
    'description'  => '<p>Upgrade to the Pro version and get:</p>
        <ul>
            <li>✅ Unlimited templates</li>
            <li>✅ Priority support</li>
            <li>✅ Advanced customization</li>
            <li>✅ Regular updates</li>
        </ul>
        <p>30-day money-back guarantee!</p>',
    'badge_text'   => 'PRO VERSION',
    'image_url'    => plugin_dir_url( __FILE__ ) . 'assets/images/pro-banner.png',
    'dismiss_type' => 'temporary',
    'reshow_after' => 14,
    'pages'        => array( 'toplevel_page_my-plugin' ),
    'width'        => '550px',
    'buttons'      => array(
        array(
            'text'  => 'Upgrade Now - 50% OFF',
            'url'   => 'https://example.com/pricing/',
            'class' => 'ca-fw-btn-primary',
            'icon'  => 'dashicons-star-filled',
        ),
        array(
            'text'  => 'Maybe Later',
            'url'   => '#',
            'class' => 'ca-fw-btn-secondary',
        ),
    ),
) )->show();
```

### Example 2: Welcome Popup (Show Once)

```php
$framework->create_popup( array(
    'id'           => 'welcome-popup',
    'title'        => '👋 Welcome to MyPlugin!',
    'description'  => '<p>Thank you for installing MyPlugin. Here are some quick tips to get started:</p>
        <ol>
            <li>Go to Settings → MyPlugin</li>
            <li>Configure your preferences</li>
            <li>Start using the shortcode <code>[my_plugin]</code></li>
        </ol>',
    'dismiss_type' => 'permanent',
    'pages'        => array( 'toplevel_page_my-plugin' ),
    'buttons'      => array(
        array(
            'text'  => 'Go to Settings',
            'url'   => admin_url( 'admin.php?page=my-plugin' ),
            'class' => 'ca-fw-btn-primary',
        ),
        array(
            'text'  => 'Read Documentation',
            'url'   => 'https://example.com/docs/',
            'class' => 'ca-fw-btn-outline',
        ),
    ),
) )->show();
```

### Example 3: Seasonal Popup with Image

```php
$framework->create_popup( array(
    'id'           => 'new-year-popup-2025',
    'title'        => '🎆 New Year Sale!',
    'description'  => 'Start the new year with powerful tools. All plans on sale!',
    'badge_text'   => 'NEW YEAR 2025',
    'image_url'    => 'https://example.com/images/new-year-sale.jpg',
    'start_date'   => '2024-12-28',
    'end_date'     => '2025-01-05',
    'dismiss_type' => 'temporary',
    'reshow_after' => 2,
    'pages'        => array(
        'toplevel_page_my-plugin',
        'my-plugin_page_settings',
    ),
    'buttons'      => array(
        array(
            'text'  => '🎁 Claim 60% OFF',
            'url'   => 'https://example.com/new-year-sale/',
            'class' => 'ca-fw-btn-primary',
        ),
    ),
) )->show();
```

### Example 4: Survey/Feedback Popup

```php
$framework->create_popup( array(
    'id'           => 'feedback-popup',
    'title'        => '💬 We Value Your Feedback',
    'description'  => '<p>How has your experience been with MyPlugin?</p>
        <p>Your feedback helps us improve!</p>',
    'dismiss_type' => 'temporary',
    'reshow_after' => 30,
    'width'        => '450px',
    'buttons'      => array(
        array(
            'text'  => '⭐ Leave a Review',
            'url'   => 'https://wordpress.org/support/plugin/my-plugin/reviews/',
            'class' => 'ca-fw-btn-primary',
        ),
        array(
            'text'  => 'Report an Issue',
            'url'   => 'https://wordpress.org/support/plugin/my-plugin/',
            'class' => 'ca-fw-btn-outline',
        ),
    ),
) )->show();
```

---

## Closing Behavior

Popups can be closed by:

1. **Clicking the X button** in the top right corner
2. **Clicking the overlay** (dark background area)
3. **Pressing the Escape key**

All three methods trigger the dismiss AJAX request.
