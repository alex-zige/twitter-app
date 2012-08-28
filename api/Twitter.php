<?php

class Twitter {

    protected static $base_url = "https://api.twitter.com/1";

    protected static $screen_name;

    public static function getFollowersbyScreenName($screen_name){

    //init a restful client. 
     $twitter = new RestClient(
                array('base_url' => self::$base_url)
           	 );

     $result = $twitter->get("followers/ids.json?cursor=-1&screen_name=".$screen_name);

    return  $result;
    }

    public static function customDB(){

       if(strpos($_SERVER['DOCUMENT_ROOT'], 'alex') != false){
                //query the string 
                $db = DB::open('twitter','localhost','root','root');

              }else{
       
                $db = DB::open('twitter','localhost','root','');

              }

        return $db;
    }

} 