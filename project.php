<?php
/**
 * Template Name: Project template
 * Description: A Page Template that lists the project bookmarks (links)
 */

// remove_all_filters('option_sticky_posts', true);
get_header()
?>

<div id="content-container">
	<div id="content">
 
		<!-- Left Column -->
		<div id="container_lgm">
			<div id="left" class="column">
				<div id="left-content">



<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<!--
<?php $mypost = get_post_class(); print_r($mypost) ; ?>
-->

					<div class="viewcontent cc-page <?php if ( is_sticky() ) echo "sticky"; ?>" id="post-<?php the_ID(); ?>">

						<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>


						<?php the_content(); ?>

<?php
$projects = get_bookmarks( array('category_name'=> 'Project',
                               'categorize'   => 0,
                                ) ); 

if ( count($projects) > 0 ) 
{
    echo '<table class="projects">';
    foreach ($projects as $project)
    {
        echo '<tr><td class="image">';
        if ( !empty($project->link_image) )
            echo '<a href="' . $project->link_url . '">' . 
                   '<img src="' . $project->link_image . '" alt="' . $project->link_name . '" /></a>';
        echo '</td>';
        echo '<td class="description"><h4><a href="' . $project->link_url . '">' . 
               $project->link_name . '</a></h4>';
        echo '<p>' . $project->link_description . '</p></td></tr>';

    }
    echo '</tr></table>';
}
?>
					</div>

					<div id="pageinfo">
<?/*						<div id="pagetranslations">
							<ul>
								<li class="current" style="background-image:url('<? bloginfo('template_url')?>/img/flags/en.png');">en</li>
								<li><a href="/2011/index.php?p=fr%2Fhome" style="background-image:url('<? bloginfo('template_url')?>/img/flags/fr.png');">fr</a></li>
							</ul>
						</div>
*/ ?>						<div id="pagerevision">Last edited on <?php the_modified_date(); ?></div>
						<div class="break"></div>
					</div>

<?php endwhile; endif; ?>

<div class="navigation">
	<div class="alignright"><? next_posts_link() ?></div>
	<div class="alignleft"><? previous_posts_link() ?></div>
</div>

				</div>
			</div>
		</div><!-- end container_lgm -->


		<?php get_sidebar(); ?>


	</div><!-- end content -->
</div><!-- end content-container -->



<?php
get_footer();
