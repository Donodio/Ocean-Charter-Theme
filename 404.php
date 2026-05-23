<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package OceanCharter
 */

get_header(); ?>

<main id="primary" class="site-main error-404">

	<section class="error-content container">
		<span class="error-code">404</span>
		<h1 class="page-title">Lost at Sea?</h1>
		<p>It seems we can't find the page you're looking for. Let us guide you back to the fleet.</p>
		<div class="error-actions">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">Back to Home</a>
			<a href="<?php echo get_post_type_archive_link('boat'); ?>" class="btn-secondary">Explore Fleet</a>
		</div>
	</section>

</main>

<style>
<style>
	.error-404 {
		height: 80vh;
		min-height: 600px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		background: var(--secondary);
	}
	.error-code {
		display: block;
		font-size: 140px;
		font-weight: 800;
		color: var(--primary);
		line-height: 1;
		margin-bottom: 24px;
		opacity: 0.15;
		letter-spacing: -2px;
	}
	.error-404 .page-title {
		font-family: var(--font-heading);
		font-size: clamp(40px, 5vw, 64px);
		margin-bottom: 24px;
		color: var(--text-light);
		font-weight: 400;
	}
	.error-404 p {
		font-size: 18px;
		color: var(--text-muted);
		max-width: 500px;
		margin: 0 auto 40px;
	}
	.error-actions {
		display: flex;
		justify-content: center;
		gap: 20px;
	}
</style>

<?php get_footer();
