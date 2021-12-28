<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
?>
<!DOCTYPE html>
<?php
	require_once("f_Construct.php");
	if(count($_GET) != 0)
	{
		startJump($_GET);
	}
	else
	{
		startJump($_POST);
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<script language="JavaScript"><!--
	history.forward();
--></script>
</head>
<body>
<?php
	session_regenerate_id();
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$filename = $_SESSION['filename'];
	$limit_num = $form_ini[$filename]['limit'];
	$main_table =$form_ini[$filename]['use_maintable_num'];
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
	$keyarray = array_keys($_POST);
	$url = 'retry';
	$out = "";
	$out_array = array();
	$check_array = array();
	
	foreach($keyarray as $key)
	{
		if($key == 'next')
		{
			if(isset($_POST['out']))
			{
				$out = $_POST['out'];
				$out_array = explode(',',$out);
			}
			foreach($keyarray as $check_key)
			{
				if(strstr($check_key,'check_') == true)
				{
					$_SESSION['list'][$check_key] = 1;
				}
			}
			for($i = 0 ; $i < count($out_array) ; $i++)
			{
				if(isset($_SESSION['list'][$out_array[$i]]) == true)
				{
					unset($_SESSION['list'][$out_array[$i]]);
				}
			}
			$_SESSION['pre_post'] = $_POST;
			$_SESSION['list']['limitstart'] += $limit_num ;
			$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kensaku.php");
			exit();
		}
		if($key == 'back')
		{
			if(isset($_POST['out']))
			{
				$out = $_POST['out'];
				$out_array = explode(',',$out);
			}
			foreach($keyarray as $check_key)
			{
				if(strstr($check_key,'check_') == true)
				{
					$_SESSION['list'][$check_key] = 1;
				}
			}
			for($i = 0 ; $i < count($out_array) ; $i++)
			{
				if(isset($_SESSION['list'][$out_array[$i]]) == true)
				{
					unset($_SESSION['list'][$out_array[$i]]);
				}
			}
			$_SESSION['pre_post'] = $_POST;
			$_SESSION['list']['limitstart'] -= $limit_num ;
			$_SESSION['list']['limit'] = ' limit '.$_SESSION['list']['limitstart'].','
											.$limit_num;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kensaku.php");
			exit();
		}
		if($key == 'all_check')
		{
			echo "しばらくお待ちください。";
			$check_array = make_check_array($_SESSION['list'],$main_table);
			for($i = 0 ; $i < count($check_array) ; $i++ )
			{
				$_SESSION['list'][$check_array[$i]] = 1;
			}
			$_SESSION['list']['isAll'] = true;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kensaku.php");
			exit();
		}
		if($key == 'all_clear')
		{
			echo "しばらくお待ちください。";
			foreach($_SESSION['list'] as $list_key => $list_value)
			{
				if(strstr($list_key,'check_') == true)
				{
					unset($_SESSION['list'][$list_key]);
				}
			}
			$_SESSION['list']['isAll'] = false;
			header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kensaku.php");
			exit();
		}
	}
	if(isset($_GET['year']) && isset($_GET['month']))
	{
		$formnum = $form_ini[$filename]['sech_form_num'];
		unset($_SESSION['list']);
		$_SESSION['list']['form_'.$formnum.'_0'] = $_GET['year'];
		$_SESSION['list']['form_'.$formnum.'_1'] = $_GET['month'];
		$_SESSION['list']['limit'] = ' LIMIT 0,'.$limit_num.' ';
		$_SESSION['list']['limitstart'] =0;
		$_SESSION['list']['isAll'] = false;
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/kensaku.php");
		exit();
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/".$url.".php");
		exit();
	}
?>
</body>
</html>



