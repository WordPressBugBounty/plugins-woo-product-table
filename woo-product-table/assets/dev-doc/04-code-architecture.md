# কোড আর্কিটেকচার - গভীর বিশ্লেষণ

এই ডকুমেন্টে প্লাগিনের কোড আর্কিটেকচার বিস্তারিতভাবে ব্যাখ্যা করা হয়েছে।

## সামগ্রিক আর্কিটেকচার

### ডিজাইন প্যাটার্ন

প্লাগিনটি নিম্নলিখিত প্যাটার্ন ব্যবহার করে:

1. **MVC (Model-View-Controller) প্যাটার্ন:**
   - Model: WooCommerce প্রোডাক্ট ডেটা
   - View: টেমপ্লেট ফাইল (`includes/items/`, `templates/`)
   - Controller: শর্টকোড ক্লাস (`inc/shortcode.php`)

2. **Singleton Pattern:**
   - মূল `WPT_Product_Table` ক্লাস
   - একটি মাত্র ইন্সট্যান্স তৈরি হয়

3. **Factory Pattern:**
   - টেবিল রো তৈরির জন্য `Row` ক্লাস

## প্লাগিন লাইফসাইকেল

### ১. প্লাগিন ইনিশিয়ালাইজেশন

**ফাইল:** `woo-product-table.php`

```php
// ধাপ ১: কনস্ট্যান্ট ডিফাইন করা
define( 'WPT_DEV_VERSION', '5.0.6.2' );
define( 'WPT_BASE_URL', plugins_url() . '/woo-product-table/' );

// ধাপ ২: Freemius SDK লোড করা
if ( ! function_exists( 'wpt_fs' ) ) {
    function wpt_fs() {
        // Freemius initialization
    }
}

// ধাপ ৩: মূল ক্লাস তৈরি
class WPT_Product_Table {
    private static $_instance = null;
    
    public static function getInstance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
```

### ২. Autoloader সেটআপ

**ফাইল:** `autoloader.php`

```php
spl_autoload_register( function( $class ) {
    // WOO_PRODUCT_TABLE\Admin\Admin_Loader
    // → admin/admin-loader.php
    
    $namespace = 'WOO_PRODUCT_TABLE\\';
    if ( strpos( $class, $namespace ) !== 0 ) {
        return;
    }
    
    // Namespace থেকে ফাইল পাথ তৈরি
    $class_file = str_replace( $namespace, '', $class );
    $class_file = strtolower( str_replace( '\\', '/', $class_file ) );
    $class_file = str_replace( '_', '-', $class_file );
    
    // ফাইল require করা
    require_once WPT_BASE_DIR . $class_file . '.php';
});
```

### ৩. অ্যাডমিন এরিয়া লোড

**ফাইল:** `admin/admin-loader.php`

```php
namespace WOO_PRODUCT_TABLE\Admin;

class Admin_Loader extends Base {
    public function __construct() {
        // পেজ লোডার
        $main_page = new Page_Loader();
        $main_page->run();
        
        // ফিচার লোডার
        $features = new Feature_Loader();
        $features->run();
    }
}
```

### ৪. কাস্টম পোস্ট টাইপ রেজিস্ট্রেশন

**ফাইল:** `admin/wpt_product_table_post.php`

```php
function wpt_product_table_post() {
    $args = array(
        'label' => __( 'PRODUCT TABLE', 'woo-product-table' ),
        'supports' => array( 'title' ),
        'public' => true,
        'show_ui' => true,
        'menu_icon' => $icon,
        'capability_type' => 'wpt_product_table',
    );
    
    register_post_type( 'wpt_product_table', $args );
}
add_action( 'init', 'wpt_product_table_post' );
```

## শর্টকোড সিস্টেম

### শর্টকোড রেজিস্ট্রেশন

**ফাইল:** `inc/shortcode.php`

```php
namespace WOO_PRODUCT_TABLE\Inc;

class Shortcode extends Shortcode_Base {
    
    public function __construct() {
        // শর্টকোড রেজিস্টার করা
        add_shortcode( 'Product_Table', array( $this, 'product_table' ) );
    }
    
    public function product_table( $atts ) {
        // শর্টকোড এট্রিবিউট পার্স করা
        $this->atts = shortcode_atts( array(
            'id' => '',
            'name' => '',
        ), $atts );
        
        // টেবিল আইডি পাওয়া
        $this->table_id = $this->get_table_id();
        
        // টেবিল রেন্ডার করা
        return $this->render_table();
    }
}
```

### টেবিল রেন্ডারিং প্রক্রিয়া

```
[Product_Table id='123']
    ↓
Shortcode::product_table()
    ↓
1. এট্রিবিউট পার্স করা
2. টেবিল কনফিগ লোড করা (get_post_meta)
3. WP_Query রান করা (প্রোডাক্ট পাওয়ার জন্য)
4. লুপ শুরু করা
    ↓
    Row::generate() (প্রতিটি প্রোডাক্টের জন্য)
        ↓
        1. প্রোডাক্ট ডেটা লোড
        2. <tr> ট্যাগ শুরু
        3. প্রতিটি কলামের জন্য <td> তৈরি
            ↓
            includes/items/{column}.php লোড করা
        4. </tr> ট্যাগ শেষ
5. পেজিনেশন রেন্ডার
6. সম্পূর্ণ HTML রিটার্ন
```

## ক্লাস হায়ারার্কি

### Core Classes

```
Core\Base (বেস ক্লাস)
├── Admin\Admin_Loader
├── Inc\Shortcode_Base
│   └── Inc\Shortcode
│       └── Inc\Shortcode_Ajax
└── Inc\Table\Table_Base
    └── Inc\Table\Row
```

### প্রধান ক্লাসের বিবরণ

#### Core\Base

সব ক্লাসের parent class।

```php
namespace WOO_PRODUCT_TABLE\Core;

class Base {
    protected function apply_filter( $hook_name, $value ) {
        return apply_filters( $hook_name, $value );
    }
    
    protected function do_action( $hook_name ) {
        do_action( $hook_name );
    }
    
    // অন্যান্য কমন মেথড
}
```

#### Inc\Shortcode

শর্টকোড হ্যান্ডলিং এর মূল ক্লাস।

**গুরুত্বপূর্ণ প্রপার্টি:**
```php
public $table_id;           // টেবিল পোস্ট আইডি
public $unique_id;          // ইউনিক র্যান্ডম স্ট্রিং
public $posts_per_page;     // প্রতি পেজে পণ্য সংখ্যা
public $page_number;        // বর্তমান পেজ নম্বর
public $table_type;         // টেবিল টাইপ
```

**গুরুত্বপূর্ণ মেথড:**
```php
get_table_id()              // টেবিল আইডি পাওয়া
get_table_config()          // টেবিল কনফিগ লোড করা
get_products_query()        // WP_Query তৈরি করা
render_table()              // টেবিল HTML তৈরি করা
```

#### Inc\Table\Row

প্রতিটি প্রোডাক্ট রো তৈরি করে।

**গুরুত্বপূর্ণ প্রপার্টি:**
```php
public $product_id;         // প্রোডাক্ট আইডি
public $product_type;       // প্রোডাক্ট টাইপ (simple, variable)
public $column_array;       // কলামের লিস্ট
public $avialable_variables; // টেমপ্লেটে পাঠানো ভেরিয়েবল
```

**গুরুত্বপূর্ণ মেথড:**
```php
generate()                  // রো HTML তৈরি করা
td_start()                  // <td> ট্যাগ শুরু
td_end()                    // </td> ট্যাগ শেষ
data_for_extract()          // টেমপ্লেটের জন্য ডেটা প্রস্তুত
```

## ডেটা ফ্লো

### টেবিল কনফিগারেশন ফ্লো

```
অ্যাডমিন এডিট পেজ
    ↓
ফর্ম সাবমিট (admin/post_metabox_form.php)
    ↓
ডেটা সেনিটাইজ এবং ভ্যালিডেট
    ↓
update_post_meta($table_id, 'config', $config)
    ↓
ডাটাবেসে সংরক্ষণ (wp_postmeta টেবিল)
```

### শর্টকোড রেন্ডারিং ফ্লো

```
ফ্রন্টএন্ড পেজ লোড
    ↓
WordPress শর্টকোড পার্সার
    ↓
Shortcode::product_table($atts)
    ↓
get_post_meta($table_id, 'config') - কনফিগ লোড
    ↓
WP_Query - প্রোডাক্ট ফেচ করা
    ↓
foreach ($products as $product)
    ↓
    Row::generate($product)
        ↓
        foreach ($columns as $column)
            ↓
            include "includes/items/{$column}.php"
    ↓
HTML আউটপুট
```

### AJAX রিকোয়েস্ট ফ্লো

```
ফ্রন্টএন্ড (wpt-frontend.js)
    ↓
jQuery.ajax({
    action: 'wpt_table_ajax',
    table_id: 123,
    page: 2
})
    ↓
WordPress AJAX Handler
    ↓
Inc\Shortcode_Ajax::ajax_callback()
    ↓
Shortcode::product_table() কল করা
    ↓
JSON Response রিটার্ন
    ↓
ফ্রন্টএন্ড DOM আপডেট
```

## হুক সিস্টেম

### Filter Hooks

**কলাম সম্পর্কিত:**
```php
// নতুন কলাম যোগ করা
apply_filters( 'wpto_default_column_arr', $columns );

// কলাম টেমপ্লেট লোকেশন
apply_filters( 'wpto_template_loc_item_' . $keyword, $file_path );

// উপলব্ধ ভেরিয়েবল
apply_filters( 'wpt_avialable_variables', $variables );
```

**টেবিল সম্পর্কিত:**
```php
// টেবিল CSS ক্লাস
apply_filters( 'wpt_table_class', $classes );

// টেবিল HTML
apply_filters( 'wpt_table_html', $html );

// পেজিনেশন HTML
apply_filters( 'wpt_pagination_html', $html );
```

**কোয়েরি সম্পর্কিত:**
```php
// WP_Query আর্গুমেন্ট
apply_filters( 'wpt_query_args', $args );

// মেটা কোয়েরি
apply_filters( 'wpt_meta_query', $meta_query );

// ট্যাক্স কোয়েরি
apply_filters( 'wpt_tax_query', $tax_query );
```

### Action Hooks

**কলাম রেন্ডারিং:**
```php
// কলাম শুরুতে
do_action( 'wpt_column_top', $keyword, $product );

// কলাম শেষে
do_action( 'wpt_column_bottom', $keyword, $product );

// কলাম সেটিংস ফর্ম
do_action( 'wpto_column_setting_form_' . $keyword, $device, $settings );
```

**টেবিল রেন্ডারিং:**
```php
// টেবিল শুরুতে
do_action( 'wpt_before_table' );

// টেবিল শেষে
do_action( 'wpt_after_table' );

// প্রতিটি রো এর শুরুতে
do_action( 'wpt_before_row', $product );
```

## ডাটাবেস স্কিমা

### wp_posts টেবিল

```sql
-- টেবিল পোস্ট
post_type = 'wpt_product_table'
post_title = 'টেবিলের নাম'
post_status = 'publish'
```

### wp_postmeta টেবিল

```sql
-- কনফিগারেশন ডেটা
meta_key = 'config'
meta_value = serialized array

-- কলাম সেটিংস
meta_key = 'column_settings'
meta_value = serialized array

-- কোয়েরি সেটিংস
meta_key = 'query_settings'
meta_value = serialized array
```

### মেটা ডেটা স্ট্রাকচার

```php
// config মেটা
array(
    'posts_per_page' => 20,
    'pagination_type' => 'default',
    'table_type' => 'normal_table',
    // ... আরও সেটিংস
)

// column_settings মেটা
array(
    'desktop' => array(
        'thumbnails' => array('enable' => true, 'width' => 50),
        'product_title' => array('enable' => true),
        'price' => array('enable' => true),
        // ... অন্যান্য কলাম
    ),
    'mobile' => array(
        // ... মোবাইল কলাম
    )
)
```

## টেমপ্লেট সিস্টেম

### টেমপ্লেট ফাইল লোকেশন

```php
// 1. থিম ডিরেক্টরি চেক করা
$theme_file = get_stylesheet_directory() . '/woo-product-table/items/' . $keyword . '.php';

// 2. প্যারেন্ট থিম চেক করা
$parent_file = get_template_directory() . '/woo-product-table/items/' . $keyword . '.php';

// 3. প্লাগিন ডিরেক্টরি
$plugin_file = WPT_BASE_DIR . 'includes/items/' . $keyword . '.php';

// 4. ফিল্টার দিয়ে কাস্টম লোকেশন
$file = apply_filters( 'wpto_template_loc_item_' . $keyword, $plugin_file );
```

### টেমপ্লেট ভেরিয়েবল

প্রতিটি টেমপ্লেট ফাইলে নিম্নলিখিত ভেরিয়েবল উপলব্ধ:

```php
$id                 // প্রোডাক্ট আইডি
$product            // WC_Product অবজেক্ট
$table_ID           // টেবিল পোস্ট আইডি
$column_settings    // কলাম সেটিংস
$config_value       // টেবিল কনফিগ
$data               // প্রোডাক্ট ডেটা অ্যারে
$args               // শর্টকোড আর্গুমেন্ট
// ... আরও অনেক
```

## পারফরম্যান্স অপটিমাইজেশন

### ক্যাশিং

```php
// Transient API ব্যবহার করা
$cache_key = 'wpt_products_' . $table_id . '_' . $page;
$products = get_transient( $cache_key );

if ( false === $products ) {
    $products = $this->get_products();
    set_transient( $cache_key, $products, 12 * HOUR_IN_SECONDS );
}
```

### লেজি লোডিং

শুধুমাত্র প্রয়োজনীয় ফাইল লোড করা:

```php
// শুধু ফ্রন্টএন্ডে শর্টকোড লোড করা
if ( ! is_admin() ) {
    new WOO_PRODUCT_TABLE\Inc\Shortcode();
}

// শুধু অ্যাডমিনে অ্যাডমিন ক্লাস লোড করা
if ( is_admin() ) {
    new WOO_PRODUCT_TABLE\Admin\Admin_Loader();
}
```

## সিকিউরিটি

### Nonce ভেরিফিকেশন

```php
// AJAX রিকোয়েস্টে
check_ajax_referer( 'wpt_ajax_nonce', 'nonce' );

// ফর্ম সাবমিটে
wp_verify_nonce( $_POST['_wpnonce'], 'wpt_save_table' );
```

### ডেটা সেনিটাইজেশন

```php
// ইনপুট সেনিটাইজ করা
$table_id = intval( $_POST['table_id'] );
$text = sanitize_text_field( $_POST['text'] );
$html = wp_kses_post( $_POST['html'] );

// আউটপুট এস্কেপ করা
echo esc_html( $text );
echo esc_url( $url );
echo esc_attr( $attribute );
```

### ক্যাপাবিলিটি চেক

```php
// পারমিশন চেক করা
if ( ! current_user_can( 'manage_wpt_product_table' ) ) {
    wp_die( 'Unauthorized access' );
}
```

## পরবর্তী ধাপ

- [নতুন ফিচার যোগ করা](05-adding-features.md)
- [কাস্টম কলাম তৈরি](06-custom-column.md)
- [হুক রেফারেন্স](07-hooks-reference.md)
