<?php

$app_access_token = 'YOUR_APP_TOKEN';// Get it from here: https://developers.facebook.com/docs/opengraph/howtos/publishing-with-app-token/
 $template = 'My Wonderful App notification to all!';//Change this to your own message. More about templating here: https://developers.facebook.com/docs/concepts/notifications/#impl
 $user_id = 4;//fb_id

$url = "https://graph.facebook.com/".$user_id."/notifications";

  $params = array('http' => array(
	 'method' => 'POST',
	 'access_token' => $app_access_token,
       'template' => http_build_query($template)
            ));

  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  echo $response;

?>
