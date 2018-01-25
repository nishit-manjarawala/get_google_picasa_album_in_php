<?php
session_start();
if(isset($_GET['album_url']) && isset($_SESSION['token'])):
	$token=json_decode($_SESSION['token']);
	$data=json_decode(file_get_contents($_GET['album_url']."&access_token=".$access_token= $token->access_token.''));
	$content_array=(array) $data->feed;
	
	$originalsize_array= (array) $content_array['gphoto$size'];
	//echo $originalsize_array['$t'];
	
	$original_height_size_array= (array) $content_array['gphoto$height'];
	//echo $original_height_size_array['$t'];
	
	$original_width_size_array= (array) $content_array['gphoto$width'];
	//echo $original_width_size_array['$t'];
	
	$media_group_array=(array) $content_array['media$group'];
	
	 $image=$media_group_array['media$content'][0]->url;
	
	$original_image = substr_replace($image, '/s'.$original_height_size_array['$t'],strrpos($image,"/"), 0);
	echo'<img src="'.$original_image.'" />';
endif;
?>