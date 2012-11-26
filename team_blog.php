<?php
/**
 * Template Name: Team blog
 * Description: A Page Template which only shows the blog posts in the category "team"
 */

// ==== FUNCTIONS ====
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
if (!function_exists('debug')) {
    function debug($label, $value) {
        echo("<pre>$label: ".str_replace(array('<', '>'), array('&lt;', '&gt;'), print_r($value, 1))."</pre>");
    }
}

// ==== SETTINGS ====
?>

<?php get_header(); ?>
<?php get_sidebar(); ?>

<style>
/* for debugging */
/*
#grid-wrapper
{border:1px solid black}
*/
</style>

<div id="content" role="main" style="width:500px;">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

<?php
    $query = new WP_Query();
    // http://codex.wordpress.org/Class_Reference/wpdb
    // $query_parameter = array(array('posts_per_page' => $mosaic_posts_per_page));
    // exclude posts from the team category
    if ($term = get_term_by('name', 'team', 'category')) {
        // debug('term', $term);
        $query_parameter['cat'] = $term->term_id;
    }
    $query->query($query_parameter);

    while ($query->have_posts()) :
        $query->the_post();
        ?>
			<div class="grid-item" id="post-<?php the_ID(); ?>">
        <?php
        $title = get_the_title();
        $content = get_the_content();
        $author = get_the_author();
        if (empty($title)) {
            if (strlen($content) < 150) {
                $title = $content;
                $content = '';
            }  else {
                $title = 'new post by '.$author;
            }
        }
        echo(sprintf(
            '<h2 class="post-title"><a href="%s" rel="bookmark">%s</a></h2>',
            get_permalink(),
            $title
        ));
        echo(sprintf(
            '<p class="post-content">%s</p>',
            // get_the_excerpt()
            $content
        ));
        // $content = get_the_content('Details &raquo;');
        // debug('content', $content);
        // debug('category', get_the_category());
        $category = '';
        /*
        @xxx: maybe insert the tags...
        $category = get_the_category();
        $themen = '';
        $categories = array();
        foreach ($category as $item) {
            if ($item->slug != 'uncategorized') {
                $categories[] = '<a href="'.get_category_link($item->term_id ).'">'.$item->cat_name.'</a>';
            }
        }
        if (!empty($categories)) {
            $category = sprintf(
                " Topic: %s.",
                implode(', ', $categories)
            );
        }
        */
        echo(sprintf(
            "<p class=\"post-meta\">%s by %s.%s</p>",
            get_the_time(get_option('date_format')),
            $author,
            $category
        ));
        ?>
        </div> <?php // .grid-item ?>
        <?php
    endwhile;
?>

</div> <?php // #content ?>


<?php get_footer(); ?>
