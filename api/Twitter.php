<?php

class Twitter {

    protected static $db_name = 'twitter';

    protected static $table_name = 'twitter_user';

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
    public static function generateUpdateSQL($twitter_user = 'galaxy_watcher', $array_values=''){
  
       $element = '';

       $sql = "UPDATE `".self::$db_name."`.`".self::$table_name."` SET ";

       //only if valid formate.
      if(is_array($array_values) && $twitter_user!=""){

        foreach ($array_values as $key => $value) {

         $element .="`".$key."` = '".$value."',";

        }
       //trim the last char commda.
       $element = rtrim($element,',');

      $sql .= $element." WHERE `twitter_user`.`username` = '".$twitter_user."';";

      }else{

        $sql =null;

      }
      return $sql;

    }

} 