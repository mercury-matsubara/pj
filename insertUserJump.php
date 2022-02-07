<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:');
	header('Content-type: text/html; charset=Shift_JIS');  
	require_once("f_Construct.php");
	startJump($_POST);
	session_regenerate_id();
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if($key == 'insert')
		{
			$_SESSION['insertUser'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertUserCheck.php");
		}
		if($key == 'cancel')
		{
            $_SESSION['insertUser'] = null;
            
            //ŒŸõðŒ•ÛŽ
//			$_SESSION['pre_post'] = array();
//			$_SESSION['pre_post'][$key] = $_POST[$key];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertUser.php");
		}
        if($key == 'back')
        {
            $_SESSION['editUser'] = null;
			$_SESSION['result_array'] = null;
			unset($_SESSION['listUser']['id']);
			if(isset($_SESSION['pre_post']['true']))
			{
				unset($_SESSION['pre_post']['true']);
			}
            
			$_SESSION['filename'] = "listUser_5";

			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listUser.php");
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



