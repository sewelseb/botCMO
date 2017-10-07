#!/usr/bin/php
<?php

    date_default_timezone_set('GMT');

    require_once ('connexionDB.php');
    require_once('Objects/TwitterBot.php');
    require_once ('Objects/Codebird.php');

    \Codebird\Codebird::setConsumerKey('9yU40IvxY8dufevOjNGsq8one', '5nVh7BQCoogGwOOax5QfgsenSc1KP2pK9HwzGsg7WhNqJWe64k');
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken('2463782784-2sW0z6FysgOXl3aLBFqGCkmdw5yFLvG2oGh4tQI', 'OjLa6xFyozTiPGKVAtt3AZ5nXoFHR4PPunPGIlMt9N5Wh');

    $twitter = new TwitterBot($cb);
    header('Content-Type: text/html; charset=utf-8');

//    $twitter = new TwitterBot('9yU40IvxY8dufevOjNGsq8one', '5nVh7BQCoogGwOOax5QfgsenSc1KP2pK9HwzGsg7WhNqJWe64k');
//
//    $twitter->setToken('2463782784-2sW0z6FysgOXl3aLBFqGCkmdw5yFLvG2oGh4tQI', 'OjLa6xFyozTiPGKVAtt3AZ5nXoFHR4PPunPGIlMt9N5Wh');

    //$twitter->addReply(array('paintball%20playlist -RT'),'(.*)~i','replyBot');

    //$twitter->run();

    //$twitter->test();

    $twitter->postMOC($bdd);
    $articlesManager = new ArticlesManager($bdd);
    $stockOfArticles = $articlesManager->getArticlesToPost();



    $articleToPost = $articlesManager->prepareArticleForPosting($stockOfArticles[0]);

    var_dump($articleToPost->getImage());

    $twitter->buildPost($articleToPost);

    $articlesManager->setPostAsPublished($articleToPost);

?>
