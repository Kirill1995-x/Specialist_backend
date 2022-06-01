<?php

require "init.php";

$email=$_POST["email"];
$password=$_POST["password"];
$status_of_profile='1';
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");

$sql="SELECT * FROM information_about_specialist where email like '".$email."'";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	$row = mysqli_fetch_row($result);
	if(password_verify($password, $row[13]))
	{
		if ($row[2]==1 && $row[3]==0)
		{
			if($row[4]==1 && $row[5]==0)
			{
				$id=$row[0];
				mysqli_query($con, "UPDATE information_about_specialist SET date_last_visit='".$date_last_visit."', time_last_visit='".$time_last_visit."' WHERE id='".$id."';");
				$filename='images_specialist/'.$id;
				if (!file_exists($filename)) 
				{
					mkdir($filename, 0777);
				}
				$status_of_busy=$row[1];
				$surname=$row[7];
				$name=$row[8];
				$middlename=$row[9];
				$child_home=$row[10];
				$type_of_specialist=$row[11];
				$email=$row[12];
				$phone_number=$row[14];
				$call_hours=$row[15];
				$city=$row[16];
				$subject=$row[17];
				$name_of_photo=$row[18];
				$access_token=$row[21];
				$status_of_profile=$row[22];
				if($status_of_profile=='1')
				{
					$code="login_success";	
					array_push($response, array("code"=>$code, "id"=>$id, "status_of_busy"=>$status_of_busy, "surname"=>$surname, "name"=>$name, "middlename"=>$middlename, 
					"child_home"=>$child_home, "type_of_specialist"=>$type_of_specialist, "email"=>$email, "phone_number"=>$phone_number, "call_hours"=>$call_hours,"city"=>$city, 
					"subject_of_country"=>$subject, "name_of_photo"=>$name_of_photo, "access_token"=>$access_token));
					echo json_encode($response);
				}
				else
				{
					$code="account_deleted";
					$title="Ответ от сервера";
					$message="Ваш аккаунт был удален. Нажмите 'Восстановить', если хотите восстановить аккаунт. После этого Вам на почту будет направлена ссылка для подтверждения";
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "email"=>$email));
					echo json_encode($response);
				}
			}
			else
			{
				$code="registration_not_finish_organization";
				$title="Ошибка авторизации";
				$message="Мы не получили подтверждения от представителя Вашей организации, что Вы являетесь сотрудником. Для ускорения процесса Вы можете сообщить ему, что ссылка для подтверждения находится на email организации.";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
		}
		else
		{
			$code="registration_not_finish_user";
			$title="Ошибка авторизации";
			$message="Ваша регистрация не была завершена. Перейдите, пожалуйста в свою почту $row[12] и перейдите по ссылке для завершения регистрации.";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
			echo json_encode($response);
		}
		
	}
	else
	{
		$code="login_failed";
		$title="Ошибка авторизации...";
		$message="Пользователь не найден";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="login_failed";
	$title="Ошибка авторизации...";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>