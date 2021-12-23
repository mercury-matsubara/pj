<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_Button.php");
	start();
	$judge = false;
	if(isset($_SESSION['edit']['true']))
	{
		$judge = true;
	}
	
	$title1 = "";
	$title2 = "";
	if($judge)
	{
		$form_ini = parse_ini_file('./ini/form.ini', true);
		$_SESSION['post'] = $_SESSION['pre_post'];
		$isMaster = false;
		$filename = $_SESSION['filename'];
		$main_table = $form_ini[$filename]['use_maintable_num'];
		
		
		
		$title1 = $form_ini[$filename]['title'];
		switch ($form_ini[$main_table]['table_type'])
		{
		case 0:
			$title2 = '�X�V����';
			break;
		case 1:
			$title2 = '�X�V����';
			$isMaster = true;
			break;
		default:
			$title2 = '';
		}
	}
	if($judge)
	{
		$isexist = true;
		$checkResultarray = existID($_SESSION['list']['id']);
		if(count($checkResultarray) == 0)
		{
			$isexist = false;
		}
		$filename = $_SESSION['filename'];
		
		$isexist = true;
		$checkResultarray = existID($_SESSION['list']['id']);
		if(count($checkResultarray) == 0)
		{
			$isexist = false;
		}
		if($isexist)
		{
			$errorinfo = existCheck($_SESSION['edit'],$main_table,2);
			if(count($errorinfo) > 2 || $errorinfo[0] != "" || $errorinfo[1] != "")
			{
				unset($_SESSION['edit']['true']);
				$_SESSION['pre_post'] = $_SESSION['post'];
				$_SESSION['post'] == null;
				header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
						.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/editCheck.php");
				exit();
			}
		
			require_once("f_DB.php");
			update($_SESSION['edit']);
			unset($_SESSION['upload']);
			echo "<form action='pageJump.php' method='post'><div class='left'>";
			echo makebutton();
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "<div class = 'center'>";
			echo "<a class = 'title'>".$title1.$title2."</a>";
			echo "</div>";
			echo "<br><br>";
			echo EditComp($_SESSION['edit'],$_SESSION['data']);
			echo "</form>";
			echo "<div class = 'center'>";
			echo "<form action='listJump.php' method='post'>";
			echo "<input type='submit' name = 'cancel' value='�ꗗ�ɖ߂�'
					class = 'free'>";
			echo "</form></div>";
			$_SESSION['edit'] = null;
			$_SESSION['data'] = null;
			$_SESSION['upload'] = null;
		}
		else
		{
			echo "<form action='pageJump.php' method='post'><div class='left'>";
			echo makebutton();
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "</form>";
			echo "<br><br><div = class='center'>";
			echo "<a class = 'title'>".$title1.$title2."�s��</a>";
			echo "</div><br><br>";
			echo "<div class ='center'>
					<a class ='error'>���̒[���ł��łɃf�[�^���폜����Ă��邽�߁A".$title2."�ł��܂���B</a>
					</div>";
			echo '<form action="listJump.php" method="post" >';
			echo "<div class = 'center'>";
			echo '<input type="submit" name = "cancel" value = "�ꗗ�ɖ߂�" class = "free">';
			echo "</div>";
			echo "</form>";
		}
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
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
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(window).resize(function()
	{
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
	});
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
		set_button_size();
	});
--></script>
</head>
<body>


</body>

</html>
