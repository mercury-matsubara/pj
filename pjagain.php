<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////


	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
        if(isset($_SESSION['kensaku']))
        {
            $_SESSION['list'] = $_SESSION['kensaku'];
            unset($_SESSION['kensaku']);
        }
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
		var judge = false;
		if(document.getElementById('5CODE').value != "")
		{
			judge = true;
		}
		else
		{
			alert("終了するプロジェクトを選択してください。");
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
	
	$sql = array();
	$sql = joinSelectSQL($_SESSION['list'],$main_table);
	$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
	$damy_array = array();
	$list ="";
	$sql[0] = str_replace("*", "DISTINCT endpjinfo.PROJECTNUM,endpjinfo.EDABAN,endpjinfo.PJNAME,5CODE", $sql[0]);
	$sql[1] = str_replace("*", "DISTINCT endpjinfo.PROJECTNUM,endpjinfo.EDABAN,endpjinfo.PJNAME,5CODE", $sql[1]);
	$list = makeList_radio($sql,$_SESSION['list'],$main_table);
	$columns = $form_ini[$filename]['sech_form_num'];
	$form = makeformModal_set($_SESSION['list'],'',"form",$columns);
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	
	$checkList = $_SESSION['check_column'];
        
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="pjagainJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "表示" class="free" >';
	echo "</td></tr></table>";
	echo $list;
	echo "</form>";
	echo '<form name ="drop" id = "drop" action="pjagainJump.php" method="post" onsubmit ="return checkonradio();">';
	echo "<br><table><tr><td>";
	echo "<input type = 'hidden' name = '5CODE' id = '5CODE' value =''>";
//	echo "<input type = 'hidden' name = '6CODE' id = '6CODE' value =''>";
	echo $form_drop ;
	echo "</td><td valign='bottom' >";
	echo '<input type="submit" name="again" class="button" value="終了ＰＪキャンセル">';
	echo "</td>";
	echo "</tr></table>";
	echo "</form>";
	echo "</div>";
	echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "</form>";
?>
	
</body>
</html>
