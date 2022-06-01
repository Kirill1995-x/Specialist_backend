<?php

require "init.php";

$email=$_GET["email"];
$code=$_GET["code_registration"];
$dbcode="";

$query="SELECT code_registration FROM information_about_specialist WHERE email = '".$email."';";

$result=mysqli_query($con, $query);

if ($result)
{
	$row = mysqli_fetch_row($result);
	$dbcode=$row[0];
	
	if ($dbcode==$code)
	{
		$qry="UPDATE information_about_specialist SET confirmed='1', code_registration='0' WHERE email = '".$email."';";
		$fresult=mysqli_query($con, $qry);
		echo '<b><h3>Поздравляем! Ваш аккаунт активирован.</h3></b>';
	}
	else if($dbcode==0)
	{
		echo '<b><h3>Верификация уже пройдена. Не забудьте убедиться, что представитель организации также перешел по ссылке.</h3></b>';
	}
	else
	{
		echo '<b><h3>Код не совпадает. Верификация не пройдена.</h3></b>';
	}	
}
else
{
	echo '<b><h3>Результат не возвращен.</h3></b>';
}

mysqli_close($con);

?>