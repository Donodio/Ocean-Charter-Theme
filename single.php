<?php
/**
 * The template for displaying all single posts
 *
 * @package OceanCharter
 */

get_header(); ?>

<main id="primary" class="site-main standard-post">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header container">
				<div class="entry-meta">
					<?php ocean_charter_posted_on(); ?>
				</div>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="post-featured-image container">
					<?php the_post_thumbnail( 'full' ); ?>
				</div>
			<?php endif; ?>

			<div class="entry-content container">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ocean-charter' ),
					'after'  => '</div>',
				) );
				?>
			</div>

			<footer class="entry-footer container">
				<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( esc_html__( ', ', 'ocean-charter' ) );
				if ( $categories_list ) {
					/* translators: 1: list of categories. */
					printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ocean-charter' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</footer>
		</article>

		<?php
		the_post_navigation();

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile;
	?>

</main>

<style>
	.standard-post {
		padding-top: 140px;
		padding-bottom: 100px;
	}
	.entry-meta {
		margin-bottom: 10px;
		font-size: 13px;
		color: var(--primary);
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.entry-header {
		margin-bottom: 40px;
		text-align: center;
	}
	.entry-title {
		font-family: 'Playfair Display', serif;
		font-size: 48px;
		color: #fff;
	}
	.post-featured-image {
		margin-bottom: 60px;
	}
	.post-featured-image img {
		width: 100%;
		border-radius: 8px;
	}
	.entry-content {
		max-width: 800px;
		line-height: 1.8;
		color: var(--text-muted);
	}
	.entry-footer {
		margin-top: 60px;
		padding-top: 20px;
		border-top: 1px solid var(--glass-border);
		font-size: 14px;
		color: var(--text-muted);
	}
</style>

<?php get_footer();
