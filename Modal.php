<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
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
	require_once("f_Construct.php");
	$tablenum = "";
        $row="";                                //Hi»o^ζΚiTOPjΜζ€Θ‘o^Ιgp
	
	if(count($_POST) != 0)
	{
		startJump($_POST);
		$tablenum = $_POST['tablenum'];
		$form_name = $_POST['form'];
                if(isset($_POST['row']))
                {
                        $row = $_POST['row'];
                }
	}
	else if(count($_GET) != 0)
	{
		startJump($_GET);
		$form_name = $_GET['form'];
		$tablenum = $_GET['tablenum'];
                if(isset($_GET['row']))                     
                {
                        $row = $_GET['row'];
                }
		$_POST = array();
	}
	else
	{
		startJump($_GET);
	}
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$columns_num = $form_ini[$tablenum]['insert_form_num'];
	$columns_num_array = explode(',',$columns_num);
	$filename = $_SESSION['filename'];
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$columns_num = '402,403,202,203';
		$columns_num_array = explode(',',$columns_num);
	}
	
	$form_num = '';
	$form_type = '';
	if(isset($_SESSION['Modal']) == false)
	{
		$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
		$_SESSION['Modal']['limitstart'] = 0;
	}
	else
	{
		if(isset($_POST['serch']))
		{
			$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
			$_SESSION['Modal']['limitstart'] = 0;
		}
		else if(isset($_POST['back']))
		{
			$_SESSION['Modal']['limitstart'] -= 10;
			$_SESSION['Modal']['limit'] = ' LIMIT '.
			$_SESSION['Modal']['limitstart'].',10 ';
		}
		else if(isset($_POST['next']))
		{
			$_SESSION['Modal']['limitstart'] += 10;
			$_SESSION['Modal']['limit'] = ' LIMIT '.
			$_SESSION['Modal']['limitstart'].',10 ';
		}
		else if(isset($_POST['backall']))
		{
			$_SESSION['Modal']['limitstart'] = 0;
			$_SESSION['Modal']['limit'] = ' LIMIT '.
			$_SESSION['Modal']['limitstart'].',10 ';
		}
		else if(isset($_POST['nextall']))
		{
			$_SESSION['Modal']['limitstart'] = $_SESSION['Modal']['max'];
			$_SESSION['Modal']['limit'] = ' LIMIT '.
			$_SESSION['Modal']['limitstart'].',10 ';
		}
		else
		{
			$_SESSION['Modal']['limit'] = ' LIMIT 0,10 ';
			$_SESSION['Modal']['limitstart'] = 0;
			$_POST = array();
		}
	}
	for($i = 0 ; $i < count($columns_num_array) ; $i++)
	{
		$type = $form_ini[$columns_num_array[$i]]['form_type'];
		$form_type .= $type.',';
		switch($type)
		{
                        case 1:
                        case 2:
                                $form_num .='3,';
                                break;
                        case 3:
                        case 4:
                                $form_num .='2,';
                                break;
                        case 9:
                                $form_num .=$form_ini[$columns_num_array[$i]]['form_num'].',';
                                break;
                        default :
                                $form_num .='1,';
                                break;
		}
	}
	$form_num = substr($form_num,0,-1);
	$form_type = substr($form_type,0,-1);
	if($form_num == '')
	{
		$form_num = '0';
	}
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2' || $filename == 'genbaend_5')
	{
		$_POST['form_405_0'] = '0';
	}

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
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
	function select_value(value,name,type)
	{
		value = value.split("#$");
		name = name.split(",");
		type = type.split(",");
//		var obj = document.forms.drop;
		for(var i = 0 ; i < value.length ; i++)
		{
			var obj = document.getElementsByName(name[i])[(document.getElementsByName(name[i]).length-1)];
			if(type[i] == 9)
			{
				obj.value = value[i];
//				obj.name[i].value = value[i];
			}
			else
			{
				var select = obj;
				var selectnum = obj.selectedIndex;
				select.options[selectnum].selected = false;
				select.options[selectnum].disabled = true;
				for(var j = 0; j < select.options.length ; j++)
				{
					if(select.options[j].value == value[i])
					{
						select.options[j].selected = false;
						select.options[j].disabled = true;
					}
				}
			}
		}
	}
	
	function toMainWin()
	{
                var filename = "<?php echo $filename; ?>";
//		var opener = window.dialogArguments;
                var opener = window.opener;
		var columnum = "<?php echo $columns_num; ?>";
		var type = "<?php echo $form_type; ?>";
		var form_num ="<?php echo $form_num; ?>";
		var tablenum ="<?php echo $tablenum; ?>";
		var form ="<?php echo $form_name; ?>";
//		var opener_form = opener.document.forms[form];
		var opener_form = opener.document;
		var array = columnum.split(",");
		type = type.split(",");
		form_num = form_num.split(",");
                if(filename == 'TOP_1' || filename == 'TOP_3')
                {
                    //wθ³κ½sΙf[^π}ό
                    var row = "<?php echo $row; ?>";
                    var value = document.getElementsByName(tablenum+'CODE')[(document.getElementsByName(tablenum+'CODE').length-1)].value;
                    opener_form.getElementsByName(tablenum+'CODE_'+row)[(opener_form.getElementsByName(tablenum+'CODE_'+row).length-1)].value = value ;  
                }
                else
                {
                    var value = document.getElementsByName(tablenum+'CODE')[(document.getElementsByName(tablenum+'CODE').length-1)].value;
                    opener_form.getElementsByName(tablenum+'CODE')[(opener_form.getElementsByName(tablenum+'CODE').length-1)].value = value ;
                }	
                for( i = 0; i < array.length; i++) 
		{
			if(type[i] == 9)
			{
				for( j = 0; j <form_num[i]; j++ )
				{
                                        if(filename == 'TOP_1' || filename == 'TOP_3')
                                        {
                                            //wθ³κ½sΙf[^π}ό
                                            var row = "<?php echo $row; ?>";
                                            var name1 = "form_"+array[i]+"_"+(j);
                                            var name2 = "form_"+array[i]+"_"+(j)+"_"+row;
                                            var obj1 = document.getElementsByName(name1)[(document.getElementsByName(name1).length-1)];
                                            var obj2 = opener_form.getElementsByName(name2)[(opener_form.getElementsByName(name2).length-1)];
                                            var el = obj1.value;
                                            obj2.value = el;
                                            obj2.style.backgroundColor = '';
                                        }
                                        else
                                        {
                                            var name = "form_"+array[i]+"_"+(j);
                                            var obj1 = document.getElementsByName(name)[(document.getElementsByName(name).length-1)];
                                            var obj2 = opener_form.getElementsByName(name)[(opener_form.getElementsByName(name).length-1)];
                                            var el = obj1.value;
                                            obj2.value = el;
                                            obj2.style.backgroundColor = '';
                                        }
				}
			}
			else
			{
				for( j = 0; j <form_num[i]; j++ )
				{
					var name = "form_"+array[i]+"_"+(j);
					var obj1 = document.getElementsByName(name)[(document.getElementsByName(name).length-1)];
					var opner_el = opener_form.getElementsByName(name)[(opener_form.getElementsByName(name).length-1)];
//					var obj = document.forms["drop"].elements(name);
					var select = obj1.selectedIndex;
//					alert(select);
					var selectvalue = obj1.options[select].value;
//					var opner_el = opener.document.getElementById(name);
					var opnerselect = opner_el.selectedIndex;
					opner_el.options[opnerselect].selected = false;
					opner_el.options[opnerselect].disabled = true;
					for(var k = 0; k < opner_el.options.length ; k++)
					{
						if(opner_el.options[k].value == selectvalue)
						{
							opner_el.options[k].disabled = false;
							opner_el.options[k].selected = true;
						}
					}
					opner_el.style.backgroundColor = '';
				}
			}
		}
		close();
	}
	function close_dailog()
	{
		close();
	}
// --></script>
</head>
<body>

<?php
	$sql = array();
	if($filename == 'PROGRESSINFO_1')
	{
		$_POST['form_607_0'] = "1";
	}
        if($filename == 'TOP_1' || $filename == 'TOP_3')
	{
		$_POST['form_607_0'] = "1";
                //OC΅Δ’ιΠυΕ©?IΙo
                $_POST['form_402_0'] = $_SESSION['user']['STAFFID'];
                $_POST['form_403_0'] = $_SESSION['user']['STAFFNAME'];
	}
	if($filename == 'PROGRESSINFO_2' && $tablenum == '6')
	{
		$_POST['form_607_0'] = "1";
	}
	//²|
	if($tablenum == '3')
	{
		$_POST['form_607_0'] = "1";
	}
	$sql = joinSelectSQL($_POST,$tablenum);
	//2018-1-12 μΤ
	if($filename != 'ENDPJLIST_2')
	{
		$sql = SQLsetOrderby($_POST,$tablenum,$sql);
	}
	else
	{
		if($tablenum == 5)
		{
			$sql[0] = substr($sql[0],0,-1);
			$sql[0] .= " ORDER BY projectnuminfo.PROJECTNUM ASC, edabaninfo.EDABAN ASC;";
		}
		else
		{
			$sql[0] = substr($sql[0],0,-1);
			$sql[0] .= " ORDER BY STAFFID ASC;";
		}
	}
	//2018-1-12
	
	//2017-12-26
	if(($filename == 'PROGRESSINFO_1' || $filename == 'PROGRESSINFO_2' || $filename == 'TOP_1' || $filename == 'TOP_3') && $tablenum == '3')
	{
		$sql[0] = "select * from kouteiinfo";
		$sql[1] = "select COUNT(*) from kouteiinfo";
		if(isset($_POST['form_302_0']))
		{
			if(isset($_POST['form_303_0']))
			{
				$sql[0] .= " WHERE KOUTEIID LIKE '%".$_POST['form_302_0']."%' AND KOUTEINAME LIKE '%".$_POST['form_303_0']."%'";
				$sql[1] .= " WHERE KOUTEIID LIKE '%".$_POST['form_302_0']."%' AND KOUTEINAME LIKE '%".$_POST['form_303_0']."%'";
			}
			else
			{
				$sql[0] .= " WHERE KOUTEIID LIKE '%".$_POST['form_302_0']."%'";
				$sql[1] .= " WHERE KOUTEIID LIKE '%".$_POST['form_302_0']."%'";
			}
		}
		else if(isset($_POST['form_303_0']))
		{
			$sql[0] .= " WHERE KOUTEINAME LIKE  '%".$_POST['form_303_0']."%'";
			$sql[1] .= " WHERE KOUTEINAME LIKE  '%".$_POST['form_303_0']."%'";
		}
		$sql[0] .= " ORDER BY KOUTEIID ASC;";
		$sql[1] .= ";";
	}

/*	if($filename == 'PROGRESSINFO_2' && $tablenum == '3')
	{
		$sql[0] = "select * from kouteiinfo;";
		//μΤ
		$sql[1] = "select COUNT(*) from kouteiinfo;";
	}
*/	$damy_array = array();

	$list ="";
	$list = makeList_Modal($sql,$_POST,$tablenum);
	$columns = $form_ini[$tablenum]['sech_form_num'];
        $_SESSION["tablenum"] = $tablenum;
	$form = makeformModal_set($_POST,'',"form",$columns);
	$columns = $form_ini[$tablenum]['insert_form_num'];
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$columns = '402,403,202,203';
	}
	$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	$checkList = $_SESSION['check_column'];

	echo "<LEFT><div class = 'pad' id = 'pop'>";
	echo '<form name ="form" action="Modal.php"  target = "Modal" method="post">';
	echo "<input type = 'hidden' name = 'tablenum' value = '".$tablenum."'>";
	echo "<input type = 'hidden' name = 'form' value = '".$form_name."'>";
        if($filename == 'TOP_1' || $filename == 'TOP_3')
        {
                echo "<input type = 'hidden' name = 'row' value = '".$row."'>";
        }
	echo "<table><tr><td>";
	echo "<fieldset><legend>υπ</legend>";
	echo $form;
	
	
	
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" class="button" name="serch" value = "\¦">';
	echo "</td></tr></table><br>";
	echo $list;
	echo "</form>";
	echo "</tr></table>";
	echo "<br><table><tr><td>";
	echo "<form name = 'drop' id = 'drop' metod = 'post'>";
	echo "<input type = 'hidden' name = '".$tablenum."CODE' value =''>";
	echo $form_drop ;
	echo "</form></td><td valign='bottom' >";
	echo '<input type="button" class="button" value="θ" onClick = "toMainWin();">';
	echo "</td>";
	echo '<td valign="bottom" >';
	echo '<input type="button" class="button" value="ΒΆι" onClick = "close_dailog();"></td>';
	echo "</tr></table>";
	echo "</div></LEFT>";
?>

<script language="JavaScript"><!--
	window.name = "Modal";																						//@submit{^ΕXΙqζΚJ©Θ’ζ€Ι
// --></script>
	
</body>
</html>
