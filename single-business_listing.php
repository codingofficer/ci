<?php
get_header();
the_post();

$listing_id = get_the_ID();

$gallery = get_field('gallery');

$street = trim((string) get_field('address_street'));
$city   = trim((string) get_field('address_city'));
$addr   = trim($street . ($street && $city ? ', ' : '') . $city);

$lat = get_field('lat');
$lng = get_field('lng');

/**
 * Convert ACF time values:
 * - If numeric (e.g. 37800) treat as seconds since midnight -> H:i
 * - Else return as-is (already formatted like 09:00)
 */
function ci_format_time_value($value): string
{
    if ($value === null || $value === '') {
        return '';
    }

    if (is_numeric($value)) {
        $seconds = (int) $value;
        if ($seconds < 0) $seconds = 0;
        $seconds = $seconds % 86400;

        $hours = (int) floor($seconds / 3600);
        $mins  = (int) floor(($seconds % 3600) / 60);

        return sprintf('%02d:%02d', $hours, $mins);
    }

    return (string) $value;
}

/**
 * Reviews stats (query ALL ids only for stats)
 */
$ratings_breakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$rating_sum = 0;
$rating_count = 0;

$stats_q = new WP_Query([
    'post_type'      => 'review',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        [
            'key'     => 'target_id',
            'value'   => $listing_id,
            'compare' => '=',
        ]
    ],
]);

if (!empty($stats_q->posts)) {
    foreach ($stats_q->posts as $review_post_id) {
        $r = (int) get_field('rating', $review_post_id);
        if ($r >= 1 && $r <= 5) {
            $ratings_breakdown[$r]++;
            $rating_sum += $r;
            $rating_count++;
        }
    }
}
wp_reset_postdata();

$avg = $rating_count ? round($rating_sum / $rating_count, 1) : 0;

/**
 * Initial reviews (page 1)
 */
$per_page = 10;

$reviews_q = new WP_Query([
    'post_type'      => 'review',
    'posts_per_page' => $per_page,
    'paged'          => 1,
    'meta_query'     => [
        [
            'key'     => 'target_id',
            'value'   => $listing_id,
            'compare' => '=',
        ]
    ],
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$has_more = ($reviews_q->max_num_pages > 1);

$nonce = wp_create_nonce('ci_load_more_reviews');
$ajax_url = admin_url('admin-ajax.php');
?>

<article class="ci-listing">

    <div class="wrap ci-layout">

        <!-- LEFT COLUMN -->
        <div class="ci-left">

            <header class="ci-hero-left">
                <h1 class="ci-title"><?php the_title(); ?></h1>

                <?php if ($rating_count): ?>
                    <div class="ci-rating-summary" aria-label="Average rating">
                        <span class="ci-stars" aria-hidden="true">
                            <?php
                            $rounded = (int) round($avg);
                            echo str_repeat('★', $rounded) . str_repeat('☆', 5 - $rounded);
                            ?>
                        </span>
                        <span class="ci-rating-value">
                            <?php echo esc_html(number_format($avg, 1)); ?>
                            <span class="ci-muted">(<?php echo (int) $rating_count; ?>)</span>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($addr): ?>
                    <p class="ci-address"><?php echo esc_html($addr); ?></p>
                <?php endif; ?>

                <div class="ci-actions">
                    <?php if ($p = get_field('phone')): ?>
                        <a class="ci-btn" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $p)); ?>">Call</a>
                    <?php endif; ?>

                    <?php if ($w = get_field('website')): ?>
                        <a class="ci-btn" target="_blank" rel="noopener" href="<?php echo esc_url($w); ?>">Website</a>
                    <?php endif; ?>

                    <?php if ($lat && $lng): ?>
                        <a class="ci-btn" target="_blank" rel="noopener"
                           href="<?php echo esc_url('https://maps.google.com/?q=' . rawurlencode($lat . ',' . $lng)); ?>">
                            Directions
                        </a>
                    <?php endif; ?>

                    <a class="ci-btn-outline" href="#claim">Claim</a>
                </div>
            </header>

            <main class="ci-main">

				<?php
				$content = trim(get_post_field('post_content', get_the_ID()));
				?>

				<?php if (!empty($content)) : ?>
					<div class="ci-content">
						<?php the_content(); ?>
					</div>
				<?php else : ?>
					<div class="ci-content ci-content--empty">
						<p>This merchant hasn’t added a description yet.</p>
						<small>Are you the owner? Click "Claim" to update this listing.</small>
					</div>
				<?php endif; ?>

                <section class="ci-reviews" id="reviews">
                    <div class="ci-section-head">
                        <h2>Reviews</h2>
                        <?php if ($rating_count): ?>
                            <span class="ci-pill"><?php echo esc_html($avg); ?> / 5 · <?php echo (int) $rating_count; ?> total</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($rating_count): ?>
                        <div class="ci-rating-breakdown" aria-label="Rating breakdown">
                            <?php for ($star = 5; $star >= 1; $star--): ?>
                                <?php
                                $num = (int) $ratings_breakdown[$star];
                                $percent = $rating_count ? ($num / $rating_count) * 100 : 0;
                                ?>
                                <div class="ci-bar-row">
                                    <span class="ci-bar-label"><?php echo (int) $star; ?>★</span>
                                    <div class="ci-bar" role="img" aria-label="<?php echo esc_attr($num . ' reviews with ' . $star . ' stars'); ?>">
                                        <div style="width:<?php echo esc_attr($percent); ?>%"></div>
                                    </div>
                                    <span class="ci-bar-num"><?php echo (int) $num; ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <div id="ci-review-grid" class="ci-review-grid">
                        <?php if ($reviews_q->have_posts()): ?>
                            <?php while ($reviews_q->have_posts()): $reviews_q->the_post(); ?>
                                <?php $r = (int) get_field('rating'); ?>
                                <div class="ci-review-card">
                                    <div class="ci-review-top">
                                        <h3 class="ci-review-title"><?php the_title(); ?></h3>
                                        <?php if ($r >= 1 && $r <= 5): ?>
                                            <div class="ci-stars" aria-label="<?php echo esc_attr($r . ' out of 5'); ?>">
                                                <?php echo str_repeat('★', $r) . str_repeat('☆', 5 - $r); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="ci-review-content">
                                        <?php
                                        $raw = wp_strip_all_tags(get_the_content());
                                        echo esc_html(wp_trim_words($raw, 55, '…'));
                                        ?>
                                    </div>

                                    <div class="ci-review-meta">
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php else: ?>
                            <p class="ci-muted">No reviews yet.</p>
                        <?php endif; ?>
                    </div>

                    <?php if ($has_more): ?>
                        <div class="ci-loadmore-wrap">
                            <button
                                id="ci-loadmore"
                                class="ci-btn-outline ci-loadmore"
                                type="button"
                                data-page="1"
                                data-per-page="<?php echo (int) $per_page; ?>"
                                data-listing-id="<?php echo (int) $listing_id; ?>"
                                data-ajax-url="<?php echo esc_url($ajax_url); ?>"
                                data-nonce="<?php echo esc_attr($nonce); ?>"
                            >
                                Load more reviews
                            </button>
                            <div id="ci-loadmore-status" class="ci-muted ci-loadmore-status" aria-live="polite"></div>
                        </div>
                    <?php endif; ?>

                </section>

            </main>

        </div>

        <!-- RIGHT COLUMN -->
        <aside class="ci-right">

            <?php if (!empty($gallery) && is_array($gallery)): ?>
                <?php $main = (int) $gallery[0]; ?>
                <div class="ci-gallery">
                    <div class="ci-gallery-main">
                        <?php echo wp_get_attachment_image($main, 'large', false, ['id' => 'ci-main-img', 'loading' => 'eager']); ?>
                    </div>

                    <?php if (count($gallery) > 1): ?>
                        <div class="ci-gallery-thumbs" aria-label="Gallery thumbnails">
                            <?php foreach ($gallery as $img_id): $img_id = (int) $img_id; ?>
                                <button type="button" class="ci-thumb-btn" aria-label="View image">
                                    <img class="ci-thumb"
                                         src="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'thumbnail')); ?>"
                                         data-full="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'large')); ?>"
                                         alt="">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ci-card">
                <h3>Opening Hours</h3>
                <ul class="ci-hours">
                    <?php
                    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    if ($rows = get_field('opening_hours')) :
                        foreach ($rows as $row) :
                            $day_idx = isset($row['day']) ? (int) $row['day'] - 1 : -1;
                            $day_lbl = $days[$day_idx] ?? '';

                            $is_closed = !empty($row['closed']);
                            $open = ci_format_time_value($row['open_time'] ?? '');
                            $close = ci_format_time_value($row['close_time'] ?? '');
                            ?>
                            <li>
                                <span class="ci-hours-day"><?php echo esc_html($day_lbl); ?></span>
                                <span class="ci-hours-time">
                                    <?php
                                    if ($is_closed) {
                                        echo 'Closed';
                                    } else {
                                        $range = trim($open . ($open && $close ? ' – ' : '') . $close);
                                        echo esc_html($range ?: 'Not provided');
                                    }
                                    ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span class="ci-hours-day">—</span><span class="ci-hours-time">Not provided</span></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div id="claim" class="ci-card">
                <h3>Claim this business</h3>
                <?php echo do_shortcode('[ci_claim_form]'); ?>
            </div>

        </aside>

    </div>

</article>

<script>
(() => {
  const main = document.getElementById('ci-main-img');
  if (main) {
    document.querySelectorAll('.ci-thumb').forEach(img => {
      img.addEventListener('click', () => {
        const full = img.getAttribute('data-full');
        if (!full) return;
        main.setAttribute('src', full);
        main.setAttribute('srcset', '');
      });
    });
  }

  const btn = document.getElementById('ci-loadmore');
  if (!btn) return;

  const grid = document.getElementById('ci-review-grid');
  const status = document.getElementById('ci-loadmore-status');

  const setStatus = (msg) => { if (status) status.textContent = msg || ''; };

  btn.addEventListener('click', async () => {
    const ajaxUrl = btn.dataset.ajaxUrl;
    const nonce = btn.dataset.nonce;
    const listingId = btn.dataset.listingId;
    const perPage = btn.dataset.perPage;
    const nextPage = (parseInt(btn.dataset.page || '1', 10) + 1);

    btn.disabled = true;
    setStatus('Loading…');

    try {
      const form = new URLSearchParams();
      form.set('action', 'ci_load_more_reviews');
      form.set('nonce', nonce);
      form.set('listing_id', listingId);
      form.set('page', String(nextPage));
      form.set('per_page', String(perPage));

      const res = await fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: form.toString(),
        credentials: 'same-origin',
      });

      const data = await res.json();
      if (!data || !data.success) {
        throw new Error((data && data.data && data.data.message) ? data.data.message : 'Load failed');
      }

      if (data.data && data.data.html) {
        grid.insertAdjacentHTML('beforeend', data.data.html);
      }

      btn.dataset.page = String(nextPage);

      if (!data.data.has_more) {
        btn.remove();
        setStatus('');
      } else {
        btn.disabled = false;
        setStatus('');
      }
    } catch (e) {
      btn.disabled = false;
      setStatus('Could not load more reviews.');
    }
  });
})();
</script>

<?php get_footer(); ?>