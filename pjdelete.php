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
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>—š—ğíœ</title>
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
--></script>
</head>
<body>

<?php
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class='left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	echo "<center>";
	echo "<a class = 'title'>—š—ğíœ</a>";
	echo "<br><br>";
	echo ("ÅIíœ“ú: ".Delete_rireki()."<br><br>");
	echo '<form action="deleterirekiJump.php" method="post">';
	echo "<input type='submit' name='delete' value = '—š—ğíœ' class='free'>";
	echo "</form>";
	echo "</center>";
?>

</body>

</html>