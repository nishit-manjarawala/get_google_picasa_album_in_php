<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '941558839068-4tmp4i0n5u093ms11misj0v25meubmd9.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'B9U9heGmDppLY9RY_tX7MHht'; //Google client secret
$redirectURL = 'http://localhost:3000/get_google_picasa_album_in_php/'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to CodexWorld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);
$gClient->setScopes(array('https://picasaweb.google.com/data/','https://www.googleapis.com/auth/plus.login','https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/drive'));
//$gClient->setScopes(array('https://picasaweb.google.com/data/'));
$google_oauthV2 = new Google_Oauth2Service($gClient);
?>