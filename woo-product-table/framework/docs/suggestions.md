# Future Feature Suggestions

Ideas and suggestions for extending the CA Framework in future versions.

---

## 🟢 High Priority

### 1. ~~Countdown Timer for Offers~~ ✅ Implemented

Live countdown timer is now available for both offers and popups via `show_countdown` parameter.

```php
$framework->create_offer( array(
    'title'          => 'Flash Sale!',
    'end_date'       => '2025-03-15 23:59:59',
    'show_countdown' => true,
    'template'       => 'flash',
) )->show();
```

### 2. Analytics & Tracking
Track offer impressions, clicks, and dismiss rates to measure campaign effectiveness.

```php
// Proposed API
$framework->create_offer( array(
    'tracking' => true,
    // ...
) )->show();

// Get stats
$stats = $framework->get_offer_stats( 'offer-id' );
// Returns: array( 'impressions' => 150, 'clicks' => 23, 'dismisses' => 12 )
```

### 3. Conditional Display Rules
Show offers based on conditions like user role, plugin version, days since install, etc.

```php
// Proposed API
$framework->create_offer( array(
    'conditions' => array(
        'min_days_since_install' => 7,
        'max_days_since_install' => 30,
        'user_roles'             => array( 'administrator' ),
        'plugin_version_below'   => '3.0.0',
    ),
    // ...
) )->show();
```

### 4. A/B Testing for Offers
Test different offer variations to see which performs better.

```php
// Proposed API
$framework->create_ab_test( 'test-id', array(
    'variant_a' => array( 'title' => 'Get 30% Off!', 'template' => 'starter' ),
    'variant_b' => array( 'title' => 'Save $50!', 'template' => 'flash' ),
) )->show();
```

---

## 🟡 Medium Priority

### 5. Review/Rating Request
A specialized prompt asking users to rate the plugin on WordPress.org after a certain usage period.

```php
// Proposed API
$framework->create_review_prompt( array(
    'plugin_slug'       => 'my-plugin',
    'min_days_active'   => 14,
    'min_usage_count'   => 10, // e.g., 10 times feature X was used
    'wordpress_org_url' => 'https://wordpress.org/support/plugin/my-plugin/reviews/',
) )->show();
```

### 6. Changelog Popup
Automatically show new features/changes after a plugin update.

```php
// Proposed API
$framework->create_changelog_popup( array(
    'version'  => '2.5.0',
    'changes'  => array(
        'new'   => array( 'Feature A', 'Feature B' ),
        'fix'   => array( 'Bug fix C' ),
        'tweak' => array( 'Performance improvement' ),
    ),
) )->show();
```

### 7. Notification Center
A centralized notification center in the admin bar for all plugin messages.

```php
// Proposed API
$framework->add_notification( array(
    'title'    => 'New Feature Available',
    'message'  => 'Check out our new export feature.',
    'type'     => 'info', // info, success, warning, error
    'priority' => 'low',
) );
```

### 8. Inline Plugin Ads
Small, non-intrusive ads within plugin settings pages for related products.

```php
// Proposed API
$framework->create_inline_ad( array(
    'position'    => 'sidebar', // sidebar, footer, inline
    'title'       => 'Need More Features?',
    'description' => 'Try our Pro addon.',
    'cta_text'    => 'Learn More',
    'cta_url'     => 'https://example.com/pro/',
) )->render();
```

---

## 🔵 Low Priority / Nice to Have

### 9. Multi-Step Onboarding Wizard
Guide new users through plugin setup with a step-by-step wizard.

```php
// Proposed API
$framework->create_wizard( array(
    'id'    => 'setup-wizard',
    'steps' => array(
        array(
            'title'   => 'Welcome',
            'content' => 'Let\'s get you set up...',
        ),
        array(
            'title'    => 'Basic Settings',
            'fields'   => array( /* form fields */ ),
        ),
        array(
            'title'   => 'Done!',
            'content' => 'You\'re all set.',
        ),
    ),
) )->show();
```

### 10. Smart Scheduling
Schedule multiple offers in sequence so they don't overlap.

```php
// Proposed API
$framework->schedule_offers( array(
    array( 'start' => '2025-01-01', 'end' => '2025-01-15', 'offer' => $offer_1_args ),
    array( 'start' => '2025-02-01', 'end' => '2025-02-14', 'offer' => $offer_2_args ),
    array( 'start' => '2025-03-15', 'end' => '2025-03-20', 'offer' => $offer_3_args ),
) );
```

### 11. Custom Template Support
Allow developers to register custom offer templates from their plugin.

```php
// Proposed API
$framework->register_template( 'my-custom', plugin_dir_path( __FILE__ ) . 'templates/my-offer.php' );

$framework->create_offer( array(
    'template' => 'my-custom',
    // ...
) )->show();
```

### 12. Email Collection Popup
A popup with an email input field for newsletter/list building.

```php
// Proposed API
$framework->create_email_popup( array(
    'title'       => 'Stay Updated!',
    'description' => 'Subscribe for tips, updates, and exclusive deals.',
    'webhook_url' => 'https://example.com/api/subscribe',
    'fields'      => array( 'email', 'name' ),
) )->show();
```

### 13. Remote Offer Fetching
Fetch offer data from a remote API endpoint to manage offers without plugin updates.

```php
// Proposed API
$framework->create_remote_offer( array(
    'api_url'    => 'https://example.com/api/offers/',
    'cache_time' => 12 * HOUR_IN_SECONDS,
    'fallback'   => array( /* local offer config */ ),
) )->show();
```

### 14. Feature Request / Feedback Form
A built-in feedback form to collect user suggestions directly from the plugin.

```php
// Proposed API
$framework->create_feedback_form( array(
    'title'       => 'Help Us Improve!',
    'webhook_url' => 'https://example.com/api/feedback',
    'fields'      => array(
        array( 'type' => 'rating', 'label' => 'How would you rate this plugin?' ),
        array( 'type' => 'textarea', 'label' => 'Any suggestions?' ),
    ),
) )->render();
```

---

## 🟣 Popup Feature Suggestions

### 15. Popup Auto-Show Delay
Show the popup after a specified delay (e.g., 5 seconds after page load) instead of immediately.

```php
// Proposed API
$framework->create_popup( array(
    'title'      => 'Special Offer!',
    'auto_delay' => 5, // Show after 5 seconds
    'pages'      => array( 'toplevel_page_my-plugin' ),
    // ...
) )->show();
```

### 16. Popup Animation Styles
Allow different entry/exit animations for popups (slide, zoom, bounce, fade).

```php
// Proposed API
$framework->create_popup( array(
    'title'     => 'New Feature!',
    'animation' => 'zoom', // fade, slide-up, slide-down, zoom, bounce
    // ...
) )->show();
```

### 17. Popup Position Variants
Show popups in different positions besides center (bottom-right corner, top banner, sidebar slide-in).

```php
// Proposed API
$framework->create_popup( array(
    'title'    => 'Quick Tip',
    'position' => 'bottom-right', // center, bottom-right, bottom-left, top-banner, slide-right
    'width'    => '350px',
    // ...
) )->show();
```

### 18. Multi-Step Popup / Popup Slides
A popup with multiple steps/slides that users can navigate through (next/previous).

```php
// Proposed API
$framework->create_popup( array(
    'title'  => 'Onboarding',
    'slides' => array(
        array(
            'title'       => 'Step 1: Welcome',
            'description' => 'Thanks for installing!',
            'image_url'   => 'https://example.com/step1.png',
        ),
        array(
            'title'       => 'Step 2: Configure',
            'description' => 'Set up your preferences.',
            'image_url'   => 'https://example.com/step2.png',
        ),
        array(
            'title'       => 'Step 3: Done!',
            'description' => 'You are all set.',
            'buttons'     => array(
                array( 'text' => 'Start Using', 'url' => admin_url( 'admin.php?page=my-plugin' ) ),
            ),
        ),
    ),
) )->show();
```

### 19. Popup with Coupon Code
A popup that displays a coupon code with a one-click copy button.

```php
// Proposed API
$framework->create_popup( array(
    'title'       => '🎉 Exclusive Discount!',
    'description' => 'Use this coupon code to get 30% off.',
    'coupon_code' => 'SAVE30NOW',
    'copy_button' => true, // Adds a "Copy" button next to the coupon
    // ...
) )->show();
```

### 20. Popup Frequency Limiting
Limit how many popups can appear per page load or per session to avoid overwhelming users.

```php
// Proposed API
$framework->set_popup_limit( array(
    'max_per_page'    => 1,   // Max 1 popup per page load
    'max_per_session' => 2,   // Max 2 popups per session
    'cooldown_hours'  => 4,   // Minimum 4 hours between popups
) );
```

### 21. Video Popup
A popup optimized for embedding promotional or tutorial videos.

```php
// Proposed API
$framework->create_popup( array(
    'title'     => 'Watch Our Tutorial',
    'video_url' => 'https://www.youtube.com/embed/VIDEO_ID',
    'width'     => '720px',
    // ...
) )->show();
```

---

## 📊 Priority Matrix

| Feature                     | Impact | Effort | Priority |
|-----------------------------|--------|--------|----------|
| ~~Countdown Timer~~         | High   | Low    | ✅ Done   |
| Analytics & Tracking        | High   | Medium | 🟢 High  |
| Conditional Display Rules   | High   | Medium | 🟢 High  |
| A/B Testing                 | High   | High   | 🟢 High  |
| Review/Rating Request       | Medium | Low    | 🟡 Medium|
| Changelog Popup             | Medium | Low    | 🟡 Medium|
| Notification Center         | Medium | Medium | 🟡 Medium|
| Inline Ads                  | Medium | Low    | 🟡 Medium|
| Onboarding Wizard           | Medium | High   | 🔵 Low   |
| Smart Scheduling            | Low    | Medium | 🔵 Low   |
| Custom Templates            | Medium | Low    | 🔵 Low   |
| Email Popup                 | Low    | Medium | 🔵 Low   |
| Remote Offer Fetching       | Medium | Medium | 🔵 Low   |
| Feedback Form               | Low    | Medium | 🔵 Low   |
| Popup Auto-Show Delay       | Medium | Low    | 🟣 Popup |
| Popup Animation Styles      | Low    | Low    | 🟣 Popup |
| Popup Position Variants     | Medium | Medium | 🟣 Popup |
| Multi-Step Popup             | Medium | High   | 🟣 Popup |
| Popup with Coupon Code      | Medium | Low    | 🟣 Popup |
| Popup Frequency Limiting    | High   | Medium | 🟣 Popup |
| Video Popup                 | Medium | Low    | 🟣 Popup |
