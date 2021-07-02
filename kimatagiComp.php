<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
//	print_r($_SESSION);
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	unset($_SESSION['nenzi']['kimatagi']);
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	$title .= "��������";
	if($filename == 'nenzi_5')
	{
		$_SESSION['post'] = $_SESSION['pre_post'];
		unset($_SESSION['pre_post']);
		$flag = true;
		//���܂���
		//POST�f�[�^�擾
		$org5code = $_SESSION['kimatagi']['5CODE'];
		$orgpjcode = $_SESSION['kimatagi']['nextcode'];
		$orgedaban = $_SESSION['kimatagi']['nextedaban'];
		$orgpjname = $_SESSION['kimatagi']['nextname'];
		$addpjnum = $_SESSION['kimatagi']['1CODE'];
		$addadanum = $_SESSION['kimatagi']['2CODE'];
		$addcharge = $_SESSION['kimatagi']['charge'];
		$nowpjcode = $_SESSION['kimatagi']['nowcode'];
		$nowedaban = $_SESSION['kimatagi']['nowedaban'];
		$nowpjname = $_SESSION['kimatagi']['nowname'];
		$nowcharge = $_SESSION['kimatagi']['nowcharge'];
		$message = "";
		
		$judge = false;
		$con = dbconect();																									 //db�ڑ��֐����s
		$sql = "INSERT INTO projectinfo (1CODE,2CODE,CHARGE,5PJSTAT) VALUES (".$addpjnum.",".$addadanum.",".$addcharge.",1) ;";
		$result = $con->query($sql) or ($judge = true);																	 //�N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
			$flag = false;
			$message .= "����PJ�o�^�����ɂăG���[���������܂����B<br>";
		}
		
		if($flag)
		{
			//�����������z������������
			$sql = "UPDATE projectinfo SET CHARGE =  ".($nowcharge - $addcharge)." WHERE 5CODE = ".$org5code." ;";
			$result = $con->query($sql) or ($judge = true);																	 //�N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
				$flag = false;
				$message .= "�O��PJ�X�V�����ɂăG���[���������܂����B<br>";
			}
		}
		if($flag)
		{
			if('�G���[' == pjend($_SESSION['kimatagi']))
			{
				$flag = false;
				$message .= "PJ�I�������ɂăG���[���������܂����B<br>";
			}
		}
		
		if($flag)
		{
			echo "<left>";
			echo "<form action='pageJump.php' method='post'><div class='left'>";
			echo makebutton($filename,'top');
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "</form>";
			echo "</left>";

//			$isReadOnly = true;
//			$out_column ='';
//			$form = makeformInsert_set("",$out_column,$isReadOnly,"insert");
//			$checkList = $_SESSION['check_column'];
//			$notnullcolumns = $_SESSION['notnullcolumns'];
//			$notnulltype = $_SESSION['notnulltype'];
//			echo "<div style='clear:both;'></div>";
//			echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
//						onsubmit = "return check(\''.$checkList.
//						'\',\''.$notnullcolumns.
//						'\',\''.$notnulltype.'\');">';
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>���܂�������</a>";
			echo "</div>";
			echo "<center><br><br>";
			echo "<table>";
			echo "<tr><td class = 'space'><td class='center' colspan='2'>�O��PJ</td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�v���W�F�N�g�R�[�h</a></td><td class = 'two'><a class = 'comp' >".$nowpjcode."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�}�ԃR�[�h</a></td><td class = 'two'><a class = 'comp' >".$nowedaban."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>���ԁE�Č���</a></td><td class = 'two'><a class = 'comp' >".$nowpjname."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>���z</a></td><td class = 'two'><a class = 'comp' >".($nowcharge - $addcharge)."</a></td></tr>";
			echo "<tr><td class = 'space'><td class='center' colspan='2'>�@</td></tr>";
			echo "<tr><td class = 'space'><td class='center' colspan='2'>����PJ</td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�v���W�F�N�g�R�[�h</a></td><td class = 'two'><a class = 'comp' >".$orgpjcode."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�}�ԃR�[�h</a></td><td class = 'two'><a class = 'comp' >".$orgedaban."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>���ԁE�Č���</a></td><td class = 'two'><a class = 'comp' >".$orgpjname."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>���z</a></td><td class = 'two'><a class = 'comp' >".$addcharge."</a></td></tr>";
			echo "</table>";
	//		echo "</form>";
			echo '<form action="nenziJump.php" method="post">';
			echo "<input type='hidden' name='period_0' value='".$_SESSION['nenzi']['period']."'>";
			echo "<br><input type='submit' name='delete' value = '���܂���' class='free'>";
			echo "<input type='submit' name='push' value = '�N������' class='free'>";
			echo "</form>";
			echo "</center>";
			unset($_SESSION['kimatagi']);
		}
		else
		{
			echo "<left>";
			echo "<form action='pageJump.php' method='post'><div class='left'>";
			echo makebutton($filename,'top');
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo "</form>";
			echo "</left>";
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>���܂��������G���[</a>";
			echo $message;
			echo "</div>";
			echo "<center><br><br>";
			echo "<table>";
			echo "<tr><td class = 'space'><td class='center' colspan='2'>�O��PJ</td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�v���W�F�N�g�R�[�h</a></td><td class = 'two'><a class = 'comp' >".$nowpjcode."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>�}�ԃR�[�h</a></td><td class = 'two'><a class = 'comp' >".$nowedaban."</a></td></tr>";
			echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>���ԁE�Č���</a></td><td class = 'two'><a class = 'comp' >".$nowpjname."</a></td></tr>";
			echo "</table>";
			echo '<form action="nenziJump.php" method="post">';
			echo "<input type='hidden' name='period_0' value='".$_SESSION['nenzi']['period']."'>";
			echo "<br><input type='submit' name='delete' value = '���܂���' class='free'>";
			echo "<input type='submit' name='push' value = '�N������' class='free'>";
			echo "</form>";
			echo "</center>";
		}
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
				.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
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
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
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
</body>
</html>
