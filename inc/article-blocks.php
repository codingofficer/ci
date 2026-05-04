<?php
/**
 * Flexible article blocks for Cigar Library and News articles.
 */

function ci_article_block_choices() {
  return [
    'background' => [
      'none'  => 'Default',
      'soft'  => 'Soft grey',
      'cream' => 'Warm cream',
      'dark'  => 'Dark',
    ],
    'width' => [
      'contained' => 'Contained',
      'wide'      => 'Wide',
      'full'      => 'Full width',
    ],
  ];
}

function ci_article_block_options_fields($prefix) {
  $choices = ci_article_block_choices();

  return [
    [
      'key' => "field_{$prefix}_background",
      'label' => 'Background Colour',
      'name' => 'background',
      'type' => 'button_group',
      'choices' => $choices['background'],
      'default_value' => 'none',
      'return_format' => 'value',
      'layout' => 'horizontal',
    ],
    [
      'key' => "field_{$prefix}_width",
      'label' => 'Block Width',
      'name' => 'width',
      'type' => 'button_group',
      'choices' => $choices['width'],
      'default_value' => 'contained',
      'return_format' => 'value',
      'layout' => 'horizontal',
    ],
    [
      'key' => "field_{$prefix}_featured",
      'label' => 'Featured / Boxed Section',
      'name' => 'featured',
      'type' => 'true_false',
      'ui' => 1,
      'default_value' => 0,
    ],
  ];
}

function ci_article_block_sub_fields($fields, $prefix) {
  return array_merge($fields, ci_article_block_options_fields($prefix));
}

add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_ci_article_blocks',
    'title' => 'Article Block Editor',
    'fields' => [
      [
        'key' => 'field_ci_article_blocks_intro',
        'label' => 'Block Editor',
        'name' => '',
        'type' => 'message',
        'message' => 'Use the blocks below to build designed articles. Drag blocks to reorder, duplicate them from the row controls, and paste video or social links into embed blocks.',
        'new_lines' => 'wpautop',
      ],
      [
        'key' => 'field_ci_article_blocks',
        'label' => 'Article Blocks',
        'name' => 'article_blocks',
        'type' => 'flexible_content',
        'button_label' => 'Add Article Section',
        'layouts' => [
          'layout_ci_text_section' => [
            'key' => 'layout_ci_text_section',
            'name' => 'text_section',
            'label' => 'Text Section',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_text_content',
                'label' => 'Text',
                'name' => 'text',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
              ],
            ], 'ci_text'),
          ],
          'layout_ci_image_block' => [
            'key' => 'layout_ci_image_block',
            'name' => 'image_block',
            'label' => 'Image Block',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_image_image',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
              ],
              [
                'key' => 'field_ci_image_caption',
                'label' => 'Caption',
                'name' => 'caption',
                'type' => 'text',
              ],
            ], 'ci_image'),
          ],
          'layout_ci_image_text_split' => [
            'key' => 'layout_ci_image_text_split',
            'name' => 'image_text_split',
            'label' => 'Image + Text Split',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_split_position',
                'label' => 'Layout',
                'name' => 'image_position',
                'type' => 'button_group',
                'choices' => [
                  'left' => 'Left image / right text',
                  'right' => 'Left text / right image',
                ],
                'default_value' => 'left',
                'return_format' => 'value',
              ],
              [
                'key' => 'field_ci_split_image',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'large',
                'library' => 'all',
              ],
              [
                'key' => 'field_ci_split_caption',
                'label' => 'Image Caption',
                'name' => 'caption',
                'type' => 'text',
              ],
              [
                'key' => 'field_ci_split_heading',
                'label' => 'Heading',
                'name' => 'heading',
                'type' => 'text',
              ],
              [
                'key' => 'field_ci_split_text',
                'label' => 'Text',
                'name' => 'text',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'delay' => 0,
              ],
            ], 'ci_split'),
          ],
          'layout_ci_pull_quote' => [
            'key' => 'layout_ci_pull_quote',
            'name' => 'pull_quote',
            'label' => 'Pull Quote / Highlight',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_quote_text',
                'label' => 'Quote',
                'name' => 'quote',
                'type' => 'textarea',
                'rows' => 4,
                'new_lines' => 'br',
              ],
              [
                'key' => 'field_ci_quote_attribution',
                'label' => 'Attribution',
                'name' => 'attribution',
                'type' => 'text',
              ],
            ], 'ci_quote'),
          ],
          'layout_ci_cta_section' => [
            'key' => 'layout_ci_cta_section',
            'name' => 'cta_section',
            'label' => 'CTA Section',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_cta_heading',
                'label' => 'Heading',
                'name' => 'heading',
                'type' => 'text',
              ],
              [
                'key' => 'field_ci_cta_text',
                'label' => 'Text',
                'name' => 'text',
                'type' => 'textarea',
                'rows' => 3,
              ],
              [
                'key' => 'field_ci_cta_link',
                'label' => 'Button Link',
                'name' => 'link',
                'type' => 'link',
                'return_format' => 'array',
              ],
            ], 'ci_cta'),
          ],
          'layout_ci_embed_block' => [
            'key' => 'layout_ci_embed_block',
            'name' => 'embed_block',
            'label' => 'Video / Social Embed',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_embed_url',
                'label' => 'Embed URL',
                'name' => 'embed_url',
                'type' => 'url',
                'instructions' => 'Paste a YouTube, Instagram, Vimeo, X/Twitter or other supported embed link.',
              ],
              [
                'key' => 'field_ci_embed_caption',
                'label' => 'Caption',
                'name' => 'caption',
                'type' => 'text',
              ],
            ], 'ci_embed'),
          ],
          'layout_ci_review_guide' => [
            'key' => 'layout_ci_review_guide',
            'name' => 'review_guide',
            'label' => 'Review / Guide Layout',
            'display' => 'block',
            'sub_fields' => ci_article_block_sub_fields([
              [
                'key' => 'field_ci_review_style',
                'label' => 'Layout Style',
                'name' => 'style',
                'type' => 'button_group',
                'choices' => [
                  'review' => 'Review style',
                  'guide' => 'Guide style',
                ],
                'default_value' => 'review',
                'return_format' => 'value',
              ],
              [
                'key' => 'field_ci_review_heading',
                'label' => 'Heading',
                'name' => 'heading',
                'type' => 'text',
              ],
              [
                'key' => 'field_ci_review_summary',
                'label' => 'Summary',
                'name' => 'summary',
                'type' => 'textarea',
                'rows' => 3,
              ],
              [
                'key' => 'field_ci_review_score',
                'label' => 'Score / Rating',
                'name' => 'score',
                'type' => 'text',
                'instructions' => 'Optional, e.g. 92/100 or 4.5/5.',
              ],
              [
                'key' => 'field_ci_review_items',
                'label' => 'Structured Points',
                'name' => 'items',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Point',
                'sub_fields' => [
                  [
                    'key' => 'field_ci_review_item_label',
                    'label' => 'Label',
                    'name' => 'label',
                    'type' => 'text',
                    'parent_repeater' => 'field_ci_review_items',
                  ],
                  [
                    'key' => 'field_ci_review_item_value',
                    'label' => 'Value',
                    'name' => 'value',
                    'type' => 'text',
                    'parent_repeater' => 'field_ci_review_items',
                  ],
                ],
              ],
              [
                'key' => 'field_ci_review_link',
                'label' => 'Optional Link',
                'name' => 'link',
                'type' => 'link',
                'return_format' => 'array',
              ],
            ], 'ci_review'),
          ],
        ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'cigar_news',
        ],
      ],
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'cigar_library',
        ],
      ],
    ],
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'show_in_rest' => 0,
  ]);
});

function ci_article_block_classes() {
  $background = get_sub_field('background') ?: 'none';
  $width = get_sub_field('width') ?: 'contained';
  $classes = [
    'ci-article-block',
    'ci-block-bg-' . sanitize_html_class($background),
    'ci-block-width-' . sanitize_html_class($width),
  ];

  if (get_sub_field('featured')) {
    $classes[] = 'ci-block-featured';
  }

  return implode(' ', $classes);
}

function ci_article_block_image($image_id, $caption = '') {
  if (!$image_id) {
    return;
  }

  echo '<figure class="ci-block-figure">';
  echo wp_get_attachment_image((int) $image_id, 'full');

  if ($caption) {
    echo '<figcaption>' . esc_html($caption) . '</figcaption>';
  }

  echo '</figure>';
}

function ci_article_block_link($link, $class = 'ci-block-button') {
  if (empty($link['url']) || empty($link['title'])) {
    return;
  }

  $target = !empty($link['target']) ? $link['target'] : '_self';

  $rel = $target === '_blank' ? ' rel="noopener"' : '';

  echo '<a class="' . esc_attr($class) . '" href="' . esc_url($link['url']) . '" target="' . esc_attr($target) . '"' . $rel . '>';
  echo esc_html($link['title']);
  echo '</a>';
}

function ci_render_article_blocks() {
  if (!function_exists('have_rows') || !have_rows('article_blocks')) {
    return false;
  }

  echo '<div class="ci-article-blocks">';

  while (have_rows('article_blocks')) {
    the_row();
    $layout = get_row_layout();
    $classes = ci_article_block_classes();

    if ($layout === 'text_section') {
      echo '<section class="' . esc_attr($classes) . ' ci-block-text">';
      echo '<div class="ci-block-inner">';
      echo apply_filters('the_content', get_sub_field('text'));
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'image_block') {
      echo '<section class="' . esc_attr($classes) . ' ci-block-image">';
      echo '<div class="ci-block-inner">';
      ci_article_block_image(get_sub_field('image'), get_sub_field('caption'));
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'image_text_split') {
      $position = get_sub_field('image_position') === 'right' ? 'right' : 'left';
      echo '<section class="' . esc_attr($classes) . ' ci-block-split ci-block-split-' . esc_attr($position) . '">';
      echo '<div class="ci-block-inner">';
      echo '<div class="ci-block-split-media">';
      ci_article_block_image(get_sub_field('image'), get_sub_field('caption'));
      echo '</div>';
      echo '<div class="ci-block-split-copy">';
      if (get_sub_field('heading')) {
        echo '<h2>' . esc_html(get_sub_field('heading')) . '</h2>';
      }
      echo apply_filters('the_content', get_sub_field('text'));
      echo '</div>';
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'pull_quote') {
      echo '<section class="' . esc_attr($classes) . ' ci-block-quote">';
      echo '<div class="ci-block-inner">';
      echo '<blockquote>';
      echo '<p>' . wp_kses_post(nl2br(get_sub_field('quote'))) . '</p>';
      if (get_sub_field('attribution')) {
        echo '<cite>' . esc_html(get_sub_field('attribution')) . '</cite>';
      }
      echo '</blockquote>';
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'cta_section') {
      echo '<section class="' . esc_attr($classes) . ' ci-block-cta">';
      echo '<div class="ci-block-inner">';
      if (get_sub_field('heading')) {
        echo '<h2>' . esc_html(get_sub_field('heading')) . '</h2>';
      }
      if (get_sub_field('text')) {
        echo '<p>' . esc_html(get_sub_field('text')) . '</p>';
      }
      ci_article_block_link(get_sub_field('link'));
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'embed_block') {
      $url = get_sub_field('embed_url');
      echo '<section class="' . esc_attr($classes) . ' ci-block-embed">';
      echo '<div class="ci-block-inner">';
      if ($url) {
        $embed = wp_oembed_get($url);
        echo '<div class="ci-block-embed-frame">';
        echo $embed ? $embed : '<a href="' . esc_url($url) . '">' . esc_html($url) . '</a>';
        echo '</div>';
      }
      if (get_sub_field('caption')) {
        echo '<p class="ci-block-caption">' . esc_html(get_sub_field('caption')) . '</p>';
      }
      echo '</div>';
      echo '</section>';
    }

    if ($layout === 'review_guide') {
      $style = get_sub_field('style') === 'guide' ? 'guide' : 'review';
      echo '<section class="' . esc_attr($classes) . ' ci-block-review ci-block-review-' . esc_attr($style) . '">';
      echo '<div class="ci-block-inner">';
      echo '<div class="ci-block-review-main">';
      if (get_sub_field('heading')) {
        echo '<h2>' . esc_html(get_sub_field('heading')) . '</h2>';
      }
      if (get_sub_field('summary')) {
        echo '<p>' . esc_html(get_sub_field('summary')) . '</p>';
      }
      ci_article_block_link(get_sub_field('link'), 'ci-block-text-link');
      echo '</div>';
      echo '<div class="ci-block-review-aside">';
      if (get_sub_field('score')) {
        echo '<strong class="ci-block-score">' . esc_html(get_sub_field('score')) . '</strong>';
      }
      if (have_rows('items')) {
        echo '<dl>';
        while (have_rows('items')) {
          the_row();
          echo '<div>';
          echo '<dt>' . esc_html(get_sub_field('label')) . '</dt>';
          echo '<dd>' . esc_html(get_sub_field('value')) . '</dd>';
          echo '</div>';
        }
        echo '</dl>';
      }
      echo '</div>';
      echo '</div>';
      echo '</section>';
    }
  }

  echo '</div>';

  return true;
}

function ci_article_blocks_plain_text($post_id = null) {
  if (!function_exists('get_field')) {
    return '';
  }

  $blocks = get_field('article_blocks', $post_id ?: get_the_ID());
  if (empty($blocks) || !is_array($blocks)) {
    return '';
  }

  $text = '';
  array_walk_recursive($blocks, function ($value, $key) use (&$text) {
    if (is_string($value) && !in_array($key, ['url', 'target', 'background', 'width', 'image_position', 'style'], true)) {
      $text .= ' ' . wp_strip_all_tags($value);
    }
  });

  return trim($text);
}

function ci_article_word_count($post_id = null) {
  $post_id = $post_id ?: get_the_ID();
  $content = get_post_field('post_content', $post_id) . ' ' . ci_article_blocks_plain_text($post_id);

  return str_word_count(wp_strip_all_tags($content));
}
