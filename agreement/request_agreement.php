<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';

function check_fields($surname_of_graduate, $name_of_graduate, $middlename_of_graduate, $surname, $name, $middlename, $email, $phone, $agreement, $title)
{
	if(empty($surname_of_graduate))return false;
	else if(empty($name_of_graduate))return false;
	else if(empty($middlename_of_graduate))return false;
	else if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($email))return false;
	else if(empty($phone))return false;
	else if(empty($agreement))return false;
	else if(empty($title))return false;
	else return true;
}

$surname_of_graduate=$_POST["surname_of_graduate"];
$name_of_graduate=$_POST["name_of_graduate"];
$middlename_of_graduate=$_POST["middlename_of_graduate"];
$surname=$_POST["surname"];
$name=$_POST["name"];
$middlename=$_POST["middlename"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$agreement=$_POST["agreement"];
$title=$_POST["title"];
$name_for_document=$surname.'_'.$name.'_'.uniqid("",true).'_'.$title;

$response=array();

if(check_fields($surname_of_graduate, $name_of_graduate, $middlename_of_graduate, $surname, $name, $middlename, $email, $phone, $agreement, $title))
{
	file_put_contents($name_for_document, base64_decode($agreement));
	$code="request_was_sent_success";
	$title="Отправка запроса";
	$message="Данные успешно отправлены. Результаты придут Вам на почту.";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);

	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	$mail->setFrom($email, $surname.' '.$name.' '.$middlename);
	$mail->addReplyTo($email, $surname.' '.$name.' '.$middlename);
	$mail->addAddress('info@tohelptohelp.ru');
	$mail->Subject = 'Регистрация несовершеннолетнего в приложении ВПомощь';                         
	$mail->Body = 
	'ФИО несовершеннолетнего: '.$surname_of_graduate.' '.$name_of_graduate.' '.$middlename_of_graduate.';<br>
	телефон специалиста: '.$phone.';';
	$mail->AltBody = 
	"ФИО несовершеннолетнего: $surname_of_graduate $name_of_graduate $middlename_of_graduate;
	телефон специалиста: $phone;";
	$mail->addAttachment($name_for_document);
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
