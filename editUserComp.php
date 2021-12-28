<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 

	require_once("f_Construct.php");
	require_once("f_Button.php");
	require_once("f_DB.php");
	start();
	$judge = false;
	if(isset($_SESSION['post']['true']))
	{
		if($_SESSION['post']['true'])
		{
			$judge = true;
			$_SESSION['post'] = $_SESSION['pre_post'];
			$_SESSION['pre_post'] = null;
		}
	}
	if($judge)
	{
		
		$isexist = true;
		$checkResultarray = selectID($_SESSION['listUser']['id']);
		if(count($checkResultarray) == 0)
		{
			$isexist = false;
		}
		$filename = $_SESSION['filename'];
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton();
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		if($isexist)
		{
			updateUser();
			$userName = $_SESSION['editUser']['uid'];
			$password = $_SESSION['editUser']['newpass'];
			$_SESSION['editUser'] = null;
			$_SESSION['result_array'] = null;
			$pass = "";
			$passLength = 0;
			$passLength = mb_strlen( $password ,"UTF-8");
			for ($i = 0; $i < $passLength ; $i++)
			{
				$pass .="●";
			}
			$password = null;
			echo "<div = class='center'>";
			echo "<a class = 'title'>管理者更新完了</a>";
			echo "</div><br><br>";
			echo "<table><tr><td class = 'space'></td><td class = 'one'>管理者ID</td>";
			echo "<td class = 'two'>";
			echo $userName;
			echo "</td>";
			echo "</tr><tr><td class = 'space'></td><td class = 'one'>パスワード</td>";
			echo "<td class = 'two'>";
			echo $pass;
			echo '</td>';
			echo "</tr></table>";
			echo "<br>";
			echo '<form action="listUserJump.php" method="post">';
			echo "<div class = 'center'>";
			echo "<input type='submit' name='cancel' class ='free'
					 value = '一覧に戻る' >";
			echo "</div>";
			echo "</form>";
		}
		else
		{
			echo "<div = class='center'>";
			echo "<a class = 'title'>管理者更新不可</a>";
			echo "</div><br><br>";
			echo "<div class ='center'>
					<a class ='error'>他の端末ですでにデータが削除されているため、更新できません。</a>
					</div>";
			echo '<form action="listUserJump.php" method="post" >';
			echo "<div class = 'center'>";
			echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
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
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>管理者更新完了</title>
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
