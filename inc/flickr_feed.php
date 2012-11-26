<?php

include_once(TEMPLATEPATH.'/inc/lib/phpFlickr/phpFlickr.php');

$flickr = new phpFlickr('c10ac6b9a528c33e71bebe2c04b6bc84');
// $flickr_lgm = $flickr->groups_pools_getPhotos('c10ac6b9a528c33e71bebe2c04b6bc84', '776360@N22');
$flickr_n = 12;
$flickr_m = 3;

$flickr_page = 1000; // way too big: it will return no matches... but also the number of available pages
$flickr_lgm = array('photo' => array());
for ($i = 0; $i < 2; $i++) {
    $flickr_lgm = $flickr->groups_pools_getPhotos('776360@N22', null, null, null, $flickr_n, $flickr_page);
    $flickr_page = rand(1, $flickr_lgm['pages'] - 1); // never show the last pages as it may be not complete
}
$flickr_icon = array();
foreach ($flickr_lgm['photo'] as $item) {
    $flickr_icon[] = array (
        'href' => 'http://www.flickr.com/photos/'.$item['owner'].'/'.$item['id'].'/in/pool-776360@N22/',
        'img' => 'http://farm'.$item['farm'].'.static.flickr.com/'.$item['server'].'/'.$item['id'].'_'.$item['secret'].'_s.jpg',
    );
}

// $debug = print_r($flickr_lgm, 1);

$flickr_html = "";
for ($i = 0; $i < $flickr_n / $flickr_m; $i++) {
    // $flickr_html .= "<p>";
    for ($j = 0; $j < $flickr_m; $j++) {
        $flickr_html .= '<a href="'.$flickr_icon[($flickr_m*$i)+$j]['href'].'"><img src="'.$flickr_icon[($flickr_m*$i)+$j]['img'].'" /></a>';

    } // for j
    // $flickr_html .= "<p>\n";
    $flickr_html .= "\n";
} // for i
