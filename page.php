<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="ci-page-wrap">

  <div class="ci-page-container">

    <header class="ci-page-header">
      <h1 class="ci-page-title"><?php the_title(); ?></h1>
      <span class="ci-page-date"><?php echo get_the_date(); ?></span>
    </header>

    <div class="ci-page-content">
      <?php the_content(); ?>
    </div>

  </div>

</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>