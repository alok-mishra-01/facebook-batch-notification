<?php
 
/*

This is a short standalone script to post batch notification to all your facebook canvas app users in one shot. I had to send a app notification to 10,000 odd users at a time. I could not find a similar script in php anywhere, so I had to fire up emacs to write one myself. Hope it helps someone in need.

Features:
- Splits all your users into smaller arrays to sends it to the facebook batch api
- Puts the correct json template format and posts the notification to the users
- Doesnt need cURL

Caution:
Since this script is meant to be run from php command line or directly from web root directory, you may face the timeout error for php if you have more than 500 users or so. 

To be on the safer side, edit your 'max_execution_time' value in php.ini to a very high value, since facebook posts them 50 at a time.


Original Version: 
Alok Mishra
12 July 2013

V0.1: Initial tested working version with manual one file settings


*/


$app_access_token = 'YOUR_APP_TOKEN';// Get it from here: https://developers.facebook.com/docs/opengraph/howtos/publishing-with-app-token/

$id = 'POST';
$body = 'template= My Wonderful App notification to all!'; //Change this to your own message. More about templating here: https://developers.facebook.com/docs/concepts/notifications/#impl

$arrayofids = array('1','1234','11111111');// Put all the facebook ids of your users here in this format



$facebooklimit = 39;// It should be 50, I have kept it lower just to keep it safe

$limit = intval(sizeof($arrayofids)/$facebooklimit);



// Split array into parts specified by $limit
$limit = ceil(count($arrayofids) / $limit);
$array = array_chunk($arrayofids, $limit);
echo '<pre>';
print_r($array);
echo '<pre>';

$facebooklimit++;

for($k = 0; $k < $facebooklimit ; $k++)
{

$userdata = array();
    for ($i = 0; $i <= 40; $i++) 
    {

        $a = (string)($array[$k][$i]).'/notifications';
echo $a;
echo "<br>";
        $userdata[] = array(
           'method' => $id, 'relative_url' => $a, 'body' => $body
        );

    }

    $template = "";

    $template = json_encode($userdata);

   echo $template;
    $postdata = http_build_query(
        array(
         'access_token' => $app_access_token,
           'batch' => $template
        )
    );
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);


    $result = file_get_contents('https://graph.facebook.com/', false, $context);



    echo $result;

echo "<br>";
echo "<br>";
}
 
?>
