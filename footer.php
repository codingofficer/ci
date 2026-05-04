<footer class="ci-footer">

  <div class="wrap">

    <!-- TOP ROW -->
    <div class="ci-footer-top">

      <div class="ci-footer-logo">
        <a href="<?php echo home_url('/'); ?>">
          <img src="https://new-ci-build.magicsandbox.co.uk/wp-content/uploads/2026/03/logo-c-white.png"
               alt="<?php bloginfo('name'); ?>">
        </a>
      </div>

		<div class="ci-footer-social">
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

      <a href="<?php echo home_url('/newsletter/'); ?>" class="ci-newsletter-btn">
        Join Our Newsletter
        <i class="fa-regular fa-envelope"></i>
      </a>

    </div>

    <hr class="ci-footer-divider">

    <!-- MAIN GRID -->
    <div class="ci-footer-grid">

      <!-- COMPANY -->
      <div class="ci-footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="<?php echo home_url('/about-us/'); ?>">About Us</a></li>
          <li><a href="<?php echo home_url('/faqs/'); ?>">FAQs</a></li>
          <li><a href="<?php echo home_url('/contact-us/'); ?>">Contact Us</a></li>
        </ul>
      </div>

      <!-- LEGAL -->
      <div class="ci-footer-col">
        <h4>Legal</h4>
        <ul>
          <li><a href="<?php echo home_url('/terms-and-conditions/'); ?>">Terms & Conditions</a></li>
          <li><a href="<?php echo home_url('/privacy-policy/'); ?>">Privacy Policy</a></li>
          <li><a href="<?php echo home_url('/terms-of-use/'); ?>">Terms of Use</a></li>
        </ul>
      </div>

      <!-- ACCOUNT -->
      <div class="ci-footer-col">
        <h4>Account</h4>
        <ul>
          <li>
            <a href="<?php echo is_user_logged_in() ? home_url('/account/') : wp_login_url(); ?>">
              My Account
            </a>
          </li>

          <li>
            <a href="<?php echo home_url('/add-directory-listing/'); ?>">
              Add a Listing
            </a>
          </li>

          <li>
            <a href="<?php echo home_url('/account/'); ?>">
              Join Our Community
            </a>
          </li>

          <?php if (is_user_logged_in()) : ?>
            <li>
              <a href="<?php echo wp_logout_url(home_url()); ?>">
                Logout
              </a>
            </li>
          <?php endif; ?>

        </ul>
      </div>

      <!-- FOLLOW -->
		<div class="ci-footer-col">
		  <h4>Follow Us</h4>
		  <ul>
			<li>
			  <a href="https://www.facebook.com/CigarInspector"
				 aria-label="Follow Cigar Inspector on Facebook"
				 target="_blank"
				 rel="nofollow noopener noreferrer">
				Follow us on Facebook
			  </a>
			</li>
			<li>
			  <a href="https://www.instagram.com/cigarinspector/"
				 aria-label="Follow Cigar Inspector on Instagram"
				 target="_blank"
				 rel="nofollow noopener noreferrer">
				Follow us on Instagram
			  </a>
			</li>
			<li>
			  <a href="<?php echo home_url('/newsletter/'); ?>"
				 aria-label="Subscribe to the Cigar Inspector newsletter">
				Subscribe to our Newsletter
			  </a>
			</li>
		  </ul>
		</div>

    </div>

    <div class="ci-footer-bottom">
      © <?php echo date('Y'); ?> Cigar Inspector. All rights reserved.
    </div>

  </div>

</footer>

<?php wp_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

  const toggle = document.querySelector('.ci-toggle');
  const menu = document.querySelector('.ci-mobile-menu');
  const overlay = document.querySelector('.ci-mobile-overlay');
  const closeBtn = document.querySelector('.ci-mobile-close');

  function openMenu() {
    menu.classList.add('active');
    overlay.classList.add('active');
  }

  function closeMenu() {
    menu.classList.remove('active');
    overlay.classList.remove('active');
  }

  toggle.addEventListener('click', openMenu);
  closeBtn.addEventListener('click', closeMenu);
  overlay.addEventListener('click', closeMenu);

});
</script>

</body>
</html>