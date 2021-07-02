<?php




/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////









/************************************************************************************************************
function InsertSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function InsertSQL($post,$tablenum,$over){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	if($filename == 'EDABANINFO_1')
	{
		$columns = "202,203";
	}
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);
	$table_columns = $form_ini[$tablenum]['insert_form_num'];
	$table_columns_array = explode(',',$table_columns);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$insert_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$formtype ="";
	$delimiter = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$insert_SQL .= "INSERT INTO ".$tableName." (";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if(isset($form_ini[$columns_array[$i]]['column']) == true)
		{
			$columnName = $form_ini[$columns_array[$i]]['column'];
			$insert_SQL .= $columnName.",";
		}
		else if($tablenum == $columns_array[$i])
		{
			for($k = 0 ; $k < count($table_columns_array) ; $k++)
			{
				$columnName = $form_ini[$table_columns_array[$k]]['column'];
				$insert_SQL .= $columnName.",";
			}
		}
	}
	if($mastertablenum != '')
	{
		for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$insert_SQL .= $mastertablenum_array[$i]."CODE,";
		}
	}
	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ")VALUES(";
	
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if(isset($form_ini[$columns_array[$i]]['form_type']) == true)
		{
			$formtype = $form_ini[$columns_array[$i]]['form_type'];
			if($formtype == 1 || $formtype == 2|| $formtype == 4  )
			{
				$delimiter = "-";
			}
			else
			{
				$delimiter = "";
			}
			for($j = 0; $j < 5 ; $j++)
			{
				if($over == "")
				{
					$serch_str = "form_".$columns_array[$i]."_".$j;
				}
				else
				{
					$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
				}
				if(isset($post[$serch_str]))
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
			}
			$columnValue = rtrim($columnValue,$delimiter);
			$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
			$singleQute = $fieldtype_ini[$fieldtype];
			$insert_SQL .= $singleQute.$columnValue.$singleQute.",";
			$columnValue ="";
		}
		else if($tablenum == $columns_array[$i])
		{
			for($k = 0 ; $k < count($table_columns_array) ; $k++)
			{
				$formtype = $form_ini[$table_columns_array[$k]]['form_type'];
				if($formtype == 1 || $formtype == 2|| $formtype == 4  )
				{
					$delimiter = "-";
				}
				else
				{
					$delimiter = "";
				}
				for($j = 0; $j < 5 ; $j++)
				{
					if($over == "")
					{
						$serch_str = "form_".$table_columns_array[$k]."_".$j;
					}
					else
					{
						$serch_str = "form_".$table_columns_array[$k]."_".$j."_".$over ;
					}
					if(isset($post[$serch_str]))
					{
						$columnValue .= $post[$serch_str].$delimiter;
					}
				}
				$columnValue = rtrim($columnValue,$delimiter);
				$fieldtype = $form_ini[$table_columns_array[$k]]['fieldtype'];
				$singleQute = $fieldtype_ini[$fieldtype];
				$insert_SQL .= $singleQute.$columnValue.$singleQute.",";
				$columnValue ="";
			}
		}
	}
	if($mastertablenum != '')
	{
		for($i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$insert_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
		}
	}
	$insert_SQL = substr($insert_SQL,0,-1);
	$insert_SQL .= ");";
	return($insert_SQL);
}


/************************************************************************************************************
function SelectSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function SelectSQL($post,$tablenum,$over){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		for($j = 0; $j < 5 ; $j++)
		{
			if($over == "")
			{
				$serch_str = "form_".$columns_array[$i]."_".$j;
			}
			else
			{
				$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
			}
			if(isset($post[$serch_str]))
			{
				$columnValue .= $post[$serch_str];
				$columnValue = str_replace(" ", "", $columnValue); 
				$columnValue = str_replace("　", "", $columnValue);
			}
		}
		$columnName = $form_ini[$columns_array[$i]]['column'];
		$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
		$singleQute = $fieldtype_ini[$fieldtype];
		$columnValue = rtrim($columnValue,"-");
		if ($columnValue != "")
		{
			$select_SQL .= " convert(replace(replace(".$columnName
						.",' ',''),'　','') using utf8) COLLATE utf8_unicode_ci = ";
			$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
		}
		$columnValue ="";
	}
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL = rtrim($select_SQL,'AND');
	$select_SQL .= ";";
//	echo ($select_SQL);
}


/************************************************************************************************************
function joinSelectSQL($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
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
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);
	$between = $form_ini[$filename]['betweenColumn'];

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
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
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
	if ($filename != 'ENDPJLIST_2' && $filename != 'MONTHLIST_2' && $filename != 'PJLIST_2' && $tablenum == 5)
	{
		$select_SQL .= " 5PJSTAT = 1 ";
		$count_SQL .= " 5PJSTAT = 1 ";
	}
	else if ($filename != 'ENDPJLIST_2' && $tablenum == 8)
	{
		$select_SQL .= " 5PJSTAT = 2 ";
		$count_SQL .= " 5PJSTAT = 2 ";
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





/************************************************************************************************************
function idSelectSQL($code_value,$tablenum,$code)

引数	$post

戻り値	なし
************************************************************************************************************/
function idSelectSQL($code_value,$tablenum,$code){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	$select_SQL .= " ".$code." = ";
	$select_SQL .= $code_value." ";
	$select_SQL .= ";";
	return $select_SQL;
}


/************************************************************************************************************
function UpdateSQL($post,$tablenum,$over)

引数	$post

戻り値	なし
************************************************************************************************************/
function UpdateSQL($post,$tablenum,$over){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$mastertablenum = $form_ini[$tablenum]['seen_table_num'];
	$mastertablenum_array = explode(',',$mastertablenum);
	$update_column = $form_ini[$filename]['up_cloumn_num'];
	$update_value = $form_ini[$filename]['up_cloumn_value'];
	$update_column_array = explode(',',$update_column);
	$update_value_array = explode(',',$update_value);
	$table_columns = $form_ini[$tablenum]['insert_form_num'];
	$table_columns_array = explode(',',$table_columns);

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$update_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$formtype = "";
	$delimiter = "";
	
	$date = date_create('NOW');
	$DATE = "";
	$DATETIME = "";
	$DATE = date_format($date, 'Y-m-d');
	$DATETIME = date_format($date, 'Y-m-d H:i:s');
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	
	$filename = $_SESSION['filename'];
	
	if($filename == 'SAIINFO_2')
	{
		$columns_array = array();
		$columns_array[0] = '806';
		$mastertablenum_array = array();
	}
	
	
	
	
	
	
	$update_SQL .= "UPDATE ".$tableName." SET";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if(isset($form_ini[$columns_array[$i]]['form_type']) == true)
		{
			$formtype = $form_ini[$columns_array[$i]]['form_type'];
			if($formtype == 1 || $formtype == 2|| $formtype == 4  )
			{
				$delimiter = "-";
			}
			else
			{
				$delimiter = "";
			}
			for($j = 0; $j < 5 ; $j++)
			{
				if($over == "")
				{
					$serch_str = "form_".$columns_array[$i]."_".$j;
				}
				else
				{
					$serch_str = "form_".$columns_array[$i]."_".$j."_".$over ;
				}
				if(isset($post[$serch_str]))
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
			}
			$columnValue = rtrim($columnValue,$delimiter);
			$columnName = $form_ini[$columns_array[$i]]['column'];
			$fieldtype = $form_ini[$columns_array[$i]]['fieldtype'];
			$singleQute = $fieldtype_ini[$fieldtype];
			$update_SQL .= " ".$columnName." = ";
			$update_SQL .= $singleQute.$columnValue.$singleQute." ,";
			$columnValue ="";
		}
		else if($tablenum == $columns_array[$i])
		{
			for($k = 0 ; $k < count($table_columns_array) ; $k++)
			{
				$formtype = $form_ini[$table_columns_array[$k]]['form_type'];
				if($formtype == 1 || $formtype == 2|| $formtype == 4  )
				{
					$delimiter = "-";
				}
				else
				{
					$delimiter = "";
				}
				for($j = 0; $j < 5 ; $j++)
				{
					if($over == "")
					{
						$serch_str = "form_".$table_columns_array[$k]."_".$j;
					}
					else
					{
						$serch_str = "form_".$table_columns_array[$k]."_".$j."_".$over ;
					}
					if(isset($post[$serch_str]))
					{
						$columnValue .= $post[$serch_str].$delimiter;
					}
				}
				$columnValue = rtrim($columnValue,$delimiter);
				$columnName = $form_ini[$table_columns_array[$k]]['column'];
				$fieldtype = $form_ini[$table_columns_array[$k]]['fieldtype'];
				$singleQute = $fieldtype_ini[$fieldtype];
				$update_SQL .= " ".$columnName." = ";
				$update_SQL .= $singleQute.$columnValue.$singleQute." ,";
				$columnValue ="";
			}
		}
	}
	if($mastertablenum != '')
	{
		for( $i = 0 ; $i < count($mastertablenum_array) ; $i++)
		{
			$update_SQL .= " ".$mastertablenum_array[$i]."CODE = ";
			$update_SQL .= $post[$mastertablenum_array[$i]."CODE"].",";
		}
	}
	if($update_column != '')
	{
		for( $i = 0 ; $i < count($update_column_array) ; $i++)
		{
			$columnName = $form_ini[$update_column_array[$i]]['column'];
			$fieldtype = $form_ini[$update_column_array[$i]]['fieldtype'];
			$singleQute = $fieldtype_ini[$fieldtype];
			if($update_value_array[$i] == 'DATETIME')
			{
				$columnValue = $DATETIME;
			}
			else if($update_value_array[$i] == 'DATE')
			{
				$columnValue = $DATE;
			}
			else
			{
				$columnValue = $update_value_array[$i];
			}
			$update_SQL .= " ".$columnName." = ";
			$update_SQL .= $singleQute.$columnValue.$singleQute." ,";
			$columnValue ="";
		}
	}
	$update_SQL = rtrim($update_SQL,',');
	$update_SQL .= " WHERE ".$tablenum."CODE = ".$post[$tablenum."CODE"];
	$update_SQL .= ";";
	return $update_SQL;
}


/************************************************************************************************************
function DeleteSQL($codeValue,$tablenum,$code)

引数	$post

戻り値	なし
************************************************************************************************************/
function DeleteSQL($codeValue,$tablenum,$code){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$delete_SQL = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$delete_SQL .= "DELETE FROM ".$tableName." ";
	$delete_SQL .= " WHERE ".$code." = ".$codeValue;
	$delete_SQL .= ";";
	return($delete_SQL);
}



/************************************************************************************************************
function uniqeSelectSQL($post,$tablenum,$columns)

引数	$post

戻り値	なし
************************************************************************************************************/
function uniqeSelectSQL($post,$tablenum,$columns){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];

	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = "";
	$columnValue = "";
	$formatType = "";
	$select_SQL = "";
	$singleQute = "";
	$key_array = array();
	$fieldtype = "";
	$serch_str = "";
	$key_id = array();
	$uniqefiled = array();
	$isValueExit = true;
	$judge = true;
	$delimiter = "";
	$formtype = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	if(isset($post['uniqe']) == false)
	{
		$judge = false;
	}
	$select_SQL .= "SELECT * FROM ".$tableName." WHERE";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[$i] == "")
		{
			break;
		}
		$uniqefiled = $columns_array[$i];
		$uniqefiled = explode('~',$columns_array[$i]);
		for($j = 0 ; $j < count($uniqefiled) ; $j++)
		{
			$formtype = $form_ini[$uniqefiled[$j]]['form_type'];
			$columnName = $form_ini[$uniqefiled[$j]]['column'];
			if($formtype == 1 || $formtype == 2|| $formtype == 4  )
			{
				$delimiter = "-";
			}
			else
			{
				$delimiter = "";
			}
			for($k = 0; $k < 5 ; $k++)
			{
				if(strstr($columnName,'CODE') != false)
				{
					$serch_str = $columnName;
					if($k != 0)
					{
						break;
					}
				}
				else
				{
					$serch_str = "form_".$uniqefiled[$j]."_".$k;
				}
				if(isset($post[$serch_str]))
				{
					$columnValue .= $post[$serch_str].$delimiter;
				}
			}
			$columnValue  = rtrim($columnValue,$delimiter);
			if(isset($post['uniqe'][$columns_array[$i]]))
			{
				if($post['uniqe'][$columns_array[$i]] != $columnValue )
				{
					$judge = false;
				}
			}
			$fieldtype = $form_ini[$uniqefiled[$j]]['fieldtype'];
			$singleQute = $fieldtype_ini[$fieldtype];
			if (count($uniqefiled) == 1)
			{
				$select_SQL .= " ".$columnName." = ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." OR";
			}
			else if( count($uniqefiled) > 1)
			{
				if($j == 0)
				{
					$select_SQL .="(";
				}
				$select_SQL .= " ".$columnName." = ";
				$select_SQL .= $singleQute.$columnValue.$singleQute." AND";
			}
			$columnValue ="";
		}
		if(count($uniqefiled) > 1)
		{
			$select_SQL = rtrim($select_SQL,'(');
			$select_SQL = rtrim($select_SQL,'AND');
			$select_SQL .= ") OR";
		}
	}
	$select_SQL = rtrim($select_SQL,'OR');
	$select_SQL = rtrim($select_SQL,'WHERE');
	$select_SQL .= ";";
	if($judge == true)
	{
		$select_SQL = "";
	}
	return $select_SQL;
}

/************************************************************************************************************
function kensakuSelectSQL($post,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function kensakuSelectSQL($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
	$columns_array = explode(',',$columns);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$befor_year = ($year - 2);
	$after_year = ($year + 3);

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	for($i = 0 ; $i < count($masterNums_array) ; $i++)
	{
		$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
		$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
						$masterNums_array[$i]."CODE = ".$masterName[$i].".".
						$masterNums_array[$i]."CODE ) ";
	}
	$select_SQL .="WHERE date_format(".$tableName."."
					.$form_ini[$columns_array[0]]['column'].",'%Y') BETWEEN ";
	$select_SQL .= $befor_year." AND ".$after_year." ;";
	return($select_SQL);
}

/************************************************************************************************************
function codeSelectSQL($code,$tablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function codeSelectSQL($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$fieldtype_ini = parse_ini_file('./ini/fieldtype.ini');
	require_once 'f_DB.php';
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$code_array = explode(',',$code);
	$tableName = $form_ini[$tablenum]['table_name'];
	$masterNums = $form_ini[$tablenum]['seen_table_num'];
	$masterNums_array = explode(',',$masterNums);

	//------------------------//
	//          変数          //
	//------------------------//
	$select_SQL = "";
	$masterName = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$select_SQL .= "SELECT * FROM ".$tableName." ";
	for($i = 0 ; $i < count($masterNums_array) ; $i++)
	{
		$masterName[$i] = $form_ini[$masterNums_array[$i]]['table_name'];
		$select_SQL .= "LEFT JOIN ".$masterName[$i]." ON (".$tableName.".".
						$masterNums_array[$i]."CODE = ".$masterName[$i].".".
						$masterNums_array[$i]."CODE ) ";
	}
	$select_SQL .="WHERE";
	for($i = 0 ; $i < count($code_array) ; $i++ )
	{
		$select_SQL .= " ".$tablenum."CODE = ".$code_array[$i]." OR";
	}
	$select_SQL = rtrim($select_SQL,'OR');
	$select_SQL .= ";";
	return($select_SQL);
}


/************************************************************************************************************
function codeCountSQL($tablenum,$listtablenum)

引数	$post

戻り値	なし
************************************************************************************************************/
function codeCountSQL($tablenum,$listtablenum){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$tableName = $form_ini[$listtablenum]['table_name'];
	$code = $_SESSION['list']['id'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM ".$tableName." WHERE ".$tablenum."CODE = ".$code." ;";
	
	return($sql);
	
}




/************************************************************************************************************
function hannyuusyutuSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function hannyuusyutuSQL($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = $SQL_ini[$filename]['sql1'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '0' ";
//			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'];
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
		}
	}
	else
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
	}
	$sql .= $SQL_ini[$filename]['sql2'];
	if(isset($post['4CODE']) && $filename == 'HENKYAKUINFO_2')
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '0' ";
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
		}
	}
	else if($filename == 'HENKYAKUINFO_2')
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '0' ";
	}
	$sql .= $SQL_ini[$filename]['sql3'];
	
	$sqlresult[0] = $SQL_ini[$filename]['sql'].$sql;
	$sqlresult[1] = "SELECT COUNT(*)".$sql;
	
	
	return($sqlresult);
	
}



/************************************************************************************************************
function itemListSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function itemListSQL($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = array();
	$sql = "";
	$serchkey = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	if($filename == 'SIZAILIST_2' || $filename == 'GENBALIST_2')
	{
		$serchkey = 'GENBA';
	}
	else
	{
		$serchkey = $filename;
	}
	$sql = $SQL_ini[$serchkey]['sql1'];
	if(isset($post['4CODE'])  && $filename == 'SIZAILIST_2')
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'].' ;';
		}
		else
		{
			$sql .= ' ;';
		}
	}
	else if(isset($post['1CODE'])  && $filename == 'GENBALIST_2')
	{
		if($post['1CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['1CODE'].' ;';
		}
		else
		{
			$sql .= ' ;';
		}
	}
	else if($filename == 'ZAIKOINFO_2')
	{
		$isone = false;
		if(isset($post['form_102_0']))
		{
			$value = explode(' ',$post['form_102_0']);
			$value = implode('%',$value);
			$sql .= " WHERE sizaiinfo.SIZAIID LIKE '%".$post['form_102_0']."%' ";
			$isone  = true;
		}
		if(isset($post['form_103_0']))
		{
			$value = explode(' ',$post['form_103_0']);
			$value = implode('%',$value);
			if($isone)
			{
				$sql .= " AND ";
			}
			else
			{
				$sql .= " WHERE ";
			}
			$columnValue = str_replace(" ", "%", $post['form_103_0']); 
			$columnValue = str_replace("　", "%", $columnValue);
			$sql .= " convert(replace(replace(sizaiinfo.SIZAINAME,' ',''),'　','') using utf8) COLLATE utf8_unicode_ci ";
			$sql .= " LIKE '%".$columnValue."%' ";
		}
		$sql .= " ;";
	}
	else if($filename == 'MONTHLIST_2')
	{
		$isone = false;
		if(isset($post['5CODE']))
		{
			if($post['5CODE'] != "")
			{
				$sql .= " WHERE 5CODE = ".$post['5CODE']." ";
				$isone  = true;
			}
		}
		if(isset($post['4CODE']))
		{
			if($post['4CODE'] != "")
			{
				if($isone)
				{
					$sql .= " AND ";
				}
				else
				{
					$sql .= " WHERE ";
					$isone  = true;
				}
				$sql .= " 4CODE = ".$post['4CODE']." ";
			}
		}
		if(isset($post['form_904_0']))
		{
			
			if($post['form_904_0'] != "")
			{
				if($isone)
				{
					$sql .= " AND ";
				}
				else
				{
					$sql .= " WHERE ";
				}
				$sql .= " PERIOD = ".$post['form_904_0']." ";
			}
		}
//		$sql .= $SQL_ini[$serchkey]['group'];
//		$sql .= " ;";
	}
	else if($filename == 'PJLIST_2')
	{
		if(isset($post['5CODE']))
		{
			
			if($post['5CODE'] != "")
			{
				if($isone)
				{
					$sql .= " AND ";
				}
				else
				{
					$sql .= " WHERE ";
					$isone  = true;
				}
				$sql .= " c.5CODE = ".$post['5CODE']." ";
			}
		}
		
		if(isset($post['4CODE']))
		{
			
			if($post['4CODE'] != "")
			{
				if($isone)
				{
					$sql .= " AND ";
				}
				else
				{
					$sql .= " WHERE ";
					$isone  = true;
				}
				$sql .= " b.4CODE = ".$post['4CODE']." ";
			}
		}
		if(isset($post['form_708_0']))
		{
			
			if($post['form_708_0'] != "")
			{
				if($isone)
				{
					$sql .= " AND ";
				}
				else
				{
					$sql .= " WHERE ";
					$isone  = true;
				}
				$sql .= " c.5PJSTAT = ".$post['form_708_0']." ";
			}
		}
		if(!empty($post['form_start_0']) || !empty($post['form_start_1']) || !empty($post['form_start_2']))
		{
			$startY = $post['form_start_0'];
			$startM = $post['form_start_1'];
			$startD = $post['form_start_2'];
			$startdate = "";
			$endY = $post['form_end_0'];
			$endM = $post['form_end_1'];
			$endD = $post['form_end_2'];
			$enddate = "";
		}
		if ( !empty($startY) && !empty($startM) && !empty($startD)){
			$startdate = $startY."-".$startM."-".$startD;
		}
		
		if (!empty($endY) && !empty($endM) && !empty($endD)){
			$enddate = $endY."-".$endM."-".$endD;
		}
		
		if ( !empty($startdate) && !empty($enddate)) {
			if($isone)
			{
				$sql .= " AND ";
			}
			else
			{
				$sql .= " WHERE ";
				$isone  = true;
			}
			
			$sql .= " progressinfo.SAGYOUDATE BETWEEN '".$startdate."' AND '".$enddate."' ";
		}
		
		$cntsql = $sql;
		$sql .= ' group by progressinfo.7CODE';
		$sql .= ' ;';
	}
	else
	{
		$sql .= ' ;';
	}
	
	$sqlresult[0] = $SQL_ini[$serchkey]['sql'].$sql;
	if ($filename == 'PJLIST_2')
	{
		$sql = $cntsql;
	}

	$sqlresult[1] = "SELECT COUNT(*)".$sql;
//麻野間	SELECT文にGROUP BYとセミコロンを追加
	if($filename == 'MONTHLIST_2')
	{
		$sqlresult[0] .= $SQL_ini[$serchkey]['group'];
		$sqlresult[1] = "SELECT COUNT(*) FROM (" . $sqlresult[0] ." ) AS DUMMY ;" ;
		$sqlresult[0] .= " ;";
		//$sqlresult[1] .= " ;";
		//$sqlresult[1] = " ;";
	}
	
	
	return($sqlresult);
	
}



/************************************************************************************************************
function henkyakuSQL($post)

引数	$post

戻り値	なし
************************************************************************************************************/
function henkyakuSQL($post,$genbastatus){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = 'HENKYAKUINFO_2';
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = "";
	$sql = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = $SQL_ini[$filename]['sql1'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE']." AND genbainfo.GENBASTATUS = '".$genbastatus."' ";
		}
		else
		{
			$sql .=  " WHERE genbainfo.GENBASTATUS = '".$genbastatus."' ";
		}
	}
	else
	{
		$sql .=  " WHERE genbainfo.GENBASTATUS = '".$genbastatus."' ";
	}
	$sql .= $SQL_ini[$filename]['sql2'];
	if(isset($post['4CODE']))
	{
		if($post['4CODE'] != '')
		{
			$sql .= $SQL_ini[$filename]['where'].' '.$post['4CODE'];
		}
	}
	$sql .= $SQL_ini[$filename]['sql3'];
	
	$sqlresult = $SQL_ini[$filename]['sql'].$sql;
	
	
	return($sqlresult);
	
}

/************************************************************************************************************
function SQLsetOrderby($post,$tablenum,$sql)

引数	$post

戻り値	なし
************************************************************************************************************/
function SQLsetOrderby($post,$tablenum,$sql){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$orderby = " ORDER BY ";
	$orderby_columns = $form_ini[$tablenum]['orderby_columns'];
	$orderby_columns_array = explode(',',$orderby_columns);
	$orderby_type = $form_ini[$tablenum]['orderby_type'];
	$orderby_type_array = explode(',',$orderby_type);
	$oderby_array = array();
	$oderby_array[0] = " ASC ";
	$oderby_array[1] = " DESC ";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sqlresult = "";
	
	$sql[0] = substr($sql[0],0,-1);
	$sql[1] = substr($sql[1],0,-1);
	//------------------------//
	//          処理          //
	//------------------------//
	
	if($filename == 'ENDPJLIST_2' || $filename == 'pjagain_5' || $filename == 'pjend_5')
	{
		$sql[0] .= " ORDER BY projectnuminfo.PROJECTNUM DESC, edabaninfo.EDABAN DESC";
		$sql[1] .= " ORDER BY projectnuminfo.PROJECTNUM DESC, edabaninfo.EDABAN DESC";
	}
	else
	{
		for($i = 0 ; $i < count($orderby_columns_array) ; $i++ )
		{
			if($orderby_columns == "")
			{
				break;
			}
			$orderby_column_name = $form_ini[$orderby_columns_array[$i]]['column'];
			$sql[0] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
			$sql[1] .= " ".$orderby." ".$orderby_column_name." ".$oderby_array[$orderby_type_array[$i]];
			$orderby = " , ";
		}
	}
	
	
	if(isset($post['sort']))
	{
		$orderby_column_num = $post['sort'];
		if($orderby_column_num != 0 && $orderby_column_num != 1)
		{
			$orderby_table_num = $form_ini[$orderby_column_num]['table_num'];
			$orderby_column_name = $form_ini[$orderby_column_num]['column'];
			$orderby_table_name = $form_ini[$orderby_table_num]['table_name'];
			$sql[0] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
			$sql[1] .= " ".$orderby." ".$orderby_table_name.".".
							$orderby_column_name." ".$post['radiobutton'];
		}
	}
	
	$sql[0] .= " ;";
	$sql[1] .= " ;";
	return($sql);
	
}


?>
