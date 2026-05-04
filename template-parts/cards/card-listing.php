<article <?php post_class('card card--listing'); ?>>

	<?php
	$gallery  = function_exists('get_field') ? get_field('gallery') : null;
	$thumb_id = get_post_thumbnail_id();
	$image_id = null;
	$image_url = null;

	if ($thumb_id) {
		$image_id = $thumb_id;

	} elseif (!empty($gallery) && is_array($gallery)) {
		$image_id = is_array($gallery[0]) ? $gallery[0]['ID'] : $gallery[0];

	} else {
		$content = get_the_content();

		if (preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches)) {
			$image_url = $matches[1];
		}
	}
	?>

  <!-- Image -->
	<a class="card__media" href="<?php the_permalink(); ?>">

	<?php if ($image_id): ?>

	  <?php echo wp_get_attachment_image(
		$image_id,
		'large',
		false,
		[
		  'loading' => 'lazy',
		  'alt' => esc_attr(get_the_title())
		]
	  ); ?>

	<?php else: ?>

	  <?php
	  global $post;
	  $image_url = null;

	  if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $post->post_content, $matches)) {
		  $image_url = $matches[1];
	  }
	  ?>

	  <?php if ($image_url): ?>
		<img src="<?php echo esc_url($image_url); ?>"
			 alt="<?php echo esc_attr(get_the_title()); ?>"
			 loading="lazy">
	  <?php else: ?>
		<img src="<?php echo get_template_directory_uri(); ?>/assets/img/fallback-merchant.jpg"
			 alt="Cigar merchant"
			 loading="lazy">
	  <?php endif; ?>

	<?php endif; ?>

	</a>

  <!-- Content -->
  <div class="card__body">
    <a class="card__title" href="<?php the_permalink(); ?>">
      <?php the_title(); ?>
    </a>

    <?php
    // Rating (if function exists)
    if (function_exists('ci_get_rating')) {
        [$avg, $cnt] = ci_get_rating(get_the_ID());
    } else {
        $avg = 0;
        $cnt = 0;
    }
    ?>
    <div class="card__rating">
      <?php echo $avg ? sprintf('%.1f★ (%d)', $avg, $cnt) : 'Unrated'; ?>
    </div>

    <p class="card__meta">
      <?php
      $city    = function_exists('get_field') ? get_field('address_city') : '';
      $country = function_exists('get_field') ? get_field('address_country') : '';

      if ($city || $country) {
          echo esc_html(trim($city . ', ' . $country, ', '));
      } else {
          echo 'Location unavailable';
      }
      ?>
    </p>
  </div>

</article>