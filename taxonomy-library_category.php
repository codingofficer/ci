<?php
get_header();

$paged = max(1, get_query_var('paged'));
$current_term = get_queried_object();
$current_cat = $current_term->slug ?? '';
?>

<div class="container library-archive">

  <!-- HEADER -->
  <div class="library-header">
    <h1><?php single_term_title(); ?></h1>

    <?php if (term_description()) : ?>
      <p><?php echo term_description(); ?></p>
    <?php endif; ?>
  </div>

  <!-- CATEGORY FILTER -->
  <div class="library-filters">

    <?php
    $terms = get_terms([
      'taxonomy'   => 'library_category',
      'hide_empty' => true,
    ]);

    // First 5 = primary
    $primary_terms = array_slice($terms, 0, 5);
    ?>

    <!-- Primary Category Pills -->
    <div class="library-primary-cats">

      <!-- ALL -->
		<a href="<?php echo get_post_type_archive_link('cigar_library'); ?>"
		   class="<?php echo !is_tax('library_category') ? 'active' : ''; ?>">
		  All
		</a>

      <?php foreach ($primary_terms as $term) : ?>
        <a href="<?php echo esc_url(get_term_link($term)); ?>"
           class="<?php echo ($current_cat === $term->slug) ? 'active' : ''; ?>">
          <?php echo esc_html($term->name); ?>
        </a>
      <?php endforeach; ?>

    </div>

    <!-- More Categories Dropdown -->
    <div class="library-more-cats">
      <select onchange="if (this.value) window.location.href=this.value;">
        <option value="">More Categories</option>

        <?php foreach ($terms as $term) : ?>

          <?php if (!in_array($term, $primary_terms, true)) : ?>

            <option value="<?php echo esc_url(get_term_link($term)); ?>"
              <?php selected($current_cat, $term->slug); ?>>
              <?php echo esc_html($term->name); ?>
            </option>

          <?php endif; ?>

        <?php endforeach; ?>

      </select>
    </div>

  </div>

  <?php
  // USE DEFAULT WORDPRESS QUERY (CRITICAL)
  global $wp_query;
  $query = $wp_query;
  ?>

  <?php if ($query->have_posts()) : ?>

    <?php $count = 0; ?>

    <?php while ($query->have_posts()) : $query->the_post(); ?>

      <?php if ($count === 0) : ?>

        <!-- FEATURED -->
        <article class="library-featured">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('ci-hero'); ?>
          <?php endif; ?>

          <div class="featured-overlay">
            <h2>
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </h2>
          </div>
        </article>

        <!-- OPEN GRID -->
        <div class="library-grid">

      <?php else : ?>

        <!-- GRID ITEMS -->
        <article class="library-card">

          <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('ci-tile'); ?>
            </a>
          <?php endif; ?>

          <div class="library-content">
            <h3>
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </h3>

            <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>

            <span class="library-date">
              <?php echo get_the_date(); ?>
            </span>
          </div>

        </article>

      <?php endif; ?>

      <?php $count++; ?>

    <?php endwhile; ?>

    </div> <!-- CLOSE GRID -->

    <!-- PAGINATION -->
    <div class="pagination">
      <?php
      echo paginate_links([
        'total'   => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
      ]);
      ?>
    </div>

    <?php wp_reset_postdata(); ?>

  <?php else : ?>
    <p>No articles found.</p>
  <?php endif; ?>

</div>

<?php get_footer(); ?>