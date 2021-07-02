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
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<?php
	require_once("f_Button.php");
	require_once("f_DB.php");
	require_once("f_Form.php");
	
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['post'] = $_SESSION['pre_post'];
	$isMaster = false;
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$judge = false;
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	switch ($form_ini[$main_table]['table_type'])
	{
		case 0:
			$title2 = '�폜�m�F';
			$judge = true;
			break;
		case 1:
			$title2 = '�폜�m�F';
			$isMaster = true;
			break;
		default:
			$title2 = '';
	}
	if($isMaster)
	{
		if($filename != 'PJTOUROKU_2')
		{
			if(!table_code_exist())
			{
				$judge = '1';
			}
		}
		else
		{
			$judge = '1';
			pjCheck($_SESSION['kobetu']['id']);
			if(!empty($_SESSION['message']))
			{
				unset($_SESSION['message']);
			}
			else
			{
				$message = "<br><a class = 'error'>�i����񂪓o�^����Ă��邽�ߍ폜�ł��܂���B</a>";
				$_SESSION['kobetu']['shintyoku'] = true;
				$judge = '2';
			}
		}
	}
	$isexist = true;
	$checkResultarray = existID($_SESSION['list']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}

?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
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
<?php
	$_SESSION['edit']['true'] = true;
	$_SESSION['pre_post'] = $_SESSION['post'];
	$filename = $_SESSION['filename'];
	if($isexist)
	{
		if($filename != 'PJTOUROKU_2')
		{
			echo "<form action='pageJump.php' method='post'><div class = 'left'>";
			echo makebutton($filename,'top');
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>".$title1.$title2."</a>";
			if($judge == false)
			{
				echo "<br><a class = 'error'>���̃}�X�^�[�͑��̃e�[�u���Ŏg�p����Ă���̂ō폜�ł��܂���B</a>";
			}
			echo "</div>";
			echo "<br><br>";
			echo EditComp($_SESSION['edit'],$_SESSION['data']);
			echo "</form>";
			echo "<div class = 'center'>";
			echo "<form action='listJump.php' method='post'>";
			echo "<input type='submit' name = 'delete' value='�폜'
					class='button' style = 'height:30px;'>";
			echo "<input type='submit' name = 'cancel' value='�ꗗ�ɖ߂�'
					class='button' style = 'height:30px;'>";
			echo "</form></div>";
		}
		else
		{
			echo "<form action='pageJump.php' method='post'><div class = 'left'>";
			echo makebutton($filename,'top');
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>".$title1.$title2."</a>";
			echo "</form>";
			if(!empty($message))
			{
				echo '<br>'.$message.'<br><br>';
			}
			echo "</div>";
			echo make_pjdel($_SESSION['kobetu']['id']);
			echo "<div class = 'center'>";
			echo "<form action='listJump.php' method='post'>";
			if(empty($message))
			{
				echo "<input type='submit' name = 'delete' value='�폜'
						class='button' style = 'height:30px;'>";
			}
			echo "<input type='submit' name = 'cancel' value='�ꗗ�ɖ߂�'
					class='button' style = 'height:30px;'>";
			echo "</form></div>";
		}
	}
	else
	{
		$judge = false;
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "<br><br><div = class='center'>";
		echo "<a class = 'title'>".$title1.$title2."�s��</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>���̒[���ł��łɃf�[�^���폜����Ă��邽�߁A".$title2."�ł��܂���B</a>
				</div>";
		echo '<form action="listJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "�ꗗ�ɖ߂�" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>

<script language="JavaScript"><!--

	window.onload = function(){
		var filename  = '<?php echo $judge ?>';
		var judge = '<?php echo $judge ?>';
		if(judge == 1)
		{
			if(confirm("���͓��e����m�F�B\n���폜���܂�����낵���ł����H\
						\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
			{
				location.href = "./deleteComp.php";
			}
		}
		else if(judge == 2)
		{
			if(confirm("�i����񂪓o�^����Ă��܂��B\n�i�������폜����܂�����낵���ł����H\
						\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
			{
				location.href = "./deleteComp.php";
			}

		}
	}
--></script>
</body>

</html>