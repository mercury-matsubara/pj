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
<?php
	session_start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	require_once("f_Construct.php");
	startJump($_GET);
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$path = "./error.error";
	$title = "";
	
	if(isset($_GET['path']))
	{
		$path = $_GET['path'];
		$title = $_GET['title'];;
	}
?>
<head>
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	
	
	$(window).resize(function()
	{
		var w = $(window).width ();
		var h = $(window).height ();
		$('object').css({
			width : (w - 30),
			height : (h - 100)
		});
		$('table#link').css({
			width : (w - 20)
		});
		$('div.center').css({
			width : (w)
		});
	});

	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var w = $(window).width ();
		var h = $(window).height ();
		$('object').css({
			width : (w - 30),
			height : (h - 100)
		});
		$('table#link').css({
			width : (w - 20)
		});
		$('div.center').css({
			width : (w)
		});
		set_button_size();
	});

	function closewindow()
	{
		window.open('about:blank','_self').close();
	}
// --></script>
</head>
<body>
<?php
	echo '<input type = "button" class = "free" value = "PDF画面を閉じる" onClick="closewindow();" >';
	echo "<div class='center'>";
	echo "<a class = 'title'>".$title."</a>";
	if(file_exists($path))
	{
		echo '<object data="'.$path.'"></object>';
	}
	else
	{
		echo "<br><br><br><a class = 'error'>他の端末ですでにファイルが削除されています。</a>";
	}
	echo"</div>";
?>
</body>
</html>
