<?php
/**
 * A standard page. The cart, checkout and account pages use the full width.
 *
 * @package MaisonSquare
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ms_wide = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );
?>

<main id="main" class="ms-main ms-container ms-page<?php echo $ms_wide ? ' ms-page--wide' : ''; ?>">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'ms-article' ); ?>>
			<h1 class="ms-pagetitle"><?php echo esc_html( ms_headline( get_the_title() ) ); ?></h1>
			<div class="ms-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
