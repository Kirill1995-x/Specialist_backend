<?php
require "init.php";

$id=$_POST["id"];
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");

$response=array();

$sql="UPDATE information_about_specialist SET date_last_visit='".$date_last_visit."', time_last_visit='".$time_last_visit."' WHERE id='".$id."';";

$result=mysqli_query($con,$sql);

if ($result==true)
{
	$code="success";
	$title="Ответ от сервера";
	$message="Данные успешно приняты";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
else
{
	$code="failed";
	$title="Ответ от сервера";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>