<?php
/**
 * Template Name: Mosaic template
 * Description: A Page Template that showcases Sticky Posts, Asides, and Blog Posts
 */

// ==== FUNCTIONS ====
if (!function_exists('debug')) {
    function debug($label, $value) {
        if ($_SERVER['REMOTE_ADDR'] == '178.195.77.72') {
            echo("<pre>$label: ".str_replace(array('<', '>'), array('&lt;', '&gt;'), print_r($value, 1))."</pre>");
            // echo("<pre>".print_r(debug_backtrace(), 1)."</pre>");
        }
    }
}

function css_allmend_vscript() {
    $css = "
        .grid-item {width:200px; padding:15px 15px 30px 0px; margin-bottom:5px;}
        #grid-wrapper .post-title a {color:#cc0000;}
        #grid-wrapper .post-content {margin-bottom:0px; padding-bottom:0px; font-size:0.9em;}
        #grid-wrapper .post-theme {font-size:0.8em;}
        /* #grid-wrapper .post-meta {margin-top:0px; padding:0px 5px; background-color:#ddd;} */
        #main {padding-left:0px; margin-left:0px;}
        /* #content {padding:0px; margin:0px;} */
        #content {padding:0px; margin:0px; width:700px;}
";
        if ($_SERVER['REMOTE_ADDR'] == '178.195.77.72') {
            /*
            $css .= "
                #grid-wrapper {background-color:yellow; width:800px;}
                .grid-item {background-color:red;}
            ";
            */
        }
    //echo('<style type="text/css">'.$css.'</style>'."\n");
}
add_image_size('allmend-mosaic-thumbnail', 200, 200);
?>

<?php
// ==== SETTINGS ====
$mosaic_posts_per_page = 4;
?>

<?php
// ==== OUTPUT ====
?>


<?php wp_enqueue_script('jquery'); ?>
<?php wp_enqueue_script('jquery.easing',get_bloginfo('template_url').'/js/jquery.easing.1.3.js'); ?>
<?php wp_enqueue_script('jquery.vgrid',get_bloginfo('template_url').'/js/jquery.vgrid.0.1.7.min.js'); ?>
<?php add_action('wp_print_styles', 'css_allmend_vscript', 12); ?>
<?php get_header(); ?>
<?php get_sidebar(); ?>

<style>
/* for debugging */
/*
#grid-wrapper
{border:1px solid black}
*/
</style>

<script>
        var $j = jQuery.noConflict();
        $j(function(){
            //$j("#grid-wrapper").vgrid();
        }
        );
</script>

<div id="content" role="main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

<div id="grid-wrapper">
<?php
    // get_var("SELECT term_ID FROM $wpdb->terms WHERE name='test'");  
    // $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    // $paged = WP_find::tags('test');
    $query = new WP_Query();
    // $query = new WP_Query('category_name=staff');
    // $query = new WP_Query('tag=cooking');
    // $query->query(array('posts_per_page' => 6, 'tag__not_in' => array(6)));
    // http://codex.wordpress.org/Class_Reference/wpdb
    $tag_id = $wpdb->get_var("SELECT term_ID FROM $wpdb->terms WHERE name='test'");
    // debug('tag_id', $tag_id);
    // $query->query(array('posts_per_page' => $mosaic_posts_per_page, 'tag__not_in' => $tag_id));
    $query_parameter = array(array('posts_per_page' => $mosaic_posts_per_page));
    // exclude posts from the team category
    if ($term = get_term_by('name', 'team', 'category')) {
        // debug('term', $term);
        $query_parameter['cat'] = '-'.$term->term_id;
    }
    $query->query($query_parameter);
    // http://ottopress.com/2010/wordpress-3-1-advanced-taxonomy-queries/
    // $query->query(array('posts_per_page' => 6, 'tax_query' => array(array('taxonomy' => 'post_tag', 'terms' => array('test'), 'field' => 'slug', 'operator' => 'NOT IN'))));
    // $query->query('posts_per_page=6&tag__not_in=test');
    // --> if i ever want to exclude the tag from the blog page: http://zeo.my/exclude-category-in-wordpress/ ... make a copy of index.php and edit it

    while ($query->have_posts()) :
        $query->the_post();
        ?>
			<div class="grid-item" id="post-<?php the_ID(); ?>">
        <?php
        if (has_post_thumbnail(get_the_ID())) :
            echo(sprintf(
                '<div class="grid-image"><a href="%s" rel="bookmark" class="none">%s</a></div>',
                get_permalink(),
                get_the_post_thumbnail(get_the_ID(), 'allmend-mosaic-thumbnail', array('alt' => get_the_title(), 'title' => get_the_title()))
            ));
        // else :
        //     debug('txt', 'no image');
        endif;
        echo(sprintf(
            '<h2 class="post-title"><a href="%s" rel="bookmark">%s</a></h2>',
            get_permalink(),
            get_the_title()
        ));
        echo(sprintf(
            '<p class="post-content">%s</p>',
            get_the_excerpt()
        ));
        // $content = get_the_content('Details &raquo;');
        // debug('content', $content);
        // debug('category', get_the_category());
        $category = get_the_category();
        $themen = '';
        $categories = array();
        foreach ($category as $item) {
            if ($item->slug != 'uncategorized') {
                $categories[] = '<a href="'.get_category_link($item->term_id ).'">'.$item->cat_name.'</a>';
            }
        }
        if (!empty($categories)) {
            echo(sprintf(
                "<p class=\"post-theme\">Topic: %s.</p>",
                implode(', ', $categories)
            ));
        }
        //echo(sprintf(
            //"<p class=\"post-meta\">%s by %s.</p>",
            //get_the_time(get_option('date_format')),
            //get_the_author()
        //));
        ?>
        </div> <?php // .grid-item ?>
        <?php
    endwhile;
    /*
    if noposts :// have_posts()
    ?>
    <p><?php _e('No posts.'); ?></p>
<?php endif;
    */
?>

</div> <?php // #grid-wrapper ?>

</div> <?php // #content ?>


<?php get_footer(); ?>
