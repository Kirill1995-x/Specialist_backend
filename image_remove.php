<?php
require "init.php";

$id=$_POST["id"];
$name_of_photo=$_POST["name_of_photo"];
$file_location="images_specialist/".$id."/".$name_of_photo;
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	$sql="UPDATE information_about_specialist SET name_of_photo='without_photo',
												  date_last_visit='".$date_last_visit."',
												  time_last_visit='".$time_last_visit."' WHERE id like '".$id."'";
	$result=mysqli_query($con, $sql);

	if($result)
	{
		if(unlink($file_location))
		{
			$code="delete_photo_success";
			array_push($response, array("code"=>$code));
			echo json_encode($response);
		}
		else
		{
			$code="delete_photo_failed";
			$title="Ответ от сервера";
			$message="Не удалось удалить изображение";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
			echo json_encode($response);
		}
	}
	else
	{
		$code="update_name_of_photo_failed";
		$title="Ответ от сервера";
		$message="Не удалось обновить данные пользователя";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="update_name_of_photo_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>