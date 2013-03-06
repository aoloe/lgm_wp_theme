<?php
// remove_all_filters('option_sticky_posts', true);
get_header()
?>


<div id="content">
<!--
<if rame src="/2013/r+w/index.php" style="width: 250px; height: 325px; overflow: hidden"></if rame>
-->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <!--
    <?php $mypost = get_post_class(); print_r($mypost) ; ?>
    -->

					<div class="viewcontent cc-page <?php if ( is_sticky() ) echo "sticky"; ?>" id="post-<?php the_ID(); ?>">

						<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a><?php edit_post_link(__('✎'), '<span>', '</span>'); ?></h1>

						<?php the_content(); ?>

					</div>

                    <div class=”comments-template”>
                    <?php comments_template(); ?>
                    </div>

<?php endwhile; endif; ?>

<div class="navigation">
	<div class="alignright"><? next_posts_link() ?></div>
	<div class="alignleft"><? previous_posts_link() ?></div>
</div>

		</div><!-- end container_lgm -->


		<?php get_sidebar(); ?>


</div><!-- end content -->

<?php
get_footer();
