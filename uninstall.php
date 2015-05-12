<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'woocommerce_gift_coupon_text_color_header' );
delete_option( 'woocommerce_gift_coupon_text_color_footer' );
delete_option( 'woocommerce_gift_coupon_text_color_title' );
delete_option( 'woocommerce_gift_coupon_bg_color_header' );
delete_option( 'woocommerce_gift_coupon_bg_color_footer' );
delete_option( 'woocommerce_gift_coupon_info_paragraph' );
delete_option( 'woocommerce_gift_coupon_subject' );
delete_option( 'woocommerce_gift_coupon_title' );

$table = $wpdb->prefix . "woocommerce_gift_coupon";
$sql = 'DROP TABLE '.$table;
$wpdb->query( $sql );
