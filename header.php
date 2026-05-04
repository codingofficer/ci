<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NVWBVHM6');</script>
<!-- End Google Tag Manager -->

<meta name="google-site-verification" content="2uIXIOWHEnfyFTBr_6MpPc0Hwbv7RkD8HO1-KSTZEqE" />
	
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVWBVHM6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	
<div class="top-strip"></div>

<header class="ci-header">

  <div class="header-wrap ci-header-grid">

    <!-- LEFT BRANDING -->
    <div class="ci-branding">
      <a class="ci-logo" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="https://new-ci-build.magicsandbox.co.uk/wp-content/uploads/2026/03/logo-c.png"
             alt="<?php bloginfo('name'); ?>">
      </a>

      <div class="ci-tagline">
        <strong>Read and write Cigar Reviews.</strong>
		<br>
        <span>Find Cigar Merchants you can trust.</span>
      </div>
    </div>

    <!-- RIGHT AREA -->
    <div class="ci-right">

      <!-- TOP ROW -->
      <div class="ci-actions">

        <!-- HAMBURGER -->
        <button class="ci-toggle" aria-label="Menu">
          <span></span>
          <span></span>
          <span></span>
        </button>

        <!-- SEARCH -->
        <form class="ci-search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
          <input type="search" name="s" placeholder="Search here..." />
			<button type="submit" aria-label="Search">
			  <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
			</button>
        </form>

        <!-- ACCOUNT -->
        <a href="/account" class="ci-account">
          <i class="fa-regular fa-user"></i>
          <span>Account</span>
        </a>

      </div>

      <!-- NAVIGATION -->
      <nav class="ci-nav">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'ci-menu'
        ]);
        ?>
      </nav>

    </div>

  </div>

</header>
	
<!-- MOBILE SIDEBAR -->
<div class="ci-mobile-overlay"></div>

<aside class="ci-mobile-menu">

  <button class="ci-mobile-close" aria-label="Close menu">
    <span></span>
    <span></span>
  </button>

  <!-- Logo -->
  <div class="ci-mobile-top">
    <img src="https://new-ci-build.magicsandbox.co.uk/wp-content/uploads/2026/03/logo-c.png" alt="<?php bloginfo('name'); ?>">
  </div>

  <!-- Navigation -->
  <nav class="ci-mobile-nav">
    <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'ci-mobile-menu-list'
    ]);
    ?>
  </nav>

  <!-- Bottom Content -->
  <div class="ci-mobile-bottom">
    <p>Join the Cigar Inspector community.</p>

	<div class="ci-mobile-social">
	  <a href="https://twitter.com/CigarInspector"
		 aria-label="Follow us on Twitter"
		 target="_blank"
		 rel="nofollow noopener noreferrer">
		<i class="fa-brands fa-twitter" aria-hidden="true"></i>
	  </a>

	  <a href="https://www.facebook.com/CigarInspector"
		 aria-label="Follow us on Facebook"
		 target="_blank"
		 rel="nofollow noopener noreferrer">
		<i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
	  </a>

	  <a href="https://www.instagram.com/cigarinspector/"
		 aria-label="Follow us on Instagram"
		 target="_blank"
		 rel="nofollow noopener noreferrer">
		<i class="fa-brands fa-instagram" aria-hidden="true"></i>
	  </a>

	  <a href="https://www.youtube.com/channel/UCViOA5Stw8JF8_xJHJCPlEA"
		 aria-label="Subscribe to our YouTube channel"
		 target="_blank"
		 rel="nofollow noopener noreferrer">
		<i class="fa-brands fa-youtube" aria-hidden="true"></i>
	  </a>
	</div>

  </div>

</aside>

<div class="ci-feature-bar">
  <div class="wrap ci-feature-grid">

    <div class="feature-item">
      <i class="fa-regular fa-newspaper"></i>
      <span>Read Cigar News & Editorial</span>
    </div>

    <div class="feature-item">
      <i class="fa-solid fa-map-pin"></i>
      <span>Rate & Review Smoking Spots</span>
    </div>

    <div class="feature-item">
      <i class="fa-solid fa-book-open"></i>
      <span>Discover Expert Cigar Reviews</span>
    </div>

    <div class="feature-item">
      <i class="fa-solid fa-building-columns"></i>
      <span>Browse Our Cigar Library</span>
    </div>

  </div>
</div>
	
<main class="site-main">