<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	startJump($_POST);
	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$limit_num = $form_ini[$filename]['limit'];
	$main_table =$form_ini[$filename]['use_maintable_num'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'next')
		{
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'back')
		{
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'nextall')
		{
			$_SESSION['list']['limitstart'] = $_SESSION['list']['max'] ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'backall')
		{
			$_SESSION['list']['limitstart'] = 0 ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'cancel')
		{
                    if(!isset($_SESSION['kensaku']))
                    {
                        $_SESSION['kensaku'] = $_SESSION['list'];
			$_SESSION['list'] = $_POST;
                    }
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjend.php");
			exit();
		}
		if($key == 'end')
		{
                        $_SESSION['kensaku'] = $_SESSION['list'];
			$_SESSION['list'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/pjendCheck.php");
			exit();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09                                             *
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



