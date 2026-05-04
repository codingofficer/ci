<?php
/**
 * Sidebar (Homepage)
 * Blocks: sidebar_top ad, Upcoming Events, Newsletter, sidebar_bottom ad
 */

// Top ad slot (optional)
echo do_shortcode('[ci_ad position="sidebar_top"]');

// Upcoming events
$events = function_exists('ci_query_upcoming_events') ? ci_query_upcoming_events(5) : (new WP_Query([
  'post_type' => 'event',
  'post_status' => 'publish',
  'posts_per_page' => 5,
  'meta_key' => 'event_date',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => [[
    'key' => 'event_date',
    'value' => current_time('Y-m-d'),
    'type' => 'DATE',
    'compare' => '>=',
  ]],
  'no_found_rows' => true,
]));
?>
<section class="widget">
  <h3><?php esc_html_e('Upcoming Events','cigar-inspector'); ?></h3>
  <?php if ($events->have_posts()): ?>
    <ul class="mini-list">
      <?php while($events->have_posts()): $events->the_post();
        $date = function_exists('get_field') ? get_field('event_date') : '';
        ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          <?php if ($date): ?>
            <small class="muted"><?php echo esc_html( date_i18n(get_option('date_format'), strtotime($date)) ); ?></small>
          <?php endif; ?>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
  <?php else: ?>
    <p class="muted"><?php esc_html_e('No upcoming events.','cigar-inspector'); ?></p>
  <?php endif; ?>
</section>

<section class="widget">
  <h3><?php esc_html_e('Signup to our Mailing List','cigar-inspector'); ?></h3>
  <p class="muted"><?php esc_html_e('To receive all the latest news, articles and reviews.','cigar-inspector'); ?></p>
  <?php echo do_shortcode('[newsletter_form id="ci_home_sidebar"]'); ?>
</section>

<div class="widget">
  <?php echo do_shortcode('[ci_ad position="sidebar_bottom"]'); ?>
</div>
