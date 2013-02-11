<?php

include_once(TEMPLATEPATH.'/inc/lib/feed.php');

$identica_html = "";
$feed = new Rss_feed();
$feed->set_url("http://identi.ca/api/statusnet/groups/timeline/lgm.rss");
$feed->read();
$identica_item = $feed->get_item();
// $identica_item = array_slice($feed->get_item(), 0, 5);
// debugger('identica_item', $identica_item);
$i = 0;
foreach ($identica_item as $item) {
    $author = preg_replace('/\d/', '',  $item['author']);
    if (!in_array($author, array('fastq', 'usgets', 'ukirane', 'surahmat', 'gosipok', 'sasaras', 'until', 'getsdroid', 'dsodos', 'kaceren', 'colagenperona', 'kumpulbareng', 'jetwinguk', 'lcdfernsehertest', 'plasmafernsehertest', 'matratzenauflageshop', 'torocurvesnowthrower', 'testberichte'))) {
        $i++;
        $identica_html .= "
            <dt><a href=\"".$item['link']."\">".substr($item['title'], 0, strpos($item['title'], ':'))."</a>
            <span>".$item['date']."</span></dt>
            <dd>".$item['description']."</dd>
        ";
    }
    if ($i > 5) {
        break;
    }
}
