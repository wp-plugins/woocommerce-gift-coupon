<?php
/**
 * Plugin Name: Woocommerce Gift Coupon
 * Description: This plugin generates coupons from products bought by WooCommerce, once generated customer sends by email
 * Depends: WooCommerce
 * Version: 1.1
 * Author: Alberto Pérez
 * Author URI: http://www.studiosweb.es/wordpress/woocommerce-gift-coupon/
 * License: GPL2
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define( 'WOOCOMMERCE_GIFT_COUPON_VERSION', '1.0' );
define( 'WOOCOMMERCE_GIFT_COUPON_DIR', plugin_dir_path(__FILE__) );
define( 'WOOCOMMERCE_GIFT_COUPON_URL', plugin_dir_url(__FILE__) );

add_action( 'admin_enqueue_scripts', 'woocommerce_gift_coupon_plugin_scripts' );

function woocommerce_gift_coupon_plugin_scripts() {
   wp_register_script( 'woocommerce_gift_coupon_script', WOOCOMMERCE_GIFT_COUPON_URL.'admin/js/jscolor/jscolor.js' );
   wp_enqueue_script( 'woocommerce_gift_coupon_script' );
   wp_register_style( 'woocommerce_gift_coupon_css', WOOCOMMERCE_GIFT_COUPON_URL.'admin/css/styles.css' );
   wp_enqueue_style( 'woocommerce_gift_coupon_css' );
}

register_activation_hook( __FILE__,'woocommerce_gift_coupon_activation' );
register_deactivation_hook( __FILE__, 'woocommerce_gift_coupon_deactivation' );
register_uninstall_hook( __FILE__, 'woocommerce_gift_coupon_uninstall' );

add_filter( 'plugin_row_meta', 'woocommerce_gift_coupon_row_meta', 10, 2 );

function woocommerce_gift_coupon_row_meta( $links, $file ) {
   if ( strpos( $file, 'woocommerce-gift-coupon.php' ) !== false ) {
	$new_links = 
	array(
	'<a href="admin.php?page=woocommerce_gift_coupon_options_page">Settings</a>',
	'<a href="admin.php?page=woocommerce_gift_coupon_information_page">Documentation</a>',
        '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F3E987XCMEGRQ"><b>Donate</b></a>',
	);	
	$links = array_merge( $links, $new_links );
   }
return $links;
}

add_action( 'admin_menu', 'woocommerce_gift_coupon_menu' );
function woocommerce_gift_coupon_menu() {
    add_menu_page( 'Woo Gift Coupon', 'Woo Gift Coupon', 'manage_options', 'woocommerce_gift_coupon_options_page', 'woocommerce_gift_coupon_import_options_page', WOOCOMMERCE_GIFT_COUPON_URL.'admin/images/woocommerce_gift_coupon-icon.png', 103 );
    add_submenu_page( 'woocommerce_gift_coupon_options_page', 'Documentation', 'Documentation', 'manage_options', 'woocommerce_gift_coupon_information_page', 'woocommerce_gift_coupon_import_information_page' );
}

function woocommerce_gift_coupon_import_options_page() {
  require_once( WOOCOMMERCE_GIFT_COUPON_DIR."admin/options_admin_page.php" );
}

function woocommerce_gift_coupon_import_information_page() {
  require_once( WOOCOMMERCE_GIFT_COUPON_DIR."admin/information_admin_page.php" );
}

function woocommerce_gift_coupon_activation() {
    global $wpdb;
    $table = $wpdb->prefix."woocommerce_gift_coupon";
    $sql = "CREATE TABLE IF NOT EXISTS $table(
    id_user BIGINT(20) UNSIGNED NOT NULL,
    id_coupon BIGINT(20) UNSIGNED NOT NULL,
    id_order BIGINT(20) NOT NULL,
    KEY woocomerce_key_user_generate_coupons (id_user),
    KEY woocomerce_key_coupon_generate_coupons (id_coupon),
    KEY woocomerce_key_order_generate_coupons (id_order),
    FOREIGN KEY (id_user) REFERENCES ".$wpdb->prefix."users(ID) ON DELETE CASCADE,
    FOREIGN KEY (id_coupon) REFERENCES ".$wpdb->prefix."posts(ID) ON DELETE CASCADE,
    FOREIGN KEY (id_order) REFERENCES ".$wpdb->prefix."woocommerce_order_items(order_id) ON DELETE CASCADE
    )CHARACTER SET utf8 COLLATE utf8_general_ci";

    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    $post = array(
        'post_author' => 1,
        'post_content' => '',
        'post_status' => "publish",
        'post_title' => 'Coupon example product',
        'post_parent' => '',
        'post_type' => "product",
     );

    $post_id = wp_insert_post( $post );

    wp_set_object_terms( $post_id, 'GiftCoupon', 'product_cat' );
    wp_set_object_terms( $post_id, 'simple', 'product_type' );

    update_post_meta( $post_id, '_visibility', 'visible' );
    update_post_meta( $post_id, '_stock_status', 'instock' );
    update_post_meta( $post_id, 'total_sales', '0' );
    update_post_meta( $post_id, '_downloadable', 'no' );
    update_post_meta( $post_id, '_virtual', 'no' );
    update_post_meta( $post_id, '_regular_price', "30" );
    update_post_meta( $post_id, '_sale_price', "" );
    update_post_meta( $post_id, '_purchase_note', "" );
    update_post_meta( $post_id, '_featured', "no" );
    update_post_meta( $post_id, '_weight', "" );
    update_post_meta( $post_id, '_length', "" );
    update_post_meta( $post_id, '_width', "" );
    update_post_meta( $post_id, '_height', "" );
    update_post_meta( $post_id, '_sku', "" );
    update_post_meta( $post_id, '_product_attributes', array() );
    update_post_meta( $post_id, '_sale_price_dates_from', "" );
    update_post_meta( $post_id, '_sale_price_dates_to', "" );
    update_post_meta( $post_id, '_price', "30" );
    update_post_meta( $post_id, '_sold_individually', "" );
    update_post_meta( $post_id, '_manage_stock', "no" );
    update_post_meta( $post_id, '_backorders', "no" );
    update_post_meta( $post_id, '_stock', "" );
    update_post_meta( $post_id, 'coupon_amount', "30" );

    update_option( 'woocommerce_gift_coupon_text_color_header', 'ffffff' );
    update_option( 'woocommerce_gift_coupon_text_color_footer', 'ffffff' );
    update_option( 'woocommerce_gift_coupon_text_color_title', '000000' );
    update_option( 'woocommerce_gift_coupon_bg_color_header', '000000' );
    update_option( 'woocommerce_gift_coupon_bg_color_footer', '000000' );
    update_option( 'woocommerce_gift_coupon_info_paragraph', 'Use each of these codes to apply discounts on next purchases' );
    update_option( 'woocommerce_gift_coupon_subject', 'New generated coupons' );
    update_option( 'woocommerce_gift_coupon_title', 'New generated coupons' );
}

function woocommerce_gift_coupon_deactivation() {
    flush_rewrite_rules();
}

add_action( 'init', 'woocommerce_gift_coupon' );

function woocommerce_gift_coupon() {
    if ( !class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'woocommerce_gift_coupon_woocommerce_not_active' );
   }
}

function woocommerce_gift_coupon_woocommerce_not_active() {
    if ( !current_user_can( 'activate_plugins' ) )
	return;
    ?>
    <div class="error">
        <?php
        $url_woocommerce = add_query_arg(
            array( 'tab'    => 'plugin-information',
		'plugin'    => 'woocommerce',
		'TB_iframe' => 'true', 
            ), admin_url( 'plugin-install.php' ) );
        $title_woocommerce = __( 'WooCommerce', 'woocommerce-gift-coupon' );
        printf( __( '<p>To begin using Woocommerce git coupon, please install and activate the latest version of <a href="%s" class="thickbox" title="%s">%s</a>.</p>', 'woocommerce-gift-coupon' ), esc_url( $url_woocommerce ), $title_woocommerce, $title_woocommerce ); ?>
    </div>
    <?php
}

add_action( 'admin_notices', 'woocommerce_gift_coupon_admin_notices' );
add_action( 'admin_footer', 'woocommerce_gift_coupon_bulk' );
add_action( 'load-edit.php', 'woocommerce_gift_coupon_bulk_action' );

function woocommerce_gift_coupon_bulk() {
    global $post_type;
    if ( $post_type == 'shop_order' ) {
            wp_create_category("coupons2", 0);
            ?>
            <script type="text/javascript">
                jQuery(function() {
                    jQuery('<option>').val('generate_coupon').text('<?php _e( 'Generate coupons', 'woocommerce-gift-coupon' )?>').appendTo("select[name='action']");
                    jQuery('<option>').val('generate_coupon').text('<?php _e( 'Generate coupons', 'woocommerce-gift-coupon' )?>').appendTo("select[name='action2']");
                });
            </script>
        <?php
    }
}

function woocommerce_gift_coupon_bulk_action() {
    require_once( WOOCOMMERCE_GIFT_COUPON_DIR."includes/mail-template.php" );
    global $typenow, $woocommerce, $wpdb;
    $post_type = $typenow;
    $wp_list_table = _get_list_table( 'WP_Posts_List_Table' ); 
    $action = $wp_list_table->current_action();	
    $allowed_actions = array( "generate_coupon" );
    if ( ! in_array( $action, $allowed_actions ) ) {
	return;
    }
    check_admin_referer( 'bulk-posts' );
    if( isset( $_REQUEST['post'] ) ) {
	$post_ids = array_map( 'intval', $_REQUEST['post'] );
    }	
    if( empty( $post_ids ) ) {
	return;
    }
    $sendback = remove_query_arg( array( 'generated_coupon', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
    if ( ! $sendback ) {
	$sendback = admin_url( "edit.php?post_type=$post_type" );
    }	
    $pagenum = $wp_list_table->get_pagenum();
    $sendback = add_query_arg( 'paged', $pagenum, $sendback );	
    switch ( $action ) {
	case 'generate_coupon':			
            $generated_coupon = 0;
		foreach ( $post_ids as $post_id ) {
                    $order = new WC_Order( $post_id );
                    $items = $order->get_items();
                    $mailto = $order->billing_email;
                    $coupons_mail = array();
                    $sc = false;
                    foreach ( $items as $key => $item ) {
                        $product_id = $item['product_id'];
                        $product_cats = wp_get_post_terms( $product_id, 'product_cat' );
                        foreach( $product_cats as $cat ) {
                           if( $cat->name=="GiftCoupon" ) {
                               $sc = true;
                           }
                        }
                        if( $sc == true ) {
                            for ( $i = 1; $i <= $item["qty"]; $i++ ) {
                               $couponcode = md5( time() . $post_id . rand( 1, 500 ) );
                               $coupon = array(
                                    'post_title'    => $couponcode,
                                    'post_excerpt'  => 'Discount coupon',
                                    'post_status'   => 'publish',
                                    'post_author'   => 1,
                                    'post_type'     => 'shop_coupon',
                                  );

                                $new_coupon_id = wp_insert_post( $coupon );

                                $wpdb->insert( $wpdb->prefix.'woocommerce_gift_coupon', 
                                        array( 
                                            'id_user' => $order->user_id,
                                            'id_coupon' => $new_coupon_id,
                                            'id_order' => $post_id,
                                        ), 
                                        array( 
                                            '%s',
                                            '%s',
                                            '%s',
                                        ) 
                                );

                                if ( $wpdb == false ) {
                                    return false;
                                }
                                
                                $type = get_post_meta( $product_id, 'discount_type' );
                                $amount = get_post_meta( $product_id, 'coupon_amount' );
                                $individual_use = get_post_meta( $product_id, 'individual_use' );
                                $product_ids = get_post_meta( $product_id, 'product_ids' );
                                $exclude_product_ids = get_post_meta( $product_id, 'exclude_product_ids' );
                                $usage_limit = get_post_meta( $product_id, 'usage_limit' );
                                $usage_limit_per_user = get_post_meta( $product_id, 'usage_limit_per_user' );
                                $limit_usage_to_x_items = get_post_meta( $product_id, 'limit_usage_to_x_items' );
                                $expiry_date = get_post_meta( $product_id, 'expiry_date' );
                                $apply_before_tax = get_post_meta( $product_id, 'apply_before_tax' );
                                $free_shipping = get_post_meta( $product_id, 'free_shipping' );
                                $exclude_sale_items = get_post_meta( $product_id, 'exclude_sale_items' );
                                $product_categories = get_post_meta( $product_id, 'product_categories' );
                                $exclude_product_categories = get_post_meta( $product_id, 'exclude_product_categories' );
                                $minimum_amount = get_post_meta( $product_id, 'minimum_amount' );
                                $maximum_amount = get_post_meta( $product_id, 'maximum_amount' );
                                $customer_email = get_post_meta( $product_id, 'customer_email' );

                                update_post_meta( $new_coupon_id, 'discount_type', $type[0] );
                                update_post_meta( $new_coupon_id, 'coupon_amount', $amount[0] );
                                update_post_meta( $new_coupon_id, 'individual_use', $individual_use[0] );
                                update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit[0] );
                                update_post_meta( $new_coupon_id, 'usage_limit_per_user', $usage_limit_per_user[0] );
                                update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', $limit_usage_to_x_items[0] );
                                update_post_meta( $new_coupon_id, 'expiry_date', $expiry_date[0] );
                                update_post_meta( $new_coupon_id, 'apply_before_tax', $apply_before_tax[0] );
                                update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping[0] );
                                update_post_meta( $new_coupon_id, 'product_ids', $product_ids[0] );
                                update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_product_ids[0] );
                                update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items[0] );
                                update_post_meta( $new_coupon_id, 'product_categories', $product_categories[0] );
                                update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_product_categories[0] );
                                update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount[0] );
                                update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount[0] );
                                update_post_meta( $new_coupon_id, 'customer_email', $customer_email[0] );
                                
                                $coupons_mail[ $generated_coupon ]['coupon_code'] = $couponcode;
                                $coupons_mail[ $generated_coupon ]['order_ID'] = $post_id;
                                $coupons_mail[ $generated_coupon ]['price'] = $amount[0];
                                if( ! empty( $expiry_date[0] ) ) {
                                    $coupons_mail[ $generated_coupon ]['expiration_date'] = $expiry_date[0];   
                                }
                                $generated_coupon++;
                            }
                        }
                        $sc = false;
                    }
                    if( ! empty( $coupons_mail ) ) {
                        $body = woocommerce_gift_coupon_generate_body_mail( $coupons_mail );
                        add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
                        $woocommerce_gift_coupon_subject = get_option( 'woocommerce_gift_coupon_subject' );
                        wp_mail( $mailto, $woocommerce_gift_coupon_subject,  $body );
                    } 
		}
		$sendback = add_query_arg( array( 'generated_coupon' => $generated_coupon, 'ids' => join( ',', $post_ids ) ), $sendback );
	break;
	default: 
            return;
    }	
    $sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view' ), $sendback );	
    wp_redirect( $sendback );
    exit();
}

function woocommerce_gift_coupon_admin_notices() {
    global $pagenow;	
    if ( $pagenow == 'edit.php' && ! isset( $_GET['trashed'] ) ) {
	$generated_coupon = 0;
	if ( isset( $_REQUEST['generated_coupon'] ) && (int) $_REQUEST['generated_coupon'] ) {
            $generated_coupon = (int) $_REQUEST['generated_coupon'];
	} elseif ( isset( $_GET['generated_coupon'] ) && (int) $_GET['generated_coupon'] ) {
            $generated_coupon = (int) $_GET['generated_coupon'];
	}
	if ( $generated_coupon ) {
            $message = sprintf( _n( 'Coupons generated.', '%s coupons generated.', $generated_coupon ), number_format_i18n( $generated_coupon ) );
		echo "<div class=\"updated\"><p>{$message}</p></div>";
        }
    }
}

add_filter( 'manage_edit-shop_order_columns', 'woocommerce_gift_coupon_columns' ); 

function woocommerce_gift_coupon_columns( $columns ) {
    $columns['coupon_purchased']    = __( 'Bought coupons', 'woocommerce-gift-coupon' );
    $columns['coupon_status']    = __( 'Coupon Status', 'woocommerce-gift-coupon' );
    $columns['coupons']    = __( 'Coupons', 'woocommerce-gift-coupon' );
    return $columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'woocommerce_gift_coupon_render_columns' );
function woocommerce_gift_coupon_render_columns( $column ) {
    global $post, $woocommerce, $wpdb;
    $order = new WC_Order( $post->ID );
    switch ( $column ) {
        case 'coupon_purchased' :
            $items = $order->get_items();
            $catsproduct = array();
            $numc = 0;
            foreach( $items as $item ) {
                $product_cats = wp_get_post_terms( $item['product_id'], 'product_cat' );
                foreach( $product_cats as $cat ) {
                    if( $cat->name == "GiftCoupon" ) {
                        $numc++;
                        if( $item['qty'] > 1 ) {
                            $addsum = $item['qty'] - 1;
                            $numc = $numc + $addsum;
                        }
                        $catsproduct[0] = $numc;
                    }
                }
            }
            if( $catsproduct[0] > 0 ) {
               printf( '<span class="%s">%s ('.$catsproduct[0].' items)</span>', sanitize_title( "Yes" ), __( "Yes", 'woocommerce-gift-coupon' ) );
            }else{
               printf( '<span class="%s">%s ('.$catsproduct[0].' items)</span>', sanitize_title( "No" ), __( "No", 'woocommerce-gift-coupon' ) );
            }
        break;    
        case 'coupon_status' :
            $coupons_generated = $wpdb->get_var( "SELECT COUNT(*) FROM wp_woocommerce_gift_coupon WHERE id_order='".$post->ID."'" );
            if ( $coupons_generated > 0 ) {
                printf( '<span class="%s">%s</span>', sanitize_title( "Sended" ), __( "Sended", 'woocommerce-gift-coupon' ) );
            }else{
                printf( '<span class="%s">%s</span>', sanitize_title( "Not Generated" ), __( "Not Generated", 'woocommerce-gift-coupon' ) );
            }
	break;
        case 'coupons' :
            $coupons_generated = $wpdb->get_results( "SELECT * FROM wp_woocommerce_gift_coupon WHERE id_order='".$post->ID."'" );
            if( ! empty( $coupons_generated ) ) {
                foreach ( $coupons_generated as $coupon ) {
                    printf( '<span class="%s">%s</span>', sanitize_title( '- <a href="'.get_edit_post_link( $coupon->id_coupon ).'">'.$coupon->id_coupon.'</a><br />' ), __( '- <a href="'.get_edit_post_link( $coupon->id_coupon ).'">'.$coupon->id_coupon.'</a><br />', 'woocommerce-gift-coupon' ) );
                }
            }else{
                printf( '<span class="%s">%s</span>', sanitize_title( "No Data" ), __( "No Data" , 'woocommerce-gift-coupon' ) );
            }
	break;
    }
}

add_filter( 'tag_row_actions', 'woocommerce_gift_coupon_remove_trash_link', 10, 2 );

function woocommerce_gift_coupon_remove_trash_link( $actions, $post ) {
    if( $post->name == "GiftCoupon" ) {
      unset( $actions['edit'] );
      unset( $actions['trash'] );
      unset( $actions['delete'] );
      unset( $actions['inline hide-if-no-js'] );
    ?>
      <script type="text/javascript">
          jQuery(document).ready(function() {
            var termid = "<?php echo $post->term_id; ?>";
            var classcheck = document.getElementById("tag-"+termid);
            jQuery(classcheck).find('input:checkbox').each(function() {
                jQuery(this).attr("disabled", true);
            });
          });
        
      </script>
    <?php }
   return $actions;
}

add_action( 'add_meta_boxes', 'woocommerce_gift_coupon_product_add' );  
add_action( 'save_post', 'woocommerce_gift_coupon_product_save' );

function woocommerce_gift_coupon_product_add() {
    add_meta_box( 'product_details', 'Gift Coupon', 'woocommerce_gift_coupon_call', 'product', 'normal', 'high' );
}

function woocommerce_gift_coupon_call( $post ) {
    echo '<div id="woocommerce-coupon-data" class="postbox">';
        WC_Meta_Box_Coupon_Data::output( $post );
    echo '</div>';
}

function woocommerce_gift_coupon_product_save( $post_id ) {
    global $post;
    if( ! empty( $_POST ) && ! empty( $post ) ) {
        if( $post->post_type=="product" ) {
            $type                   = wc_clean( $_POST['discount_type'] );
            $amount                 = wc_format_decimal( $_POST['coupon_amount'] );
            $usage_limit            = empty( $_POST['usage_limit'] ) ? '' : absint( $_POST['usage_limit'] );
            $usage_limit_per_user   = empty( $_POST['usage_limit_per_user'] ) ? '' : absint( $_POST['usage_limit_per_user'] );
            $limit_usage_to_x_items = empty( $_POST['limit_usage_to_x_items'] ) ? '' : absint( $_POST['limit_usage_to_x_items'] );
            $individual_use         = isset( $_POST['individual_use'] ) ? 'yes' : 'no';
            $expiry_date            = wc_clean( $_POST['expiry_date'] );
            $apply_before_tax       = isset( $_POST['apply_before_tax'] ) ? 'yes' : 'no';
            $free_shipping          = isset( $_POST['free_shipping'] ) ? 'yes' : 'no';
            $exclude_sale_items     = isset( $_POST['exclude_sale_items'] ) ? 'yes' : 'no';
            $minimum_amount         = wc_format_decimal( $_POST['minimum_amount'] );
            $maximum_amount         = wc_format_decimal( $_POST['maximum_amount'] );
            $customer_email         = array_filter( array_map( 'trim', explode( ',', wc_clean($_POST['customer_email'] ) ) ) );
            $product_ids            = implode( ',', array_filter( array_map( 'intval', explode( ',', $_POST['product_ids'] ) ) ) );
            $exclude_product_ids    = implode( ',', array_filter( array_map( 'intval', explode( ',', $_POST['exclude_product_ids'] ) ) ) );
            $product_categories         = isset( $_POST['product_categories'] ) ? array_map( 'intval', $_POST['product_categories'] ) : array();
            $exclude_product_categories = isset( $_POST['exclude_product_categories'] ) ? array_map( 'intval', $_POST['exclude_product_categories'] ) : array();

            update_post_meta( $post_id, 'discount_type', $type );
            update_post_meta( $post_id, 'coupon_amount', $amount );
            update_post_meta( $post_id, 'individual_use', $individual_use );
            update_post_meta( $post_id, 'product_ids', $product_ids );
            update_post_meta( $post_id, 'exclude_product_ids', $exclude_product_ids );
            update_post_meta( $post_id, 'usage_limit', $usage_limit );
            update_post_meta( $post_id, 'usage_limit_per_user', $usage_limit_per_user );
            update_post_meta( $post_id, 'limit_usage_to_x_items', $limit_usage_to_x_items );
            update_post_meta( $post_id, 'expiry_date', $expiry_date );
            update_post_meta( $post_id, 'apply_before_tax', $apply_before_tax );
            update_post_meta( $post_id, 'free_shipping', $free_shipping );
            update_post_meta( $post_id, 'exclude_sale_items', $exclude_sale_items );
            update_post_meta( $post_id, 'product_categories', $product_categories );
            update_post_meta( $post_id, 'exclude_product_categories', $exclude_product_categories );
            update_post_meta( $post_id, 'minimum_amount', $minimum_amount );
            update_post_meta( $post_id, 'maximum_amount', $maximum_amount );
            update_post_meta( $post_id, 'customer_email', $customer_email );
        }
    }
}
?>
