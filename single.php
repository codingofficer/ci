<?php get_header(); the_post(); ?>
<article class="wrap">
  <header><h1><?php the_title(); ?></h1></header>
  <div class="content"><?php the_content(); ?></div>
</article>
<?php get_footer(); ?>
