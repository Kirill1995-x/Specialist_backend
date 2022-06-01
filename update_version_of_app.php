<?php

require "init.php";

$name_of_app=$_POST["name_of_app"];
$store=$_POST["store"];

if(empty($store)) $store = "play_market";

$sql="SELECT version_of_app FROM version WHERE name_of_app = '".$name_of_app."' and store = '".$store."';";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	$row = mysqli_fetch_row($result);
	$version_of_app=$row[0];
	$code="success";
	array_push($response, array("code"=>$code, "version_of_app"=>$version_of_app));
	echo json_encode($response);
}
else
{
	$code="failed";
	array_push($response, array("code"=>$code));
	echo json_encode($response);
}

mysqli_close($con);

?>