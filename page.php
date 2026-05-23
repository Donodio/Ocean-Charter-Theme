<?php
/**
 * The template for displaying all pages
 *
 * @package OceanCharter
 */

get_header(); ?>

<main id="primary" class="site-main standard-page">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header container">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>

			<div class="entry-content container">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ocean-charter' ),
					'after'  => '</div>',
				) );
				?>
			</div>
		</article>

		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile;
	?>

</main>

<style>
<style>
	.standard-page {
		padding-top: 160px;
		padding-bottom: 120px;
		background: var(--secondary);
		min-height: 80vh;
	}
	.entry-header {
		margin-bottom: 60px;
		text-align: center;
	}
	.entry-title {
		font-family: var(--font-heading);
		font-size: clamp(40px, 5vw, 64px);
		color: var(--text-light);
		font-weight: 400;
	}
	.entry-content {
		max-width: 800px;
		line-height: 1.8;
		color: var(--text-muted);
		font-size: 18px;
	}
	.entry-content p {
		margin-bottom: 25px;
	}
</style>

<?php get_footer();
