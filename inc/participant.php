<?php
include_once('library/Debugger.php');
include_once('library/Filesystem.php');
include_once('library/Language.php');
Language::read();
include_once('library/Data.php');
include_once('engine/Participant.php');
Participant::read();

include_once('engine/Navigation.php');
include_once('library/Template.php');

$template = new Template();
$template->set('list', Participant::get_list_alphabetical());
$content = $template->fetch('template/participant_list.php');

$template->clear();
$template->set('content', $content);
echo($template->fetch('template/libregraphicsmeeting.php'));
