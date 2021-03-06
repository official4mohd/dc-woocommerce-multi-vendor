<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-billing.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.4.5
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp;
$payment_admin_settings = get_option('wcmp_payment_settings_name');
$payment_mode = array('' => __('Payment Mode', 'dc-woocommerce-multi-vendor'));
if (isset($payment_admin_settings['payment_method_paypal_masspay']) && $payment_admin_settings['payment_method_paypal_masspay'] = 'Enable') {
    $payment_mode['paypal_masspay'] = __('PayPal Masspay', 'dc-woocommerce-multi-vendor');
}
if (isset($payment_admin_settings['payment_method_paypal_payout']) && $payment_admin_settings['payment_method_paypal_payout'] = 'Enable') {
    $payment_mode['paypal_payout'] = __('PayPal Payout', 'dc-woocommerce-multi-vendor');
}
if (isset($payment_admin_settings['payment_method_direct_bank']) && $payment_admin_settings['payment_method_direct_bank'] = 'Enable') {
    $payment_mode['direct_bank'] = __('Direct Bank', 'dc-woocommerce-multi-vendor');
}
$vendor_payment_mode_select = apply_filters('wcmp_vendor_payment_mode', $payment_mode);
?>
<div class="col-md-12">
    <form method="post" name="shop_settings_form" class="wcmp_billing_form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3><?php _e('Payment Method', 'dc-woocommerce-multi-vendor'); ?></h3>
            </div>                     
            <div class="panel-body">
                <div class="form-group">
                    <label for="vendor_payment_mode" class="control-label col-sm-3 col-md-3"><?php _e('Choose Payment Method', 'dc-woocommerce-multi-vendor'); ?></label>
                    <div class="col-md-6 col-sm-9">
                        <select class="form-control" name="vendor_payment_mode" id="vendor_payment_mode">
                            <?php foreach ($vendor_payment_mode_select as $key => $value) : ?>
                                <option <?php if ($vendor_payment_mode['value'] == $key) echo 'selected' ?>  value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="payment-gateway payment-gateway-paypal_masspay payment-gateway-paypal_payout <?php echo apply_filters('wcmp_vendor_paypal_email_container_class', ''); ?>">
                    <div class="form-group">
                        <label for="vendor_paypal_email" class="control-label col-sm-3 col-md-3"><?php _e('Enter your Paypal ID', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input  class="form-control" type="text" name="vendor_paypal_email" value="<?php echo isset($vendor_paypal_email['value']) ? $vendor_paypal_email['value'] : ''; ?>"  placeholder="<?php _e('Enter your Paypal ID', 'dc-woocommerce-multi-vendor'); ?>">
                        </div>
                    </div>
                </div>
                <div class="payment-gateway payment-gateway-direct_bank">
                    <div class="form-group">
                        <label for="vendor_bank_account_type" class="control-label col-sm-3 col-md-3"><?php _e('Account type', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <select id="vendor_bank_account_type" name="vendor_bank_account_type" class="form-control">
                                <option <?php if ($vendor_bank_account_type['value'] == 'current') echo 'selected' ?> value="current"><?php _e('Current', 'dc-woocommerce-multi-vendor'); ?></option>
                                <option <?php if ($vendor_bank_account_type['value'] == 'savings') echo 'selected' ?>  value="savings"><?php _e('Savings', 'dc-woocommerce-multi-vendor'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_bank_name" class="control-label col-sm-3 col-md-3"><?php _e('Bank Name', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text" id="vendor_bank_name" name="vendor_bank_name" class="user-profile-fields" value="<?php echo isset($vendor_bank_name['value']) ? $vendor_bank_name['value'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_aba_routing_number" class="control-label col-sm-3 col-md-3"><?php _e('ABA Routing Number', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text" id="vendor_aba_routing_number" name="vendor_aba_routing_number" class="user-profile-fields" value="<?php echo isset($vendor_aba_routing_number['value']) ? $vendor_aba_routing_number['value'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_destination_currency" class="control-label col-sm-3 col-md-3"><?php _e('Destination Currency', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text" name="vendor_destination_currency" value="<?php echo isset($vendor_destination_currency['value']) ? $vendor_destination_currency['value'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_bank_address" class="control-label col-sm-3 col-md-3"><?php _e('Bank Address', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <textarea class="form-control" name="vendor_bank_address" cols="" rows=""><?php echo isset($vendor_bank_address['value']) ? $vendor_bank_address['value'] : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_destination_currency" class="control-label col-sm-3 col-md-3"><?php _e('Destination Currency', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text" name="vendor_destination_currency" value="<?php echo isset($vendor_destination_currency['value']) ? $vendor_destination_currency['value'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_iban" class="control-label col-sm-3 col-md-3"><?php _e('IBAN', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text"  name="vendor_iban" value="<?php echo isset($vendor_iban['value']) ? $vendor_iban['value'] : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_account_holder_name" class="control-label col-sm-3 col-md-3"><?php _e('Account Holder Name', 'dc-woocommerce-multi-vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="form-control" type="text" placeholder=""  name="vendor_account_holder_name" value="<?php echo isset($vendor_account_holder_name['value']) ? $vendor_account_holder_name['value'] : ''; ?>">
                        </div>
                    </div>
                </div>
                <?php do_action('wcmp_after_vendor_billing'); ?>
            </div>
        </div>



        

        <div class="wcmp-action-container">
            <button class="btn btn-default" name="store_save_billing" ><?php _e('Save Options', 'dc-woocommerce-multi-vendor'); ?></button>
            <div class="clear"></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#vendor_payment_mode').on('change', function () {
            $('.payment-gateway').hide();
            $('.payment-gateway-' + $(this).val()).show();
        }).change();
    });
</script>