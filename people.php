<?php
/**
 * Template Name: LGM people
 * Description: A Page Template that shows a list of all people at the lgm
 */
// error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', false);


include('Talk.php');
$talk = new lgm_Talk();
?>

<?php
// ==== OUTPUT ====
?>


<?php get_header(); ?>

<div id="content" role="main" style="width:500px;">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

<?php

$email = array();

$entry = $talk->get_entry();
// debug('entry', $entry);

foreach ($entry as $item) {
    if (!in_array($item['email'], $email)) {
        $email[] = $item['email'];
    }
}

include('Register.php');
$register = new lgm_Register();

$entry = $register->get_entry();
// debug('entry', $entry);

foreach ($entry as $item) {
    if (!in_array($item['email'], $email)) {
        $email[] = $item['email'];
    }
}


foreach ($email as $item) {
    echo('<p>'. $item.'</p>');
}

?>

<?php get_sidebar(); ?>

</div> <?php // #content ?>


<?php get_footer(); ?>
