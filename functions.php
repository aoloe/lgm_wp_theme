<?php
// ==== FUNCTIONS ====
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (!function_exists('debug')) {
    function debug($label, $value) {
        echo("<pre>$label: ".str_replace(array('<', '>'), array('&lt;', '&gt;'), print_r($value, 1))."</pre>");
    }
}

add_theme_support( 'post-thumbnails' );

	remove_action('init', 'wp_admin_bar_init');
	function disable_admin_bar() {
	    add_filter( 'show_admin_bar', '__return_false' );
	    add_action( 'admin_print_scripts-profile.php',
	         'hide_admin_bar_settings' );
	}
	add_action( 'init', 'disable_admin_bar' );
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !is_admin() ) {
	   wp_deregister_script('jquery');
	   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"), false);
	   wp_enqueue_script('jquery');
	}
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }

function add_some_mimes($mimes) {
    $ourMimes = array(
        'svg' => 'image/svg+xml',
        'ttf' => 'font/ttf',
    );
    return array_merge($mimes,$ourMimes);
}

add_filter('upload_mimes', 'add_some_mimes');

/**
 * fixes the missing private pages in the parent drow down
 * we use it for the members part of the site
 * function copied from comment 136 in isue http://core.trac.wordpress.org/ticket/8592 
 */
function admin_private_parent_metabox($output)
{
    global $post;

    $args = array(
        'post_type'         => $post->post_type,
        'exclude_tree'      => $post->ID,
        'selected'          => $post->post_parent,
        'name'              => 'parent_id',
        'show_option_none'  => __('(no parent)'),
        'sort_column'       => 'menu_order, post_title',
        'echo'              => 0,
        'post_status'       => array('publish', 'private'),
    );

    $defaults = array(
        'depth'                 => 0,
        'child_of'              => 0,
        'selected'              => 0,
        'echo'                  => 1,
        'name'                  => 'page_id',
        'id'                    => '',
        'show_option_none'      => '',
        'show_option_no_change' => '',
        'option_none_value'     => '',
    );

    $r = wp_parse_args($args, $defaults);
    extract($r, EXTR_SKIP);

    $pages = get_pages($r);
    $name = esc_attr($name);
    // Back-compat with old system where both id and name were based on $name argument
    if (empty($id))
    {
        $id = $name;
    }

    if (!empty($pages))
    {
        $output = "<select name=\"$name\" id=\"$id\">\n";

        if ($show_option_no_change)
        {
            $output .= "\t<option value=\"-1\">$show_option_no_change</option>";
        }
        if ($show_option_none)
        {
            $output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";
        }
        $output .= walk_page_dropdown_tree($pages, $depth, $r);
        $output .= "</select>\n";
    }

    return $output;
}
add_filter('wp_dropdown_pages', 'admin_private_parent_metabox');

/**
 * don't show the category 'team' in the main blog!
 */
function exclude_category( $query ) {
    if ( is_feed() || is_home() ) {
        if ($term = get_term_by('name', 'team', 'category')) {
            // debug('term', $term);
            $query = set_query_var( 'cat',  '-'.$term->term_id);
        }
    }

    return $query;
}

add_filter( 'pre_get_posts', 'exclude_category' );

function the_title_trim($title) {
    // Might aswell make use of this function to escape attributes
    $title = attribute_escape($title);
    // What to find in the title
    $findthese = array(
        '/^'.__('Protected').':/', // / is just the delimeter
        '/^'.__('Private').':/'
    );
    // What to replace it with
    // Items replace by array key
    $title = preg_replace($findthese, '', $title);
    return $title;
}
add_filter('the_title', 'the_title_trim');


function show_post_by_path($path) {
/*
  $post = get_page_by_path($path);
  $content = apply_filters('the_content', $post->post_content);
  echo $content;
*/
}

function show_post_by_id($id) {
  $post = get_page($id);
  $content = apply_filters('the_content', $post->post_content);
  echo $content;
}
?>
