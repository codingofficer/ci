<?php
/**
 * Front Page — Cigar Inspector
 * Pixel-aligned to Homepage Layout Template
 */
get_header();

/* ===============================
   Query Fallbacks (unchanged)
================================= */
if (!function_exists('ci_query_featured_news')) {
  function ci_query_featured_news($count = 5) {
    $sticky = array_filter(array_map('intval', (array) get_option('sticky_posts')));
    $args = [
      'post_type'           => 'cigar_news',
      'post_status'         => 'publish',
      'posts_per_page'      => intval($count),
      'ignore_sticky_posts' => empty($sticky),
    ];
    if (!empty($sticky)) {
      $args['post__in'] = $sticky;
      $args['orderby']  = 'post__in';
    }
    return new WP_Query($args);
  }
}

function ci_query_latest_articles($per_page = 10) {

  $paged = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
  $paged = $paged ? $paged : 1;

  return new WP_Query([
    'post_type'      => ['cigar_news', 'cigar_library'],
    'post_status'    => 'publish',
    'posts_per_page' => intval($per_page),
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
}

if (!function_exists('ci_query_upcoming_events')) {
  function ci_query_upcoming_events($limit = 5) {
    $today = current_time('Y-m-d');
    return new WP_Query([
      'post_type'      => 'event',
      'post_status'    => 'publish',
      'posts_per_page' => intval($limit),
      'meta_key'       => 'event_date',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_query'     => [[
        'key'     => 'event_date',
        'value'   => $today,
        'type'    => 'DATE',
        'compare' => '>=',
      ]],
      'no_found_rows'  => true,
    ]);
  }
}
?>

<!-- ===============================
     HERO GRID (Manual Override + Fallback)
================================= -->
<?php
$use_manual = function_exists('get_field')
  ? get_field('use_manual_hero', 'option')
  : false;

$hero_posts = array_fill(0, 5, null);

/* =========================
   MANUAL HERO (ONLY IF HAS IMAGE)
========================= */

if ($use_manual) {

  $slots = [
    'large'        => get_field('large_banner', 'option'),
    'top_left'     => get_field('top_left_banner', 'option'),
    'top_right'    => get_field('top_right_banner', 'option'),
    'bottom_left'  => get_field('bottom_left_banner', 'option'),
    'bottom_right' => get_field('bottom_right_banner', 'option'),
  ];

  $slot_values = array_values($slots);

  foreach ($slot_values as $i => $post_obj) {
    if (
      $post_obj instanceof WP_Post &&
      has_post_thumbnail($post_obj->ID)
    ) {
      $hero_posts[$i] = $post_obj;
    }
  }
}

/* =========================
   FALLBACK (ONLY POSTS WITH IMAGE)
========================= */

if (in_array(null, $hero_posts, true)) {

  $exclude_ids = array_filter(wp_list_pluck($hero_posts, 'ID'));

	$fallback = new WP_Query([
	  'post_type'      => 'cigar_library',
	  'post_status'    => 'publish',
	  'posts_per_page' => 5,
	  'post__not_in'   => $exclude_ids,
	  'orderby'        => 'date',
	  'order'          => 'DESC',
	  'meta_query'     => [
		[
		  'key'     => '_thumbnail_id',
		  'compare' => 'EXISTS'
		]
	  ]
	]);

  $fallback_posts = [];

  while ($fallback->have_posts()) {
    $fallback->the_post();
    $fallback_posts[] = get_post(get_the_ID());
  }

  wp_reset_postdata();

  foreach ($hero_posts as $i => $post_obj) {
    if ($post_obj === null && !empty($fallback_posts)) {
      $hero_posts[$i] = array_shift($fallback_posts);
    }
  }
}
?>

<?php if (!empty($hero_posts)) : ?>
<section class="container featured-grid">

  <?php foreach ($hero_posts as $index => $post_obj) :
    setup_postdata($post_obj); ?>

    <?php if ($index === 0) : ?>
      <article class="featured-large card">
        <?php if (has_post_thumbnail($post_obj->ID)) :
          echo get_the_post_thumbnail($post_obj->ID, 'large');
        endif; ?>
        <div class="overlay">
          <span class="category">
            <?php echo esc_html(get_the_term_list($post_obj->ID, 'category', '', ', ')); ?>
          </span>
          <h2>
            <a href="<?php echo esc_url(get_permalink($post_obj->ID)); ?>">
              <?php echo esc_html(get_the_title($post_obj->ID)); ?>
            </a>
          </h2>
        </div>
      </article>
    <?php else : ?>
      <article class="featured-small card">
        <?php if (has_post_thumbnail($post_obj->ID)) :
          echo get_the_post_thumbnail($post_obj->ID, 'medium');
        endif; ?>
        <div class="overlay">
          <h3>
            <a href="<?php echo esc_url(get_permalink($post_obj->ID)); ?>">
              <?php echo esc_html(get_the_title($post_obj->ID)); ?>
            </a>
          </h3>
        </div>
      </article>
    <?php endif; ?>

  <?php endforeach; wp_reset_postdata(); ?>

</section>
<?php endif; ?>

<!-- Leaderboard Ad -->
<section class="container ad-banner">
  <?php echo do_shortcode('[ci_ad position="leaderboard"]'); ?>
</section>

<!-- ===============================
     MAIN CONTENT GRID
================================= -->
<main class="container main-content">

  <!-- LEFT COLUMN -->
	<section class="latest-news">
	  <h2><?php esc_html_e('Latest Articles','cigar-inspector'); ?></h2>

	<?php $news = ci_query_latest_articles(10); ?>

	<?php if ($news->have_posts()) : ?>
	  <?php while ($news->have_posts()) : $news->the_post(); ?>

		<?php if (!has_post_thumbnail()) continue; ?>

		<article class="news-item">

		  <?php the_post_thumbnail('medium'); ?>

		  <div class="news-content">

			<span class="category">
			  <?php
			  $terms = get_the_terms(get_the_ID(), 'category');
			  if ($terms && !is_wp_error($terms)) {
				echo esc_html($terms[0]->name);
			  }
			  ?>
			</span>

			<h3>
			  <a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			  </a>
			</h3>

			<p><?php echo wp_trim_words(get_the_excerpt(), 22); ?></p>

			<span class="date">
			  <?php echo esc_html(get_the_date()); ?>
			</span>

		  </div>

		</article>

	  <?php endwhile; ?>

	  <div class="pagination">
		<?php
		echo paginate_links([
		  'base'    => home_url('/articles/page/%#%/'),
		  'format'  => '',
		  'current' => max(1, get_query_var('paged') ?: get_query_var('page')),
		  'total'   => $news->max_num_pages,
		]);
		?>
	  </div>

	  <?php wp_reset_postdata(); ?>

	<?php endif; ?>
	  
		<section class="ci-home-about">
		  <div class="wrap">

			<h2>About Cigar Inspector</h2>

			<p>
			  Cigar Inspector offers thoughtful coverage of cigars and spirits. From expert articles and honest reviews to helpful podcasts and curated event insights, we’re here to help enthusiasts explore with confidence.
			</p>

			<h3>Stay Current with News and Reviews</h3>
			<p>
			  We provide reliable updates on new cigar releases, brand spotlights, industry trends, and regulations. Whether you’re new to cigars or a seasoned smoker, our content is designed to keep you informed and engaged.
			</p>

			<h3>Podcasts and Conversations</h3>
			<p>
			  Enjoy on-the-go learning through interviews with industry experts, discussions on pairing ideas, and deep dives into cigar craftsmanship.
			</p>

			<h3>Cigar Events That Bring Enthusiasts Together</h3>
			<p>
			  Explore events that celebrate cigars and spirits, from international expos to local tastings.
			</p>

			<h3>Why Readers Trust Cigar Inspector</h3>
			<p>
			  With years of experience and a global audience, we focus on authenticity, education, and accessibility.
			</p>

		  </div>
		</section>

  </section>

  <!-- RIGHT SIDEBAR -->
  <aside class="sidebar">

    <!-- Events -->
    <section class="widget">
      <h3><?php esc_html_e('Upcoming Events','cigar-inspector'); ?></h3>
      <?php $events = ci_query_upcoming_events(5); ?>
      <?php if ($events->have_posts()) : ?>
        <ul class="mini-list">
          <?php while ($events->have_posts()) : $events->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <?php if (function_exists('get_field') && get_field('event_date')) : ?>
                <small class="muted">
                  <?php echo esc_html(date_i18n(get_option('date_format'), strtotime(get_field('event_date')))); ?>
                </small>
              <?php endif; ?>
            </li>
          <?php endwhile; ?>
        </ul>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="muted"><?php esc_html_e('No upcoming events.','cigar-inspector'); ?></p>
      <?php endif; ?>
    </section>

    <!-- Newsletter -->
    <section class="widget" style="display: none;">
      <h3><?php esc_html_e('Signup to our Mailing List','cigar-inspector'); ?></h3>
      <p class="muted"><?php esc_html_e('To receive all the latest news, articles and reviews.','cigar-inspector'); ?></p>
      <?php echo do_shortcode('[newsletter_form id="ci_home_sidebar"]'); ?>
    </section>

    <!-- Sidebar Ad -->
    <div class="widget" style="display: none;">
      <?php echo do_shortcode('[ci_ad position="sidebar_bottom"]'); ?>
    </div>

  </aside>

</main>

<?php get_footer(); ?>