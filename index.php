<?php get_header(); ?>
<section class="wrap">
  <?php if (have_posts()) : while (have_posts()) : the_post();
    get_template_part('template-parts/cards/card','news');
  endwhile;
    get_template_part('template-parts/components/pagination');
  else:
    echo '<p>'.esc_html__('No content found.','cigar-inspector').'</p>';
  endif; ?>
</section>
<?php get_footer(); ?>
