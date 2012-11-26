<?php
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
                    <p><?php edit_post_link(__('[Edit]'), '<p>', '</p>'); ?>

						<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

						<?php the_content(); ?>

					</div>

                    <div class=”comments-template”>
                    <?php comments_template(); ?>
                    </div>

					<div id="pageinfo">
<?/*						<div id="pagetranslations">
							<ul>
								<li class="current" style="background-image:url('<? bloginfo('template_url')?>/img/flags/en.png');">en</li>
								<li><a href="/2011/index.php?p=fr%2Fhome" style="background-image:url('<? bloginfo('template_url')?>/img/flags/fr.png');">fr</a></li>
							</ul>
						</div>
*/ ?>						<div id="pagerevision">Last edited on <?php the_modified_date(); ?><?php edit_post_link(__('[Edit]'), ''); ?></div>
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
