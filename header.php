<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<title><?= ucfirst($wp_query->queried_object->post_name) ?> / <?php bloginfo('name'); ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="<? bloginfo('template_url') ?>/css/reset.css" type="text/css"/>
	<link rel="stylesheet" href="<? bloginfo('template_url') ?>/css/fonts.css" type="text/css"/>
	<link rel="stylesheet" href="<? bloginfo('template_url') ?>/style.css" type="text/css"/>
        <meta name="Author" content="<?php bloginfo('name'); ?>" />
        <meta name="robots" content="index, follow" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
 

<?php wp_head(); ?>
<script>
var TEMPLATE_PATH = '<? bloginfo('template_url') ?>';
</script>
<script type="text/javascript" src="<? bloginfo('template_url') ?>/js/lgm.js"></script>
</head>
<body>

    <div id="page">

	<div id="header-container">
		<div class="header">

			<div id="logo" style="height:305px;">
				<div class="column" style="float: left; width: 50%;">
                <div style="position:absolute; right:0px; top:-25px;"><?php do_action('icl_language_selector'); ?></div>
					<?
					$lang = isset($_GET['lang']) ? $_GET['lang'] : false;
					$backtohome = $lang=='fr' ? 'accueil' : 'Back to home';
					?>
					<a href="/2013/<? if($lang) echo '?lang='.$lang; ?>" title="<?= $backtohome ?>"><img src="<? bloginfo('template_url') ?>/img/LGM<? if($lang) echo '_es'; ?>.png" /></a>
				</div>
				<div class="column" style="float: right; width: 33.333%; height:100px;"> 
                    <a id="propose" class="image-link" href="http://libregraphicsmeeting.org/2013/call-for-presentations/"><?php /*<img alt="propose your talk!" src="<?php bloginfo('template_url') ?>/img/badges/yourtalk.png" border="0" /> */ ?></a>
                    <a id="join" class="image-link" href="http://libregraphicsmeeting.org/2013/registration/"><img alt="register!" src="<?php bloginfo('template_url') ?>/img/badges/signup.png" border="0" /></a>
                    <a id="futuretools" class="image-link" href="http://libregraphicsmeeting.org//2013/about-lgm"><img alt="Future tools" src="<?php bloginfo('template_url') ?>/img/badges/futuretools.png" border="0" /></a>
                    <a id="support" class="image-link" href="http://pledgie.com/campaigns/19064"><img alt="Support LGM" src="<?php bloginfo('template_url') ?>/img/badges/support.png" border="0" /></a>
                    <?php /* static button
					<!-- <a id="donate" class="image-link" href="http://pledgie.com/campaigns/14610"><img alt="review!" src="<? bloginfo('template_url') ?>/img/pledgie_banner<? if($lang) echo '_fr'; ?>.png" border="0" /></a> -->
                    */ ?>
				</div>
				<div class="column" style="float: right; width: 16.666%;"></div>
			</div><!-- end logo -->

		</div><!-- end header -->
	</div><!-- end header-container -->
