<?php 
if( ! empty( $_POST['Submit'] ) && $_POST['Submit'] == 'Save options' ) {

    $woocommerce_gift_coupon_info_paragraph = isset( $_POST['woocommerce_gift_coupon_info_paragraph'] ) ? $_POST['woocommerce_gift_coupon_info_paragraph'] : null ;
    $woocommerce_gift_coupon_subject = isset( $_POST['woocommerce_gift_coupon_subject'] ) ? $_POST['woocommerce_gift_coupon_subject'] : null ;
    $woocommerce_gift_coupon_title = isset( $_POST['woocommerce_gift_coupon_title'] ) ? $_POST['woocommerce_gift_coupon_title'] : null ;
    $woocommerce_gift_coupon_text_color_header = isset( $_POST['woocommerce_gift_coupon_text_color_header'] ) ? $_POST['woocommerce_gift_coupon_text_color_header'] : null ;
    $woocommerce_gift_coupon_text_color_footer = isset( $_POST['woocommerce_gift_coupon_text_color_footer'] ) ? $_POST['woocommerce_gift_coupon_text_color_footer'] : null ;
    $woocommerce_gift_coupon_text_color_title = isset( $_POST['woocommerce_gift_coupon_text_color_title'] ) ? $_POST['woocommerce_gift_coupon_text_color_title'] : null ;
    $woocommerce_gift_coupon_bg_color_header = isset( $_POST['woocommerce_gift_coupon_bg_color_header'] ) ? $_POST['woocommerce_gift_coupon_bg_color_header'] : null ;
    $woocommerce_gift_coupon_bg_color_footer = isset( $_POST['woocommerce_gift_coupon_bg_color_footer'] ) ? $_POST['woocommerce_gift_coupon_bg_color_footer'] : null ;

    update_option( 'woocommerce_gift_coupon_info_paragraph', $woocommerce_gift_coupon_info_paragraph );
    update_option( 'woocommerce_gift_coupon_subject', $woocommerce_gift_coupon_subject );
    update_option( 'woocommerce_gift_coupon_title', $woocommerce_gift_coupon_title );
    update_option( 'woocommerce_gift_coupon_text_color_header', $woocommerce_gift_coupon_text_color_header );
    update_option( 'woocommerce_gift_coupon_text_color_footer', $woocommerce_gift_coupon_text_color_footer );
    update_option( 'woocommerce_gift_coupon_text_color_title', $woocommerce_gift_coupon_text_color_title );
    update_option( 'woocommerce_gift_coupon_bg_color_header', $woocommerce_gift_coupon_bg_color_header );
    update_option( 'woocommerce_gift_coupon_bg_color_footer', $woocommerce_gift_coupon_bg_color_footer );

    print '<div class="updated">';
         _e( 'Options saved.' );
    print '</div>';

}

$woocommerce_gift_coupon_info_paragraph = get_option( 'woocommerce_gift_coupon_info_paragraph' );
$woocommerce_gift_coupon_subject = get_option( 'woocommerce_gift_coupon_subject' );
$woocommerce_gift_coupon_title = get_option( 'woocommerce_gift_coupon_title' );
$woocommerce_gift_coupon_text_color_header = get_option( 'woocommerce_gift_coupon_text_color_header' );
$woocommerce_gift_coupon_text_color_footer = get_option( 'woocommerce_gift_coupon_text_color_footer' );
$woocommerce_gift_coupon_text_color_title = get_option( 'woocommerce_gift_coupon_text_color_title' );
$woocommerce_gift_coupon_bg_color_header = get_option( 'woocommerce_gift_coupon_bg_color_header' );
$woocommerce_gift_coupon_bg_color_footer = get_option( 'woocommerce_gift_coupon_bg_color_footer' );       
?>

<div class="container">
    <form name="woocommerce_gift_coupon_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
        <div class="wgc-box">
            <div class="header medium">
                <?php _e( '<h4>Basic coupon email configuration:</h4>', 'woocommerce-gift-coupon' ); ?>
            </div>
            <div class="wgc-box-body">
                <table>
                    <tr>
                        <td><?php _e( 'Paragraph information:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><textarea name="woocommerce_gift_coupon_info_paragraph"><?php echo $woocommerce_gift_coupon_info_paragraph; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Subject email:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" name="woocommerce_gift_coupon_subject" value="<?php echo $woocommerce_gift_coupon_subject; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Title email:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" name="woocommerce_gift_coupon_title" value="<?php echo $woocommerce_gift_coupon_title; ?>"/></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="wgc-box">
            <div class="header medium">
                <?php _e( '<h4>Styles template Email:</h4>', 'woocommerce-gift-coupon' ); ?>
            </div>
            <div class="wgc-box-body">
                <table>
                    <tr>
                        <td><?php _e( 'Background header color:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" class="color" name="woocommerce_gift_coupon_bg_color_header" id="woocommerce_gift_coupon_bg_color_header" value="<?php echo $woocommerce_gift_coupon_bg_color_header; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Text color header:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" class="color" name="woocommerce_gift_coupon_text_color_header" id="woocommerce_gift_coupon_text_color_header" value="<?php echo $woocommerce_gift_coupon_text_color_header; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Background footer color:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" class="color" name="woocommerce_gift_coupon_bg_color_footer" id="woocommerce_gift_coupon_bg_color_footer" value="<?php echo $woocommerce_gift_coupon_bg_color_footer; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Text color footer:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" class="color" name="woocommerce_gift_coupon_text_color_footer" id="woocommerce_gift_coupon_text_color_footer" value="<?php echo $woocommerce_gift_coupon_text_color_footer; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Text color title:', 'woocommerce-gift-coupon' ); ?></td>
                        <td><input type="text" class="color" name="woocommerce_gift_coupon_text_color_title" id="woocommerce_gift_coupon_text_color_title" value="<?php echo $woocommerce_gift_coupon_text_color_title; ?>"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="block">
            <p class="submit">
                <input type="submit" class="button button-primary" name="Submit" id="Submit" value="<?php _e( 'Save options', 'woocommerce-gift-coupon' ); ?>" />
            </p>
        </div>
    </form>
</div> <!-- container -->

