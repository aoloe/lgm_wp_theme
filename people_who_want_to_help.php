<?php
/**
 * Template Name: LGM participants helping
 * Description: A Page Template that shows entries from grafity forms' form for LGM registration
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$row = $wpdb->get_results( "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = 1", ARRAY_A );
// debug('row', $row);
$field = array();
foreach ($row as $item) {
    // debug('item', $item);
    $iitem = unserialize($item['display_meta']);
    // debug('entry', $entry);
    foreach ($iitem['fields'] as $iiitem) {
        $field[$iiitem['id']] = $iiitem['label'];
    }
}
// debug('field', $field);

/*
[19] => First name
[20] => Last name
[31] => Project or organization
[4] => Role
[5] => Nickname
[12] => Email
[22] => Website/Blog
[26] => List of participants
[24] => My participation
[21] => What are your goals in attending LGM 2012?
[10] => Pre-LGM activities
[11] => I will be attending LGM on:
[28] => Great LGM Supper - Wednesday May 2
[25] => Official LGM T-Shirt - €20
[13] => Eco-friendly question 1: Country you’re travelling from
[14] => Eco-friendly question 2: I travelled (mostly) by:
[15] => Eco-friendly question 3: Number of kilometers!
[9] => Comments
[29] => Status of participant
[30] => Notes about status of participant
*/

/** read the entries */
$field = array(
    19 => 'firstname',
    20 => 'lastname',
    5 =>  'nickmane',
    12 => 'email',
    '24.2' => 'participation', // if float, you need ROUND() in the comparison below
    '9' => 'comments',
);

$row = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE form_id = 1 AND ROUND(field_number, 1) IN (".implode(", ", array_keys($field)).") ORDER BY lead_id", ARRAY_A  );
// debug('row', $row);
$entry = array();
$item_empty = array();
foreach ($field as $key => $value) {
    $item_empty[$value] = '';
}
foreach ($row as $item) {
    if (!array_key_exists($item['lead_id'], $entry)) {
        $entry[$item['lead_id']] = $item_empty;
    }
    // debug('item', $item);
    if (array_key_exists($item['field_number'], $field)) {
        $entry[$item['lead_id']][$field[$item['field_number']]] = $item['value'];
    }
}
// debug('entry', $entry);

foreach ($entry as $item) {
    if (!empty($item['participation'])) {
        echo(sprintf(
            '<p>%s %s%s%s%s</p>',
            $item['firstname'],
            $item['lastname'],
            !empty($item['nickmane']) ? ' ('.$item['nickmane'].')' : '',
            !empty($item['email']) ? ' <a href="mailto:'.$item['email'].'">'.$item['email'].'</a>': '',
            !empty($item['comments']) ? ' ('.$item['comments'].')' : ''
        ));
    }
}


?>

</div> <?php // #content ?>


<?php get_footer(); ?>
