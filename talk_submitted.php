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

<div id="content" role="main">

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
        'Talk LGM 2013: '.str_replace('"', '', substr($item['title'], 0, 50)), 
        sprintf(
"Dear %s,%%0D%%0A
%%0D%%0A
We are happy to confirm that your talk proposal has been accepted and will be part of the LGM 2013 program. The meeting takes place from 10-13 April in Madrid, Spain.%%0D%%0A
%%0D%%0A
Right now we are finalizing the schedule, which will be online by Sunday April 1.%%0D%%0A
%%0D%%0A
If you asked for support from LGM, take into account that reimbursements happen only after LGM, and cover travel costs (economy class, no accommodation etc.). For more information, see here: http://libregraphicsmeeting.org/2013/reimbursements .%%0D%%0A
%%0D%%0A
Please contact us as soon as possible if anything changes in your trip.%%0D%%0A
%%0D%%0A
We look forward to your contribution and to a great LGM!.%%0D%%0A
%%0D%%0A
For the LGM team,%%0D%%0A
Femke Snelting",
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
