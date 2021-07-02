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
	
	if(isset($_POST))
	{
		$_SESSION['insert'] = $_POST;
	}
	else
	{
		$_SESSION['insert'] = array();
	}
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = 'ìoò^';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = 'ìoò^';
		$isMaster = true;
		$isReadOnly = true;
		break;
	default:
		$title2 = '';
	}
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$edalist = edaget();
	$koulist = kouget();
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./saiban.js'></script>
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
		var filename = "<?php echo $filename; ?>";
		if(type===0)
		{
			if(str.match(/[^0-9\.]+/))
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('îºäpêîéöÇ∆ÉsÉäÉIÉhÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==1)
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
				window.alert('ëSäpÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
				window.alert('îºäpÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
				window.alert('îºäpâpêîÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
				window.alert('îºäpêîéöÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
	//	if (size < (str.length))
		if (size < strlen(str) && isJust == 2)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
				window.alert(size+'ï∂éöà»ì‡Ç≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
		else if (size != strlen(str) && strlen(str) != 0 && isJust == 1)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
				window.alert(size+'ï∂éöÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
				window.alert('ílÇì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
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
					window.alert('îºäpêîéöÇ≈ì¸óÕÇµÇƒÇ≠ÇæÇ≥Ç¢');
					judge = false;
				}
				else
				
				{
					Charge += parseInt(document.getElementById(id).value);
				}
			}
			document.getElementById('chage').value = Charge;
		}
/*		//èdï°É`ÉFÉbÉN
				if(filename == 'EDABANINFO_1')
		{
			var edaitem = "<?php echo $edalist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			if(name == 'form_202_0')
			{
				while(numcnt < edaitem.length - 1)
				{
					if (str == edaitem[numcnt + 0])
					{
						judge = false;
						alert('Ç±ÇÃé}î‘ÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
			else
			{
				while(numcnt < edaitem.length - 1)
				{
					if (str == edaitem[numcnt + 1])
					{
						judge = false;
						alert('Ç±ÇÃêªî‘ÅEàƒåèñºÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
		}
		if(filename == 'EDABANINFO_1')
		{
			var edaitem = "<?php echo $edalist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			if(name == 'form_202_0')
			{
				while(numcnt < edaitem.length - 1)
				{
					if (str == edaitem[numcnt + 0])
					{
						judge = false;
						alert('Ç±ÇÃé}î‘ÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
			else
			{
				while(numcnt < edaitem.length - 1)
				{
					if (str == edaitem[numcnt + 1])
					{
						judge = false;
						alert('Ç±ÇÃêªî‘ÅEàƒåèñºÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
		}
*/
		if(filename == 'KOUTEIINFO_1')
		{
			var kouitem = "<?php echo $koulist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			if(name == 'form_302_0')
			{
				while(numcnt < kouitem.length - 1)
				{
					if (str == kouitem[numcnt + 0])
					{
						judge = false;
						alert('Ç±ÇÃçHíˆî‘çÜÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
			else
			{
				while(numcnt < kouitem.length - 1)
				{
					if (str == kouitem[numcnt + 1])
					{
						judge = false;
						alert('Ç±ÇÃçHíˆÇÕìoò^çœÇ›Ç≈Ç∑');
						document.getElementById(name).style.backgroundColor = '#ff0000';
						break;
					}
					numcnt = numcnt + 2;
				}
			}
		}
		if(filename == 'EDABANINFO_1' && name == 'form_403_0')
		{
			var syaitem = "<?php echo $syalist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			while(numcnt < syaitem.length - 1)
			{
				if (str == syaitem[numcnt + 0])
				{
					judge = false;
					alert('Ç±ÇÃé–àıî‘çÜÇÕìoò^çœÇ›Ç≈Ç∑');
					document.getElementById(name).style.backgroundColor = '#ff0000';
					break;
				}
				numcnt = numcnt + 2;
			}
		}
		return judge;
	}


	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		var filename = "<?php echo $filename; ?>";
		if(isCancel == false)
		{
			if(filename == 'PROGRESSINFO_1')
			{
				if(document.getElementById('form_102_0').value == "")
				{
					document.getElementById('form_102_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_202_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_203_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_402_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_403_0').style.backgroundColor = '#ff0000';
					window.alert('ÉvÉçÉWÉFÉNÉgÇëIëÇµÇƒÇ≠ÇæÇ≥Ç¢');
					judge = false;
				}
				else
				{
					document.getElementById('form_102_0').style.backgroundColor = '';
					document.getElementById('form_202_0').style.backgroundColor = '';
					document.getElementById('form_203_0').style.backgroundColor = '';
					document.getElementById('form_402_0').style.backgroundColor = '';
					document.getElementById('form_403_0').style.backgroundColor = '';
				}
				if(document.getElementById('form_302_0').value == "")
				{
					document.getElementById('form_302_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_303_0').style.backgroundColor = '#ff0000';
					window.alert('çHíˆÇëIëÇµÇƒÇ≠ÇæÇ≥Ç¢');
					judge = false;
				}
				else
				{
					document.getElementById('form_302_0').style.backgroundColor = '';
					document.getElementById('form_303_0').style.backgroundColor = '';
				}
				var checkListArray = checkList.split(",");
				var notNullArray = notnullcolumns.split(",");
				var notNullTypeArray = notnulltype.split(",");
				for (var i = 0 ; i < checkListArray.length ; i++ )
				{
					var param = checkListArray[i].split("~");
					if((param[0] == 'form_102_0') || (param[0] == 'form_202_0') || (param[0] == 'form_203_0') || (param[0] == 'form_302_0')|| (param[0] == 'form_303_0') || (param[0] == 'form_402_0') || (param[0] == 'form_403_0'))
					{
						continue;
					}
					if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
					{
						judge = false;
					}
				}
				for(var i = 0 ; i < notnullcolumns.length ; i++ )
				{
					var formelements = document.forms["insert"];
					for(var j = 0 ; j < formelements.length ; j++ )
					{
						if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
						{
							var tagname = formelements.elements[j].tagName;
							if(tagname == 'SELECT')
							{
								var selectnum = formelements.elements[j].selectedIndex;
								if(formelements.elements[j].options[selectnum].value == "")
								{
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('ílÇëIëÇµÇƒâ∫Ç≥Ç¢');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
						}
					}
				}
			}
			else
			{
				var checkListArray = checkList.split(",");
				var notNullArray = notnullcolumns.split(",");
				var notNullTypeArray = notnulltype.split(",");
				for (var i = 0 ; i < checkListArray.length ; i++ )
				{
					var param = checkListArray[i].split("~");
					if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
					{
						judge = false;
					}
				}
				for(var i = 0 ; i < notnullcolumns.length ; i++ )
				{
					var formelements = document.forms["insert"];
					for(var j = 0 ; j < formelements.length ; j++ )
					{
						if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
						{
							var tagname = formelements.elements[j].tagName;
							if(tagname == 'SELECT')
							{
								var selectnum = formelements.elements[j].selectedIndex;
								if(formelements.elements[j].options[selectnum].value == "")
								{
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('ílÇëIëÇµÇƒâ∫Ç≥Ç¢');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
						}
					}
				}
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
		url = 'Modal.php?tablenum='+GET+'&form=insert';
		n = showModalDialog(
			url,
			this,
//			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
		);
	
	}
	function AddTableRows(id){
		var table01 = document.getElementById('insert');
		var tr = table01.getElementsByTagName("TR");
		var tr_count = tr.length;
		var start = true;
		var start_count = 0;
		var end =true;
		var end_count = 0;
		totalcount++;
		for(count=0 ; count < tr_count ; count++)
		{
			if(tr[count].id==id){
				if(start)
				{
					start_count = count;
					start =false;
				}
			}
			else
			{
				if(start == false)
				{
					if(end)
					{
						end_count = count;
						end = false;
					}
				}
			}
		}
		if(end_count==0)
		{
			end_count=tr_count;
		}
		rows = new Array();
		cells = new Array();
		for(counter=0; counter<(end_count-start_count) ; counter++)
		{
			var row = table01.insertRow((end_count+counter));
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var row2 = table01.rows[start_count+counter];
			var cell4 = row2.cells[2];
			var cell5 = row2.cells[1];
			cell3.innerHTML = cell4.innerHTML;
			cell2.innerHTML = cell5.innerHTML;
			
			var inp = cell3.getElementsByTagName("INPUT");
			for( var count = 0, len = inp.length; count < len; count++ ){
				var id = inp[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
			var inp2 = cell3.getElementsByTagName("SELECT");
			for( var count = 0, len = inp2.length; count < len; count++ ){
				var id = inp2[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
		}
		totalcount++;
	}

--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$out_column ='';
	$form = makeformInsert_set($_SESSION['insert'],$out_column,$isReadOnly,"insert");
	$checkList = $_SESSION['check_column'];
	$notnullcolumns = $_SESSION['notnullcolumns'];
	$notnulltype = $_SESSION['notnulltype'];
	echo "<form action='pageJump.php' method='post'><div class='left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "<form action='insertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	echo "<input type ='submit' value = 'ñﬂÇÈ' name = 'back' class = 'free'>";
	echo "</div></form>";
	echo "<div style='clear:both;'></div>";
	echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check(\''.$checkList.
				'\',\''.$notnullcolumns.
				'\',\''.$notnulltype.'\');">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br><br>";
	echo $form;
	echo "</tr></table>";
	echo "<div class = 'center'>";
	echo '<input type="submit" name = "insert" value = "ìoò^" class="free">';
	echo '<input type="submit" name = "cancel" value = "ÉNÉäÉA" class="free" onClick ="isCancel = true;">';
//	echo '<input type="submit" name = "back" value = "ñﬂÇÈ" class="free" onClick ="isCancel = true;">';
	echo "</form>";
	echo "</div>";
?>
</body>
</html>


