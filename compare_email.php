<?php

require "init.php";

$email=$_POST["email"];

$sql="SELECT * FROM email_of_organization WHERE email_of_organization = '".$email."';";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	$code="compare_email_successful";
	array_push($response, array("code"=>$code));
	echo json_encode($response);
}
else
{
	$code="compare_email_failed";
	$title="Ответ от сервера";
	$message="Организации с таким email нет в базе данных. Вашей организации необходимо оформить заявку на включение в базу данных. Перейти к оформлению заявки?";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>