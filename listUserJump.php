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
		if (strstr($key, '_button'))
		{
//			if($_POST['uid'] !="" && $_POST['uid'] != null)
//			{
//				$_SESSION['listUser']['where'] = "where LUSERNAME LIKE '%".$_POST['uid']."%'";
//				$_SESSION['pre_post']['uid'] = $_POST['uid'];
//			}
//			else
//			{
//				$_SESSION['listUser']['where'] ="";
//				$_SESSION['listUser']['uid'] = "";
//				$_SESSION['pre_post']['uid'] = "";
//			}
            
            //在籍社員のみ表示する
            $_SESSION['listUser']['where'] = "where LUSERNAME is not null AND LUSERPASS is not null ";
            
            if($_POST['4CODE'] != "" && $_POST['form_402_0'] != "" && $_POST['form_403_0'] != "")
            {
                $_SESSION['listUser']['where'] .= " AND 4CODE = ".$_POST['4CODE']." ";
                $_SESSION['pre_post']['4CODE'] = $_POST['4CODE'];
                $_SESSION['pre_post']['form_402_0'] = $_POST['form_402_0'];
                $_SESSION['pre_post']['form_403_0'] = $_POST['form_403_0'];
            }
            else 
            {
                $_SESSION['pre_post']['4CODE'] = "";
                $_SESSION['pre_post']['form_402_0'] = "";
                $_SESSION['pre_post']['form_403_0'] = "";
            }
			if($_POST['sort'] == 2)
			{
				if($_POST['radiobutton'] == 'asc')
				{
                    $_SESSION['listUser']['orderby'] = "order by STAFFID ASC ";
					$_SESSION['pre_post']['radiobutton'] = "asc";
				}
				else if($_POST['radiobutton'] == 'desc')
				{
                    $_SESSION['listUser']['orderby'] = "order by STAFFID DESC ";
					$_SESSION['pre_post']['radiobutton'] = "desc";
				}
			}
            elseif($_POST['sort'] == 3)
            {
				if($_POST['radiobutton'] == 'asc')
				{
                    $_SESSION['listUser']['orderby'] = "order by STAFFNAME ASC ";
					$_SESSION['pre_post']['radiobutton'] = "asc";
				}
				else if($_POST['radiobutton'] == 'desc')
				{
                    $_SESSION['listUser']['orderby'] = "order by STAFFNAME DESC ";
					$_SESSION['pre_post']['radiobutton'] = "desc";
				}
                
            }
			else
			{
				$_SESSION['listUser']['orderby'] ="";
				$_SESSION['pre_post']['radiobutton'] = "asc";
			}
			$_SESSION['pre_post']['sort'] = $_POST['sort'];
			$_SESSION['listUser']['limit'] = ' limit 0,10';
			$_SESSION['listUser']['limitstart'] =0;
			$_SESSION['post'] = null;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listUser.php");
		}
        if($key == 'insert')
        {
            $_SESSION['filename'] = "insertUser_5";
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertUser.php");            
        }
		if(strstr($key, '_edit'))
		{
            $_SESSION['filename'] = "editUser_5";
			$idarray = explode('_',$key);
			$_SESSION['listUser']['id'] = $idarray[0];
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editUser.php");
		}
		if($key == 'next')
		{
			$_SESSION['listUser']['limitstart'] += 10 ;
			$_SESSION['listUser']['limit'] = ' limit '.$_SESSION['listUser']['limitstart'].',10';
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listUser.php");
		}
		if($key == 'back')
		{
			$_SESSION['listUser']['limitstart'] -= 10 ;
			$_SESSION['listUser']['limit'] = ' limit '.$_SESSION['listUser']['limitstart'].',10';
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/listUser.php");
		}
		if($key == 'cancel')
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
		if($key == 'change')
		{
			$_SESSION['editUser'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editUserCheck.php");
		}
		if($key == 'delete')
		{
			$_SESSION['editUser'] = null;
            $_SESSION["deleteUser"] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/deleteUserCheck.php");
		}
        if ($key == 'clear')
		{
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editUser.php");
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



