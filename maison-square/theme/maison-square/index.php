<?php
/**
 * Fallback template. The store has no blog, so this is only used for
 * the rare address that no other template covers.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_search() ) {
	/* translators: %s: what the visitor searched for */
	$ms_title = sprintf( __( 'results for "%s"', 'maison-square' ), get_search_query() );
} elseif ( is_archive() ) {
	$ms_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$ms_title = get_bloginfo( 'name' );
}
?>

<main id="main" class="ms-main ms-container ms-page">
	<h1 class="ms-pagetitle"><?php echo esc_html( ms_headline( $ms_title ) ); ?></h1>

	<?php if ( have_posts() ) : ?>
		<ul class="ms-results">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php
			endwhile;
			?>
		</ul>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p class="ms-lead"><?php esc_html_e( 'Nothing matches yet. Try another word, or browse the shop.', 'maison-square' ); ?></p>
		<?php ms_search_form( 'ms-search-empty' ); ?>
		<p class="ms-actions">
			<a class="ms-button" href="<?php echo esc_url( ms_shop_url() ); ?>"><?php esc_html_e( 'Go to the shop', 'maison-square' ); ?></a>
		</p>
	<?php endif; ?>
</main>

<?php
get_footer();
