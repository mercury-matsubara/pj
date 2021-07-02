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
<?php
	require_once("f_DB.php");
	require_once("f_Button.php");
	require_once("f_File.php");
	require_once("f_Form.php");
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>月次処理</title>
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
		var res = confirm("月次処理を行いますがよろしいですか。");
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
		document.getElementById('month').value = document.getElementById('month_0').value;
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
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class='left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	echo "<center>";
	echo "<a class = 'title'>月次処理</a>";
	echo "<br><a class = 'error'>".$message."</a>";
	echo "<br><br>";
	echo ("前回実施月： ".getuzi_rireki()."<br><br>");
	echo '<form action="getuziJump.php" method="post" >';
	$today = explode('/',date("Y/m/d"));
	if($today[1] == '6')
	{
		$post['period_0'] = getperiod($today[1],$today[0]) - 1;
	}
	else
	{
		$post['period_0'] = getperiod($today[1],$today[0]);
	}
	
	$post['month_0'] = $today[1]-1;
	echo '<table><tr><td>月次処理対象期 </td><td>'.period_pulldown_set("period","",$post,"","","").'</td></tr>';
	echo '<tr><td>月次処理対象月 </td><td>'.month_pulldown_set("month","",$post,"","","").'</td></tr></table>';
	echo makeEndMonth();
	echo "<div style='display:inline-flex'>";
	echo "<input type='submit' name='delete' value = '月次処理' class='free' onClick = 'return check();'>";
	echo "</form>";
	echo "<form action='download_csv.php' method='post'>";
	echo "<input type = 'hidden' name = 'period' id = 'period' value = ''>";
	echo "<input type = 'hidden' name = 'month' id = 'month' value = ''>";
	echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' onClick = ' set_value(); '>";
	echo "</form>";
	echo "</div>";
	echo "</center>";
?>

</body>

</html>
