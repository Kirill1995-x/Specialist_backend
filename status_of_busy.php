<?php
require "init.php";

$id=$_POST["id"];
$status_of_busy=$_POST["status_of_busy"];
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	$sql="UPDATE information_about_specialist SET status_of_busy='".$status_of_busy."' WHERE id='".$id."';";

	$result=mysqli_query($con,$sql);

	if ($result==true)
	{
		$code="status_of_busy_success";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
	else
	{
		$code="status_of_busy_failed";
		$title="Ответ от сервера";
		$message="Статус не обновлен. Повторите попытку";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="status_of_busy_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>