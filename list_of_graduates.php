<?php
require "init.php";

$id=$_GET["id"];
$access_token=$_GET["access_token"];
$graduate=$_GET["graduate"];
$subject_of_country=$_GET["subject_of_country"];
$city=$_GET["city"];
$child_home=$_GET["child_home"];

if(check_access_token($id, $access_token, $con))
{
	if(empty($city) && empty($child_home))
	{
		$sql="SELECT DISTINCT * FROM `information_about_users` AS u, `questionary` AS q WHERE 
				u.id=q.id_of_user AND			
				u.status_of_profile='1' AND 
				u.subject_of_country='".$subject_of_country."' AND
			   (LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name)) OR
				LOCATE('".$graduate."', CONCAT(u.name,' ', u.surname)) OR
				LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name, ' ', u.middlename)))";
	}
	else if(empty($child_home))
	{
		$sql="SELECT DISTINCT * FROM `information_about_users` AS u, `questionary` AS q WHERE 
				u.id=q.id_of_user AND			
				u.status_of_profile='1' AND 
				u.subject_of_country='".$subject_of_country."' AND
				LOCATE('".$city."', u.city) AND
			   (LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name)) OR
				LOCATE('".$graduate."', CONCAT(u.name,' ', u.surname)) OR
				LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name, ' ', u.middlename)))";
	}
	else if(empty($city))
	{
		$sql="SELECT DISTINCT * FROM `information_about_users` AS u, `questionary` AS q WHERE 
				u.id=q.id_of_user AND			
				u.status_of_profile='1' AND 
				u.subject_of_country='".$subject_of_country."' AND
				LOCATE('".$child_home."', u.child_home) AND
			   (LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name)) OR
				LOCATE('".$graduate."', CONCAT(u.name,' ', u.surname)) OR
				LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name, ' ', u.middlename)))";
	}
	else 
	{
		$sql="SELECT DISTINCT * FROM `information_about_users` AS u, `questionary` AS q WHERE 
				u.id=q.id_of_user AND			
				u.status_of_profile='1' AND 
				u.subject_of_country='".$subject_of_country."' AND
				LOCATE('".$city."', u.city) AND
				LOCATE('".$child_home."', u.child_home) AND
			   (LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name)) OR
				LOCATE('".$graduate."', CONCAT(u.name,' ', u.surname)) OR
				LOCATE('".$graduate."', CONCAT(u.surname,' ', u.name, ' ', u.middlename)))";	
	}

	$result=mysqli_query($con,$sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id_of_user'=>$row['id_of_user'], 'surname'=>$row['surname'], 'name'=>$row['name'], 'middlename'=>$row['middlename'], 'child_home'=>$row['child_home'], 
		'email'=>$row['email'], 'phone_number'=>$row['phone_number'], 'city'=>$row['city'], 'subject_of_country'=>$row['subject_of_country'], 
		'registration_address'=>$row['registration_address'], 'factual_address'=>$row['factual_address'], 'type_of_flat'=>$row['type_of_flat'], 'sex'=>$row['sex'], 
		'date_of_born'=>$row['date_of_born'], 'month_of_born'=>$row['month_of_born'], 'year_of_born'=>$row['year_of_born'], 'main_target'=>$row['main_target'], 
		'problem_education'=>$row['problem_education'], 'problem_flat'=>$row['problem_flat'], 'problem_money'=>$row['problem_money'], 
		'problem_law'=>$row['problem_law'], 'problem_other'=>$row['problem_other'], 'name_education_institution'=>$row['name_education_institution'], 
		'level_of_education'=>$row['level_of_education'], 'my_professional'=>$row['my_professional'], 'my_interests'=>$row['my_interests'], 
		'date_of_last_questionary'=>$row['date_of_last_questionary'], 'name_of_photo'=>$row['name_of_photo']));
	}

	echo json_encode(array("data"=>$data));
}
else
{
	$response=array();
	$code="failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>