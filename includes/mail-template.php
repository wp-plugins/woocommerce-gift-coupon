<?php

function woocommerce_gift_coupon_do_offset( $level ) {
    $offset = "";
    for ( $i=1; $i < $level; $i++ ) {
        $offset = $offset . "<td></td>";
    }
    
    return $offset;
}

function woocommerce_gift_coupon_show_array( $data, $level, $sub ) {
    if ( is_array( $data ) == 1 ) {
        $bodyemail = "";
        foreach( $data as $key_val => $value ) {
            $offset = "";
            if ( is_array( $value ) == 1 ) {
                $bodyemail .= "<tr>";
                $offset = woocommerce_gift_coupon_do_offset( $level );
                $bodyemail .= $offset . "<td>" . $key_val . "</td>";
                $bodyemail .= woocommerce_gift_coupon_show_array( $value, $level + 1, 1 );
            }else{
                if ( $sub != 1 ) {
                    $bodyemail .= "<tr>";
                    $offset = woocommerce_gift_coupon_do_offset( $level );
                }
                $sub = 0;
                $key_val = str_replace( "_", " ", $key_val );
                $bodyemail .= $offset . "<td><b>" . ucfirst( $key_val ) . ":</b></td><td>" . $value . "</td>";
                $bodyemail .= "</tr>\n";
            }
        }
        
        return $bodyemail;
        
    }else{
        
        return;
        
    }
}

function woocommerce_gift_coupon_generate_body_mail( $data ) {
    
    $woocommerce_gift_coupon_subject = get_option( 'woocommerce_gift_coupon_subject' );
    $woocommerce_gift_coupon_title = get_option( 'woocommerce_gift_coupon_title' );
    $woocommerce_gift_coupon_info_paragraph = get_option( 'woocommerce_gift_coupon_info_paragraph' );
    $woocommerce_gift_coupon_text_color_header = get_option( 'woocommerce_gift_coupon_text_color_header' );
    $woocommerce_gift_coupon_text_color_footer = get_option( 'woocommerce_gift_coupon_text_color_footer' );
    $woocommerce_gift_coupon_text_color_title = get_option( 'woocommerce_gift_coupon_text_color_title' );
    $woocommerce_gift_coupon_bg_color_header = get_option( 'woocommerce_gift_coupon_bg_color_header' );
    $woocommerce_gift_coupon_bg_color_footer = get_option( 'woocommerce_gift_coupon_bg_color_footer' );
    
    $email ='
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>' . $woocommerce_gift_coupon_subject . '</title>
            <style type="text/css">
                * {line-height: 100%;}
                img {display: block;}
                .ecxMsoNormal img {display: block;}
                .ExternalClass {width:100% !important;}
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font,
                .ExternalClass td, .ExternalClass div {line-height: 100%;}
                .ExternalClass p {margin-bottom: 0 !important;}
                img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
                a img {border:none;}
                .applelinks a {color:#222222; text-decoration: none;}
                .ExternalClass img[class^=Emoji] {width: 10px !important; height: 10px !important; display: inline !important;}
            </style>
            </head>
            <body>
                <table width="500" cellpadding="10" cellspacing="0" style="border-collapse: collapse; background: ' . $woocommerce_gift_coupon_bg_color_header . '; color: ' . $woocommerce_gift_coupon_text_color_header . '; margin:0 auto;">
                    <tr>
                        <td align="center"><span>' . $woocommerce_gift_coupon_info_paragraph . '</span></td>
                    </tr>
                </table>
                <table width="500" cellpadding="10" cellspacing="0" style="border-collapse: collapse; color: ' . $woocommerce_gift_coupon_text_color_title . '; background: #fff; margin:0 auto;">
                    <tr>
                        <td align="center"><h1 style="color: ' . $woocommerce_gift_coupon_text_color_title . '; margin:0 auto; text-align:center; border-bottom:1px solid #ccc; padding: 0 0 20px 0;">' . $woocommerce_gift_coupon_title . '</h1></td>
                    </tr>
                </table>
                <table width="500" cellpadding="10" cellspacing="0" style="border-collapse: collapse; background: #fafafa; color: #3c3c3c; border:1px solid #ccc; margin:0 auto;">
                    ' . woocommerce_gift_coupon_show_array( $data, 1, 0 ) . '
                </table>
                <table width="500" cellpadding="10" cellspacing="0" style="border-collapse: collapse; background: ' . $woocommerce_gift_coupon_bg_color_footer . '; color: ' . $woocommerce_gift_coupon_text_color_footer . '; margin:0 auto;">
                    <tr>
                        <td align="center"><span style="color: ' . $woocommerce_gift_coupon_text_color_footer . '">' . get_bloginfo( 'name' ) . '</span></td>
                    </tr>
                </table>
            </body>
        </html>';

    return $email;

}
