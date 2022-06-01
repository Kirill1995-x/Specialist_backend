<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");

$sql="SELECT status_of_busy, surname, name, middlename, child_home, email, phone_number, call_hours, city, subject_of_country, name_of_photo, status_of_profile 
	  FROM information_about_specialist WHERE id like '".$id."' AND access_token like '".$access_token."';";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	mysqli_query($con, "UPDATE information_about_specialist SET date_last_visit='".$date_last_visit."', time_last_visit='".$time_last_visit."' WHERE id='".$id."';");
	$row = mysqli_fetch_row($result);
	$status_of_busy=$row[0];
	$surname=$row[1];
	$name=$row[2];
	$middlename=$row[3];
	$child_home=$row[4];
	$email=$row[5];
	$phone_number=$row[6];
	$call_hours=$row[7];
	$city=$row[8];
	$subject=$row[9];
	$name_of_photo=$row[10];
	$status_of_profile=$row[11];
	$code="success";	
	array_push($response, array("code"=>$code, "id"=>$id, "status_of_busy"=>$status_of_busy, "surname"=>$surname, "name"=>$name, "middlename"=>$middlename, "child_home"=>$child_home, 
	"email"=>$email, "phone_number"=>$phone_number, "call_hours"=>$call_hours,"city"=>$city, "subject_of_country"=>$subject, "name_of_photo"=>$name_of_photo, "status_of_profile"=>$status_of_profile));
	echo json_encode($response);
	
}
else
{
	$code="failed";
	$title="Ошибка";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>