<?php get_header(); ?>

<div class="ci-product-wrap">

  <div class="ci-product-grid">

    <!-- LEFT: IMAGE -->
    <div class="ci-product-image">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large'); ?>
      <?php endif; ?>
    </div>

    <!-- RIGHT: CONTENT -->
    <div class="ci-product-main">

      <h1 class="ci-product-title"><?php the_title(); ?></h1>

      <?php
      $region = get_field('cigar_region');
      $filler = get_field('filler');
      $wrapper = get_field('wrapper');
      $binder = get_field('binder');
      $length = get_field('length');
      $ring = get_field('ring_gauge');
      $vitola = get_field('vitola');
      $construction = get_field('construction');
      $price = get_field('price_paid');
      ?>

      <div class="ci-specs">

        <?php if ($region): ?>
          <div><span>Region</span><strong><?= esc_html($region); ?></strong></div>
        <?php endif; ?>

        <?php if ($filler): ?>
          <div><span>Filler</span><strong><?= esc_html($filler); ?></strong></div>
        <?php endif; ?>

        <?php if ($wrapper): ?>
          <div><span>Wrapper</span><strong><?= esc_html($wrapper); ?></strong></div>
        <?php endif; ?>

        <?php if ($binder): ?>
          <div><span>Binder</span><strong><?= esc_html($binder); ?></strong></div>
        <?php endif; ?>

        <?php if ($length): ?>
          <div><span>Length</span><strong><?= esc_html($length); ?></strong></div>
        <?php endif; ?>

        <?php if ($ring): ?>
          <div><span>Ring Gauge</span><strong><?= esc_html($ring); ?></strong></div>
        <?php endif; ?>

        <?php if ($vitola): ?>
          <div><span>Vitola</span><strong><?= esc_html($vitola); ?></strong></div>
        <?php endif; ?>

        <?php if ($construction): ?>
          <div><span>Construction</span><strong><?= esc_html($construction); ?></strong></div>
        <?php endif; ?>

        <?php if ($price): ?>
          <div><span>Price Paid</span><strong><?= esc_html($price); ?></strong></div>
        <?php endif; ?>

      </div>

    </div>

  </div>

  <!-- REVIEW CONTENT -->
  <div class="ci-product-content">
    <?php the_content(); ?>
  </div>

</div>

<?php get_footer(); ?>