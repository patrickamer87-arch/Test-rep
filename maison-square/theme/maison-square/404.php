<?php
/**
 * Shown when an address does not exist.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="ms-main ms-container ms-page ms-notfound">
	<h1 class="ms-pagetitle"><?php esc_html_e( 'this page is not here.', 'maison-square' ); ?></h1>
	<p class="ms-lead"><?php esc_html_e( 'The link is old or the address has a typo. Search for what you need, or go back to the shop.', 'maison-square' ); ?></p>

	<?php ms_search_form( 'ms-search-404' ); ?>

	<p class="ms-actions">
		<a class="ms-button" href="<?php echo esc_url( ms_shop_url() ); ?>"><?php esc_html_e( 'Go to the shop', 'maison-square' ); ?></a>
		<a class="ms-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the home page', 'maison-square' ); ?></a>
	</p>
</main>

<?php
get_footer();
