#!/usr/bin/php
<?php

    date_default_timezone_set('GMT');

    require_once('Objects/TwitterBot.php');

    header('Content-Type: text/html; charset=utf-8');

    $twitter = new TwitterBot('kLy11JKimycjBMyNBapPbzCuo', 'RpVjXnHi7hsBFYah2pp09LlgNjrdUnrNPMwrECBvnABnesInCJ');

    //$twitter->setToken('732606536518934528-DwDlP9UVEWDNutDI3kUd7txLyKeQy7a', '2PLNeBlCRFeQ8JgADECi3QBv9ssvXh1wUrdiLObBNB4Ld');

    //$twitter->addReply(array('paintball%20playlist -RT'),'(.*)~i','replyBot');

    //$twitter->run();

    //$twitter->test();

    $twitter->postMOC();

?>
