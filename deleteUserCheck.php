<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
?>
<!DOCTYPE html>
<?php
	require_once("f_DB.php");
	$isexist = true;
	$checkResultarray = selectID($_SESSION['listUser']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>�Ǘ��폜�m�F</title>
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
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$pass = "";
	$passLength = 0;
	$passLength = mb_strlen( $_SESSION['result_array']['LUSERPASS'] ,"UTF-8");
	for ($i = 0; $i < $passLength ; $i++)
	{
		$pass .="��";
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	
	if($isexist)
	{
		echo "<center>";
		echo "<a class = 'title'>�Ǘ��폜�m�F</a>";
		echo "<br><br>";
		$_SESSION['pre_post'] = $_SESSION['post'] ;
		$_SESSION['post']['true'] = true;
		echo '<form action="listUserJump.php" method="post">';
		echo "<table><tr><td id = 'item'>�Ǘ���ID</td>";
		echo "<td>".$_SESSION['result_array']['LUSERNAME']."</td>";
		echo "</tr><tr><td id = 'item'>�p�X���[�h</td>";
		echo "<td>".$pass."</td>";
		echo "</tr></table>";
		echo "<br>";
		echo '<input type="submit" name = "delete" value = "�폜" 
				class="free">';
		echo '<input type="submit" name = "cancel" value = "�ꗗ�ɖ߂�" 
				class = "free">';
		echo "</form>";
		echo "</center>";
	}
	else
	{
		echo "<div = class='center'>";
		echo "<a class = 'title'>�Ǘ��ҍX�V�s��</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>���̒[���ł��łɃf�[�^���폜����Ă��邽�߁A�X�V�ł��܂���B</a>
				</div>";
		echo "<br>";
		echo '<form action="listUserJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "�ꗗ�ɖ߂�" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
</body>

<script language="JavaScript"><!--
	window.onload = function(){
		var judge_go = '<?php echo $isexist ; ?>';
		if(judge_go)
		{
			if(confirm("���͓��e����m�F�B\n���X�V���܂�����낵���ł����H" +
				"\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
			{
				location.href = "./deleteUserComp.php";
			}
		}
	}
--></script>

</html>
