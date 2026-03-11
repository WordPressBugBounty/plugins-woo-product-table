# Recommended Plugins Documentation

The Recommended Plugins system displays a modern card-based interface suggesting plugins to enhance the user experience. Includes install/activate buttons, `show_on_hook()` for non-dismissible display, and permanent dismiss support.

---

## Table of Contents

- [Basic Usage](#basic-usage)
- [Plugin Configuration](#plugin-configuration)
- [Display Methods](#display-methods)
- [Show on Hook (No Dismiss)](#show-on-hook)
- [Dismiss Behavior](#dismiss-behavior)
- [Complete Examples](#complete-examples)

---

## Basic Usage

```php
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$framework->recommended_plugins( array(
    array(
        'name'        => 'UltraAddons Elementor',
        'slug'        => 'developer-developer-developer',
        'path'        => 'developer-developer-developer/developer-developer-developer.php',
        'description' => 'Starter Elementor addons for developers.',
        'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.png',
    ),
) )->show(); //add dissmiss id as second param - it's optional
```

---

## Plugin Configuration

Each plugin in the array accepts:

| Parameter     | Type   | Default | Description                                            |
|---------------|--------|---------|--------------------------------------------------------|
| `name`        | string | `''`    | Display name of the plugin                             |
| `slug`        | string | `''`    | WordPress.org plugin slug (for installation)           |
| `path`        | string | `''`    | Plugin path (e.g., `'plugin-folder/plugin-file.php'`)  |
| `description` | string | `''`    | Short description of what the plugin does              |
| `icon`        | string | `''`    | URL to the plugin icon image                           |
| `url`         | string | `''`    | Optional URL for more info about the plugin            |

---

## Display Methods

### Method 1: Admin Notice

Shows as a dismissible notice at the top of admin pages:

```php
$framework->recommended_plugins( array(
    // ... plugins array
), 'dismiss_id-optional' )->show();
```

### Method 2: Section in Plugin Page

Render within your plugin's settings or dashboard page:

```php
$recommended = $framework->recommended_plugins( array(
    // ... plugins array
) );

// In your admin page callback:
$recommended->render_section();
```

---

## Show on Hook

Use `show_on_hook()` to render the recommended plugins on a specific WordPress action hook **without a dismiss button**. This is useful for embedding plugin suggestions inside your settings page or dashboard.

### Basic Usage

```php
$recommended = $framework->recommended_plugins( array(
    array(
        'name'        => 'UltraAddons Elementor',
        'slug'        => 'developer-developer-developer',
        'path'        => 'developer-developer-developer/developer-developer-developer.php',
        'description' => 'Starter Elementor addons for developers.',
        'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.png',
    ),
) );

$recommended->show_on_hook( 'my_plugin_recommended_section' );
```

### Chaining Multiple Hooks

You can chain `show_on_hook()` to display on multiple hooks:

```php
$framework->recommended_plugins( array(
    // ... plugins array
) )->show_on_hook( 'my_plugin_dashboard_bottom' )
   ->show_on_hook( 'my_plugin_settings_sidebar' );
```

### Combining show() and show_on_hook()

You can use both methods on the same instance:

```php
$recommended = $framework->recommended_plugins( array(
    array(
        'name' => 'Super Cache',
        'slug' => 'super-cache',
        'path' => 'super-cache/super-cache.php',
        'description' => 'Speed up your site with caching.',
    ),
) );

// Dismissible version in admin notices
$recommended->show();

// Non-dismissible version on specific hooks
$recommended->show_on_hook( 'my_plugin_before_settings' );
```

> **Important:** Plugins rendered via `show_on_hook()` do NOT have dismiss functionality. The dismiss check is skipped, and the dismiss button is hidden.

---

## Dismiss Behavior

The recommended plugins notice includes a dismiss button:

- **Dismiss is permanent** by default for the current user.
- When dismissed, the notice won't appear again (stored in user meta).
- Each user can dismiss independently.

### Dismiss ID

The dismiss ID is automatically generated as `{plugin_slug}_recommended`. For example, if your plugin slug is `my-plugin`, the dismiss ID will be `my-plugin_recommended`.

---

## Difference from Required Plugins

| Feature                | Required Plugins          | Recommended Plugins           |
|------------------------|---------------------------|-------------------------------|
| **Importance**         | Must be installed         | Optional, enhances experience |
| **Dismiss**            | No dismiss button         | Has dismiss button            |
| **Header icon**        | Warning (⚠️)             | Star (⭐)                    |
| **Install button**     | Primary (filled) style    | Outline style                 |
| **Auto-hide**          | When all active           | When dismissed or all active  |
| **Style**              | Warning-themed            | Purple star-themed            |

---

## Complete Examples

### Example 1: Single Recommended Plugin

```php
$framework->recommended_plugins( array(
    array(
        'name'        => 'developer developer developer',
        'slug'        => 'developer-developer-developer',
        'path'        => 'developer-developer-developer/developer-developer-developer.php',
        'description' => 'Starter addons for Elementor page builder.',
        'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.png',
    ),
) )->show();
```

### Example 2: Multiple Recommendations

```php
$framework->recommended_plugins( array(
    array(
        'name'        => 'developer developer developer',
        'slug'        => 'developer-developer-developer',
        'path'        => 'developer-developer-developer/developer-developer-developer.php',
        'description' => 'Starter addons for Elementor page builder.',
        'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.png',
    ),
    array(
        'name'        => 'Developer Plugin 2',
        'slug'        => 'developer-plugin-2',
        'path'        => 'developer-plugin-2/developer-plugin-2.php',
        'description' => 'Another great plugin by the same team.',
        'icon'        => 'https://ps.w.org/developer-plugin-2/assets/icon-256x256.png',
    ),
    array(
        'name'        => 'Developer Plugin 3',
        'slug'        => 'developer-plugin-3',
        'path'        => 'developer-plugin-3/developer-plugin-3.php',
        'description' => 'Extend your workflow with this tool.',
    ),
) )->show();
```

### Example 3: In Plugin Settings Page

```php
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$recommended = $framework->recommended_plugins( array(
    array(
        'name'        => 'Starter SEO',
        'slug'        => 'starter-seo',
        'path'        => 'starter-seo/starter-seo.php',
        'description' => 'Optimize your site for search engines.',
        'icon'        => 'https://ps.w.org/starter-seo/assets/icon-256x256.png',
    ),
) );

// Render in your settings page
add_action( 'admin_menu', function() use ( $recommended ) {
    add_menu_page(
        'My Plugin',
        'My Plugin',
        'manage_options',
        'my-plugin',
        function() use ( $recommended ) {
            echo '<div class="wrap">';
            echo '<h1>My Plugin</h1>';

            // Your settings content here...

            echo '<h2>You May Also Like</h2>';
            $recommended->render_section();

            echo '</div>';
        }
    );
} );
```

---

## Notes

- Active plugins are automatically excluded from the display.
- Install functionality uses WordPress's built-in `wp.updates` API.
- The grid layout automatically adjusts based on the number of plugins.
- If no icon is provided, a gradient placeholder icon is shown.
- Once all recommended plugins are active, the section hides automatically.
