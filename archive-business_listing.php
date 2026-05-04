<?php get_header(); ?>

<section class="ci-archive-wrap">

  <div class="wrap">

    <header class="ci-archive-header">
      <h1><?php post_type_archive_title(); ?></h1>
      <p class="ci-archive-sub">Discover trusted cigar merchants worldwide.</p>
    </header>

    <div class="ci-archive-layout">

      <!-- ================= FILTER ================= -->
      <aside class="ci-filters">

        <form method="get" class="ci-filter-card">

          <h3>Filter Merchants</h3>

          <!-- Search -->
          <div class="ci-filter-group">
            <label>Search</label>
            <input type="text"
                   name="q"
                   placeholder="Search by name..."
                   value="<?php echo esc_attr($_GET['q'] ?? ''); ?>">
          </div>

          <!-- Country -->
          <div class="ci-filter-group">
            <label>Country</label>
            <select name="country">
              <option value="">All Countries</option>
              <?php
              global $wpdb;

              $countries = $wpdb->get_col("
                SELECT DISTINCT meta_value
                FROM {$wpdb->postmeta}
                WHERE meta_key = 'address_country'
                AND meta_value != ''
                AND LENGTH(meta_value) > 2
                ORDER BY meta_value ASC
              ");

              foreach ($countries as $country) {
                  echo '<option value="'.esc_attr($country).'" '.selected($_GET['country'] ?? '', $country, false).'>'.esc_html($country).'</option>';
              }
              ?>
            </select>
          </div>

          <!-- City -->
          <div class="ci-filter-group">
            <label>City</label>
            <select name="city">
              <option value="">All Cities</option>
              <?php
              $cities = $wpdb->get_col("
                SELECT DISTINCT meta_value
                FROM {$wpdb->postmeta}
                WHERE meta_key = 'address_city'
                AND meta_value != ''
                AND meta_value NOT LIKE '%-%'
                ORDER BY meta_value ASC
              ");

              foreach ($cities as $city) {
                  echo '<option value="'.esc_attr($city).'" '.selected($_GET['city'] ?? '', $city, false).'>'.esc_html($city).'</option>';
              }
              ?>
            </select>
          </div>

          <!-- Images -->
          <div class="ci-filter-group">
            <label>Images</label>
            <div class="ci-radio-group">

              <label>
                <input type="radio" name="has_image" value=""
                  <?php checked($_GET['has_image'] ?? '', ''); ?>>
                <span>All</span>
              </label>

              <label>
                <input type="radio" name="has_image" value="1"
                  <?php checked($_GET['has_image'] ?? '', '1'); ?>>
                <span>With image</span>
              </label>

              <label>
                <input type="radio" name="has_image" value="0"
                  <?php checked($_GET['has_image'] ?? '', '0'); ?>>
                <span>Without image</span>
              </label>

            </div>
          </div>

          <div class="ci-filter-actions">
            <button type="submit" class="ci-btn-primary">Apply Filters</button>
            <a href="<?php echo esc_url(get_post_type_archive_link('business_listing')); ?>" class="ci-reset">Reset</a>
          </div>

        </form>

      </aside>

      <!-- ================= RESULTS ================= -->
      <div class="ci-archive-results">

        <div class="ci-grid">
          <?php if (have_posts()) :
            while (have_posts()) : the_post();
              get_template_part('template-parts/cards/card','listing');
            endwhile;
          else:
            echo '<p>No merchants found.</p>';
          endif; ?>
        </div>

        <div class="ci-pagination">
          <?php get_template_part('template-parts/components/pagination'); ?>
        </div>

      </div>

    </div>

  </div>

</section>

<?php get_footer(); ?>