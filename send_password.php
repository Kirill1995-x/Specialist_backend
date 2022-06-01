<?php
require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$password=password_hash($_POST["new_password"], PASSWORD_BCRYPT);
$response=array();

if(check_access_token($id, $access_token, $con))
{
	$sql="UPDATE information_about_specialist SET `password`='".$password."' WHERE `id`='".$id."';";
	$result=mysqli_query($con,$sql);
	if ($result==true)
	{
		$code="update_password_success";
		$message="Пароль успешно обновлен";
		array_push($response, array("code"=>$code, "message"=>$message));
		echo json_encode($response);
	}
	else
	{
		$code="update_password_failed";
		$message="Пароль не обновлен. Повторите попытку";
		array_push($response, array("code"=>$code, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="update_password_failed";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>