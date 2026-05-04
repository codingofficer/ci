<?php
/**
 * Theme bootstrap
 */
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/assets.php';
require_once __DIR__ . '/inc/queries.php';
require_once __DIR__ . '/inc/schema.php';
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/article-blocks.php';

add_filter('acf/settings/save_json', function ($path) {
  return get_stylesheet_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});

add_action('after_setup_theme', function () {
  add_theme_support('post-thumbnails');
  add_image_size('ci-hero', 1200, 700, true); // big hero
  add_image_size('ci-tile',  560,  340, true); // small tiles
});

// Enqueue CSS/JS (adjust paths to match your /assets build)
add_action('wp_enqueue_scripts', function () {

  // Base stylesheet
  wp_enqueue_style(
    'ci-style',
    get_stylesheet_uri(),
    [],
    filemtime(get_stylesheet_directory() . '/style.css')
  );
	
	wp_enqueue_style(
	  'fontawesome',
	  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
	  [],
	  null
	);

  // Front page CSS
  $front_css = get_template_directory() . '/assets/css/front-page.css';
  if (is_front_page() && file_exists($front_css)) {
    wp_enqueue_style(
      'ci-front',
      get_template_directory_uri() . '/assets/css/front-page.css',
      ['ci-style'],
      filemtime($front_css)
    );
  }
	// Paginated articles
	$articles_css = get_template_directory() . '/assets/css/articles.css';

	if (is_page_template('page-articles.php') && file_exists($articles_css)) {
	  wp_enqueue_style(
		'ci-articles',
		get_template_directory_uri() . '/assets/css/articles.css',
		[],
		filemtime($articles_css)
	  );
	}

	// Cigar Library CSS (archive + single + taxonomy)
	$library_css = get_template_directory() . '/assets/css/cigar-library.css';

	if (
	  (
		is_post_type_archive('cigar_library') ||
		is_singular('cigar_library') ||
		is_tax('library_category') ||
		is_post_type_archive('cigar_news') ||
		is_singular('cigar_news') ||
		is_tax('news_categories')
	  )
	  && file_exists($library_css)
	) {
	  wp_enqueue_style(
		'ci-library',
		get_template_directory_uri() . '/assets/css/cigar-library.css',
		['ci-style'],
		filemtime($library_css)
	  );
	}
	
	$taxonomy_css = get_template_directory() . '/assets/css/cigar-library-taxonomy.css';

	if (is_tax('library_category') && file_exists($taxonomy_css)) {
	  wp_enqueue_style(
		'ci-library-taxonomy',
		get_template_directory_uri() . '/assets/css/cigar-library-taxonomy.css',
		['ci-library'],
		filemtime($taxonomy_css)
	  );
	}

  // Optional Theme JS
  $theme_js = get_template_directory() . '/assets/js/theme.js';
  if (file_exists($theme_js)) {
    wp_enqueue_script(
      'ci-theme',
      get_template_directory_uri() . '/assets/js/theme.js',
      ['jquery'],
      filemtime($theme_js),
      true
    );
  }

});

// Widgets / Sidebars
add_action('widgets_init', function () {
  register_sidebar([
    'name'          => __('Home Sidebar','cigar-inspector'),
    'id'            => 'home_sidebar',
    'description'   => 'Widgets for the homepage right column.',
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ]);
});

// Make shortcodes work in common places (widgets/ACF text)
add_filter('widget_text', 'do_shortcode');                 // classic text widget
add_filter('widget_text_content', 'do_shortcode');         // block-based text
add_filter('the_excerpt', 'do_shortcode');
add_filter('acf/format_value/type=textarea', function($value){ return do_shortcode($value); }, 11, 1);
add_filter('acf/format_value/type=wysiwyg',  function($value){ return do_shortcode($value); }, 11, 1);

// Helper when you manually echo strings containing shortcodes:
function ci_echo_sc($str){ echo do_shortcode($str); }



// Add custom author fields
function library_add_author_fields($user) {
?>
  <h3>Author Profile Settings</h3>

  <table class="form-table">

    <tr>
      <th><label for="author_role">Author Role</label></th>
      <td>
        <input type="text" name="author_role" id="author_role"
        value="<?php echo esc_attr(get_user_meta($user->ID, 'author_role', true)); ?>"
        class="regular-text" />
      </td>
    </tr>

    <tr>
      <th><label for="author_bio_short">Short Bio</label></th>
      <td>
        <textarea name="author_bio_short" id="author_bio_short" rows="3" class="large-text"><?php
          echo esc_textarea(get_user_meta($user->ID, 'author_bio_short', true));
        ?></textarea>
      </td>
    </tr>

	<tr>
	  <th><label for="author_avatar_id">Author Avatar</label></th>
	  <td>

		<?php
		$avatar_id = get_user_meta($user->ID, 'author_avatar_id', true);
		$avatar_url = $avatar_id ? wp_get_attachment_image_url($avatar_id, 'thumbnail') : '';
		?>

		<div id="author-avatar-preview" style="margin-bottom:10px;">
		  <?php if ($avatar_url): ?>
			<img src="<?php echo esc_url($avatar_url); ?>" style="max-width:120px;border-radius:8px;" />
		  <?php endif; ?>
		</div>

		<input type="hidden" name="author_avatar_id" id="author_avatar_id"
			   value="<?php echo esc_attr($avatar_id); ?>" />

		<button type="button" class="button" id="upload_author_avatar">
		  Upload Avatar
		</button>

		<button type="button" class="button" id="remove_author_avatar">
		  Remove
		</button>

		<p class="description">Upload a custom author avatar.</p>

	  </td>
	</tr>

  </table>
<?php
}
add_action('show_user_profile', 'library_add_author_fields');
add_action('edit_user_profile', 'library_add_author_fields');


// Save fields
function library_save_author_fields($user_id) {

  if (!current_user_can('edit_user', $user_id)) return false;

  update_user_meta($user_id, 'author_role', sanitize_text_field($_POST['author_role']));
  update_user_meta($user_id, 'author_bio_short', sanitize_textarea_field($_POST['author_bio_short']));
  update_user_meta($user_id, 'author_avatar_id', intval($_POST['author_avatar_id']));
}
add_action('personal_options_update', 'library_save_author_fields');
add_action('edit_user_profile_update', 'library_save_author_fields');


add_action('admin_enqueue_scripts', function($hook) {

  if ($hook === 'profile.php' || $hook === 'user-edit.php') {
    wp_enqueue_media();

    wp_enqueue_script(
      'author-avatar-upload',
      get_stylesheet_directory_uri() . '/assets/js/author-avatar.js',
      ['jquery'],
      '1.0',
      true
    );
  }

});


add_action('admin_head', function() {
    echo '<style>

        /* Hide About Yourself heading */
        h2:has(+ table tr.user-url-wrap) {
            display: none !important;
        }

        /* Hide entire About Yourself table */
        tr.user-url-wrap,
        tr.user-description-wrap,
        tr.user-profile-picture {
            display: none !important;
        }

    </style>';
});


add_action('init', function () {

    /* --------------------------------------------------
       CIGARS POST TYPE
       Archive: /cigars/
       Single:  /cigars/montecristo-no-2/
    -------------------------------------------------- */

    if (!post_type_exists('cigar_review')) {

        register_post_type('cigar_review', [

            'labels' => [
                'name'               => 'Cigars',
                'singular_name'      => 'Cigar',
                'menu_name'          => 'Cigars',
                'add_new'            => 'Add Cigar',
                'add_new_item'       => 'Add New Cigar',
                'edit_item'          => 'Edit Cigar',
                'new_item'           => 'New Cigar',
                'view_item'          => 'View Cigar',
                'search_items'       => 'Search Cigars',
                'not_found'          => 'No cigars found',
                'not_found_in_trash' => 'No cigars found in Trash',
            ],

            'public'        => true,
            'has_archive'   => 'cigars',

            'rewrite' => [
                'slug'       => 'cigars',
                'with_front' => false
            ],

            'menu_icon'     => 'dashicons-products',

            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'comments',
                'revisions'
            ],

            'show_in_rest'  => true,
        ]);
    }


    /* --------------------------------------------------
       CIGAR CATEGORY TAXONOMY
    -------------------------------------------------- */

    if (!taxonomy_exists('ci_category')) {

        register_taxonomy('ci_category', ['cigar_review'], [

            'labels' => [
                'name'          => 'Categories',
                'singular_name' => 'Category',
                'menu_name'     => 'Categories',
            ],

            'hierarchical' => true,
            'public'       => true,

            'rewrite' => [
                'slug'         => 'cigar-category',
                'with_front'   => false,
                'hierarchical' => true
            ],

            'show_in_rest' => true,
        ]);
    }

});


add_action('wp_enqueue_scripts', function() {

  if (is_singular('cigar_review')) {
    wp_enqueue_style(
      'ci-cigar-product',
      get_stylesheet_directory_uri() . '/assets/css/cigar-product.css',
      [],
      '1.0'
    );
  }

});


function ci_listing_assets() {
    if (is_singular('business_listing')) {

        wp_enqueue_style(
            'ci-listing',
            get_stylesheet_directory_uri() . '/assets/css/listing.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'ci-listing-js',
            get_stylesheet_directory_uri() . '/assets/js/listing.js',
            [],
            '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts','ci_listing_assets');


add_action('init', function () {
    register_post_type('review', [
        'labels' => [
            'name'          => 'Reviews',
            'singular_name' => 'Review',
        ],
        'public'       => true,
        'show_in_menu' => true,
        'supports'     => ['title', 'editor', 'author', 'revisions'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'reviews'],
        'show_in_rest' => true,
    ]);
});


add_action('pre_get_posts', function($query) {

    if (is_admin() || !$query->is_main_query()) return;

    if (is_post_type_archive('business_listing')) {

        $meta_query = ['relation' => 'AND'];

        if (!empty($_GET['q'])) {
            $query->set('s', sanitize_text_field($_GET['q']));
        }

        if (!empty($_GET['country'])) {
            $meta_query[] = [
                'key'     => 'address_country',
                'value'   => sanitize_text_field($_GET['country']),
                'compare' => '='
            ];
        }

        if (!empty($_GET['city'])) {
            $meta_query[] = [
                'key'     => 'address_city',
                'value'   => sanitize_text_field($_GET['city']),
                'compare' => '='
            ];
        }

        if (isset($_GET['has_image']) && $_GET['has_image'] !== '') {

            if ($_GET['has_image'] === '1') {
                $meta_query[] = [
                    'key'     => 'gallery',
                    'compare' => 'EXISTS'
                ];
            }

            if ($_GET['has_image'] === '0') {
                $meta_query[] = [
                    'key'     => 'gallery',
                    'compare' => 'NOT EXISTS'
                ];
            }
        }

        if (count($meta_query) > 1) {
            $query->set('meta_query', $meta_query);
        }
    }
});

add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');

    // Custom thumbnail for business listings
    add_image_size('ci-thumb', 600, 400, true);
});

/* CI Settings Banner AJAX Fix */
add_filter('acf/fields/post_object/query', function($args, $field, $post_id) {

    $target_fields = [
        'large_banner',
        'top_left_banner',
        'top_right_banner',
        'bottom_left_banner',
        'bottom_right_banner'
    ];

    if (!in_array($field['name'], $target_fields)) {
        return $args;
    }

    if (!empty($args['s'])) {

        $args['s'] = trim($args['s']);

        add_filter('posts_search', function($search, $wp_query) {

            global $wpdb;

            if (!empty($wp_query->query_vars['s'])) {
                $term = esc_sql($wp_query->query_vars['s']);

                return " AND ({$wpdb->posts}.post_title LIKE '%{$term}%')";
            }

            return $search;

        }, 10, 2);
    }

    return $args;

}, 10, 3);

