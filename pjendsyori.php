<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	
	if($filename == 'pjend_5')
	{
		$message = pjend($_SESSION['list']);        
		if($message == '完了')
		{
            error_log("PJ終了処理結果：　正常\n",3,"pjend.log");
            insert_sousarireki($_SESSION["filename"],"5",$_SESSION["seizyou5code"]);
            unset($_SESSION['seizyou5code']);
			echo "<div class = 'center'><br><br>";
			echo "<form action='pageJump.php' method='post'>";
			echo makebutton();
			echo "</form>";
			echo "<a class = 'title'>".$title."完了</a>";
			echo "</div>";
			echo "<div class = 'center'>";
			echo "</div>";
                        echo '<form action="pjendJump.php" method="post" >';
			echo "<div class = 'left' id = 'space_button'>　</div>";
			echo "<div><table id = 'button'><tr><td>";
                        echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
			echo "</td></tr></table></div>";
			echo "</form>";
		}
		else
		{
            error_log("PJ終了処理結果：　異常\n",3,"pjend.log");
			$error = $_SESSION['error'];
			echo "<div class = 'center'><br><br>";
			echo "<form action='pageJump.php' method='post'>";
			echo makebutton();
			echo "</form>";
			echo "<a class = 'title'>".$title."エラー</a>";
			echo "</div>";
			echo "<div class = 'center'>";
			echo "</div>";
			echo "<div class = 'left' id = 'space_button'>　</div>";
			echo "<div><table id = 'button'><tr><td>";
			echo makeList_error($error);
			echo "</td></tr></table></div>";
			unset($_SESSION['pjend']);
			unset($_SESSION['error']);
		}
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
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
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
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
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
		set_button_size();
	});
	$(window).resize(function()
	{
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
	});
--></script>
</head>
<body>
</body>
</html>
