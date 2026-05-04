<?php
function ci_query_featured_listings($args = []) {
  $defaults = [
    'post_type' => 'business_listing',
    'posts_per_page' => 8,
    'meta_key' => '_featured_until',
    'orderby'  => 'meta_value',
    'order'    => 'DESC',
    'no_found_rows' => true,
  ];
  return new WP_Query( wp_parse_args($args, $defaults) );
}

function ci_query_latest_news($ppp = 10) {
  return new WP_Query([
    'post_type' => 'cigar_news',
    'posts_per_page' => $ppp,
    'no_found_rows' => false
  ]);
}
