<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php 
	wp_head(); 
	$header_bg = get_option( 'casaview_header_bg', '#ffffff' );
	$menu_text_color = get_option( 'casaview_menu_text_color', '#1c1d21' );
	?>
	<style>
		.site-header {
			background-color: <?php echo esc_attr( $header_bg ); ?> !important;
			padding: 15px 0px !important;
		}
		.main-navigation ul li a {
			color: <?php echo esc_attr( $menu_text_color ); ?> !important;
		}
		.main-navigation ul li.menu-item-has-children > a i {
			color: <?php echo esc_attr( $menu_text_color ); ?> !important;
		}

		/* ── Hamburger: hidden on desktop ── */
		#cv-hamburger {
			display: none;
		}

		@media (max-width: 1024px) {

			/* Header actions: flex row, aligned, with gap for CTA + hamburger */
			.header-actions {
				display: flex;
				align-items: center;
				gap: 12px;
			}

			/* Show hamburger */
			#cv-hamburger {
				display: flex;
				align-items: center;
				justify-content: center;
				background: transparent;
				border: 1.5px solid #1c1d21;
				border-radius: 6px;
				width: 40px;
				height: 40px;
				cursor: pointer;
				padding: 0;
				flex-shrink: 0;
				transition: none;
			}
			#cv-hamburger:hover {
				background: transparent;
			}
			#cv-hamburger span {
				display: block;
				width: 20px;
				height: 2px;
				background: #1c1d21;
				border-radius: 2px;
				transition: none;
				position: absolute;
			}
			#cv-hamburger span:nth-child(1) { transform: translateY(-6px); }
			#cv-hamburger span:nth-child(2) { transform: translateY(0); }
			#cv-hamburger span:nth-child(3) { transform: translateY(6px); }

			/* X state when open — instant, no animation */
			#cv-hamburger.cv-open span:nth-child(1) { transform: translateY(0) rotate(45deg); }
			#cv-hamburger.cv-open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
			#cv-hamburger.cv-open span:nth-child(3) { transform: translateY(0) rotate(-45deg); }

			/* Mobile nav drawer — instant open, no animation */
			.main-navigation {
				display: none;
				position: absolute;
				top: 100%;
				left: 0;
				right: 0;
				background: <?php echo esc_attr( $header_bg ); ?>;
				background-color: <?php echo esc_attr( $header_bg ); ?>;
				border-top: 1px solid rgba(28,29,33,0.1);
				box-shadow: 0 8px 24px rgba(0,0,0,0.10);
				z-index: 999;
				padding: 12px 0;
				transition: none !important;
				animation: none !important;
			}
			.main-navigation.cv-nav-open {
				display: block;
			}
			.main-navigation ul {
				flex-direction: column;
				gap: 0;
				padding: 0;
				margin: 0;
			}
			.main-navigation ul li {
				border-bottom: 1px solid rgba(28,29,33,0.07);
			}
			.main-navigation ul li:last-child {
				border-bottom: none;
			}
			.main-navigation ul li a {
				display: block;
				padding: 14px 24px;
				font-size: 15px;
				transition: none !important;
			}
			.main-navigation ul li a:hover {
				padding-left: 24px; /* cancel desktop hover indent */
			}

			/* Sub-menus: fully visible, instant, no hover/fade/slide effects */
			.main-navigation ul .sub-menu {
				position: static !important;
				opacity: 1 !important;
				visibility: visible !important;
				transform: none !important;
				box-shadow: none !important;
				background: rgba(28,29,33,0.04) !important;
				border-radius: 0 !important;
				border: none !important;
				padding: 0 !important;
				transition: none !important;
				animation: none !important;
				display: block !important;
			}
			.main-navigation ul li:hover > .sub-menu {
				transform: none !important;
				opacity: 1 !important;
				visibility: visible !important;
			}
			.main-navigation ul .sub-menu li a {
				padding: 12px 24px 12px 40px;
				font-size: 14px;
				transition: none !important;
			}
			.main-navigation ul .sub-menu li a:hover {
				padding-left: 40px; /* cancel desktop hover indent */
				background: transparent;
			}

			/* Keep site-header position relative so drawer anchors correctly */
			.site-header {
				position: relative !important;
			}
		}
	</style>
</head>
<body <?php body_class( is_rtl() ? 'rtl' : '' ); ?>>
<?php wp_body_open(); ?>

<?php
$phone = get_option( 'casaview_contact_phone' );
$email = get_option( 'casaview_contact_email' );
$whatsapp = get_option( 'casaview_contact_whatsapp' );
$fb = get_option( 'casaview_social_facebook' );
$tw = get_option( 'casaview_social_twitter' );
$ig = get_option( 'casaview_social_instagram' );
$li = get_option( 'casaview_social_linkedin' );

$show_top_bar = ! empty( $phone ) || ! empty( $email ) || ! empty( $whatsapp ) || ! empty( $fb ) || ! empty( $tw ) || ! empty( $ig ) || ! empty( $li );

$sticky_header = get_option( 'casaview_sticky_header', 1 );
$header_class = 'site-header';
if ( $sticky_header ) {
	$header_class .= ' sticky-header';
}
?>

<?php if ( $show_top_bar ) : ?>
<div class="header-top-bar">
	<div class="container top-bar-container">
		<div class="top-bar-contact">
			<?php if ( ! empty( $phone ) ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><i class="fa-solid fa-phone"></i> <?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<?php if ( ! empty( $email ) ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fa-solid fa-envelope"></i> <?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
			<?php if ( ! empty( $whatsapp ) ) : ?>
				<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
			<?php endif; ?>
		</div>
		<div class="top-bar-social">
			<?php if ( ! empty( $fb ) ) : ?>
				<a href="<?php echo esc_url( $fb ); ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
			<?php endif; ?>
			<?php if ( ! empty( $tw ) ) : ?>
				<a href="<?php echo esc_url( $tw ); ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
			<?php endif; ?>
			<?php if ( ! empty( $ig ) ) : ?>
				<a href="<?php echo esc_url( $ig ); ?>" target="_blank"><i class="fa-brands fa-instagram"></i></a>
			<?php endif; ?>
			<?php if ( ! empty( $li ) ) : ?>
				<a href="<?php echo esc_url( $li ); ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<header class="<?php echo esc_attr( $header_class ); ?>">
	<div class="container header-container">
		<?php
		$logo_url = get_option( 'casaview_logo_url' );
		if ( ! $logo_url ) {
			$logo_url = get_template_directory_uri() . '/assets/images/logo.jpg';
		}
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-wrapper" style="display: flex; align-items: center; gap: 14px;">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="PR Works Real Estate" style="height: 110px; width: auto; object-fit: contain; border-radius: 4px;">
			<?php if ( ! get_option( 'casaview_logo_url' ) ) : ?>
			<div class="logo-text-group" style="display: flex; flex-direction: column;">
				<div class="logo-main" style="line-height: 1.1; font-size: 22px;">PR<span>WORKS</span></div>
				<div class="logo-sub" style="font-size: 9px; letter-spacing: 2.5px;">REAL ESTATE</div>
			</div>
			<?php endif; ?>
		</a>

		<nav class="main-navigation" id="cv-main-nav">
			<?php casaview_render_custom_menu(); ?>
		</nav>

		<div class="header-actions">
			<?php 
			$cta_text = get_option( 'casaview_header_cta_text', 'List With Us' );
			$cta_link = get_option( 'casaview_header_cta_link', '#contact' );
			// CTA Popup options
			$cta_popup_enable      = get_option( 'casaview_cta_popup_enable', 0 );
			$cta_popup_title       = get_option( 'casaview_cta_popup_title', '' );
			$cta_popup_description = get_option( 'casaview_cta_popup_description', '' );
			$cta_popup_shortcode   = get_option( 'casaview_cta_popup_shortcode', '' );
			?>
			<?php if ( $cta_popup_enable ) : ?>
			<button class="header-cta" id="casaview-cta-popup-trigger" type="button" aria-haspopup="dialog" aria-expanded="false"><?php echo esc_html( $cta_text ); ?></button>
			<?php else : ?>
			<button class="header-cta" onclick="location.href='<?php echo esc_url( $cta_link ); ?>'" type="button"><?php echo esc_html( $cta_text ); ?></button>
			<?php endif; ?>
			<!-- Hamburger: visible only on mobile/tablet via CSS -->
			<button id="cv-hamburger" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="cv-main-nav" style="position:relative;">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
	function checkHeaderSticky() {
		const header = document.querySelector('.site-header.sticky-header');
		if (header) {
			if (window.scrollY > 40) {
				header.classList.add('is-sticky');
			} else {
				header.classList.remove('is-sticky');
			}
		}
	}
	window.addEventListener('scroll', checkHeaderSticky);
	checkHeaderSticky();
});
</script>

<script id="cv-hamburger-js">
/* Hamburger menu toggle — mobile/tablet only */
(function () {
	'use strict';
	var btn = document.getElementById('cv-hamburger');
	var nav = document.getElementById('cv-main-nav');
	if (!btn || !nav) return;

	function openNav() {
		nav.classList.add('cv-nav-open');
		btn.classList.add('cv-open');
		btn.setAttribute('aria-expanded', 'true');
	}
	function closeNav() {
		nav.classList.remove('cv-nav-open');
		btn.classList.remove('cv-open');
		btn.setAttribute('aria-expanded', 'false');
	}
	function toggleNav() {
		nav.classList.contains('cv-nav-open') ? closeNav() : openNav();
	}

	btn.addEventListener('click', function (e) {
		e.stopPropagation();
		toggleNav();
	});

	/* Close when clicking outside */
	document.addEventListener('click', function (e) {
		if (nav.classList.contains('cv-nav-open') && !nav.contains(e.target) && e.target !== btn) {
			closeNav();
		}
	});

	/* Close on Escape key */
	document.addEventListener('keydown', function (e) {
		if ((e.key === 'Escape' || e.key === 'Esc') && nav.classList.contains('cv-nav-open')) {
			closeNav();
			btn.focus();
		}
	});

	/* Close nav when a menu link is clicked (SPA-style navigation) */
	nav.querySelectorAll('a').forEach(function (link) {
		link.addEventListener('click', function () {
			closeNav();
		});
	});
})();
</script>

<?php if ( $cta_popup_enable ) : ?>
<!-- =====================================================
     CTA Header Popup Modal
     Scoped to header CTA popup feature only.
===================================================== -->
<div id="casaview-cta-modal"
	 role="dialog"
	 aria-modal="true"
	 aria-labelledby="casaview-cta-modal-title"
	 class="casaview-cta-overlay"
	 style="display:none;">
	<div class="casaview-cta-box">

		<button class="casaview-cta-close"
		        id="casaview-cta-modal-close"
		        type="button"
		        aria-label="<?php esc_attr_e( 'Close popup', 'casaview' ); ?>">&times;</button>

		<?php if ( $cta_popup_title ) : ?>
		<h2 id="casaview-cta-modal-title" class="casaview-cta-title"><?php echo esc_html( $cta_popup_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $cta_popup_description ) : ?>
		<p class="casaview-cta-desc"><?php echo esc_html( $cta_popup_description ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_popup_shortcode ) : ?>
		<div class="casaview-cta-content">
			<?php echo do_shortcode( $cta_popup_shortcode ); ?>
		</div>
		<?php endif; ?>

	</div><!-- .casaview-cta-box -->
</div><!-- #casaview-cta-modal -->

<style id="casaview-cta-modal-css">
/* ---- CTA Popup Modal — all selectors scoped under casaview-cta- ---- */
.casaview-cta-overlay {
	position: fixed;
	inset: 0;
	z-index: 999999;
	background: rgba(10, 10, 20, 0.65);
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 16px;
	backdrop-filter: blur(3px);
	-webkit-backdrop-filter: blur(3px);
}
.casaview-cta-overlay.cv-fade-in {
	animation: cvFadeIn 0.22s ease both;
}
@keyframes cvFadeIn {
	from { opacity: 0; }
	to   { opacity: 1; }
}
.casaview-cta-box {
	position: relative;
	background: #fff;
	border-radius: 14px;
	padding: 44px 40px 38px;
	max-width: 540px;
	width: 100%;
	max-height: 90vh;
	overflow-y: auto;
	box-shadow: 0 24px 64px rgba(0, 0, 0, 0.28);
	animation: cvSlideUp 0.28s cubic-bezier(.22,1,.36,1) both;
}
@keyframes cvSlideUp {
	from { transform: translateY(28px); opacity: 0; }
	to   { transform: translateY(0);    opacity: 1; }
}
.casaview-cta-close {
	position: absolute;
	top: 12px;
	right: 16px;
	background: transparent;
	border: none;
	font-size: 28px;
	line-height: 1;
	cursor: pointer;
	color: #888;
	padding: 4px 8px;
	border-radius: 4px;
	transition: color 0.18s, background 0.18s;
}
.casaview-cta-close:hover {
	color: #c5a880;
	background: rgba(197, 168, 128, 0.1);
}
.casaview-cta-title {
	font-size: 22px;
	font-weight: 700;
	color: #1c1d21;
	margin: 0 0 10px;
	padding-right: 28px;
	line-height: 1.3;
}
.casaview-cta-desc {
	color: #555;
	font-size: 15px;
	line-height: 1.65;
	margin: 0 0 22px;
}
.casaview-cta-content {
	margin-top: 4px;
}
/* Responsive */
@media (max-width: 600px) {
	.casaview-cta-box {
		padding: 36px 20px 28px;
		border-radius: 10px;
	}
	.casaview-cta-title { font-size: 18px; }
	.casaview-cta-desc  { font-size: 14px; }
}

/* ---- CTA Popup Form Styles (styled exactly like Contact Page forms, scoped to other pages) ---- */
body:not(.page-template-page-contact) #casaview-cta-modal .form-group,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form {
	display: flex !important;
	flex-direction: column !important;
	gap: 20px !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form-control-wrap {
	display: block !important;
	width: 100% !important;
	margin-top: 8px !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal label,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form label {
	font-family: var(--font-en), sans-serif !important;
	font-size: 14px !important;
	font-weight: 600 !important;
	color: #1c1d21 !important;
	text-transform: none !important;
	letter-spacing: normal !important;
	margin-bottom: 0 !important;
	display: block !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal label br,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form label br {
	display: none !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal input[type="text"],
body:not(.page-template-page-contact) #casaview-cta-modal input[type="email"],
body:not(.page-template-page-contact) #casaview-cta-modal input[type="tel"],
body:not(.page-template-page-contact) #casaview-cta-modal textarea,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input[type="text"],
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input[type="email"],
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input[type="tel"],
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form textarea {
	width: 100% !important;
	background: #ffffff !important;
	border: 1px solid #e2e8f0 !important;
	border-radius: 8px !important;
	color: #1c1d21 !important;
	padding: 14px 16px !important;
	font-size: 14px !important;
	font-weight: 500 !important;
	outline: none !important;
	transition: all 0.3s ease !important;
	font-family: var(--font-en), sans-serif !important;
	box-sizing: border-box !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal input::placeholder,
body:not(.page-template-page-contact) #casaview-cta-modal textarea::placeholder,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input::placeholder,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form textarea::placeholder {
	color: #a0aec0 !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal input:focus,
body:not(.page-template-page-contact) #casaview-cta-modal textarea:focus,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input:focus,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form textarea:focus {
	border-color: var(--accent-gold) !important;
	box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.15) !important;
	background: #ffffff !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal textarea,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form textarea {
	height: 120px !important;
	resize: none !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal input[type="submit"],
body:not(.page-template-page-contact) #casaview-cta-modal .form-submit-btn,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input[type="submit"] {
	background: var(--primary-color) !important;
	color: #ffffff !important;
	font-weight: 600 !important;
	font-size: 14px !important;
	text-transform: none !important;
	letter-spacing: normal !important;
	padding: 14px 28px !important;
	border-radius: 8px !important;
	border: none !important;
	cursor: pointer !important;
	transition: all 0.3s ease !important;
	margin-top: 5px !important;
	width: auto !important;
	box-sizing: border-box !important;
	display: inline-block !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal input[type="submit"]:hover,
body:not(.page-template-page-contact) #casaview-cta-modal .form-submit-btn:hover,
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-form input[type="submit"]:hover {
	background: #d44e27 !important;
	transform: translateY(-1px) !important;
	box-shadow: 0 6px 20px rgba(240, 100, 60, 0.25) !important;
}

/* Response Messages & validation errors */
body:not(.page-template-page-contact) #casaview-cta-modal .wpcf7-response-output {
	margin: 20px 0 0 0 !important;
	padding: 12px 16px !important;
	border-radius: 8px !important;
	font-size: 13px !important;
	border: 1px solid !important;
	font-weight: 600 !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal div.wpcf7-validation-errors {
	background: rgba(240, 100, 60, 0.1) !important;
	border-color: #f0643c !important;
	color: #f0643c !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal div.wpcf7-mail-sent-ok {
	background: rgba(28, 187, 140, 0.1) !important;
	border-color: #1cbb8c !important;
	color: #1cbb8c !important;
}

body:not(.page-template-page-contact) #casaview-cta-modal span.wpcf7-not-valid-tip {
	color: #f0643c !important;
	font-size: 12px !important;
	margin-top: 5px !important;
	display: block !important;
}
</style>

<script id="casaview-cta-modal-js">
/* CTA Header Popup — vanilla JS, no external deps */
(function () {
	'use strict';
	var trigger  = document.getElementById('casaview-cta-popup-trigger');
	var modal    = document.getElementById('casaview-cta-modal');
	var closeBtn = document.getElementById('casaview-cta-modal-close');

	if (!trigger || !modal) return;

	function openModal() {
		modal.style.display = 'flex';
		modal.classList.add('cv-fade-in');
		document.body.style.overflow = 'hidden';
		trigger.setAttribute('aria-expanded', 'true');
		/* Move focus into modal for accessibility */
		var firstFocus = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
		if (firstFocus) setTimeout(function() { firstFocus.focus(); }, 50);
	}

	function closeModal() {
		modal.style.display = 'none';
		modal.classList.remove('cv-fade-in');
		document.body.style.overflow = '';
		trigger.setAttribute('aria-expanded', 'false');
		trigger.focus();
	}

	/* Open on CTA click */
	trigger.addEventListener('click', openModal);

	/* Close on X button */
	closeBtn.addEventListener('click', closeModal);

	/* Close on overlay click (outside the box) */
	modal.addEventListener('click', function (e) {
		if (e.target === modal) closeModal();
	});

	/* Close on Escape key */
	document.addEventListener('keydown', function (e) {
		if ((e.key === 'Escape' || e.key === 'Esc') && modal.style.display === 'flex') {
			closeModal();
		}
	});
})();
</script>
<?php endif; // casaview_cta_popup_enable ?>

