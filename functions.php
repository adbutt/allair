<?php

require_once(trailingslashit(get_stylesheet_directory()) . 'brands-shortcode.php');



add_filter('gform_enable_field_label_visibility_settings', '__return_true');


// Add custom script

function add_my_script()
{
    wp_enqueue_script(
        'custom-js',
        get_stylesheet_directory_uri() . '/js/custom.js',
        false,
        '1.1',
        true
    );
    wp_enqueue_script('custom-js');
}
add_action('wp_enqueue_scripts', 'add_my_script', 999);



/**
 * Custom CSS
 */
function custom_child_styles()
{
    wp_enqueue_style('aas-styles', get_stylesheet_directory_uri() . '/custom.css', array(), urlencode(date('l jS \of F Y his A')), 'all');
}
add_action('wp_enqueue_scripts', 'custom_child_styles', 9001);




/**
 * Register desktop header button widget.
 *
 */
function header_call_button_widget_init()
{

    register_sidebar(array(
        'name'          => 'Header Call Button',
        'id'            => 'header_call_btn',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'header_call_button_widget_init');


/**
 * Register mobile header button widget.
 *
 */
function header_mobile_call_button_widget_init()
{

    register_sidebar(array(
        'name'          => 'Header Mobile Call Button',
        'id'            => 'header_mob_call_btn',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'header_mobile_call_button_widget_init');

/**
 * Remove script versions
 *
 */
function _remove_script_version($src)
{
    $parts = explode('?', $src);
    return $parts[0];
}
add_filter('script_loader_src', '_remove_script_version', 15, 1);
add_filter('style_loader_src', '_remove_script_version', 15, 1);

/**
 *  Brand Image sizing
 */
add_image_size('brand-thumbnail-size', 9999, 80);

/**
 *  Remove Related Products
 */

// remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

/**
 *  Add SKU below title
 */
add_action('woocommerce_single_product_summary', 'dev_designs_show_sku', 5);
function dev_designs_show_sku()
{
    global $product;
    echo '<div class="p-sku">' . $product->get_sku() . '</div>';
}

/* Remove product meta */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);


/**
 *  Add Star Ratings
 */
add_action('woocommerce_single_product_summary', 'dev_designs_show_stars', 6);
function dev_designs_show_stars()
{
    global $product;
    $id = $product->get_id();
    $heating = get_field('heating', $id);
    $cooling = get_field('cooling', $id);
    $html = '';
    if ($heating || $cooling) {
        $html .= '<div class="star-ratings">';
        if ($heating) {
            $html .= '<div class="star-ratings-css orange" title="' . $heating . '"><span>Heating</span></div>';
        }
        if ($cooling) {
            $html .= '<div class="star-ratings-css green" title="' . $cooling . '"><span>Cooling</span></div>';
        }
        $html .= '</div>';
    }
    echo $html;
}

/**
 *  Remove Unwanted Tabs
 */
add_filter('woocommerce_product_tabs', 'yikes_remove_description_tab', 20, 1);

function yikes_remove_description_tab($tabs)
{

    // Remove the description tab
    if (isset($tabs['description'])) unset($tabs['description']);
    if (isset($tabs['additional_information'])) unset($tabs['additional_information']);
    return $tabs;
}

//Hide Price Range for WooCommerce Variable Products
add_filter(
    'woocommerce_variable_sale_price_html',
    'lw_variable_product_price',
    10,
    2
);
add_filter(
    'woocommerce_variable_price_html',
    'lw_variable_product_price',
    10,
    2
);

function lw_variable_product_price($v_price, $v_product)
{

    // Product Price
    $prod_prices = array(
        $v_product->get_variation_price('min', true),
        $v_product->get_variation_price('max', true)
    );
    $prod_price = $prod_prices[0] !== $prod_prices[1] ? sprintf(
        __('From: %1$s', 'woocommerce'),
        wc_price($prod_prices[0])
    ) : wc_price($prod_prices[0]);

    // Regular Price
    $regular_prices = array(
        $v_product->get_variation_regular_price('min', true),
        $v_product->get_variation_regular_price('max', true)
    );
    sort($regular_prices);
    $regular_price = $regular_prices[0] !== $regular_prices[1] ? sprintf(
        __('From: %1$s', 'woocommerce'),
        wc_price($regular_prices[0])
    ) : wc_price($regular_prices[0]);

    if ($prod_price !== $regular_price) {
        $prod_price = '<del>' . $regular_price . $v_product->get_price_suffix() . '</del> <ins>' .
            $prod_price . $v_product->get_price_suffix() . '</ins>';
    }
    return $prod_price;
}

// List Product Downloads Shortcode
function list_product_downloads()
{

    global $product;
    $id = $product->get_id();
    $html = '';
    if (have_rows('downloads', $id)) {
        $html .= '<div class="downloads-wrapper">';
        while (have_rows('downloads', $id)) : the_row();
            $dtitle = get_sub_field('download_title');
            $dfile = get_sub_field('file');
            $tmplurl = get_stylesheet_directory_uri();
            $html .= '<a href="' . $dfile . '" class="d-file"><img src="' . $tmplurl . '/images/pdf.png"><span class="d-title">' . $dtitle . '</span></a>';
        endwhile;

        $html .= '</div>';
    }
    return $html;
}
add_shortcode('downloads', 'list_product_downloads');

// Third, remove prices from shop and category

// add_filter('woocommerce_variable_sale_price_html', 'woocommerce_remove_prices', 10, 2);
// add_filter('woocommerce_variable_price_html', 'woocommerce_remove_prices', 10, 2);
// add_filter('woocommerce_get_price_html', 'woocommerce_remove_prices', 10, 2);

// function woocommerce_remove_prices($price, $product)
// {
//     $price = '';
//     return $price;
// }
add_filter('woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text');
function custom_woocommerce_product_add_to_cart_text()
{
    global $product;

    $product_type = $product->product_type;

    switch ($product_type) {
        case 'external':
            return __('View Product', 'woocommerce');
            break;
        case 'grouped':
            return __('View Product', 'woocommerce');
            break;
        case 'simple':
            return __('View Product', 'woocommerce');
            break;
        case 'variable':
            return __('View Product', 'woocommerce');
            break;
        default:
            return __('View Product', 'woocommerce');
    }
}
add_action('mk_theme_before_footer_start', 'add_extended_footer', 10);
function add_extended_footer()
{
    if (is_product() || is_page('1995')) {
        get_template_part('views/extended-footer');
    }
}
