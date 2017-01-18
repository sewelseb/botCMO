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
    private $_maxWPId;

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
            $this->getTagsFromMOC( $item['tags'][0]);
            $article->setTags(implode(',', $item['tags']));

            $articles[$i]=$article;
            $i++;
        }
        //var_dump($articles);

        $this->_articles = $articles;
    }

    public function getTagsFromMOC($tagId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://lecourrierdumaghrebetdelorient.info/wp-json/wp/v2/tags/".$tagId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $parsed_json = curl_exec($ch);
        $tag = json_decode($parsed_json, true);

        echo('tags:');
        var_dump($tag['name']);


    }


    public function insertArticlesInDB()
    {
        $this->getMaxId();
        foreach ($this->_articles as $article)
        {
            if($this->_maxWPId<intval($article->getidWP()))
            {
                $this->_bdd->exec('INSERT INTO article
                                (title, image, category, tags, id_wp)
                                VALUE (\''.$article->getTitle().'\', \''.$article->getImage().'\', \''.$article->getCategory().'\',\''.$article->getTags().'\', \''.$article->getidWP().'\')
                ');
            }
        }
    }

    public function getMaxId()
    {
        $res = $this->_bdd->query('SELECT MAX(id_wp) FROM article');

        if(is_object($res))
        {
            while ($max=$res->fetch(PDO::FETCH_ASSOC))
            {
                //var_dump(intval($max['MAX(id_wp)']));
                $this->_maxWPId=intval($max['MAX(id_wp)']);
            }
        }
    }

    public function selectPostForTwitter()
    {
        $res = $this->_bdd->query('SELECT MIN(id_wp) FROM article WHERE ');

        if(is_object($res))
        {
            while ($max=$res->fetch(PDO::FETCH_ASSOC))
            {
                //var_dump(intval($max['MAX(id_wp)']));

            }
        }
    }
}