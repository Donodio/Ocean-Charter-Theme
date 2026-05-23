<?php
/**
 * Template Name: Contact Page
 * Ocean Charter - Contact - Faithful to Stitch "Ocean Charter - Contact" design
 *
 * @package OceanCharter
 */

get_header();

if ( have_posts() ) {
    the_post();
    if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
        echo '<main id="main" class="oc-page oc-page--contact">';
        the_content();
        echo '</main>';
        get_footer();
        exit;
    }
}
?>

<main id="primary" class="site-main page-contact">

	<!-- ═══════════════════════════════════════════════
	     PAGE HERO
	     ═══════════════════════════════════════════════ -->
	<section class="ct-hero">
		<div class="ct-hero__bg"></div>
		<div class="container ct-hero__content">
			<span class="fp-eyebrow">Get In Touch</span>
			<h1 class="ct-hero__title">Connect<br>with <span class="ct-hero__accent">Us</span></h1>
			<p class="ct-hero__desc">Your bespoke maritime journey begins here. Experience the pinnacle of nautical luxury with our dedicated concierge.</p>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     MAIN CONTACT SECTION
	     ═══════════════════════════════════════════════ -->
	<section class="ct-main">
		<div class="container ct-main__inner">

			<!-- Contact Info Column -->
			<div class="ct-info">
				<h2 class="ct-info__title">Global Headquarters</h2>

				<div class="ct-info-block">
					<div class="ct-info-block__icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
					</div>
					<div>
						<strong>Monaco Office</strong>
						<span>7 Quai Antoine 1er, 98000 Monaco</span>
					</div>
				</div>

				<div class="ct-info-block">
					<div class="ct-info-block__icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.7A2 2 0 012 .99h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
					</div>
					<div>
						<strong>International Support</strong>
						<span>+377 99 99 00 00</span>
					</div>
				</div>

				<div class="ct-info-block">
					<div class="ct-info-block__icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
					</div>
					<div>
						<strong>General Enquiries</strong>
						<span>concierge@oceancharter.com</span>
					</div>
				</div>

				<div class="ct-whatsapp">
					<div class="ct-whatsapp__label">
						<strong>Instant Booking</strong>
						<span>WhatsApp our Concierge</span>
					</div>
					<a href="https://wa.me/377000000" target="_blank" rel="noopener" class="ct-whatsapp__btn">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
						Chat Now
					</a>
				</div>

				<div class="ct-offices">
					<h3 class="ct-offices__title">Additional Offices</h3>
					<div class="ct-office">
						<strong>Monaco HQ</strong>
						<span>7 Quai Antoine 1er, Monaco</span>
					</div>
					<div class="ct-office">
						<strong>Palma de Mallorca</strong>
						<span>Passeig Marítim, 07014, Spain</span>
					</div>
					<div class="ct-office">
						<strong>Fort Lauderdale</strong>
						<span>100 N Andrews Ave, FL 33301, USA</span>
					</div>
					<div class="ct-office">
						<strong>Dubai</strong>
						<span>Dubai Marina, Gate 1, UAE</span>
					</div>
				</div>
			</div>

			<!-- Contact Form Column -->
			<div class="ct-form-wrap">
				<h2 class="ct-form__title">Send an Inquiry</h2>
				<p class="ct-form__subtitle">Our charter specialists typically respond within 2 hours during business hours.</p>

				<form class="ct-form" id="charter-inquiry-form" method="post" novalidate>
					<?php wp_nonce_field( 'ocean_charter_contact', 'oc_nonce' ); ?>

					<div class="ct-form__row ct-form__row--two">
						<div class="ct-form__field">
							<label for="ct-first-name">First Name *</label>
							<input type="text" id="ct-first-name" name="first_name" placeholder="James" required>
						</div>
						<div class="ct-form__field">
							<label for="ct-last-name">Last Name *</label>
							<input type="text" id="ct-last-name" name="last_name" placeholder="Hartley" required>
						</div>
					</div>

					<div class="ct-form__row ct-form__row--two">
						<div class="ct-form__field">
							<label for="ct-email">Email Address *</label>
							<input type="email" id="ct-email" name="email" placeholder="james@example.com" required>
						</div>
						<div class="ct-form__field">
							<label for="ct-phone">Phone Number</label>
							<input type="tel" id="ct-phone" name="phone" placeholder="+1 (555) 000-0000">
						</div>
					</div>

					<div class="ct-form__row ct-form__row--two">
						<div class="ct-form__field">
							<label for="ct-charter-type">Charter Type</label>
							<select id="ct-charter-type" name="charter_type">
								<option value="">Select a package...</option>
								<option>Sunset Cruise</option>
								<option>Day Charter</option>
								<option>Multi-Day Voyage</option>
								<option>Corporate Event</option>
								<option>Birthday / Celebration</option>
								<option>Wedding at Sea</option>
								<option>Bespoke Voyage</option>
							</select>
						</div>
						<div class="ct-form__field">
							<label for="ct-guests">Number of Guests</label>
							<select id="ct-guests" name="guests">
								<option value="">Select...</option>
								<option>2 – 4 guests</option>
								<option>5 – 8 guests</option>
								<option>9 – 12 guests</option>
								<option>13 – 20 guests</option>
								<option>20+ guests</option>
							</select>
						</div>
					</div>

					<div class="ct-form__row ct-form__row--two">
						<div class="ct-form__field">
							<label for="ct-depart-date">Departure Date</label>
							<input type="date" id="ct-depart-date" name="depart_date">
						</div>
						<div class="ct-form__field">
							<label for="ct-destination">Destination Region</label>
							<select id="ct-destination" name="destination">
								<option value="">Select region...</option>
								<option>Mediterranean</option>
								<option>Caribbean & Bahamas</option>
								<option>South Pacific</option>
								<option>South East Asia</option>
								<option>Northern Europe</option>
								<option>Indian Ocean</option>
								<option>Other / Open to suggestions</option>
							</select>
						</div>
					</div>

					<div class="ct-form__field">
						<label for="ct-message">Tell Us About Your Dream Voyage</label>
						<textarea id="ct-message" name="message" rows="5" placeholder="Share any specific requirements, dream destinations, or special occasions we should know about..."></textarea>
					</div>

					<div class="ct-form__field ct-form__field--check">
						<label class="ct-checkbox">
							<input type="checkbox" name="privacy" required>
							<span>I agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a></span>
						</label>
					</div>

					<button type="submit" class="btn-primary ct-form__submit">Send My Inquiry →</button>
				</form>
			</div>

		</div>
	</section>

</main>

<style>
.page-contact { background: var(--secondary); }

/* Hero */
.ct-hero { position: relative; min-height: 28vh; display: flex; align-items: center; overflow: hidden; padding: 100px 0 50px; }
.ct-hero__bg { position: absolute; inset: 0; background: linear-gradient(160deg, #060e1a 0%, #0d1f35 60%, #060e1a 100%); }
.ct-hero__bg::before { content: ''; position: absolute; right: 0; top: 0; width: 50%; height: 100%; background: radial-gradient(ellipse at 90% 50%, rgba(217,178,48,0.06) 0%, transparent 60%); }
.ct-hero__content { position: relative; z-index: 2; }
.ct-hero__title { font-family: var(--font-heading); font-size: clamp(42px, 7vw, 88px); font-weight: 400; color: var(--text-light); margin: 16px 0 24px; line-height: 1.1; }
.ct-hero__accent { color: var(--primary); }
.ct-hero__desc { font-size: 20px; color: var(--text-muted); max-width: 550px; line-height: 1.7; }

/* Main Contact */
.ct-main { padding: 100px 0 120px; }
.ct-main__inner { display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px; align-items: start; }

/* Info column */
.ct-info__title { font-family: var(--font-heading); font-size: 30px; color: var(--text-light); font-weight: 400; margin: 0 0 40px; }
.ct-info-block { display: flex; gap: 16px; align-items: flex-start; margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--glass-border); }
.ct-info-block:last-of-type { border-bottom: none; }
.ct-info-block__icon { width: 44px; height: 44px; background: var(--surface); border: 1px solid var(--glass-border); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0; }
.ct-info-block strong { display: block; color: var(--text-light); font-size: 15px; margin-bottom: 4px; font-weight: 600; }
.ct-info-block span { color: var(--text-muted); font-size: 15px; }

/* WhatsApp */
.ct-whatsapp { background: linear-gradient(135deg, rgba(37, 211, 102, 0.06), rgba(37, 211, 102, 0.02)); border: 1px solid rgba(37, 211, 102, 0.2); border-radius: var(--radius-lg); padding: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin: 32px 0; }
.ct-whatsapp__label strong { display: block; color: var(--text-light); font-size: 15px; margin-bottom: 4px; }
.ct-whatsapp__label span { color: var(--text-muted); font-size: 14px; }
.ct-whatsapp__btn { display: inline-flex; align-items: center; gap: 10px; background: #25D366; color: #fff; padding: 12px 20px; border-radius: var(--radius); font-weight: 600; font-size: 14px; text-decoration: none; transition: background var(--transition-fast); white-space: nowrap; }
.ct-whatsapp__btn:hover { background: #1DB954; color: #fff; }

/* Offices */
.ct-offices { margin-top: 40px; }
.ct-offices__title { font-family: var(--font-heading); font-size: 20px; color: var(--text-light); font-weight: 500; margin: 0 0 24px; }
.ct-office { padding: 14px 0; border-bottom: 1px solid var(--glass-border); display: flex; flex-direction: column; gap: 3px; }
.ct-office:last-child { border-bottom: none; }
.ct-office strong { color: var(--text-light); font-size: 14px; }
.ct-office span { color: var(--text-muted); font-size: 13px; }

/* Form */
.ct-form-wrap { background: var(--surface); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 56px; }
.ct-form__title { font-family: var(--font-heading); font-size: 30px; color: var(--text-light); font-weight: 400; margin: 0 0 12px; }
.ct-form__subtitle { color: var(--text-muted); font-size: 15px; margin-bottom: 40px; }
.ct-form__row { display: flex; flex-direction: column; gap: 24px; margin-bottom: 24px; }
.ct-form__row--two { flex-direction: row; }
.ct-form__field { display: flex; flex-direction: column; gap: 8px; flex: 1; }
.ct-form__field label { font-size: 13px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
.ct-form input[type="text"],
.ct-form input[type="email"],
.ct-form input[type="tel"],
.ct-form input[type="date"],
.ct-form select,
.ct-form textarea { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid var(--glass-border); border-radius: var(--radius); padding: 14px 18px; color: var(--text-light); font-family: var(--font-body); font-size: 15px; transition: border-color var(--transition-fast), box-shadow var(--transition-fast); }
.ct-form input:focus, .ct-form select:focus, .ct-form textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(217,178,48,0.1); }
.ct-form input::placeholder, .ct-form textarea::placeholder { color: rgba(255,255,255,0.2); }
.ct-form select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23667' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; }
.ct-form select option { background: var(--surface); color: var(--text-light); }
.ct-form textarea { resize: vertical; min-height: 120px; }
.ct-form__field--check { margin-top: 8px; }
.ct-checkbox { display: flex; gap: 12px; align-items: flex-start; cursor: pointer; }
.ct-checkbox input { margin-top: 3px; accent-color: var(--primary); flex-shrink: 0; }
.ct-checkbox span { color: var(--text-muted); font-size: 14px; line-height: 1.5; }
.ct-checkbox a { color: var(--primary); }
.ct-form__submit { width: 100%; margin-top: 24px; padding: 18px; font-size: 15px; }

@media (max-width: 1024px) {
	.ct-main__inner { grid-template-columns: 1fr; gap: 60px; }
}
@media (max-width: 768px) {
	.ct-form-wrap { padding: 32px 24px; }
	.ct-form__row--two { flex-direction: column; }
}
</style>

<?php get_footer(); ?>
