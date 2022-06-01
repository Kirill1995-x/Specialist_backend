<?php

require "init.php";

$id_of_specialist=$_GET["id_of_specialist"];
$type_of_specialist=$_GET["type_of_specialist"];
$subject_of_country=$_GET["subject_of_country"];
$access_token=$_GET["access_token"];

if(check_access_token($id_of_specialist, $access_token, $con))
{
	if($type_of_specialist=='Педагог-психолог')
	{
		$sql="SELECT id_of_specialist, status  FROM request_to_specialist 
		WHERE 
		((id_of_specialist='0' and status='1') or (id_of_specialist='".$id_of_specialist."' and 
		(status='1' OR status='2'))) and
		subject_of_country='".$subject_of_country."' and
		type_of_request='1'";
	}
	else if($type_of_specialist=='Юрист')
	{
		$sql="SELECT id_of_specialist, status  FROM request_to_specialist 
		WHERE 
		((id_of_specialist='0' and status='1') or (id_of_specialist='".$id_of_specialist."' and 
		(status='1' OR status='2'))) and
		subject_of_country='".$subject_of_country."' and
		type_of_request='2'";
	}
	else if($type_of_specialist=='Социальный педагог')
	{
		$sql="SELECT id_of_specialist, status  FROM request_to_specialist 
		WHERE 
		((id_of_specialist='0' and status='1') or (id_of_specialist='".$id_of_specialist."' and 
		(status='1' OR status='2'))) and 
		subject_of_country='".$subject_of_country."' and
		(type_of_request='3' or type_of_request='5' or type_of_request='6')";
	}
	else if($type_of_specialist=='Специалист по социальной работе')
	{
		$sql="SELECT id_of_specialist, status  FROM request_to_specialist 
		WHERE 
		((id_of_specialist='0' and status='1') or (id_of_specialist='".$id_of_specialist."' and (status='1' OR status='2'))) and 
		subject_of_country='".$subject_of_country."' and
		(type_of_request='3' or type_of_request='4' or type_of_request='5' or type_of_request='6' or type_of_request='7')";
	}

	$result=mysqli_query($con, $sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id_of_specialist'=>$row['id_of_specialist'], 'status'=>$row['status']));
	}

	echo json_encode(array("data"=>$data));
}

mysqli_close($con);

?>