<?php
/**
 * Template Name: Program schedule template
 * Description: A Page Template that lists the project bookmarks (links)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);


/*
wp_deregister_script('jquery-ui');
wp_register_script('jquery-ui', ("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/jquery-ui.min.js"), false);
wp_enqueue_script('jquery-ui');
*/

debug('_REQUEST', $_REQUEST);

if (array_key_exists('lang', $_REQUEST) && ($_REQUEST['lang'] == 'es')) {
    get_header();
    get_sidebar();
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    $post = get_page_by_path('programa');
    $content = apply_filters('the_content', $post->post_content);
    echo $content;

    get_footer();
    exit();
}

include('Talk.php');
$talk = new lgm_Talk();
?>

<?php
function css_lgm_program_script() {
    $css = "
            .show_hide {display:none;}
            ";
    echo('<style type="text/css">'.$css.'</style>'."\n");
}
add_action('wp_print_styles', 'css_lgm_program_script', 12);
?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<script>
$(document).ready(function(){
 
        $(".program_slots").hide();
        $(".show_hide").show();
 
    $('.show_hide').click(function(){
    $(".program_slots").slideToggle();
    });
    $(".program_slot").live("click", function(){
        alert('chuila');
        $("#program_slot_detail").load("/2013/wp/wp-content/themes/lgm/program_description.php")

    });

 
});
</script>
<div id="content" role="main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

</div> <?php // #content ?>

<div id="program_slot_detail"></div>

<?php
// $field = $talk->get_field();
// debug('field', $field);
/*
    [7] => Your talk's title
    [18] => Day
    [19] => Time slot (hh:mm (dd))
    [1] => Last name
    [2] => First name
    [1] => Last name
    [4] => Email
    [16] => Additional speakers for this talk
    [6] => Your talk’s summary
    [5] => Your short biography
    [17] => Website
    [8] => Your submission is for
    [9] => Slides
    [21] => Slides (Pdf)
    [10] => Sponsorship
    [11] => Approximative amount of sponsorship you need
    [12] => Currency
    [13] => Remarks, questions, scheduling needs, etc.
    [14] => Status of proposal
    [15] => Comments on status



    [7] => Title of your presentation
    [18] => Preferred day
    [22] => Day
    [23] => Time slot
    [24] => Duration
    [2] => First name
    [1] => Last name
    [4] => Email
    [16] => Additional speakers
    [6] => Summary of your presentation
    [5] => Short biography
    [17] => Website
    [8] => Preferred format
    [9] => Slides
    [21] => Slides (PDF)
    [10] => Travel support
    [11] => Travelcosts (if you need support)
    [12] => Currency
    [13] => Comments, questions, other needs
    [14] => Status of proposal
    [15] => Comments on status
*/

$entry = $talk->get_entry();
// debug('entry', $entry);

// the grid size must be adapted depending on the start and end hours (9:00-24:00)
$grid = array();
for ($i = 10; $i < 14; $i++) {
    for ($j = 0; $j < 180; $j++) { // 12 slots per hour on 15 hours
        for ($k = 0; $k < 6; $k++) { // max 6 things in parallel
            $grid[$i][$j][$k] = 0;
        }
    }
}
// debug('grid', $grid);

function time_to_column($day, $time, $duration) {
    global $grid;
    $result = 0;
    $slot = (time_to_minutes($time) - 540) / 5; // minus the first 9 hours of the day in 5 min slots
    $end = $slot + ($duration / 5); // 5 minute slots
    $placed = false;
    while (!$placed) {
        $free = true;
        for ($i = $slot; $i < $end; $i++) {
            $free = $free && ($grid[$day][$i][$result] == 0);
        }
        if ($free) {
            $placed = true;
        } else {
            $result++;
        }
    }
    for ($i = $slot; $i < $end; $i++) {
        $grid[$day][$i][$result] = 1; // we could put the talk id...
    }
    return $result;
} // time_to_column()

function time_to_minutes($time) {
    $result = 0;
    list($hour, $minute) = explode(':', $time);
    $result = $hour*60 + $minute;
    // debug('time', $time);
    // debug('result', $result);
    return $result;
}

$day = array (
    10 => 'Wednesday April 10th',
    11 => 'Thursday April 11th',
    12 => 'Friday April 12th',
    13 => 'Saturday April 13th',
    14 => 'Sunday April 14th',
);

$schedule = array(
    10 => array(),
    11 => array(),
    12 => array(),
    14 => array(),
);
$unscheduled = array();
// debug('entry', $entry);
foreach ($entry as $key => $value) {
    // debug('url', $value['url']);
    if (!empty($value['day']) && !empty($value['time'])) {
        $minute = time_to_minutes($value['time']);
        $schedule[$value['day']][$minute][] = array (
            'id' => $key,
            'title' => $value['title'],
            'talker' => $value['firstname'].' '.$value['lastname'].(empty($value['speakers']) ? '' : ', '.$value['speakers']),
            'url' => $value['url'],
            'summary' => $value['summary'],
            'remarks' => $value['remarks'],
            'time' => $value['time'],
            'time_slot' => $minute,
            'time_column' => time_to_column($value['day'], $value['time'], $value['duration']),
            'duration' => $value['duration'],
            'slide' => $value['slide'],
            'type' => $value['type'],
        );
        // debug('value', $value);
    } elseif ($value['status'] != 'Cancelled') {
        $unscheduled[] = array (
            'id' => $key,
            'title' => $value['title'],
            'talker' => $value['firstname'].' '.$value['lastname'].$value['speakers'],
            'type' => $value['type'],
        );
    }
}

// debug('schedule', $schedule);
// debug('unscheduled', $unscheduled);


$cell_width = 140;
$minute_height = 1.6;
$max_cells = 3;
$time_column = 100;

foreach ($schedule as $key => $value) {
    ksort($value);

    echo('<h1>'.$day[$key]."</h1>\n");

    if (current_user_can('edit_posts')) {
        echo('<a href="#" class="show_hide">Show/hide schedule</a>');
        echo('<div class="program_slots" style="position:relative; height:'.(60*$minute_height*10).'px; width:'.($time_column + $max_cells * $cell_width).'px; background-color:pink;">');
        foreach ($value as $start => $slot) {
            foreach ($slot as $item) {
                echo(
                '<div class="program_slot" style="position:absolute; top:'.(($item['time_slot'] - 60*($key == 2 ? 9 : 10)) * $minute_height).'px; left:'.($time_column + ($item['time_column'] * $cell_width)).'px; height:'.($item['duration'] * $minute_height).'px; width:'.$cell_width.'px; overflow:hidden; background-color:green;'.($item['duration'] <= 20 ? ' padding-top:0px; margin-top:0px;' : '').' border:1px solid yellow;">'.
                ($item['duration'] <= 20 ? '<p style="padding:0px; margin:0px;"><small style="font-size:0.8em;">' : '').
                $item['title'].
                ($item['duration'] <= 20 ? '</small></p>' : '').
                '</div>'
                );
                /*
                echo(
                '<p>'.
                $item['time'].'<br />'.
                $item['title'].
                (
                    current_user_can('edit_posts') ?
                    ' <sup>[<a href="/2013/wp/wp-admin/admin.php?page=gf_entries&view=entry&id=3&lid='.$item['id'].'&s=sikk&paged=1&screen_mode=edit">e</a>]</sup>' :
                    ''
                ).
                '<br />'.
                '<em>'.$item['talker'].'</em>'.
                // $item['remarks'].
                "</p>\n"
                );
                */
            }
        }
        echo('</div>');
    }
    foreach ($value as $start => $slot) {
        foreach ($slot as $item) {
            // debug('item', $item);
            // debug('item[type]', $item['type']);
            echo(
            '<p style="width:400px;">'.
            $item['time'].
            ($item['type'] == 'A workshop' ? ' (workshop)' : '').
            ($item['type'] == 'A meeting' ? ' (meeting)' : '').
            '<br />'.
            "<strong>".$item['title']."</strong>".
            (
                current_user_can('edit_posts') ?
                ' <sup>[<a href="/2013/wp/wp-admin/admin.php?page=gf_entries&view=entry&id=3&lid='.$item['id'].'&s=sikk&paged=1&screen_mode=edit">e</a>]</sup>' :
                ''
            ).
            '<br />'.
            '<em>'.$item['talker'].($item['url'] == '' ? '' : '<br /><a href="http://'.$item['url'].'">'.$item['url'].'</a>').'</em>'.
            // $item['remarks'].
            (
                $item['slide'] ?
                "<br />\n".'<a href="'.$item['slide'].'">Slides</a>' :
                ''
            ).
            "</p>\n".
            "<p style=\"width:400px;\"><em>".str_replace("\n", "<br />\n", $item['summary'])."</em></p>\n"
            );
        }
    }
}
/*
$grid_string = array();
for ($i = 0; $i < 4; $i++) {
    $grid_string[$i] = "\n";
    for ($j = 0; $j < count($grid[$i]); $j++) {
        for ($k = 0; $k < count($grid[$i][$j]); $k++) {
            $grid_string[$i] .= $grid[$i][$j][$k] ? '*' : ' ';
        }
        $grid_string[$i] .= "\n";
    }
    $grid_string[$i] .= "_____\n";
}
*/





// debug('grid_string', $grid_string);
// debug('grid', $grid);


if (current_user_can('edit_posts')) {
    $i = 0;
    $table = '<table style="border:1px solid black;"><tr>';
    foreach ($unscheduled as $item) {
        $table .= '<td style="border:1px solid black;">'.$item['title'].'<sup><a href="/2013/wp/wp-admin/admin.php?page=gf_entries&view=entry&id=3&lid='.$item['id'].'&s=sikk&paged=1&screen_mode=edit">&gt;e</a></sup></p>'.'<br>'.$item['talker'].'<br><span style="font-size:6pt;">'.$item['type'].'</span>'.'</td>';
        if ($i > 0 && $i % 5 == 0) {
            $table .= '</tr><tr>';
            $i = 0;
        } else {
            $i++;
        }
    }
    $table .= '</tr></table>';
    echo($table);
}

?>

<?php get_footer(); ?>
