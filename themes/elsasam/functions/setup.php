<?php

function elsasam_setup() {
  add_editor_style('css/editor-style.css');
  add_theme_support('post-thumbnails');
	update_option('thumbnail_size_w', 170);
  update_option('medium_size_w', 470);
  update_option('large_size_w', 970);

}
add_action('init', 'elsasam_setup');

if (! isset($content_width))
	$content_width = 600;

function elsasam_excerpt_readmore() {
    return '&nbsp; <a href="'. get_permalink() . '">' . '&hellip; ' . __('Read more', 'elsasam') . ' <i class="glyphicon glyphicon-arrow-right"></i>' . '</a></p>';
}
add_filter('excerpt_more', 'elsasam_excerpt_readmore');

/*  Browser detection body_class() output
/* ------------------------------------ */ 
function elsasam_browser_body_class( $classes ) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
 
    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $browser = suelsasamr( "$browser", 25, 8);
        if ($browser == "MSIE 7.0"  ) {
            $classes[] = 'ie7';
            $classes[] = 'ie';
        } elseif ($browser == "MSIE 6.0" ) {
            $classes[] = 'ie6';
            $classes[] = 'ie';
        } elseif ($browser == "MSIE 8.0" ) {
            $classes[] = 'ie8';
            $classes[] = 'ie';
        } elseif ($browser == "MSIE 9.0" ) {
            $classes[] = 'ie9';
            $classes[] = 'ie';
        } else {
            $classes[] = 'ie';
        }
    }
    else $classes[] = 'unknown';
 
    if( $is_iphone ) $classes[] = 'iphone';
 
    return $classes;
}
add_filter( 'body_class', 'elsasam_browser_body_class' );

/**
* show custom order column values
*/
function show_order_column($name){
  global $post;

  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
}
add_action('manage_slide_posts_custom_column','show_order_column');

/**
* make column sortable
*/
function order_column_register_sortable($columns){
  $columns['menu_order'] = 'menu_order';
  return $columns;
}
add_filter('manage_edit-slide_sortable_columns','order_column_register_sortable');

/**
* extra HTML for Maintenance Mode plugin
*/
// function new_text($text) {
// $text = '<a class="logo"></a>';
//     return $text;
// }

// add_filter('wpmm_text', 'new_text');

add_filter( 'woocommerce_cart_shipping_packages', 'dropship_woocommerce_cart_shipping_packages' );

function dropship_woocommerce_cart_shipping_packages( $packages ) {
    // Reset the packages
    $packages = array();
  
    // Bulky items
    $dropship_items = array();
    $regular_items = array();
    
    // Sort bulky from regular
    foreach ( WC()->cart->get_cart() as $item ) {
        if ( $item['data']->needs_shipping() ) {
            if ( $item['data']->get_shipping_class() == 'wacamole' ) {
                $dropship_items[] = $item;
            } else {
                $regular_items[] = $item;
            }
        }
    }
    
    // Put inside packages
    if ( $dropship_items ) {
        $packages[] = array(
            'ship_via' => array( 'flat_rate' ),
            'contents' => $dropship_items,
            'contents_cost' => array_sum( wp_list_pluck( $dropship_items, 'line_total' ) ),
            'applied_coupons' => WC()->cart->applied_coupons,
            'destination' => array(
                'country' => WC()->customer->get_shipping_country(),
                'state' => WC()->customer->get_shipping_state(),
                'postcode' => WC()->customer->get_shipping_postcode(),
                'city' => WC()->customer->get_shipping_city(),
                'address' => WC()->customer->get_shipping_address(),
                'address_2' => WC()->customer->get_shipping_address_2()
            )
        );
    }
    if ( $regular_items ) {
        $packages[] = array(
            'contents' => $regular_items,
            'contents_cost' => array_sum( wp_list_pluck( $regular_items, 'line_total' ) ),
            'applied_coupons' => WC()->cart->applied_coupons,
            'destination' => array(
                'country' => WC()->customer->get_shipping_country(),
                'state' => WC()->customer->get_shipping_state(),
                'postcode' => WC()->customer->get_shipping_postcode(),
                'city' => WC()->customer->get_shipping_city(),
                'address' => WC()->customer->get_shipping_address(),
                'address_2' => WC()->customer->get_shipping_address_2()
            )
        );
    }    
    
    return $packages;
}

/**
 * WooCommerce Related Products
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */ 
function woo_related_products_limit() {
  global $product;
    
    $args['posts_per_page'] = 6;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
  function jk_related_products_args( $args ) {

    $args['posts_per_page'] = 4; // 4 related products
    $args['columns'] = 4; // arranged in 4 columns
    return $args;
}

// function woocommerce_product_loop_start() { 
//     echo '<div class="row es-wc-product-loop">'; 
// }
 
// function woocommerce_product_loop_end() { 
//     echo '</div>'; 
// }

add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
        global $wp_query;
        $cat = $wp_query->get_queried_object();
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        $image = wp_get_attachment_url( $thumbnail_id );
        if ( $image ) {
            echo '<img class="cat-img" src="' . $image . '" alt="" />';
        }
    }
}

/**
* Show all product attributes on the product page
*/
function isa_woocommerce_all_pa(){
 
    global $product;
    $attributes = $product->get_attributes();
 
    if ( ! $attributes ) {
        return;
    }
 
    $out = '<ul class="custom-attributes">';
 
    foreach ( $attributes as $attribute ) {
 
 
        // skip variations
        if ( $attribute['is_variation'] ) {
        continue;
        }
 
 
        if ( $attribute['is_taxonomy'] ) {
 
            $terms = wp_get_post_terms( $product->id, $attribute['name'], 'all' );
 
            // get the taxonomy
            $tax = $terms[0]->taxonomy;
 
            // get the tax object
            $tax_object = get_taxonomy($tax);
 
            // get tax label
            if ( isset ($tax_object->labels->name) ) {
                $tax_label = $tax_object->labels->name;
            } elseif ( isset( $tax_object->label ) ) {
                $tax_label = $tax_object->label;
            }
 
            foreach ( $terms as $term ) {
                $archive_link = get_term_link( $term->slug, $tax );
                $out .= '<li class="' . esc_attr( $attribute['name'] ) . ' ' . esc_attr( $term->slug ) . '">';
                $out .= '<span class="attribute-label">' . $tax_label . ': </span> ';
                $out .= '<a href="'.$archive_link.'"><span class="attribute-value">' . $term->name . '</span></a></li>';
 
            }
 
        } else {
 
            $out .= '<li class="' . sanitize_title($attribute['name']) . ' ' . sanitize_title($attribute['value']) . '">';
            $out .= '<span class="attribute-label">' . $attribute['name'] . ': </span> ';
            $out .= '<span class="attribute-value">' . $attribute['value'] . '</span></li>';
        }
    }
 
    $out .= '</ul>';
 
    echo $out;
}
add_action('woocommerce_single_product_summary', 'isa_woocommerce_all_pa', 25);

add_theme_support( 'woocommerce' );
