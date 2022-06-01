<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

function check_fields($surname, $name, $middlename, $child_home, $email, $phone_number, $city, $subject_of_country, $call_hours)
{
	if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($child_home))return false;
	else if(empty($email))return false;
	else if(empty($phone_number))return false;
	else if(empty($city))return false;
	else if(empty($subject_of_country))return false;
	else if(empty($call_hours))return false;
	else return true;
}

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$surname=mysqli_real_escape_string($con,$_POST["surname"]);
$name=mysqli_real_escape_string($con,$_POST["name"]);
$middlename=mysqli_real_escape_string($con,$_POST["middlename"]);
$child_home=mysqli_real_escape_string($con,$_POST["child_home"]);
$email=$_POST["email"];
$phone_number=$_POST["phone_number"];
$city=mysqli_real_escape_string($con,$_POST["city"]);
$subject_of_country=mysqli_real_escape_string($con,$_POST["subject_of_country"]);
$call_hours=mysqli_real_escape_string($con,$_POST["call_hours"]);
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(check_fields($surname, $name, $middlename, $child_home, $email, $phone_number, $city, $subject_of_country, $call_hours))
	{
		$choose_from_bd="SELECT email from information_about_specialist WHERE id='".$id."'";
		$result_of_request=mysqli_query($con, $choose_from_bd);
		$row=mysqli_fetch_row($result_of_request);
		if ($email==$row[0])
		{
			$sql="UPDATE information_about_specialist SET surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					call_hours='".$call_hours."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="Данные успешно обновлены";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
		}
		else
		{
			$check="SELECT * from information_about_specialist WHERE email='".$email."'";
			$check_email=mysqli_query($con, $check);
			if (mysqli_num_rows($check_email)>0)
			{
					$code="my_profile_email_failed";
					$title="Ответ от сервера";
					$message="Пользователь с таким email уже есть. Данные не обновлены";
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "email_for_my_profile"=>$row[0]));
					echo json_encode($response);
			}
			else
			{
					$confirmed=false;
					$code_registration=rand();
					
					$sql="UPDATE information_about_specialist SET confirmed='".$confirmed."',
					code_registration='".$code_registration."',
					surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					call_hours='".$call_hours."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="$name $middlename, на вашу почту $email отправлена ссылка. Перейдите по ней для подтверждения изменения почты. Если сообщение не появилось, проверьте Спам";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
						
						$mail = new PHPMailer();
						$mail->CharSet = "utf-8";
						$mail->setFrom('info@tohelptohelp.ru', 'Онлайн-куратор');
						$mail->addReplyTo('info@tohelptohelp.ru', 'Онлайн-куратор');
						$mail->addAddress($email);
						$mail->Subject = 'Обновление почты в приложении Онлайн-куратор';                         
						$mail->Body = 
						''.$name.' '.$middlename.',<br>
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты.<br>
						Если ссылка не работает, скопируйте ее в адресную строку.<br>
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
						https://tohelptohelp.ru/specialist/emailverupdate.php?email='.$email.'&code_registration='.$code_registration.'';
						$mail->AltBody = 
						"$name $middlename,
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты.
						Если ссылка не работает, скопируйте ее в адресную строку.
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.
						https://tohelptohelp.ru/specialist/emailverupdate.php?email=$email&code_registration=$code_registration";
						if ($mail->send()) echo 'Письмо отправлено!';
						else echo 'Ошибка: ' . $mail->ErrorInfo;
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
			}
		}
	}
	else
	{
		$code="my_profile_get_failed";
		$title="Ответ от сервера";
		$message="Не все обязательные поля заполнены";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="my_profile_get_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>