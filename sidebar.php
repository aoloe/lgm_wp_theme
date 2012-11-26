		<!-- Right Column --> 
		<div id="right" class="column"> 
			<div id="right-content"> 

				<? include_once('inc/flickr_feed.php') ?>
				<div id="flickr" class="menu-block">
				<h3 style="margin-left:0px;"><a href="http://www.flickr.com/groups/776360@N22/pool/">Flickr pool</a></h3>
				<?= $flickr_html ?>
				</div> <!-- div#flickr -->


				<? include_once('inc/identica_feed.php') ?>
				<dl id="twits" class="menu-block">
				<h3>Identi.ca feed</h3>
				<?= $identica_html ?>
				</dl>

			</div><!-- end right-content --> 
		</div><!-- end right --> 

		<!-- Center Column --> 
		<div id="center" class="column"> 
			<div id="center-content"> 

				<!-- Main menu -->
				<div class="menu">
                <?php $test = array(); ?>
				<?= /* wp_nav_menu('Program') */ wp_nav_menu(array('depth' => 1)); // only show the first level to avoid listing public team pages ?>

				</div><!-- end menu -->
				<!-- End Main Menu --> 
                <?php if (is_user_logged_in()) : ?>
                    <div class="menu">
                    <h3>Team</h3>
                    <ul>
                    <?php
                    $rows = $wpdb->get_results( "SELECT ID, post_title FROM wp2012_posts WHERE post_type = 'page' /* AND post_status = 'private' */ AND post_parent=36" );
                    // debug('myrows', $myrows);
                    foreach ($rows as $item) : ?>
                    <li style="margin-bottom:6px;"><a href="<?php echo(get_bloginfo('url').'?page_id='.$item->ID); ?>"><?php echo($item->post_title); ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                    </div>
                <?php endif; ?>


				<div id="logos"> 
					<h3>Partners</h3> 
<?php show_post_by_id(98); ?>
                    <?php /*
					<a href="http://create.freedesktop.org"><img src="<? bloginfo('template_url') ?>/img/partner_create.png" /></a> 
                    */ ?>
			
                    <?php /*
					<a href="http://www.technikum-wien.at"><img src="<? bloginfo('template_url') ?>/img/partner_technikum_wien.png"  style="width:140px;"/></a> 
					<a href="http://www.linuxwochen.at/"><img src="<? bloginfo('template_url') ?>/img/partner_linuxwochen.gif"  style="width:140px;"/></a> 
                    */ ?>
<?php /*
					<a href="http://www.francophonie.org/"><img src="<? bloginfo('template_url') ?>/img/partner_OIF.png"  style="width:140px;"/></a> 
*/ ?>
					 
				</div><!-- logos --> 
<div id="social">
<h3>Follow Us</h3>
<?php wp_list_bookmarks( array('category_name'=> 'Social',
                               'title_before' => '',
                               'title_after'   => '',
                               'categorize'   => 0,
                               'title_li'     => '',
                               'limit'        => 5, 
                               'orderby'      => 'rand'
                                ) ); ?>
</div>

<br />
<div id="presshits">
<h3>In the Press</h3>
<?php wp_list_bookmarks( array('category_name'=> 'Press Hit',
                               'title_before' => '',
                               'title_after'  => '',
                               'categorize'   => 0,
                               'title_li'     => '',
                               'limit'        => 5, 
                               'orderby'      => 'rand'
                                ) ); ?>
</div>


			</div><!-- center-content --> 
		</div><!-- center --> 
