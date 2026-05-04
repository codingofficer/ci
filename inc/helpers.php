<?php
function ci_term_list($post_id, $taxonomy) {
  $terms = get_the_terms($post_id, $taxonomy);
  if (!$terms || is_wp_error($terms)) return '';
  return join(', ', wp_list_pluck($terms, 'name'));
}

function ci_get_rating($post_id) {
  $avg = get_post_meta($post_id, '_rating_avg', true);
  $cnt = get_post_meta($post_id, '_rating_count', true);
  return [(float)$avg, (int)$cnt];
}
