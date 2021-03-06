<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
?>
<div class="product-header">
	<div class="product-header-title mk-grid">
		<h1><?php single_term_title(); ?></h1>
	</div>
</div>
<div class="product-breads">
	<div class="b-crumbs mk-grid">
		<?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
	</div>
</div>
<?php
do_action('woocommerce_before_main_content');
//get the current taxonomy term
$term = get_queried_object();
// print_r($term);

$image = get_field('hero_image', $term);
if ($image) { ?>
	<div class="hero-holder" style="background-image: url('<?php echo $image; ?>');">

	</div>

<?php
}

/**
 * Hook: woocommerce_archive_description.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
do_action('woocommerce_archive_description');

?>
<div class="archive-display">
	<div id="brand-series" class="tab-content current">
		<?php if (!$term->parent > 0) { ?>
			<div class="shop-by-tabs">
				<div class="shop-by">
					<h2>Shop By Product Series</h2>
				</div>

			</div>
			<div class="archive-brands">
				<div class="archive-series">
					<?php echo do_shortcode('[pwb-all-series per_page="10" image_size="medium" hide_empty="false" order_by="name" order="ASC" title_position="after"]'); ?>
				</div>
			</div>
		<?php } ?>
	</div>
	<div id="product-wrapper" class="tab-content">
		<div id="mk-archive-products">
			<h2 class="archive-prod-title">Shop by Products</h2>
			<?php

			if (woocommerce_product_loop()) {

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked wc_print_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action('woocommerce_before_shop_loop');

				if (version_compare(WC_VERSION, '3.3.1', '<')) {
					$args = array(
						'before'  => woocommerce_product_loop_start(false),
						'after'   => woocommerce_product_loop_end(false),
					);

					/**
					 * woocommerce_product_subcategories has been deprecated in 3.3.1.
					 * Keep it in here for 3.2.6 below as there is no deprecated notice
					 * for this function right now.
					 *
					 * @todo Remove this when WC add deprecated notice.
					 */
					woocommerce_product_subcategories($args);
				}

				woocommerce_product_loop_start();

				/**
				 * WC 3.3.1 add wc_get_loop_prop function to get total product.
				 * @var boolean
				 */
				$total_prop = true;
				if (function_exists('wc_get_loop_prop')) {
					$total_prop = wc_get_loop_prop('total');
				}

				if ($total_prop) {
					while (have_posts()) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 *
						 * @hooked WC_Structured_Data::generate_product_data() - 10
						 */
						do_action('woocommerce_shop_loop');

						wc_get_template_part('content', 'product');
					}
				}

				woocommerce_product_loop_end();

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action('woocommerce_after_shop_loop');
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action('woocommerce_no_products_found');
			}

			?>

		</div>
	</div>
</div>
<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');
?>

<?php get_template_part('views/extended-footer'); ?>


<?php
get_footer('shop');
