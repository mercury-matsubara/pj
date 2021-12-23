<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	$title .= "��������";
	$_SESSION['post'] = $_SESSION['pre_post'];
	unset($_SESSION['pre_post']);
	$_SESSION['nenzi']['kimatagi'] = true;
//	$message = genbaend($_SESSION['list']);
	$isReadOnly = true;
	$out_column ='';
	$form = makeformInsert_set("",$out_column,$isReadOnly,"insert");
	$checkList = $_SESSION['check_column'];
	$notnullcolumns = $_SESSION['notnullcolumns'];
	$notnulltype = $_SESSION['notnulltype'];
	$columns = "102,202,203";
	// DB�擾
	$id = $_SESSION['list']['5CODE'];
	$con = dbconect();																									// db�ڑ��֐����s
	$judge = false;
	$sql[1] = "SELECT * FROM projectinfo, projectnuminfo, edabaninfo WHERE projectinfo.1CODE = projectnuminfo.1CODE AND projectinfo.2CODE = edabaninfo.2CODE AND projectinfo.5CODE = '".$id."' ;";
	$result = $con->query($sql[1]) or ($judge = true);																	// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION['list']['temp'] = $result_row['5CODE'] ;//5�R�[�h
		$_SESSION['list']['form_102_0'] = $result_row['PROJECTNUM'] ;//PJ�R�[�h
		$_SESSION['list']['form_202_0'] = $result_row['EDABAN'] ;//�}�ԃR�[�h
		$_SESSION['list']['form_203_0'] = $result_row['PJNAME'] ;//���ԁE�Č���
	}
	$modal = makeformModal_set($_SESSION['list'],'readOnly','drop',$columns);
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
<head>
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
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
	
	function inputcheck(name,size,type,isnotnull,isJust){
		var judge =true;
		var str = document.getElementById(name).value;
		m = String.fromCharCode(event.keyCode);
		var len = 0;
		var str2 = escape(str);
		if(type===0)
		{
			if(str.match(/[0-9a-zA-Z\. ]+/g))
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('���p�����ƃs���I�h�œ��͂��Ă�������');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else 
		if(type==1)
		{
			for(i = 0; i < str2.length; i++, len++){
				if(str2.charAt(i) == "%"){
					if(str2.charAt(++i) == "u"){
						i += 3;
						len++;
					}
					else
					{
						judge=false;
					}
					i++;
				}
				else
				{
					judge=false;
				}
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('�S�p�œ��͂��Ă�������');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==2)
		{
			for(i = 0; i < str2.length; i++, len++){
				if(str2.charAt(i) == "%"){
					if(str2.charAt(++i) == "u"){
						i += 3;
						len++;
						judge=false;
					}
				}
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('���p�œ��͂��Ă�������');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==3)
		{
			if(str.match(/[^0-9A-Za-z]+/)) 
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('���p�p���œ��͂��Ă�������');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==4 || type==7 )
		{
			if(str.match(/[^0-9]+/)) 
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('���p�����œ��͂��Ă�������');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
	//	if (size < (str.length))
//		if (size < strlen(str) && isJust == 2)
		if (size < (str.length) && isJust == 2)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
				window.alert(size+'�����ȓ��œ��͂��Ă�������');
			}
			document.getElementById(name).style.backgroundColor = '#ff0000';
			judge = false;
		}
		else if(isJust == 2)
		{
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
		}
//		else if (size != strlen(str) && strlen(str) != 0 && isJust == 1)
		else if (size != (str.length) && (str.length) != 0 && isJust == 1)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
				window.alert(size+'�����œ��͂��Ă�������');
			}
			document.getElementById(name).style.backgroundColor = '#ff0000';
			judge = false;
		}
		else if(isJust == 1)
		{
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
		}
		
		if(isnotnull == 1)
		{
			if(document.getElementById(name).value == '')
			{
				document.getElementById(name).style.backgroundColor = '#ff0000';
				judge = false;
				window.alert('�l����͂��Ă�������');
			}
			else if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
		}
		if(judge == true && type==7)
		{
			var name_array =  name.split("_");
			var total = name_array[1];
			var Charge = 0 ;
			for(var i = 1 ; i <= total ; i++)
			{
				var id = 'kobetu_'+ total + '_' + i;
				if(document.getElementById(id).value == '')
				{
					Charge += 0;
				}
				else if(document.getElementById(id).value.match(/[^0-9]+/))
				{
					document.getElementById(id).style.backgroundColor = '#ff0000';
					window.alert('���p�����œ��͂��Ă�������');
					judge = false;
				}
				else
				
				{
					Charge += parseInt(document.getElementById(id).value);
				}
			}
			document.getElementById('chage').value = Charge;
		}

		return judge;
	}
	
	function check()
	{
		var judge = true;
		if(document.getElementsByName('1CODE')[(document.getElementsByName('1CODE').length-1)].value == '')
		{
			document.getElementById('form_102_0').style.backgroundColor = '#ff0000';
			document.getElementById('form_103_0').style.backgroundColor = '#ff0000';
			window.alert('�v���W�F�N�g��I�����Ă�������');
			judge = false;
		}
		if(document.getElementsByName('2CODE')[(document.getElementsByName('2CODE').length-1)].value == '')
		{
			document.getElementById('form_202_0').style.backgroundColor = '#ff0000';
			document.getElementById('form_203_0').style.backgroundColor = '#ff0000';
			window.alert('�}�Ԃ�I�����Ă�������');
			judge = false;
		}
		if(document.getElementById("charge").value == '' || document.getElementById("charge").value == 0 )
		{
			document.getElementById('charge').style.backgroundColor = '#ff0000';
			window.alert('���z����͂��Ă�������');
			judge = false;
		}
		return judge;
	}
	
	function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.8);
		h = (h * 0.8);
		url = 'Modal.php?tablenum='+GET+'&form=insert';
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

--></script>
</head>
<body>
<?php
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton();
		echo "</form>";
		echo '<form name ="form" action="nenziJump.php" method="post">';
		echo "<input type ='submit' value = '�߂�' name = 'cancel' class = 'free'>";
		echo "</form>";
		echo "</div>";
		echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
					onsubmit = "return check();">';
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>���܂�������</a>";
		echo "</div>";
		echo "<table><tr><td>";
		echo "<fieldset><legend>�O���o�i</legend>";
		echo $modal;
		echo "</td></tr></table>";
		echo "<div class = 'center' >";
		echo "<br><br><br><FONT color='red'>���܂�����v���W�F�N�g��I�����Ă��������B<br>�����ɐV�K�o�^���邽�߁A�I������v���W�F�N�g�̃v���W�F�N�g�R�[�h�y�ю}�ԃR�[�h�͎��O�ɍ쐬���Ă��������B</FONT><br><br>";
		echo "</div></div><br><br>";
		echo $form;
		echo "<input type='hidden' name = 'temp' value = '".$id."'>";
		echo "<td class = 'space'></td><td class ='one'><a class = 'itemname'>���z</a></td><td class = 'two'><input type='text' name = 'charge' id = 'charge' value = ''></td>";
		echo "</tr></table>";
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "mid" value = "�ݒ�" class="free">';
		echo "</form>";
		echo "</div>";
?>
</body>
</html>
