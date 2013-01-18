<?php
/**
 * Template Name: LGM participants list
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

<div id="content" role="main">

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
$row = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE form_id = 1 AND field_number IN (19, 20, 4, 31, 5, 22, 26) ORDER BY lead_id DESC", ARRAY_A  );
// debug('row', $row);
$entry = array();
foreach ($row as $item) {
    if (!array_key_exists($item['lead_id'], $entry)) {
        $entry[$item['lead_id']] = array (
            'firstname' => '',
            'lastname' => '',
            'nickmane' => '',
            'project' => '',
            'role' => '',
            'url' => '',
            'show' => '',
        );
    }
    // debug('item', $item);
    if ((($item['field_number'] != 31) || (strlen($item['value']) > 8)) && ($item['value'] == strtoupper($item['value']))) {
        $item['value'] = ucwords(strtolower($item['value']));
    }
    switch ($item['field_number']) {
        case 19 :
            $entry[$item['lead_id']]['firstname'] = $item['value'];
        break;
        case 20 :
            $entry[$item['lead_id']]['lastname'] = $item['value'];
        break;
        case 5 :
            $entry[$item['lead_id']]['nickmane'] = $item['value'];
        break;
        case 31 :
            $entry[$item['lead_id']]['project'] = $item['value'];
        break;
        case 4 :
            $entry[$item['lead_id']]['role'] = $item['value'];
        break;
        case 22 :
            $entry[$item['lead_id']]['url'] = str_replace('http://', '', $item['value']);
        break;
        case 26 :
            $entry[$item['lead_id']]['show'] = substr($item['value'], 0, 3) == 'Yes';
        break;
    }
}
// debug('entry', $entry);

foreach ($entry as $item) {
    if ($item['show']) {
        echo('<p>'.(!empty($item['url']) ? '<a href="http://'.$item['url'].'">': '').$item['firstname'].' '.$item['lastname'].(!empty($item['url']) ? '</a>': '').(!empty($item['nickmane']) ? ' ('.$item['nickmane'].')' : '').'<br />'.$item['project'].(!empty($item['role']) ? ' ('.$item['role'].')' : '').'</p>');
    }
}


?>

</div> <?php // #content ?>


<?php get_footer(); ?>
