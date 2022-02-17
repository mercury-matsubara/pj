<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	startJump($_POST);
	require_once ("f_DB.php");
	session_regenerate_id();
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
	{
		if($key == 'insert')
		{
			$counter = 0;
			if(isset($_SESSION['upload']) == true)
			{
				foreach($_SESSION['upload'] as $delete => $file)
				{
					unlink($file);
				}
			}
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
					$filename .= $timestamp.'_'.session_id().'_'.$counter.'.'.$extention;
					move_uploaded_file( $value['tmp_name'], $filename );
					$counter++;
					$_POST[$form] = $filename;
					$_SESSION['upload'][$form] = $filename;
					$filename ="";
				}
			}
			$_SESSION['files'] = $_FILES;
			$_SESSION['insert'] = $_POST;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertCheck.php");
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
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insert.php");
			
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
			$filename = $_SESSION['filename'];
			$filename_array = explode('_',$filename);
                        if($filename == 'TOP_1')
                        {
                            $_SESSION['filename'] = $filename_array[0]."_4";
                            header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                            		.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/TOP.php");
                        }
                        else
                        {
                            $_SESSION['filename'] = $filename_array[0]."_2";
                            header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                            		.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
                        }
		}
		
		if($key == 'mid')
		{
/*			//特殊処理
			//期またぎ
			//POSTデータ取得
			$org5code = $_POST["temp"];
			$orgpjcode = $_POST["form_102_0"];
			$orgedaban = $_POST["form_202_0"];
			$orgpjname = $_POST["form_203_0"];
			$addpjnum = $_POST["1CODE"];
			$addadanum = $_POST["2CODE"];
			$addcharge = $_POST["charge"];
			$today = explode('/',date("Y/m/d"));
			$period = $_SESSION['nenzi']['period'];
			//またぐ分を生成(次期プロジェクトに新規生成)
			$con = dbconect();																									 //db接続関数実行
			$sql = "INSERT INTO projectinfo (1CODE,2CODE,CHARGE,5PJSTAT) VALUES (".$addpjnum.",".$addadanum.",".$addcharge.",1) ;";
			$result = $con->query($sql) or ($judge = true);																	 //クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				error_log($sql,0);
				$judge = false;
			}
			
			//またぐ分の金額取得
			$sql = "SELECT * FROM projectinfo where projectinfo.5CODE = '".$org5code."' ;";
			$result = $con->query($sql) or ($judge = true);																	 //クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$nowcharge = $result_row['CHARGE'] ;
			}
			
			//もちこし金額分を差し引く
			$sql = "UPDATE projectinfo SET CHARGE =  ".($nowcharge - $addcharge)." WHERE 5CODE = ".$org5code." ;";
			$result = $con->query($sql) or ($judge = true);																	 //クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			
			$counter = 0;
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
					$filename .= $timestamp.'_'.session_id().'_'.$counter.'.'.$extention;
					move_uploaded_file( $value['tmp_name'], $filename );
					$counter++;
					$_POST[$form] = $filename;
					$_SESSION['upload'][$form] = $filename;
					$filename ="";
				}
			}
			//元のPJを終了させる
			$_SESSION['5CODE'] = $org5code;
			pjend($_SESSION);
			$_SESSION['files'] = $_FILES;
			$_SESSION['insert'] = $_POST;
			
			unset($_SESSION['list']);
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kimatagiComp.php");
*/
			//2017-12-27
			//POSTデータ取得
			$_SESSION['kimatagi']['5CODE'] = $_POST["temp"];
			$_SESSION['kimatagi']['nextcode'] = $_POST["form_102_0"];
			$_SESSION['kimatagi']['nextedaban'] = $_POST["form_202_0"];
			$_SESSION['kimatagi']['nextname'] = $_POST["form_203_0"];
			$_SESSION['kimatagi']['1CODE'] = $_POST["1CODE"];
			$_SESSION['kimatagi']['2CODE'] = $_POST["2CODE"];
			$_SESSION['kimatagi']['charge'] = $_POST["charge"];
			$judge = false;
			$con = dbconect();																									 //db接続関数実行
			//またぐ分の金額取得
			$sql = "SELECT * FROM projectinfo LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE projectinfo.5CODE = '".$_SESSION['kimatagi']['5CODE']."' ;";
			$result = $con->query($sql) or ($judge = true);																	 //クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$_SESSION['kimatagi']['nowcharge'] = $result_row['CHARGE'];
				$_SESSION['kimatagi']['nowcode'] = $result_row['PROJECTNUM'];
				$_SESSION['kimatagi']['nowedaban'] = $result_row['EDABAN'];
				$_SESSION['kimatagi']['nowname'] = $result_row['PJNAME'];
			}
			
			unset($_SESSION['list']);
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kimatagiCheck.php");
		}
                if($key == "pjtouroku")
                {       
                        $filename = $_SESSION['filename'];
                        $filename_array = explode('_',$filename);
			$_SESSION['filename'] = $filename_array[0]."_2";
                        header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kobetu.php");
                }
	}
?>
<!DOCTYPE html PUBLIC "-W3C/DTD HTML 4.01">
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



