# নতুন ফিচার যোগ করা - স্টেপ বাই স্টেপ গাইড

এই ডকুমেন্টে আপনি শিখবেন কিভাবে Woo Product Table প্লাগিনে নতুন ফিচার যোগ করবেন।

## ফিচার যোগ করার সাধারণ প্রক্রিয়া

### ধাপ ১: পরিকল্পনা করুন

নতুন ফিচার তৈরি করার আগে নিচের প্রশ্নগুলোর উত্তর দিন:

- ✅ এই ফিচারটি কী করবে?
- ✅ কোন ইউজার এটি ব্যবহার করবে? (অ্যাডমিন/ফ্রন্টএন্ড ইউজার)
- ✅ এটি কোথায় দেখা যাবে? (অ্যাডমিন প্যানেল/ফ্রন্টএন্ড টেবিল)
- ✅ কোন ফাইলে কোড লিখতে হবে?
- ✅ কোন হুক ব্যবহার করতে হবে?

### ধাপ ২: Git ব্রাঞ্চ তৈরি করুন

```bash
cd /path/to/woo-product-table
git checkout -b feature/my-feature-name_from_5.0.6.3
```

**মনে রাখবেন:** ব্রাঞ্চের নামের শেষে `_from_5.0.6.3` অবশ্যই থাকতে হবে।

### ধাপ ৩: কোড লিখুন

নিচের উদাহরণগুলো দেখুন বিভিন্ন ধরনের ফিচারের জন্য।

### ধাপ ৪: টেস্ট করুন

- ফিচার কাজ করছে কিনা চেক করুন
- বিভিন্ন ব্রাউজারে টেস্ট করুন
- মোবাইলে দেখুন

### ধাপ ৫: কমিট এবং পুশ করুন

```bash
git add .
git commit -m "Added new feature: feature name"
git push origin feature/my-feature-name_from_5.0.6.3
```

## উদাহরণ ১: টেবিলে মোট দাম দেখানো

এই ফিচারে আমরা টেবিলের শেষে সব প্রোডাক্টের মোট দাম দেখাবো।

### কোড

**ফাইল তৈরি করুন:** `inc/features/total-price.php`

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class Total_Price {
    
    public function __construct() {
        add_action( 'wpt_after_table', array( $this, 'show_total_price' ), 10, 2 );
    }
    
    public function show_total_price( $products, $table_id ) {
        $total = 0;
        
        foreach ( $products as $product ) {
            $product_obj = wc_get_product( $product->ID );
            if ( $product_obj ) {
                $total += (float) $product_obj->get_price();
            }
        }
        
        ?>
        <div class="wpt-total-price">
            <strong>মোট দাম:</strong> 
            <?php echo wc_price( $total ); ?>
        </div>
        <?php
    }
}

// Initialize
new Total_Price();
```

**ফাইল লোড করুন:** `inc/features/basics.php` তে যোগ করুন

```php
require_once WPT_BASE_DIR . 'inc/features/total-price.php';
```

**CSS যোগ করুন:** `assets/css/wpt-frontend.css`

```css
.wpt-total-price {
    padding: 20px;
    background: #f9f9f9;
    border-top: 2px solid #333;
    text-align: right;
    font-size: 18px;
    margin-top: 10px;
}
```

## উদাহরণ ২: কাস্টম সার্চ ফিল্টার

টেবিলে একটি ক্যাটাগরি ফিল্টার যোগ করা।

### কোড

**ফাইল তৈরি করুন:** `inc/handle/category-filter.php`

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Handle;

class Category_Filter {
    
    public function __construct() {
        add_action( 'wpt_before_table', array( $this, 'render_filter' ), 5 );
        add_filter( 'wpt_query_args', array( $this, 'filter_by_category' ), 10, 2 );
    }
    
    public function render_filter( $table_id ) {
        // সেটিংস চেক করুন
        $enable = get_post_meta( $table_id, '_enable_category_filter', true );
        if ( ! $enable ) {
            return;
        }
        
        // সব ক্যাটাগরি পাওয়া
        $categories = get_terms( array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ) );
        
        if ( empty( $categories ) ) {
            return;
        }
        
        ?>
        <div class="wpt-category-filter">
            <label>ক্যাটাগরি ফিল্টার:</label>
            <select class="wpt-category-select" data-table="<?php echo esc_attr( $table_id ); ?>">
                <option value="">সব ক্যাটাগরি</option>
                <?php foreach ( $categories as $category ) : ?>
                    <option value="<?php echo esc_attr( $category->term_id ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    
    public function filter_by_category( $args, $table_id ) {
        // AJAX রিকোয়েস্ট চেক করুন
        if ( isset( $_POST['category_id'] ) && ! empty( $_POST['category_id'] ) ) {
            $category_id = intval( $_POST['category_id'] );
            
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            );
        }
        
        return $args;
    }
}

new Category_Filter();
```

**JavaScript যোগ করুন:** `assets/js/wpt-frontend.js`

```javascript
jQuery(document).ready(function($) {
    $('.wpt-category-select').on('change', function() {
        var categoryId = $(this).val();
        var tableId = $(this).data('table');
        
        // AJAX কল করুন
        $.ajax({
            url: wpt_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wpt_table_ajax',
                table_id: tableId,
                category_id: categoryId,
                nonce: wpt_ajax.nonce
            },
            success: function(response) {
                // টেবিল আপডেট করুন
                $('.wpt-table-wrapper-' + tableId).html(response.html);
            }
        });
    });
});
```

**অ্যাডমিন সেটিংস যোগ করুন:** `admin/tabs/search_n_filter.php` তে

```php
<tr>
    <th>
        <label>ক্যাটাগরি ফিল্টার চালু করুন</label>
    </th>
    <td>
        <input type="checkbox" 
               name="_enable_category_filter" 
               value="1" 
               <?php checked( get_post_meta( $post->ID, '_enable_category_filter', true ), '1' ); ?>>
    </td>
</tr>
```

## উদাহরণ ৩: এক্সপোর্ট টু CSV ফিচার

টেবিলের ডেটা CSV ফাইল হিসেবে ডাউনলোড করা।

### কোড

**ফাইল তৈরি করুন:** `inc/features/export-csv.php`

```php
<?php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class Export_CSV {
    
    public function __construct() {
        add_action( 'wpt_before_table', array( $this, 'render_button' ) );
        add_action( 'wp_ajax_wpt_export_csv', array( $this, 'export_csv' ) );
        add_action( 'wp_ajax_nopriv_wpt_export_csv', array( $this, 'export_csv' ) );
    }
    
    public function render_button( $table_id ) {
        $enable = get_post_meta( $table_id, '_enable_csv_export', true );
        if ( ! $enable ) {
            return;
        }
        
        ?>
        <div class="wpt-export-wrapper">
            <button class="wpt-export-csv" data-table="<?php echo esc_attr( $table_id ); ?>">
                📥 CSV ডাউনলোড করুন
            </button>
        </div>
        <?php
    }
    
    public function export_csv() {
        // Nonce ভেরিফাই করুন
        check_ajax_referer( 'wpt_ajax_nonce', 'nonce' );
        
        $table_id = isset( $_POST['table_id'] ) ? intval( $_POST['table_id'] ) : 0;
        if ( ! $table_id ) {
            wp_send_json_error( 'Invalid table ID' );
        }
        
        // প্রোডাক্ট কোয়েরি করুন
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
        );
        
        $products = get_posts( $args );
        
        // CSV হেডার সেট করুন
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=products-' . date('Y-m-d') . '.csv' );
        
        // আউটপুট স্ট্রিম খুলুন
        $output = fopen( 'php://output', 'w' );
        
        // হেডার রো
        fputcsv( $output, array( 'ID', 'Title', 'Price', 'SKU', 'Stock' ) );
        
        // ডেটা রো
        foreach ( $products as $product_post ) {
            $product = wc_get_product( $product_post->ID );
            
            fputcsv( $output, array(
                $product->get_id(),
                $product->get_name(),
                $product->get_price(),
                $product->get_sku(),
                $product->get_stock_status(),
            ) );
        }
        
        fclose( $output );
        exit;
    }
}

new Export_CSV();
```

**JavaScript:** `assets/js/wpt-frontend.js`

```javascript
jQuery(document).ready(function($) {
    $('.wpt-export-csv').on('click', function(e) {
        e.preventDefault();
        
        var tableId = $(this).data('table');
        var url = wpt_ajax.ajax_url + 
                  '?action=wpt_export_csv' +
                  '&table_id=' + tableId +
                  '&nonce=' + wpt_ajax.nonce;
        
        // নতুন উইন্ডোতে খুলুন (ডাউনলোড শুরু হবে)
        window.location.href = url;
    });
});
```

## উদাহরণ ৪: কাস্টম শর্টকোড প্যারামিটার

শর্টকোডে নতুন প্যারামিটার যোগ করা।

### লক্ষ্য

```
[Product_Table id='123' category='electronics' limit='5']
```

### কোড

**ফাইল:** `inc/shortcode.php` তে মডিফাই করুন

`product_table()` মেথডে:

```php
public function product_table( $atts ) {
    // নতুন প্যারামিটার যোগ করুন
    $this->atts = shortcode_atts( array(
        'id' => '',
        'name' => '',
        'category' => '',      // নতুন
        'limit' => '',         // নতুন
    ), $atts );
    
    // ... বাকি কোড
}
```

`get_products_query()` মেথডে:

```php
public function get_products_query() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $this->posts_per_page,
    );
    
    // ক্যাটাগরি ফিল্টার
    if ( ! empty( $this->atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $this->atts['category'],
            ),
        );
    }
    
    // লিমিট
    if ( ! empty( $this->atts['limit'] ) ) {
        $args['posts_per_page'] = intval( $this->atts['limit'] );
    }
    
    return new \WP_Query( $args );
}
```

## উদাহরণ ৫: কাস্টম স্টাইল অপশন

অ্যাডমিন প্যানেল থেকে টেবিলের স্টাইল কন্ট্রোল করা।

### অ্যাডমিন সেটিংস

**ফাইল:** `admin/tabs/table_style.php` তে যোগ করুন

```php
<tr>
    <th>
        <label>টেবিল বর্ডার কালার</label>
    </th>
    <td>
        <input type="color" 
               name="table_border_color" 
               value="<?php echo esc_attr( get_post_meta( $post->ID, 'table_border_color', true ) ?: '#cccccc' ); ?>">
    </td>
</tr>

<tr>
    <th>
        <label>হেডার ব্যাকগ্রাউন্ড কালার</label>
    </th>
    <td>
        <input type="color" 
               name="header_bg_color" 
               value="<?php echo esc_attr( get_post_meta( $post->ID, 'header_bg_color', true ) ?: '#333333' ); ?>">
    </td>
</tr>
```

### ফ্রন্টএন্ড CSS প্রয়োগ

**ফাইল:** `inc/handle/enqueue.php` এ যোগ করুন

```php
public function custom_table_css( $table_id ) {
    $border_color = get_post_meta( $table_id, 'table_border_color', true );
    $header_bg = get_post_meta( $table_id, 'header_bg_color', true );
    
    if ( ! $border_color && ! $header_bg ) {
        return;
    }
    
    ?>
    <style>
        .wpt-table-<?php echo esc_attr( $table_id ); ?> {
            <?php if ( $border_color ) : ?>
                border-color: <?php echo esc_attr( $border_color ); ?>;
            <?php endif; ?>
        }
        
        .wpt-table-<?php echo esc_attr( $table_id ); ?> thead {
            <?php if ( $header_bg ) : ?>
                background-color: <?php echo esc_attr( $header_bg ); ?>;
            <?php endif; ?>
        }
    </style>
    <?php
}

// হুক করুন
add_action( 'wpt_before_table', array( $this, 'custom_table_css' ) );
```

## বেস্ট প্র্যাক্টিস

### ১. সবসময় Nonce ব্যবহার করুন

```php
// ফর্ম সাবমিটে
wp_nonce_field( 'wpt_save_table', '_wpnonce' );

// ভেরিফাই করুন
if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wpt_save_table' ) ) {
    wp_die( 'Security check failed' );
}
```

### ২. ডেটা সেনিটাইজ করুন

```php
$text = sanitize_text_field( $_POST['text'] );
$email = sanitize_email( $_POST['email'] );
$url = esc_url_raw( $_POST['url'] );
$int = intval( $_POST['number'] );
```

### ৩. আউটপুট এস্কেপ করুন

```php
echo esc_html( $text );
echo esc_url( $url );
echo esc_attr( $attribute );
```

### ৪. Hooks ব্যবহার করুন

সরাসরি কোর ফাইল এডিট না করে হুক ব্যবহার করুন:

```php
// ভাল ✅
add_filter( 'wpt_table_class', function( $classes ) {
    $classes[] = 'my-custom-class';
    return $classes;
});

// খারাপ ❌
// কোর ফাইল এডিট করা
```

### ৫. Namespace ব্যবহার করুন

```php
namespace WOO_PRODUCT_TABLE\Inc\Features;

class My_Feature {
    // কোড
}
```

### ৬. কমেন্ট লিখুন

```php
/**
 * টেবিলে মোট দাম দেখানোর ফাংশন
 * 
 * @param array $products প্রোডাক্টের লিস্ট
 * @param int   $table_id টেবিল পোস্ট আইডি
 * @return void
 */
public function show_total_price( $products, $table_id ) {
    // কোড
}
```

## টেস্টিং চেকলিস্ট

নতুন ফিচার টেস্ট করার সময়:

- [ ] বিভিন্ন ব্রাউজারে টেস্ট করেছেন (Chrome, Firefox, Safari)
- [ ] মোবাইল ডিভাইসে টেস্ট করেছেন
- [ ] অ্যাডমিন প্যানেলে সব কাজ করছে
- [ ] ফ্রন্টএন্ডে সব কাজ করছে
- [ ] কোন JavaScript এরর নেই
- [ ] কোন PHP এরর নেই
- [ ] অন্য ফিচারে কোন প্রভাব পড়েনি
- [ ] পারফরম্যান্স ভালো আছে

## পরবর্তী ধাপ

- [কাস্টম কলাম তৈরি](06-custom-column.md)
- [হুক রেফারেন্স](07-hooks-reference.md)
- [সাধারণ কাজ](08-common-tasks.md)
