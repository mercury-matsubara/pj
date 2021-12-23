<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	
	require_once("f_DB.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	$isMaster = false;
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$errorinfo = existCheck($_SESSION['insert'],$main_table,1);
	if(count($errorinfo) != 1 || $errorinfo[0] != "")
	{
		unset($_SESSION['insert']['true']);
		$_SESSION['pre_post'] = $_SESSION['post'];
		$_SESSION['post'] = null;
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/insertCheck.php");
		exit();
	}
	$judge = false;
	if(isset($_SESSION['insert']['true']))
	{
		if($_SESSION['insert']['true'])
		$judge = true;
	}
	
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '“o˜^Š®—¹';
		break;
	case 1:
		$title2 = '“o˜^Š®—¹';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
	if($judge)
	{
		require_once("f_Button.php");
		$filename = $_SESSION['filename'];
		require_once("f_DB.php");
		insert($_SESSION['insert']);
		unset($_SESSION['upload']);
		echo "<form action='pageJump.php' method='post'><div class = 'left'>";
		echo makebutton();
		echo "</form>";
		echo "</div>";
//		echo "<form action='insertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
//		echo "<input type ='submit' value = '–ß‚é' name = 'back' class = 'free'>";
//		echo "</div></form>";
		echo "<div style='clear:both;'></div>";
		echo "<div class = 'center'>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div>";
		echo "<br><br>";
		echo InsertComp($_SESSION['insert']);
		echo "<div class = 'center'>";
		echo "<form action='insertJump.php' method='post'>";
                echo '<input type="submit" name = "back" value = "ˆê——‚É–ß‚é" class="free">';
		echo "<input type='submit' name = 'cancel' value='“o˜^‚É–ß‚é'
				class='free'>";
                if($filename == "EDABANINFO_1")
                {
                    echo "<input type='submit' name = 'pjtouroku' value='ŒÂ•Ê‹àŠzÝ’è'
				class='free'>";
                }
		echo "</form></div>";
		$_SESSION['insert'] = null;
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
