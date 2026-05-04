<?php
add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('responsive-embeds');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  add_theme_support('editor-styles');
  add_editor_style('assets/css/main.css');

  register_nav_menus([
    'primary' => __('Primary Menu','cigar-inspector'),
    'footer'  => __('Footer Menu','cigar-inspector'),
  ]);

  // Image sizes (tune later)
  add_image_size('ci-card', 640, 360, true);
  add_image_size('ci-thumb', 400, 225, true);
});


// ==============================
// CIGAR LIBRARY POST TYPE
// ==============================

add_action('init', function () {

  register_post_type('cigar_library', [
  'labels' => [
    'name'               => 'Cigar Library',
    'singular_name'      => 'Cigar Library',
    'menu_name'          => 'Cigar Library',
    'add_new'            => 'Add Entry',
    'add_new_item'       => 'Add Library Entry',
    'edit_item'          => 'Edit Library Entry',
    'new_item'           => 'New Library Entry',
    'view_item'          => 'View Library Entry',
    'search_items'       => 'Search Library',
    'not_found'          => 'No library entries found',
    'not_found_in_trash' => 'No library entries found in Trash',
  ],
    'public'        => true,
    'has_archive'   => true,
    'menu_position' => 6,
    'menu_icon'     => 'dashicons-welcome-learn-more',
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
    'rewrite'       => ['slug' => 'cigar-library'],
    'show_in_rest'  => true,
  ]);

  // ==============================
  // LIBRARY CATEGORIES
  // ==============================

  register_taxonomy('library_category', 'cigar_library', [
    'labels' => [
      'name'          => 'Library Categories',
      'singular_name' => 'Library Category',
      'menu_name'     => 'Library Categories',
    ],
    'hierarchical' => true,
    'public'       => true,
    'rewrite'      => ['slug' => 'library-category'],
    'show_in_rest' => true,
  ]);

});