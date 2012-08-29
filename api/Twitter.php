<?php

class Twitter {

    protected static $db_name = 'twitter';

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

    public static function updates($twitter_user = 'galaxy_watcher', $array_values=''){

    //  UPDATE `twitter`.`twitter_user` SET `requests` = '2' WHERE `twitter_user`.`ID` =6;

      $array_values = array(

        'requests'=>'2',

        );
     $element='';

     $sql = "UPDATE `twitter`.`twitter_user` SET";

      if(is_array($array_values)){

        foreach ($array_values as $key => $value) {

         $element .="`".$key."` = '".$value."',";

        }

      
       $element .= rtrim($element,',');

        $sql .=" WHERE `twitter_user`.`ID` =6;";

        echo $sql;

      }else{




      }



    }

} 