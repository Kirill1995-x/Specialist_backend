<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

function check_fields($surname, $name, $middlename, $child_home, $email, $password, $phone_number, 
                      $city, $subject, $type_of_specialist, $email_of_organization, $call_hours, $agreement)
{
	if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($child_home))return false;
	else if(empty($email))return false;
	else if(empty($password))return false;
	else if(empty($phone_number))return false;
	else if(empty($city))return false;
	else if(empty($subject))return false;
	else if(empty($type_of_specialist))return false;
	else if(empty($email_of_organization))return false;
	else if(empty($call_hours))return false;
	else if(empty($agreement))return false;
	else return true;
}

$surname=mysqli_real_escape_string($con,$_POST["surname"]);
$name=mysqli_real_escape_string($con,$_POST["name"]);
$middlename=mysqli_real_escape_string($con,$_POST["middlename"]);
$child_home=mysqli_real_escape_string($con,$_POST["child_home"]);
$email=$_POST["email"];
$password=password_hash($_POST["password"], PASSWORD_BCRYPT);
$phone_number=$_POST["phone_number"];
$city=mysqli_real_escape_string($con,$_POST["city"]);
$subject=mysqli_real_escape_string($con,$_POST["subject_of_country"]);
$type_of_specialist=mysqli_real_escape_string($con,$_POST["type_of_specialist"]);
$email_of_organization=mysqli_real_escape_string($con,$_POST["email_of_organization"]);
$call_hours=mysqli_real_escape_string($con,$_POST["call_hours"]);
$agreement=mysqli_real_escape_string($con,$_POST["agreement"]);
$status_of_busy=1;
$name_of_photo='without_photo';
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$access_token=md5(random_bytes(16));

$response=array();

if(check_fields($surname, $name, $middlename, $child_home, $email, $password, $phone_number, $city, $subject, $type_of_specialist, $email_of_organization, $call_hours, $agreement))
{
	if($agreement=='success')
	{
		$sql="select * from information_about_specialist where email like '".$email."';";
		$result=mysqli_query($con,$sql);
		
		if (mysqli_num_rows($result)>0)
		{
			$code="reg_failed";
			$title="Ответ от сервера";
			$message="Пользователь с таким email уже существует";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
			echo json_encode($response);
		}
		else
		{
			$confirmed=false;
			$confirmed_email=false;
			$code_registration=rand();
			$email_registration=rand();
			
			$sql="INSERT INTO information_about_specialist (status_of_busy, confirmed, code_registration, confirmed_email, email_registration, surname, name, middlename, 
			child_home, email, password, phone_number, call_hours, city, subject_of_country, type_of_specialist, name_of_photo, date_last_visit, time_last_visit, access_token)
			VALUES ('".$status_of_busy."','".$confirmed."','".$code_registration."', '".$confirmed_email."','".$email_registration."','".$surname."','".$name."','".$middlename."',
			'".$child_home."','".$email."','".$password."','".$phone_number."', '".$call_hours."', '".$city."', '".$subject."','".$type_of_specialist."','".$name_of_photo."',
			'".$date_last_visit."', '".$time_last_visit."', '".$access_token."')";
			
			$result=mysqli_query($con,$sql);
			
			if ($result)
			{
				$code="reg_success";
				$title="Ответ от сервера";
				$message="$name $middlename, спасибо за регистрацию. На вашу почту $email отправлена ссылка. Перейдите по ней для завершения регистрации. Если сообщение не появилось, проверьте Спам";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
				
				$mail = new PHPMailer();
				$mail->CharSet = "utf-8";
				$mail->setFrom('info@tohelptohelp.ru', 'Онлайн-куратор');
				$mail->addReplyTo('info@tohelptohelp.ru', 'Онлайн-куратор');
				$mail->addAddress($email);
				$mail->Subject = 'Регистрация в приложении Онлайн-куратор';                         
				$mail->Body = 
				''.$name.' '.$middlename.',<br>
				пожалуйста, нажмите на ссылку ниже для подтверждения регистрации.<br>
				Если ссылка не работает, скопируйте ее в адресную строку.<br>
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
				https://tohelptohelp.ru/specialist/emailver.php?email='.$email.'&code_registration='.$code_registration.'';
				$mail->AltBody = 
				"$name $middlename,
				пожалуйста, нажмите на ссылку ниже для подтверждения регистрации.
				Если ссылка не работает, скопируйте ее в адресную строку.
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.
				https://tohelptohelp.ru/specialist/emailver.php?email=$email&code_registration=$code_registration";
				if ($mail->send()) echo 'Письмо отправлено!';
				else echo 'Ошибка: ' . $mail->ErrorInfo;
				
				$mail_organization = new PHPMailer();
				$mail_organization->CharSet = "utf-8";
				$mail_organization->setFrom('info@tohelptohelp.ru', 'Онлайн-куратор');
				$mail_organization->addReplyTo('info@tohelptohelp.ru', 'Онлайн-куратор');
				$mail_organization->addAddress($email_of_organization);
				$mail_organization->Subject = 'Регистрация в приложении Онлайн-куратор';                         
				$mail_organization->Body = 
				'Добрый день,<br>
				Специалист '.$surname.' '.$name.' '.$middlename.' прошел регистрацию в приложении Онлайн-куратор.<br>
				Если он является сотрудником Вашей организации, пожалуйста, перейдите по ссылке ниже.<br>	
				Если ссылка не работает, скопируйте ее в адресную строку.<br>
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
								
				https://tohelptohelp.ru/specialist/emailorganizationver.php?email='.$email.'&email_registration='.$email_registration.'';
				$mail_organization->AltBody = 
				"Добрый день,
						
				Специалист $surname $name $middlename прошел регистрацию в приложении 'Онлайн-куратор'.
				Если он является сотрудником Вашей организации, пожалуйста, перейдите по ссылке ниже.	
				Если ссылка не работает, скопируйте ее в адресную строку.
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.
								
				https://tohelptohelp.ru/specialist/emailorganizationver.php?email=$email&email_registration=$email_registration";
				
				if ($mail_organization->send()) echo 'Письмо отправлено!';
				else echo 'Ошибка: ' . $mail_organization->ErrorInfo;
			}
			else
			{
				$code="reg_failed";
				$title="Ответ от сервера";
				$message="Произошла ошибка. Повторите попытку";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
		}
	}
	else
	{
		$code="reg_failed";
		$title="Ответ от сервера";
		$message="Доступ запрещен";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="reg_failed";
	$title="Ответ от сервера";
	$message="Не все обязательные поля заполнены";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>