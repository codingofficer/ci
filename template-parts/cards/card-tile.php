<?php
/**
 * Small hero tile (featured #2..#4)
 * Uses image size 'ci-tile' if registered; falls back to 'medium_large'.
 */
$cats = get_the_terms(get_the_ID(), 'category');
$chip = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
?>
<article <?php post_class('card card--tile'); ?>>
  <a class="card__media" href="<?php the_permalink(); ?>">
    <?php has_post_thumbnail() && the_post_thumbnail('ci-tile', ['loading'=>'lazy']); ?>
  </a>
  <div class="card__body">
    <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
  </div>
</article>
