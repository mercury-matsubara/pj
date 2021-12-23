<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
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
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
//	$_SESSION['post'] = $_SESSION['pre_post'];
//	$_SESSION['pre_post'] = null;
?>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
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
<?php
	$judge = true;
	echo "<form action='pageJump.php' method='post'>";
	echo "<div class = 'left' id = 'space_button'>�@</div>";
	echo "<div><table id = 'button'><tr><td>";
	echo makebutton();
	echo "</td></tr></table></div>";
	echo "</form>";
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title."</a>";
	echo "</div>";
	echo "<br><br>";
	echo "<table><tr>";
	echo "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>�v���W�F�N�g�R�[�h</a></td><td class = 'two'><a class = 'comp' >".$_SESSION['list']['form_102_0']."</a></td></tr>";
	echo "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>�}�ԃR�[�h</a></td><td class = 'two'><a class = 'comp' >".$_SESSION['list']['form_202_0']."</a></td></tr>";
	echo "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>���ԁE�Č���</a></td><td class = 'two'><a class = 'comp' >".$_SESSION['list']['form_203_0']."</a></td></tr>";
	echo "</table>";
//	echo '<form action="pjagainJump.php" method="post" >';
//	echo "<div class = 'center'>";
//	echo '<input type="submit" name = "backall" value = "�ꗗ�ɖ߂�" class = "free">';
//	echo "</div>";
//	echo "</form>";
	
?>
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("�������e����m�F�B\n�I���v���W�F�N�g���L�����Z�����܂�����낵���ł����H"))
			{
				location.href = "./pjagainsyori.php";
			}
		}
	}
--></script>
</body>
</html>


