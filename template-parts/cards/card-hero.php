<?php
/**
 * Large hero card (featured #1)
 * Uses image size 'ci-hero' if registered; falls back to 'large'.
 */
$cats = get_the_terms(get_the_ID(), 'category');
$chip = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
?>
<article <?php post_class('card card--hero'); ?>>
  <a class="card__media" href="<?php the_permalink(); ?>">
    <?php if (has_post_thumbnail()) {
      the_post_thumbnail('ci-hero', ['loading'=>'eager','decoding'=>'async']);
    } ?>
    <?php $terms = get_the_terms(get_the_ID(),'category'); ?>
    <?php if (!is_wp_error($terms) && $terms) : ?>
      <span class="badge"><?php echo esc_html($terms[0]->name); ?></span>
    <?php endif; ?>
  </a>
  <div class="card__body">
    <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p>
  </div>
</article>

