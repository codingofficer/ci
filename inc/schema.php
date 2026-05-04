<?php
add_action('wp_head', function(){
  if (is_singular('business_listing')) {
    global $post;
    [$avg,$cnt] = ci_get_rating($post->ID);
    $data = [
      "@context" => "https://schema.org",
      "@type" => "LocalBusiness",
      "name" => get_the_title(),
      "url" => get_permalink(),
      "address" => [
        "@type"=>"PostalAddress",
        "streetAddress"=> trim((string)get_field('address_street') . ' ' . (string)get_field('address_street2')),
        "addressLocality"=> (string)get_field('address_city'),
        "addressRegion"=> (string)get_field('address_province'),
        "postalCode"=> (string)get_field('address_zip'),
        "addressCountry"=> (string)get_field('address_country')
      ],
    ];
    if ($avg && $cnt) $data['aggregateRating'] = ["@type"=>"AggregateRating","ratingValue"=>$avg,"reviewCount"=>$cnt];
    echo '<script type="application/ld+json">'.wp_json_encode($data).'</script>';
  }
  if (is_singular('cigar_news')) {
    $data = [
      "@context" => "https://schema.org",
      "@type" => "NewsArticle",
      "headline" => get_the_title(),
      "datePublished" => get_the_date('c'),
      "dateModified" => get_the_modified_date('c'),
      "mainEntityOfPage" => get_permalink(),
    ];
    echo '<script type="application/ld+json">'.wp_json_encode($data).'</script>';
  }
});
