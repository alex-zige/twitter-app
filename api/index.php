<?php

require  'Slim/Slim.php';

require  'RestClient.php';

require  'DB.php';

require  'Twitter.php';

$app = new Slim();

//GET route
$app->get('/', function () {

  // this is the default controllers
});

//put update the current status and save into database as beechmark
$app->put('/twitter/:twitter_name','updateCurrentList');


//POST route create new account
$app->post('/twitter/:twitter_name', 'createNewAccount');

  //update the twitter name
    function updateCurrentList($twitter_name){

      //open up custom twitter DB
      $db = Twitter::customDB();

      //fire up the twitter request
      $result = Twitter::getFollowersbyScreenName($twitter_name);

      if($result->info->http_code == 200) {

      $results_raw = json_decode($result->response);
              
      $followers = json_encode($results_raw->ids);

      $raw_sql_update = "Update `twitter_user` SET `followers` = ".$followers." WHERE username =".$twitter_name;
   
      $db->qry($raw_sql_update);
      
      echo json_encode(array('success_code'=>'202'));

      }else{

      //update current version failed
      echo json_encode(array('error_code'=>'300'));

      }
  }

//expose web services  - GET
$app->get('/twitter/:twitter_name', 'getFollowers');

        function getFollowers($twitter_name){

        $result = Twitter::getFollowersbyScreenName($twitter_name);

        // if response okay.
        if($result->info->http_code == 200){

            $results_raw = json_decode($result->response);
            
            $followers = json_encode($results_raw->ids);

            //open up custom twitter DB
            $db = Twitter::customDB();

            $raw_sql_select = "SELECT *
            FROM `twitter_user`
            WHERE `username` LIKE '".$twitter_name."'";
        
            $flag = $db->qry($raw_sql_select,2);

            if($flag){

              //add the request + 1 => recording how many times the users used the services.
              $request = $flag['requests']+1;

              //make up the insert the query
              $update_request = array( 'requests' =>$request);


              $sql_insert = Twitter::generateUpdateSQL($twitter_name,$update_request);

              //die if run into problems.
              $sql_insert = (!$sql_insert=='') ? $sql_insert : die() ;

              // try to update the requests
              try {

               $db->qry($sql_insert);

              }
              catch (MYSQLException $e) {

               throw new Exception("Error insert the results");
                
              }
            
              $recorded_followers = $flag['followers'];

             compare($followers,$recorded_followers);

            }else{
    
            //error code 100 -> new user
            echo json_encode(array('error_code'=>'100'));

            }

      }else{
            //error code 404 -> cannot be found.
           echo json_encode(array('error_code'=>'404'));
    }
 }

/**
 * createNewAccount 
 *
 * @return JSON object with the latest unfollower.
 * @author Alex
 **/
function createNewAccount($twitter_name){


     //open up custom twitter DB
      $db = Twitter::customDB();

      $result = Twitter::getFollowersbyScreenName($twitter_name);

      if($result->info->http_code == 200) {

        $results_raw = json_decode($result->response);
            
        $followers = json_encode($results_raw->ids);

       //insert new create.
        $raw_sql_insert = "INSERT INTO `twitter`.`twitter_user` (`username`, `followers`, `fetchdate`, `requests`) VALUES ('{$twitter_name}', '{$followers}', '".date('Y-m-d H:i:s')."', '1')";
      
        $db->qry($raw_sql_insert);   

      //cannot init new users
      echo json_encode(array('success_code'=>'200'));

      }else{

      //cannot init new users
      echo json_encode(array('error_code'=>'200'));
      
      }

}
 
/**
 * compare the latest fetched array with the one that saved in db
 *
 * @return JSON object with the latest unfollower.
 * @author Alex
 **/

function compare($new,$old){

//$new = '[583633090,55178721,62406261,33170795,601586684,43453917,510013035,27183807,54434162,125846812,278971707,618476068,610599390,617961266,15914878,65777523,52688800,115309244,155857509,22518911,412420143,136041780,198085210,351258629,23715681,32238395,30386355,21175470,57201333,6033502,539950041,404225553,72711531,416236105,38411747,173508940,76458984,22458363,480797842,8017902,14157397,20022032,257302388,12850202,394264632,516466569,372075189,17230428,164911007,17504672,160314049,251823770,144774872,15495933,17799865,326380032,491650666,493733490,468235680,275354971,144092431,464584797,93278285,33510065,21146176,455410718,462625919,14841150,456787060,449918715,251748249,207706993,259457807,14205596,414615735,412812945,89087173,137166707,273757191,29054405,189499763,32499999,301691143,72473108,123058968,126742384,28211808,321034678,350854236,271245672,99781591,370518465,198766354,93824535,344861541,24443324,281426987,97662665,72494430,11555082,340732449,247056905,23789698,15775923,142491371,105050669,253097229,10718322,61703169,14906611,323674367,325609901,325604313,214749192,811900,53695514,16362921,312581359,131676397,246140338,260259862,17987951,17262774,39001516,245995767,7416512,30178047,82490614,65224345,25380150]';

//$old = '[368479258,583633090,55178721,62406261,33170795,601586684,43453917,510013035,27183807,54434162,125846812,278971707,618476068,610599390,617961266,15914878,65777523,52688800,115309244,155857509,22518911,412420143,136041780,198085210,351258629,23715681,32238395,30386355,21175470,57201333,6033502,539950041,404225553,72711531,416236105,38411747,173508940,76458984,22458363,480797842,8017902,14157397,20022032,257302388,12850202,394264632,516466569,372075189,17230428,164911007,17504672,160314049,251823770,144774872,15495933,17799865,326380032,491650666,493733490,468235680,275354971,144092431,464584797,93278285,33510065,21146176,455410718,462625919,14841150,456787060,449918715,251748249,207706993,259457807,14205596,414615735,412812945,89087173,137166707,273757191,29054405,189499763,32499999,301691143,72473108,123058968,126742384,28211808,321034678,350854236,271245672,99781591,370518465,198766354,93824535,344861541,24443324,281426987,97662665,72494430,11555082,340732449,247056905,23789698,15775923,142491371,105050669,253097229,10718322,61703169,14906611,323674367,325609901,325604313,214749192,811900,53695514,16362921,312581359,131676397,246140338,260259862,17987951,17262774,39001516,245995767,7416512,30178047,82490614,65224345,25380150]';

    $array_new = json_decode($new); // one - unfollower 

    $array_old = json_decode($old);
                         
    $unfollowers_ids = array_diff($array_old,$array_new);

   // var_dump($unfollowers_ids);

    if(!empty($unfollowers_ids))
    {
        //value has point of differents
       $unfollowers = getUnfollowersProfile($unfollowers_ids);

    echo json_encode($unfollowers);
    
    }else{

    echo json_encode(array('success_code'=>'101'));

    }
    //finding out what's missing in new.
 }


/**
 * Get unfollowers profroile by accessing the through the twitter API calls.
 * @param  array
 * @return JSON object with the latest unfollower.
 * @author Alex
 **/

function getUnfollowersProfile($unfollowers_ids){

     $unfollowers = array();

     //key=>value loop
   foreach ($unfollowers_ids as $unfollowers_id) {
      
    $unfollower = runTwitterAPI($unfollowers_id);

   }

     array_push($unfollowers, $unfollower);

    return $unfollowers;
}

function runTwitterAPI($id){

   $twitter = new RestClient(array(
            'base_url' => "https://api.twitter.com/1")
        );

    //echo $id;

        $result = $twitter->get("users/show.json?user_id=".$id);

        if($result->info->http_code == 200)
            {
        $unfollower = json_decode($result->response);
            }else{

        $unfollower = array('error_code'=>'500');      

            }

        return $unfollower;
}


//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});

$app->run();
