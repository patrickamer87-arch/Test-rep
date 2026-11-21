<?php
/**
 * WooCommerce: layout, product cards, cart count and small wording changes.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * Declare WooCommerce support and the product gallery features.
 */
function ms_woo_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 4,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'ms_woo_setup' );

/**
 * Replace WooCommerce's page wrappers with the theme's, and drop the sidebar.
 */
function ms_woo_layout_hooks() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	add_action( 'woocommerce_before_main_content', 'ms_woo_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'ms_woo_wrapper_end', 10 );
}
add_action( 'init', 'ms_woo_layout_hooks', 20 );

/**
 * Open the shop page wrapper.
 */
function ms_woo_wrapper_start() {
	echo '<main id="main" class="ms-main ms-container ms-shop">';
}

/**
 * Close the shop page wrapper.
 */
function ms_woo_wrapper_end() {
	echo '</main>';
}

/**
 * Four products per row.
 *
 * @return int
 */
function ms_woo_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'ms_woo_columns' );

/**
 * Sixteen products per page.
 *
 * @return int
 */
function ms_woo_per_page() {
	return 16;
}
add_filter( 'loop_shop_per_page', 'ms_woo_per_page' );

/**
 * Four related products in one row.
 *
 * @param array $args Related products settings.
 * @return array
 */
function ms_woo_related_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'ms_woo_related_args' );

/**
 * The number shown on the cart icon.
 *
 * @return string
 */
function ms_cart_count_html() {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
	return sprintf( '<span class="ms-cart-count" data-count="%1$d">%1$d</span>', $count );
}

/**
 * Refresh the cart number when something is added to the cart.
 *
 * @param array $fragments Page fragments.
 * @return array
 */
function ms_cart_fragment( $fragments ) {
	$fragments['span.ms-cart-count'] = ms_cart_count_html();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ms_cart_fragment' );

/**
 * Load the script that keeps the cart number current.
 */
function ms_woo_scripts() {
	wp_enqueue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'ms_woo_scripts', 30 );

/**
 * The store calls the SKU a "code".
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function ms_woo_wording( $translation, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'SKU:' === $text ) {
		return 'Code:';
	}
	return $translation;
}
if ( ! is_admin() ) {
	add_filter( 'gettext', 'ms_woo_wording', 10, 3 );
}

/**
 * Plain "Sale" tag.
 *
 * @return string
 */
function ms_woo_sale_flash() {
	return '<span class="onsale">' . esc_html__( 'Sale', 'maison-square' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'ms_woo_sale_flash' );

/**
 * "New" tag on products added in the last 30 days.
 */
function ms_woo_new_badge() {
	global $product;

	if ( ! $product instanceof WC_Product || $product->is_on_sale() ) {
		return;
	}

	$created = $product->get_date_created();
	if ( $created && ( time() - $created->getTimestamp() ) < 30 * DAY_IN_SECONDS ) {
		echo '<span class="ms-badge-new">' . esc_html__( 'New', 'maison-square' ) . '</span>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'ms_woo_new_badge', 9 );

/**
 * Slash between breadcrumb steps.
 *
 * @param array $defaults Breadcrumb settings.
 * @return array
 */
function ms_woo_breadcrumbs( $defaults ) {
	$defaults['delimiter'] = '<span class="ms-crumb-sep" aria-hidden="true">/</span>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ms_woo_breadcrumbs' );

/**
 * Latest approved product reviews with four or five stars.
 * Only real customer reviews are ever shown.
 *
 * @param int $number How many.
 * @return WP_Comment[]
 */
function ms_get_reviews( $number = 3 ) {
	$reviews = get_comments(
		array(
			'post_type'  => 'product',
			'status'     => 'approve',
			'type'       => 'review',
			'number'     => (int) $number,
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => 'rating',
					'value'   => 4,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
	return is_array( $reviews ) ? $reviews : array();
}

/**
 * Picture for a product that has no photo yet: the icon of its category
 * on a sand square, or the brand symbol when no icon fits.
 *
 * @param WC_Product|null $product Product, if known.
 * @return string Image address.
 */
function ms_noimage_src( $product = null ) {
	if ( $product instanceof WC_Product ) {
		$id    = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		$terms = get_the_terms( $id, 'product_cat' );
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$icon = ms_category_icon( $term );
				if ( 'categories' !== $icon && is_readable( MS_THEME_DIR . '/assets/img/noimage/' . $icon . '.svg' ) ) {
					return MS_THEME_URI . '/assets/img/noimage/' . $icon . '.svg';
				}
			}
		}
	}
	return MS_THEME_URI . '/assets/img/placeholder.svg';
}

/**
 * Markup of the "no photo yet" picture.
 *
 * @param string       $src  Image address.
 * @param string|array $size Image size name, or width and height.
 * @return string
 */
function ms_noimage_html( $src, $size = 'woocommerce_thumbnail' ) {
	$dimensions = is_array( $size ) ? $size : wc_get_image_size( $size );
	$width      = ! empty( $dimensions['width'] ) ? (int) $dimensions['width'] : ( ! empty( $dimensions[0] ) ? (int) $dimensions[0] : 600 );

	return sprintf(
		'<img src="%1$s" width="%2$d" height="%2$d" class="woocommerce-placeholder wp-post-image" alt="%3$s" loading="lazy">',
		esc_url( $src ),
		$width,
		esc_attr__( 'Photo coming soon', 'maison-square' )
	);
}

/**
 * Use the theme's picture wherever WooCommerce shows its grey placeholder.
 *
 * @param string $src Default placeholder address.
 * @return string
 */
function ms_woo_placeholder_src( $src ) {
	global $product;

	if ( is_singular( 'product' ) && $product instanceof WC_Product && get_queried_object_id() === $product->get_id() ) {
		return ms_noimage_src( $product );
	}
	return ms_noimage_src();
}
add_filter( 'woocommerce_placeholder_img_src', 'ms_woo_placeholder_src' );

/**
 * Same, for places that ask for the whole image tag.
 *
 * @param string       $html Image markup.
 * @param string|array $size Image size.
 * @return string
 */
function ms_woo_placeholder_img( $html, $size = 'woocommerce_thumbnail' ) {
	return ms_noimage_html( ms_noimage_src(), $size );
}
add_filter( 'woocommerce_placeholder_img', 'ms_woo_placeholder_img', 10, 2 );

/**
 * Product cards, the cart and the admin list: a product without a photo
 * shows the icon of its category.
 *
 * @param string       $image       Image markup.
 * @param WC_Product   $product     Product.
 * @param string|array $size        Image size.
 * @param array        $attr        Image attributes.
 * @param bool         $placeholder Whether a placeholder may be used.
 * @return string
 */
function ms_woo_product_image( $image, $product, $size = 'woocommerce_thumbnail', $attr = array(), $placeholder = true ) {
	if ( ! $placeholder || ! $product instanceof WC_Product || $product->get_image_id() ) {
		return $image;
	}

	if ( $product->get_parent_id() ) {
		$parent = wc_get_product( $product->get_parent_id() );
		if ( $parent && $parent->get_image_id() ) {
			return $image;
		}
	}

	return ms_noimage_html( ms_noimage_src( $product ), $size );
}
add_filter( 'woocommerce_product_get_image', 'ms_woo_product_image', 10, 5 );

/**
 * Shop and category titles end with a full stop, like every headline.
 *
 * @param string $title Page title.
 * @return string
 */
function ms_woo_page_title( $title ) {
	return ms_headline( $title );
}
add_filter( 'woocommerce_page_title', 'ms_woo_page_title' );

/**
 * Heading above the related products.
 *
 * @return string
 */
function ms_woo_related_heading() {
	return __( 'you may also like.', 'maison-square' );
}
add_filter( 'woocommerce_product_related_products_heading', 'ms_woo_related_heading' );

/**
 * Heading above the "goes well with" products picked for an item.
 *
 * @return string
 */
function ms_woo_upsells_heading() {
	return __( 'goes well with.', 'maison-square' );
}
add_filter( 'woocommerce_product_upsells_products_heading', 'ms_woo_upsells_heading' );

/**
 * The details tab lists size and weight, so it is named for what it holds.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function ms_woo_tabs( $tabs ) {
	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = __( 'Size and weight', 'maison-square' );
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ms_woo_tabs', 98 );

/**
 * No repeated heading inside the tabs: the tab label already names the content.
 */
add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

/**
 * Google and Facebook sign-in buttons under the login and sign-up forms.
 * They show once the providers are switched on in Nextend Social Login.
 */
function ms_woo_social_login() {
	if ( shortcode_exists( 'nextend_social_login' ) ) {
		echo '<div class="ms-social-login">' . do_shortcode( '[nextend_social_login]' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}
add_action( 'woocommerce_login_form_end', 'ms_woo_social_login' );
add_action( 'woocommerce_register_form_end', 'ms_woo_social_login' );

/**
 * Delivery in Lebanon costs $5 for an order under 7 kg and $10 from 7 kg.
 * The weight is the order's total, from the weight of each item (in kg) times its quantity.
 * Applies to the "Delivery" option of the Lebanon zone (flat rate, instance 2); pick-up stays free.
 *
 * @param WC_Shipping_Rate[] $rates   Rates offered for the package.
 * @param array              $package The package (cart contents and address).
 * @return WC_Shipping_Rate[]
 */
function ms_delivery_by_weight( $rates, $package ) {
	$weight = 0.0;
	foreach ( $package['contents'] as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : null;
		if ( $product && $product->needs_shipping() && $product->get_weight() ) {
			$weight += (float) $product->get_weight() * (int) $item['quantity'];
		}
	}

	foreach ( $rates as $rate ) {
		if ( 'flat_rate' === $rate->get_method_id() && 2 === (int) $rate->get_instance_id() ) {
			$rate->set_cost( $weight >= 7 ? 10 : 5 );
			$rate->set_taxes( array() );
		}
	}

	return $rates;
}
add_filter( 'woocommerce_package_rates', 'ms_delivery_by_weight', 20, 2 );
