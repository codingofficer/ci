<?php
get_header();

while (have_posts()) : the_post();

$reading_time = ceil(str_word_count(strip_tags(get_the_content())) / 200);
?>

<article class="library-article news-article">

  <?php if (has_post_thumbnail()) : ?>
    <div class="library-hero">
      <?php the_post_thumbnail('full'); ?>

      <div class="hero-category">
        <?php
        $terms = get_the_terms(get_the_ID(), 'news_categories');

        if ($terms && !is_wp_error($terms)) :
          $primary_term = $terms[0];
        ?>
          <a href="<?php echo esc_url(get_term_link($primary_term)); ?>">
            <?php echo esc_html($primary_term->name); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (function_exists('yoast_breadcrumb')) : ?>
    <div class="library-breadcrumbs">
      <?php yoast_breadcrumb('<p>', '</p>'); ?>
    </div>
  <?php endif; ?>

  <header class="library-header-wrap">

    <h1 class="library-title"><?php the_title(); ?></h1>

    <div class="library-meta">
      <span><?php echo get_the_date(); ?></span>
      <span class="dot">•</span>
      <span><?php the_author(); ?></span>
      <span class="dot">•</span>
      <span><?php echo $reading_time; ?> min read</span>
    </div>

    <?php
    $url   = urlencode(get_permalink());
    $title = urlencode(get_the_title());
    ?>

    <div class="library-share-mobile">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener">
        <i class="fab fa-x-twitter"></i>
      </a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" rel="noopener">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>

  </header>

  <div class="library-body-wrap">

    <aside class="library-share">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener">
        <i class="fab fa-facebook-f"></i>
      </a>

      <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener">
        <i class="fab fa-x-twitter"></i>
      </a>

      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" rel="noopener">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </aside>

    <div class="library-content">
      <?php the_content(); ?>
    </div>

  </div>

	<?php
	$author_id    = get_the_author_meta('ID');
	$author_name  = get_the_author();
	$author_image = get_user_meta($author_id, 'author_avatar_id', true);
	$author_bio   = get_user_meta($author_id, 'author_bio_short', true);
	?>

	<section class="library-author">
	  <div class="author-inner">

		<div class="author-avatar">
		  <?php if ($author_image) : ?>
			<?php echo wp_get_attachment_image($author_image, 'thumbnail'); ?>
		  <?php else : ?>
			<?php echo get_avatar($author_id, 100); ?>
		  <?php endif; ?>
		</div>

		<div class="author-info">
		  <span class="author-label">About the author</span>
		  <h4 class="author-name"><?php echo esc_html($author_name); ?></h4>

		  <?php if (!empty($author_bio)) : ?>
			<p class="author-bio"><?php echo esc_html($author_bio); ?></p>
		  <?php endif; ?>
		</div>

	  </div>
	</section>

  <section class="library-related">
    <h3>Related Articles</h3>

    <?php
    $related = new WP_Query([
      'post_type'      => 'cigar_news',
      'posts_per_page' => 3,
      'post__not_in'   => [get_the_ID()],
    ]);

    if ($related->have_posts()) :
      echo '<div class="related-grid">';
      while ($related->have_posts()) : $related->the_post();
    ?>
      <article class="related-item">
        <a href="<?php the_permalink(); ?>">

          <div class="related-thumb">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('ci-tile'); ?>
            <?php endif; ?>
          </div>

          <div class="related-text">
            <h4><?php the_title(); ?></h4>
            <div class="related-date">
              <i class="far fa-clock"></i>
              <span><?php echo get_the_date(); ?></span>
            </div>
          </div>

        </a>
      </article>
    <?php
      endwhile;
      echo '</div>';
      wp_reset_postdata();
    endif;
    ?>
  </section>

</article>

<?php endwhile; ?>

<?php get_footer(); ?>