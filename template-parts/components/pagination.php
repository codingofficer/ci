<?php
/**
 * Simple numbered pagination (works with custom loops)
 */
global $wp_query;

$total   = isset($wp_query->max_num_pages) ? intval($wp_query->max_num_pages) : 1;
$current = max(1, get_query_var('paged') ?: get_query_var('page'));

if ($total > 1) {
  echo '<nav class="pagination">';
  echo paginate_links([
    'total'   => $total,
    'current' => $current,
    'mid_size'=> 2,
    'prev_text' => '&laquo;',
    'next_text' => '&raquo;',
  ]);
  echo '</nav>';
}
