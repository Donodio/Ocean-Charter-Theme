<?php
/**
 * Template part for displaying boats in archives
 *
 * @package OceanCharter
 */

$boat_id = get_the_ID();
$length  = ocean_charter_get_boat_meta_helper( $boat_id, '_bbc_length' );
$guests  = ocean_charter_get_boat_meta_helper( $boat_id, '_bbc_guests' );
$price   = ocean_charter_get_boat_meta_helper( $boat_id, '_bbc_price_half_day' );
$location = ocean_charter_get_boat_meta_helper( $boat_id, '_bbc_location' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'boat-card' ); ?>>
	<a href="<?php the_permalink(); ?>" class="boat-card-link">
		<div class="boat-card-image">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large' ); ?>
			<?php else : ?>
				<div class="placeholder-img"></div>
			<?php endif; ?>
			<div class="boat-card-overlay">
				<span class="btn-primary">View Charter</span>
			</div>
			
			<?php if ( $location ) : ?>
			<div class="boat-card-location-badge">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
				<?php echo esc_html( $location ); ?>
			</div>
			<?php endif; ?>
		</div>
		
		<div class="boat-card-content">
			<div class="boat-card-header">
				<h3 class="boat-card-title"><?php the_title(); ?></h3>
				<div class="boat-card-price">
					<span class="price-val">$<?php echo number_format( $price ?: 0 ); ?></span>
					<span class="price-lbl">/ day</span>
				</div>
			</div>
			
			<div class="boat-card-specs">
				<div class="card-spec">
					<span class="spec-val"><?php echo esc_html( $length ); ?></span>
					<span class="spec-unit">Feet</span>
				</div>
				<div class="card-spec">
					<span class="spec-val"><?php echo esc_html( $guests ); ?></span>
					<span class="spec-unit">Guests</span>
				</div>
				<div class="card-spec">
					<span class="spec-val"><?php echo esc_html( ocean_charter_get_boat_meta_helper( $boat_id, '_bbc_cabins' ) ?: 1 ); ?></span>
					<span class="spec-unit">Cabins</span>
				</div>
			</div>
		</div>
	</a>
</article>

<style>
	.boat-card {
		background: var(--surface);
		border: 1px solid var(--glass-border);
		border-radius: var(--radius-lg);
		overflow: hidden;
		transition: all var(--transition-normal);
		position: relative;
	}
	.boat-card:hover {
		transform: translateY(-8px);
		border-color: var(--glass-highlight);
		box-shadow: var(--shadow-lg), 0 0 30px rgba(217, 178, 48, 0.05);
	}
	.boat-card-link {
		text-decoration: none;
		color: inherit;
		display: block;
		height: 100%;
		display: flex;
		flex-direction: column;
	}
	.boat-card-image {
		position: relative;
		height: 280px;
		overflow: hidden;
	}
	.boat-card-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform var(--transition-slow);
	}
	.placeholder-img {
		width: 100%;
		height: 100%;
		background: linear-gradient(135deg, var(--surface), var(--secondary));
	}
	.boat-card:hover .boat-card-image img {
		transform: scale(1.08);
	}
	.boat-card-overlay {
		position: absolute;
		inset: 0;
		background: rgba(10, 16, 26, 0.5);
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 0;
		transition: opacity var(--transition-normal);
		backdrop-filter: blur(2px);
	}
	.boat-card:hover .boat-card-overlay {
		opacity: 1;
	}
	.boat-card-overlay .btn-primary {
		transform: translateY(20px);
		opacity: 0;
		transition: all var(--transition-bounce);
	}
	.boat-card:hover .boat-card-overlay .btn-primary {
		transform: translateY(0);
		opacity: 1;
	}
	
	.boat-card-location-badge {
		position: absolute;
		top: 16px;
		left: 16px;
		background: rgba(10, 16, 26, 0.6);
		backdrop-filter: blur(8px);
		-webkit-backdrop-filter: blur(8px);
		padding: 6px 12px;
		border-radius: var(--radius-full);
		font-size: 11px;
		font-weight: 600;
		color: var(--text-light);
		display: flex;
		align-items: center;
		gap: 6px;
		border: 1px solid var(--glass-border);
		letter-spacing: 0.02em;
	}

	.boat-card-content {
		padding: 24px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
		background: var(--surface);
	}
	.boat-card-header {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 16px;
		margin-bottom: 24px;
	}
	.boat-card-title {
		font-family: var(--font-heading);
		font-size: 24px;
		margin: 0;
		color: var(--text-light);
		line-height: 1.2;
		font-weight: 600;
	}
	
	.boat-card-price {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		background: var(--surface-light);
		padding: 8px 12px;
		border-radius: var(--radius);
		border: 1px solid var(--glass-border);
	}
	.price-val {
		font-size: 18px;
		font-weight: 700;
		color: var(--primary);
		line-height: 1;
	}
	.price-lbl {
		font-size: 10px;
		color: var(--text-muted);
		margin-top: 4px;
		text-transform: uppercase;
		letter-spacing: 0.05em;
	}

	.boat-card-specs {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 8px;
		margin-top: auto;
		background: var(--surface-light);
		padding: 12px;
		border-radius: var(--radius);
	}
	.card-spec {
		display: flex;
		flex-direction: column;
		align-items: center;
		padding: 0 4px;
	}
	.card-spec:not(:last-child) {
		border-right: 1px solid var(--glass-border);
	}
	.spec-val {
		font-size: 15px;
		font-weight: 600;
		color: var(--text-light);
		line-height: 1.2;
	}
	.spec-unit {
		font-size: 11px;
		color: var(--text-muted);
		margin-top: 2px;
	}
</style>

