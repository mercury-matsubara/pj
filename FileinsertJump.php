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
		if($key == 'fileinsert')
		{
			foreach($_FILES as $form => $value)
			{
				if($value['size'] != 0)
				{
					$sessionid = session_id();
					$timestamp = date_create('NOW');
					$timestamp = date_format($timestamp, "YmdHis");
					$file_array = explode('.',$value['name']);
					$extention = $file_array[(count($file_array)-1)];
					$filename = './temp/';
					$filename .= "tempfileinsert.txt";
					move_uploaded_file( $value['tmp_name'], $filename );
					$_POST[$form] = $filename;
					$_SESSION['upload'][$form] = $filename;
					$filename ="";
				}
			}
			$_SESSION['files'] = $_FILES;
			$_SESSION['insert'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/FileinsertComp.php");
		}
		if($key == 'cancel')
		{
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			unset($_SESSION['files']);
			unset($_SESSION['insert']);
			unset($_SESSION['upload']);
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/Fileinsert.php");
			
		}
		if($key == 'back')
		{
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
			unset($_SESSION['files']);
			unset($_SESSION['insert']);
			unset($_SESSION['upload']);
                        //TOP画面の「ファイル取込」ボタンを押下された場合
                        if(isset($_SESSION['history']))
                        {
                                header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                                                .$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/TOP.php");
                                unset($_SESSION['history']);
                        }
                        else
                        {
                                $filename = $_SESSION['filename'];
                                $filename_array = explode('_',$filename);
                                $_SESSION['filename'] = $filename_array[0]."_2";
                                header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                                                .$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
                        }
		}
        }
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09      作成                                   *
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



