<?php
/**
 * Template Name: ics template for the program schedule
 * Description: A Page Template that lists the schedule in ics format
 */

header('Content-Type: text/calendar');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('Talk.php');
$talk = new lgm_Talk();
$field = $talk->get_field();
// debug('field', $field);
?>
BEGIN:VCALENDAR
PRODID:-//Libre Graphics Meeting//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:LGM 2013
X-WR-TIMEZONE:Europe/Madrid
X-WR-CALDESC:
<?php
function time_add($time, $minutes) {
    $result = '';
    // debug('minutes', $minutes);
    $hm = explode(':', $time);
    // debug('hm', $hm);
    $m = $hm[1] + $minutes;
    $hm[0] = sprintf('%02d', $hm[0] + intval($m / 60));
    $hm[1] = sprintf('%02d', ($m % 60));
    // debug('hm', $hm);
    $result = implode(':', $hm);
    return $result;
}
$entry = $talk->get_entry();
foreach ($entry as $key => $value) {
    if (!empty($value['day']) && !empty($value['time'])) {
        // debug('value', $value);
?>
BEGIN:VEVENT
DTSTART:2013041<?php echo($value['day']) ?>T<?php echo(str_replace(':', '', $value['time'])) ?>00Z
DTEND:2013041<?php echo($value['day']) ?>T<?php echo(str_replace(':', '', time_add($value['time'], $value['duration']))) ?>00Z
DTSTAMP:<?php echo(date('Ymd\THis')); ?>Z
UID:talkid<?php echo(sprintf('%06d', $key)); ?>@libregraphicsmeeting.org
CREATED:<?php echo(date('Ymd\THis')); ?>Z
DESCRIPTION:<?php echo($value['title'].' by '.$value['firstname'].' '.$value['lastname'].(empty($value['speakers']) ? '' : ', '.$value['speakers'])); ?>

LAST-MODIFIED:<?php echo(date('Ymd\THis')); ?>Z
LOCATION:
SEQUENCE:2
STATUS:CONFIRMED
SUMMARY:<?php echo($value['title']) ?>

TRANSP:OPAQUE
END:VEVENT
<?php
    }
}
// debug('entry', $entry);
?>
END:VCALENDAR

