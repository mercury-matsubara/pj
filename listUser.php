<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
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
<meta http-equiv="Content-Type" content="text/css; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<title>管理者一覧</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
--></script>
</head>

<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	require_once("f_Button.php");
	require_once("f_DB.php");
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	echo "<center>";
	echo "<a class = 'title'>管理者一覧</a>";
	echo "<br><br>";
	echo "</center>";
	echo "<left>";
	echo "<div class = 'pad' >";
	echo "<form action='listUserJump.php' method='post'>";
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo "<table><tr><td id = 'item'>管理者ID</td>";
	echo '<td><input type = "text" size = "30"  name = "uid"  id="uid"';
	if(isset ($_SESSION['post']['uid']))
	{
		echo "value ='".$_SESSION['post']['uid']."' ";
	}
	echo ' onchange ="return inputcheck(\'uid\',20,3);"></td>';
	echo "<tr></td>";
	echo "<td id = 'item'>ソート条件</td>";
	echo "<td>";
	echo "<select name='sort'>";
	echo "<option value='0'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 0)
		{
			echo "selected";
		}
	}
	else
	{
		echo "selected";
	}
	echo ">---ソート条件を選択してください。---</option>";
	echo "<option value='1'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 1)
		{
			echo "selected";
		}
	}
	echo ">ソートなし</option>";
	echo "<option value='2'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 2)
		{
			echo "selected";
		}
	}
	echo ">管理者ID</option>";
	echo "<input name='radiobutton' type='radio' value='asc'";
	if((isset ($_SESSION['post']['radiobutton'])))
	{
		if($_SESSION['post']['radiobutton'] == 'asc')
		{
			echo "checked";
		}
	}
	else
	{
		echo "checked";
	}
	echo ">昇順";
	echo "<input name='radiobutton' type='radio' value='desc'";
	if((isset ($_SESSION['post']['radiobutton'])))
	{
		if($_SESSION['post']['radiobutton'] == 'desc')
		{
			echo "checked";
		}
	}
	echo ">降順";
	echo "</td>";
	echo "</tr></table>";
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo "<input type='submit' name='serchUser_button' class = 'free'
			value = '表示'>";
	echo "</td></tr></table><br>";
	echo selectUser();
	echo "</form>";
	echo "<form action='pageJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	echo "<input type ='submit' value = '新規作成' class = 'free' name = 'insertUser_5_button'>";
	echo "</div>";
	echo "</form>";
	echo "</div>";
	echo "</left>";
?>
</body>

</html>
