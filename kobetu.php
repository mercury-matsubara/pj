<?php
	session_start();
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
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	$filename = $_SESSION['filename'];
	$_SESSION['kobetu']['id'] = $_SESSION['list']['id'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = '�Ј��ʋ��z�ݒ�';
	$title2 = '';
	$isMaster = false;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
//	$form = makeformSerch_set($_SESSION['list'],"form");
	
	$form = getPJdata($_SESSION['list']['id']);
	
	
	if($filename == 'PJTOUROKU_2' || $filename == 'EDABANINFO_2')
	{
		$sql[0] = $SQL_ini[$filename]['sql2'];
		$sql[1] = $SQL_ini[$filename]['sql1'];
		$_SESSION['list']['limitstart'] = 0;
		//$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div></form>";
	echo "<form action='listJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	echo "<input type ='submit' value = '�߂�' name = 'cancel' class = 'free'>";
	echo "</div></form>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return goukeiCheck();">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>��������</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
//	echo '<input type="submit" name="serch" value = "�\��" class="free" >';
	echo "</td></tr></table>";
	
	if($filename == 'PJTOUROKU_2' || $filename == 'EDABANINFO_2')
	{
		echo "<table><tr><td>���v���z : </td><td>";
		echo "<input type = 'text' value = '".$_SESSION['kobetu']['totalCharge']."' id = 'chage' name = 'chage' class = 'readOnly' size = 40 readonly >";
		echo "</td></tr></table>";
	}
	
	echo $list;
	echo "<div class = 'left'>";
	echo "<input type ='submit' name = 'syukka' class='free' value = '�ݒ�'>";
	echo "</div>";
	echo "</div>";
	echo "</form>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="alldelJump.php" method="post" onsubmit = "return delmessage();">';
	echo "<input type ='hidden' name = 'id' class='free' value = '".$_SESSION['list']['id']."'>";
	echo "<input type ='submit' name = 'alldel' class='free' value = '�N���A'>";
	echo "</form>";
        if($filename != 'EDABANINFO_2')
        {
                echo '<form name ="form" action="listJump.php" method="post" onsubmit = "return delmessage2();">';
                echo "<input type ='hidden' name = 'id' class='free' value = '".$_SESSION['list']['id']."'>";
                echo "<input type ='submit' name = 'delete' class='free' value = '�v���W�F�N�g�폜'>";
                echo "</form>";
        }
	echo "</div>";
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./syukkacheck.js'></script>
<script language="JavaScript"><!--
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
			{
				judge = false;
			}
		}
		return judge;
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
	function goukeiCheck()
	{
		var total = "<?php echo $_SESSION['kobetu']['total']; ?>";
		var id = 'kobetu_' + total + '_1';
		if(inputcheck(id,8,7,0,2))
		{
			if(document.getElementById('chage').value == document.getElementById('PJCharge').value)
			{
				if(confirm("���͓��e����m�F�B\n�L�����z�Ōʋ��z��ݒ肵�܂�����낵���ł����H\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				if(confirm("���͓��e����m�F�B\n�v���W�F�N�g���z�ƍ��v���z���قȂ�܂��B\n���v���z�Ńv���W�F�N�g���z��ύX���܂�����낵���ł����H\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
	
	function delmessage()
	{
		if(confirm("�l�ʃv���W�F�N�g�����폜���܂��B��낵���ł����H\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function delmessage2()
	{
		if(confirm("�v���W�F�N�g���폜���܂��B��낵���ł����H\n�ēx�m�F����ꍇ�́u�L�����Z���v�{�^���������Ă��������B"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
--></script>
</head>
<body>
</body>
</html>
