<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
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
        
        if($filename == 'TOP_1')
        { 
                $_POST['form_704_0'] = $_SESSION['pre_post']['ym'].$_SESSION['pre_post']['TOP_1_button'].'??';
                $_POST['form_402_0'] = $_SESSION['user']['STAFFID'];
                $_POST['form_403_0'] = $_SESSION['user']['STAFFNAME'];
        }
        
	if(isset($_POST))
	{
		$_SESSION['insert'] = $_POST;
	}
	else
	{
		$_SESSION['insert'] = array();
	}
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	switch ($form_ini[$main_table]['table_type'])
	{
                case 0:
                        $title2 = '?o?^';
                        $isReadOnly = true;
                        break;
                case 1:
                        $title2 = '?o?^';
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
        $syalist = syaget();
        
        $syain_total = 0;
        if($filename == 'PJTOUROKU_1')
	{
		$sql[0] = $SQL_ini[$filename]['sql2'];
		$sql[1] = $SQL_ini[$filename]['sql1'];
		$_SESSION['list']['limitstart'] = 0;
		$list = makeList_item($sql,$_SESSION['list']);
                $syain_total = $_SESSION['kobetu']['total']; 
	}
        if($filename == 'TOP_1')
        { 
                $list = makePROGRESSlist();
        }
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./saiban.js'></script>
<script src="./progress.js"></script>
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

	function inputcheck(name,size,type,isnotnull,isJust)
        {
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
				window.alert('???p?????ƃs???I?h?œ??͂??Ă???????');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==1)
		{
			for(i = 0; i < str2.length; i++, len++)
                        {
				if(str2.charAt(i) == "%")
                                {
					if(str2.charAt(++i) == "u")
                                        {
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
				window.alert('?S?p?œ??͂??Ă???????');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==2)
		{
			for(i = 0; i < str2.length; i++, len++)
                        {
				if(str2.charAt(i) == "%")
                                {
					if(str2.charAt(++i) == "u")
                                        {
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
				window.alert('???p?œ??͂??Ă???????');
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
				window.alert('???p?p???œ??͂??Ă???????');
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
				window.alert('???p?????œ??͂??Ă???????');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
	//	if (size < (str.length))
		if (size < strlen(str) && isJust == 2)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
				window.alert(size+'?????ȓ??œ??͂??Ă???????');
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
				window.alert(size+'?????œ??͂??Ă???????');
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
				window.alert('?l?????͂??Ă???????');
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
					window.alert('???p?????œ??͂??Ă???????');
					judge = false;
				}
				else
				
				{
					Charge += parseInt(document.getElementById(id).value);
				}
			}
			document.getElementById('chage').value = Charge;
		}
/*		//?d???`?F?b?N
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
						alert('???̎}?Ԃ͓o?^?ς݂ł?');
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
						alert('???̐??ԁE?Č????͓o?^?ς݂ł?');
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
						alert('???̎}?Ԃ͓o?^?ς݂ł?');
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
						alert('???̐??ԁE?Č????͓o?^?ς݂ł?');
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
						alert('???̍H???ԍ??͓o?^?ς݂ł?');
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
						alert('???̍H???͓o?^?ς݂ł?');
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
					alert('???̎Ј??ԍ??͓o?^?ς݂ł?');
					document.getElementById(name).style.backgroundColor = '#ff0000';
					break;
				}
				numcnt = numcnt + 2;
			}
		}
		return judge;
	}

        function goukeiCheck()
	{
		var total = "<?php echo $syain_total;?>";
		var id = 'kobetu_' + total + '_1';
		if(inputcheck(id,8,7,0,2))
		{
			if(document.getElementById('chage').value == document.getElementById('form_504_0').value)
			{
				return true;
			}
			else
			{
				if(confirm("???͓??e?????m?F?B\n?v???W?F?N?g???z?ƍ??v???z???قȂ??܂??B\n???v???z?Ńv???W?F?N?g???z???ύX???܂??????낵???ł????H\n?ēx?m?F?????ꍇ?́u?L?????Z???v?{?^?????????Ă????????B"))
				{
                                        document.getElementById('form_504_0').value = document.getElementById('chage').value;
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
					window.alert('?v???W?F?N?g???I?????Ă???????');
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
					window.alert('?H?????I?????Ă???????');
					judge = false;
				}
				else
				{
					document.getElementById('form_302_0').style.backgroundColor = '';
					document.getElementById('form_303_0').style.backgroundColor = '';
				}
                                
                                var teizi = document.getElementById('form_705_0').value * 100;
                                var zangyou = document.getElementById('form_706_0').value * 100;
                                if(teizi % 25 != 0)
                                {
                                        window.alert('?莞???Ԃ?15?????݂œ??͂??Ă????????B');
					judge = false;
                                }
                                if(zangyou % 25 != 0)
                                {
                                        window.alert('?c?Ǝ??Ԃ?15?????݂œ??͂??Ă????????B');
					judge = false;
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
									alert('?l???I?????ĉ?????');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
                            //2022-01-28 ???t???͗????J?????_?[?\???ɕύX?@start ----->>
                            var id = formelements.elements[j].id;
                            if(id == 'form_704_0')
                            {
                                if(formelements.elements[j].value == "")
                                {
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('?l???I?????ĉ?????');                                    
                                }
                                else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
                            }
                            //2022-01-28 ???t???͗????J?????_?[?\???ɕύX?@end -----<<                            
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
									alert('?l???I?????ĉ?????');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
                            //2022-01-28 ???t???͗????J?????_?[?\???ɕύX?@start ----->>
                            var id = formelements.elements[j].id;
                            if(id == 'form_704_0')
                            {
                                if(formelements.elements[j].value == "")
                                {
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('?l???I?????ĉ?????');                                    
                                }
                                else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
                            }
                            //2022-01-28 ???t???͗????J?????_?[?\???ɕύX?@end -----<<                            
						}
					}
				}
			}
            if(filename == "PJTOUROKU_1")
            {
                if(!goukeiCheck())
                {
                    judge = false;
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
                var filename = "<?php echo $filename; ?>";
                if(filename == 'TOP_1')
                {
                    //?s???????n??
                    var getArray = GET.split('_')
                    url = 'Modal.php?tablenum='+getArray[0]+'&form=insert&row='+getArray[1];
                }
                else
                {
                    url = 'Modal.php?tablenum='+GET+'&form=insert';
                }
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
	function AddTableRows(id)
        {
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
	echo "<div style='clear:both;'></div>";
        if($filename == 'TOP_1')
        {
            echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
                                    onsubmit = "return PROGRESScheck('."'insert'".');">';
        }
        else
        {
            echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
                                    onsubmit = "return check(\''.$checkList.
                                    '\',\''.$notnullcolumns.
                                    '\',\''.$notnulltype.'\');">';
        }
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br>";
	echo $form;
	echo "</tr></table>";
        if($filename == 'PJTOUROKU_1')
	{
            echo "<table><tr><td class='space' style='width: 137px;'></td><td class='one' style='width: 154px;'><a class='itemname'>???v???z : </a></td>";
            echo "<td class='two'><input type = 'text' value = '".$_SESSION['kobetu']['totalCharge']."' id = 'chage' name = 'chage' class = 'readOnly' size = 49 readonly >";
            echo "</td></tr></table>";
            echo "<table><tr><td class='space' style='width: 137px;'></td><td style='width: 1000px;'>";
            echo $list;
            echo "</td></tr></table>";
	}
	echo "<div class='center'>";
        if($filename == 'TOP_1')
        {
            echo $list;
        }
	echo '<input type="submit" name = "insert" value = "?o?^" class="free">';
        if($filename != 'TOP_1')
        {
            echo '<input type="submit" name = "cancel" value = "?N???A" class="free" onClick ="isCancel = true;">';
        }
	echo '<input type="submit" name = "back" value = "?߂?" class="free" onClick ="isCancel = true;">';
	echo "</form>";
	echo "</div>";
	echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "</form>";
?>
</body>
</html>


