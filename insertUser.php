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
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>�Ǘ��ғo�^</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
        var isCancel = false;
	
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
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		var isenpty = false;
                if(isCancel == false)
                {
                        for (var i = 0 ; i < checkListArray.length ; i++ )
                        {
                                var param = checkListArray[i].split("_");
                                if(!inputcheck(param[0],param[1],param[2]))
                                {
                                        judge = false;
                                }
                                if(document.getElementById(param[0]).value =="")
                                {
                                        judge = false;
                                        document.getElementById(param[0]).style.backgroundColor = '#ff0000';
                                        isenpty = true;
                                }
                        }
                        if(isenpty)
                        {
                                window.alert('���ڂ���͂��Ă��������B');
                        }
                        if (document.getElementById('pass').value != document.getElementById('passCheck').value)
                        {
                                judge = false;
                                window.alert('�p�X���[�h�Ɗm�F�p�p�X���[�h�̓��e����v���Ă��܂���B');
                        }
                }
		return judge;
	}
--></script>
</head>

<body>

<?php
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	$checkList = "uid_20_3,pass_20_3,passCheck_20_3";
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "<div class = 'center'>";
	echo "<a class = 'title'>�Ǘ��ғo�^</a>";
	echo "</div><br><br>";
	echo '<form action="insertUserJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td class = 'space'></td><td class = 'one'>�Ǘ���ID</td>";
	echo '<td class = "two"><input type = "text" size = "30"  name = "uid"  id="uid" 
				onchange ="return inputcheck(\'uid\',20,3);"></td>';
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>�p�X���[�h</td>";
	echo '<td class = "two"><input type = "password" size = "31" name = "pass"  id="pass" 
				onchange ="return inputcheck(\'pass\',20,3);"></td>';
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>�m�F�p�p�X���[�h</td>";
	echo '<td class = "two"><input type = "password" size = "31" name = "passCheck" id="passCheck" 
				onchange ="return inputcheck(\'passCheck\',20,3);"></td>';
	echo "</tr></table>";
	echo "<br>";
	echo "<div class = 'center'>";
	echo "<input type='submit' name = 'insert' value = '�o�^' class='free'>";
//        echo '<input type="submit" name = "back" value = "�ꗗ�ɖ߂�" 
//				class = "free" onClick ="isCancel = true;">';
	echo "</div>";
	echo "</form>";
?>


</html>
