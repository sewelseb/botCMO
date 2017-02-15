<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 17-05-16
 * Time: 19:43
 */

require_once ('ArticlesManager.php');



class TwitterBot
{

    private $oauth;
    private $replies = array();
    private $screenName;

    protected $url_update = 'https://api.twitter.com/1.1/statuses/update.json';
    protected $url_search = 'https://api.twitter.com/1.1/search/tweets.json?q=%s&result_type=recent&count=50&since_id=%s';
    protected $url_verify = 'https://api.twitter.com/1.1/account/verify_credentials.json';
    protected $url_token = 'https://twitter.com/oauth/request_token';
    protected $url_token_access = 'https://twitter.com/oauth/access_token';
    protected $url_auth = 'http://twitter.com/oauth/authorize';



    public function __construct($key, $secret){

        try{
            //$this->oauth = new OAuth($key, $secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
            $this->oauth = new OAuth($key, $secret);
        }
        catch (Exception $e)
        {
            var_dump($e);
        }


        $this->oauth->disableSSLChecks();

    }



    public function setToken($token, $secret){

        $this->oauth->setToken($token, $secret);

    }


    /*
     * twit de hello world
     */
    public function test(){

        $array = array(

            'status' => 'Hello World !'

        );

        $this->oauth->fetch($this->url_update, $array, OAUTH_HTTP_METHOD_POST);

    }

    public function addReply($terms,$regex,$type){
        $this->replies[] = array('terms' => $terms,'regex' => $regex,'type' => $type);
    }

    public function run(){
        $since_id = $this->getSinceId();



        $max_id = $since_id;

        //echo ('lancement de la fonction run');

        if ($this->verifyAccountWorks()){
            //echo ('passage du if');
            /* For each request on tweet.php */

            foreach ($this->replies as $key => $t){
                //echo ('passage boucle');
                /* find every tweet since last ID, or the maximum lasts tweets if no since_id */

                $this->oauth->fetch(sprintf($this->url_search, urlencode($t['terms'][0]), $since_id));

                $search = json_decode($this->oauth->getLastResponse());

                if($search){

                    //echo 'Terms #'.$key.' : '.count($search->statuses).' found(s)'."\n<br>";

                    /* Store the last max ID */

                    if ($search->search_metadata->max_id_str > $max_id){

                        $max_id = $search->search_metadata->max_id_str;

                    }



                    $i = 0;

                    foreach ($search->statuses as $tweet){

                        //echo '<b><a href="https://twitter.com/'.$tweet->user->screen_name.'" target="_blank" style="color:red">@'.$tweet->user->screen_name.'</a> :</b> <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'" target="_blank" style="color:black;text-decoration:none">'.$tweet->text.'</a></b><br>';

                        $tweetTab = explode('minecraft', strtolower ($tweet->text));
                        if(count($tweetTab)<2 && $tweet->user->screen_name!="Xtrem_Sport_Vid")
                        {

                            $this->sendReply($tweet, $t);
                        }


                    }

                    //echo 'Terms #'.$key.' : '.$i.' valid(s)'."\n<br>";

                }




                /* wait 100ms */

                usleep(100000);



            }



            /* setting new max id */

            $this->setSinceId($max_id);

            //echo '========= ', date('Y-m-d g:i:s A'), ' - Finished ========='."\n<br>";;

        }
    }

    public function getSinceId($file='since_id'){
        $since_id = @file_get_contents($file);
        if(!$since_id){
            $since_id = 0;
        }
        return $since_id;
    }

    public function setSinceId($max_id=null,$file='since_id'){
        file_put_contents($file, $max_id);
    }

    private function verifyAccountWorks(){
        try{
            $this->oauth->fetch($this->url_verify, array(), OAUTH_HTTP_METHOD_GET);
            $response = json_decode($this->oauth->getLastResponse());
            $this->screenName = $response->screen_name;
            return true;
        }catch(Exception $ex){
            return false;
        }
    }

    private function sendReply($tweet, $tab, $nodie=false){

        $m3 = [' ☺',' ☺',' 😏',' 😳',':)','!',';)','!'];
        $reply = 'Check our videos if you want to extend your #paintball playlist on YouTube ! '.$m3[array_rand($m3)]." http://www.youtube.com/c/SMILEPaintballNetwork?sub_confirmation=1";
        echo 'destiation: '.$tweet->user->screen_name.' \n'.'tweet: '.$tweet->text.'\n'.'response: '.$reply.'\n';


        try{
            $this->oauth->fetch($this->url_update, array('status' => '@'.$tweet->user->screen_name.' '.$reply,'in_reply_to_status_id' => $tweet->id_str,), OAUTH_HTTP_METHOD_POST);
        }catch(OAuthException $ex){
            echo 'ERROR: '.$ex->lastResponse;
            if(!$nodie){
                die();
            }
        }
    }

    public function postMOC(PDO $bdd){
        $articleManager = new ArticlesManager($bdd);
        $articleManager->getPostsFromMOC();
        $articleManager->insertArticlesInDB();
    }

    public function buildPost(Article $article)
    {
        $tags = ' #MOC';
        foreach ($article->getTags() as $tag)
        {
            $tags = $tags." #".$tag;
        }
        $array = array(

            'status' => $article->getTitle().' http:\/\/lecourrierdumaghrebetdelorient.info\/?p='.$article->getId().$tags

        );

        var_dump($array);
        if ($this->verifyAccountWorks()){

        }
        else
        {
            echo('account twitter do not work');
        }

        $this->oauth->fetch($this->url_update, $array, OAUTH_HTTP_METHOD_POST);
    }
}