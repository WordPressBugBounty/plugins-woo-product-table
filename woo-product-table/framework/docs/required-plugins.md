# Required Plugins Documentation

The Required Plugins system displays a notice with install/activate buttons for plugins that your plugin depends on. Automatically hides plugins that are already active.

---

## Table of Contents

- [Basic Usage](#basic-usage)
- [Plugin Configuration](#plugin-configuration)
- [Display Methods](#display-methods)
- [Plugin Status Detection](#plugin-status-detection)
- [Complete Examples](#complete-examples)

---

## Basic Usage

```php
require_once plugin_dir_path( __FILE__ ) . 'framework/framework.php';
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

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

---

## Plugin Configuration

Each plugin in the array accepts the following parameters:

| Parameter     | Type   | Default | Description                                          |
|---------------|--------|---------|------------------------------------------------------|
| `name`        | string | `''`    | Display name of the plugin                           |
| `slug`        | string | `''`    | WordPress.org plugin slug (for installation)         |
| `path`        | string | `''`    | Plugin path (e.g., `'plugin-folder/plugin-file.php'`)|
| `description` | string | `''`    | Short description of why it's needed                 |
| `icon`        | string | `''`    | URL to the plugin icon image                         |
| `required`    | bool   | `true`  | Whether the plugin is strictly required              |

### Finding the Plugin Path

The plugin path follows the format: `folder-name/main-file.php`

Examples:
- WooCommerce: `woocommerce/woocommerce.php`
- Elementor: `elementor/elementor.php`
- Contact Form 7: `contact-form-7/wp-contact-form-7.php`

---

## Display Methods

### Method 1: Admin Notice (Recommended)

Shows as a WordPress admin notice at the top of admin pages:

```php
$framework->required_plugins( array(
    // ... plugins array
) )->show();
```

### Method 2: Section in Settings Page

Render as a section within your plugin's settings page:

```php
$required = $framework->required_plugins( array(
    // ... plugins array
) );

// In your admin page callback:
function my_plugin_settings_page() {
    global $required;
    echo '<div class="wrap">';
    echo '<h1>My Plugin Settings</h1>';
    $required->render_section();
    // ... rest of your settings page
    echo '</div>';
}
```

---

## Plugin Status Detection

The system automatically detects three states for each plugin:

| Status          | Display                                           |
|-----------------|---------------------------------------------------|
| **Active**      | Plugin is hidden (no action needed)               |
| **Installed**   | Shows green "Activate" button                     |
| **Not Installed** | Shows purple "Install" button                   |

### How It Works

1. **Install Button Clicked** → Uses `wp.updates.installPlugin()` (WordPress AJAX installer)
2. **Installation Complete** → Button changes to "Activate"
3. **Activate Button Clicked** → Sends AJAX request to activate the plugin
4. **Activation Complete** → Card shows success state and fades out
5. **All Plugins Active** → Entire notice is removed

### Checking Status Programmatically

```php
$status = CA_Framework_Required_Plugin::get_plugin_status( 'woocommerce/woocommerce.php' );
// Returns: 'active', 'installed', or 'not_installed'
```

---

## Complete Examples

### Example 1: Single Required Plugin

```php
$framework->required_plugins( array(
    array(
        'name'        => 'WooCommerce',
        'slug'        => 'woocommerce',
        'path'        => 'woocommerce/woocommerce.php',
        'description' => 'WooCommerce is required for product table functionality.',
        'icon'        => 'https://ps.w.org/woocommerce/assets/icon-256x256.gif',
    ),
) )->show();
```

### Example 2: Multiple Required Plugins

```php
$framework->required_plugins( array(
    array(
        'name'        => 'WooCommerce',
        'slug'        => 'woocommerce',
        'path'        => 'woocommerce/woocommerce.php',
        'description' => 'Core e-commerce engine required for product tables.',
        'icon'        => 'https://ps.w.org/woocommerce/assets/icon-256x256.gif',
    ),
    array(
        'name'        => 'Elementor',
        'slug'        => 'elementor',
        'path'        => 'elementor/elementor.php',
        'description' => 'Page builder required for drag-and-drop widget.',
        'icon'        => 'https://ps.w.org/elementor/assets/icon-256x256.gif',
    ),
    array(
        'name'        => 'Contact Form 7',
        'slug'        => 'contact-form-7',
        'path'        => 'contact-form-7/wp-contact-form-7.php',
        'description' => 'Required for the inquiry form column.',
        'icon'        => 'https://ps.w.org/contact-form-7/assets/icon-256x256.png',
    ),
) )->show();
```

### Example 3: Required Plugins Inside Settings Page

```php
// During plugin initialization
$framework = CA_Framework::init( 'my-plugin', __FILE__ );

$req_plugins = $framework->required_plugins( array(
    array(
        'name'        => 'WooCommerce',
        'slug'        => 'woocommerce',
        'path'        => 'woocommerce/woocommerce.php',
        'description' => 'Required for product management.',
    ),
) );

// In your settings page render function
add_action( 'admin_menu', function() use ( $req_plugins ) {
    add_menu_page(
        'My Plugin',
        'My Plugin',
        'manage_options',
        'my-plugin',
        function() use ( $req_plugins ) {
            echo '<div class="wrap">';
            echo '<h1>My Plugin Settings</h1>';
            $req_plugins->render_section();
            echo '</div>';
        }
    );
} );
```

### Example 4: Without Icons (Uses Placeholder)

```php
$framework->required_plugins( array(
    array(
        'name'        => 'WooCommerce',
        'slug'        => 'woocommerce',
        'path'        => 'woocommerce/woocommerce.php',
        'description' => 'Required for e-commerce features.',
        // No 'icon' — a gradient placeholder icon will be shown
    ),
) )->show();
```

---

## Notes

- The install functionality uses WordPress's built-in `wp.updates` JavaScript API.
- Plugin installation requires the `install_plugins` capability.
- Plugin activation requires the `activate_plugins` capability.
- The notice will not display if the current user lacks the required capabilities.
- Once all required plugins are activated, the notice disappears completely.
