<?php
/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
require 'Slim/Slim.php';

require  'RestClient.php';

require  'DB.php';

$app = new Slim();

//GET route
$app->get('/', function () {
    $template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Twitter unfollowers</title>
            <style>
                html,body,div,span,object,iframe,
                h1,h2,h3,h4,h5,h6,p,blockquote,pre,
                abbr,address,cite,code,
                del,dfn,em,img,ins,kbd,q,samp,
                small,strong,sub,sup,var,
                b,i,
                dl,dt,dd,ol,ul,li,
                fieldset,form,label,legend,
                table,caption,tbody,tfoot,thead,tr,th,td,
                article,aside,canvas,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section,summary,
                time,mark,audio,video{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;}
                body{line-height:1;}
                article,aside,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section{display:block;}
                nav ul{list-style:none;}
                blockquote,q{quotes:none;}
                blockquote:before,blockquote:after,
                q:before,q:after{content:'';content:none;}
                a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;}
                ins{background-color:#ff9;color:#000;text-decoration:none;}
                mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold;}
                del{text-decoration:line-through;}
                abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help;}
                table{border-collapse:collapse;border-spacing:0;}
                hr{display:block;height:1px;border:0;border-top:1px solid #cccccc;margin:1em 0;padding:0;}
                input,select{vertical-align:middle;}
                html{ background: #EDEDED; height: 100%; }
                body{background:#FFF;margin:0 auto;min-height:100%;padding:0 30px;width:440px;color:#666;font:14px/23px Arial,Verdana,sans-serif;}
                h1,h2,h3,p,ul,ol,form,section{margin:0 0 20px 0;}
                h1{color:#333;font-size:20px;}
                h2,h3{color:#333;font-size:14px;}
                h3{margin:0;font-size:12px;font-weight:bold;}
                ul,ol{list-style-position:inside;color:#999;}
                ul{list-style-type:square;}
                code,kbd{background:#EEE;border:1px solid #DDD;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:0 4px;color:#666;font-size:12px;}
                pre{background:#EEE;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#666;font-size:12px;}
                pre code{background:transparent;border:none;padding:0;}
                a{color:#70a23e;}
                header{padding: 30px 0;text-align:center;}
            </style>
        </head>
        <body>
            <header></header>
            <h1>Twitter unfollowers</h1>           
        </body>
    </html>
EOT;
    echo $template;
});


//expose web services

$app->get('/twitter/:twitter_name', 'getFollowers');

        function getFollowers($twitter_name){

        $twiiter = new RestClient(array(
            'base_url' => "https://api.twitter.com/1")
        );

        $result = $twiiter->get("followers/ids.json?cursor=-1&screen_name=".$twitter_name);

        if($result->info->http_code == 200)
            
            {
        
        $results_raw = json_decode($result->response);

            }

        $followers = json_encode($results_raw->ids);

        //query the string 
        $db = DB::open();

     $raw_sql_select = "SELECT *
    FROM `twitter_user`
    WHERE `username` LIKE '".$twitter_name."'";

//    echo $raw_sql_select;
      
        $flag = $db->qry($raw_sql_select,2);

        if(!is_null($flag)){

          $recorded_followers = $flag['followers'];

          $latest_followers = $recorded_followers;

            compare($latest_followers,$recorded_followers);

        }else{

        $raw_sql_insert = "INSERT INTO `twitter`.`twitter_user` (`username`, `followers`, `fetchdate`, `requests`) VALUES ('{$twitter_name}', '{$followers}', '".date('Y-m-d H:i:s')."', '1')";

        $db->qry($raw_sql_insert);

   //     echo "\n".$raw_sql_insert;

//   echo "total".count($followers);
        }

     }

function compare($new,$old){

$new = '[55178721,62406261,33170795,601586684,43453917,510013035,27183807,54434162,125846812,278971707,618476068,610599390,617961266,15914878,65777523,52688800,115309244,155857509,22518911,412420143,136041780,198085210,351258629,23715681,32238395,30386355,21175470,57201333,6033502,539950041,404225553,72711531,416236105,38411747,173508940,76458984,22458363,480797842,8017902,14157397,20022032,257302388,12850202,394264632,516466569,372075189,17230428,164911007,17504672,160314049,251823770,144774872,15495933,17799865,326380032,491650666,493733490,468235680,275354971,144092431,464584797,93278285,33510065,21146176,455410718,462625919,14841150,456787060,449918715,251748249,207706993,259457807,14205596,414615735,412812945,89087173,137166707,273757191,29054405,189499763,32499999,301691143,72473108,123058968,126742384,28211808,321034678,350854236,271245672,99781591,370518465,198766354,93824535,344861541,24443324,281426987,97662665,72494430,11555082,340732449,247056905,23789698,15775923,142491371,105050669,253097229,10718322,61703169,14906611,323674367,325609901,325604313,214749192,811900,53695514,16362921,312581359,131676397,246140338,260259862,17987951,17262774,39001516,245995767,7416512,30178047,82490614,65224345,25380150]';

$old = '[368479258,583633090,55178721,62406261,33170795,601586684,43453917,510013035,27183807,54434162,125846812,278971707,618476068,610599390,617961266,15914878,65777523,52688800,115309244,155857509,22518911,412420143,136041780,198085210,351258629,23715681,32238395,30386355,21175470,57201333,6033502,539950041,404225553,72711531,416236105,38411747,173508940,76458984,22458363,480797842,8017902,14157397,20022032,257302388,12850202,394264632,516466569,372075189,17230428,164911007,17504672,160314049,251823770,144774872,15495933,17799865,326380032,491650666,493733490,468235680,275354971,144092431,464584797,93278285,33510065,21146176,455410718,462625919,14841150,456787060,449918715,251748249,207706993,259457807,14205596,414615735,412812945,89087173,137166707,273757191,29054405,189499763,32499999,301691143,72473108,123058968,126742384,28211808,321034678,350854236,271245672,99781591,370518465,198766354,93824535,344861541,24443324,281426987,97662665,72494430,11555082,340732449,247056905,23789698,15775923,142491371,105050669,253097229,10718322,61703169,14906611,323674367,325609901,325604313,214749192,811900,53695514,16362921,312581359,131676397,246140338,260259862,17987951,17262774,39001516,245995767,7416512,30178047,82490614,65224345,25380150]';


$array_new = json_decode($new); // one - unfollower 

$array_old = json_decode($old);

$unfollowers_ids = array_diff($array_old, $array_new);


if(!empty($unfollowers_ids))
{

    //value has point of differents
    $unfollowers = getUnfollowersProfile($unfollowers_ids);

    echo json_encode($unfollowers);
}
//finding out what's missing in new.
}

function getUnfollowersProfile($unfollowers_ids){

     $unfollowers = array();

    for ($i=0; $i < count($unfollowers_ids); $i++) { 

    $unfollower = runTwitterAPI($unfollowers_ids[$i]);

    array_push($unfollowers, $unfollower);

    }

  //  var_dump($unfollowers);

    return $unfollowers;
}

function runTwitterAPI($id){

   $twiiter = new RestClient(array(
            'base_url' => "https://api.twitter.com/1")
        );

        $result = $twiiter->get("users/show.json?user_id=".$id);

        if($result->info->http_code == 200)
            {
        $unfollower = json_decode($result->response);
            }

   // var_dump($unfollower);
        return $unfollower;
}

//POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});

$app->run();
