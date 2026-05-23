<?php
/**
 * Template part for displaying results in search pages
 *
 * @package OceanCharter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php ocean_charter_posted_on(); ?>
		</div>
		<?php endif; ?>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>

<style>
	.search-result-item {
		background: var(--glass);
		border: 1px solid var(--glass-border);
		padding: 30px;
		border-radius: 4px;
	}
	.search-result-item .entry-title {
		font-family: 'Playfair Display', serif;
		font-size: 24px;
		margin-bottom: 15px;
	}
	.search-result-item a {
		color: #fff;
		text-decoration: none;
	}
	.search-result-item a:hover {
		color: var(--primary);
	}
	.entry-summary {
		color: var(--text-muted);
		font-size: 14px;
		line-height: 1.6;
	}
</style>
