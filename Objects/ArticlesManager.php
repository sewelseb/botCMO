<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 09-12-16
 * Time: 09:47
 */

require_once ('Article.php');

class ArticlesManager
{
    public function getPostsFromMOC()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://lecourrierdumaghrebetdelorient.info/wp-json/wp/v2/posts");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $parsed_json = curl_exec($ch);
        $parsed_json = json_decode($parsed_json, true);


        $articles = array();
        $i=0;
        foreach ($parsed_json as $item)
        {
            //var_dump($item);
            $article = new Article();
            $article->setId($item);
            $article->setTitle($item['title']);

            $articles[$i]=$article;
            $i++;
        }
        var_dump($articles);
    }
}