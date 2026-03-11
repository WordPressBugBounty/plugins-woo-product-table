<?php
namespace WOO_PRODUCT_TABLE\Admin\Handle;

class Notice_Framework
{
    public $framework;
    public function __construct()
    {
        require_once WPT_BASE_DIR . 'framework/framework.php';
        $this->framework = \CA_Framework::init( 'woo-product-table', WPT_PLUGIN_FILE_NAME );

                


        // $this->testNotice();

    }

    public function run()
    {
        
    }

    public function offer_about_wpt_on_free_version(){

        //get plugin data like following from get_recommended_plugins()
        $plugins = $this->framework->recommended_plugins($this->get_recommended_plugins(true), 'wpt-recommend-plugins');
        $plugins->show_on_hook('wpt_plugin_recommend_here');//->show();
    }

    

    public function offer_about_wpt_premium(){
        $this->framework->create_offer($this->get_offer_args(
            array(
                'id' => 'wpt-discount-marce-2026',
                // 'badge_text' => 'Limited Time Offer',
                'pages_exclude'          => array('wpt', 'plugins', 'tools'),
                'randomize' => 25
            )
        ))
        ->show();

        $this->framework->create_offer($this->get_offer_args(
            array(
                'id' => 'wpt-2026-marce-inside',
                // 'badge_text' => 'Limited Time Offer',
                'pages'          => array('wpt', 'plugins', 'tools'),
                'template'      => 'flash',
                'reshow_after'  => 5,
                'reshow_unit'   => 'hours',
                //disable dismiss
                'dismiss' => false,
                'randomize' => 80,
            )
        ))
        ->show()
        ->show_on_hook('wpt_plugin_recommend_here');

        //offer eid for inside plugin
        $this->framework->create_popup($this->get_popup_args(
            array(
                'id' => 'wpt-2026-march-eid-offer',
                'reshow_after'  => 5,
                'reshow_unit'   => 'hours',
                'start_date'    => '2026-03-01',
                'image_url' => WPT_ASSETS_URL . 'images/wpt-eid-offer.png',
                
            )
        ))->show();

        //normal for inside
        $this->framework->create_popup($this->get_popup_args(
            array(
                'id' => 'wpt-2026-marce-inside',
                'reshow_after'  => 5,
                'reshow_unit'   => 'hours',
                'start_date'    => '2026-03-31',
                'image_url' => WPT_ASSETS_URL . 'images/logo.png',

            )
        ))->show();

        //normal for outside
        $this->framework->create_popup($this->get_popup_args(
            array(
                'id' => 'wpt-2026-marce-outside',
                'reshow_after'  => 5,
                'reshow_unit'   => 'days',
                'image_url' => WPT_ASSETS_URL . 'images/logo.png',
                'pages' => array(),
                'pages_exclude' => array('wpt', 'plugins', 'tools'),

            )
        ))->show();
        
        
    }
    private function get_popup_args($new_args = array())
    {
        $default_args = $this->get_offer_args(
            array(
                'id' => 'wpt-popup-march-2026',
                'badge_text' => 'UP TO 50% OFF',
                'description'  => '<p>Upgrade to the Pro version and get:</p>
                        <ul>
                            <li>✅ Access to all premium features</li>
                            <li>✅ Unlimited templates, Access to new features</li>
                            <li>✅ Priority support</li>
                            <li>✅ Advanced customization</li>
                            <li>✅ Regular updates</li>
                        </ul>
                        <p>30-day money-back guarantee!</p>',
                'pages'          => array('wpt', 'plugins', 'tools'),
                'template'      => 'flash',
                'reshow_after'  => 5,
                'reshow_unit'   => 'hours',
                'randomize' => 20,
                'image_url' => WPT_ASSETS_URL . 'images/logo.png',
                'buttons' => array(
                    array(
                        'text'  => 'Upgrade Now - 50% OFF',
                        'url'   => 'https://wooproducttable.com/pricing/',
                        'class' => 'ca-fw-btn-primary',
                        'icon'  => 'dashicons-cart',
                    ),
                    array(
                        'text'  => 'Premium Demo',
                        'url'   => 'https://wpprincipal.xyz/',
                        'class' => 'ca-fw-btn-outline',
                        'icon'  => 'dashicons-visibility',
                    ),
                )
            )
        );
        //use wp parse args function
        $final_args = wp_parse_args( $new_args, $default_args );

        return $final_args;

    }
    private function get_offer_args($new_args = array())
    {
        $default_args = array(
            // 'id'             => 'wpt-discount-marce-2026',
            'title'          => '🎉 Special discount for <strong>Woo Product Table</strong>',
            'description'    => 'Get your special discount now! <b>Limited time offer</b> just for you. Maximize your savings with this exclusive deal. Claim your discount!',
            'highlight_text' => 'UP TO 50% OFF',
            'badge_text'     => 'FLASH SALE',
            'template'       => 'developer', //flash, starter, simple
            'start_date'     => '2026-03-01',
            'dismiss_type'   => 'temporary',
            'end_date'       => '2026-04-30',
            // 'pages_exclude'          => array('wpt', 'plugins', 'tools'),
            // 'pages_exclude'  => array('wpt-settings'),
            // 'show_countdown' => true,
            'reshow_unit'    => 'days',
            'reshow_after'   => 5,
            'image_url'    => WPT_ASSETS_URL . 'images/logo.gif',
            // 'randomize'     => 50,
            'buttons' => $this->get_wpt_purchase_buttons(),
        );
        //use wp parse args function
        $final_args = wp_parse_args( $new_args, $default_args );

        return $final_args;

    }

    private function get_wpt_purchase_buttons(){
        return array(
            array(
                'text'  => 'Claim Discount',
                'url'   => 'https://wooproducttable.com/pricing/',
                'class' => 'ca-fw-btn-primary',
                'icon'  => 'dashicons-cart',
                'target' => '_blank',
            ),
            array(
                'text'   => 'View Features',
                'url'    => 'https://wooproducttable.com/',
                'class'  => 'ca-fw-btn-secondary',
                'target' => '_blank',
            ),
            //wp org link of plugins
            array(
                'text'  => 'WordPress.org',
                'url'   => 'https://wordpress.org/plugins/woo-product-table/',
                'class' => 'ca-fw-btn-secondary',
                'target' => '_blank',
            ),
        );
    }

    /**
	 * Get recommended plugins list.
	 *
	 * @param bool $shorted Whether to return a shuffled subset.
	 * @return array
	 */
	private function get_recommended_plugins( $shorted = false ) {
		$plugins = array(
			array(
				'slug'        => 'woo-product-table',
				'name'        => 'Product Table for WooCommerce',
				'description' => __( 'Display WooCommerce products in a table layout.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/woo-product-table/assets/icon-256x256.gif',
				'author'      => 'Bizzplugin',
				'path'        => 'woo-product-table/woo-product-table.php',
				'url'         => 'https://wordpress.org/plugins/woo-product-table/',
			),
			array(
				'slug'        => 'woo-min-max-quantity-step-control-single',
				'name'        => 'Min Max Control - Control Quantity for WooCommerce',
				'description' => __( 'Control minimum and maximum quantities and step values for WooCommerce products.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/woo-min-max-quantity-step-control-single/assets/icon-256x256.png',
				'author'      => 'Bizzplugin',
				'path'        => 'woo-min-max-quantity-step-control-single/wcmmq.php',
				'url'         => 'https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/',
			),
			array(
				'slug'        => 'product-sync-master-sheet',
				'name'        => 'Sync Master Sheet - Sync with Google Sheet',
				'description' => __( 'Sync your product data with a google sheet.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/product-sync-master-sheet/assets/icon-256x256.gif',
				'author'      => 'Bizzplugin',
				'path'        => 'product-sync-master-sheet/product-sync-master-sheet.php',
				'url'         => 'https://wordpress.org/plugins/product-sync-master-sheet/',
			),
			array(
				'slug'        => 'bizzorder',
				'name'        => 'Bizzview - Quick View for WooCommerce',
				'description' => __( 'Add quick view functionality to your WooCommerce products.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/ca-quick-view/assets/icon-256x256.png?new',
				'author'      => 'Bizzplugin',
				'path'        => 'ca-quick-view/starter.php',
				'url'         => 'https://wordpress.org/plugins/ca-quick-view/',
			),
			array(
				'slug'        => 'bizzswatches',
				'name'        => 'Bizzswatches - Color and Image Swatches',
				'description' => __( 'Add color and image swatches to your WooCommerce products.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/bizzswatches/assets/icon-256x256.png?new',
				'author'      => 'Bizzplugin',
				'path'        => 'bizzswatches/bizzswatches.php',
				'url'         => 'https://wordpress.org/plugins/bizzswatches/',
			),
			array(
				'slug'        => 'wc-quantity-plus-minus-button',
				'name'        => 'Quantity Plus Minus Button for WooCommerce',
				'description' => __( 'Add plus and minus buttons to WooCommerce quantity fields.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/wc-quantity-plus-minus-button/assets/icon-256x256.png',
				'author'      => 'Bizzplugin',
				'path'        => 'wc-quantity-plus-minus-button/init.php',
				'url'         => 'https://wordpress.org/plugins/wc-quantity-plus-minus-button/',
			),
			array(
				'slug'        => 'bizzmudra',
				'name'        => 'Bizzmudra - Multi Currency Switcher',
				'description' => __( 'A multi currency switcher for WooCommerce.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/bizzmudra/assets/icon-256x256.png',
				'author'      => 'Bizzplugin',
				'path'        => 'bizzmudra/bizzmudra.php',
				'url'         => 'https://wordpress.org/plugins/bizzmudra/',
			),
			array(
				'slug'        => 'sheet-to-wp-table-for-google-sheet',
				'name'        => 'Sheet to Table Live Sync for Google Sheet',
				'description' => __( 'Display Google Sheet data in WordPress tables with live sync.', 'bizzswatches' ),
				'icon'   => 'https://ps.w.org/sheet-to-wp-table-for-google-sheet/assets/icon-256x256.png',
				'author'      => 'Bizzplugin',
				'path'        => 'sheet-to-wp-table-for-google-sheet/sheet-to-wp-table-for-google-sheet.php',
				'url'         => 'https://wordpress.org/plugins/sheet-to-wp-table-for-google-sheet/',
			),
		);

		if ( $shorted ) {
			shuffle( $plugins );
			$plugins = array_slice( $plugins, 0, 6 );
		}

		return $plugins;
	}

    /**
     * Only for test purposes
     * not for production use
     *
     * @return void
     */
    public function testNotice()
    {
        $this->framework->create_offer(array(
            'id'          => 'special-offer-123',
            'title'       => 'Special - Offers!',
            'description' => '40% Off <strong>Woo Product Table</strong>',
            'template'    => 'flash',
            'buttons'     => array(
                array(
                    'text' => 'Get It Now',
                    'url'  => 'https://example.com/pricing/',
                ),
                array(
                    'text' => 'No, Thanks',
                    'url'  => '#',
                ),
                array(
                    'text' => 'Maybe Later',
                    'url'  => '#',
                ),
            ),
        ))->show();

        $this->framework->create_offer(array(
            'id'             => 'flash-sale-2026',
            'title'          => '⚡ Flash Sale!',
            'description'    => 'Only 24 hours left! Grab the deal now.',
            'highlight_text' => 'UP TO 80% OFF',
            'badge_text'     => 'FLASH SALE',
            'template'       => 'flash',
            'start_date'     => '2026-03-01',
            'end_date'       => '2026-03-23',
            'pages'          => array('wpt', 'plugins', 'tools'),
            'show_countdown' => true,
            'reshow_unit'    => 'hours',
            'reshow_after'   => 2,
            // 'randomize'     => 10,
            'buttons' => array(
                array(
                    'text'  => 'Upgrade to Pro',
                    'url'   => 'https://example.com/pro/',
                    'class' => 'ca-fw-btn-primary',
                    'icon'  => 'dashicons-star-filled',
                ),
                array(
                    'text'   => 'View Features',
                    'url'    => 'https://example.com/features/',
                    'class'  => 'ca-fw-btn-secondary',
                    'target' => '_blank',
                ),
                array(
                    'text'  => 'Documentation',
                    'url'   => 'https://example.com/docs/',
                    'class' => 'ca-fw-btn-outline',
                ),
            ),
        ))->show_on_hook('wpt_plugin_recommend_here')->show();

        $this->framework->create_popup(array(
            'id'           => 'upgradse-popup-22322euu',
            'title'        => '🚀 Unlock Premium Inside',
            'description'  => '<p>Upgrade to the Pro version and get:</p>
                        <ul>
                            <li>✅ Unlimited templates</li>
                            <li>✅ Priority support</li>
                            <li>✅ Advanced customization</li>
                            <li>✅ Regular updates</li>
                        </ul>
                        <p>30-day money-back guarantee!</p>',
            'badge_text'   => 'FLASH SALE',
            'image_url'    => WPT_ASSETS_URL . 'images/logo.png',
            'dismiss_type' => 'temporary', //permanent
            'reshow_after' => 8,
            'reshow_unit'  => 'seconds',
            // 'randomize'    => 100,
            'pages'        => array(),
            'width'        => '550px',
            'buttons'      => array(
                array(
                    'text'  => 'Upgrade Now - 50% OFF',
                    'url'   => 'https://example.com/pricing/',
                    'class' => 'ca-fw-btn-primary',
                    'icon'  => 'dashicons-star-filled',
                ),
                array(
                    'text'  => 'Maybe Later',
                    'url'   => '#',
                    'class' => 'ca-fw-btn-secondary',
                ),
                array(
                    'text'  => 'No, Thanks',
                    'url'   => '#',
                    'class' => 'ca-fw-btn-outline',
                    'icon'  => 'dashicons-no-alt',
                ),
            ),
        ))->show();

        $this->framework->create_popup(array(
            'id'           => 'adse-popup-22322euu',
            'title'        => '🚀 Unlock Saiful Islam',
            'description'  => '<p>Upgrade to the Pro version and get:</p>
                        <ul>
                            <li>✅ Unlimited templates</li>
                            <li>✅ Priority support</li>
                            <li>✅ Advanced customization</li>
                            <li>✅ Regular updates</li>
                            <li>✅ Unlimited templates</li>
                            <li>✅ Priority support</li>
                            <li>✅ Advanced customization</li>
                            <li>✅ Regular updates</li>
                        </ul>
                        <p>30-day money-back guarantee!</p>',
            'badge_text'   => 'PRO VERSION',
            // 'image_url'    => WPT_ASSETS_URL . 'images/logo.png',
            'dismiss_type' => 'temporary', //permanent
            'reshow_after' => 6,
            'reshow_unit'  => 'seconds',
            // 'randomize'    => 100,
            'pages'        => array(),
            'width'        => '550px',
            'buttons'      => array(
                array(
                    'text'  => 'Upgrade Now - 50% OFF',
                    'url'   => 'https://example.com/pricing/',
                    'class' => 'ca-fw-btn-primary',
                    'icon'  => 'dashicons-star-filled',
                ),
                array(
                    'text'  => 'Maybe Later',
                    'url'   => '#',
                    'class' => 'ca-fw-btn-secondary',
                ),
                array(
                    'text'  => 'No, Thanks',
                    'url'   => '#',
                    'class' => 'ca-fw-btn-outline',
                    'icon'  => 'dashicons-no-alt',
                ),
            ),
        ))->show();

        $plugins = $this->framework->recommended_plugins(array(
            //contact form 7
            array(
                'name'        => 'Contact Form 7',
                'slug'        => 'contact-form-7',
                'path'        => 'contact-form-7/wp-contact-form-7.php',
                'description' => 'Just another contact form plugin. Simple but flexible.',
                'icon'        => 'https://ps.w.org/contact-form-7/assets/icon-256x256.png',
            ),
            array(
                'name'        => 'developer developer developrff',
                'slug'        => 'developer-developer-developer',
                'path'        => 'developer-developer-developer/developer-developer-developer.php',
                'description' => 'Starter addons for Elementor page builder.',
                'icon'        => 'https://ps.w.org/developer-developer-developer/assets/icon-256x256.png',
            ),
            array(
                'name'        => 'Developer Plugin 2',
                'slug'        => 'developer-plugin-2',
                'path'        => 'developer-plugin-2/developer-plugin-2.php',
                'description' => 'Another great plugin by the same team.',
                'icon'        => 'https://ps.w.org/developer-plugin-2/assets/icon-256x256.png',
            ),
            array(
                'name'        => 'Developer Plugin 3',
                'slug'        => 'woo-product-table',
                'path'        => 'woo-product-table/woo-product-table.php',
                'description' => 'Extend your workflow with this tool.',
            ),
        ), 'wpt-test');
        $plugins->show_on_hook('wpt_plugin_recommend_here')->show();
    }
}