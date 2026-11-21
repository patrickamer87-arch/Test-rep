<?php
/**
 * Site header: top line, logo, shop menu, search, account and cart.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

$ms_has_woo = function_exists( 'WC' );
$ms_account = $ms_has_woo ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$ms_cart    = $ms_has_woo ? wc_get_cart_url() : home_url( '/' );
$ms_topbar  = trim( (string) ms_opt( 'topbar_text' ) );
$ms_email   = sanitize_email( ms_opt( 'email' ) );
$ms_count   = function_exists( 'ms_cart_count_html' ) ? ms_cart_count_html() : '';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ms-skip" href="#main"><?php esc_html_e( 'Skip to content', 'maison-square' ); ?></a>

<?php if ( '' !== $ms_topbar || $ms_email ) : ?>
<div class="ms-topbar">
	<div class="ms-container ms-topbar__inner">
		<?php if ( '' !== $ms_topbar ) : ?>
			<p class="ms-topbar__text"><?php echo ms_icon( 'delivery', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $ms_topbar ); ?></span></p>
		<?php endif; ?>
		<?php if ( $ms_email ) : ?>
			<a class="ms-topbar__link" href="<?php echo esc_url( 'mailto:' . $ms_email ); ?>"><?php echo esc_html( $ms_email ); ?></a>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>

<header class="ms-header" id="top">
	<div class="ms-container ms-header__bar">
		<button type="button" class="ms-iconbtn ms-header__menu" data-ms-drawer-open aria-controls="ms-drawer" aria-expanded="false">
			<?php echo ms_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Open the menu', 'maison-square' ); ?></span>
		</button>

		<?php echo ms_logo( 'color', 36 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<span class="ms-yline" aria-hidden="true"></span>

		<div class="ms-header__tools">
			<form role="search" method="get" class="ms-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="ms-search-field"><?php esc_html_e( 'Search products', 'maison-square' ); ?></label>
				<input type="search" id="ms-search-field" name="s" placeholder="<?php esc_attr_e( 'Search products', 'maison-square' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" class="ms-iconbtn">
					<?php echo ms_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Search', 'maison-square' ); ?></span>
				</button>
			</form>

			<button type="button" class="ms-iconbtn ms-header__search" data-ms-drawer-open data-ms-drawer-search aria-controls="ms-drawer" aria-expanded="false">
				<?php echo ms_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search products', 'maison-square' ); ?></span>
			</button>

			<a class="ms-iconbtn ms-header__account" href="<?php echo esc_url( $ms_account ); ?>">
				<?php echo ms_icon( 'account' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'My account', 'maison-square' ); ?></span>
			</a>

			<a class="ms-iconbtn ms-cartlink" href="<?php echo esc_url( $ms_cart ); ?>">
				<?php echo ms_icon( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo $ms_count; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Cart', 'maison-square' ); ?></span>
			</a>
		</div>
	</div>

	<nav class="ms-nav" aria-label="<?php esc_attr_e( 'Shop', 'maison-square' ); ?>">
		<div class="ms-container">
			<?php ms_primary_nav(); ?>
		</div>
	</nav>
</header>

<div class="ms-drawer" id="ms-drawer" hidden>
	<div class="ms-drawer__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'maison-square' ); ?>">
		<div class="ms-drawer__head">
			<?php echo ms_logo( 'color', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<button type="button" class="ms-iconbtn" data-ms-drawer-close>
				<?php echo ms_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close the menu', 'maison-square' ); ?></span>
			</button>
		</div>

		<form role="search" method="get" class="ms-search ms-search--drawer" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="ms-search-drawer"><?php esc_html_e( 'Search products', 'maison-square' ); ?></label>
			<input type="search" id="ms-search-drawer" name="s" placeholder="<?php esc_attr_e( 'Search products', 'maison-square' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			<input type="hidden" name="post_type" value="product">
			<button type="submit" class="ms-iconbtn">
				<?php echo ms_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'maison-square' ); ?></span>
			</button>
		</form>

		<nav class="ms-drawer__nav" aria-label="<?php esc_attr_e( 'Shop', 'maison-square' ); ?>">
			<?php ms_primary_nav(); ?>
		</nav>

		<ul class="ms-drawer__links">
			<li><a href="<?php echo esc_url( $ms_account ); ?>"><?php echo ms_icon( 'account', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'My account', 'maison-square' ); ?></span></a></li>
			<li><a href="<?php echo esc_url( $ms_cart ); ?>"><?php echo ms_icon( 'cart', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Cart', 'maison-square' ); ?></span></a></li>
		</ul>

		<?php ms_contact_list( 'ms-contact ms-contact--drawer' ); ?>
	</div>
	<div class="ms-drawer__scrim" data-ms-drawer-close></div>
</div>
