<?php
/**
 * Maison Square theme.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

define( 'MS_THEME_VERSION', '0.2.0' );
define( 'MS_THEME_DIR', get_template_directory() );
define( 'MS_THEME_URI', get_template_directory_uri() );

require MS_THEME_DIR . '/inc/template-tags.php';
require MS_THEME_DIR . '/inc/setup.php';
require MS_THEME_DIR . '/inc/customizer.php';
require MS_THEME_DIR . '/inc/banners.php';
require MS_THEME_DIR . '/inc/no-blog.php';

if ( class_exists( 'WooCommerce' ) ) {
	require MS_THEME_DIR . '/inc/woocommerce.php';
}
