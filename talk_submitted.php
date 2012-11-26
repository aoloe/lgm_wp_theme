<?php
/**
 * Template Name: LGM talk submissions list
 * Description: A Page Template that shows entries from grafity forms' form for LGM talks 
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('Talk.php');
$talk = new lgm_Talk();
?>

<?php
// ==== OUTPUT ====
?>


<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" role="main" style="width:500px;">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

<?php
/** read the list of fields */
// $field = $talk->get_field();
// debug('field', $field);

/*
    [2] => First name
    [1] => Last name
    [4] => Email
    [17] => Website
    [16] => Additional speakers for this talk
    [7] => Your talk's title
    [6] => Your talk’s summary
    [5] => Your short biography
    [8] => Your submission is for
    [9] => Slides
    [10] => Sponsorship
    [11] => Approximative amount of sponsorship you need
    [12] => Currency
    [13] => Remarks, questions, scheduling needs, etc.
    [14] => Status of proposal
    [15] => Comments on status
*/

/** read the entries */

$entry = $talk->get_entry();
// debug('entry', $entry);

foreach ($entry as $item) {
    echo('<p>'.
    '<b>'.$item['title'].'</b>'.
    sprintf(
        '[ <a href="mailto:%s?subject=%s&body=%s">confirm</a> ]',
        $item['email'],
        'Talk LGM 2012: '.str_replace('"', '', substr($item['title'], 0, 50)), 
        sprintf(
"Dear %s,%%0D%%0A
%%0D%%0A
We are happy to confirm that your  talk proposal has been accepted and will be part of the LGM 2012 schedule. We are finalizing the schedule at present time and it will be online on Sunday April 1.  Please make sure to contact us asap if you encounter any change in your trip.%%0D%%0A
%%0D%%0A
We look forward to a great LGM !%%0D%%0A
%%0D%%0A
Have a nice day,%%0D%%0A
%%0D%%0A
Ale Rimoldi%%0D%%0A
for the LGM team",
            $item['firstname']
        )
    ).
    '<br />'.
    (!empty($item['url']) ? '<a href="http://'.$item['url'].'">': '').$item['firstname'].' '.$item['lastname'].(!empty($item['url']) ? '</a>': '').(!empty($item['speakers']) ? '(+ '.$item['speakers'].')' : '').'<br />'.
    '<em>'.$item['type'].'</em><br />'.
    $item['summary'].'<br />'.
    '&nbsp;<br />'.
    $item['biography'].'<br />'.
    (!empty($item['sponsorhip']) ? $item['sponsorhip'].' '.$item['sponsorhip_currency'].'<br />' : '').
    (!empty($item['remarks']) ? '<em>'.$item['remarks'].'</em><br />' : '').
    '</p>');
}


?>

</div> <?php // #content ?>


<?php get_footer(); ?>
