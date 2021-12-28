<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_Button.php");
	require_once("f_File.php");
	require_once("f_Form.php");
	start();
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;

?>
<!DOCTYPE html>
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
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>年次処理</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
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
	
	function check()
	{
		var judge = true;
		var res = confirm("年次処理を行いますがよろしいですか。");
		if ( res == true ) { 
			// OKボタンを押した時の処理
		} else {
			judge = false;
		}
		return judge;
	}
	function set_value()
	{
		document.getElementById('period').value = document.getElementById('period_0').value;
	}
--></script>
</head>
<body>

<?php
	$message = "";
	$filename = $_SESSION['filename'];
	if(isset($_SESSION['post']['message']))
	{
		$message = $_SESSION['post']['message'];
		$_SESSION['post']['massage'] = null;
	}
	if(isset($_SESSION['post']['item']))
	{
		$post = $_SESSION['post']['item'];
	}
	else
	{
		$post = array();
	}
	if(empty($_SESSION['nenzi']['checkmessage']))
	{
		echo "<left>";
		echo "<form action='pageJump.php' method='post'>";
		echo makebutton();
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "</left>";
		echo "<center><br>";
		echo "<a class = 'title'>年次処理</a>";
		echo "<br><a class = 'error'>".$message."</a>";
		echo "<br><br>";
		echo ("前回実施期： ".nenzi_rireki()."<br><br>");
		echo '<table><tr><td><form action="nenziJump.php" method="post">';
		$today = explode('/',date("Y/m/d"));
		$post['period_0'] = getperiod($today[1],$today[0]) - 1;
		echo '年次処理対象期 '.period_pulldown_set("period","",$post,"","","").'</td></tr></table>';
		echo "<div style='display:inline-flex'>";
        echo "<br><br><input type='submit' name='delete' value = '期またぎ' class='free' disabled>";             //期またぎボタン無効化
		echo "<input type='submit' name='push' value = '年次処理' class='free' onClick = 'return check();'>";
		echo "</form>";
		echo "<form action='download_csv.php' method='post'>";
		echo "<input type = 'hidden' name = 'period' id = 'period' value = ''>";
		echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' onClick = ' set_value(); '>";
		echo "</form>";
		echo "</div>";
		echo "</center>";
	}
	else
	{
		nenji($_SESSION['nenzi']['period']);
		echo "<form action='pageJump.php' method='post'>";
		echo makebutton();
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "<center>";
		echo "<a class = 'title'>年次処理完了</a>";
		echo "<br><br>";
		echo ("実施期: ".$_SESSION['nenzi']['period']."期<br><br>");
		echo "</center>";
		unset($_SESSION['nenzi']);
		unset($_SESSION['list']);
	}
?>
</body>
</html>