<?php
	include('./f_DB.php');
	$id=$_POST['textid'];

	$sql = "DELETE FROM calenderinfo where 7CODE = ".$id;
	
	$con=dbconect();
//	$stmt=$con->prepare('SET NAMES utf8mb4');
//	$stmt->execute();
	$stmt=$con->prepare($sql) or exit('prepare errorn');
	$stmt->execute() or exit('bind errorn');
  
  
	header("HTTP/1.1 301 Moved Permanently");
//	header("Location: http://localhost/leadplan/calendar.php");		//—v•ÏX
	header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/calendar.php");
?>
