<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	startJump($_POST);
	session_regenerate_id();
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	$judge = true;
	foreach($keyarray as $key)
	{
		if ($key == 'mail_send')
		{
			$_SESSION['mail'] = $_POST;
			echo "<center>";
			echo "メール送信中です。暫くお待ちください。";
			echo "</center>";
			echo '<script type="text/javascript">';
			echo "<!--\n";
			echo "window.open('./Mail_send.php','Modal')";
//			echo 'location.href = "./Mail_send.php";';
			echo '// -->';
			echo '</script>';
			$judge = false;
//			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
//					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/Mail_send.php");
//			exit();
		}
	}
	if($judge)
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
		exit();
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



