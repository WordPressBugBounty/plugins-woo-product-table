# কাস্টম কলাম তৈরি - সম্পূর্ণ টিউটোরিয়াল

এই গাইডে আপনি শিখবেন কিভাবে Woo Product Table এ নতুন কাস্টম কলাম তৈরি করবেন।

## কলাম সিস্টেম কিভাবে কাজ করে

### প্রক্রিয়া

1. **কলাম রেজিস্ট্রার করা** - ডিফল্ট কলাম লিস্টে যোগ করা
2. **টেমপ্লেট ফাইল তৈরি** - কলামের HTML তৈরি করা
3. **সেটিংস ফর্ম তৈরি** - অ্যাডমিন প্যানেলে কলাম সেটিংস
4. **CSS/JS যোগ করা** - প্রয়োজন অনুযায়ী স্টাইল এবং স্ক্রিপ্ট

## উদাহরণ ১: সিম্পল কলাম - প্রোডাক্ট ভিউ কাউন্ট

একটি সহজ কলাম যা প্রোডাক্ট কতবার দেখা হয়েছে তা দেখাবে।

### ধাপ ১: কলাম রেজিস্ট্রার করুন

**ফাইল তৈরি করুন:** `inc/features/view-count-column.php`

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class View_Count_Column {
    
    public function __construct() {
        // কলাম যোগ করুন
        add_filter( 'wpto_default_column_arr', array( $this, 'add_column' ) );
        
        // টেমপ্লেট লোকেশন সেট করুন
        add_filter( 'wpto_template_loc_item_view_count', array( $this, 'template_location' ) );
        
        // সেটিংস ফর্ম যোগ করুন
        add_action( 'wpto_column_setting_form_view_count', array( $this, 'settings_form' ), 10, 2 );
    }
    
    /**
     * কলাম লিস্টে যোগ করুন
     */
    public function add_column( $columns ) {
        $columns['view_count'] = 'ভিউ কাউন্ট';
        return $columns;
    }
    
    /**
     * টেমপ্লেট ফাইলের লোকেশন
     */
    public function template_location( $file ) {
        return WPT_BASE_DIR . 'inc/features/templates/view-count.php';
    }
    
    /**
     * অ্যাডমিন সেটিংস ফর্ম
     */
    public function settings_form( $device_name, $column_settings ) {
        $show_icon = isset( $column_settings['view_count']['show_icon'] ) ? 
                     $column_settings['view_count']['show_icon'] : 'yes';
        ?>
        <tr>
            <td>
                <label>আইকন দেখান?</label>
            </td>
            <td>
                <select name="column_settings<?php echo esc_attr( $device_name ); ?>[view_count][show_icon]">
                    <option value="yes" <?php selected( $show_icon, 'yes' ); ?>>হ্যাঁ</option>
                    <option value="no" <?php selected( $show_icon, 'no' ); ?>>না</option>
                </select>
            </td>
        </tr>
        <?php
    }
}

// Initialize
new View_Count_Column();
```

**লোড করুন:** `inc/features/basics.php` তে

```php
require_once WPT_BASE_DIR . 'inc/features/view-count-column.php';
```

### ধাপ ২: টেমপ্লেট ফাইল তৈরি করুন

**ফাইল তৈরি করুন:** `inc/features/templates/view-count.php`

```php
<?php
/**
 * View Count Column Template
 * 
 * Available Variables:
 * @var int    $id                 Product ID
 * @var object $product            WC_Product Object
 * @var int    $table_ID           Table Post ID
 * @var array  $column_settings    Column Settings
 * @var array  $config_value       Table Config
 */

// ভিউ কাউন্ট পাওয়া
$view_count = get_post_meta( $id, '_wpt_view_count', true );
$view_count = $view_count ? intval( $view_count ) : 0;

// সেটিংস পাওয়া
$show_icon = isset( $column_settings['view_count']['show_icon'] ) ? 
             $column_settings['view_count']['show_icon'] : 'yes';

?>
<div class="wpt-view-count">
    <?php if ( $show_icon === 'yes' ) : ?>
        <span class="dashicons dashicons-visibility"></span>
    <?php endif; ?>
    <span class="count"><?php echo esc_html( $view_count ); ?></span>
</div>
```

### ধাপ ৩: ভিউ কাউন্ট ট্র্যাক করুন

**ফাইল:** `inc/features/view-count-column.php` তে যোগ করুন

```php
public function __construct() {
    // ... আগের কোড
    
    // প্রোডাক্ট পেজে ভিউ কাউন্ট করুন
    add_action( 'woocommerce_before_single_product', array( $this, 'track_view' ) );
}

/**
 * প্রোডাক্ট ভিউ ট্র্যাক করুন
 */
public function track_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }
    
    $product_id = get_the_ID();
    $count = get_post_meta( $product_id, '_wpt_view_count', true );
    $count = $count ? intval( $count ) : 0;
    
    update_post_meta( $product_id, '_wpt_view_count', $count + 1 );
}
```

## উদাহরণ ২: অ্যাডভান্সড কলাম - কাস্টম বাটন

একটি কলাম যেখানে কাস্টম বাটন থাকবে।

### ধাপ ১: কলাম রেজিস্ট্রার

**ফাইল তৈরি করুন:** `inc/features/custom-button-column.php`

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class Custom_Button_Column {
    
    public function __construct() {
        add_filter( 'wpto_default_column_arr', array( $this, 'add_column' ) );
        add_filter( 'wpto_template_loc_item_custom_button', array( $this, 'template_location' ) );
        add_action( 'wpto_column_setting_form_custom_button', array( $this, 'settings_form' ), 10, 2 );
    }
    
    public function add_column( $columns ) {
        $columns['custom_button'] = 'কাস্টম বাটন';
        return $columns;
    }
    
    public function template_location( $file ) {
        return WPT_BASE_DIR . 'inc/features/templates/custom-button.php';
    }
    
    public function settings_form( $device_name, $column_settings ) {
        $button_text = isset( $column_settings['custom_button']['text'] ) ? 
                       $column_settings['custom_button']['text'] : 'বিস্তারিত দেখুন';
        
        $button_url = isset( $column_settings['custom_button']['url'] ) ? 
                      $column_settings['custom_button']['url'] : '';
        
        $button_color = isset( $column_settings['custom_button']['color'] ) ? 
                        $column_settings['custom_button']['color'] : '#0073aa';
        ?>
        <tr>
            <td><label>বাটন টেক্সট</label></td>
            <td>
                <input type="text" 
                       name="column_settings<?php echo esc_attr( $device_name ); ?>[custom_button][text]" 
                       value="<?php echo esc_attr( $button_text ); ?>" 
                       class="regular-text">
            </td>
        </tr>
        <tr>
            <td><label>কাস্টম URL (অপশনাল)</label></td>
            <td>
                <input type="url" 
                       name="column_settings<?php echo esc_attr( $device_name ); ?>[custom_button][url]" 
                       value="<?php echo esc_attr( $button_url ); ?>" 
                       class="regular-text">
                <p class="description">খালি রাখলে প্রোডাক্ট লিংক ব্যবহার হবে</p>
            </td>
        </tr>
        <tr>
            <td><label>বাটন কালার</label></td>
            <td>
                <input type="color" 
                       name="column_settings<?php echo esc_attr( $device_name ); ?>[custom_button][color]" 
                       value="<?php echo esc_attr( $button_color ); ?>">
            </td>
        </tr>
        <?php
    }
}

new Custom_Button_Column();
```

### ধাপ ২: টেমপ্লেট ফাইল

**ফাইল তৈরি করুন:** `inc/features/templates/custom-button.php`

```php
<?php
/**
 * Custom Button Column Template
 */

// সেটিংস পাওয়া
$settings = isset( $column_settings['custom_button'] ) ? $column_settings['custom_button'] : array();

$button_text = isset( $settings['text'] ) ? $settings['text'] : 'বিস্তারিত দেখুন';
$button_url = isset( $settings['url'] ) ? $settings['url'] : '';
$button_color = isset( $settings['color'] ) ? $settings['color'] : '#0073aa';

// URL নির্ধারণ করুন
if ( empty( $button_url ) ) {
    $button_url = get_permalink( $id );
}

// প্লেসহোল্ডার রিপ্লেস করুন
$button_url = str_replace( '{product_id}', $id, $button_url );
$button_url = str_replace( '{product_sku}', $product->get_sku(), $button_url );

?>
<div class="wpt-custom-button-wrapper">
    <a href="<?php echo esc_url( $button_url ); ?>" 
       class="wpt-custom-button" 
       style="background-color: <?php echo esc_attr( $button_color ); ?>;">
        <?php echo esc_html( $button_text ); ?>
    </a>
</div>

<style>
.wpt-custom-button {
    display: inline-block;
    padding: 8px 20px;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: opacity 0.3s;
}
.wpt-custom-button:hover {
    opacity: 0.8;
}
</style>
```

## উদাহরণ ৩: ডাইনামিক কলাম - শর্টকোড সাপোর্ট

একটি কলাম যেখানে যেকোনো শর্টকোড চালানো যাবে।

### কলাম রেজিস্ট্রেশন

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class Shortcode_Column {
    
    public function __construct() {
        add_filter( 'wpto_default_column_arr', array( $this, 'add_column' ) );
        add_filter( 'wpto_template_loc_item_shortcode', array( $this, 'template_location' ) );
        add_action( 'wpto_column_setting_form_shortcode', array( $this, 'settings_form' ), 10, 2 );
    }
    
    public function add_column( $columns ) {
        $columns['shortcode'] = 'শর্টকোড';
        return $columns;
    }
    
    public function template_location( $file ) {
        return WPT_BASE_DIR . 'inc/features/templates/shortcode.php';
    }
    
    public function settings_form( $device_name, $column_settings ) {
        $shortcode = isset( $column_settings['shortcode']['code'] ) ? 
                     $column_settings['shortcode']['code'] : '';
        ?>
        <tr>
            <td><label>শর্টকোড</label></td>
            <td>
                <textarea name="column_settings<?php echo esc_attr( $device_name ); ?>[shortcode][code]" 
                          class="large-text" 
                          rows="3"><?php echo esc_textarea( $shortcode ); ?></textarea>
                <p class="description">
                    প্লেসহোল্ডার: {product_id}, {product_sku}, {product_title}<br>
                    উদাহরণ: [my_shortcode id="{product_id}"]
                </p>
            </td>
        </tr>
        <?php
    }
}

new Shortcode_Column();
```

### টেমপ্লেট ফাইল

```php
<?php
/**
 * Shortcode Column Template
 */

$settings = isset( $column_settings['shortcode'] ) ? $column_settings['shortcode'] : array();
$shortcode = isset( $settings['code'] ) ? $settings['code'] : '';

if ( empty( $shortcode ) ) {
    echo '<span class="wpt-no-shortcode">শর্টকোড সেট করা হয়নি</span>';
    return;
}

// প্লেসহোল্ডার রিপ্লেস করুন
$shortcode = str_replace( '{product_id}', $id, $shortcode );
$shortcode = str_replace( '{product_sku}', $product->get_sku(), $shortcode );
$shortcode = str_replace( '{product_title}', $product->get_title(), $shortcode );

// শর্টকোড এক্সিকিউট করুন
echo do_shortcode( $shortcode );
```

## উদাহরণ ৪: ইন্টারেক্টিভ কলাম - Ajax উইশলিস্ট

Ajax সহ উইশলিস্ট বাটন।

### PHP কোড

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class Wishlist_Column {
    
    public function __construct() {
        add_filter( 'wpto_default_column_arr', array( $this, 'add_column' ) );
        add_filter( 'wpto_template_loc_item_wishlist_btn', array( $this, 'template_location' ) );
        
        // Ajax হ্যান্ডলার
        add_action( 'wp_ajax_wpt_toggle_wishlist', array( $this, 'toggle_wishlist' ) );
        add_action( 'wp_ajax_nopriv_wpt_toggle_wishlist', array( $this, 'toggle_wishlist' ) );
    }
    
    public function add_column( $columns ) {
        $columns['wishlist_btn'] = 'উইশলিস্ট';
        return $columns;
    }
    
    public function template_location( $file ) {
        return WPT_BASE_DIR . 'inc/features/templates/wishlist-btn.php';
    }
    
    public function toggle_wishlist() {
        check_ajax_referer( 'wpt_ajax_nonce', 'nonce' );
        
        $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        $user_id = get_current_user_id();
        
        if ( ! $user_id ) {
            wp_send_json_error( array( 'message' => 'লগইন করুন' ) );
        }
        
        // উইশলিস্ট পাওয়া
        $wishlist = get_user_meta( $user_id, '_wpt_wishlist', true );
        $wishlist = $wishlist ? $wishlist : array();
        
        // টগল করুন
        if ( in_array( $product_id, $wishlist ) ) {
            $wishlist = array_diff( $wishlist, array( $product_id ) );
            $status = 'removed';
        } else {
            $wishlist[] = $product_id;
            $status = 'added';
        }
        
        // আপডেট করুন
        update_user_meta( $user_id, '_wpt_wishlist', $wishlist );
        
        wp_send_json_success( array(
            'status' => $status,
            'count' => count( $wishlist )
        ) );
    }
}

new Wishlist_Column();
```

### টেমপ্লেট ফাইল

```php
<?php
/**
 * Wishlist Button Template
 */

$user_id = get_current_user_id();
$wishlist = $user_id ? get_user_meta( $user_id, '_wpt_wishlist', true ) : array();
$in_wishlist = is_array( $wishlist ) && in_array( $id, $wishlist );

?>
<div class="wpt-wishlist-wrapper">
    <button class="wpt-wishlist-btn <?php echo $in_wishlist ? 'active' : ''; ?>" 
            data-product-id="<?php echo esc_attr( $id ); ?>"
            data-nonce="<?php echo wp_create_nonce( 'wpt_ajax_nonce' ); ?>">
        <span class="heart-icon">
            <?php echo $in_wishlist ? '❤️' : '🤍'; ?>
        </span>
        <span class="text">
            <?php echo $in_wishlist ? 'উইশলিস্টে আছে' : 'উইশলিস্টে যোগ করুন'; ?>
        </span>
    </button>
</div>

<script>
jQuery(document).ready(function($) {
    $('.wpt-wishlist-btn').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var productId = $btn.data('product-id');
        var nonce = $btn.data('nonce');
        
        $.ajax({
            url: wpt_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wpt_toggle_wishlist',
                product_id: productId,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.status === 'added') {
                        $btn.addClass('active');
                        $btn.find('.heart-icon').text('❤️');
                        $btn.find('.text').text('উইশলিস্টে আছে');
                    } else {
                        $btn.removeClass('active');
                        $btn.find('.heart-icon').text('🤍');
                        $btn.find('.text').text('উইশলিস্টে যোগ করুন');
                    }
                }
            }
        });
    });
});
</script>

<style>
.wpt-wishlist-btn {
    border: 1px solid #ddd;
    background: #fff;
    padding: 8px 15px;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.3s;
}
.wpt-wishlist-btn:hover {
    background: #f5f5f5;
}
.wpt-wishlist-btn.active {
    background: #ffe6e6;
    border-color: #ff4444;
}
</style>
```

## কলাম তৈরির বেস্ট প্র্যাক্টিস

### ১. ইউনিক Keyword ব্যবহার করুন

```php
// ভাল ✅
$columns['my_custom_view_count'] = 'ভিউ কাউন্ট';

// খারাপ ❌
$columns['count'] = 'কাউন্ট'; // খুব সাধারণ, কনফ্লিক্ট হতে পারে
```

### ২. Settings সবসময় Validate করুন

```php
$button_text = isset( $column_settings['custom_button']['text'] ) ? 
               sanitize_text_field( $column_settings['custom_button']['text'] ) : 
               'ডিফল্ট টেক্সট';
```

### ৩. Template Variables চেক করুন

```php
if ( ! isset( $product ) || ! is_object( $product ) ) {
    return;
}
```

### ৪. CSS Scope করুন

```css
/* ভাল ✅ */
.wpt-my-column { }

/* খারাপ ❌ */
.column { } /* খুব সাধারণ, অন্য প্লাগিনের সাথে কনফ্লিক্ট */
```

### ৫. আলাদা ফাইল তৈরি করুন

```
inc/features/
├── my-column.php           # মূল ক্লাস
└── templates/
    └── my-column.php       # টেমপ্লেট
```

## টেস্টিং

### বিভিন্ন শর্তে টেস্ট করুন

- ✅ সিম্পল প্রোডাক্ট
- ✅ ভেরিয়েবল প্রোডাক্ট
- ✅ আউট অফ স্টক প্রোডাক্ট
- ✅ ডিসকাউন্ট সহ প্রোডাক্ট
- ✅ মোবাইল ডিভাইসে
- ✅ বিভিন্ন থিমে

## পরবর্তী ধাপ

- [হুক রেফারেন্স](07-hooks-reference.md)
- [সাধারণ কাজ](08-common-tasks.md)
