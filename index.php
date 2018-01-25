<?php
//Include GP config file && User class
include_once 'gpConfig.php';
include_once 'User.php';

if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	//Initialize User class
	$user = new User();
	
	//Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'first_name'    => $gpUserProfile['given_name'],
        'last_name'     => $gpUserProfile['family_name'],
        'email'         => $gpUserProfile['email'],
        'gender'        => $gpUserProfile['gender'],
        'locale'        => $gpUserProfile['locale'],
        'picture'       => $gpUserProfile['picture'],
        'link'          => $gpUserProfile['link']
    );
    $userData = $user->checkUser($gpUserData);
	
	//Storing user data into session
	$_SESSION['userData'] = $userData;
	
	//Render facebook profile data
    if(!empty($userData)){
        $output = '<h1>Google+ Profile Details </h1>';
        $output .= '<img src="'.$userData['picture'].'" width="300" height="220">';
        $output .= '<br/>Google ID : ' . $userData['oauth_uid'];
        $output .= '<br/>Name : ' . $userData['first_name'].' '.$userData['last_name'];
        $output .= '<br/>Email : ' . $userData['email'];
        $output .= '<br/>Gender : ' . $userData['gender'];
        $output .= '<br/>Locale : ' . $userData['locale'];
        $output .= '<br/>Logged in with : Google';
        $output .= '<br/><a href="'.$userData['link'].'" target="_blank">Click to Visit Google+ Page</a>';
        $output .= '<br/>Logout from <a href="logout.php">Google</a>'; 
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
} else {
	$authUrl = $gClient->createAuthUrl();
	$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login with Google using PHP by CodexWorld</title>
<style type="text/css">
h1{font-family:Arial, Helvetica, sans-serif;color:#999999;}
</style>
</head>
<body>
<div><?php echo $output; ?></div>

<?php
if(isset($_SESSION['token'])):
	$token=json_decode($_SESSION['token']);
	//$data=file_get_contents('https://picasaweb.google.com/data/feed/api/user/115619542492492123535?alt=json&access=all&access_token=ya29.GltMBZ1zV6UUgXDgnjNxS9m5HG_nRpse7FWPlTWomJXQvhV98SAmAW80fiAdl_VDaVxRDItKRLYrdZ01UuzjyakzW1DTsQFZYGOExKy51n3rQdqeCf4sc4oikhr8');
	$data=file_get_contents('https://picasaweb.google.com/data/feed/api/user/'.$userData['oauth_uid'].'?alt=json&kind=album&access=all&access_token='.$access_token= $token->access_token.'');
	echo'<table>';
		echo'<tr>';
			echo'<td></td>';
			echo'<td>Cover</td>';
			echo'<td>Title</td>';
			echo'<td>Album ID</td>';
			echo'<td>Published on</td>';
			echo'<td>Updated on</td>';
			echo'<td>Rights</td>';
			echo'<td>Number Photos</td>';
			echo'<td>Url</td>';
			
		echo'</tr>';
		foreach(json_decode($data)->feed->entry as $content):
			$content_array=(array) $content;
			echo"<tr>";
				echo'<td></td>';
				$media_group_array=(array) $content_array['media$group'];
				$image_details_array=(array) $media_group_array['media$thumbnail'][0];
				echo'<td><img src="'.$image_details_array['url'].'" /></td>';
				
				$title_array=(array) $content->title;
				echo"<td>".$title_array['$t']."</td>";
				
				$album_id_array=(array) $content_array['gphoto$id'];
				echo'<td>'.$album_id_array['$t'].'</td>';
				
				$published_array=(array) $content->published;
				echo"<td>".$published_array['$t']."</td>";
				
				$updateded_array=(array) $content->updated;
				echo"<td>".$updateded_array['$t']."</td>";
				
				$rights_array=(array) $content->rights;
				echo'<td>'.$rights_array['$t'].'</td>';
				
				$number_photos_array=(array) $content_array['gphoto$numphotos'];
				echo'<td>'.$number_photos_array['$t'].'</td>';
				
				echo'<td style="width:10px;word-wrap: break-word;"><a href="album_images.php?album_url='.$content->link[0]->href.'">'.$content->link[0]->href.'</a></td>';
			echo"</tr>";
		endforeach;
	echo'</table>';
endif;
?>
</body>
</html>