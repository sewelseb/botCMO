#!/usr/bin/php
<?php

    date_default_timezone_set('GMT');

    require_once ('connexionDB.php');
    require_once('Objects/TwitterBot.php');

    header('Content-Type: text/html; charset=utf-8');

    $twitter = new TwitterBot('2463782784-2sW0z6FysgOXl3aLBFqGCkmdw5yFLvG2oGh4tQI', 'OjLa6xFyozTiPGKVAtt3AZ5nXoFHR4PPunPGIlMt9N5Wh');

    //$twitter->setToken('732606536518934528-DwDlP9UVEWDNutDI3kUd7txLyKeQy7a', '2PLNeBlCRFeQ8JgADECi3QBv9ssvXh1wUrdiLObBNB4Ld');

    //$twitter->addReply(array('paintball%20playlist -RT'),'(.*)~i','replyBot');

    //$twitter->run();

    //$twitter->test();

    $twitter->postMOC($bdd);
    $articlesManager = new ArticlesManager($bdd);
    $stockOfArticles = $articlesManager->getArticlesToPost();



    $articleToPost = $articlesManager->prepareArticleForPosting($stockOfArticles[0]);

    var_dump($articleToPost->getImage());

    $twitter->buildPost($articleToPost);

?>
