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
*                                          ver 1.0.0  2014/05/09                                             *
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
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$code = "";
	$mail = array();
	if(isset($_SESSION['list']['checkdata']))
	{
		$code = $_SESSION['list']['checkdata'];
	}
?>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link rel='stylesheet' href='./jquery-ui-1.10.3.custom.css' />
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery-ui-1.10.3.custom.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./Mail.js'></script>
<script language="JavaScript"><!--
	
	var idnum = -1;
	var count_ajax = 0;
	var iserror = false;
	var dialog_w = 600;
	window.name = "Modal";																						//�@submit�{�^���ōX�Ɏq��ʊJ���Ȃ��悤��
	var code = "<?php echo $code; ?>";
	
	$(window).resize(function()
	{
		var w = $(window).width();
		var h = $(window).height();
		var d_w = $('div.center').width ();
		dialog_w = w - 200;
		if(dialog_w < 400)
		{
			dialog_w = w;
		}
		$('input#title').css({
			width : (dialog_w - 70)
		});
		$('textarea#sentence').css({
			width : (dialog_w - 70),
			height : (h - 220)
		});
		$('div.center').css({
			width : (w)
		});
		var t =  $('table.mail').width();
		var t1 =  $('table#button').width();
		var width_div = 0;
		var width_div1 = 0;
		width_div = w/2 - (t)/2;
		width_div1 = w/2 - (t1)/2;
		$('td#space_mail_1').css({
			width : width_div
		});
		$('td#space_button_1').css({
			width : width_div1
		});
		$('#dialog').dialog({
			width: dialog_w
		});
//		var click1 = document.getElementById('mail');
//		if(this.fireEvent)
//		{ // for IE
//			this.fireEvent("onclick");
//			alert('IE');
//		}
//		else
//		{ // for Firefox, Chrome, Safari
//			var evt = document.createEvent("MouseEvents");
//			evt.initEvent("click", false, true);
//			this.dispatchEvent(evt);
//			alert('���̑�');
//		}
	});

	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		var w = $(window).width();
		var h = $(window).height();
		dialog_w = w - 200;
		if(dialog_w < 400)
		{
			dialog_w = w;
		}
		$('input#title').css({
			width : (dialog_w - 70)
		});
		$('textarea#sentence').css({
			width : (dialog_w - 70),
			height : (h - 220)
		});
		var t =  $('table.mail').width();
		var t1 =  $('table#button').width();
		var width_div = 0;
		var width_div1 = 0;
		width_div = w/2 - (t)/2;
		width_div1 = w/2 - (t1)/2;
		$('td#space_mail_1').css({
			width : width_div
		});
		$('td#space_button_1').css({
			width : width_div1
		});
		
		
		$('#dialog').dialog({
			autoOpen: false,
			width: dialog_w,
			title : "�ʕ��ʊm�F",
			modal : true,
			buttons:
			{
				'bt1':
				{ 
					text : "���ʍX�V",
					name : "mod",
					id : "modify",
					click : function() {
						kousinn();
					}
				},
				'bt3': {
					text : "����",
					name : "cls",
					id : "close",
					click : function() {
						$('#dialog').dialog('close');
					}
				}
			}
		});
		$('#dialog2').dialog({
			autoOpen: false,
			width: dialog_w,
			title : "���[�����M���c",
			modal : true,
			open:function(event, ui){ $(".ui-dialog-titlebar-close").hide();send_mail();}
		});
		set_button_size();
		
		
	});
	
	
	
	function check_mail(id)
	{
		var id_array = id.split("_");
		var checknum = id_array[1];
		var title = document.getElementById('title'+checknum).value;
		var sentence = document.getElementById('sentence'+checknum).value;
		document.getElementById('title').value = title;
		document.getElementById('sentence').value = sentence;
		idnum = id_array[1];
		$('#dialog').dialog('open');
	}
	function kousinn()
	{
		if(idnum != -1)
		{
			var title = document.getElementById('title').value;
			var sentence = document.getElementById('sentence').value;
			document.getElementById('title'+idnum).value = title;
			document.getElementById('sentence'+idnum).value = sentence;
		}
	}
	
	function closewindow()
	{
		close();
	}
	
	
// --></script>
</head>
<body>

<?php
	if($code != '')
	{
		$checkadress = "";
		$disabled = "disabled";
		$mail = make_mail($code,$tablenum);
		$result_mail = make_mail_radio($mail[3],$mail[0]);
		echo "<br>";
		echo "<div class='center'>";
		echo "<a class = 'title'> ���[�����s </a>";
		echo "</a></div>";
		echo '<form name ="form" action="MailJump.php"  target = "Modal" method="post">';
		for($i = 0; $i < count($mail[0]) ; $i++)
		{
			echo "<input type ='hidden' name = 'adress[".$i."]' id='adress".$i."'
					 value ='".$mail[0][$i]."' >";
			echo "<input type ='hidden' name = 'title[".$i."]' id = 'title".$i."'
					 value ='".$mail[1][$i]."' >";
			echo "<input type ='hidden' name = 'sentence[".$i."]' id = 'sentence".$i."'
					 value ='".$mail[2][$i]."' >";
			$checkadress = trim($mail[0][$i]);
			$checkadress = trim($checkadress,'�@');
			if($checkadress != '')
			{
				$disabled = "";
			}
		}
		echo "<table id = 'space_button'><tr><td id = 'space_button_1'></td><td>";
		echo "<table id='button'><tr>";
		echo "<td><input type ='button' name = 'mail_send' class='free' "
				.$disabled." value = '�S�����M' onClick = 'click_send();'></td>";
		echo '<td><input type="button" class="free" value ="�L�����Z��" 
				onClick="closewindow();" ></td>';
		echo "</tr></table>";
		echo "</td></tr></table>";
		echo "<div class = 'center'>";
		echo $result_mail[1];
		echo "</div>";
		if($mail[4] != 0)
		{
			echo "<div class = 'center'>";
			echo "<a class = 'error' >".$mail[4]."���̃f�[�^���폜����Ă��܂����B</a>";
			echo "</div>";
		}
		echo "<div class = 'center'>";
		echo "<a class = 'error' >���[�����M������ꎞ�Ԃ�1000���E���10000���ƒ�߂��Ă��܂��B</a>";
		echo "</div>";
		echo "<div class = 'center'>";
		echo "<a class = 'error' >����𒴂��܂��ƈꎞ�Ԃ��������[���̑��M�ł��Ȃ��Ȃ�܂��B</a>";
		echo "</div>";
		echo "<table id = 'space_mail'><tr><td id = 'space_mail_1'></td><td>";
		echo $result_mail[0];
		echo "</td></tr></table>";
		echo "<br>";
		echo "</div>";
		echo "</form>";
		echo "<div id ='dialog'>";
		echo "<table id = 'editer'><tr><td>";
		echo "<a class = 'itamname'>����</a></td>";
		echo "<td><input type ='textbox' name ='title_text'  id='title' ></td></tr>";
		echo "<tr><td class='top' ><a class = 'itamname'>�{��</a></td>";
		echo '<td><textarea id="sentence" name="sentence_text" ></textarea></td>';
		echo "</table>";
		echo "</div>";
		echo "<div id = 'dialog2'>";
		echo "���[�����M���ł��B���΂炭���҂����������B";
		echo "</div>";
	}
?>

	
</body>
</html>
