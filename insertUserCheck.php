<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
?>
<!DOCTYPE html>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>���[�U�[�o�^�ē���</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	var button = "";
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
		var result = false;
		if(button == 'insert')
		{
			var judge = true;
			var isenpty = false;
			var checkListArray = checkList.split(",");
			for (var i = 0 ; i < checkListArray.length ; i++ )
			{
				var param = checkListArray[i].split("_");
				if(inputcheck(param[0],param[1],param[2]) == false)
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
            
            //�Ј��ԍ����̓`�F�b�N
            if(document.getElementById('form_402_0').value == "")
            {
                judge = false;
                document.getElementById('form_402_0').style.backgroundColor = '#ff0000';
                isenpty = true;
            }
            
            //�Ј������̓`�F�b�N
            if(document.getElementById('form_403_0').value == "")
            {
                judge = false;
                document.getElementById('form_403_0').style.backgroundColor = '#ff0000';
                isenpty = true;
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
			result = judge;
		}
		if(button == 'cancel')
		{
			result = true;
		}
        
        if(button == 'back')
        {
            result = true;
        }
		return result;
	}
	
	function set_button(buttonName)
	{
		button = buttonName;
	}
    
    function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.8);
		h = (h * 0.8);
		url = 'Modal.php?tablenum='+GET+'&form=edit';
//		n = showModalDialog(
//			url,
//			this,
////			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}
    
    //�u���E�U�o�b�N�h�~
    window.addEventListener('pageshow', function() { 
        if (event.persisted) {
            window.location.href = 'retry.php';
        } else {
            
        }    
      });

--></script>
</head>

<body>

<?php
	require_once("f_DB.php");
	$judge = false;
	$columnName = UserCheck($_SESSION['insertUser']['uid'],$_SESSION['insertUser']['pass']);
	if ($columnName == "")
	{
		$judge = true;
		$_SESSION['pre_post'] = $_SESSION['post'];
		$_SESSION['post']['true'] = true;
	}
	else
	{
		$judge = false;
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	$checkList = "uid_20_3,pass_20_3,passCheck_20_3";
    
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "<div class='center'>";
	echo "<a class = 'title'>���[�U�[�o�^�ē���</a>";
	echo "</div><br><br>";
	echo '<form action="insertUserJump.php" method="post"
			 onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table>";
    echo "<tr><td class='space'></td><td></td><td>";
    echo '<input type="button" value="�Ј��I��" onclick="popup_modal(\'4\')">';
    echo "<input type='hidden' name='4CODE' value='".$_SESSION['insertUser']['4CODE']."'>";
    echo "</td></tr>";
    echo "<tr><td class='space'></td><td class='one'>�Ј��ԍ�</td>";
    echo "<td><input type='text' name='form_402_0' id='form_402_0' value='".$_SESSION['insertUser']['form_402_0']."' readonly class='readOnly' size='20px' onchange='retrun inputcheck('form_402_0',6,4,1,1);'></td></tr>";
    echo "<tr><td class='space'></td><td class='one'>�Ј���</td>";
    echo "<td><input type='text' name='form_403_0' id='form_403_0' value='".$_SESSION['insertUser']['form_403_0']."' readonly class='readOnly' size='60px' onchange='return inputcheck('form_402_0',6,4,1,1);'></td></tr>";
    echo "<tr><td class = 'space'></td><td class = 'one'>���[�U�[ID</td>";
	echo '<td class = "two"><input type = "text" size = "30" name = "uid" id="uid" 
			value ="'.$_SESSION['insertUser']['uid'].'" onchange ="return inputcheck(\'uid\',20,3);"></td>';
	if(!$judge)
	{
		echo "<td><a class ='error'>���ɓo�^����Ă��܂��B</a></td>";
	}
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>�p�X���[�h</td>";
	echo '<td class = "two"><input type = "password" size = "31" name ="pass" id="pass" 
			value = "'.$_SESSION['insertUser']['pass'].'" onchange ="return inputcheck(\'pass\',20,3);"></td>';
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>�m�F�p�p�X���[�h</td>";
	echo '<td class = "two"><input type = "password" size = "31" name = "passCheck" id="passCheck" 
			value = "'.$_SESSION['insertUser']['passCheck'].'"onchange ="return inputcheck(\'passCheck\',20,3);"></td>';
	echo "</tr></table>";
	echo "<br>";
	echo "<div class = 'center'>";
	echo '<input type="submit" name="insert" value = "�o�^" class="free"
			 onClick="set_button(\'insert\');">';
	echo '<input type="submit" name="cancel" value = "�L�����Z��" class="free"
			 onClick="set_button(\'cancel\');">';
    //�ꗗ�ɖ߂�{�^���ǉ�
    echo '<input type="submit" name="back" value = "�ꗗ�ɖ߂�" class="free"
             onClick="set_button(\'back\');">';
	echo "</div>";
	echo "</form>";
?>

<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("���͓��e����m�F�B\n���o�^���܂�����낵���ł����H" +
						"\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
			{
				location.href = "./insertUserComp.php";
			}
		}
	}
--></script>

</html>
