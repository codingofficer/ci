<?php [$avg,$cnt] = ci_get_rating(get_the_ID()); ?>
<div class="stars" aria-label="<?php echo esc_attr("$avg out of 5, $cnt reviews"); ?>">
  <?php
    $full = floor($avg); $half = ($avg - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half;
    echo str_repeat('<span class="star full">★</span>',$full);
    echo str_repeat('<span class="star half">★</span>',$half);
    echo str_repeat('<span class="star empty">☆</span>',$empty);
  ?>
  <span class="count">(<?php echo (int)$cnt; ?>)</span>
</div>
