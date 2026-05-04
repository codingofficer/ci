<?php get_header(); ?>
<section class="wrap">
  <h1><?php printf(esc_html__('Search: %s','cigar-inspector'), get_search_query()); ?></h1>
  <div class="cards">
    <?php if (have_posts()) : while (have_posts()) : the_post();
      $pt = get_post_type();
      if ($pt === 'business_listing') get_template_part('template-parts/cards/card','listing');
      elseif ($pt === 'cigar_news') get_template_part('template-parts/cards/card','news');
      else get_template_part('template-parts/cards/card','news');
    endwhile; else:
      echo '<p>'.esc_html__('Nothing matched your search.','cigar-inspector').'</p>';
    endif; ?>
  </div>
  <?php get_template_part('template-parts/components/pagination'); ?>
</section>
<?php get_footer(); ?>
