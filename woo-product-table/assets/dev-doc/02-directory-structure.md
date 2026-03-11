# ডিরেক্টরি স্ট্রাকচার - বিস্তারিত

এই ডকুমেন্টে প্লাগিনের সম্পূর্ণ ফাইল এবং ফোল্ডার স্ট্রাকচার বিস্তারিতভাবে ব্যাখ্যা করা হয়েছে।

## রুট লেভেল ফাইল

### woo-product-table.php
- **উদ্দেশ্য:** মূল প্লাগিন ফাইল
- **কাজ:** 
  - প্লাগিন হেডার ইনফরমেশন
  - সব কনস্ট্যান্ট ডিফাইন করা
  - Freemius SDK ইনিশিয়ালাইজ করা
  - মূল `WPT_Product_Table` ক্লাস ডিফাইন করা
- **গুরুত্বপূর্ণ কনস্ট্যান্ট:**
  - `WPT_DEV_VERSION` - ডেভেলপমেন্ট ভার্সন নম্বর
  - `WPT_BASE_URL` - প্লাগিন বেস URL
  - `WPT_BASE_DIR` - প্লাগিন ডিরেক্টরি পাথ
  - `WPT_ASSETS_URL` - অ্যাসেটস ফোল্ডার URL

### autoloader.php
- **উদ্দেশ্য:** PHP ক্লাস অটোলোডিং
- **কাজ:** Namespace অনুযায়ী ক্লাস ফাইল অটোমেটিক লোড করা

### package.json
- **উদ্দেশ্য:** NPM প্যাকেজ কনফিগারেশন
- **স্ক্রিপ্ট:**
  - `npm run plugin-zip` - প্লাগিন জিপ তৈরি
  - `npm run fs` - Freemius ডিপ্লয়মেন্ট

### readme.md
- **উদ্দেশ্য:** GitHub রিপোজিটরি ডকুমেন্টেশন
- **কন্টেন্ট:** ইনস্টলেশন, ফিচার, কন্ট্রিবিউশন গাইড

### readme.txt
- **উদ্দেশ্য:** WordPress.org প্লাগিন ডিরেক্টরি ফরম্যাট
- **কন্টেন্ট:** প্লাগিন বর্ণনা, চেঞ্জলগ, ইনস্টলেশন

## ডিরেক্টরি স্ট্রাকচার

### /admin - অ্যাডমিন এরিয়া
```
admin/
├── admin-loader.php          # অ্যাডমিন এরিয়ার মূল লোডার
├── admin-enqueue.php         # অ্যাডমিন CSS/JS এনকিউ
├── action-hook.php           # অ্যাডমিন অ্যাকশন হুক
├── functions.php             # অ্যাডমিন ফাংশন
├── wpt_product_table_post.php # কাস্টম পোস্ট টাইপ রেজিস্ট্রেশন
├── post_metabox.php          # মেটাবক্স তৈরি
├── post_metabox_form.php     # মেটাবক্স ফর্ম
├── duplicate.php             # টেবিল ডুপ্লিকেট ফিচার
├── menu_plugin_setting_link.php # প্লাগিন সেটিংস লিংক
├── page-loader.php           # পেজ লোডার
├── handle/                   # হ্যান্ডলার ক্লাস
│   ├── feature-loader.php
│   ├── pro-version-update.php
│   └── ...
├── page/                     # অ্যাডমিন পেজ
│   ├── configure.php         # কনফিগার পেজ
│   ├── browse-plugins.php    # প্লাগিন ব্রাউজ
│   ├── form-submit.php       # ফর্ম সাবমিট হ্যান্ডলার
│   └── ...
└── tabs/                     # সেটিংস ট্যাব
    ├── config.php            # কনফিগ ট্যাব
    ├── column_settings.php   # কলাম সেটিংস
    ├── query.php             # কোয়েরি সেটিংস
    ├── search_n_filter.php   # সার্চ এবং ফিল্টার
    ├── table_style.php       # টেবিল স্টাইল
    └── ...
```

**ব্যবহার:**
- টেবিল তৈরি এবং এডিট করার সব কোড এখানে
- অ্যাডমিন প্যানেলের UI এবং ফাংশনালিটি

### /inc - মূল শর্টকোড সিস্টেম (নতুন)
```
inc/
├── shortcode.php             # মূল শর্টকোড ক্লাس
├── shortcode-base.php        # শর্টকোড বেস ক্লাস
├── shortcode-ajax.php        # AJAX হ্যান্ডলিং
├── handle/                   # বিভিন্ন হ্যান্ডলার
│   ├── args.php              # আর্গুমেন্ট হ্যান্ডলিং
│   ├── enable-column.php     # কলাম এনাবল
│   ├── pagination.php        # পেজিনেশন
│   ├── search-box.php        # সার্চ বক্স
│   ├── table-body.php        # টেবিল বডি
│   ├── message.php           # মেসেজ ডিসপ্লে
│   └── ...
├── table/                    # টেবিল জেনারেশন
│   ├── row.php               # টেবিল রো ক্লাস
│   └── table-base.php        # টেবিল বেস ক্লাস
└── features/                 # বিভিন্ন ফিচার
    └── basics.php
```

**ব্যবহার:**
- ফ্রন্টএন্ড শর্টকোড রেন্ডারিং
- টেবিল জেনারেশন লজিক
- AJAX রিকোয়েস্ট হ্যান্ডলিং

### /includes - পুরাতন শর্টকোড সিস্টেম
```
includes/
├── shortcode.php             # পুরাতন শর্টকোড (ব্যাকওয়ার্ড কম্প্যাটিবিলিটি)
├── functions.php             # হেল্পার ফাংশন
├── enqueue.php               # ফ্রন্টএন্ড CSS/JS
├── preview_table.php         # টেবিল প্রিভিউ
├── variation_html.php        # ভেরিয়েবল প্রোডাক্ট HTML
├── extra_items_manager.php   # এক্সট্রা আইটেম ম্যানেজার
└── items/                    # কলাম টেমপ্লেট
    ├── thumbnails.php        # থাম্বনেইল কলাম
    ├── product_title.php     # টাইটেল কলাম
    ├── price.php             # দাম কলাম
    ├── stock.php             # স্টক কলাম
    ├── rating.php            # রেটিং কলাম
    ├── category.php          # ক্যাটাগরি কলাম
    ├── tags.php              # ট্যাগ কলাম
    ├── sku.php               # SKU কলাম
    ├── attribute.php         # অ্যাট্রিবিউট কলাম
    ├── custom_field.php      # কাস্টম ফিল্ড
    ├── action.php            # অ্যাকশন বাটন
    └── ...
```

**ব্যবহার:**
- প্রতিটি কলামের জন্য আলাদা টেমপ্লেট ফাইল
- পুরাতন কোডের সাথে কম্প্যাটিবিলিটি

### /assets - স্ট্যাটিক ফাইল
```
assets/
├── css/                      # স্টাইলশিট
│   ├── wpt-frontend.css
│   ├── wpt-backend.css
│   └── ...
├── js/                       # জাভাস্ক্রিপ্ট
│   ├── wpt-frontend.js
│   ├── wpt-backend.js
│   └── ...
├── images/                   # ইমেজ ফাইল
├── DataTables/               # DataTables লাইব্রেরি
├── select2/                  # Select2 লাইব্রেরি
├── fontello/                 # আইকন ফন্ট
└── dev-doc/                  # ডেভেলপার ডকুমেন্টেশন (নতুন)
```

**ব্যবহার:**
- সব CSS, JavaScript এবং ইমেজ ফাইল
- থার্ড-পার্টি লাইব্রেরি

### /core - কোর ফাংশনালিটি
```
core/
└── base.php                  # বেস ক্লাস
```

**ব্যবহার:**
- সব ক্লাসের জন্য বেস ফাংশনালিটি

### /framework - ফ্রেমওয়ার্ক
```
framework/
└── ca-framework/             # CodeAstrology ফ্রেমওয়ার্ক
    ├── classes/
    └── ...
```

**ব্যবহার:**
- কাস্টম ফ্রেমওয়ার্ক কোড

### /templates - HTML টেমপ্লেট
```
templates/
├── store.php                 # স্টোর টেমপ্লেট
└── table-preview.php         # টেবিল প্রিভিউ টেমপ্লেট
```

**ব্যবহার:**
- HTML টেমপ্লেট ফাইল

### /modules - মডিউল
```
modules/
├── elementor.php             # Elementor ইন্টিগ্রেশন
├── elementor-widget.php      # Elementor উইজেট
└── Mobile_Detect.php         # মোবাইল ডিটেকশন
```

**ব্যবহার:**
- থার্ড-পার্টি প্লাগিন ইন্টিগ্রেশন

### /compatible - কম্প্যাটিবিলিটি
```
compatible/
└── plugins/                  # অন্যান্য প্লাগিনের সাথে কম্প্যাটিবিলিটি
    ├── acf.php
    ├── yith-wishlist.php
    └── ...
```

**ব্যবহার:**
- অন্যান্য জনপ্রিয় প্লাগিনের সাথে ইন্টিগ্রেশন

### /premium - প্রিমিয়াম ভার্সন
```
premium/
├── premium-loader.php        # প্রিমিয়াম ফিচার লোডার
├── admin/                    # প্রিমিয়াম অ্যাডমিন
├── inc/                      # প্রিমিয়াম শর্টকোড
├── includes/                 # প্রিমিয়াম আইটেম
├── assets/                   # প্রিমিয়াম অ্যাসেটস
└── ...
```

**ব্যবহার:**
- প্রিমিয়াম ভার্সনের ফিচার (শুধুমাত্র পেইড ভার্সনে)

### /languages - অনুবাদ
```
languages/
├── woo-product-table-bn_BD.l10n.php    # বাংলা অনুবাদ
├── woo-product-table-en_US.l10n.php    # ইংরেজি
├── woo-product-table-de_DE.l10n.php    # জার্মান
└── ...
```

**ব্যবহার:**
- বহুভাষিক সাপোর্ট

### /vendor - থার্ড-পার্টি লাইব্রেরি
```
vendor/
└── freemius/                 # Freemius SDK
```

**ব্যবহার:**
- Composer ডিপেন্ডেন্সি এবং Freemius

### /build - বিল্ড ফাইল
```
build/
├── build-zip.js              # জিপ তৈরির স্ক্রিপ্ট
├── build-zip-fs.js           # Freemius জিপ স্ক্রিপ্ট
└── ...
```

**ব্যবহার:**
- প্লাগিন প্যাকেজিং

### /wpml - WPML সাপোর্ট
```
wpml/
└── (WPML কম্প্যাটিবিলিটি ফাইল)
```

**ব্যবহার:**
- WPML মাল্টিলিঙ্গুয়াল প্লাগিন সাপোর্ট

## গুরুত্বপূর্ণ নোট

### নতুন কোড বনাম পুরাতন কোড

1. **/inc/** ফোল্ডার - নতুন কোড (ভার্সন 5.0+)
   - Namespace ব্যবহার করা হয়েছে
   - OOP প্যাটার্ন অনুসরণ করা হয়েছে
   - ভবিষ্যতের ডেভেলপমেন্ট এখানে হবে

2. **/includes/** ফোল্ডার - পুরাতন কোড (ভার্সন 4.x)
   - ব্যাকওয়ার্ড কম্প্যাটিবিলিটির জন্য রাখা হয়েছে
   - নতুন ফিচার এখানে যোগ করা উচিত নয়

### ফাইল নেমিং কনভেনশন

- ক্লাস ফাইল: `class-name.php`
- ফাংশন ফাইল: `functions.php`, `helper-functions.php`
- টেমপ্লেট ফাইল: `template-name.php`

## পরবর্তী ধাপ

- [শুরু করার গাইড](03-getting-started.md)
- [কোড আর্কিটেকচার](04-code-architecture.md)
