<?php

require "init.php";

$email=$_GET["email"];

$sql="SELECT status_of_profile FROM information_about_specialist WHERE email = '".$email."'";

$result=mysqli_query($con, $sql);

if ($result)
{
	$row = mysqli_fetch_row($result);
	$status_of_profile=$row[0];
	
	if ($status_of_profile=='0')
	{
		$qry="UPDATE information_about_specialist SET status_of_profile='1' WHERE email = '".$email."';";
		$result=mysqli_query($con, $qry);
		echo '<b><h3>Поздравляем! Аккаунт восстановлен. Вы можете снова пользоваться приложением!</h3></b>';
	}
	else
	{
		echo '<b><h3>Аккаунт не был удален</h3></b>';
	}	
}
else
{
	echo '<b><h3>Произошла ошибка. Возможно была неверно указана почта</h3></b>';
}

mysqli_close($con);

?>