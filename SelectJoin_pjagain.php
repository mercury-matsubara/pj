<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
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
	$filename = 'pjagain_5';
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

function joinSelectSQL($post,$tablenum){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = 'pjagain_5';
	$columns = $form_ini[$tablenum]['insert_form_num'];
	echo '$columns：'.$columns.'<br>';
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	echo '$tableName：'.$tableName.'<br>';
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	echo '$masterNums：'.$masterNums.'<br>';
	$masterNums_array = explode(',',$masterNums);
	$between = $form_ini[$filename]['betweenColumn'];
	echo '$between：'.$between.'<br>';

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$count_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$formtype = "";
	$serch_str = "";
	$key_id = array();
	$masterName = array();
	$mastercolumns ="";
	$mastercolumns_array = array();
	$formatdate = "";
	$singleQute_start = "";
	$singleQute_end = "";
	$convert = "";
	$sql = array();
	$an = 0;
	
	
	
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$columns_array[count($columns_array)] = '405';
	}
	
	
	$keyarray = array_keys($post);
	$columns_array2 = array();
	foreach($keyarray as $key)
	{
		if (strstr($key, 'form_') && strstr($key, '_0'))
		{
			$key_array = explode('_',$key);
			if(isset($form_ini[$key_array[1]]))
			{
				$columns_array2[count($columns_array2)] = $key_array[1];
			}
		}
	}
	
	$columns_array = $columns_array2;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	$count_SQL .= "SELECT COUNT(*) FROM ".$tableName." ";
	
	//佐竹
	if ($tablenum == 3)
	{
		$select_SQL .= ",projectditealinfo ";
		$count_SQL .= ",projectditealinfo ";
	}
	echo $select_SQL.'<br>';
	//佐竹
	if($masterNums != '')
	{
		for($i = 0 ; $i < count($masterNums_array) ; $i++)
		{
			$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
			$select_SQL .= "LEFT JOIN ".$masterName[$i]." USING (".$masterNums_array[$i]."CODE ) ";
			$count_SQL .= "LEFT JOIN ".$masterName[$i]." USING (".$masterNums_array[$i]."CODE ) ";
			$masterNums1 = $form_ini[$masterNums_array[$i]]['seen_table_num'];
			$masterNums_array1 = explode(',',$masterNums1);
			$masterName1 = array();
			if($masterNums1 != '')
			{
				for($k = 0 ; $k < count($masterNums_array1) ; $k++)
				{
					$masterName1[$k] = $form_ini[$masterNums_array1[$k]]['table_name'];
					$select_SQL .= "LEFT JOIN ".$masterName1[$k]." USING (".$masterNums_array1[$k]."CODE ) ";
					$count_SQL .= "LEFT JOIN ".$masterName1[$k]." USING (".$masterNums_array1[$k]."CODE ) ";
					$masterNums2 = $form_ini[$masterNums_array1[$k]]['seen_table_num'];
					$masterNums_array2 = explode(',',$masterNums2);
					$masterName2 = array();
					if($masterNums2 != '')
					{
						for($j = 0 ; $j < count($masterNums_array2) ; $j++)
						{
							$masterName2[$j] = $form_ini[$masterNums_array2[$j]]['table_name'];
							$select_SQL .= "LEFT JOIN ".$masterName2[$j]." USING (".$masterNums_array2[$j]."CODE ) ";
							$count_SQL .= "LEFT JOIN ".$masterName2[$j]." USING (".$masterNums_array2[$j]."CODE ) ";
						}
					}
				}
			}
		}
	}
	$select_SQL .= " WHERE";
	$count_SQL .= " WHERE";
	if($filename == 'PROGRESSINFO_2' && $tablenum == '7' ){
			$select_SQL .= " 7PJSTAT = 1";
			$count_SQL .= " 7PJSTAT = 1";
	}
	if($filename == 'PROGRESSINFO_1' && $tablenum == '6')
	{
			$select_SQL .= " 6PJSTAT = 1 AND";
			$count_SQL .= " 6PJSTAT = 1 AND";
	}
	echo $columns_array[0].'<br>';
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		echo '<br>'.($i+1).'回目';
		$formtype = $form_ini[$columns_array[$i]]['form_type'];
		for($j = 0; $j < 5 ; $j++)
		{
			$serch_str = "form_".$columns_array[$i]."_".$j;
			if(isset($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str];
				if($post[$serch_str] != "" && $formtype != 9)
				{
					switch ($j)
					{
					case 0:
						$formatdate .='%Y';
						break;
					case 1:
						$formatdate .='%c';
						break;
					case 2:
						$formatdate .='%e';
						break;
					default:
						$formatdate .='';
					}
				}
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$table_Number = $form_ini[$columns_array[$i]]['table_num'];
		$tableName = $form_ini[$table_Number]['table_name'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		if ($singleQute == '')
		{
			$convert = " ".$tableName.".".$columnName;
			$singleQute_start = " = ";
			$singleQute_end = "";
		}
		else
		{	
			if($filename == 'PROGRESSINFO_2' && $tablenum == '7' ){
				if ($columnName == "STAFFID"){
					$convert =  " AND convert(replace(replace(".$tableName.".".$columnName
							.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
				} else if ($columnName == "PROJECTNUM" && $an == 0){
					$convert =  " AND convert(replace(replace(".$tableName.".".$columnName
							.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
				}
				
				else {
					$convert =  " convert(replace(replace(".$tableName.".".$columnName
							.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
				}
			} else 
			{
				$convert =  " convert(replace(replace(".$tableName.".".$columnName
							.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
			}
			$singleQute_start = "LIKE '%";
			$singleQute_end = "%'";
		}
		if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
		{
			$columnValue = str_replace(" ", "%", $columnValue); 
			$columnValue = str_replace("　", "%", $columnValue);
			$select_SQL .= $convert;
			$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
			$count_SQL .= $convert;
			$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
			$an = 1;
		}
		else if ($columnValue != "")
		{
			$select_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$count_SQL .= " DATE_FORMAT(".$tableName.".".$columnName.",'".$formatdate."') =";
			$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
			$formatdate = "";
		}
		$columnValue ="";
	}
	echo $select_SQL.'<br>';
//	if($masterNums != '')
//	{
//		for($i = 0 ; $i < count($masterNums_array) ; $i++)
//		{
//			$mastercolumns = $form_ini[$masterNums_array[$i]]['insert_form_num'];
//			$mastercolumns_array = explode(',',$mastercolumns);
//			for($j = 0 ; $j < count($mastercolumns_array) ; $j++)
//			{
//				for($k = 0; $k < 5 ; $k++)
//				{
//					$serch_str = "form_".$mastercolumns_array[$j]."_".$k;
//					if(isset($post[$serch_str]))
//					{
//						$columnValue .= $post[$serch_str];
//						if($post[$serch_str] != "" && $formtype != 9)
//						{
//							switch ($k){
//							case 0:
//								$formatdate .='%Y';
//								break;
//							case 1:
//								$formatdate .='%c';
//								break;
//							case 2:
//								$formatdate .='%e';
//								break;
//							default:
//								$formatdate .='';
//							}
//						}
//					}
//				}
//				$columnName = $form_ini[$mastercolumns_array[$j]]['column'];
//				$fieldtype = $form_ini[$mastercolumns_array[$j]]['fieldtype'];
//				$formtype = $form_ini[$mastercolumns_array[$j]]['form_type'];
//				$singleQute = $fieldtype_ini[$fieldtype];
//				if ($singleQute == '')
//				{
//					$convert = " ".$masterName[$i].".".$columnName;
//					$singleQute_start = " = ";
//					$singleQute_end = "";
//				}
//				else
//				{
//					$convert =  " convert(replace(replace(".$masterName[$i].".".$columnName
//								.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
//					$singleQute_start = "LIKE '%";
//					$singleQute_end = "%'";
//				}
//				if ($columnValue != "" && ($formtype >= 9 || $formtype == 3 || $formtype == 5 ))
//				{
//					$columnValue = str_replace(" ", "%", $columnValue); 
//					$columnValue = str_replace("　", "%", $columnValue);
//					$select_SQL .= $convert;
//					$select_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//					$count_SQL .= $convert;
//					$count_SQL .= $singleQute_start.$columnValue.$singleQute_end." AND";
//				}
//				else if($columnValue != "")
//				{
//					$select_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
//					$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
//					$count_SQL .= " DATE_FORMAT(".$masterName[$i].".".$columnName.",'".$formatdate."') =";
//					$count_SQL .= $singleQute.$columnValue.$singleQute." AND";
//					$formatdate = "";
//				}
//				$columnValue ="";
//			}
//		}
//	}

	//佐竹
	if ($filename != 'ENDPJLIST_2' && $filename != 'MONTHLIST_2' && $tablenum == 5)
	{
		$select_SQL .= " 5PJSTAT = 1 ";
		$count_SQL .= " 5PJSTAT = 1 ";
	}
	else if ($filename != 'ENDPJLIST_2' && $tablenum == 8)
	{
		$select_SQL .= " 8PJSTAT = 2 ";
		$count_SQL .= " 8PJSTAT = 2 ";
	}
	//佐竹

	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$count_SQL = rtrim($count_SQL,'WHERE');
	$count_SQL = rtrim($count_SQL,'AND');
	
	
	if($filename == 'genbaend_5')
	{
		
		if(strstr($select_SQL, ' WHERE ') == false)
		{
			$select_SQL .= " WHERE ( GENBASTATUS = '0' OR  GENBASTATUS = '1') ";
			$count_SQL .= " WHERE ( GENBASTATUS = '0' OR  GENBASTATUS = '1') ";
		}
		else
		{
			$select_SQL .= " AND ( GENBASTATUS = '0' OR  GENBASTATUS = '1') ";
			$count_SQL .= " AND ( GENBASTATUS = '0' OR  GENBASTATUS = '1') ";
		}
	}
	
	
	
	if($between != "")
	{
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$start_date = "";
		$end_date = "";
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		if(isset($post['form_start_0']))
		{
			if($post['form_start_0'] == "")
			{
				$start_date = $before_year;
			}
			else
			{
				$start_date = $post['form_start_0'];
			}
		}
		if(isset($post['form_start_1']))
		{
			if($post['form_start_1'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_1'];
			}
		}
		if(isset($post['form_start_2']))
		{
			if($post['form_start_2'] == "")
			{
				$start_date .= "-1";
			}
			else
			{
				$start_date .= "-".$post['form_start_2'];
			}
		}
		if(isset($post['form_end_0']))
		{
			if($post['form_end_0'] == "")
			{
				$end_date = $year + $after_year;
			}
			else
			{
				$end_date = $post['form_end_0'];
			}
		}
		if(isset($post['form_end_1']))
		{
			if($post['form_end_1'] == "")
			{
				$end_date .= "-12";
			}
			else
			{
				$end_date .= "-".$post['form_end_1'];
			}
		}
		if(isset($post['form_end_2']))
		{
			if($post['form_end_2'] == "")
			{
				$end_date .= "-31";
			}
			else
			{
				$end_date .= "-".$post['form_end_2'];
			}
		}
		$tablenum_between = $form_ini[$between]['table_num'];
		$column_name_between = $form_ini[$between]['column'];
		$table_name_between = $form_ini[$tablenum_between]['table_name'];
		if($form_ini[$between]['fieldtype'] == 'DATETIME' && $start_date != '')
		{
			$start_date .= ' 00:00:00';
			$end_date .= ' 23:59:59';
		}
		if(strstr($select_SQL, ' WHERE ') == false && $start_date != '')
		{
			$select_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " WHERE ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
		else if($start_date != '')
		{
			$select_SQL .= " AND  ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
			$count_SQL .= " AND  ".$table_name_between.".".$column_name_between." BETWEEN '".$start_date."' AND '".$end_date."' ";
		}
	}
	$select_SQL .= ";";
	$count_SQL .= ";";
	$sql[0] = $select_SQL;
	$sql[1] = $count_SQL;
	return ($sql);
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
			alert("終了するプロジェクトを選択してください。");
			judge = false;
		}
		return judge;
	}
	
// --></script>
</head>
<body>


<?php
	echo $filename.'<br>';
	echo $main_table.'<br>';
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
//	$_SESSION['list']['form_506_0'] = '1';
	
	$sql = array();
	$sql = joinSelectSQL($_SESSION['list'],$main_table);
	$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
	echo $sql[0].'<br>';
	$damy_array = array();
	$list ="";
	$list = makeList_radio($sql,$_SESSION['list'],$main_table);
	$columns = $form_ini[$filename]['sech_form_num'];
	$form = makeformModal_set($_SESSION['list'],'',"form",$columns);
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	
	
	$checkList = $_SESSION['check_column'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "</form>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="pjendJump.php" method="post" 
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
	echo '<form name ="drop" id = "drop" action="pjendJump.php" method="post" onsubmit ="return checkonradio();">';
	echo "<br><table><tr><td>";
	echo "<input type = 'hidden' name = '".$main_table."CODE' id = '".$main_table."CODE' value =''>";
	echo $form_drop ;
	echo "</td><td valign='bottom' >";
	echo '<input type="submit" name="end" class="button" value="ＰＪ再起">';
	echo "</td>";
	echo "</tr></table>";
	echo "</form>";
	echo "</div>";
?>
	
</body>
</html>
