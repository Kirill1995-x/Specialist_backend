<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$response=array();
if(check_access_token($id, $access_token, $con))
{
	$sql="UPDATE information_about_specialist SET `status_of_profile`='0' WHERE `id`='".$id."'";
	$result=mysqli_query($con, $sql);

	if ($result)
	{
		$code="success";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
	else
	{
		$code="failed";
		$title="Ответ от сервера";
		$message="Не удалось удалить аккаунт. Повторите попытку или обратитесь в тех.поддержку";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);	
	}
}
else
{
	$code="failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>