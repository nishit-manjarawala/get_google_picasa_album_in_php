<?php
session_start();
if(isset($_GET['album_url']) && isset($_SESSION['token'])):
	$token=json_decode($_SESSION['token']);
	$data=json_decode(file_get_contents($_GET['album_url']."&access_token=".$access_token= $token->access_token.''));
	echo'<table>';
		echo'<tr>';
			echo'<td></td>';
			echo'<td>Thumbnail</td>';
			echo'<td>Title</td>';
			echo'<td>image ID</td>';
			echo'<td>Published on</td>';
			echo'<td>Updated on</td>';
			echo'<td>Url</td>';
			
		echo'</tr>';
	foreach($data->feed->entry as $content):
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
			
			
			
			echo'<td style="width:10px;word-wrap: break-word;"><a href="single_image.php?album_url='.$content->link[0]->href.'">'.$content->link[0]->href.'</a></td>';
		echo"</tr>";
		
		// echo'<pre>';
			// print_r($content);
		// echo'</pre>';
		// die();
	endforeach;
	echo"</table>";
	
endif;
?>