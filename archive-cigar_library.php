<?php
get_header();

$paged = max(1, get_query_var('paged'));

$current_cat = isset($_GET['library_category']) 
  ? sanitize_text_field($_GET['library_category']) 
  : '';

$tax_query = [];

if (!empty($current_cat)) {
  $tax_query = [
    [
      'taxonomy' => 'library_category',
      'field'    => 'slug',
      'terms'    => $current_cat,
    ]
  ];
}
?>

<div class="container library-archive">

  <div class="library-header">
    <h1>Cigar Library</h1>
    <p>Explore in-depth guides, interviews and evergreen cigar knowledge.</p>
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

		<a href="<?php echo get_post_type_archive_link('cigar_library'); ?>"
		   class="<?php echo empty($current_cat) ? 'active' : ''; ?>">
		  All
		</a>

		<?php foreach ($primary_terms as $term) : ?>
		  <a href="?library_category=<?php echo esc_attr($term->slug); ?>"
			 class="<?php echo ($current_cat === $term->slug) ? 'active' : ''; ?>">
			<?php echo esc_html($term->name); ?>
		  </a>
		<?php endforeach; ?>

	  </div>

	  <!-- More Categories Dropdown -->
	  <div class="library-more-cats">
		<form method="get">
		  <select name="library_category" onchange="this.form.submit()">
			<option value="">More Categories</option>

			<?php foreach ($terms as $term) : ?>

			  <?php if (!in_array($term, $primary_terms, true)) : ?>

				<option value="<?php echo esc_attr($term->slug); ?>"
				  <?php selected($current_cat, $term->slug); ?>>
				  <?php echo esc_html($term->name); ?>
				</option>

			  <?php endif; ?>

			<?php endforeach; ?>

		  </select>
		</form>
	  </div>

	</div>

  <?php
  /**
   * MAIN QUERY
   * We fetch 10 posts.
   * First one becomes featured.
   * Remaining 9 go into grid.
   */
	$args = [
	  'post_type'      => 'cigar_library',
	  'post_status'    => 'publish',
	  'posts_per_page' => 10,
	  'paged'          => $paged,
	];

	if (!empty($tax_query)) {
	  $args['tax_query'] = $tax_query;
	}

	$query = new WP_Query($args);
  ?>

  <?php if ($query->have_posts()) : ?>

    <?php
    $count = 0;
    ?>

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
        'current' => $paged,
      ]);
      ?>
    </div>

    <?php wp_reset_postdata(); ?>

  <?php else : ?>
    <p>No articles found.</p>
  <?php endif; ?>

</div>

<?php get_footer(); ?>