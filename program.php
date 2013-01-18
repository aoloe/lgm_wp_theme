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
*/

$entry = $talk->get_entry();
// debug('entry', $entry);

$grid = array();
for ($i = 0; $i < 4; $i++) {
    for ($j = 0; $j < 120; $j++) { // 12 slots per hour on 10 hours
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
            $free = $free && ($grid[$day - 2][$i][$result] == 0);
        }
        if ($free) {
            $placed = true;
        } else {
            $result++;
        }
    }
    for ($i = $slot; $i < $end; $i++) {
        $grid[$day - 2][$i][$result] = 1; // we could put the talk id...
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
    2 => 'Wednesday May 2nd',
    3 => 'Thursday May 3rd',
    4 => 'Friday May 4th',
    5 => 'Saturday May 5th',
);

$schedule = array(
    2 => array(),
    3 => array(),
    4 => array(),
    5 => array(),
);
$unscheduled = array();
// debug('entry', $entry);
foreach ($entry as $key => $value) {
    if (!empty($value['day']) && !empty($value['time'])) {
        $minute = time_to_minutes($value['time']);
        $schedule[$value['day']][$minute][] = array (
            'id' => $key,
            'title' => $value['title'],
            'talker' => $value['firstname'].' '.$value['lastname'].(empty($value['speakers']) ? '' : ', '.$value['speakers']),
            'type' => $value['type'],
            'summary' => $value['summary'],
            'remarks' => $value['remarks'],
            'time' => $value['time'],
            'time_slot' => $minute,
            'time_column' => time_to_column($value['day'], $value['time'], $value['duration']),
            'duration' => $value['duration'],
            'slide' => $value['slide'],
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
            '<em>'.$item['talker'].'</em>'.
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
