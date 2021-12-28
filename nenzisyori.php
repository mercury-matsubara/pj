<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	start();
	
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
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
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
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
		for(var i = 0 ; i < value.length ; i++)
		{
			var obj = document.getElementsByName(name[i])[(document.getElementsByName(name[i]).length-1)];
			if(type[i] == 9)
			{
				obj.value = value[i];
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
	
	function checkonradio()
	{
		var id ='<?php echo $main_table; ?>';
		var judge = false;
		id += 'CODE';
		document.getElementById(id).value;
		if(document.getElementById(id).value != "")
		{
			judge = true;
		}
		else
		{
			alert("ä˙Ç‹ÇΩÇ¨èàóùÇ∑ÇÈÇoÇiÇëIëÇµÇƒÇ≠ÇæÇ≥Ç¢ÅB");
			judge = false;
		}
		return judge;
	}
	
// --></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	$start_year = getyear('6',$_SESSION['nenzi']['period']);
	$end_year = getyear('5',$_SESSION['nenzi']['period']);
	//2017-12-28
	$where = "";
	if(!empty($_SESSION['list']['form_102_0']))
	{
		$where .= "projectnuminfo.PROJECTNUM LIKE '%".$_SESSION['list']['form_102_0']."%' AND ";
	}
	if(!empty($_SESSION['list']['form_202_0']))
	{
		$where .= "edabaninfo.EDABAN LIKE '%".$_SESSION['list']['form_202_0']."%' AND ";
	}
	if(!empty($_SESSION['list']['form_203_0']))
	{
		$where .= "edabaninfo.PJNAME LIKE '%".$_SESSION['list']['form_203_0']."%' AND ";
	}

	$sql = array();
	$sql[0] = "SELECT DISTINCT(5CODE),PROJECTNUM,EDABAN,PJNAME,CHARGE ";
	$sql[1] = "SELECT COUNT(DISTINCT(5CODE),PROJECTNUM,EDABAN,PJNAME,CHARGE) ";
	
	$sqlbody = "FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) LEFT JOIN kouteiinfo USING(3CODE) WHERE ";
	if(!empty($where))
	{
		$sqlbody .= $where;
	}
	
	$sqlbody .= "projectinfo.5PJSTAT = 1 AND progressinfo.SAGYOUDATE BETWEEN '".$start_year."-06-01' AND '".$end_year."-05-31' ORDER BY PROJECTNUM,EDABAN ;";
	
	$sql[0] .= $sqlbody;
	$sql[1] .= $sqlbody;
	//2017-12-28Ç±Ç±Ç‹Ç≈
	$damy_array = array();
	$list ="";
	$list = makeList_radio($sql,$_SESSION['list'],$main_table);
	$columns = $form_ini[$filename]['sech_form_num'];
	$form = makeformModal_set($_SESSION['list'],'',"form",$columns);
	$columns = "102,202,203";
	$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	
	
	$checkList = $_SESSION['check_column'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div></form>";
	echo '<form name ="form" action="nenziJump.php" method="post">';
	echo "<input type ='submit' value = 'ñﬂÇÈ' name = 'cancel' class = 'free'>";
	echo "</div>";
    echo "</form>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="nenziJump.php" method="post">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>åüçıèåè</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "ï\é¶" class="free" >';
	echo "</td></tr></table></form>";
	echo '<form name ="form" action="nenziJump.php" method="post">';
	echo $list;
	echo "<br><table><tr><td>";
	echo "<input type = 'hidden' name = '".$main_table."CODE' id = '".$main_table."CODE' value =''>";
	echo $form_drop ;
	echo "</td><td valign='bottom' >";
	echo '<input type="submit" name="end" class="button" value="ä˙Ç‹ÇΩÇ¨èàóù" onClick = "return checkonradio()">';
	echo "</td>";
	echo "</tr></table>";
	echo "</form>";
	echo "</div>";
?>
	
</body>
</html>
