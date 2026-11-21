<?php
/**
 * Site footer: contact details, site map, payment and delivery marks.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

$ms_has_woo  = function_exists( 'WC' );
$ms_note     = trim( (string) ms_opt( 'footer_note' ) );
$ms_payments = ms_list( 'payments' );
$ms_carriers = ms_list( 'carriers' );
$ms_social   = ms_social_links();

// Fallback "shop" links: the first product categories, then the whole shop.
$ms_shop_links = array();
foreach ( ms_categories( 6 ) as $ms_term ) {
	$ms_term_link = get_term_link( $ms_term );
	if ( ! is_wp_error( $ms_term_link ) ) {
		$ms_shop_links[ $ms_term->name ] = $ms_term_link;
	}
}
$ms_shop_links[ __( 'New arrivals', 'maison-square' ) ] = ms_new_arrivals_url();
$ms_shop_links[ __( 'Shop all', 'maison-square' ) ]     = ms_shop_url();

// Fallback "help" links.
$ms_help_links = array();
if ( $ms_has_woo ) {
	$ms_help_links[ __( 'My account', 'maison-square' ) ] = wc_get_page_permalink( 'myaccount' );
	$ms_help_links[ __( 'Cart', 'maison-square' ) ]       = wc_get_cart_url();
}
$ms_info_page = get_page_by_path( 'info' );
if ( $ms_info_page && 'publish' === $ms_info_page->post_status ) {
	$ms_help_links[ get_the_title( $ms_info_page ) ] = get_permalink( $ms_info_page );
}
$ms_privacy = get_privacy_policy_url();
if ( $ms_privacy ) {
	$ms_help_links[ __( 'Privacy policy', 'maison-square' ) ] = $ms_privacy;
}
?>

<footer class="ms-footer">
	<div class="ms-container ms-footer__top">
		<div class="ms-footer__brand">
			<?php echo ms_logo( 'reversed', 40 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="ms-yline" aria-hidden="true"></span>
			<div class="ms-footer__details">
				<?php if ( '' !== $ms_note ) : ?>
					<p class="ms-footer__note"><?php echo esc_html( $ms_note ); ?></p>
				<?php endif; ?>
				<?php ms_contact_list( 'ms-contact ms-contact--footer' ); ?>
				<?php if ( $ms_social ) : ?>
					<ul class="ms-social">
						<?php foreach ( $ms_social as $ms_label => $ms_url ) : ?>
							<li><a href="<?php echo esc_url( $ms_url ); ?>" rel="noopener"><?php echo esc_html( $ms_label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<div class="ms-footer__cols">
			<nav class="ms-footer__col" aria-label="<?php esc_attr_e( 'Shop links', 'maison-square' ); ?>">
				<h2 class="ms-footer__title"><?php esc_html_e( 'shop.', 'maison-square' ); ?></h2>
				<?php ms_footer_menu( 'footer_shop', $ms_shop_links ); ?>
			</nav>

			<nav class="ms-footer__col" aria-label="<?php esc_attr_e( 'Help links', 'maison-square' ); ?>">
				<h2 class="ms-footer__title"><?php esc_html_e( 'help.', 'maison-square' ); ?></h2>
				<?php ms_footer_menu( 'footer_help', $ms_help_links ); ?>
			</nav>

			<div class="ms-footer__col">
				<?php if ( $ms_payments ) : ?>
					<h2 class="ms-footer__title"><?php esc_html_e( 'we accept.', 'maison-square' ); ?></h2>
					<?php ms_marks( $ms_payments ); ?>
				<?php endif; ?>

				<?php if ( $ms_carriers ) : ?>
					<h2 class="ms-footer__title"><?php esc_html_e( 'we ship with.', 'maison-square' ); ?></h2>
					<?php ms_marks( $ms_carriers ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="ms-footer__bottom">
		<div class="ms-container ms-footer__bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Maison Square</p>
			<a href="#top"><?php esc_html_e( 'Back to top', 'maison-square' ); ?></a>
		</div>
	</div>
</footer>

<?php ms_whatsapp_button(); ?>

<?php wp_footer(); ?>
</body>
</html>
