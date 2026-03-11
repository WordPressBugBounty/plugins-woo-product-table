# API Reference

Complete API reference for all CA Framework classes and methods.

---

## Table of Contents

- [CA_Framework (Main Class)](#ca_framework)
- [CA_Framework_Offer](#ca_framework_offer)
- [CA_Framework_Popup](#ca_framework_popup)
- [CA_Framework_Required_Plugin](#ca_framework_required_plugin)
- [CA_Framework_Recommended_Plugin](#ca_framework_recommended_plugin)
- [CA_Framework_Dismiss_Handler](#ca_framework_dismiss_handler)

---

## CA_Framework

The main framework class. Singleton-per-plugin pattern.

### `CA_Framework::init( $plugin_slug, $plugin_file )`

Initialize the framework for a plugin.

**Parameters:**
| Name           | Type   | Description                              |
|----------------|--------|------------------------------------------|
| `$plugin_slug` | string | Unique plugin identifier (e.g., `'wpt'`) |
| `$plugin_file` | string | Main plugin file path (`__FILE__`)       |

**Returns:** `CA_Framework` instance

```php
$framework = CA_Framework::init( 'my-plugin', __FILE__ );
```

---

### `CA_Framework::get_instance( $plugin_slug )`

Retrieve an existing framework instance.

**Parameters:**
| Name           | Type   | Description         |
|----------------|--------|---------------------|
| `$plugin_slug` | string | Plugin identifier   |

**Returns:** `CA_Framework|null`

```php
$framework = CA_Framework::get_instance( 'my-plugin' );
```

---

### `$framework->create_offer( $args )`

Create a new offer/notice.

**Parameters:**
| Name    | Type  | Description                |
|---------|-------|----------------------------|
| `$args` | array | Offer configuration array  |

**Returns:** `CA_Framework_Offer`

```php
$offer = $framework->create_offer( array(
    'title'    => 'Sale!',
    'template' => 'starter',
) );
$offer->show();
```

---

### `$framework->create_popup( $args )`

Create a new popup.

**Parameters:**
| Name    | Type  | Description                |
|---------|-------|----------------------------|
| `$args` | array | Popup configuration array  |

**Returns:** `CA_Framework_Popup`

```php
$popup = $framework->create_popup( array(
    'title' => 'Upgrade Now!',
    'pages' => array( 'toplevel_page_my-plugin' ),
) );
$popup->show();
```

---

### `$framework->required_plugins( $plugins )`

Register required plugins.

**Parameters:**
| Name       | Type  | Description                        |
|------------|-------|------------------------------------|
| `$plugins` | array | Array of plugin config arrays      |

**Returns:** `CA_Framework_Required_Plugin`

---

### `$framework->recommended_plugins( $plugins )`

Register recommended plugins.

**Parameters:**
| Name       | Type  | Description                        |
|------------|-------|------------------------------------|
| `$plugins` | array | Array of plugin config arrays      |

**Returns:** `CA_Framework_Recommended_Plugin`

---

### `$framework->get_dir()`

Get the framework directory path (with trailing slash).

**Returns:** `string`

---

### `$framework->get_url()`

Get the framework directory URL (with trailing slash).

**Returns:** `string`

---

### `$framework->get_slug()`

Get the parent plugin slug.

**Returns:** `string`

---

## CA_Framework_Offer

Handles date-based promotional offers.

### `new CA_Framework_Offer( $args )`

**Parameters (in `$args`):**

| Key              | Type   | Default           | Description                        |
|------------------|--------|-------------------|------------------------------------|
| `id`             | string | auto-generated    | Unique offer identifier            |
| `plugin_slug`    | string | `''`              | Parent plugin slug (set by framework) |
| `title`          | string | `''`              | Offer title                        |
| `description`    | string | `''`              | Offer description (HTML allowed)   |
| `start_date`     | string | `''`              | Start date (`Y-m-d` or datetime)   |
| `end_date`       | string | `''`              | End date (`Y-m-d` or datetime)     |
| `template`       | string | `'starter'`       | Template: `starter`, `developer`, `flash` |
| `buttons`        | array  | `array()`         | Button configurations              |
| `badge_text`     | string | `''`              | Badge label text                   |
| `highlight_text` | string | `''`              | Large highlight text               |
| `image_url`      | string | `''`              | Image URL                          |
| `show_countdown` | bool   | `false`           | Show live countdown timer          |
| `dismiss_type`   | string | `'permanent'`     | `'permanent'` or `'temporary'`     |
| `reshow_after`   | int    | `0`               | Time to re-show after dismiss      |
| `reshow_unit`    | string | `'days'`          | Unit: `'days'` or `'hours', 'seconds', 'minutes'`        |
| `hook`           | string | `'admin_notices'` | WordPress hook                     |
| `priority`       | int    | `10`              | Hook priority                      |
| `pages`          | array  | `array()`         | Allowed screen IDs                 |
| `capability`     | string | `'manage_options'`| Required capability                |

---

### `$offer->show()`

Hook the offer for display (with dismiss button).

**Returns:** `$this` (chainable)

```php
$framework->create_offer( $args )->show();
```

---

### `$offer->show_on_hook( $hook, $priority )`

Hook the offer for display on a specific action hook **without dismiss button**.

**Parameters:**
| Name        | Type   | Default | Description              |
|-------------|--------|---------|--------------------------|
| `$hook`     | string | —       | WordPress action hook    |
| `$priority` | int    | `10`    | Hook priority            |

**Returns:** `$this` (chainable)

```php
// Single hook
$framework->create_offer( $args )->show_on_hook( 'my_plugin_before_content' );

// Multiple hooks (chained)
$framework->create_offer( $args )
    ->show_on_hook( 'my_plugin_dashboard' )
    ->show_on_hook( 'my_plugin_settings_top' );

// Combined with show()
$framework->create_offer( $args )
    ->show()
    ->show_on_hook( 'my_plugin_before_content' );
```

---

### `$offer->should_display()`

Check if the offer should be visible.

**Returns:** `bool`

Checks: capability, dismiss status, date range, page restriction.

---

### `$offer->render()`

Render the offer HTML. Called automatically by WordPress hook.

---

### `CA_Framework_Offer::render_buttons( $buttons )`

Static method to render button HTML.

**Parameters:**
| Name       | Type  | Description              |
|------------|-------|--------------------------|
| `$buttons` | array | Array of button configs  |

**Returns:** `string` HTML

**Button config:**
| Key      | Type   | Default              | Description      |
|----------|--------|----------------------|------------------|
| `text`   | string | `''`                 | Button text      |
| `url`    | string | `'#'`                | Button URL       |
| `class`  | string | `'ca-fw-btn-primary'`| CSS class        |
| `target` | string | `'_blank'`           | Link target      |
| `icon`   | string | `''`                 | Dashicons class  |

---

### `CA_Framework_Offer::render_countdown( $config )`

Static method to render countdown timer HTML.

**Parameters:**
| Name      | Type  | Description                                     |
|-----------|-------|-------------------------------------------------|
| `$config` | array | Offer or popup config array (needs `show_countdown` and `end_date`) |

**Returns:** `string` HTML (empty string if countdown not enabled or no end_date)

```php
echo CA_Framework_Offer::render_countdown( array(
    'show_countdown' => true,
    'end_date'       => '2025-12-31 23:59:59',
) );
```

---

## CA_Framework_Popup

Handles modal popup overlays.

### `new CA_Framework_Popup( $args )`

**Parameters (in `$args`):**

| Key              | Type   | Default           | Description                   |
|------------------|--------|-------------------|-------------------------------|
| `id`             | string | auto-generated    | Unique popup identifier       |
| `plugin_slug`    | string | `''`              | Parent plugin slug            |
| `title`          | string | `''`              | Popup title                   |
| `description`    | string | `''`              | Content (HTML allowed)        |
| `start_date`     | string | `''`              | Start date                    |
| `end_date`       | string | `''`              | End date                      |
| `buttons`        | array  | `array()`         | Button configurations         |
| `badge_text`     | string | `''`              | Badge text                    |
| `image_url`      | string | `''`              | Top image URL                 |
| `show_countdown` | bool   | `false`           | Show live countdown timer     |
| `dismiss_type`   | string | `'temporary'`     | `'permanent'` or `'temporary'`|
| `reshow_after`   | int    | `7`               | Time to re-show               |
| `reshow_unit`    | string | `'days'`          | Unit: `'days'` or `'hours', 'seconds', 'minutes'`   |
| `pages`          | array  | `array()`         | Allowed screen IDs            |
| `capability`     | string | `'manage_options'`| Required capability           |
| `width`          | string | `'520px'`         | Max popup width               |
| `overlay`        | bool   | `true`            | Show dark overlay             |

---

### `$popup->show()`

Hook the popup for display (via `admin_footer`).

**Returns:** `$this` (chainable)

---

### `$popup->should_display()`

Check if the popup should be visible.

**Returns:** `bool`

---

### `$popup->render()`

Render the popup HTML. Called automatically.

---

## CA_Framework_Required_Plugin

Manages required plugin dependencies.

### `new CA_Framework_Required_Plugin( $plugin_slug, $plugins )`

**Parameters:**
| Name           | Type   | Description                  |
|----------------|--------|------------------------------|
| `$plugin_slug` | string | Parent plugin identifier     |
| `$plugins`     | array  | Array of plugin configs      |

**Plugin config:**
| Key           | Type   | Default | Description                     |
|---------------|--------|---------|---------------------------------|
| `name`        | string | `''`    | Plugin display name             |
| `slug`        | string | `''`    | WordPress.org slug              |
| `path`        | string | `''`    | Plugin path (folder/file.php)   |
| `description` | string | `''`    | Short description               |
| `icon`        | string | `''`    | Icon URL                        |
| `required`    | bool   | `true`  | Whether strictly required       |

---

### `$required->show()`

Show as admin notice.

**Returns:** `$this` (chainable)

---

### `$required->render_section()`

Render as a section (without notice wrapper).

---

### `CA_Framework_Required_Plugin::get_plugin_status( $plugin_path )`

Static method to check plugin status.

**Parameters:**
| Name           | Type   | Description                       |
|----------------|--------|-----------------------------------|
| `$plugin_path` | string | Plugin path (e.g., `'woo/woo.php'`)|

**Returns:** `string` — `'active'`, `'installed'`, or `'not_installed'`

---

## CA_Framework_Recommended_Plugin

Displays recommended plugin suggestions.

### `new CA_Framework_Recommended_Plugin( $plugin_slug, $plugins )`

Same constructor signature as Required Plugin.

**Additional plugin config key:**
| Key   | Type   | Default | Description                  |
|-------|--------|---------|------------------------------|
| `url` | string | `''`    | Optional plugin info URL     |

---

### `$recommended->show()`

Show as dismissible admin notice.

**Returns:** `$this` (chainable)

---

### `$recommended->show_on_hook( $hook, $priority )`

Display on a specific action hook **without dismiss button**.

**Parameters:**
| Name        | Type   | Default | Description              |
|-------------|--------|---------|--------------------------|
| `$hook`     | string | —       | WordPress action hook    |
| `$priority` | int    | `10`    | Hook priority            |

**Returns:** `$this` (chainable)

```php
// Single hook
$framework->recommended_plugins( $plugins )->show_on_hook( 'my_settings_sidebar' );

// Multiple hooks (chained)
$framework->recommended_plugins( $plugins )
    ->show_on_hook( 'my_plugin_dashboard' )
    ->show_on_hook( 'my_plugin_settings' );

// Combined with show()
$framework->recommended_plugins( $plugins )
    ->show()
    ->show_on_hook( 'my_plugin_settings_bottom' );
```

---

### `$recommended->render_section()`

Render as a section (without notice wrapper or dismiss button).

---

## CA_Framework_Dismiss_Handler

Handles AJAX dismiss and plugin activation requests.

### `CA_Framework_Dismiss_Handler::register( $plugin_slug )`

Register AJAX hooks. Called automatically by the framework.

---

### `CA_Framework_Dismiss_Handler::is_dismissed( $dismiss_id )`

Check if an item is dismissed for the current user.

**Parameters:**
| Name          | Type   | Description              |
|---------------|--------|--------------------------|
| `$dismiss_id` | string | Unique dismiss identifier|

**Returns:** `bool`

```php
if ( ! CA_Framework_Dismiss_Handler::is_dismissed( 'my-offer-id' ) ) {
    // Show the offer
}
```

---

### `CA_Framework_Dismiss_Handler::handle_dismiss()`

AJAX handler for dismiss requests. Registered automatically.

**POST Parameters:**
| Name           | Type   | Description                              |
|----------------|--------|------------------------------------------|
| `dismiss_id`   | string | Unique identifier                        |
| `dismiss_type` | string | `'permanent'` or `'temporary'`           |
| `reshow_after` | int    | Time until re-show (temporary only)      |
| `reshow_unit`  | string | `'days'` or `'hours', 'seconds', 'minutes'` (default: `'days'`)|
| `nonce`        | string | Security nonce                           |

---

### `CA_Framework_Dismiss_Handler::handle_activate_plugin()`

AJAX handler for plugin activation. Registered automatically.

**POST Parameters:**
| Name          | Type   | Description                       |
|---------------|--------|-----------------------------------|
| `plugin_path` | string | Plugin path (folder/file.php)     |
| `nonce`       | string | Security nonce                    |
