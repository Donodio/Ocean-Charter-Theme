<?php
/**
 * Template part for displaying posts
 *
 * @package OceanCharter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'standard-post-card' ); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="card-meta">
			<?php ocean_charter_posted_on(); ?>
		</div>

		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>
</article>

<style>
	.standard-post-card {
		background: var(--glass);
		border: 1px solid var(--glass-border);
		border-radius: 4px;
		padding: 25px;
		display: flex;
		flex-direction: column;
	}
	.post-thumbnail {
		margin: -25px -25px 25px -25px;
		overflow: hidden;
	}
	.post-thumbnail img {
		width: 100%;
		aspect-ratio: 16/9;
		object-fit: cover;
		transition: transform 0.3s ease;
	}
	.standard-post-card:hover .post-thumbnail img {
		transform: scale(1.05);
	}
	.card-meta {
		font-size: 11px;
		color: var(--primary);
		text-transform: uppercase;
		margin-bottom: 15px;
		letter-spacing: 1.5px;
	}
	.standard-post-card .entry-title {
		font-family: 'Playfair Display', serif;
		font-size: 22px;
		margin-bottom: 15px;
	}
	.standard-post-card a {
		color: #fff;
		text-decoration: none;
	}
	.standard-post-card a:hover {
		color: var(--primary);
	}
	.entry-content {
		color: var(--text-muted);
		font-size: 14px;
		line-height: 1.6;
	}
</style>
