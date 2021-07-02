<?php
	include('./f_DB.php');

	$id=$_POST['textid'];
	$title=$_POST['title1'];
	$start_date= $_POST['start_year'];
	$start_hour= $_POST['start_hour'];
	$start_min= $_POST['start_min'];
	$end_date= $_POST['end_year'];
	$end_hour= $_POST['end_hour'];
	$end_min= $_POST['end_min'];
	$honbun= $_POST['honbun'];
	
	
	$title = mb_convert_encoding($title, "cp932", "UTF-8");
	$honbun = mb_convert_encoding($honbun, "cp932", "UTF-8");
	
	$start_array = explode("/", $start_date);
	$start_year = $start_array[0];
	$start_year = str_pad($start_year, 4, "0", STR_PAD_LEFT);
	$start_month = $start_array[1];
	$start_month = str_pad($start_month, 2, "0", STR_PAD_LEFT);
	$start_day = $start_array[2];
	$start_day = str_pad($start_day, 2, "0", STR_PAD_LEFT);
	
	$end_array = explode("/", $end_date);
	$end_year = $end_array[0];
	$end_year = str_pad($end_year, 4, "0", STR_PAD_LEFT);
	$end_month = $end_array[1];
	$end_month = str_pad($end_month, 2, "0", STR_PAD_LEFT);
	$end_day = $end_array[2];
	$end_day = str_pad($end_day, 2, "0", STR_PAD_LEFT);

	$sql = "update calenderinfo set SUBJECT='".$title."',MAINTEXT='".$honbun."', STARTDATE='".$start_year.$start_month.$start_day.$start_hour.$start_min."00'".", ENDDATE='".$end_year.$end_month.$end_day.$end_hour.$end_min."00'"." where 7CODE = ".$id;
	$con = dbconect();
//	$stmt=$con->prepare('SET NAMES utf8mb4');
//	$stmt->execute();
	$stmt=$con->prepare($sql) or exit('prepare errorn');
	$stmt->execute() or exit('bind errorn');
  
	header("HTTP/1.1 301 Moved Permanently");
//	header("Location: http://localhost/leadplan/calendar.php");		// —v•ÏX
	header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/calendar.php");
?>
