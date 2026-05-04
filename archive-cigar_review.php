<?php get_header(); ?>

<?php
/* --------------------------------------------------
   Helper: Get distinct meta values safely
-------------------------------------------------- */
function ci_get_distinct_meta_values($key) {
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT DISTINCT TRIM(meta_value)
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '{$key}'
        AND p.post_type = 'cigar_review'
        AND p.post_status = 'publish'
        AND meta_value != ''
        ORDER BY meta_value ASC
    ");

    return array_filter($results);
}
?>

<div class="wrap ci-archive-wrap">

    <header class="ci-archive-hero">
        <h1>Cigars</h1>
        <p>Explore our complete cigar library with detailed specs.</p>
    </header>

    <div class="ci-archive-layout">

        <!-- =============================
             FILTER SIDEBAR
        ============================== -->
        <aside class="ci-filters">
            <div class="ci-filter-card">

                <form method="get">

                    <h3>Filter Cigars</h3>

                    <!-- Category -->
                    <div class="ci-filter-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">All Categories</option>
                            <?php
                            $terms = get_terms([
                                'taxonomy' => 'ci_category',
                                'hide_empty' => true
                            ]);
                            foreach ($terms as $term) {
                                echo '<option value="'.esc_attr($term->slug).'" '.selected($_GET['category'] ?? '', $term->slug, false).'>'.$term->name.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Region -->
                    <div class="ci-filter-group">
                        <label>Region</label>
                        <select name="region">
                            <option value="">All Regions</option>
                            <?php
                            foreach (ci_get_distinct_meta_values('cigar_region') as $value) {
                                echo '<option value="'.esc_attr($value).'" '.selected($_GET['region'] ?? '', $value, false).'>'.$value.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Construction -->
                    <div class="ci-filter-group">
                        <label>Construction</label>
                        <select name="construction">
                            <option value="">All</option>
                            <?php
                            foreach (ci_get_distinct_meta_values('construction') as $value) {
                                echo '<option value="'.esc_attr($value).'" '.selected($_GET['construction'] ?? '', $value, false).'>'.$value.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Vitola -->
                    <div class="ci-filter-group">
                        <label>Vitola</label>
                        <select name="vitola">
                            <option value="">All</option>
                            <?php
                            foreach (ci_get_distinct_meta_values('vitola') as $value) {
                                echo '<option value="'.esc_attr($value).'" '.selected($_GET['vitola'] ?? '', $value, false).'>'.$value.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Ring Gauge -->
                    <div class="ci-filter-group">
                        <label>Ring Gauge</label>
                        <div class="ci-range">
                            <input type="number" name="ring_min" placeholder="Min"
                                value="<?php echo esc_attr($_GET['ring_min'] ?? ''); ?>">
                            <input type="number" name="ring_max" placeholder="Max"
                                value="<?php echo esc_attr($_GET['ring_max'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="ci-filter-group">
                        <label>Price ($)</label>
                        <div class="ci-range">
                            <input type="number" step="0.01" name="price_min" placeholder="Min"
                                value="<?php echo esc_attr($_GET['price_min'] ?? ''); ?>">
                            <input type="number" step="0.01" name="price_max" placeholder="Max"
                                value="<?php echo esc_attr($_GET['price_max'] ?? ''); ?>">
                        </div>
                    </div>

                    <button type="submit" class="ci-btn-primary">Apply Filters</button>

                    <a href="<?php echo esc_url(get_post_type_archive_link('cigar_review')); ?>"
                       class="ci-reset">Reset</a>

                </form>
            </div>
        </aside>

        <!-- =============================
             GRID
        ============================== -->
        <main class="ci-grid">

        <?php
        $paged = max(1, get_query_var('paged'));

        $meta_query = ['relation' => 'AND'];
        $tax_query  = [];

        if (!empty($_GET['category'])) {
            $tax_query[] = [
                'taxonomy' => 'ci_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['category'])
            ];
        }

        if (!empty($_GET['region'])) {
            $meta_query[] = [
                'key'     => 'cigar_region',
                'value'   => sanitize_text_field($_GET['region']),
                'compare' => '='
            ];
        }

        if (!empty($_GET['construction'])) {
            $meta_query[] = [
                'key'     => 'construction',
                'value'   => sanitize_text_field($_GET['construction']),
                'compare' => '='
            ];
        }

        if (!empty($_GET['vitola'])) {
            $meta_query[] = [
                'key'     => 'vitola',
                'value'   => sanitize_text_field($_GET['vitola']),
                'compare' => '='
            ];
        }

        if (!empty($_GET['ring_min']) || !empty($_GET['ring_max'])) {
            $meta_query[] = [
                'key'     => 'ring_gauge',
                'value'   => [
                    $_GET['ring_min'] ?: 0,
                    $_GET['ring_max'] ?: 999
                ],
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN'
            ];
        }

        if (!empty($_GET['price_min']) || !empty($_GET['price_max'])) {
            $meta_query[] = [
                'key'     => 'price_paid',
                'value'   => [
                    $_GET['price_min'] ?: 0,
                    $_GET['price_max'] ?: 9999
                ],
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN'
            ];
        }

        $query = new WP_Query([
            'post_type'      => 'cigar_review',
            'posts_per_page' => 12,
            'paged'          => $paged,
            'tax_query'      => $tax_query,
            'meta_query'     => count($meta_query) > 1 ? $meta_query : []
        ]);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post(); ?>

                <article class="ci-card">
                    <a href="<?php the_permalink(); ?>" class="ci-card-link">

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="ci-card-media">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="ci-card-content">
                            <h3><?php the_title(); ?></h3>
                            <span class="ci-card-region">
                                <?php echo esc_html(get_post_meta(get_the_ID(), 'cigar_region', true)); ?>
                            </span>
                        </div>

                    </a>
                </article>

        <?php endwhile; ?>

            <div class="ci-pagination">
                <?php echo paginate_links([
                    'total'   => $query->max_num_pages,
                    'current' => $paged
                ]); ?>
            </div>

        <?php else : ?>
            <p>No cigars found.</p>
        <?php endif;

        wp_reset_postdata();
        ?>

        </main>

    </div>
</div>

<?php get_footer(); ?>