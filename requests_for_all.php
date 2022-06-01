<?php
require "init.php";

$id=$_GET["id"];
$access_token=$_GET["access_token"];
$id_of_specialist=0;
$type_of_specialist=$_GET["type_of_specialist"];
$status=1;
$subject_of_country=$_GET["subject_of_country"];

if(check_access_token($id, $access_token, $con))
{
	if($type_of_specialist=='Педагог-психолог')
	{
		$sql="SELECT request_to_specialist.id, 
					 information_about_users.surname, 
					 information_about_users.name, 
					 information_about_users.middlename, 
					 information_about_users.city, 
					 information_about_users.phone_number, 
					 information_about_users.email, 
					 request_to_specialist.type_of_request, 
					 request_to_specialist.message_of_user, 
					 request_to_specialist.TIME_sent_user, 
					 request_to_specialist.DATE_sent_user  
			FROM request_to_specialist, information_about_users 
			WHERE request_to_specialist.id_of_specialist='".$id_of_specialist."' and request_to_specialist.status='".$status."' and 
				  request_to_specialist.type_of_request='1' and information_about_users.subject_of_country='".$subject_of_country."' and
				  request_to_specialist.id_of_user=information_about_users.id";
	}
	else if($type_of_specialist=='Юрист')
	{
		$sql="SELECT request_to_specialist.id, 
					 information_about_users.surname, 
					 information_about_users.name, 
					 information_about_users.middlename, 
					 information_about_users.city, 
					 information_about_users.phone_number, 
					 information_about_users.email, 
					 request_to_specialist.type_of_request, 
					 request_to_specialist.message_of_user, 
					 request_to_specialist.TIME_sent_user, 
					 request_to_specialist.DATE_sent_user  
			FROM request_to_specialist, information_about_users 
			WHERE request_to_specialist.id_of_specialist='".$id_of_specialist."' and request_to_specialist.status='".$status."' and
				  request_to_specialist.type_of_request='2' and information_about_users.subject_of_country='".$subject_of_country."' and
				  request_to_specialist.id_of_user=information_about_users.id";
	}
	else if($type_of_specialist=='Социальный педагог')
	{
		$sql="SELECT request_to_specialist.id, 
					 information_about_users.surname, 
					 information_about_users.name, 
					 information_about_users.middlename, 
					 information_about_users.city, 
					 information_about_users.phone_number, 
					 information_about_users.email, 
					 request_to_specialist.type_of_request, 
					 request_to_specialist.message_of_user, 
					 request_to_specialist.TIME_sent_user, 
					 request_to_specialist.DATE_sent_user  
			FROM request_to_specialist, information_about_users 
			WHERE request_to_specialist.id_of_specialist='".$id_of_specialist."' and request_to_specialist.status='".$status."' and 
				 (request_to_specialist.type_of_request='3' or request_to_specialist.type_of_request='5' or request_to_specialist.type_of_request='6') and 
				 information_about_users.subject_of_country='".$subject_of_country."' and
				 request_to_specialist.id_of_user=information_about_users.id";
	}
	else if($type_of_specialist=='Специалист по социальной работе')
	{
		$sql="SELECT request_to_specialist.id, 
					 information_about_users.surname, 
					 information_about_users.name, 
					 information_about_users.middlename, 
					 information_about_users.city, 
					 information_about_users.phone_number, 
					 information_about_users.email, 
					 request_to_specialist.type_of_request, 
					 request_to_specialist.message_of_user, 
					 request_to_specialist.TIME_sent_user, 
					 request_to_specialist.DATE_sent_user  
			FROM request_to_specialist, information_about_users  
			WHERE request_to_specialist.id_of_specialist='".$id_of_specialist."' and request_to_specialist.status='".$status."' and 
				 (request_to_specialist.type_of_request='3' or request_to_specialist.type_of_request='4' or request_to_specialist.type_of_request='5' or 
				  request_to_specialist.type_of_request='6' or request_to_specialist.type_of_request='7') and 
				  information_about_users.subject_of_country='".$subject_of_country."' and
				  request_to_specialist.id_of_user=information_about_users.id";
	}

	$result=mysqli_query($con,$sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id'=>$row['id'], 'surname_of_user'=>$row['surname'], 'name_of_user'=>$row['name'], 'middlename_of_user'=>$row['middlename'], 
		'city'=>$row['city'], 'phone_of_user'=>$row['phone_number'], 'email_of_user'=>$row['email'], 'type_of_request'=>$row['type_of_request'],
		'message_of_user'=>$row['message_of_user'], 'time_sent_user'=>$row['TIME_sent_user'], 'date_sent_user'=>$row['DATE_sent_user']));	
	}

	echo json_encode(array("data"=>$data));
}

mysqli_close($con);

?>