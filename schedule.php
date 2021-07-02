<?php
	include('./f_DB.php');
	$html = array();
	$sql = "SELECT * FROM calenderinfo";
	$con=dbconect();
//	$stmt=$con->prepare('SET NAMES utf8mb4');
	$stmt=$con->prepare($sql) or exit('prepare errorn');
	$stmt->execute() or exit('bind errorn');
	$stmt->bind_result($id, $luseid, $subject, $maintext, $schedate, $addtime, $startdate, $enddate);
	while ($stmt->fetch())
	{
		$subject = mb_convert_encoding($subject, "UTF-8", "SJIS");
		$maintext = mb_convert_encoding($maintext, "UTF-8", "SJIS");
		$html[] = array("id" =>$id  ,"title"  => $subject, "maintext" =>$maintext,"start"  =>$startdate,"end" =>$enddate ,"allDay" => false);
	}
	
	header('Content-type: application/json');
	echo json_encode($html);
?>