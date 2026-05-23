<?php
/**
 * The template for displaying archive pages
 *
 * @package OceanCharter
 */

get_header(); ?>

<main id="primary" class="site-main generic-archive">

	<?php if ( have_posts() ) : ?>

		<header class="page-header container">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header>

		<div class="container grid-layout">
			<?php
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that
				 * will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();
			?>
		</div>

	<?php else : ?>

		<?php get_template_part( 'template-parts/content', 'none' ); ?>

	<?php endif; ?>

</main>

<style>
	.generic-archive {
		padding-top: 140px;
		padding-bottom: 100px;
	}
	.page-header {
		margin-bottom: 60px;
		text-align: center;
	}
	.page-title {
		font-family: 'Playfair Display', serif;
		font-size: 48px;
		color: var(--primary);
	}
	.archive-description {
		color: var(--text-muted);
		margin-top: 20px;
		font-size: 18px;
	}
	.grid-layout {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
		gap: 30px;
	}
</style>

<?php get_footer();
