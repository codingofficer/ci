<article <?php post_class('card card--cigar'); ?>>
  <a class="card__media" href="<?php the_permalink(); ?>">
    <?php if (has_post_thumbnail()) the_post_thumbnail('ci-card'); ?>
  </a>
  <div class="card__body">
    <a class="card__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <p class="card__meta"><?php echo ci_term_list(get_the_ID(),'ci_categories'); ?></p>
    <p class="card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
  </div>
</article>
