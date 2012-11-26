<?php
// ini_set('display_errors', '1'); 
// error_reporting(E_ALL);
include_once('library/Debugger.php');
include_once('library/Language.php');
Language::read();
include_once('library/Filesystem.php');
include_once('library/Data.php');


include_once('engine/Navigation.php');
include_once('library/Template.php');
$template = new Template();



if (true) {  // change true to false to disable the form
    // if (!empty($_POST) && isset($_POST['save']) && (!empty($_POST['lastname']) || !empty($_POST['firstname'] || !empty($_POST['nickname'])))) {
    if (!empty($_POST) && array_key_exists('save', $_POST) && (array_key_exists('lastname', $_POST) && array_key_exists('firstname', $_POST) && array_key_exists('nickname', $_POST)) && (($_POST['lastname'] != '') || ($_POST['firstname'] != '') || ($_POST['nickname'] != '') ) ) {
        // Debugger::structure('_POST', $_POST);
        include_once('engine/Participant.php');
        Participant::read();
        $participant = Participant::register($_POST['id']);
        $content = $template->fetch('data/signup_confirmation_en.php');
        // $content = Data::read('data/signup_confirmation_'.Language::get().'php', false);
        // Debugger::structure('content', $content);

        // $mail_to = 'ale.comp_06@xox.ch';
        // $mail_to = 'a.l.e@ideale.ch';
        $mail_subject = sprintf(
            'Registration: %s %s',
            $participant['firstname'],
            $participant['lastname']
        );
        $mail_body = sprintf(
            '%s %s has registered for LGM 2010'."\n".
            'you may edit his/her profile by following this link'."\n".
            'http://www.libregraphicsmeeting.org/admin/participant.php?id=%d',
            $participant['firstname'],
            $participant['lastname'],
            $participant['id']
        );
        if (empty($participant['email'])) {
            $mail_from = 'ale.comp_06@xox.ch';
            $mail_from_name = $participant['firstname'].' '.$participant['lastname'];
        } else {
            $mail_from = $participant['email'];
        }

        $mail_header = array (
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' .$mail_from,
            'Reply-To: ' .$mail_from,
        );

        // mail($mail_to, $mail_subject, $mail_body, implode("\r\n", $mail_header)."\r\n");
        // Debugger::structure('mail()', mail('ale.comp_06@xox.ch', 'test', 'just a test', null, '-fa.l.e@ideale.ch'));
        // see PHPMailer/examples/test_smtp_basic.php
        require_once('PHPMailer/class.phpmailer.php');
        $mail = new PHPMailer();
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host       = "10.1.0.100"; // SMTP server
        $mail->SetFrom('ale.comp_06@xox.ch', 'LGM signup form');
        $mail->AddReplyTo($mail_from, $mail_from_name);
        $mail->Subject    = $mail_subject;
        $mail->Body    = $mail_body;
        $address = "ale.comp_06@xox.ch";
        $mail->AddAddress($address, "a.l.e");
        $mail->Send();
    } else {
        $calendar = Data::read('data/calendar.php', false);
        $day = Data::read('data/day.php', false);
        $day = Language::get_array($day);
        $month = Data::read('data/month.php', false);
        $month = Language::get_array($month);
        $date = Data::read('data/date.php', false);
        $date = Language::get_array($date);
        $project = Data::read('data/project.php', false);
        // Debugger::structure('project', $project);
        // Debugger::structure('language', Language::get());
        // Debugger::structure('calendar', $calendar);
        if (is_array($project)) {
        $list = array_keys($project);
        } else {
          $list = array();
        }
        sort($list);
        $template->set('project', $list);
        $template->set('calendar', $calendar);
        $template->set('day', $day);
        $template->set('date', $date);
        $template->set('month', $month);
        $template->set('date_format', Language::get() == 'en' ? '%1$s %3$s %2$d' : '%1$s %2$d %3$s');
        $form = $template->fetch('template/participant_form.php');
        $template->clear();
        // $content = file_get_contents('template/signup_'.Language::get().'.php');
        $template->set('form', $form);
        $content = $template->fetch('template/participant_signup.php');
    }
} else {
    $content = $template->fetch('template/participant_nosignup.php');
} // if true/false
$template->clear();
// Debugger::structure('content', $content);
$template->set('content', $content);
echo($template->fetch('template/libregraphicsmeeting.php'));
