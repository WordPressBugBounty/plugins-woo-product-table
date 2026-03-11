# সাধারণ কাজের গাইড

এই ডকুমেন্টে সাধারণ কিছু কাজের ধাপে ধাপে সমাধান দেওয়া হয়েছে।

## টেবিল কাস্টমাইজেশন

### ১. টেবিলের ডিফল্ট কলাম পরিবর্তন করুন

**সমস্যা:** নতুন টেবিল তৈরি করলে ডিফল্টভাবে কিছু কলাম সিলেক্ট করতে চাই।

**সমাধান:**

```php
add_filter( 'wpt_default_enabled_columns', function( $columns ) {
    return array(
        'thumbnails',
        'product_title',
        'price',
        'stock',
        'action'
    );
});
```

### ২. নির্দিষ্ট ক্যাটাগরির প্রোডাক্ট লুকান

**সমস্যা:** "Uncategorized" ক্যাটাগরির প্রোডাক্ট টেবিলে দেখাতে চাই না।

**সমাধান:**

```php
add_filter( 'wpt_query_args', function( $args ) {
    $args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => 'uncategorized',
        'operator' => 'NOT IN'
    );
    return $args;
});
```

### ৩. টেবিলে প্রোডাক্ট সর্ট অর্ডার পরিবর্তন করুন

**সমস্যা:** ডিফল্ট সর্টিং পরিবর্তন করতে চাই।

**সমাধান:**

```php
add_filter( 'wpt_query_args', function( $args ) {
    // দামের ভিত্তিতে সর্ট (কম থেকে বেশি)
    $args['meta_key'] = '_price';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'ASC';
    return $args;
});
```

### ৪. টেবিলে ডিসকাউন্ট পার্সেন্টেজ দেখান

**সমস্যা:** সেলে থাকা প্রোডাক্টে কত ছাড় তা দেখাতে চাই।

**সমাধান:**

**ফাইল তৈরি করুন:** `functions.php` (থিমে)

```php
add_action( 'wpt_column_top', function( $keyword, $product ) {
    if ( $keyword === 'price' && $product->is_on_sale() ) {
        $regular = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        
        if ( $regular > 0 ) {
            $discount = round( ( ( $regular - $sale ) / $regular ) * 100 );
            echo '<span class="discount-badge">' . $discount . '% ছাড়</span>';
        }
    }
}, 10, 2 );
```

**CSS যোগ করুন:**

```css
.discount-badge {
    background: #ff4444;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    margin-left: 5px;
}
```

## প্রোডাক্ট ফিল্টারিং

### ৫. শুধুমাত্র স্টকে আছে প্রোডাক্ট দেখান

**সমাধান:**

```php
add_filter( 'wpt_meta_query', function( $meta_query ) {
    $meta_query[] = array(
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '='
    );
    return $meta_query;
});
```

### ৬. নির্দিষ্ট দামের রেঞ্জের প্রোডাক্ট দেখান

**সমস্যা:** শুধুমাত্র ১০০০-৫০০০ টাকার মধ্যে প্রোডাক্ট দেখাতে চাই।

**সমাধান:**

```php
add_filter( 'wpt_meta_query', function( $meta_query ) {
    $meta_query[] = array(
        'key' => '_price',
        'value' => array( 1000, 5000 ),
        'compare' => 'BETWEEN',
        'type' => 'NUMERIC'
    );
    return $meta_query;
});
```

### ৭. ফিচার্ড প্রোডাক্ট আগে দেখান

**সমাধান:**

```php
add_filter( 'wpt_query_args', function( $args ) {
    $args['meta_query'][] = array(
        'relation' => 'OR',
        array(
            'key' => '_featured',
            'value' => 'yes',
            'compare' => '='
        ),
        array(
            'key' => '_featured',
            'compare' => 'NOT EXISTS'
        )
    );
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = '_featured';
    
    return $args;
});
```

## স্টাইলিং

### ৮. টেবিল হেডার রং পরিবর্তন করুন

**সমাধান:**

থিমের `style.css` বা Custom CSS এ:

```css
.wpt-product-table thead {
    background: #2c3e50 !important;
    color: white !important;
}

.wpt-product-table thead th {
    border-color: #34495e !important;
}
```

### ৯. জোড় এবং বিজোড় রো তে ভিন্ন রং

**সমাধান:**

```css
.wpt-product-table tbody tr:nth-child(odd) {
    background: #f9f9f9;
}

.wpt-product-table tbody tr:nth-child(even) {
    background: #ffffff;
}
```

### ১০. মোবাইলে টেবিল স্টাইল পরিবর্তন করুন

**সমাধান:**

```css
@media (max-width: 768px) {
    .wpt-product-table {
        font-size: 12px;
    }
    
    .wpt-product-table td,
    .wpt-product-table th {
        padding: 8px 4px;
    }
    
    .wpt-product-table .product-thumbnail img {
        max-width: 50px;
    }
}
```

## কাস্টম ফিল্ড এবং ডেটা

### ১১. ACF কাস্টম ফিল্ড দেখান

**সমস্যা:** ACF প্লাগিন দিয়ে তৈরি কাস্টম ফিল্ড টেবিলে দেখাতে চাই।

**সমাধান:**

**ফাইল:** `includes/items/custom_field.php` (ইতিমধ্যে আছে)

এই ফাইল ব্যবহার করে ACF ফিল্ড দেখানো যায়। কলাম সেটিংসে ফিল্ডের নাম দিন।

**অথবা নতুন কলাম তৈরি করুন:**

```php
add_filter( 'wpto_default_column_arr', function( $columns ) {
    $columns['my_acf_field'] = 'আমার ফিল্ড';
    return $columns;
});

add_filter( 'wpto_template_loc_item_my_acf_field', function( $file ) {
    return __DIR__ . '/my-acf-field.php';
});
```

**টেমপ্লেট ফাইল:** `my-acf-field.php`

```php
<?php
$field_value = get_field( 'my_field_name', $id );
if ( $field_value ) {
    echo esc_html( $field_value );
} else {
    echo '-';
}
```

### ১২. প্রোডাক্ট ট্যাগ কাস্টমাইজ করুন

**সমস্যা:** ট্যাগের স্টাইল পরিবর্তন করতে চাই।

**সমাধান:**

```php
// functions.php
add_filter( 'wpt_product_tags_html', function( $html, $product ) {
    $tags = get_the_terms( $product->get_id(), 'product_tag' );
    
    if ( ! $tags || is_wp_error( $tags ) ) {
        return '';
    }
    
    $output = '<div class="custom-tags">';
    foreach ( $tags as $tag ) {
        $output .= '<span class="tag" style="background: #e74c3c; color: white; padding: 3px 8px; margin: 2px; border-radius: 3px;">';
        $output .= esc_html( $tag->name );
        $output .= '</span>';
    }
    $output .= '</div>';
    
    return $output;
}, 10, 2 );
```

## পেজিনেশন

### ১৩. প্রতি পেজে ডিফল্ট প্রোডাক্ট সংখ্যা পরিবর্তন করুন

**সমাধান:**

```php
add_filter( 'wpt_posts_per_page', function( $per_page, $table_id ) {
    return 50; // ডিফল্ট 50 প্রোডাক্ট
}, 10, 2 );
```

### ১৪. কাস্টম পেজিনেশন স্টাইল

**সমাধান:**

```css
.wpt-pagination {
    text-align: center;
    margin: 20px 0;
}

.wpt-pagination a,
.wpt-pagination span {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 2px;
    background: #f5f5f5;
    color: #333;
    border-radius: 4px;
    text-decoration: none;
}

.wpt-pagination a:hover {
    background: #0073aa;
    color: white;
}

.wpt-pagination .current {
    background: #0073aa;
    color: white;
}
```

## সার্চ এবং ফিল্টার

### ১৫. সার্চে SKU যোগ করুন

**সমাধান:**

```php
add_filter( 'wpt_search_fields', function( $fields ) {
    $fields[] = '_sku';
    return $fields;
});
```

### ১৬. ক্যাটাগরি ড্রপডাউন ফিল্টার যোগ করুন

**সমাধান:**

```php
add_action( 'wpt_before_table', function( $table_id ) {
    $categories = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ) );
    
    if ( ! empty( $categories ) ) {
        ?>
        <div class="wpt-category-filter">
            <label>ক্যাটাগরি:</label>
            <select id="wpt-cat-filter-<?php echo esc_attr( $table_id ); ?>">
                <option value="">সব ক্যাটাগরি</option>
                <?php foreach ( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->slug ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#wpt-cat-filter-<?php echo $table_id; ?>').on('change', function() {
                var cat = $(this).val();
                var url = window.location.href.split('?')[0];
                if (cat) {
                    window.location.href = url + '?product_cat=' + cat;
                } else {
                    window.location.href = url;
                }
            });
        });
        </script>
        <?php
    }
});
```

## পারফরম্যান্স অপটিমাইজেশন

### ১৭. টেবিল ডেটা ক্যাশ করুন

**সমাধান:**

```php
add_filter( 'wpt_query_args', function( $args, $table_id ) {
    // ক্যাশ কী তৈরি করুন
    $cache_key = 'wpt_products_' . $table_id . '_' . md5( serialize( $args ) );
    
    // ক্যাশ থেকে পাওয়ার চেষ্টা করুন
    $cached = get_transient( $cache_key );
    
    if ( false !== $cached ) {
        return $cached;
    }
    
    // ক্যাশ সেট করুন (১ ঘণ্টার জন্য)
    set_transient( $cache_key, $args, HOUR_IN_SECONDS );
    
    return $args;
}, 10, 2 );
```

### ১৮. ইমেজ লেজি লোডিং চালু করুন

**সমাধান:**

```php
add_filter( 'wpt_product_image_attributes', function( $attr ) {
    $attr['loading'] = 'lazy';
    return $attr;
});
```

## ইন্টিগ্রেশন

### ১৯. YITH Wishlist প্লাগিনের সাথে

**সমাধান:**

```php
add_action( 'wpt_column_top', function( $keyword, $product ) {
    if ( $keyword === 'action' && function_exists( 'yith_wcwl_get_wishlist_url' ) ) {
        echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]' );
    }
}, 10, 2 );
```

### ২০. WooCommerce Compare প্লাগিনের সাথে

**সমাধান:**

```php
add_action( 'wpt_column_top', function( $keyword, $product ) {
    if ( $keyword === 'action' && class_exists( 'YITH_Woocompare' ) ) {
        ?>
        <a href="?action=yith-woocompare-add-product&id=<?php echo $product->get_id(); ?>" 
           class="compare-btn">
            তুলনা করুন
        </a>
        <?php
    }
}, 10, 2 );
```

## ইউজার রোল ভিত্তিক

### ২১. নির্দিষ্ট ইউজার রোলের জন্য দাম লুকান

**সমাধান:**

```php
add_filter( 'wpt_show_price_column', function( $show, $product ) {
    // গেস্ট ইউজারদের জন্য দাম লুকান
    if ( ! is_user_logged_in() ) {
        return false;
    }
    
    // শুধু হোলসেল ইউজারদের জন্য দেখান
    if ( ! current_user_can( 'wholesale_customer' ) ) {
        return false;
    }
    
    return $show;
}, 10, 2 );
```

### ২২. লগইন ইউজারদের জন্য স্পেশাল প্রাইস

**সমাধান:**

```php
add_filter( 'wpt_product_price', function( $price, $product ) {
    if ( is_user_logged_in() ) {
        $regular_price = $product->get_regular_price();
        $special_price = $regular_price * 0.9; // 10% ছাড়
        
        return wc_price( $special_price ) . ' <small>(মেম্বার প্রাইস)</small>';
    }
    return $price;
}, 10, 2 );
```

## এক্সপোর্ট এবং ইম্পোর্ট

### ২৩. CSV এ টেবিল ডেটা এক্সপোর্ট করুন

**সমাধান:**

```php
add_action( 'wpt_after_table', function( $table_id ) {
    ?>
    <a href="<?php echo admin_url( 'admin-ajax.php?action=wpt_export_csv&table_id=' . $table_id ); ?>" 
       class="export-btn">
        📥 CSV ডাউনলোড করুন
    </a>
    <?php
});

// AJAX হ্যান্ডলার
add_action( 'wp_ajax_wpt_export_csv', 'wpt_export_csv_handler' );
function wpt_export_csv_handler() {
    $table_id = isset( $_GET['table_id'] ) ? intval( $_GET['table_id'] ) : 0;
    
    // CSV হেডার
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=products.csv' );
    
    $output = fopen( 'php://output', 'w' );
    
    // হেডার রো
    fputcsv( $output, array( 'ID', 'Title', 'Price', 'SKU' ) );
    
    // প্রোডাক্ট ডেটা
    $products = wc_get_products( array( 'limit' => -1 ) );
    foreach ( $products as $product ) {
        fputcsv( $output, array(
            $product->get_id(),
            $product->get_name(),
            $product->get_price(),
            $product->get_sku(),
        ) );
    }
    
    fclose( $output );
    exit;
}
```

## ডিবাগিং

### ২৪. টেবিল কনফিগ ডিবাগ করুন

**সমাধান:**

```php
add_action( 'wpt_after_table', function( $table_id ) {
    if ( current_user_can( 'manage_options' ) ) {
        $config = get_post_meta( $table_id, 'config', true );
        echo '<pre style="background: #f5f5f5; padding: 10px; overflow: auto;">';
        echo 'Table Config:';
        print_r( $config );
        echo '</pre>';
    }
});
```

### ২৫. WP_Query ডিবাগ করুন

**সমাধান:**

```php
add_action( 'wpt_after_query', function( $query, $table_id ) {
    if ( current_user_can( 'manage_options' ) && isset( $_GET['debug'] ) ) {
        echo '<pre>';
        echo 'Query Args:';
        print_r( $query->query_vars );
        echo 'Found Products: ' . $query->found_posts;
        echo '</pre>';
    }
}, 10, 2 );
```

## পরবর্তী ধাপ

- [ট্রাবলশুটিং](09-troubleshooting.md)
- [উন্নতি এবং সাজেশন](10-improvements-suggestions.md)
