# হুক এবং ফিল্টার রেফারেন্স

Woo Product Table প্লাগিনে অনেক হুক (Filter এবং Action) রয়েছে যা দিয়ে প্লাগিনের ফাংশনালিটি এক্সটেন্ড করা যায়।

## Filter Hooks

### কলাম সম্পর্কিত

#### wpto_default_column_arr

নতুন কলাম যোগ করার জন্য।

**প্যারামিটার:**
- `$columns` (array) - কলামের অ্যারে

**উদাহরণ:**
```php
add_filter( 'wpto_default_column_arr', function( $columns ) {
    $columns['my_column'] = 'আমার কলাম';
    return $columns;
});
```

#### wpto_template_loc_item_{keyword}

কলামের টেমপ্লেট ফাইল লোকেশন পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$file_path` (string) - টেমপ্লেট ফাইলের পাথ
- `{keyword}` - কলামের keyword (যেমন: product_title, price)

**উদাহরণ:**
```php
add_filter( 'wpto_template_loc_item_price', function( $file ) {
    return __DIR__ . '/templates/my-custom-price.php';
});
```

#### wpt_avialable_variables

টেমপ্লেট ফাইলে পাঠানো ভেরিয়েবল কাস্টমাইজ করার জন্য।

**প্যারামিটার:**
- `$variables` (array) - ভেরিয়েবলের অ্যারে

**উদাহরণ:**
```php
add_filter( 'wpt_avialable_variables', function( $variables ) {
    $variables['my_custom_data'] = 'আমার ডেটা';
    return $variables;
});
```

### টেবিল সম্পর্কিত

#### wpt_table_class

টেবিলের CSS ক্লাস যোগ করার জন্য।

**প্যারামিটার:**
- `$classes` (array) - CSS ক্লাসের অ্যারে
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_filter( 'wpt_table_class', function( $classes, $table_id ) {
    $classes[] = 'my-custom-table';
    return $classes;
}, 10, 2 );
```

#### wpt_table_html

সম্পূর্ণ টেবিল HTML পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$html` (string) - টেবিল HTML
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_filter( 'wpt_table_html', function( $html, $table_id ) {
    $html = '<div class="custom-wrapper">' . $html . '</div>';
    return $html;
}, 10, 2 );
```

#### wpt_pagination_html

পেজিনেশন HTML পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$html` (string) - পেজিনেশন HTML
- `$total_pages` (int) - মোট পেজ সংখ্যা
- `$current_page` (int) - বর্তমান পেজ

**উদাহরণ:**
```php
add_filter( 'wpt_pagination_html', function( $html, $total, $current ) {
    // কাস্টম পেজিনেশন তৈরি করুন
    return $html;
}, 10, 3 );
```

### কোয়েরি সম্পর্কিত

#### wpt_query_args

WP_Query আর্গুমেন্ট পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$args` (array) - WP_Query আর্গুমেন্ট
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_filter( 'wpt_query_args', function( $args, $table_id ) {
    // শুধু ফিচার্ড প্রোডাক্ট দেখান
    $args['meta_query'][] = array(
        'key' => '_featured',
        'value' => 'yes'
    );
    return $args;
}, 10, 2 );
```

#### wpt_meta_query

মেটা কোয়েরি পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$meta_query` (array) - মেটা কোয়েরি অ্যারে

**উদাহরণ:**
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

#### wpt_tax_query

ট্যাক্সোনমি কোয়েরি পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$tax_query` (array) - ট্যাক্স কোয়েরি অ্যারে

**উদাহরণ:**
```php
add_filter( 'wpt_tax_query', function( $tax_query ) {
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => 'electronics'
    );
    return $tax_query;
});
```

### প্রোডাক্ট সম্পর্কিত

#### wpt_product_price

প্রোডাক্টের দাম ফরম্যাট করার জন্য।

**প্যারামিটার:**
- `$price` (string) - ফরম্যাট করা দাম
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট

**উদাহরণ:**
```php
add_filter( 'wpt_product_price', function( $price, $product ) {
    // দামের পরে "টাকা" যোগ করুন
    $price .= ' টাকা';
    return $price;
}, 10, 2 );
```

#### wpt_add_to_cart_text

"Add to Cart" বাটনের টেক্সট পরিবর্তন করার জন্য।

**প্যারামিটার:**
- `$text` (string) - বাটন টেক্সট
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট

**উদাহরণ:**
```php
add_filter( 'wpt_add_to_cart_text', function( $text, $product ) {
    if ( $product->is_on_sale() ) {
        return 'এখনই কিনুন 🔥';
    }
    return $text;
}, 10, 2 );
```

### সার্চ এবং ফিল্টার

#### wpt_search_fields

সার্চ করার সময় কোন ফিল্ডে সার্চ হবে তা নির্ধারণ করার জন্য।

**প্যারামিটার:**
- `$fields` (array) - সার্চ ফিল্ডের লিস্ট

**উদাহরণ:**
```php
add_filter( 'wpt_search_fields', function( $fields ) {
    $fields[] = 'sku';
    $fields[] = 'excerpt';
    return $fields;
});
```

## Action Hooks

### টেবিল রেন্ডারিং

#### wpt_before_table

টেবিলের আগে কিছু যোগ করার জন্য।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_before_table', function( $table_id ) {
    echo '<div class="custom-notice">বিশেষ অফার চলছে!</div>';
});
```

#### wpt_after_table

টেবিলের পরে কিছু যোগ করার জন্য।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_after_table', function( $table_id ) {
    echo '<div class="table-footer">আরও পণ্য দেখুন</div>';
});
```

#### wpt_before_thead

টেবিল হেডারের আগে।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_before_thead', function( $table_id ) {
    // কিছু করুন
});
```

#### wpt_after_thead

টেবিল হেডারের পরে।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_after_thead', function( $table_id ) {
    // কিছু করুন
});
```

### রো রেন্ডারিং

#### wpt_before_row

প্রতিটি প্রোডাক্ট রো এর আগে।

**প্যারামিটার:**
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_before_row', function( $product, $table_id ) {
    if ( $product->is_on_sale() ) {
        echo '<span class="sale-badge">সেল</span>';
    }
}, 10, 2 );
```

#### wpt_after_row

প্রতিটি প্রোডাক্ট রো এর পরে।

**প্যারামিটার:**
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_after_row', function( $product, $table_id ) {
    // কিছু করুন
}, 10, 2 );
```

### কলাম রেন্ডারিং

#### wpt_column_top

প্রতিটি কলামের শুরুতে।

**প্যারামিটার:**
- `$keyword` (string) - কলাম keyword
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_column_top', function( $keyword, $product, $table_id ) {
    if ( $keyword === 'price' && $product->is_on_sale() ) {
        echo '<span class="discount-label">ছাড়</span>';
    }
}, 10, 3 );
```

#### wpt_column_bottom

প্রতিটি কলামের শেষে।

**প্যারামিটার:**
- `$keyword` (string) - কলাম keyword
- `$product` (WC_Product) - প্রোডাক্ট অবজেক্ট
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_column_bottom', function( $keyword, $product, $table_id ) {
    // কিছু করুন
}, 10, 3 );
```

#### wpto_column_setting_form_{keyword}

অ্যাডমিন প্যানেলে কলাম সেটিংস ফর্ম।

**প্যারামিটার:**
- `$device_name` (string) - ডিভাইস নাম (desktop/mobile)
- `$column_settings` (array) - কলাম সেটিংস

**উদাহরণ:**
```php
add_action( 'wpto_column_setting_form_price', function( $device, $settings ) {
    ?>
    <tr>
        <td>আমার কাস্টম সেটিং</td>
        <td><input type="text" name="my_setting"></td>
    </tr>
    <?php
}, 10, 2 );
```

### পেজিনেশন

#### wpt_before_pagination

পেজিনেশনের আগে।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_before_pagination', function( $table_id ) {
    echo '<div class="pagination-info">পেজ নেভিগেশন:</div>';
});
```

#### wpt_after_pagination

পেজিনেশনের পরে।

**প্যারামিটার:**
- `$table_id` (int) - টেবিল পোস্ট আইডি

**উদাহরণ:**
```php
add_action( 'wpt_after_pagination', function( $table_id ) {
    // কিছু করুন
});
```

### অ্যাডমিন সম্পর্কিত

#### wpt_admin_after_save

টেবিল সংরক্ষণের পরে।

**প্যারামিটার:**
- `$post_id` (int) - টেবিল পোস্ট আইডি
- `$post` (WP_Post) - পোস্ট অবজেক্ট

**উদাহরণ:**
```php
add_action( 'wpt_admin_after_save', function( $post_id, $post ) {
    // ক্যাশ ক্লিয়ার করুন
    delete_transient( 'wpt_table_' . $post_id );
}, 10, 2 );
```

#### wpt_admin_before_tabs

অ্যাডমিন ট্যাব শুরুর আগে।

**প্যারামিটার:**
- `$post` (WP_Post) - পোস্ট অবজেক্ট

**উদাহরণ:**
```php
add_action( 'wpt_admin_before_tabs', function( $post ) {
    echo '<div class="admin-notice">নোটিস</div>';
});
```

#### wpt_admin_after_tabs

অ্যাডমিন ট্যাব শেষের পরে।

**প্যারামিটার:**
- `$post` (WP_Post) - পোস্ট অবজেক্ট

**উদাহরণ:**
```php
add_action( 'wpt_admin_after_tabs', function( $post ) {
    // কিছু করুন
});
```

## হুক ব্যবহারের উদাহরণ

### উদাহরণ ১: শুধুমাত্র স্টকে আছে এমন প্রোডাক্ট দেখান

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

### উদাহরণ ২: টেবিলে কাস্টম মেসেজ যোগ করুন

```php
add_action( 'wpt_before_table', function( $table_id ) {
    $notice = get_post_meta( $table_id, '_custom_notice', true );
    if ( $notice ) {
        echo '<div class="wpt-notice">' . esc_html( $notice ) . '</div>';
    }
});
```

### উদাহরণ ৩: প্রোডাক্ট টাইটেলে ব্যাজ যোগ করুন

```php
add_action( 'wpt_column_top', function( $keyword, $product, $table_id ) {
    if ( $keyword === 'product_title' ) {
        if ( $product->is_featured() ) {
            echo '<span class="featured-badge">ফিচার্ড</span>';
        }
        if ( $product->is_on_sale() ) {
            echo '<span class="sale-badge">সেল</span>';
        }
    }
}, 10, 3 );
```

### উদাহরণ ৪: কাস্টম CSS ক্লাস যোগ করুন

```php
add_filter( 'wpt_table_class', function( $classes, $table_id ) {
    // টেবিল টাইপ অনুযায়ী ক্লাস
    $table_type = get_post_meta( $table_id, 'table_type', true );
    if ( $table_type ) {
        $classes[] = 'table-type-' . $table_type;
    }
    
    // প্রিমিয়াম ইউজারের জন্য
    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        $classes[] = 'premium-user-table';
    }
    
    return $classes;
}, 10, 2 );
```

### উদাহরণ ৫: প্রোডাক্ট ডেটা এক্সপোর্ট করুন

```php
add_action( 'wpt_after_table', function( $table_id ) {
    ?>
    <button class="export-data" data-table="<?php echo esc_attr( $table_id ); ?>">
        ডেটা এক্সপোর্ট করুন
    </button>
    <script>
    jQuery('.export-data').on('click', function() {
        // AJAX দিয়ে ডেটা এক্সপোর্ট
    });
    </script>
    <?php
});
```

## হুক ডকুমেন্টেশন লিখুন

নতুন হুক তৈরি করলে ডকুমেন্ট করুন:

```php
/**
 * Filter product price display
 * 
 * @since 5.0.7
 * @param string     $price   Formatted price
 * @param WC_Product $product Product object
 * @param int        $table_id Table post ID
 * @return string Modified price
 */
$price = apply_filters( 'wpt_product_price', $price, $product, $table_id );
```

## পরবর্তী ধাপ

- [সাধারণ কাজ](08-common-tasks.md)
- [ট্রাবলশুটিং](09-troubleshooting.md)
