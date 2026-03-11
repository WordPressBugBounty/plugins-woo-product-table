# ট্রাবলশুটিং গাইড

এই ডকুমেন্টে সাধারণ সমস্যা এবং তাদের সমাধান দেওয়া হয়েছে।

## ইনস্টলেশন সমস্যা

### সমস্যা ১: প্লাগিন অ্যাক্টিভেট হচ্ছে না

**এরর মেসেজ:** "The plugin does not have a valid header."

**কারণ:**
- `woo-product-table.php` ফাইলে প্লাগিন হেডার সঠিক নেই
- ফাইল করাপ্ট হয়েছে

**সমাধান:**
1. `woo-product-table.php` ফাইল খুলুন
2. প্রথম লাইনে `<?php` আছে কিনা চেক করুন
3. Plugin Name, Version ইত্যাদি হেডার কমেন্ট ঠিক আছে কিনা দেখুন
4. ফাইলে কোন BOM (Byte Order Mark) নেই তা নিশ্চিত করুন

```php
<?php
/**
 * Plugin Name: Product Table for WooCommerce
 * Version: 5.0.6
 * ...
 */
```

### সমস্যা ২: WooCommerce Dependency এরর

**এরর মেসেজ:** "WooCommerce is required for this plugin to work."

**কারণ:**
- WooCommerce প্লাগিন ইনস্টল করা নেই
- WooCommerce নিষ্ক্রিয় আছে

**সমাধান:**
1. Dashboard → Plugins → Add New
2. "WooCommerce" সার্চ করুন
3. ইনস্টল এবং অ্যাক্টিভেট করুন
4. Woo Product Table প্লাগিন আবার অ্যাক্টিভেট করুন

## টেবিল ডিসপ্লে সমস্যা

### সমস্যা ৩: শর্টকোড কাজ করছে না

**লক্ষণ:**
- পেজে `[Product_Table id='123']` টেক্সট দেখা যাচ্ছে
- টেবিল দেখা যাচ্ছে না

**কারণ:**
- শর্টকোড সঠিকভাবে লেখা হয়নি
- টেবিল ID ভুল
- প্লাগিন সঠিকভাবে লোড হয়নি

**সমাধান:**

**পদক্ষেপ ১:** শর্টকোড চেক করুন
```
সঠিক: [Product_Table id='123']
ভুল:   [product_table id='123']  ❌ (ছোট হাতের p)
ভুল:   [Product_Table id=123]    ❌ (কোট নেই)
```

**পদক্ষেপ ২:** টেবিল ID যাচাই করুন
1. Dashboard → Product Table → All Items
2. টেবিল এডিট করুন
3. শর্টকোড কপি করুন

**পদক্ষেপ ৩:** ডিবাগ চালু করুন
`wp-config.php` তে:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

`wp-content/debug.log` ফাইল চেক করুন।

### সমস্যা ৪: টেবিল খালি দেখাচ্ছে

**লক্ষণ:**
- টেবিল দেখা যাচ্ছে কিন্তু কোন প্রোডাক্ট নেই
- "No products found" মেসেজ

**কারণ:**
- কোন প্রোডাক্ট পাবলিশ করা নেই
- কোয়েরি সেটিংস ভুল
- ফিল্টার খুব স্ট্রিক্ট

**সমাধান:**

**পদক্ষেপ ১:** প্রোডাক্ট চেক করুন
1. Products → All Products
2. কমপক্ষে একটি প্রোডাক্ট পাবলিশ করা আছে কিনা দেখুন

**পদক্ষেপ ২:** টেবিল কোয়েরি চেক করুন
1. টেবিল এডিট করুন
2. Query ট্যাবে যান
3. ক্যাটাগরি/ট্যাগ ফিল্টার খালি করুন
4. "Posts per page" সংখ্যা বাড়ান

**পদক্ষেপ ৩:** ডিবাগ কোড যোগ করুন
```php
add_action( 'wpt_after_query', function( $query ) {
    echo '<pre>';
    echo 'Found Products: ' . $query->found_posts . "\n";
    echo 'SQL: ' . $query->request;
    echo '</pre>';
});
```

### সমস্যা ৫: টেবিল স্টাইল ভেঙ্গে গেছে

**লক্ষণ:**
- টেবিল সুন্দর দেখাচ্ছে না
- CSS লোড হচ্ছে না
- লেআউট ভাঙা

**কারণ:**
- CSS ফাইল লোড হয়নি
- থিমের সাথে কনফ্লিক্ট
- ক্যাশ সমস্যা

**সমাধান:**

**পদক্ষেপ ১:** ব্রাউজার ক্যাশ ক্লিয়ার করুন
- Chrome: Ctrl + Shift + Delete
- Hard Reload: Ctrl + F5

**পদক্ষেপ ২:** CSS লোড চেক করুন
1. F12 প্রেস করুন (DevTools)
2. Network ট্যাবে যান
3. Reload করুন
4. `wpt-frontend.css` ফাইল 200 স্ট্যাটাস দিচ্ছে কিনা দেখুন

**পদক্ষেপ ৩:** থিম কনফ্লিক্ট চেক করুন
ডিফল্ট Twenty Twenty-Three থিমে সুইচ করে দেখুন সমস্যা থাকে কিনা।

## JavaScript সমস্যা

### সমস্যা ৬: DataTable কাজ করছে না

**লক্ষণ:**
- সর্টিং কাজ করছে না
- সার্চ বক্স কাজ করছে না
- পেজিনেশন কাজ করছে না

**কারণ:**
- jQuery লোড হয়নি
- DataTables library লোড হয়নি
- JavaScript এরর আছে

**সমাধান:**

**পদক্ষেপ ১:** Console চেক করুন
1. F12 প্রেস করুন
2. Console ট্যাবে যান
3. লাল রঙের এরর আছে কিনা দেখুন

**পদক্ষেপ ২:** jQuery চেক করুন
Console এ টাইপ করুন:
```javascript
jQuery.fn.jquery
```
ভার্সন নম্বর দেখাতে হবে। না দেখালে jQuery লোড হয়নি।

**পদক্ষেপ ৩:** DataTables চেক করুন
```javascript
jQuery.fn.DataTable
```
`undefined` দেখালে DataTables লোড হয়নি।

**পদক্ষেপ ৪:** স্ক্রিপ্ট ম্যানুয়ালি এনকিউ করুন
```php
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'jquery' );
});
```

### সমস্যা ৭: AJAX কাজ করছে না

**লক্ষণ:**
- পেজিনেশনে ক্লিক করলে কিছু হয় না
- ফিল্টার কাজ করছে না

**কারণ:**
- AJAX URL সঠিক নেই
- Nonce ভেরিফিকেশন ফেইল হচ্ছে
- Server side এরর

**সমাধান:**

**পদক্ষেপ ১:** Network ট্যাব চেক করুন
1. F12 → Network
2. XHR ফিল্টার সিলেক্ট করুন
3. AJAX রিকোয়েস্ট দেখুন
4. Response দেখুন

**পদক্ষেপ ২:** AJAX URL চেক করুন
Console এ:
```javascript
console.log(wpt_ajax.ajax_url);
```
Output হবে: `https://yoursite.com/wp-admin/admin-ajax.php`

**পদক্ষেপ ৩:** PHP এরর লগ দেখুন
```bash
tail -f wp-content/debug.log
```

## পারফরম্যান্স সমস্যা

### সমস্যা ৮: টেবিল লোড হতে অনেক সময় লাগছে

**কারণ:**
- অনেক বেশি প্রোডাক্ট
- ভারী কোয়েরি
- ইমেজ সাইজ বড়

**সমাধান:**

**পদক্ষেপ ১:** প্রতি পেজে প্রোডাক্ট কমান
```
Query ট্যাব → Posts per page: 20 (50 এর বদলে)
```

**পদক্ষেপ ২:** ক্যাশিং চালু করুন
```php
add_filter( 'wpt_enable_cache', '__return_true' );
```

**পদক্ষেপ ৩:** ইমেজ অপটিমাইজ করুন
- থাম্বনেইল সাইজ ছোট করুন
- Lazy loading চালু করুন

```php
add_filter( 'wpt_thumbnail_size', function() {
    return array( 50, 50 ); // Width, Height
});
```

**পদক্ষেপ ৪:** Query Monitor প্লাগিন ব্যবহার করুন
1. Query Monitor ইনস্টল করুন
2. Slow queries খুঁজুন
3. Optimize করুন

### সমস্যা ৯: Memory Limit এরর

**এরর মেসেজ:** "Fatal error: Allowed memory size exhausted"

**সমাধান:**

**পদক্ষেপ ১:** Memory Limit বাড়ান
`wp-config.php` তে:
```php
define( 'WP_MEMORY_LIMIT', '256M' );
```

**পদক্ষেপ ২:** প্রোডাক্ট সংখ্যা কমান
একসাথে খুব বেশি প্রোডাক্ট লোড করবেন না।

**পদক্ষেপ ৩:** Pagination ব্যবহার করুন
সব প্রোডাক্ট একসাথে না দেখিয়ে পেজিনেশন ব্যবহার করুন।

## কনফিগারেশন সমস্যা

### সমস্যা ১০: কলাম দেখাচ্ছে না

**কারণ:**
- কলাম সেটিংসে এনাবল করা নেই
- ডিভাইস সেটিংস ভুল (Desktop/Mobile)

**সমাধান:**

**পদক্ষেপ ১:** কলাম সেটিংস চেক করুন
1. টেবিল এডিট করুন
2. Column Settings ট্যাবে যান
3. Desktop ট্যাবে কলাম চেক করুন
4. Save করুন

**পদক্ষেপ ২:** ডিভাইস চেক করুন
মোবাইলে দেখছেন? তাহলে Mobile ট্যাব চেক করুন।

### সমস্যা ১১: সেটিংস সেভ হচ্ছে না

**কারণ:**
- Nonce verification ফেইল
- Permission issue
- max_input_vars limit

**সমাধান:**

**পদক্ষেপ ১:** max_input_vars বাড়ান
`php.ini` তে:
```ini
max_input_vars = 3000
```

অথবা `.htaccess` এ:
```apache
php_value max_input_vars 3000
```

**পদক্ষেপ ২:** Permission চেক করুন
```php
if ( ! current_user_can( 'manage_wpt_product_table' ) ) {
    // এই ইউজার সেভ করতে পারবে না
}
```

## ডেটা সমস্যা

### সমস্যা ১২: প্রোডাক্ট ডেটা ভুল দেখাচ্ছে

**লক্ষণ:**
- দাম ভুল
- স্টক স্ট্যাটাস ভুল
- ইমেজ দেখাচ্ছে না

**সমাধান:**

**পদক্ষেপ ১:** WooCommerce ডেটা আপডেট করুন
1. WooCommerce → Status → Tools
2. "Recount terms" ক্লিক করুন
3. "Clear transients" ক্লিক করুন

**পদক্ষেপ ২:** প্রোডাক্ট পুনরায় সেভ করুন
সমস্যাযুক্ত প্রোডাক্ট এডিট করে আবার সেভ করুন।

### সমস্যা ১৩: ভেরিয়েবল প্রোডাক্ট সঠিক দেখাচ্ছে না

**কারণ:**
- Variations সঠিকভাবে কনফিগার করা নেই
- Variation ডেটা মিসিং

**সমাধান:**

**পদক্ষেপ ১:** Variations চেক করুন
1. প্রোডাক্ট এডিট করুন
2. Variations ট্যাবে যান
3. সব Variations সেভ করা আছে কিনা দেখুন

**পদক্ষেপ ২:** Regenerate Variations
```php
add_action( 'init', function() {
    if ( isset( $_GET['regenerate_variations'] ) ) {
        $product = wc_get_product( $_GET['product_id'] );
        if ( $product && $product->is_type( 'variable' ) ) {
            // Variations regenerate logic
        }
    }
});
```

## থিম কম্প্যাটিবিলিটি

### সমস্যা ১৪: নির্দিষ্ট থিমে কাজ করছে না

**সমাধান:**

**পদক্ষেপ ১:** Theme conflict চেক করুন
ডিফল্ট থিমে (Twenty Twenty-Three) সুইচ করে দেখুন।

**পদক্ষেপ ২:** CSS Priority বাড়ান
```css
.wpt-product-table td {
    padding: 10px !important;
}
```

**পদক্ষেপ ৩:** Compatibility code যোগ করুন
```php
add_action( 'after_setup_theme', function() {
    // Theme specific fixes
    if ( 'Divi' === wp_get_theme()->get( 'Name' ) ) {
        // Divi specific code
    }
});
```

## প্লাগিন কনফ্লিক্ট

### সমস্যা ১৫: অন্য প্লাগিনের সাথে কনফ্লিক্ট

**লক্ষণ:**
- একটা প্লাগিন অ্যাক্টিভেট করলে সমস্যা শুরু হয়

**সমাধান:**

**পদক্ষেপ ১:** Conflict চিহ্নিত করুন
1. সব প্লাগিন নিষ্ক্রিয় করুন
2. শুধু WooCommerce এবং Woo Product Table চালু করুন
3. একটা একটা করে অন্য প্লাগিন চালু করুন
4. কোনটায় সমস্যা হয় দেখুন

**পদক্ষেপ ২:** Priority পরিবর্তন করুন
```php
add_action( 'init', 'my_function', 5 ); // Early priority
add_action( 'init', 'my_function', 99 ); // Late priority
```

## ডিবাগ টুলস

### ব্যবহারযোগ্য টুলস

**Query Monitor:**
```bash
wp plugin install query-monitor --activate
```

**Debug Bar:**
```bash
wp plugin install debug-bar --activate
```

**WP-CLI:**
```bash
wp wpt list-tables
wp wpt regenerate-table 123
```

## সহায়তা পাওয়ার উপায়

যদি সমস্যার সমাধান না হয়:

1. **GitHub Issues:** https://github.com/codersaiful/woo-product-table/issues
2. **Support Forum:** WordPress.org support
3. **ডকুমেন্টেশন:** `assets/dev-doc/` ফোল্ডার
4. **Debug Log:** `wp-content/debug.log` ফাইল শেয়ার করুন

## পরবর্তী ধাপ

- [উন্নতি এবং সাজেশন](10-improvements-suggestions.md)
- [সাধারণ কাজ](08-common-tasks.md)
