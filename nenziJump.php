<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	startJump($_POST);
	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$limit_num = $form_ini[$filename]['limit'];
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if ($key == 'delete')
		{
			if($_POST['period_0'] == "")
			{
				$_SESSION['pre_post']['message'] = '期を選択してください。';
				$_SESSION['pre_post']['item']['period_0'] = $_POST['period_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzi.php");
				exit();
			}
			else
			{
//				$_SESSION['list']['form_102_0'] = $_POST['period_0'];
				$_SESSION['nenzi']['period'] = $_POST['period_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
				exit();
			}
		}
		if (strstr($key, 'serch'))
		{
			$_SESSION['list'] = $_POST;
			$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
			$_SESSION['list']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
			exit();
		}
		if($key == 'next')
		{
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
			exit();
		}
		if($key == 'nextall')
		{
			$_SESSION['list']['limitstart'] = $_SESSION['list']['max'] ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
			exit();
		}
		if($key == 'backall')
		{
			$_SESSION['list']['limitstart'] = 0 ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
			exit();
		}
		if($key == 'back')
		{
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
			exit();
		}
		if($key == 'end')
		{
			$_SESSION['list'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kimatagi.php");
			exit();
		}
		if($key == 'push')
		{
			if($_POST['period_0'] == "" || $_SESSION['nenzi']['period'] = "")
			{
				$_SESSION['pre_post']['message'] = '期を選択してください。';
				$_SESSION['pre_post']['item']['period_0'] = $_POST['period_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzi.php");
				exit();
			}
			else
			{
				$_SESSION['nenzi']['period'] = $_POST['period_0'];
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenziCheck.php");
				exit();
			}
		}
		if($key == 'cancel')
		{
			if(!empty($_SESSION['nenzi']['kimatagi']))
			{
				unset($_SESSION['nenzi']['kimatagi']);
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzisyori.php");
				exit();
			}
			else
			{
				unset($_SESSION['nenzi']);
				unset($_SESSION['list']);
				if(isset($_SESSION['pre_post']['true']))
				{
					unset($_SESSION['pre_post']['true']);
				}
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/nenzi.php");
				exit();
			}
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



