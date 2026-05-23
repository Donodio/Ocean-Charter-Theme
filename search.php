<?php
/**
 * The template for displaying search results pages
 *
 * @package OceanCharter
 */

get_header(); ?>

<main id="primary" class="site-main search-results">

	<header class="page-header container">
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search Results for: %s', 'ocean-charter' ), '<span>' . get_search_query() . '</span>' );
			?>
		</h1>
	</header>

	<div class="container results-grid">
		<?php if ( have_posts() ) : ?>

			<?php
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you have a custom design for each post type, you can use post-type-specific content parts.
				 */
				if ( 'boat' === get_post_type() ) {
					get_template_part( 'template-parts/content', 'boat' );
				} else {
					get_template_part( 'template-parts/content', 'search' );
				}

			endwhile;

			the_posts_navigation();

		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>
	</div>

</main>

<style>
	.search-results {
		padding-top: 140px;
		padding-bottom: 100px;
	}
	.page-header {
		margin-bottom: 60px;
		text-align: center;
	}
	.page-title {
		font-family: 'Playfair Display', serif;
		font-size: 32px;
	}
	.page-title span {
		color: var(--primary);
	}
	.results-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
		gap: 30px;
	}
</style>

<?php get_footer();
