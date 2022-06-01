<?php
require "init.php";

$id_of_specialist=$_POST["id_of_specialist"];
$access_token=$_POST["access_token"];
$id_of_request=$_POST["id_of_request"];
$status=2;
$date=date("d.m.Y");
$time=date("H:i");
$response=array();

if(check_access_token($id_of_specialist, $access_token, $con))
{
	$sql="UPDATE request_to_specialist SET id_of_specialist='".$id_of_specialist."', status='".$status."', TIME_got_specialist='".$time."', DATE_got_specialist='".$date."'
	WHERE id='".$id_of_request."'";

	$result=mysqli_query($con,$sql);

	if ($result==true)
	{
		$code="request_confirm_success";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
	else
	{
		$code="request_confirm_failed";
		$title="Ответ от сервера";
		$message="Запрос не взят в работу. Повторите попытку.";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="request_confirm_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>