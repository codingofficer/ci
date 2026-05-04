<?php
/*
Template Name: Articles
*/
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$query = new WP_Query([
  'post_type'      => ['cigar_news', 'cigar_library'],
  'post_status'    => 'publish',
  'posts_per_page' => 10,
  'paged'          => $paged,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'meta_query'     => [
    [
      'key'     => '_thumbnail_id',
      'compare' => 'EXISTS'
    ]
  ]
]);
?>

<main class="articles-page">

  <div class="articles-container">

    <h1 class="articles-title">Latest Articles</h1>

    <?php if ($query->have_posts()) : ?>
      <?php while ($query->have_posts()) : $query->the_post(); ?>

        <?php if (!has_post_thumbnail()) continue; ?>

        <article class="article-card">

          <a href="<?php the_permalink(); ?>" class="article-link">

            <div class="article-image">
              <?php the_post_thumbnail('large'); ?>
            </div>

            <div class="article-content">

              <span class="article-category">
                <?php
                $terms = get_the_terms(get_the_ID(), 'category');
                if ($terms && !is_wp_error($terms)) {
                  echo esc_html($terms[0]->name);
                }
                ?>
              </span>

              <h2 class="article-title">
                <?php the_title(); ?>
              </h2>

              <p class="article-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 26); ?>
              </p>

              <span class="article-date">
                <?php echo esc_html(get_the_date()); ?>
              </span>

            </div>

          </a>

        </article>

      <?php endwhile; ?>

		<div class="articles-pagination">
		  <?php
		  echo paginate_links([
			'base'      => home_url('/articles/page/%#%/'),
			'format'    => '',
			'current'   => max(1, get_query_var('paged')),
			'total'     => $query->max_num_pages,
			'prev_text' => '« Previous',
			'next_text' => 'Next »',
		  ]);
		  ?>
		</div>

      <?php wp_reset_postdata(); ?>

    <?php endif; ?>

  </div>

</main>

<?php get_footer(); ?>