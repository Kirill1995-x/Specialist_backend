<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';

function check_fields($surname, $name, $middlename, $email, $phone, $message_to_tech_support)
{
	if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($email))return false;
	else if(empty($phone))return false;
	else if(empty($message_to_tech_support))return false;
	else return true;
}

$surname=$_POST["surname"];
$name=$_POST["name"];
$middlename=$_POST["middlename"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$message_to_tech_support=$_POST["message"];
$version_sdk=$_POST["version_sdk"];
$version_os=$_POST["version_os"];
$device=$_POST["device"];
$manufacturer=$_POST["manufacturer"];
$model=$_POST["model"];
$title=$_POST["title"];
$screenshot=$_POST["screenshot"];
$app=$_POST["app"];
$name_for_document=$surname.'_'.$name.'_'.uniqid("",true).'_'.$title;

$response=array();

if(check_fields($surname, $name, $middlename, $email, $phone, $message_to_tech_support))
{
	if($screenshot!='without')file_put_contents($name_for_document, base64_decode($screenshot));
	$code="request_was_sent_success";
	$title="Отправка вопроса";
	$message="Ваш вопрос успешно отправлен. В ближайшее время тех.поддержка займется его решением.";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);

	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	$mail->setFrom($email, $surname.' '.$name.' '.$middlename);
	$mail->addReplyTo($email, $surname.' '.$name.' '.$middlename);
	$mail->addAddress('info@tohelptohelp.ru');
	if($app=='specialist')$mail->Subject = 'Проблемы с приложением Онлайн-куратор';    
	else $mail->Subject = 'Проблемы с приложением ВПомощь'; 
	$mail->Body = 
	'ФИО пользователя: '.$surname.' '.$name.' '.$middlename.';<br>
	телефон: '.$phone.';<br>
	текст вопроса: '.$message_to_tech_support.';<br>
	версия SDK устройства: '.$version_sdk.';<br>
	версия ОС: '.$version_os.';<br>
	устройство: '.$device.';<br>
	изготовитель: '.$manufacturer.';<br>
	модель: '.$model.'';
	$mail->AltBody = 
	"ФИО пользователя: $surname $name $middlename;
	телефон: $phone;
	текст вопроса: $message_to_tech_support;
	версия SDK устройства: $version_sdk;
	версия ОС: $version_os;
	устройство: $device;
	изготовитель: $manufacturer;
	модель: $model";
	if($screenshot!='without')$mail->addAttachment($name_for_document);
	if ($mail->send()) echo 'Письмо отправлено!';
	else echo 'Ошибка: ' . $mail->ErrorInfo;
}
else
{
	$code="request_wasnt_sent_failed";
	$title="Ответ от сервера";
	$message="Не все обязательные поля заполнены";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>
