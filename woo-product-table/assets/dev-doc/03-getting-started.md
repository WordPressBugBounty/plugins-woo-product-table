# শুরু করার গাইড - নতুন ডেভেলপারদের জন্য

এই গাইডটি বিশেষভাবে নতুন ডেভেলপারদের জন্য তৈরি করা হয়েছে যারা Woo Product Table প্লাগিনে কাজ শুরু করতে চান।

## প্রয়োজনীয় জ্ঞান

কাজ শুরু করার আগে আপনার যা জানা থাকা উচিত:

### বেসিক
- ✅ HTML, CSS এর মৌলিক জ্ঞান
- ✅ JavaScript/jQuery এর বেসিক
- ✅ PHP এর বেসিক (ভেরিয়েবল, ফাংশন, অ্যারে, লুপ)

### ইন্টারমিডিয়েট
- ✅ WordPress এর মৌলিক ধারণা (হুক, ফিল্টার, শর্টকোড)
- ✅ WooCommerce এর বেসিক (পণ্য, ক্যাটাগরি)
- ✅ PHP OOP (ক্লাস, অবজেক্ট, namespace)

### টুলস
- ✅ Git এর বেসিক (clone, commit, push, pull)
- ✅ টেক্সট এডিটর (VS Code সুপারিশকৃত)
- ✅ লোকাল সার্ভার (XAMPP/WAMP)

## লোকাল ডেভেলপমেন্ট সেটআপ

### ধাপ ১: লোকাল সার্ভার ইনস্টল

**Windows এর জন্য XAMPP:**
1. [XAMPP ডাউনলোড](https://www.apachefriends.org/) করুন
2. ইনস্টল করুন `C:\xampp` এ
3. XAMPP Control Panel খুলুন
4. Apache এবং MySQL স্টার্ট করুন

**অথবা WAMP:**
1. [WAMP ডাউনলোড](https://www.wampserver.com/en/) করুন
2. ইনস্টল করুন
3. সার্ভিস স্টার্ট করুন

### ধাপ ২: WordPress ইনস্টল

1. [WordPress ডাউনলোড](https://wordpress.org/download/) করুন
2. Extract করুন `C:\xampp\htdocs\mysite` এ
3. ব্রাউজারে যান: `http://localhost/mysite`
4. ইনস্টলেশন সম্পন্ন করুন
   - Database নাম: `mysite_db`
   - Username: `root`
   - Password: (খালি রাখুন XAMPP এর জন্য)
   - Host: `localhost`

### ধাপ ৩: WooCommerce ইনস্টল

1. WordPress Dashboard → Plugins → Add New
2. "WooCommerce" সার্চ করুন
3. Install এবং Activate করুন
4. Setup Wizard সম্পন্ন করুন

### ধাপ ৪: স্যাম্পল প্রোডাক্ট ইম্পোর্ট

1. যান: `C:\xampp\htdocs\mysite\wp-content\plugins\woocommerce\sample-data\`
2. `sample_products.csv` ফাইলটি খুঁজুন
3. WooCommerce → Products → Import
4. CSV ফাইল সিলেক্ট করে ইম্পোর্ট করুন

### ধাপ ৫: প্লাগিন ক্লোন করুন

1. Git Bash খুলুন
2. প্লাগিন ডিরেক্টরিতে যান:
```bash
cd C:\xampp\htdocs\mysite\wp-content\plugins
```

3. রিপোজিটরি ক্লোন করুন:
```bash
git clone https://github.com/codersaiful/woo-product-table.git
```

4. প্লাগিন অ্যাক্টিভেট করুন:
   - Dashboard → Plugins → "Product Table for WooCommerce"
   - Activate ক্লিক করুন

### ধাপ ৬: Node.js সেটআপ (অপশনাল, বিল্ডের জন্য)

1. [Node.js ডাউনলোড](https://nodejs.org/) করুন এবং ইনস্টল করুন
2. প্লাগিন ডিরেক্টরিতে যান:
```bash
cd C:\xampp\htdocs\mysite\wp-content\plugins\woo-product-table
```

3. Dependencies ইনস্টল করুন:
```bash
npm install
```

## প্রথম টেবিল তৈরি করা

### ধাপ ১: নতুন টেবিল তৈরি

1. Dashboard → Product Table → Add New
2. টাইটেল দিন: "আমার প্রথম টেবিল"
3. **Column Settings ট্যাব:**
   - Thumbnail চেক করুন
   - Product Title চেক করুন
   - Price চেক করুন
   - Stock চেক করুন
   - Add to Cart চেক করুন

4. **Query ট্যাব:**
   - Posts per page: 10
   - Order by: Date
   - Order: DESC

5. **Publish** বাটনে ক্লিক করুন

### ধাপ ২: শর্টকোড কপি করুন

প্রকাশ করার পর, আপনি একটি শর্টকোড দেখতে পাবেন:
```
[Product_Table id='123']
```
এটি কপি করুন।

### ধাপ ৩: পেজে শর্টকোড যোগ করুন

1. Pages → Add New
2. টাইটেল: "Products"
3. শর্টকোড পেস্ট করুন
4. Publish করুন
5. View Page করে দেখুন

## কোড এডিটর সেটআপ

### VS Code (সুপারিশকৃত)

1. [VS Code ডাউনলোড](https://code.visualstudio.com/) করুন
2. প্রয়োজনীয় এক্সটেনশন ইনস্টল করুন:
   - **PHP Intelephense** - PHP autocomplete
   - **WordPress Snippets** - WordPress কোড স্নিপেট
   - **GitLens** - Git ইন্টিগ্রেশন
   - **Beautify** - কোড ফরম্যাটিং

3. প্রজেক্ট খুলুন:
   - File → Open Folder
   - Select: `C:\xampp\htdocs\mysite`

### এডিটর সেটিংস

`.vscode/settings.json` তৈরি করুন:
```json
{
  "editor.tabSize": 4,
  "editor.insertSpaces": false,
  "files.associations": {
    "*.php": "php"
  },
  "emmet.includeLanguages": {
    "php": "html"
  }
}
```

## ডিবাগিং সেটআপ

### WordPress Debug Mode চালু করুন

`wp-config.php` ফাইল এডিট করুন:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

এখন সব এরর `wp-content/debug.log` ফাইলে সেভ হবে।

### ব্রাউজার DevTools

1. **Chrome DevTools:**
   - F12 প্রেস করুন
   - Console ট্যাব - JavaScript এরর দেখার জন্য
   - Network ট্যাব - AJAX রিকোয়েস্ট দেখার জন্য

## Git ওয়ার্কফ্লো

### নতুন ব্রাঞ্চ তৈরি করুন

```bash
cd C:\xampp\htdocs\mysite\wp-content\plugins\woo-product-table
git checkout -b feature/my-new-feature_from_5.0.6.3
```

**গুরুত্বপূর্ণ:** ব্রাঞ্চের নামের শেষে `_from_5.0.6.3` যোগ করতে হবে।

### পরিবর্তন দেখুন

```bash
git status
git diff
```

### পরিবর্তন কমিট করুন

```bash
git add .
git commit -m "Added new feature"
```

### পুশ করুন

```bash
git push origin feature/my-new-feature_from_5.0.6.3
```

## প্রথম কোড পরিবর্তন

### একটি সিম্পল টেস্ট

আসুন একটি সহজ পরিবর্তন করি যাতে বুঝতে পারি কিভাবে কাজ করে।

**গুরুত্বপূর্ণ:** প্লাগিনের কোর ফাইল সরাসরি এডিট করবেন না। পরিবর্তে হুক ব্যবহার করুন।

**থিমের functions.php ফাইলে যোগ করুন:**

```php
// প্রোডাক্ট টাইটেলে ইমোজি যোগ করুন
add_filter( 'the_title', function( $title, $post_id ) {
    // শুধুমাত্র প্রোডাক্ট টেবিলের জন্য
    if ( get_post_type( $post_id ) === 'product' && doing_action( 'wpt_column_top' ) ) {
        return '🎉 ' . $title;
    }
    return $title;
}, 10, 2 );
```

অথবা কলামের শুরুতে যোগ করুন:

```php
add_action( 'wpt_column_top', function( $keyword, $product ) {
    if ( $keyword === 'product_title' ) {
        echo '<span class="emoji">🎉 </span>';
    }
}, 10, 2 );
```

এখন পেজ রিফ্রেশ করলে সব প্রোডাক্ট টাইটেলের আগে 🎉 দেখতে পাবেন!

## ডেভেলপমেন্ট চেকলিস্ট

প্রতিটি পরিবর্তনের পর:

- [ ] কোড সিনট্যাক্স ঠিক আছে কিনা চেক করুন
- [ ] ব্রাউজারে টেবিল দেখুন
- [ ] Browser Console এ এরর আছে কিনা চেক করুন
- [ ] `debug.log` ফাইলে PHP এরর চেক করুন
- [ ] বিভিন্ন ব্রাউজারে টেস্ট করুন (Chrome, Firefox)
- [ ] মোবাইলে কেমন দেখাচ্ছে চেক করুন

## সাধারণ সমস্যা এবং সমাধান

### প্লাগিন অ্যাক্টিভেট হচ্ছে না

**সমস্যা:** "The plugin does not have a valid header"

**সমাধান:** 
- `woo-product-table.php` ফাইল খুলুন
- প্রথম লাইনে `<?php` আছে কিনা নিশ্চিত করুন
- ফাইলে কোন BOM (Byte Order Mark) নেই তা নিশ্চিত করুন

### সাদা পেজ দেখাচ্ছে

**সমস্যা:** পেজ সম্পূর্ণ সাদা, কিছু দেখা যাচ্ছে না

**সমাধান:**
- `wp-content/debug.log` চেক করুন
- সম্ভবত PHP syntax error আছে
- সর্বশেষ পরিবর্তন undo করুন

### টেবিল দেখাচ্ছে না

**সমস্যা:** শর্টকোড কাজ করছে না

**সমাধান:**
- শর্টকোড সঠিক আছে কিনা চেক করুন
- টেবিল ID সঠিক আছে কিনা চেক করুন
- WooCommerce অ্যাক্টিভ আছে কিনা চেক করুন

### CSS/JS লোড হচ্ছে না

**সমস্যা:** স্টাইল বা স্ক্রিপ্ট কাজ করছে না

**সমাধান:**
- Browser cache ক্লিয়ার করুন (Ctrl + Shift + Delete)
- F5 দিয়ে রিফ্রেশ করুন
- DevTools Network ট্যাবে 404 এরর চেক করুন

## পরবর্তী ধাপ

এখন আপনি প্লাগিনে কাজ করার জন্য প্রস্তুত! পরবর্তী ডকুমেন্ট পড়ুন:

- [কোড আর্কিটেকচার](04-code-architecture.md) - কোডের গভীর বিশ্লেষণ
- [নতুন ফিচার যোগ করা](05-adding-features.md) - কিভাবে নতুন ফিচার তৈরি করবেন
- [কাস্টম কলাম তৈরি](06-custom-column.md) - নতুন কলাম বানানো শিখুন

## সহায়ক রিসোর্স

### WordPress
- [WordPress Developer Resources](https://developer.wordpress.org/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)

### WooCommerce
- [WooCommerce Developer Documentation](https://woocommerce.github.io/code-reference/)
- [WooCommerce Hooks Reference](https://woocommerce.github.io/code-reference/hooks/hooks.html)

### PHP
- [PHP Manual](https://www.php.net/manual/en/)
- [PHP The Right Way](https://phptherightway.com/)

### Git
- [Git Handbook](https://guides.github.com/introduction/git-handbook/)
- [Learn Git Branching](https://learngitbranching.js.org/)
