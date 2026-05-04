<?php get_header(); ?>
<section class="wrap">
  <h1><?php single_term_title(); ?></h1>
  <div class="cards">
    <?php if (have_posts()) : while (have_posts()) : the_post();
      get_template_part('template-parts/cards/card','listing');
    endwhile; endif; ?>
  </div>
  <?php get_template_part('template-parts/components/pagination'); ?>
</section>
<?php get_footer(); ?>
