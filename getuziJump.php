<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	startJump($_POST);
	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if ($key == 'delete')
		{
			if($_POST['period_0'] == "" || $_POST['month_0'] == "")
			{
				$_SESSION['pre_post']['message'] = '期,月を選択してください。';
				$_SESSION['pre_post']['item']['period_0'] = $_POST['period_0'];
				$_SESSION['pre_post']['item']['month_0'] = $_POST['month_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/getuzi.php");
				exit();
			}
			else
			{
				$_SESSION['getuji']['period'] = $_POST['period_0'];
				$_SESSION['getuji']['month'] = $_POST['month_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/getuziCheck.php");
				exit();
			}
		}
		if($key == 'cancel')
		{
			unset($_SESSION['getuji']);
			unset($_SESSION['list']);
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/getuzi.php");
			exit();
		}
	}
	header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
			.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	exit();
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.1.0  2014/07/03                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
</body>
</html>



