<?php
/*
 * The template for displaying vendor coupons
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/coupon-manager/coupons.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp;
do_action('before_wcmp_vendor_dashboard_coupon_list_table');
?>
<div class="col-md-12">
    <div class="panel panel-default panel-pading">
        <table id="coupons_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?php _e('Coupon(s)', 'dc-woocommerce-multi-vendor'); ?></th>
                    <th><?php _e('Coupon Amount', 'dc-woocommerce-multi-vendor'); ?></th>
                    <th><?php _e('Usage / Limit', 'dc-woocommerce-multi-vendor'); ?></th>
                    <th><?php _e('Expiry Date', 'dc-woocommerce-multi-vendor'); ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div class="wcmp-action-container">
            <a href="<?php echo wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_coupon_endpoint', 'vendor', 'general', 'add-coupon'));?>" class="btn btn-default"><?php echo __('Add Coupon', 'dc-woocommerce-multi-vendor');?></a>
        </div>
    </div>
</div>
<style>
    .vendor-coupon .row-actions{ visibility: hidden;}
    .vendor-coupon:hover .row-actions{ visibility: visible;}
    span.delete a{color: #a00;}
</style>
<script>
jQuery(document).ready(function($) {
    var vendor_coupons;
    vendor_coupons = $('#coupons_table').DataTable({
        ordering  : false,
        searching  : false,
        processing: true,
        serverSide: true,
        language: {
            "emptyTable": "<?php echo __('No coupons found!','dc-woocommerce-multi-vendor'); ?>",
            "info": "<?php echo __('Showing _START_ to _END_ of _TOTAL_ coupons','dc-woocommerce-multi-vendor'); ?>",
            "infoEmpty": "<?php echo __('Showing 0 to 0 of 0 coupons','dc-woocommerce-multi-vendor'); ?>",
            "lengthMenu": "<?php echo __('Show coupons _MENU_','dc-woocommerce-multi-vendor'); ?>",
            "zeroRecords": "<?php echo __('No matching coupons found','dc-woocommerce-multi-vendor'); ?>"
        },
        ajax:{
            url : woocommerce_params.ajax_url+'?action=wcmp_vendor_coupon_list', 
            type: "post", 
        },
        createdRow: function (row, data, index) {
            $(row).addClass('vendor-coupon');
        },
        columns: [
            { data: "coupons", className: "name" },
            { data: "amount" },
            { data: "uses_limit" },
            { data: "expiry_date" }
        ]
    });
});
</script>
<?php do_action('after_wcmp_vendor_dashboard_coupon_list_table'); 