<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
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
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>������������</title>
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
	$judge = true;
	$message = getuji($_SESSION['getuji']['month'],$_SESSION['getuji']['period']);
	if($message == '��������')
	{
		echo "<center>";
		echo "<a class = 'title'>������������</a>";
		echo "<br><br><a>�����������������܂����B </a>";
		echo "<table><tr>";
		echo "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>�������s��</a></td><td class = 'two'><a class = 'comp' >".$_SESSION['getuji']['period']."���@".$_SESSION['getuji']['month']."��</a></td></tr>";
		echo "</table>";
		echo "<br><br>";
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>�@</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
		echo "</center>";
	}
	else
	{
		if($message == '���������ɂăG���[���������܂����B')
		{
			$list = makeList_error($_SESSION['error']);
		}
		
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>���������G���[</a>";
		echo "</div>";
		echo "<br><br>";
		echo "<center><div>";
		echo $message;
		if($list != "")
		{
			echo $list;
		}
		echo "</div><br><br>";
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>�@</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
	}
?>

</body>

</html>