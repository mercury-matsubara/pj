<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 

	require_once("f_Construct.php");
	start();
	$judge = false;
	if(isset($_SESSION['post']['true']))
	{
		if($_SESSION['post']['true'])
		$judge = true;
	}
	if($judge)
	{
		require_once("f_Button.php");
		$filename = $_SESSION['filename'];
		require_once("f_DB.php");
		insertUser();
		$userName = $_SESSION['insertUser']['uid'];
		$password = $_SESSION['insertUser']['pass'];
		$_SESSION['insertUser'] = null;
		$pass = "";
		$passLength = 0;
		$passLength = mb_strlen( $password ,"UTF-8");
		for ($i = 0; $i < $passLength ; $i++)
		{
			$pass .="●";
		}
		$password = null;
		echo "<form action='pageJump.php' method='post'><div class = 'left'>";
		echo makebutton();
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "<div class = 'center'>";
		echo "<a class = 'title'>管理者登録完了</a>";
		echo "</div><br><br>";
		echo "<table><tr><td class = 'space'></td><td class = 'one'>管理者ID</td>";
		echo "<td class = 'two'>";
		echo $userName;
		echo '</td>';
		echo "</tr><tr><td class = 'space'></td><td class = 'one'>パスワード</td>";
		echo "<td class = 'two'>";
		echo $pass;
		echo '</td>';
		echo "</tr></table>";
		echo "<br>";
		echo "<div class = 'center'>";
		echo "<input type='submit' name='insertUser_5_button' 
				class='free' value = '登録画面に戻る'>";
		echo "</div>";
		echo "</form>";
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
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>管理者登録完了</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./jquery.corner.js'></script>
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
