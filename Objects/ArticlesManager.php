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
    private $_bdd;
    private $_articles;

    function __construct(PDO $bdd)
    {
        $this->_bdd = $bdd;
    }

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

            //$article->setId($item['id']);
            //print_r($item['title']['rendered']);
            $article->setTitle($item['title']['rendered']);
            $article->setCategory(explode('-', $item['title']['rendered'])[0]);
            $article->setIdWP($item['id']);

            $articles[$i]=$article;
            $i++;
        }
        var_dump($articles);

        $this->_articles = $articles;
    }

    public function insertArticlesInDB()
    {
        foreach ($this->_articles as $article)
        {
            $this->_bdd->exec('INSERT INTO article
                                (title, image, category, tags, id_wp)
                                VALUE (\''.$article->getTitle().'\', \''.$article->getImage().'\', \''.$article->getCategory().'\',\''.$article->getTags().'\', \''.$article->getidWP().'\')
            ');
        }
    }

    public function getMaxId()
    {
        $this->_bdd->exec('SELECT max(wp_id) FROM article');
    }
}