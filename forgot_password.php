<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

$email=$_POST["email"];
$password = '';
$array = array(
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
	'!','@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+', '=', '?', '{', '}', '[', ']', '.', ',');
for ($i = 0; $i < 8; $i++) 
{
	$password .= $array[random_int(0, count($array) - 1)];
}

$password_hash=password_hash($password, PASSWORD_BCRYPT);

$sql="SELECT id FROM information_about_specialist WHERE email = '".$email."'";
$result=mysqli_query($con, $sql);
$response=array();
if(mysqli_num_rows($result)>0)
{
	$sql="UPDATE information_about_specialist SET `password` = '".$password_hash."' WHERE `email` = '".$email."'";
	$result=mysqli_query($con, $sql);
	
	if ($result)
	{
		$code="password_was_sent_to_email";
		$title="Запрос пароля";
		$message="Ваш пароль отправлен на почту $email";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
		
		$mail = new PHPMailer();
		$mail->CharSet = "utf-8";
		$mail->setFrom('info@tohelptohelp.ru', 'Онлайн-куратор');
		$mail->addReplyTo('info@tohelptohelp.ru', 'Онлайн-куратор');
		$mail->addAddress($email);
		$mail->Subject = 'Пароль от аккаунта Онлайн-куратор';                         
		$mail->Body = 
		'Добрый день,<br>
		Направляем Вам новый пароль от Вашего аккаунта - '.$password.'. Рекомендуем его изменить после того, как Вы зайдете в свой аккаунт.<br>
		С уважением,<br>
		команда разработчиков Онлайн-куратор';
		$mail->AltBody = 
		"Добрый день,
		Направляем Вам новый пароль от Вашего аккаунта - $password. Рекомендуем его изменить после того, как Вы зайдете в свой аккаунт.
		С уважением,
		команда разработчиков Онлайн-куратор";
		if ($mail->send()) echo 'Письмо отправлено!';
		else echo 'Ошибка: ' . $mail->ErrorInfo;
	}
	else
	{
		$code="password_was_not_sent_to_email";
		$title="Запрос пароля";
		$message="Не удалось направить пароль. Повторите попытку";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);	
	}
}
else
{
	$code="password_was_not_sent_to_email";
	$title="Запрос пароля";
	$message="Пользователь с почтой $email не зарегистрирован.";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);


?>