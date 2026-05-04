<?php
/*
Template Name: Account Page
*/
get_header();

if (is_user_logged_in()) :

$current_user = wp_get_current_user();
?>

<div class="account-wrapper">
  <div class="account-card account-dashboard">

    <div class="account-tabs">
      <button class="account-tab active" data-tab="overview">Overview</button>
      <button class="account-tab" data-tab="profile">Profile</button>
      <button class="account-tab" data-tab="password">Password</button>
      <a class="account-tab logout-tab" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
    </div>

    <!-- OVERVIEW -->
	<div class="account-form active" id="overview">
	  <h2>Welcome back</h2>
	  <p>You are logged in as <strong><?php echo esc_html($current_user->user_email); ?></strong></p>
	  <p>Member since <?php echo date_i18n(get_option('date_format'), strtotime($current_user->user_registered)); ?></p>

	  <?php if (current_user_can('manage_options')) : ?>
		<div class="account-admin-cta">
		  <a href="<?php echo admin_url(); ?>" class="account-admin-btn">
			Go to Admin Dashboard
		  </a>
		</div>
	  <?php endif; ?>
	</div>

	<!-- PROFILE -->
	<div class="account-form" id="profile">
	  <h2>Profile Details</h2>

	  <?php
	  $author_bio   = get_user_meta($current_user->ID, 'author_bio_short', true);
	  $author_role  = get_user_meta($current_user->ID, 'author_role', true);
	  $avatar_id    = get_user_meta($current_user->ID, 'author_avatar_id', true);
	  ?>

	  <form method="post" enctype="multipart/form-data">

		<!-- Avatar Preview -->
		<div class="account-avatar-preview">
		  <?php if ($avatar_id): ?>
			<?php echo wp_get_attachment_image($avatar_id, 'thumbnail'); ?>
		  <?php else: ?>
			<?php echo get_avatar($current_user->ID, 100); ?>
		  <?php endif; ?>
		</div>

		<!-- Upload New Avatar -->
		<label>Profile Picture</label>
		<input type="file" name="author_avatar" accept="image/*">

		<input type="text" name="first_name"
		  value="<?php echo esc_attr($current_user->first_name); ?>"
		  placeholder="First Name">

		<input type="text" name="last_name"
		  value="<?php echo esc_attr($current_user->last_name); ?>"
		  placeholder="Last Name">

		<input type="email" name="user_email"
		  value="<?php echo esc_attr($current_user->user_email); ?>"
		  required>

		<input type="text" name="author_role"
		  value="<?php echo esc_attr($author_role); ?>"
		  placeholder="Your Role (e.g. Editor, Reviewer)">

		<textarea name="author_bio_short" rows="4"
		  class="account-textarea"
		  placeholder="Short bio"><?php echo esc_textarea($author_bio); ?></textarea>

		<button type="submit" name="save_profile">
		  Save Changes
		</button>

	  </form>

	  <?php
	  if (isset($_POST['save_profile'])) {

		if (
		  !isset($_POST['ci_profile_nonce']) ||
		  !wp_verify_nonce($_POST['ci_profile_nonce'], 'ci_profile_update')
		) {
		  return;
		}
		  
		// Update basic user fields
		wp_update_user([
		  'ID' => $current_user->ID,
		  'first_name' => sanitize_text_field($_POST['first_name']),
		  'last_name'  => sanitize_text_field($_POST['last_name']),
		  'user_email' => sanitize_email($_POST['user_email'])
		]);

		// Update custom fields
		update_user_meta($current_user->ID, 'author_role',
		  sanitize_text_field($_POST['author_role'])
		);

		update_user_meta($current_user->ID, 'author_bio_short',
		  sanitize_textarea_field($_POST['author_bio_short'])
		);

		// Handle Avatar Upload
		if (!empty($_FILES['author_avatar']['name'])) {

		  require_once ABSPATH . 'wp-admin/includes/file.php';
		  require_once ABSPATH . 'wp-admin/includes/media.php';
		  require_once ABSPATH . 'wp-admin/includes/image.php';

		  $attachment_id = media_handle_upload('author_avatar', 0);

		  if (!is_wp_error($attachment_id)) {
			update_user_meta($current_user->ID, 'author_avatar_id', $attachment_id);
		  }
		}

		echo '<p class="success">Profile updated successfully.</p>';

		// Refresh user object
		wp_set_current_user($current_user->ID);
	  }
	  ?>
	</div>

    <!-- PASSWORD -->
    <div class="account-form" id="password">
      <h2>Change Password</h2>

      <form method="post">

        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit" name="change_password">
          Update Password
        </button>

      </form>

      <?php
      if (isset($_POST['change_password'])) {

        if ($_POST['new_password'] === $_POST['confirm_password']) {

          wp_set_password($_POST['new_password'], $current_user->ID);

          echo '<p class="success">Password updated. Please log in again.</p>';
          echo '<meta http-equiv="refresh" content="2;url='.wp_logout_url().'">';

        } else {
          echo '<p class="error">Passwords do not match.</p>';
        }
      }
      ?>
    </div>

  </div>
</div>

<?php else : ?>

<!-- EXISTING LOGIN / REGISTER (UNCHANGED) -->

<div class="account-wrapper">

  <div class="account-card">

    <div class="account-tabs">
      <button class="account-tab active" data-tab="login">Sign In</button>
      <button class="account-tab" data-tab="register">Create Account</button>
    </div>

    <!-- LOGIN FORM -->
    <div class="account-form active" id="login">
      <h2>Sign in</h2>
      <p>Already have an account? Sign in and continue.</p>

      <?php wp_login_form([
        'redirect' => home_url('/account/'),
      ]); ?>

      <div class="account-extra-links">
        <a href="<?php echo wp_lostpassword_url(); ?>">
          Forgot your password?
        </a>
      </div>
    </div>

    <!-- REGISTER FORM -->
    <div class="account-form" id="register">

      <h2>Create your account</h2>
      <p>Just an email and password, then you'll continue.</p>

      <form method="post">
        <input type="email" name="reg_email" placeholder="Email" required>
        <input type="password" name="reg_password" placeholder="Password" required>
        <button type="submit" name="custom_register">
          Create account & continue
        </button>
      </form>

      <?php
      if (isset($_POST['custom_register'])) {

        $email = sanitize_email($_POST['reg_email']);
        $password = $_POST['reg_password'];

        $user_id = wp_create_user($email, $password, $email);

        if (!is_wp_error($user_id)) {
          wp_set_current_user($user_id);
          wp_set_auth_cookie($user_id);
          echo '<p class="success">Account created successfully.</p>';
        } else {
          echo '<p class="error">' . $user_id->get_error_message() . '</p>';
        }
      }
      ?>

    </div>

  </div>

</div>

<?php endif; ?>

<script>
document.querySelectorAll('.account-tab').forEach(tab => {
  tab.addEventListener('click', function() {

    if(this.classList.contains('logout-tab')) return;

    document.querySelectorAll('.account-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.account-form').forEach(f => f.classList.remove('active'));

    this.classList.add('active');
    document.getElementById(this.dataset.tab).classList.add('active');
  });
});
</script>

<?php get_footer(); ?>