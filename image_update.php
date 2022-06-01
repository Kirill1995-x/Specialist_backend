<?php
require "init.php";

$id=$_POST["id"];
$old_name_of_photo=$_POST["old_name_of_photo"];
$new_name_of_photo=md5(uniqid("",true)).".jpg";
$image=$_POST["image"];
$file_location="images_specialist/".$id."/";
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	$sql="UPDATE information_about_specialist SET name_of_photo='".$new_name_of_photo."',
												  date_last_visit='".$date_last_visit."',
												  time_last_visit='".$time_last_visit."' WHERE id like '".$id."'";

	$result=mysqli_query($con, $sql);

	if($result)
	{
			if($old_name_of_photo!="without_photo")
			{
				if(unlink($file_location.$old_name_of_photo))
				{
					$code="send_photo_success";
					$title="Ответ от сервера";
					$message="Старое изображение удалено. Новое добавлено";
					file_put_contents($file_location.$new_name_of_photo, base64_decode($image));
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "new_name_of_photo"=>$new_name_of_photo));
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
				file_put_contents($file_location.$new_name_of_photo, base64_decode($image));
				$code="send_photo_success";
				$title="Ответ от сервера";
				$message="Новое изображение добавлено";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "new_name_of_photo"=>$new_name_of_photo));
				echo json_encode($response);
			}
	}
	else
	{
		$code="send_photo_failed";
		$title="Ошибка";
		$message="Не удалось загрузить изображение";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="send_photo_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>