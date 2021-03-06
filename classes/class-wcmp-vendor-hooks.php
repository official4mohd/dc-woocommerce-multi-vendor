<?php

/**
 * Description of WCMp_Vendor_Hooks
 *
 * @author WC Marketplace
 */
class WCMp_Vendor_Hooks {

    function __construct() {
        add_action('wcmp_vendor_dashboard_navigation', array(&$this, 'wcmp_create_vendor_dashboard_navigation'));
        add_action('wcmp_vendor_dashboard_content', array(&$this, 'wcmp_create_vendor_dashboard_content'));
        add_action('before_wcmp_vendor_dashboard', array(&$this, 'save_vendor_dashboard_data'));

        add_action('wcmp_vendor_dashboard_vendor-announcements_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_announcements_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-orders_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_orders_endpoint'));
        add_action('wcmp_vendor_dashboard_storefront_endpoint', array(&$this, 'wcmp_vendor_dashboard_storefront_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-policies_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_policies_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-billing_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_billing_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-shipping_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_shipping_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-report_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_report_endpoint'));

        add_action('wcmp_vendor_dashboard_add-product_endpoint', array(&$this, 'wcmp_vendor_dashboard_add_product_endpoint'));
        add_action('wcmp_vendor_dashboard_products_endpoint', array(&$this, 'wcmp_vendor_dashboard_products_endpoint'));
        add_action('wcmp_vendor_dashboard_add-coupon_endpoint', array(&$this, 'wcmp_vendor_dashboard_add_coupon_endpoint'));
        add_action('wcmp_vendor_dashboard_coupons_endpoint', array(&$this, 'wcmp_vendor_dashboard_coupons_endpoint'));

        add_action('wcmp_vendor_dashboard_vendor-withdrawal_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_withdrawal_endpoint'));
        add_action('wcmp_vendor_dashboard_transaction-details_endpoint', array(&$this, 'wcmp_vendor_dashboard_transaction_details_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-knowledgebase_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_knowledgebase_endpoint'));
        add_action('wcmp_vendor_dashboard_vendor-report_issue_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_report_issue_endpoint'));

        add_filter('the_title', array(&$this, 'wcmp_vendor_dashboard_endpoint_title'));
        add_filter('wcmp_vendor_dashboard_menu_vendor_policies_capability', array(&$this, 'wcmp_vendor_dashboard_menu_vendor_policies_capability'));
        add_filter('wcmp_vendor_dashboard_menu_vendor_withdrawal_capability', array(&$this, 'wcmp_vendor_dashboard_menu_vendor_withdrawal_capability'));
        add_filter('wcmp_vendor_dashboard_menu_vendor_shipping_capability', array(&$this, 'wcmp_vendor_dashboard_menu_vendor_shipping_capability'));
        add_action('before_wcmp_vendor_dashboard_content', array(&$this, 'before_wcmp_vendor_dashboard_content'));
        add_action('wp', array(&$this, 'wcmp_add_theme_support'), 15);
    }

    /**
     * Create vendor dashboard menu
     * array $args
     */
    public function wcmp_create_vendor_dashboard_navigation($args = array()) {
        global $WCMp;
        $WCMp->template->get_template('vendor-dashboard/navigation.php', array('nav_items' => $this->wcmp_get_vendor_dashboard_navigation(), 'args' => $args));
    }

    public function wcmp_get_vendor_dashboard_navigation() {
        $vendor_nav = array(
            'dashboard' => array(
                'label' => __('Dashboard', 'dc-woocommerce-multi-vendor')
                , 'url' => wcmp_get_vendor_dashboard_endpoint_url('dashboard')
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_dashboard_capability', true)
                , 'position' => 0
                , 'submenu' => array()
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-dashboard'
            ),
            'store-settings' => array(
                'label' => __('Store Settings', 'dc-woocommerce-multi-vendor')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_store_settings_capability', true)
                , 'position' => 10
                , 'submenu' => array(
                    'storefront' => array(
                        'label' => __('Storefront', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_store_settings_endpoint', 'vendor', 'general', 'storefront'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_shop_front_capability', true)
                        , 'position' => 10
                        , 'link_target' => '_self'
                        , 'nav_icon' => 'la la-tag'
                    ),
                    'vendor-policies' => array(
                        'label' => __('Policies', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_policies_endpoint', 'vendor', 'general', 'vendor-policies'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_policies_capability', false)
                        , 'position' => 20
                        , 'link_target' => '_self'
                    ),
                    'vendor-billing' => array(
                        'label' => __('Billing', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_billing_endpoint', 'vendor', 'general', 'vendor-billing'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_billing_capability', true)
                        , 'position' => 30
                        , 'link_target' => '_self'
                    ),
                    'vendor-shipping' => array(
                        'label' => __('Shipping', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_shipping_endpoint', 'vendor', 'general', 'vendor-shipping'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_shipping_capability', wc_shipping_enabled())
                        , 'position' => 40
                        , 'link_target' => '_self'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-gear'
            ),
            'vendor-products' => array(
                'label' => __('Product Manager', 'dc-woocommerce-multi-vendor')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_products_capability', 'edit_products')
                , 'position' => 20
                , 'submenu' => array(
                    'products' => array(
                        'label' => __('All Products', 'dc-woocommerce-multi-vendor')
                        , 'url' => apply_filters('wcmp_vendor_products', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_products_endpoint', 'vendor', 'general', 'products')))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_products_capability', 'edit_products')
                        , 'position' => 10
                        , 'link_target' => '_self'
                    ),
                    'add-product' => array(
                        'label' => __('Add Product', 'dc-woocommerce-multi-vendor')
                        , 'url' => apply_filters('wcmp_vendor_submit_product', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_add_product_capability', 'edit_products')
                        , 'position' => 20
                        , 'link_target' => '_self'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-cubes'
            ),
            'vendor-promte' => array(
                'label' => __('Coupons', 'dc-woocommerce-multi-vendor')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_promte_capability', 'edit_shop_coupons')
                , 'position' => 30
                , 'submenu' => array(
                    'coupons' => array(
                        'label' => __('All Coupons', 'dc-woocommerce-multi-vendor')
                        , 'url' => apply_filters('wcmp_vendor_coupons', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_coupons_endpoint', 'vendor', 'general', 'coupons')))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_coupons_capability', 'edit_shop_coupons')
                        , 'position' => 10
                        , 'link_target' => '_self'
                    ),
                    'add-coupon' => array(
                        'label' => __('Add Coupon', 'dc-woocommerce-multi-vendor')
                        , 'url' => apply_filters('wcmp_vendor_submit_coupon', wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_coupon_endpoint', 'vendor', 'general', 'add-coupon')))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_add_coupon_capability', 'edit_shop_coupons')
                        , 'position' => 20
                        , 'link_target' => '_self'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-tag'
            ),
            'vendor-report' => array(
                'label' => __('Stats / Reports', 'dc-woocommerce-multi-vendor')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_report_capability', true)
                , 'position' => 40
                , 'submenu' => array(
                    'vendor-report' => array(
                        'label' => __('Overview', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_report_endpoint', 'vendor', 'general', 'vendor-report'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_report_capability', true)
                        , 'position' => 10
                        , 'link_target' => '_self'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-bar-chart'
            ),
            'vendor-orders' => array(
                'label' => __('Orders', 'dc-woocommerce-multi-vendor')
                , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders'))
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_orders_capability', true)
                , 'position' => 50
                , 'submenu' => array()
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-inbox'
            ),
            'vendor-payments' => array(
                'label' => __('Payments', 'dc-woocommerce-multi-vendor')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_payments_capability', true)
                , 'position' => 60
                , 'submenu' => array(
                    'vendor-withdrawal' => array(
                        'label' => __('Withdrawal', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_withdrawal_endpoint', 'vendor', 'general', 'vendor-withdrawal'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_withdrawal_capability', false)
                        , 'position' => 10
                        , 'link_target' => '_self'
                    ),
                    'transaction-details' => array(
                        'label' => __('History', 'dc-woocommerce-multi-vendor')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_transaction_details_endpoint', 'vendor', 'general', 'transaction-details'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_transaction_details_capability', true)
                        , 'position' => 20
                        , 'link_target' => '_self'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-money'
            ),
            'vendor-knowledgebase' => array(
                'label' => __('Knowledgebase', 'dc-woocommerce-multi-vendor')
                , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_knowledgebase_endpoint', 'vendor', 'general', 'vendor-knowledgebase'))
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_knowledgebase_capability', true)
                , 'position' => 70
                , 'submenu' => array()
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-graduation-cap'
            ),
            'vendor-report_issue' => array(
                'label' => __('Report a bug', 'dc-woocommerce-multi-vendor')
                , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_report_issue_endpoint', 'vendor', 'general', 'vendor-report_issue'))
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_knowledgebase_capability', true)
                , 'position' => 80
                , 'submenu' => array()
                , 'link_target' => '_self'
                , 'nav_icon' => 'la la-bug'
            )
        );
        return apply_filters('wcmp_vendor_dashboard_nav', $vendor_nav);
    }

    /**
     * Display Vendor dashboard Content
     * @global object $wp
     * @global object $WCMp
     * @return null
     */
    public function wcmp_create_vendor_dashboard_content() {
        global $wp, $WCMp;
        foreach ($wp->query_vars as $key => $value) {
            // Ignore pagename and page param.
            if (in_array($key, array('page', 'pagename'))) {
                continue;
            }
            do_action('before_wcmp_vendor_dashboard_content', $key);
            if (has_action('wcmp_vendor_dashboard_' . $key . '_endpoint')) {
                if ($this->current_vendor_can_view($WCMp->endpoints->get_current_endpoint())) {
                    do_action('wcmp_vendor_dashboard_' . $key . '_endpoint', $value);
                }
                return;
            }
            do_action('after_wcmp_vendor_dashboard_content');
        }
        $WCMp->template->get_template('vendor-dashboard/dashboard.php');
    }

    public function wcmp_create_vendor_dashboard_breadcrumbs($current_endpoint, $nav = array(), $firstLevel = true) {
        $nav = !empty($nav) ? $nav : $this->wcmp_get_vendor_dashboard_navigation();
        $resultArray = array();
        $current_endpoint = $current_endpoint ? $current_endpoint : 'dashboard';
        foreach ($nav as $endpoint => $menu) {
            if ($endpoint == $current_endpoint) {
                if ($firstLevel) {
                    return '<i class="' . $menu['nav_icon'] . '"></i><span> ' . $menu['label'] . '</span>';
                } else {
                    return array('<span> ' . $menu['label'] . '</span>');
                }
            }
            if (isset($menu['submenu']) && !empty($menu['submenu'])) {
                $result = $this->wcmp_create_vendor_dashboard_breadcrumbs($current_endpoint, $menu['submenu'], false);
                if ($result) {
                    $resultArray = array_merge($result);
                    if (isset($menu['submenu'][$current_endpoint]['nav_icon']) && !empty($menu['submenu'][$current_endpoint]['nav_icon'])) {
                        $resultArray[] = '<i class="' . $menu['submenu'][$current_endpoint]['nav_icon'] . '"></i>';
                    } else {
                        $resultArray[] = '<i class="' . $menu['nav_icon'] . '"></i>';
                    }
                    if (!$firstLevel) {
                        return $resultArray;
                    }
                }
            }
        }
        if (count($resultArray)) {
            return implode(array_reverse($resultArray));
        }
        return false;
    }

    public function current_vendor_can_view($current_endpoint = 'dashboard') {
        $nav = $this->wcmp_get_vendor_dashboard_navigation();
        foreach ($nav as $endpoint => $menu) {
            if ($endpoint == $current_endpoint) {
                return current_user_can($menu['capability']) || true === $menu['capability'];
            } else if (!empty($menu['submenu']) && array_key_exists($current_endpoint, $menu['submenu']) && isset($menu['submenu'][$current_endpoint]['capability'])) {
                return current_user_can($menu['submenu'][$current_endpoint]['capability']) || true === $menu['submenu'][$current_endpoint]['capability'];
            }
        }
        return true;
    }

    /**
     * Display Vendor Announcements content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_announcements_endpoint() {
        global $WCMp;
        $frontend_style_path = $WCMp->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $frontend_script_path = $WCMp->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $suffix = defined('WCMP_SCRIPT_DEBUG') && WCMP_SCRIPT_DEBUG ? '' : '.min';
        //wp_enqueue_style('font-vendor_announcements', '//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic', array(), $WCMp->version);
        //wp_enqueue_style('ui_vendor_announcements', '//code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css', array(), $WCMp->version);
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('wcmp_new_vandor_announcements_js', $frontend_script_path . 'wcmp_vendor_announcements' . $suffix . '.js', array('jquery'), $WCMp->version, true);
        //wp_enqueue_script('jquery');
        //wp_enqueue_script('wcmp_new_vandor_announcements_js_lib_ui', '//code.jquery.com/ui/1.10.4/jquery-ui.js', array('jquery'), $WCMp->version, true);
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $WCMp->template->get_template('vendor-dashboard/vendor-announcements.php', array('vendor_announcements' => $vendor->get_announcements()));
    }

    /**
     * Display vendor dashboard shop front content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_storefront_endpoint() {
        global $WCMp;
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $user_array = $WCMp->user->get_vendor_fields($vendor->id);
        $WCMp->library->load_dashboard_upload_lib();
        $WCMp->library->load_gmap_api();
        $WCMp->template->get_template('vendor-dashboard/shop-front.php', $user_array);
    }

    /**
     * display vendor policies content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_policies_endpoint() {
        global $WCMp;
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $user_array = $WCMp->user->get_vendor_fields($vendor->id);
        $WCMp->template->get_template('vendor-dashboard/vendor-policy.php', $user_array);
    }

    /**
     * Display Vendor billing settings content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_billing_endpoint() {
        global $WCMp;
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $user_array = $WCMp->user->get_vendor_fields($vendor->id);
        $WCMp->template->get_template('vendor-dashboard/vendor-billing.php', $user_array);
    }

    /**
     * Display vendor shipping content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_shipping_endpoint() {
        global $WCMp;
        $wcmp_payment_settings_name = get_option('wcmp_payment_settings_name');
        $_vendor_give_shipping = get_user_meta(get_current_vendor_id(), '_vendor_give_shipping', true);
        if (isset($wcmp_payment_settings_name['give_shipping']) && empty($_vendor_give_shipping)) {
            $WCMp->template->get_template('vendor-dashboard/vendor-shipping.php');
        } else {
            echo '<p class="wcmp_headding3">' . __('Sorry you are not authorized for this pages. Please contact with admin.', 'dc-woocommerce-multi-vendor') . '</p>';
        }
    }

    /**
     * Display vendor report content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_report_endpoint() {
        global $WCMp;
        if (isset($_POST['wcmp_stat_start_dt'])) {
            $start_date = $_POST['wcmp_stat_start_dt'];
        } else {
            // hard-coded '01' for first day     
            $start_date = date('01-m-Y');
        }

        if (isset($_POST['wcmp_stat_end_dt'])) {
            $end_date = $_POST['wcmp_stat_end_dt'];
        } else {
            // hard-coded '01' for first day
            $end_date = date('t-m-Y');
        }
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        $WCMp_Plugin_Post_Reports = new WCMp_Report();
        $array_report = $WCMp_Plugin_Post_Reports->vendor_sales_stat_overview($vendor, $start_date, $end_date);
        $WCMp->template->get_template('vendor-dashboard/vendor-report.php', $array_report);
    }

    public function wcmp_vendor_dashboard_add_product_endpoint() {
        global $WCMp, $wp;
        $WCMp->library->load_qtip_lib();
        $WCMp->library->load_colorpicker_lib();
        $WCMp->library->load_datepicker_lib();
        $WCMp->library->load_frontend_upload_lib();
        $WCMp->library->load_accordian_lib();
        $WCMp->library->load_select2_lib();
        $WCMp->library->load_tinymce_lib();
        $suffix = defined('WCMP_SCRIPT_DEBUG') && WCMP_SCRIPT_DEBUG ? '' : '.min';

        if (get_wcmp_vendor_settings('is_singleproductmultiseller', 'general') == 'Enable') {
            wp_enqueue_script('wcmp_admin_product_auto_search_js', $WCMp->plugin_url . 'assets/admin/js/admin-product-auto-search' . $suffix . '.js', array('jquery'), $WCMp->version, true);
        }
        wp_enqueue_style('product_manager_css', $WCMp->plugin_url . 'assets/frontend/css/product_manager.css', array(), $WCMp->version);
        wp_enqueue_script('product_manager_js', $WCMp->plugin_url . 'assets/frontend/js/product_manager.js', array('jquery', 'jquery-ui-accordion'), $WCMp->version, true);

        $WCMp_fpm_messages = get_forntend_product_manager_messages();
        wp_localize_script('product_manager_js', 'product_manager_messages', $WCMp_fpm_messages);
        $pro_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')];
        if ($pro_id) {
            $wcmp_have_parent_product_id = get_post_meta($pro_id, '_wcmp_parent_product_id', true);
            if ($wcmp_have_parent_product_id && apply_filters('wcmp_singleproductmultiseller_edit_product_title_disabled', true)) {
                wp_add_inline_script('product_manager_js', '(function ($) { 
                  $("#product_manager_form #title").prop("disabled", true);
              })(jQuery)');
            }
        }
        $WCMp->template->get_template('vendor-dashboard/product-manager/add-product.php', array('pro_id' => $pro_id));
    }

    public function wcmp_vendor_dashboard_products_endpoint() {
        global $WCMp;
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            $WCMp->library->load_dataTable_lib();
            $products_table_headers = array(
                'image' => '<span class="dashicons dashicons-format-image"></span>',
                'name' => __('Product', 'dc-woocommerce-multi-vendor'),
                'price' => __('Price', 'dc-woocommerce-multi-vendor'),
                'stock' => __('Stock', 'dc-woocommerce-multi-vendor'),
                'categories' => __('Categories', 'dc-woocommerce-multi-vendor'),
                'date' => __('Date', 'dc-woocommerce-multi-vendor'),
                'status' => __('Status', 'dc-woocommerce-multi-vendor'),
            );
            $products_table_headers = apply_filters('wcmp_vendor_dashboard_product_list_table_headers', $products_table_headers);
            $table_init = apply_filters('wcmp_vendor_dashboard_product_list_table_init', array(
                'ordering' => 'true',
                'searching' => 'true',
                'emptyTable' => __('No products found!', 'dc-woocommerce-multi-vendor'),
                'info' => __('Showing _START_ to _END_ of _TOTAL_ products', 'dc-woocommerce-multi-vendor'),
                'infoEmpty' => __('Showing 0 to 0 of 0 products', 'dc-woocommerce-multi-vendor'),
                'lengthMenu' => __('Show products _MENU_', 'dc-woocommerce-multi-vendor'),
                'zeroRecords' => __('No matching products found', 'dc-woocommerce-multi-vendor'),
                'search' => __('Search:', 'dc-woocommerce-multi-vendor'),
                'next' => __('Next', 'dc-woocommerce-multi-vendor'),
                'previous' => __('Previous', 'dc-woocommerce-multi-vendor'),
            ));

            $WCMp->template->get_template('vendor-dashboard/product-manager/products.php', array('products_table_headers' => $products_table_headers, 'table_init' => $table_init));
        }
    }

    public function wcmp_vendor_dashboard_add_coupon_endpoint() {
        global $WCMp, $wp;
        $WCMp->library->load_qtip_lib();
        $WCMp->library->load_colorpicker_lib();
        $WCMp->library->load_datepicker_lib();
        $WCMp->library->load_select2_lib();
        wp_enqueue_script('coupon_manager_js', $WCMp->plugin_url . 'assets/frontend/js/coupon_manager.js', array('jquery', 'jquery-ui-accordion'), $WCMp->version, true);

        $WCMp_fpm_messages = get_forntend_coupon_manager_messages();
        wp_localize_script('coupon_manager_js', 'coupon_manager_messages', $WCMp_fpm_messages);
        $coupon_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_add_coupon_endpoint', 'vendor', 'general', 'add-coupon')];
        $WCMp->template->get_template('vendor-dashboard/coupon-manager/add-coupons.php', array('couponid' => $coupon_id));
    }

    public function wcmp_vendor_dashboard_coupons_endpoint() {
        global $WCMp;
        if (is_user_logged_in() && is_user_wcmp_vendor(get_current_vendor_id())) {
            $WCMp->library->load_dataTable_lib();
            $WCMp->template->get_template('vendor-dashboard/coupon-manager/coupons.php');
        }
    }

    /**
     * Dashboard order endpoint contect
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_orders_endpoint() {
        global $WCMp, $wp;
        $vendor = get_current_vendor();
        if (isset($_POST['wcmp-submit-mark-as-ship'])) {
            $order_id = $_POST['order_id'];
            $tracking_id = $_POST['tracking_id'];
            $tracking_url = $_POST['tracking_url'];
            $vendor->set_order_shipped($order_id, $tracking_id, $tracking_url);
        }
        $vendor_order = $wp->query_vars[get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders')];
        if (!empty($vendor_order)) {
            $WCMp->template->get_template('vendor-dashboard/vendor-orders/vendor-order-details.php', array('order_id' => $vendor_order));
        } else {
            $WCMp->library->load_dataTable_lib();
            $frontend_script_path = $WCMp->plugin_url . 'assets/frontend/js/';
            $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
            $suffix = defined('WCMP_SCRIPT_DEBUG') && WCMP_SCRIPT_DEBUG ? '' : '.min';
            wp_enqueue_script('vendor_orders_js', $frontend_script_path . 'vendor_orders' . $suffix . '.js', array('jquery'), $WCMp->version, true);
            wp_localize_script('vendor_orders_js', 'wcmp_mark_shipped_text', array('text' => __('Order is marked as shipped.', 'dc-woocommerce-multi-vendor'), 'image' => $WCMp->plugin_url . 'assets/images/roket-green.png'));

            if (!empty($_POST['wcmp_start_date_order'])) {
                $start_date = $_POST['wcmp_start_date_order'];
            } else {
                $start_date = date('01-m-Y');
            }

            if (!empty($_POST['wcmp_end_date_order'])) {
                $end_date = $_POST['wcmp_end_date_order'];
            } else {
                $end_date = date('t-m-Y');
            }
            wp_localize_script('vendor_orders_js', 'vendor_orders_args', array('start_date' => strtotime($start_date), 'end_date' => strtotime($end_date . ' +1 day')));
            $WCMp->template->get_template('vendor-dashboard/vendor-orders.php', array('vendor' => $vendor));
        }
    }

    /**
     * Display Vendor Withdrawal Content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_withdrawal_endpoint() {
        global $WCMp, $wp;
        $transaction_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_vendor_withdrawal_endpoint', 'vendor', 'general', 'vendor-withdrawal')];
        if (!empty($transaction_id)) {
            $WCMp->template->get_template('vendor-dashboard/vendor-withdrawal/vendor-withdrawal-request.php', array('transaction_id' => $transaction_id));
        } else {
            $vendor = get_wcmp_vendor(get_current_vendor_id());
            if ($vendor) {
                $WCMp->library->load_dataTable_lib();
                $meta_query['meta_query'] = array(
                    array(
                        'key' => '_paid_status',
                        'value' => 'unpaid',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_commission_vendor',
                        'value' => absint($vendor->term_id),
                        'compare' => '='
                    )
                );
                $vendor_unpaid_orders = $vendor->get_orders(false, false, $meta_query);
                // withdrawal table init
                $table_init = apply_filters('wcmp_vendor_dashboard_payment_withdrawal_table_init', array(
                    'ordering' => 'false',
                    'searching' => 'false',
                    'emptyTable' => __('No orders found!', 'dc-woocommerce-multi-vendor'),
                    'info' => __('Showing _START_ to _END_ of _TOTAL_ orders', 'dc-woocommerce-multi-vendor'),
                    'infoEmpty' => __('Showing 0 to 0 of 0 orders', 'dc-woocommerce-multi-vendor'),
                    'lengthMenu' => __('Show orders _MENU_', 'dc-woocommerce-multi-vendor'),
                    'zeroRecords' => __('No matching orders found', 'dc-woocommerce-multi-vendor'),
                    'search' => __('Search:', 'dc-woocommerce-multi-vendor'),
                    'next' => __('Next', 'dc-woocommerce-multi-vendor'),
                    'previous' => __('Previous', 'dc-woocommerce-multi-vendor'),
                ));

                $WCMp->template->get_template('vendor-dashboard/vendor-withdrawal.php', array('vendor' => $vendor, 'vendor_unpaid_orders' => $vendor_unpaid_orders, 'table_init' => $table_init));
            }
        }
    }

    /**
     * Display transaction details content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_transaction_details_endpoint() {
        global $WCMp;
        $user_id = get_current_vendor_id();
        if (is_user_wcmp_vendor($user_id)) {
            $WCMp->library->load_dataTable_lib();
            $WCMp->template->get_template('vendor-dashboard/vendor-transactions.php');
        }
    }

    /**
     * Display Vendor university content
     * @global object $WCMp
     */
    public function wcmp_vendor_dashboard_vendor_knowledgebase_endpoint() {
        global $WCMp;
        wp_enqueue_style('jquery-ui-style');
        wp_enqueue_script('jquery-ui-accordion');
        $WCMp->template->get_template('vendor-dashboard/vendor-university.php');
    }

    public function wcmp_vendor_dashboard_vendor_report_issue_endpoint() {
        ?>
        <div class="col-md-12">
            <div class="panel panel-default panel-pading">
                <div class="panel-heading"><h3><?php _e('Facing issue with WCMp 3.0 beta? Report to our developers', 'dc-woocommerce-multi-vendor'); ?></h3></div>
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data" enctype="multipart/form-data" action="" class="wcmp_beta_report_issue wcmp_subtab_content form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3"><?php _e('Description', 'dc-woocommerce-multi-vendor'); ?></label>
                            <div class=" col-md-6 col-sm-9">
                                <textarea type="text" class="regular-text form-control" name="issue_description" placeholder="Please provide steps that we can follow to reproduce the problem." required></textarea>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label class="control-label col-sm-3" for="uploadfiles[]"><?php _e('Upload Screenshot(s)', 'dc-woocommerce-multi-vendor'); ?></label>
                            <div class=" col-md-6 col-sm-9">
                                <input class="no_input form-control" type="file" name="uploadfiles[]" id="uploadfiles" size="35" class="uploadfiles" multiple/>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class=" col-md-12">
                                <input type="submit" name="wcmp_beta_bug_submit" id="submit" class="btn btn-default" value="<?php _e('Submit Bug Report', 'dc-woocommerce-multi-vendor'); ?>"  />
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
        </
        <?php
    }

    public function save_vendor_dashboard_data() {
        global $WCMp;
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            switch ($WCMp->endpoints->get_current_endpoint()) {
                case 'storefront':
                case 'vendor-policies':
                case 'vendor-billing':
                    $error = $WCMp->vendor_dashboard->save_store_settings($vendor->id, $_POST);
                    if (empty($error)) {
                        wc_add_notice(__('All Options Saved', 'dc-woocommerce-multi-vendor'), 'success');
                    } else {
                        wc_add_notice($error, 'error');
                    }
                    break;
                case 'vendor-shipping':
                    $WCMp->vendor_dashboard->save_vendor_shipping($vendor->id, $_POST);
                    break;
                case 'vendor-report_issue':
                    $attachments = array();
                    $file_name = '';
                    $target_file = '';
                    if (isset($_POST['wcmp_beta_bug_submit'])) {
                        if (isset($_POST['issue_description'])) {
                            $issue_description = $_POST['issue_description'];
                            $files = $_FILES['uploadfiles'];
                            if (isset($_FILES['uploadfiles'])) {
                                $array = array();
                                foreach ($_FILES['uploadfiles'] as $key => $value) {
                                    $count = count($value);

                                    for ($i = 0; $i < $count; $i++) {
                                        $array[$i][$key] = $value[$i];
                                    }
                                }
                                $attachments = array();
                                foreach ($array as $value) {
                                    if (in_array($value['type'], wp_get_mime_types())) {
                                        $file_name = mt_rand() . '.' . explode(".", basename($value['name']))[1];
                                        $target_file = sys_get_temp_dir() . '/' . $file_name;
                                        if (move_uploaded_file($value['tmp_name'], $target_file)) {
                                            $attachments[] = $target_file;
                                        }
                                    }
                                }
                                $current_user = wp_get_current_user();
                                $current_user_email = $current_user->user_email;
                                $headers = 'From:' . $current_user_email;
                                if(wp_mail('plugins@dualcube.com', 'WCMp 3.0 beta version bug report.', $issue_description, $headers, $attachments)){
                                    wc_add_notice(__('Thanks your message has been submitted successfully', 'dc-woocommerce-multi-vendor'), 'success');
                                }
                            }
                        }
                    }
                    break;
                default :
                    break;
            }
        }
        // FPM add product messages
        if (get_transient('wcmp_fpm_product_added_msg')) {
            wc_add_notice(get_transient('wcmp_fpm_product_added_msg'), 'success');
            delete_transient('wcmp_fpm_product_added_msg');
        }
    }

    /**
     * Change endpoint page title
     * @global object $wp_query
     * @global object $WCMp
     * @param string $title
     * @return string
     */
    public function wcmp_vendor_dashboard_endpoint_title($title) {
        global $wp_query, $WCMp;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && in_the_loop() && is_page() && is_wcmp_endpoint_url()) {
            $endpoint = $WCMp->endpoints->get_current_endpoint();

            if (isset($WCMp->endpoints->wcmp_query_vars[$endpoint]['label']) && $endpoint_title = $WCMp->endpoints->wcmp_query_vars[$endpoint]['label']) {
                $title = $endpoint_title;
            }

            remove_filter('the_title', array(&$this, 'wcmp_vendor_dashboard_endpoint_title'));
        }

        return $title;
    }

    /**
     * set policies tab cap
     * @param Boolean $cap
     * @return Boolean
     */
    public function wcmp_vendor_dashboard_menu_vendor_policies_capability($cap) {
        if (('Enable' === get_wcmp_vendor_settings('is_policy_on', 'general') && apply_filters('wcmp_vendor_can_overwrite_policies', true)) || ('Enable' === get_wcmp_vendor_settings('is_customer_support_details', 'general') && apply_filters('wcmp_vendor_can_overwrite_customer_support', true))) {
            $cap = true;
        }
        return $cap;
    }

    public function wcmp_vendor_dashboard_menu_vendor_withdrawal_capability($cap) {
        if (get_wcmp_vendor_settings('wcmp_disbursal_mode_vendor', 'payment')) {
            $cap = true;
        }
        return $cap;
    }

    public function wcmp_vendor_dashboard_menu_vendor_shipping_capability($cap) {
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        if ($vendor) {
            return $vendor->is_shipping_tab_enable();
        } else {
            return false;
        }
    }

    /**
     * Generate Vendor Progress
     * @global object $WCMp
     */
    public function before_wcmp_vendor_dashboard_content($key) {
        $vendor = get_wcmp_vendor(get_current_vendor_id());
        if ($vendor) {
            $vendor_progress = wcmp_get_vendor_profile_completion($vendor->id);
            if ($vendor_progress['progress'] < 100) {
                echo '<div class="col-md-12">';
                echo '<div class="panel">';
                echo '<div class="progress" style="margin:15px;">';
                echo '<div class="progress-bar" role="progressbar" style="width: ' . $vendor_progress['progress'] . '%;" aria-valuenow="' . $vendor_progress['progress'] . '" aria-valuemin="0" aria-valuemax="100">' . $vendor_progress['progress'] . '%</div>';
                echo '</div>';
                if ($vendor_progress['todo'] && is_array($vendor_progress['todo'])) {
                    $todo_link = isset($vendor_progress['todo']['link']) ? esc_url($vendor_progress['todo']['link']) : '';
                    $todo_label = isset($vendor_progress['todo']['label']) ? $vendor_progress['todo']['label'] : '';
                    echo '<div style="margin:0 15px 15px;">' . __('To boost up your profile progress add ', 'dc-woocommerce-multi-vendor') . '<a href="' . $todo_link . '">' . $todo_label . '</a></div>';
                }
                echo '</div>';
                echo '</div>';
            }
        }
    }
    /**
     * WCMp theme supported function
     */
    public function wcmp_add_theme_support(){
        if(is_vendor_dashboard()){
            global $wp_filter;
            //Flatsome mobile menu support
            remove_action('wp_footer', 'flatsome_mobile_menu', 7);
            // Remove demo store notice
            remove_action( 'wp_footer', 'woocommerce_demo_store' );
            // Remove custom css
            $wp_head_hooks = $wp_filter['wp_head']->callbacks;
            foreach ($wp_head_hooks as $priority => $wp_head_hook){
                foreach (array_keys($wp_head_hook) as $hook){
                    if(strpos($hook, 'custom_css')){
                        remove_action( 'wp_head', $hook, $priority );
                    }
                }
            }
        }
    }

}
