<?php
/**
 * Theme setup, assets and head tags.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register what the theme supports.
 */
function ms_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	register_nav_menus(
		array(
			'primary'     => __( 'Main menu (shop categories)', 'maison-square' ),
			'footer_shop' => __( 'Footer: shop links', 'maison-square' ),
			'footer_help' => __( 'Footer: help links', 'maison-square' ),
		)
	);

	add_image_size( 'ms-square', 900, 900, true );
}
add_action( 'after_setup_theme', 'ms_setup' );

/**
 * Version string for a theme file, so browsers refetch it after an update.
 *
 * @param string $relative Path inside the theme.
 * @return string
 */
function ms_asset_version( $relative ) {
	$file = MS_THEME_DIR . $relative;
	return file_exists( $file ) ? (string) filemtime( $file ) : MS_THEME_VERSION;
}

/**
 * Styles and scripts. Runs after WooCommerce so the theme styles win.
 */
function ms_assets() {
	wp_enqueue_style(
		'ms-main',
		MS_THEME_URI . '/assets/css/main.css',
		array(),
		ms_asset_version( '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'ms-main',
		MS_THEME_URI . '/assets/js/main.js',
		array(),
		ms_asset_version( '/assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ms_assets', 20 );

/**
 * Inter is served from the theme itself (no outside font host).
 * Load the two weights every page needs straight away.
 */
function ms_preload_fonts() {
	foreach ( array( 'inter-400.woff', 'inter-700.woff' ) as $file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff" crossorigin>' . "\n",
			esc_url( MS_THEME_URI . '/assets/fonts/' . $file )
		);
	}
}
add_action( 'wp_head', 'ms_preload_fonts', 1 );

/**
 * Brand colours in the page and product editors.
 */
function ms_editor_palette() {
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Ink', 'maison-square' ),
				'slug'  => 'ms-ink',
				'color' => '#161616',
			),
			array(
				'name'  => __( 'Maison yellow', 'maison-square' ),
				'slug'  => 'ms-yellow',
				'color' => '#F7C20A',
			),
			array(
				'name'  => __( 'Cream', 'maison-square' ),
				'slug'  => 'ms-cream',
				'color' => '#FDF8EF',
			),
			array(
				'name'  => __( 'Sand', 'maison-square' ),
				'slug'  => 'ms-sand',
				'color' => '#EFE7D6',
			),
			array(
				'name'  => __( 'Graphite', 'maison-square' ),
				'slug'  => 'ms-graphite',
				'color' => '#5A5852',
			),
			array(
				'name'  => __( 'White', 'maison-square' ),
				'slug'  => 'ms-white',
				'color' => '#FFFFFF',
			),
		)
	);
}
add_action( 'after_setup_theme', 'ms_editor_palette' );

/**
 * Favicons from the brand kit, unless a site icon was set in the admin.
 */
function ms_head_icons() {
	echo '<meta name="theme-color" content="#FDF8EF">' . "\n";

	if ( has_site_icon() ) {
		return;
	}

	$base = esc_url( MS_THEME_URI . '/assets/favicon/' );
	echo '<link rel="icon" href="' . $base . 'favicon.ico" sizes="48x48">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<link rel="icon" href="' . $base . 'favicon.svg" type="image/svg+xml">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<link rel="icon" type="image/png" sizes="32x32" href="' . $base . 'favicon-32x32.png">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<link rel="icon" type="image/png" sizes="16x16" href="' . $base . 'favicon-16x16.png">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<link rel="apple-touch-icon" href="' . $base . 'apple-touch-icon.png">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<link rel="mask-icon" href="' . $base . 'safari-pinned-tab.svg" color="#161616">' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
}
add_action( 'wp_head', 'ms_head_icons', 5 );

/**
 * Mark the body so the styles can be scoped.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function ms_body_class( $classes ) {
	$classes[] = 'ms-site';
	return $classes;
}
add_filter( 'body_class', 'ms_body_class' );

// The store does not use emoji images. Skipping them saves a script on every page.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Maison Square brand on the WordPress login page and inside the admin:
 * logo on the login page, the symbol in the admin bar, and the store name in the admin footer.
 */
function ms_login_brand() {
	$logo = get_template_directory_uri() . '/assets/img/logo-horizontal-color.svg';
	?>
	<style>
		body.login { background: #FDF8EF; color: #161616; }
		body.login div#login h1 a { width: 260px !important; height: 38px !important; margin: 0 auto 28px !important; background: url(<?php echo esc_url( $logo ); ?>) center / contain no-repeat !important; }
		body.login #loginform { border: 0; border-left: 4px solid #F7C20A; border-radius: 0; box-shadow: none; }
		body.login input[type="text"], body.login input[type="password"] { border-radius: 0; }
		body.login input[type="text"]:focus, body.login input[type="password"]:focus { border-color: #161616; box-shadow: 0 0 0 1px #161616; }
		body.login.wp-core-ui .button-primary { border-color: #161616 !important; border-radius: 0 !important; background: #161616 !important; color: #fff !important; box-shadow: none !important; text-shadow: none !important; }
		body.login.wp-core-ui .button-primary:hover, body.login.wp-core-ui .button-primary:focus { border-color: #F7C20A !important; background: #F7C20A !important; color: #161616 !important; }
		body.login .button.wp-hide-pw .dashicons { color: #161616; }
		body.login #nav a, body.login #backtoblog a, body.login .privacy-policy-page-link a { color: #5A5852; }
		body.login #nav a:hover, body.login #backtoblog a:hover { color: #161616; }
		body.login .message, body.login .notice, body.login .success { border-left-color: #F7C20A; }
		body.login .language-switcher { display: none; }
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'ms_login_brand' );

function ms_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'ms_login_logo_url' );

function ms_login_logo_text() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'ms_login_logo_text' );

function ms_admin_bar_brand( $bar ) {
	$bar->remove_node( 'wp-logo' );
	$bar->add_node(
		array(
			'id'    => 'ms-brand',
			'title' => '<img src="' . esc_url( get_template_directory_uri() . '/assets/img/symbol-reversed.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="20" height="20" style="display:inline-block;width:20px;height:20px;margin:6px 0 0;vertical-align:top;">',
			'href'  => admin_url(),
			'meta'  => array( 'title' => get_bloginfo( 'name' ) ),
		)
	);
}
add_action( 'admin_bar_menu', 'ms_admin_bar_brand', 11 );

function ms_admin_footer_text() {
	return esc_html( get_bloginfo( 'name' ) ) . ' &middot; <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a>';
}
add_filter( 'admin_footer_text', 'ms_admin_footer_text' );

/*
 * Dashboard: a Maison Square start screen instead of the WordPress welcome boxes.
 */
function ms_dashboard_setup() {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	$boxes = array(
		'dashboard_primary'                  => 'side',
		'dashboard_quick_press'              => 'side',
		'dashboard_activity'                 => 'normal',
		'dashboard_right_now'                => 'normal',
		'dashboard_site_health'              => 'normal',
		'wc_admin_dashboard_setup'           => 'normal',
		'woocommerce_dashboard_recent_reviews' => 'normal',
	);
	foreach ( $boxes as $id => $context ) {
		remove_meta_box( $id, 'dashboard', $context );
	}
	wp_add_dashboard_widget( 'ms_start', get_bloginfo( 'name' ), 'ms_dashboard_start' );
}
add_action( 'wp_dashboard_setup', 'ms_dashboard_setup', 99 );

function ms_orders_url( $status = '' ) {
	$hpos = class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
	$url  = $hpos ? admin_url( 'admin.php?page=wc-orders' ) : admin_url( 'edit.php?post_type=shop_order' );
	if ( $status ) {
		$url = add_query_arg( $hpos ? 'status' : 'post_status', $status, $url );
	}
	return $url;
}

function ms_dashboard_start() {
	$woo   = function_exists( 'wc_orders_count' );
	$todo  = $woo ? wc_orders_count( 'processing' ) + wc_orders_count( 'on-hold' ) : 0;
	$live  = (int) wp_count_posts( 'product' )->publish;
	$empty = $woo ? count( wc_get_products( array( 'limit' => -1, 'status' => 'publish', 'stock_status' => 'outofstock', 'return' => 'ids' ) ) ) : 0;

	$stats = array(
		array( $todo, _n( 'order to handle', 'orders to handle', $todo, 'maison-square' ), ms_orders_url( 'wc-processing' ) ),
		array( $live, _n( 'product in the shop', 'products in the shop', $live, 'maison-square' ), admin_url( 'edit.php?post_type=product' ) ),
		array( $empty, __( 'out of stock', 'maison-square' ), admin_url( 'edit.php?post_type=product&stock_status=outofstock' ) ),
	);
	$links = array(
		array( __( 'Orders', 'maison-square' ), ms_orders_url() ),
		array( __( 'Add a product', 'maison-square' ), admin_url( 'post-new.php?post_type=product' ) ),
		array( __( 'Products', 'maison-square' ), admin_url( 'edit.php?post_type=product' ) ),
		array( __( 'Categories', 'maison-square' ), admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) ),
		array( __( 'Home banners', 'maison-square' ), admin_url( 'edit.php?post_type=ms_banner' ) ),
		array( __( 'Customers', 'maison-square' ), admin_url( 'users.php?role=customer' ) ),
		array( __( 'Delivery', 'maison-square' ), admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ),
		array( __( 'Payments', 'maison-square' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ),
		array( __( 'Contact details', 'maison-square' ), admin_url( 'customize.php?autofocus[section]=ms_contact' ) ),
		array( __( 'Pictures', 'maison-square' ), admin_url( 'upload.php' ) ),
	);
	?>
	<div class="ms-start">
		<ul class="ms-start__stats">
			<?php foreach ( $stats as $stat ) : ?>
				<li><a href="<?php echo esc_url( $stat[2] ); ?>"><strong><?php echo esc_html( number_format_i18n( $stat[0] ) ); ?></strong><span><?php echo esc_html( $stat[1] ); ?></span></a></li>
			<?php endforeach; ?>
		</ul>
		<ul class="ms-start__links">
			<?php foreach ( $links as $link ) : ?>
				<li><a href="<?php echo esc_url( $link[1] ); ?>"><?php echo esc_html( $link[0] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<p class="ms-start__shop"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View the shop', 'maison-square' ); ?></a></p>
	</div>
	<?php
}

function ms_dashboard_style() {
	if ( 'dashboard' !== get_current_screen()->id ) {
		return;
	}
	?>
	<style>
		#ms_start { border: 0; border-left: 4px solid #F7C20A; box-shadow: none; background: #FDF8EF; }
		#ms_start .postbox-header { border-bottom: 0; }
		#ms_start .postbox-header h2 { font-size: 16px; color: #161616; }
		.ms-start ul { margin: 0; padding: 0; list-style: none; }
		.ms-start__stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px !important; }
		.ms-start__stats a { display: block; padding: 12px; background: #fff; color: #161616; text-decoration: none; }
		.ms-start__stats a:hover { outline: 2px solid #F7C20A; }
		.ms-start__stats strong { display: block; font-size: 26px; line-height: 1.1; }
		.ms-start__stats span { color: #5A5852; font-size: 12px; }
		.ms-start__links { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; }
		.ms-start__links a { display: block; padding: 9px 12px; background: #161616; color: #fff; text-decoration: none; font-weight: 600; }
		.ms-start__links a:hover, .ms-start__links a:focus { background: #F7C20A; color: #161616; }
		.ms-start__shop { margin: 14px 0 0; }
		.ms-start__shop a { color: #161616; font-weight: 600; }
	</style>
	<?php
}
add_action( 'admin_head', 'ms_dashboard_style' );
