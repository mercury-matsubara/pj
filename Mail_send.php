<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
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
<?php
	require_once ("f_Form.php");
	require_once("f_Construct.php");
//	start();
	
	
//	$_SESSION['post'] = $_SESSION['pre_post'];
//	$_SESSION['pre_post'] = null;
	
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	$error = array();
	
?>
<head>
<title></title>
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
	
	window.name = "Modal";																						//�@submit�{�^���ōX�Ɏq��ʊJ���Ȃ��悤��
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var t =  $('table.mail').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t)/2;
		}
		$('div#space').css({
			width : width_div
		});
		set_button_size();
	});
	$(window).resize(function()
	{
		var w = $(window).width ();
		$('div.center').css({
			width : (w)
		});
		var t =  $('table.mail').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t)/2;
		}
		$('div#space').css({
			width : width_div
		});
	});
	function closewindow()
	{
		close();
	}
// --></script>
</head>
<body>

<?php
	echo "<br>";
	echo "<div class='center'>";
	echo "<a class = 'title'> ���[�����M���� </a>";
	echo "</div><br><br>";
	echo "<div class='center'>";
	echo "<a>���[���̑��M�������v���܂����B</a>";
	echo "</div>";
//	echo make_mail_result($_SESSION['mail']['user'],$error,$_SESSION['mail']['adress']);
	if(isset($_SESSION['error']))
	{
		echo "<div class='center'>";
		echo "<br><a class = 'error'>���[�����M����ɒB�������߈ꕔ���[�������M�ł��܂���ł����B</a>";
		echo "<br>";
		echo "</div>";
		unset($_SESSION['error']);
	}
	echo "<div class='center'>";
	echo '<br><input type="button" class="free" value ="����" onClick="closewindow();" >';
	echo "<br>";
	echo "</div>";
	unset($_SESSION['mail']);
?>

	
</body>
</html>
