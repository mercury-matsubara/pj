<?php
set_time_limit(600);

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////




/***************************************************************************
function dbconect()


引数			なし

戻り値	$con	mysql接続済みobjectT
***************************************************************************/

function dbconect(){


//-----------------------------------------------------------//
//                                                           //
//                     DBアクセス処理                        //
//                                                           //
//-----------------------------------------------------------//

	
	//-----------------------------//
	//   iniファイル読み取り準備   //
	//-----------------------------//
	$db_ini_array = parse_ini_file("./ini/DB.ini",true);																// DB基本情報格納.iniファイル
	
	//-------------------------------//
	//   iniファイル内情報取得処理   //
	//-------------------------------//
	$host = $db_ini_array["database"]["host"];																			// DBサーバーホスト
	$user = $db_ini_array["database"]["user"];																			// DBサーバーユーザー
	$password = $db_ini_array["database"]["userpass"];																	// DBサーバーパスワード
	$database = $db_ini_array["database"]["database"];																	// DB名
	
	
	//------------------------//
	//     DBアクセス処理     //
	//------------------------//
	$con = new mysqli($host,$user,$password, $database, "3306") or die('1'.$con->error);			// DB接続
	
	$con->set_charset("cp932") or die('2'.$con->error);												// cp932を使用する
	return ($con);
}


/************************************************************************************************************
function login($userName,$usserPass)


引数1	$userName				ユーザー名
引数2	$userPass				ユーザーパスワード

戻り値	$result					ログイン結果
************************************************************************************************************/
	
function login($userName,$userPass){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$Loginsql = "select * from loginuserinfo where LUSERNAME = '".$userName."' AND LUSERPASS = '".$userPass."' ;";		// ログインSQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$log_result = false;																								// ログイン判断
	$rownums = 0;																										// 検索結果件数
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($Loginsql);																					// クエリ発行
	$rownums = $result->num_rows;																						// 検索結果件数取得
	
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
	if ($rownums == 1)
	{
		$log_result = true;																								// ログイン結果true
	}
	return ($log_result);
	
}


/************************************************************************************************************
function limit_date()


引数	なし					ユーザー名

戻り値	$result					有効期限結果
************************************************************************************************************/
	
function limit_date(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$Loginsql = "select * from systeminfo;";																		// 有効期限SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$limit_result = 0;																								// 有効期限判断
	$rownums = 0;																									// 検索結果件数
	$startdate = "";
	$enddate = "";
	$befor_month = "";
	$message = "";
	$result_limit = array();
	
	//------------------------//
	//    ログイン検索処理    //
	//------------------------//
	$con = dbconect();																								// db接続関数実行
	$result = $con->query($Loginsql) or die($con-> error);														// クエリ発行
	$rownums = $result->num_rows;																					// 検索結果件数取得
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$startdate = $result_row['STARTDATE'];
	}
	
	//------------------------//
	//    ログイン判断処理    //
	//------------------------//
	$enddate = date_create($startdate);
	$enddate = date_add($enddate, date_interval_create_from_date_string('1 year'));
	$enddate = date_sub($enddate, date_interval_create_from_date_string('1 days'));
	$enddate = date_format($enddate, 'Y-m-d');
	$befor_month = date_create($enddate);
	$befor_month = date_format($befor_month, 'Y-m-01');
	$befor_month = date_create($befor_month);
	$befor_month = date_sub($befor_month, date_interval_create_from_date_string('1 month'));
	$befor_month = date_format($befor_month, 'Y-m-d');
	if($enddate >= $date)
	{
		$limit_result = 1;
		if($befor_month <= $date)
		{
			$enddate2 = date_create($enddate);
			$date2 = date_create($date);
			$limit_result = 2;
			$interval = date_diff($date2, $enddate2);
			$message = $interval->format('%a');
		}
	}
	else
	{
		$limit_result = 0;
	}
	$result_limit[0] = $limit_result;
	$result_limit[1] = $message;
	return ($result_limit);
	
}
/************************************************************************************************************
function UserCheck($userID,$userPass)


引数1	$userID						ユーザー名
引数2	$userPass					ユーザーパス

戻り値	$columnName					既に登録されているカラム名
************************************************************************************************************/
	
function UserCheck($userID,$userPass){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$checksql1 = "select * from loginuserinfo where LUSERNAME ='".$userID."' OR LUSERPASS ='".$userPass."' ;";			// 既登録確認SQL文1
	$checksql2 = "select * from loginuserinfo where LUSERNAME ='".$userID."' ;";										// 既登録確認SQL文2
	$checksql3 = "select * from loginuserinfo where LUSERPASS ='".$userPass."' ;";										// 既登録確認SQL文3
	
	//------------------------//
	//          変数          //
	//------------------------//
	$columnName = ""		;																							// 既に登録されているカラム名宣言
	$rownums = 0;																										// 検索結果件数
	
	//------------------------//
	//      チェック処理      //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($checksql1);																					// クエリ発行
	$rownums = $result->num_rows;																						// 検索結果件数取得
	if($rownums == 0)
	{
		return($columnName);
	}
	else
	{
		$result = $con->query($checksql2);																				// クエリ発行
		$rownums = $result->num_rows;																					// 検索結果件数取得
		if($rownums != 0)
		{
			$columnName .= 'LUSERNAME';
		}
		return($columnName);
	}
	
	
	
}


/************************************************************************************************************
function insertUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function insertUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['insertUser']['uid'];
	$userPass = $_SESSION['insertUser']['pass'];
	$insertsql = "insert into loginuserinfo (LUSERNAME,LUSERPASS) value ('".$userID."','".$userPass."') ;";				// 既登録確認SQL文

	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($insertsql);																							// クエリ発行
}


/************************************************************************************************************
function selectUser()


引数	なし

戻り値	list			listhtml
************************************************************************************************************/
	
function selectUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	if(!isset($_SESSION['listUser']))
	{
		$_SESSION['listUser']['limit'] = ' limit 0,10';
		$_SESSION['listUser']['limitstart'] =0;
		$_SESSION['listUser']['where'] ='';
		$_SESSION['listUser']['orderby'] ='';
	}
	
	//------------------------//
	//          定数          //
	//------------------------//
	$limit = $_SESSION['listUser']['limit'];																			// limit
	$limitstart = $_SESSION['listUser']['limitstart'];																	// limit開始位置
	$where = $_SESSION['listUser']['where'];																			// 条件
	$orderby = $_SESSION['listUser']['orderby'];																		// order by 条件
	$totalSelectsql = "SELECT * from loginuserinfo ".$where." ;";														// 管理者全件取得SQL
	$selectsql = "SELECT * from loginuserinfo ".$where.$orderby.$limit." ;";											// 管理者リスト分取得SQL文
	
	//------------------------//
	//          変数          //
	//------------------------//
	$totalcount = 0;
	$listcount = 0;
	$list_str = "";
	$counter = 1;
	$id ="";
	
	//------------------------//
	//        登録処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($totalSelectsql);																				// クエリ発行
	$totalcount = $result->num_rows;																					// 検索結果件数取得
	$result = $con->query($selectsql);																					// クエリ発行
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_str .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_str .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_str .= "<table class = 'list' ><thead><tr>";
	$list_str .= "<th>No.</th>";
	$list_str .= "<th>管理者ID</th>";
	$list_str .= "<th>編集</th>";
	$list_str .= "</tr></thead>";
	$list_str .= "<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_str .= "<tr><td ".$id." class = 'td1' >".($limitstart + $counter)."</td>";
		$list_str .= "<td ".$id."class = 'td2' >".$result_row['LUSERNAME']."</td>";
		$list_str .= "<td ".$id." class = 'td3'><input type='submit' name='"
					.$result_row['LUSERID']."_edit' value = '編集'></td></tr>";
		$counter++;
	}
	$list_str .= "</tbody>";
	$list_str .= "</table>";
	$list_str .= "<div class = 'left'>";
	$list_str .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div><div class = 'left'>";
	$list_str .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div>";
	$list_str .="<div style='clear:both;'></div>";

	return($list_str);
}

/************************************************************************************************************
function selectID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function selectID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$selectidsql = "SELECT * FROM loginuserinfo where LUSERID = ".$id." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($selectidsql);																				// クエリ発行
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function updateUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function updateUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$userID = $_SESSION['editUser']['uid'];
	$userPass = $_SESSION['editUser']['newpass'];
	$id = $_SESSION['listUser']['id'];
	$updatesql = "UPDATE loginuserinfo SET LUSERNAME ='"
				.$userID."', LUSERPASS = '".$userPass."' where LUSERID = ".$id." ;";									// 更新SQL文

	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($updatesql);																							// クエリ発行
}
/************************************************************************************************************
function deleteUser()


引数	なし

戻り値	なし
************************************************************************************************************/
	
function deleteUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$id = $_SESSION['result_array']['LUSERID'];
	$deletesql = "DELETE FROM loginuserinfo where LUSERID = ".$id." ;";													// 更新SQL文

	//------------------------//
	//        更新処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$con->query($deletesql);																							// クエリ発行
}



/************************************************************************************************************
function makeList($sql,$post)

引数1	$sql						検索SQL
引数2	$post						ページ移動時のポスト

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList($sql,$post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['result_num'];
	$columns_array = explode(',',$columns);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit_num = $form_ini[$filename]['limit'];
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
		$quotient = floor($totalcount / $limit_num);
		$remainder = $totalcount % $limit_num;
		if($remainder != 0)
		{
			$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
		}
		else
		{
			$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
		}
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																									// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$title_name = $form_ini[$columns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	if($isEdit == 1)
	{
		$list_html .="<th><a class ='head'>編集</a></th></tr><thead>";
	}
	if($filename == 'nenzi_5'){
		$list_html .="<th><a class ='head'>処理</a></th></tr><thead>";
	}

	$list_html .="<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$disabled = "";
		if($filename == 'SAIINFO_2')
		{
			if(isset($result_row['SAISTATUS']))
			{
				if($result_row['SAISTATUS'] == 1)
				{
					$disabled = " disabled = 'disabled' ";
				}
			}
		}
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			if ($field_name == "PROJECTNUM" )
			{
				$value = substr($result_row[$field_name],0,2)."-".substr($result_row[$field_name],2,4);
			}
			if ($field_name == "EDABAN" )
			{
				$value = substr($result_row[$field_name],0,4)."-".substr($result_row[$field_name],4,2);
			}
			$type = $form_ini[$columns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 3)
			{
				$class = "class = 'right' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".
			$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		if($isEdit == 1)
		{
//			$list_html .= "<td ".$id."><input type='submit' name='edit_".
//							$result_row[$main_table.'CODE']."' value = '編集' ".$disabled."></td>";
			if($filename == "PJTOUROKU_2")
			{
				$list_html .= "<td ".$id."  valign='top'><input type='submit' name='item_".
								$result_row[$main_table.'CODE']."' value = '編集' ".$disabled."></td>";
			}
			else
			{
				$list_html .= "<td ".$id."  valign='top'><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '編集' ".$disabled."></td>";
			}
		}
		
		if($filename == 'nenzi_5'){
			$list_html .= "<td ".$id."  valign='top'><input type='submit' name='edit_".
				$result_row[$main_table.'CODE']."' value = '期またぎ' ".$disabled."></td>";
		}
		
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .="<div style='display:inline-flex'>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='一番最初に戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'left'>";
	$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='一番最後に進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div style='clear:both;'></div>";
	return ($list_html);
}



/************************************************************************************************************
function makeList_Modal($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_Modal($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit_num = $form_ini[$filename]['limit'];
	$limit = $_SESSION['Modal']['limit'];																				// limit
	$limitstart = $_SESSION['Modal']['limitstart'];																		// limit開始位置
	$resultcolumns = $form_ini[$tablenum]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	//------------------------//
	//          振分          //
	//------------------------//
	
	$filename = $_SESSION['filename'];
	
	
	
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$columns = '402,403,202,203';
		$columns_array = explode(',',$columns);
	}
	
	
	
	
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
		$quotient = floor($totalcount / $limit_num);
		$remainder = $totalcount % $limit_num;
		if($remainder != 0)
		{
			$_SESSION['Modal']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
		}
		else
		{
			$_SESSION['Modal']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
		}
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																								// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	$list_html .="<tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		$column_value = $result_row[$tablenum.'CODE'].'#$';
		$form_name = $tablenum.'CODE,';
		$form_type .= '9,';
		for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
		{
			$field_name = $form_ini[$resultcolumns_array[$i]]['column'];
			$format = $form_ini[$resultcolumns_array[$i]]['format'];
			$value = $result_row[$field_name];
			if ($field_name == "PROJECTNUM" )
			{
				$value = substr($result_row[$field_name],0,2)."-".substr($result_row[$field_name],2,4);
			}
			if ($field_name == "EDABAN" )
			{
				$value = substr($result_row[$field_name],0,4)."-".substr($result_row[$field_name],4,2);
			}
			$type = $form_ini[$resultcolumns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 4)
			{
				$class = "class = 'right'";
			}
			else
			{
				$class = "";
			}
			$row .="<td ".$id." ".$class." ><a class ='body'>"
						.$value."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			$form_value = formvalue_return($columns_array[$i],$value,$type);
			$form_name .= $form_value[0];
			$column_value .= $form_value[1];
			$form_type .=  $form_value[2];
		}
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='一番最初に戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'left'>";
	$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='一番最後に進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div style='clear:both;'></div>";

	return ($list_html);
}

/************************************************************************************************************
function existCheck($post,$tablenum,$type)

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function existCheck($post,$tablenum,$type){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	//require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$uniquecolumn = $form_ini[$filename]['uniquecheck'];
	$uniquecolumn_array = explode(',',$uniquecolumn);
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$master_tablenum_array = explode(',',$master_tablenum);
	//------------------------//
	//          変数          //
	//------------------------//
	$errorinfo = array();
	$errorinfo[0] = "";
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$table_title = "";
	$counter = 1;
	$syorimei = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	switch($type)
	{
	case 1 :
		$syorimei = "登録";
		break;
	case 2 :
		$syorimei = "編集";
		break;
	case 3 :
		$syorimei = "削除";
		break;
	default :
		break;
	}
	$con = dbconect();																									// db接続関数実行
	if($type == 1)
	{

		if ($filename ==  "PJTOUROKU_1") {
			$cntrow = 0;
			$code1 = "";
			$code2 = "";
			$code1 = $post['1CODE'];
			$code2 = $post['2CODE'];

			$sql = "SELECT COUNT(*) FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2." ;";

			$result = $con->query($sql) or ($judge = true);																	// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$cntrow = $result_row['COUNT(*)'] ;
			}
			
			if($cntrow > 0){
				$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
										$table_title."すでに登録されているPJ情報ため".
										$syorimei."できません。</a></div><br>";
				$counter++;
			}
		}
	
	}
	if($type == 2)
	{
		$table_title = $form_ini[$tablenum]['table_title'];
		$code = $tablenum.'CODE';
		$codeValue = $post[$code];
		$sql = idSelectSQL($codeValue,$tablenum,$code);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."情報が削除されているため".
									$syorimei."できません。</a></div><br>";
			$counter++;
		}
		else
		{
			$errorinfo[$counter] = "";
			$counter++;
		}
	}
	for( $j = 0 ; $j < count($uniquecolumn_array) ; $j++)
	{
		if($uniquecolumn_array[$j] == "")
		{
			break;
		}
		$sql = uniqeSelectSQL($post,$tablenum,$uniquecolumn_array[$j]);
		if($sql != '')
		{
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			if(isset($result->num_rows) && $result->num_rows != 0 )
			{
				$errorinfo[0] .= $uniquecolumn_array[$j].",";
			}
		}
	}
	for($k = 0 ; $k < count($master_tablenum_array) ; $k++ )
	{
		if($master_tablenum == '')
		{
			break;
		}
		$table_title = $form_ini[$master_tablenum_array[$k]]['table_title'];
		$code = $master_tablenum_array[$k].'CODE';
		$codeValue = $post[$code];
		$sql = idSelectSQL($codeValue,$master_tablenum_array[$k],$code);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."情報が削除されているため".
									$syorimei."できません。</a></div><br>";
			$counter++;
		}
	}
	return ($errorinfo);
}
/************************************************************************************************************
function endCheck($year,$month)

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function endCheck($year,$month){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	//require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];

	//------------------------//
	//          変数          //
	//------------------------//
	$errorinfo = array();
	$errorinfo[0] = "";
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$table_title = "";
	$counter = 1;
	$syorimei = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$year."' AND MONTH = '".$month."';";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows > 0)
	{
		$errorinfo[1] = "<div class = 'center'><a class = 'error'>既に月次処理が完了している期間のため、登録できません。</a></div><br>";
	}
	return ($errorinfo);
}

/************************************************************************************************************
function insert($post)

引数		$post						入力内容

戻り値		なし
************************************************************************************************************/
function insert($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$endjudge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == 'PROGRESSINFO_2')
	{
		
	}
	if(!$endjudge)
	{
		$sql = InsertSQL($post,$tablenum,"");
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge =false;
		}
		if($main_table_type == 0)
		{
			$main_CODE = $con->insert_id;
			$post[$tablenum.'CODE'] = $main_CODE;
			for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
			{
				if($list_tablenum_array[$i] == "" )
				{
					break;
				}
				$over =getover($post,$list_tablenum_array[$i]);
				for( $j = 0; $j < count($over) ; $j++ )
				{
					$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
					$result = $con->query($sql) or ($judge = true);																// クエリ発行
					if($judge)
					{
						error_log($con->error,0);
					}
				}
			}
		}
                if($filename == 'PJTOUROKU_1')
                {
                    $CODE5 = $con->insert_id;
                    $CODE4 = "";
                    $name_arrsy = array();
                    $keyarray = array_keys($_SESSION['insert']);
                    foreach($keyarray as $key)
                    {
                        if (strstr($key, 'kobetu'))
                        {
                                $name_arrsy = explode('_',$key);
                                $CODE4 = $name_arrsy[1];
                                $judge = false;
                                if($_SESSION['insert'][$key] != '')
                                {
                                        $judge = false;
                                        $SQL = "INSERT INTO projectditealinfo (4CODE,5CODE,DETALECHARGE) VALUES(".$CODE4.",".$CODE5.",".$_SESSION['insert'][$key].");";
                                        $con->query($SQL) or ($judge = true);																	// クエリ発行
                                        if($judge)
                                        {
                                                error_log($con->error,0);
                                                $judge = false;
                                        }
                                }
                        }
                }
                }
		if($filename == 'SIZAIINFO_1')
		{
			$main_CODE = $con->insert_id;
			$sql = "INSERT INTO zaikoinfo (1CODE,ZAIKONUM) VALUES (".$main_CODE.",0)";
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
		}
		if($filename == 'EDABANINFO_1')
		{
			//枝番抽出
			$sql = "SELECT MAX(2CODE) FROM edabaninfo;";
			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge =false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$code2 = $result_row['MAX(2CODE)'] ;
			}
			//PJナンバ抽出
			$sql2 = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$post['form_102_0']."';";
			$result2 = $con->query($sql2) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge =false;
			}
			while($result_row = $result2->fetch_array(MYSQLI_ASSOC))
			{
				$code1 = $result_row['1CODE'] ;
			}
			
			//PJ登録
			$sql3 = "INSERT INTO projectinfo (1CODE,2CODE,CHARGE) VALUES (".$code1.",".$code2.",".$post['form_504_0'].");";
			$result3 = $con->query($sql3) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge =false;
			}
                        $_SESSION['list']['id'] = $con->insert_id;
		}
	}
}

/************************************************************************************************************
function make_post($main_codeValue)

引数		$main_codeValue						メインテーブルのプライマリー番号

戻り値		なし
************************************************************************************************************/
function make_post($main_codeValue){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$table_type = $form_ini[$tablenum]['table_type'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$master_tablenum_array = explode(',',$master_tablenum);
	$uniqecolumns = $form_ini[$filename]['uniquecheck'];
	$uniqecolumns_array = explode(',',$uniqecolumns);
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$over = array();
	$form_name = '';
	$form_type = '';
	$form_param = array();
	$names_array = array();
	$valus_array = array();
	$counter = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$_SESSION['edit'][$code] = $main_codeValue;
	$sql = idSelectSQL($main_codeValue,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		foreach($result_row as $key => $value)
		{
			$form_name = $param_ini[$key]['column_num'];
			foreach($uniqecolumns_array as $uniqevalue)
			{
				if(strstr($uniqevalue, $form_name) == true)
				{
					$_SESSION['edit']['uniqe'][$form_name] = $value;
				}
			}
			$form_type = $form_ini[$form_name]['form_type'];
			$form_param = formvalue_return($form_name,$value,$form_type);
			$names_array = explode(',',$form_param[0]);
			$valus_array = explode('#$',$form_param[1]);
			for($i = 0 ; $i < count($valus_array) ; $i++ )
			{
				$_SESSION['edit'][$names_array[$i]] = $valus_array[$i];
			}
		}
	}
//	if($master_tablenum != '' && $table_type != 1)
	if($master_tablenum != '')
	{
		for($i = 0 ; $i < count($master_tablenum_array) ; $i++ )
		{
			$code = $master_tablenum_array[$i].'CODE';
			$sql = idSelectSQL($_SESSION['edit'][$code],$master_tablenum_array[$i],$code);
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				foreach($result_row as $key => $value)
				{
					$form_name = $param_ini[$key]['column_num'];
					foreach($uniqecolumns_array as $uniqevalue)
					{
						if(strpos($uniqevalue, $form_name) !== false)
						{
							$_SESSION['edit']['uniqe'][$form_name] = $value;
						}
					}
					$form_type = $form_ini[$form_name]['form_type'];
					$form_param = formvalue_return($form_name,$value,$form_type);
					$names_array = explode(',',$form_param[0]);
					$valus_array = explode('#$',$form_param[1]);
					for($j = 0 ; $j < count($valus_array) ; $j++ )
					{
						$_SESSION['edit'][$names_array[$j]] = $valus_array[$j];
					}
				}
			}
		}
	}
	
	if($list_tablenum != '' && $table_type != 1)
//	if($list_tablenum != '')
	{
		for($i = 0 ; $i < count($list_tablenum_array) ; $i++ )
		{
			$code = $tablenum.'CODE';
			$sql = idSelectSQL($main_codeValue,$list_tablenum_array[$i],$code);
			$result = $con->query($sql) or ($judge = true);																// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				foreach($result_row as $key => $value)
				{
					$form_name = $param_ini[$key]['column_num'];
					foreach($uniqecolumns_array as $uniqevalue)
					{
						if(strpos($uniqevalue, $form_name) !== false)
						{
							$_SESSION['edit']['uniqe'][$form_name] = $value;
						}
					}
					$form_type = $form_ini[$form_name]['form_type'];
					$form_param = formvalue_return($form_name,$value,$form_type);
					$names_array = explode(',',$form_param[0]);
					$valus_array = explode('#$',$form_param[1]);
					for($j = 0 ; $j < count($valus_array) ; $j++ )
					{
						$_SESSION['data'][$list_tablenum_array[$i]][$counter][$names_array[$j]] = $valus_array[$j];
					}
				}
				$counter++;
			}
			$counter = 0;
		}
	}
}


/************************************************************************************************************
function update($post)

引数		$post								入力内容

戻り値		なし
************************************************************************************************************/
function update($post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = UpdateSQL($post,$tablenum,"");
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	if($main_table_type == 0)
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if(isset($post['delete'.$list_tablenum_array[$i]]))
			{
				$delete = $post['delete'.$list_tablenum_array[$i]];
				for($j = 0 ; $j < count($delete) ; $j++)
				{
					$delete_param = explode(':',$delete[$j]);
					$delete_path = $delete_param[0];
					$delete_CODE = $delete_param[1];
					$tablenum = $list_tablenum_array[$i];
					$code = $tablenum.'CODE';
					if(file_exists($delete_path))
					{
						unlink($delete_path);
					}
					$sql = DeleteSQL($delete_CODE,$tablenum,$code);
					$result = $con->query($sql) or ($judge = true);																// クエリ発行
					if($judge)
					{
						error_log($con->error,0);
					}
				}
			}
		}
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			if($list_tablenum_array[$i] == "" )
			{
				break;
			}
			$over =getover($post,$list_tablenum_array[$i]);
			for( $j = 0; $j < count($over) ; $j++ )
			{
				$sql = InsertSQL($post,$list_tablenum_array[$i],$over[$j]);
				$result = $con->query($sql) or ($judge = true);																// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}
	
}




/************************************************************************************************************
function make_csv($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_csv($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = array();
	$isonce = true;
	$csv = "";
	$where_csv = "";
	$header_csv = "";
	$value_csv = "";
	$header = "";
	$where = "";
	$path = "";
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	if($filename == 'PJLIST_2' || $filename == 'MONTHLIST_2')
	{
		$sql = itemListSQL($post);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	else
	{
		$sql = joinSelectSQL($post,$tablenum);
		$sql = SQLsetOrderby($post,$filename,$sql);
	}
	if($filename == 'PROGRESSINFO_2')
	{
		$sql[0] = str_replace("*"," PROJECTNUM,EDABAN,PJNAME,KOUTEINAME,STAFFNAME,SAGYOUDATE,TEIZITIME,ZANGYOUTIME ",$sql[0]);
	}
	if($filename == 'PJLIST_2')
	{
		$sql[0] = str_replace("*"," PROJECTNUM,EDABAN,PJNAME,STAFFID,STAFFNAME,KOUTEINAME ",$sql[0]);
	}
	if($filename == 'ENDPJLIST_2')
	{
		$sql[0] = str_replace("*"," a.PROJECTNUM as PROJECTNUM,a.EDABAN as EDABAN,a.PJNAME as PJNAME,STAFFNAME,TEIJITIME,ZANGYOTIME,DETALECHARGE,TOTALTIME,PERFORMANCE ",$sql[0]);
		$sql[0] =  str_replace("endpjinfo", " endpjinfo as a ", $sql[0]);
	}
	if($filename == 'KOUTEIINFO_2')
	{
		$sql[0] = str_replace("*"," 3CODE ,KOUTEIID , KOUTEINAME ",$sql[0]);
	}

	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		foreach($result_row as $key => $value)
		{
			if($isonce == true)
			{
				if($key == '6month' )
				{
					$header = '6月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '7month' )
				{
					$header = '7月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '8month' )
				{
					$header = '8月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '9month' )
				{
					$header = '9月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '10month' )
				{
					$header = '10月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '11month' )
				{
					$header = '11月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '12month' )
				{
					$header = '12月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '1month' )
				{
					$header = '1月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '2month' )
				{
					$header = '2月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '3month' )
				{
					$header = '3月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '4month' )
				{
					$header = '4月';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '5month' )
				{
					$header = '5月';
					$header_csv .= $header." ,";
					$where = key_value($key,$post);
				}
				if($key != 'SYUKKASUM' && $key != 'HENKYAKUSUM' && $key != 'ZAIKO')
				{
					$header = $param_ini[$key]['link_name'];
					$header_csv .= $header.",";
					$where = key_value($key,$post);
				}
				else
				{
					if($key == 'SYUKKASUM')
					{
						$header = "出荷数";
					}
					if($key == 'HENKYAKUSUM')
					{
						$header = "返却数";
					}
					if($key == 'ZAIKO')
					{
						$header = "土場在庫数";
					}
					$header_csv .= $header.",";
					$where = "";
				}
				$where_csv .= $header." = ".$where.",";
			}
			$columnnum = 0;
			if(isset($param_ini[$key]['column_num']))
			{
				$columnnum = $param_ini[$key]['column_num'];
			}
			if($columnnum != 0 )
			{
				$type = $form_ini[$columnnum]['form_type'];
				$format = $form_ini[$columnnum]['format'];
				//金額をカンマ区切りにしないように変更
				if($columnnum == '604' ||$columnnum == '806' )
				{
					$format = '0';
				}
				$value = format_change($format,$value,$type);
			}
			$value = mb_convert_encoding($value, "sjis-win", "cp932");
			$value_csv .= $value.",";
		}
		$value_csv = substr($value_csv,0,-1);
		if($isonce == true)
		{
			$header_csv = substr($header_csv,0,-1);
			$where_csv = substr($where_csv,0,-1);
			$csv .= $where_csv."\r\n".$header_csv."\r\n".$value_csv."\r\n";
		}
		else
		{
			$csv .= $value_csv."\r\n";
		}
		$value_csv = "";
		$header_csv = "";
		$isonce = false;
		
	}
	$path = csv_write($csv);
	return($path);
}
/************************************************************************************************************
function make_getujicsv($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_getujicsv($period,$month){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$isonce = true;
	$csv = "";
	$where_csv = "";
	$header_csv1 = "";
	$value_csv1 = "";
	$header_csv2 = "";
	$value_csv2 = "";
	$header = "";
	$where = "";
	$path = "";
	$before = "";
	$after = ""; 
	$judge = false;
	$year = getyear($month,$period);
	$lastday = getlastday($month,$year);
	$pjArray = array();
	$syaincnt = 0;
	$syainArray = array();
	$pj = array();
	$getuji = array();
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($i = 0; $i <= $lastday; $i++)
	{
		if($i == 0)
		{
			$hedder1 = "社員名,区分,合計,";
			$hedder2 = "\r\n".$period."期　".$month."月\r\n社員名,製番・案件名,区分,合計,";
		}
		else
		{
			$hedder1 .= $i."日,";
			$hedder2 .= $i."日,";
		}
	}
	
	//期間内に進捗データのある社員コードを取得
	$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
			."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
			.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syainArray[$syaincnt] = $result_row['4CODE'];
		$syaincnt++;
	}
	//社員番号別作業時間計算
	for($s = 0; $s < count($syainArray); $s++)
	{
		//初期化
		$name = "";
		$before = "";
		$teizi = 0;
		$zangyou = 0;
		$pjcnt = 0;
		$pjArray = array();
		
		//社員コードと日付を条件に作業日順で選択
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$name  = $result_row['STAFFNAME'];
			//プロジェクトごとに格納
			if(isset($pjArray[$result_row['6CODE']]))
			{
				$pjArray[$result_row['6CODE']][count($pjArray[$result_row['6CODE']])] = $result_row;
			}
			else
			{
				$pjArray[$result_row['6CODE']][0] = $result_row;
			}
			$after = $result_row['SAGYOUDATE'];
			if(!empty($before))
			{
				if($before == $after)
				{
					$teizi += $result_row['TEIZITIME'];
					$zangyou += $result_row['ZANGYOUTIME'];
				}
				else
				{
					//日付が変わるごとにteiziとzangyouを初期化
					$date = explode('-',$before);
					$day = $date[2];
					if(substr($day,0,1) == "0")
					{
						$day = ltrim($day,"0");
					}
					$getuji[$syainArray[$s]]['name'] = $name;
					$getuji[$syainArray[$s]][$day]['teizi'] = $teizi;
					$getuji[$syainArray[$s]][$day]['zangyou'] = $zangyou;
					$teizi = 0;
					$zangyou = 0;
					$teizi += $result_row['TEIZITIME'];
					$zangyou += $result_row['ZANGYOUTIME'];
				}
			}
			else
			{
				
				$teizi += $result_row['TEIZITIME'];
				$zangyou += $result_row['ZANGYOUTIME'];
			}
			$before = $result_row['SAGYOUDATE'];
		}
		//最後のデータを格納
		$date = explode('-',$before);
		$day = $date[2];
		if(substr($day,0,1) == "0")
		{
			$day = ltrim($day,"0");
		}
		$getuji[$syainArray[$s]]['name'] = $name;
		$getuji[$syainArray[$s]][$day]['teizi'] = $teizi;
		$getuji[$syainArray[$s]][$day]['zangyou'] = $zangyou;

		//社員プロジェクト別作業時間計算
		$keyarray = array_keys($pjArray);
		foreach($keyarray as $key)
		{
			//初期化
			$pjbefore = "";
			$pjteizi = 0;
			$pjzangyou = 0;
			
			//プロジェクトが変わるごとに名前とプロジェクト名を格納
			for($i = 0 ; $i < count($pjArray[$key]) ; $i++)
			{
				//
				$pjafter = $pjArray[$key][$i]['SAGYOUDATE'];
				if(!empty($pjbefore))
				{
					if($pjbefore == $pjafter)
					{
						$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
						$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
					}
					else
					{
						//日付が変わるごとにteiziとzangyouを初期化
						$pjdate = explode('-',$pjbefore);
						$pjday = $pjdate[2];
						if(substr($pjday,0,1) == "0")
						{
							$pjday = ltrim($pjday,"0");
						}
						$pj[$key]['name'] = $pjArray[$key][$i]['STAFFNAME'];
						$pj[$key]['pjname'] = $pjArray[$key][$i]['PJNAME'];
						$pj[$key][$pjday]['teizi'] = $pjteizi;
						$pj[$key][$pjday]['zangyou'] = $pjzangyou;
						$pjteizi = 0;
						$pjzangyou = 0;
						$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
						$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
					}
				}
				else
				{
					$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
					$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
				}
				$pjbefore = $pjArray[$key][$i]['SAGYOUDATE'];
				//最後のデータを格納
				if($i == (count($pjArray[$key])-1))
				{
					$pjdate = explode('-',$pjbefore);
					$pjday = $pjdate[2];
					if(substr($pjday,0,1) == "0")
					{
						$pjday = ltrim($pjday,"0");
					}
					$pj[$key]['name'] = $pjArray[$key][$i]['STAFFNAME'];
					$pj[$key]['pjname'] = $pjArray[$key][$i]['PJNAME'];
					$pj[$key][$pjday]['teizi'] = $pjteizi;
				}
			}
			
		}
	}
	
	
	$keyarray = array_keys($getuji);
	//社員コード順にcsvデータ作成
	foreach($keyarray as $key)
	{
		$sum1 = 0;
		$sum2 = 0;
		$hteizi = "";
		$hzangyo = "";
		$teizi = "";
		$zangyo = "";
		for($i = 1; $i <= $lastday; $i++)
		{
			if($i == 1)
			{
				$hteizi = mb_convert_encoding($getuji[$key]['name'], "sjis-win", "cp932").",[定時],";
				$hzangyo = mb_convert_encoding($getuji[$key]['name'], "sjis-win", "cp932").",[残業],";
			}
			if(!empty($getuji[$key][$i]))
			{
				$value1 = $getuji[$key][$i]['teizi'];
				$value2 = $getuji[$key][$i]['zangyou'];
				$sum1 += $getuji[$key][$i]['teizi'];
				$sum2 += $getuji[$key][$i]['zangyou'];
				$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
				$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
				$teizi .= $value1.",";
				$zangyo .= $value2.",";
			}
			else
			{
				$teizi .= ",";
				$zangyo .= ",";
			}
		}
		$value_csv1 .= $hteizi.$sum1.",".$teizi."\r\n".$hzangyo.$sum2.",".$zangyo."\r\n";
	}
	
	$keyarray = array_keys($pj);
	//社員別プロジェクトごとにcsvデータ作成
	foreach($keyarray as $key)
	{
		$sum1 = 0;
		$sum2 = 0;
		$hteizi = "";
		$hzangyo = "";
		$teizi = "";
		$zangyo = "";
		for($i = 1; $i <= $lastday; $i++)
		{
			if($i == 1)
			{
				$hteizi = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[定時],";
				$hzangyo = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[残業],";
			}
			if(!empty($pj[$key][$i]))
			{
				$value1 = $pj[$key][$i]['teizi'];
				$value2 = $pj[$key][$i]['zangyou'];
				$sum1 += $pj[$key][$i]['teizi'];
				$sum2 += $pj[$key][$i]['zangyou'];
				$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
				$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
				$teizi .= $value1.",";
				$zangyo .= $value2.",";
			}
			else
			{
				$teizi .= ",";
				$zangyo .= ",";
			}
		}
		$value_csv2 .= $hteizi.$sum1.",".$teizi."\r\n".$hzangyo.$sum2.",".$zangyo."\r\n";
	}
	$csv = $hedder1."\r\n".$value_csv1."\r\n\r\n".$hedder2."\r\n".$value_csv2;
	$path = csv_write($csv);
	return($path);
}
/************************************************************************************************************
function make_nenjicsv($post)

引数		$post							入力内容

戻り値		$path							csvファイルパス
************************************************************************************************************/
function make_nenjicsv($period){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_File.php");																						// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$isonce = true;
	$csv = "";
	$where_csv = "";
	$header_csv1 = "";
	$value_csv1 = "";
	$header_csv2 = "";
	$value_csv2 = "";
	$header = "";
	$where = "";
	$path = "";
	$before = "";
	$after = ""; 
	$judge = false;
	$start = getyear("6",$period);
	$end = getyear("5",$period);
	$lastday = getlastday($month,$year);
	$pjArray = array();
	$syaincnt = 0;
	$syainArray = array();
	$pj = array();
	$getuji = array();
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($i = 0; $i <= 12; $i++)
	{
		if($i == 0)
		{
			$hedder1 = "社員名,区分,合計,";
			$hedder2 = "\r\n".$period."期\r\n社員名,製番・案件名,区分,合計,";
		}
		else
		{
			if($i <= 7)
			{
				$hedder1 .= ($i+5)."月,";
				$hedder2 .= ($i+5)."月,";
			}
			else
			{
				$hedder1 .= ($i-7)."月,";
				$hedder2 .= ($i-7)."月,";
			}
		}
	}
	
	//期間内に進捗データのある社員コードを取得
	$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
			."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
			.$start."-06-01' AND '".$end."-05-31' ORDER BY syaininfo.4CODE;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syainArray[$syaincnt] = $result_row['4CODE'];
		$syaincnt++;
	}
	//社員番号別作業時間計算
	for($s = 0; $s < count($syainArray); $s++)
	{
		//初期化
		$name = "";
		$before = "";
		$teizi = 0;
		$zangyou = 0;
		$pjcnt = 0;
		$pjArray = array();
		
		//社員コードと日付を条件に作業日順で選択
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$start."-06-01' AND '".$end."-05-31' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$name = $result_row['STAFFNAME'];
			//プロジェクトごとに格納
			if(isset($pjArray[$result_row['6CODE']]))
			{
				$pjArray[$result_row['6CODE']][count($pjArray[$result_row['6CODE']])] = $result_row;
			}
			else
			{
				$pjArray[$result_row['6CODE']][0] = $result_row;
			}
			
			//作業月のみ取得
			$date = explode('-',$result_row['SAGYOUDATE']);
			$month = $date[1];
			if(substr($month,0,1) == "0")
			{
				$month = ltrim($month,"0");
			}
			$after = $month;
			if(!empty($before))
			{
				if($before == $after)
				{
					$teizi += $result_row['TEIZITIME'];
					$zangyou += $result_row['ZANGYOUTIME'];
				}
				else
				{
					//月が変わるごとにteiziとzangyouを初期化
					$nenji[$syainArray[$s]]['name'] = $name;
					$nenji[$syainArray[$s]][$before]['teizi'] = $teizi;
					$nenji[$syainArray[$s]][$before]['zangyou'] = $zangyou;
					$teizi = 0;
					$zangyou = 0;
					$teizi += $result_row['TEIZITIME'];
					$zangyou += $result_row['ZANGYOUTIME'];
				}
			}
			else
			{
				$teizi += $result_row['TEIZITIME'];
				$zangyou += $result_row['ZANGYOUTIME'];
			}
			$before = $month;
		}
		//最後の月を$nenjiに格納
		$nenji[$syainArray[$s]]['name'] = $name;
		$nenji[$syainArray[$s]][$before]['teizi'] = $teizi;
		$nenji[$syainArray[$s]][$before]['zangyou'] = $zangyou;
		
		//社員プロジェクト別作業時間計算
		$keyarray = array_keys($pjArray);
		foreach($keyarray as $key)
		{
			//初期化
			$pjbefore = "";
			$pjteizi = 0;
			$pjzangyou = 0;
			
			//プロジェクトが変わるごとに名前とプロジェクト名を格納
			for($i = 0 ; $i < count($pjArray[$key]) ; $i++)
			{
				//
				$date = explode('-',$pjArray[$key][$i]['SAGYOUDATE']);
				$pjmonth = $date[1];
				if(substr($pjmonth,0,1) == "0")
				{
					$pjmonth = ltrim($pjmonth,"0");
				}
				$pjafter = $pjmonth;
				if(!empty($pjbefore))
				{
					if($pjbefore == $pjafter)
					{
						$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
						$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
					}
					else
					{
						//月が変わるごとにteiziとzangyouを初期化
						$pj[$key]['name'] = $pjArray[$key][$i]['STAFFNAME'];
						$pj[$key]['pjname'] = $pjArray[$key][$i]['PJNAME'];
						$pj[$key][$pjbefore]['teizi'] = $pjteizi;
						$pj[$key][$pjbefore]['zangyou'] = $pjzangyou;
						$pjteizi = 0;
						$pjzangyou = 0;
						$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
						$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
					}
				}
				else
				{
					$pjteizi += $pjArray[$key][$i]['TEIZITIME'];
					$pjzangyou += $pjArray[$key][$i]['ZANGYOUTIME'];
				}
				$pjbefore = $pjmonth;
				//最後の月を$pjに格納
				if($i == (count($pjArray[$key])-1))
				{
					$pj[$key]['name'] = $pjArray[$key][$i]['STAFFNAME'];
					$pj[$key]['pjname'] = $pjArray[$key][$i]['PJNAME'];
					$pj[$key][$pjbefore]['teizi'] = $pjteizi;
					$pj[$key][$pjbefore]['zangyou'] = $pjzangyou;
				}
			}
		}
	}
	
	$keyarray = array_keys($nenji);
	//社員コード順にcsvデータ作成
	foreach($keyarray as $key)
	{
		$sum1 = 0;
		$sum2 = 0;
		$hteizi = "";
		$hzangyo = "";
		$teizi = "";
		$zangyo = "";
		for($i = 1; $i <= 12; $i++)
		{
			if($i == 1)
			{
				$hteizi = mb_convert_encoding($nenji[$key]['name'], "sjis-win", "cp932").",[定時],";
				$hzangyo = mb_convert_encoding($nenji[$key]['name'], "sjis-win", "cp932").",[残業],";
			}
			if($i <= 7)
			{
				if(!empty($nenji[$key][($i+5)]))
				{
					$value1 = $nenji[$key][($i+5)]['teizi'];
					$value2 = $nenji[$key][($i+5)]['zangyou'];
					$sum1 += $nenji[$key][($i+5)]['teizi'];
					$sum2 += $nenji[$key][($i+5)]['zangyou'];
					$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
					$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
					$teizi .= $value1.",";
					$zangyo .= $value2.",";
				}
				else
				{
					$teizi .= ",";
					$zangyo .= ",";
				}
			}
			else
			{
				if(!empty($nenji[$key][($i-7)]))
				{
					$value1 = $nenji[$key][($i-7)]['teizi'];
					$value2 = $nenji[$key][($i-7)]['zangyou'];
					$sum1 += $nenji[$key][($i-7)]['teizi'];
					$sum2 += $nenji[$key][($i-7)]['zangyou'];
					$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
					$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
					$teizi .= $value1.",";
					$zangyo .= $value2.",";
				}
				else
				{
					$teizi .= ",";
					$zangyo .= ",";
				}
			}
		}
		$value_csv1 .= $hteizi.$sum1.",".$teizi."\r\n".$hzangyo.$sum2.",".$zangyo."\r\n";
	}
	
	$keyarray = array_keys($pj);
	//社員別プロジェクトごとにcsvデータ作成
	foreach($keyarray as $key)
	{
		$sum1 = 0;
		$sum2 = 0;
		$hteizi = "";
		$hzangyo = "";
		$teizi = "";
		$zangyo = "";
		for($i = 1; $i <= 12; $i++)
		{
			if($i == 1)
			{
				$hteizi = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[定時],";
				$hzangyo = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[残業],";
			}
			if($i <= 7)
			{
				if(!empty($pj[$key][($i+5)]))
				{
					$value1 = $pj[$key][($i+5)]['teizi'];
					$value2 = $pj[$key][($i+5)]['zangyou'];
					$sum1 += $pj[$key][($i+5)]['teizi'];
					$sum2 += $pj[$key][($i+5)]['zangyou'];
					$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
					$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
					$teizi .= $value1.",";
					$zangyo .= $value2.",";
				}
				else
				{
					$teizi .= ",";
					$zangyo .= ",";
				}
			}
			else
			{
				if(!empty($pj[$key][($i-7)]))
				{
					$value1 = $pj[$key][($i-7)]['teizi'];
					$value2 = $pj[$key][($i-7)]['zangyou'];
					$sum1 += $pj[$key][($i-7)]['teizi'];
					$sum2 += $pj[$key][($i-7)]['zangyou'];
					$value1 = mb_convert_encoding($value1, "sjis-win", "cp932");
					$value2 = mb_convert_encoding($value2, "sjis-win", "cp932");
					$teizi .= $value1.",";
					$zangyo .= $value2.",";
				}
				else
				{
					$teizi .= ",";
					$zangyo .= ",";
				}
			}
		}
		$value_csv2 .= $hteizi.$sum1.",".$teizi."\r\n".$hzangyo.$sum2.",".$zangyo."\r\n";
	}
	$csv = $hedder1."\r\n".$value_csv1."\r\n\r\n".$hedder2."\r\n".$value_csv2;
	$path = csv_write($csv);
	return($path);
}
/************************************************************************************************************
function delete($post,$data)

引数1		$post								入力内容
引数2		$data								登録ファイル内容

戻り値	なし
************************************************************************************************************/
function delete($post,$data){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	$delete =array();
	$delete_param = array();
	$delete_path = "";
	$delete_CODE = "";
	$list_insert ="";
	$list_insert_array = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$code = $tablenum.'CODE';
	$delete_CODE = $post[$code];
	$sql = DeleteSQL($delete_CODE,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$delete_path = "";
	$delete_CODE = "";
	if($main_table_type == 0 && $list_tablenum != '')
	{
		for( $i = 0 ; $i < count($list_tablenum_array) ; $i++)
		{
			$list_insert = $form_ini[$list_tablenum_array[$i]]['insert_form_num'];
			$list_insert_array = explode(',',$list_insert);
			$code = $list_tablenum_array[$i].'CODE';
			for($j = 0; $j < count($list_insert_array) ; $j++)
			{
				if(isset($data[$list_tablenum_array[$i]]))
				{
					for($k = 0 ; $k < count($data[$list_tablenum_array[$i]]) ; $k++)
					{
						foreach($data[$list_tablenum_array[$i]][$k] as $key => $value)
						{
							if($key == '')
							{
								// 空アレイの場合
							}
							else if(strstr($key,$list_insert_array[$j]) == true )
							{
								$delete_path = $value;
								$delete_CODE = $data[$list_tablenum_array[$i]][$k][$code];
								break;
							}
						}
						if($delete_path != '' && $delete_CODE != '')
						{
							if(file_exists($delete_path))
							{ 
								unlink($delete_path );
							}
							$sql = DeleteSQL($delete_CODE,$list_tablenum_array[$i],$code);
							$result = $con->query($sql) or ($judge = true);												// クエリ発行
							if($judge)
							{
								error_log($con->error,0);
							}
							$delete_path = "";
							$delete_CODE = "";
						}
					}
				}
			}
		}
	}
	
}

/************************************************************************************************************
function make_zaikokei()

引数	なし

戻り値	なし
************************************************************************************************************/
function make_zaikokei(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "SELECT * FROM zaikoinfo;";
	$judge = false;
	$total = 0;
	$all_price = 0;
	$all_tax = 0;
	$all_recycle = 0;
	$all_cost = 0;
	$all_car_tax = 0;
	$old_buy_day = "";
	$old_make_date = "";
	$year = 99;
	$pre_year=0;
	$year_type = 0;
	$zaiko_param = array();
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$total++;
		$all_price += $result_row['BUYPRICE'];
		$all_tax += $result_row['BUYTAX'];
		$all_recycle += $result_row['CARRECYCLE'];
		$all_cost += $result_row['BUYCOST'];
		$all_car_tax += $result_row['CARTAX'];
		if($old_buy_day == '')
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strtotime($old_buy_day ) >= strtotime($result_row['BUYDATE']))
		{
			$old_buy_day = $result_row['BUYDATE'];
		}
		if(strstr($result_row['MAKEDATE'],'昭和') == true)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 2;
			}
		}
		else if(strstr($result_row['MAKEDATE'],'平成') == true && $year_type != 2)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 1;
			}
		}
		else if($year_type == 0)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 0;
			}
		}
	}
	
	$zaiko_param[0] = $total;
	$zaiko_param[1] = $old_buy_day;
	$zaiko_param[2] = $old_make_date;
	$zaiko_param[3] = $all_price;
	$zaiko_param[4] = $all_tax;
	$zaiko_param[5] = $all_recycle;
	$zaiko_param[6] = $all_cost;
	$zaiko_param[7] = $all_car_tax;
	return($zaiko_param);
	
}


/************************************************************************************************************
function make_kensaku($post,$tablenum)

引数1		$post										選択年月
引数2		$tablenum									メインテーブル番号

戻り値		$syakentable								年月選択リンクテーブル
************************************************************************************************************/
function make_kensaku($post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$befor_year = ($year - 2);
	$after_year = ($year + 3);
	$filename = $_SESSION['filename'];
	$formnum = $form_ini[$filename]['sech_form_num'];
	$columnname = $form_ini[$formnum]['column'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$syakenbi = array();
	$syaken_year ="";
	$syaken_month ="";
	$syakentable = "";
	$counter = 1;
	$wareki = "";
	$wareki1 = "";
	$wareki2 = "";
	$syakendate =array();
	$judge = false;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = kensakuSelectSQL($post,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syakendate = explode('-',$result_row[$columnname]);
		$syaken_year = $syakendate[0];
		$syaken_month = $syakendate[1];
		$syaken_month = ltrim($syaken_month,'0');
		if(isset($syakenbi[$syaken_year][$syaken_month]) == true)
		{
			$syakenbi[$syaken_year][$syaken_month]++;
		}
		else
		{
			$syakenbi[$syaken_year][$syaken_month] = 1;
		}
	}
	$syakentable = "<table id = 'syaken'><tr><th>有効期限満了月</th></tr>";
	for($yearcount = $befor_year ; $yearcount < ($after_year+1) ; $yearcount++)
	{
		$syakentable .= "<tr><td class='year".$counter."'><a class ='kensakuyear'>";
		$counter++;
		$wareki1 = wareki_year($yearcount);
		$wareki2 = wareki_year_befor($yearcount);
		if($wareki1 != $wareki2)
		{
			$wareki = $wareki1."年 - ".$wareki2."年度 [".$yearcount."]";
		}
		else
		{
			$wareki = $wareki1."年度 [".$yearcount."]";
		}
		$syakentable .= $wareki."</a></td>";
		for($monthcount = 1 ;$monthcount < (12 + 1); $monthcount++)
		{
			if(isset($syakenbi[$yearcount][$monthcount]))
			{
				$syakentable .= "<td><a href='./kensakuJump.php?year="
								.$yearcount."&month=".$monthcount."'> ";
				$syakentable .= $monthcount."月[".$syakenbi[$yearcount][$monthcount]."] </a></td>";
			}
			else
			{
				$syakentable .= "<td><a class='itemname'> ";
				$syakentable .= $monthcount."月[0] </a></td>";
			}
		}
		$syakentable .="</tr>";
	}
	$syakentable .="</table>";
	return($syakentable);
}

/************************************************************************************************************
function make_mail($code,$tablenum)

引数1		$code								
引数2		$tablenum							

戻り値		$mail_param							
************************************************************************************************************/
function make_mail($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$adress_column = $mail_ini['param']['adress_column'];
	$title_text = $mail_ini['param']['title'];
	$header_text = $mail_ini['param']['header'];
	$header_text_array = explode('~',$header_text);
	$fotter_text = $mail_ini['param']['fotter'];
	$fotter_text_array = explode('~',$fotter_text);
	$user_column = $mail_ini['param']['user_column'];
	$template = $mail_ini['param']['template'];
	$template_array = explode('~',$template);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$adress = array();
	$title = array();
	$subject = array();
	$user = array();
	$count = 0;
	$mail_param = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = $result->num_rows;
	$count_gap = ($count_code - $count_rows);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$adress[$count] = $result_row[$adress_column];
		$title[$count] = $title_text;
		$subject[$count] = "";
		for($i = 0 ; $i < count($header_text_array) ; $i++)
		{
			if(isset($result_row[$header_text_array[$i]]))
			{
				$column_num = $param_ini[$header_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$header_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($header_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $header_text_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($template_array) ; $i++)
		{
			if(isset($result_row[$template_array[$i]]))
			{
				$column_num = $param_ini[$template_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$template_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($template_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $template_array[$i];
				}
			}
		}
		for($i = 0 ; $i < count($fotter_text_array) ; $i++)
		{
			if(isset($result_row[$fotter_text_array[$i]]))
			{
				$column_num = $param_ini[$fotter_text_array[$i]]['column_num'];
				$format = $form_ini[$column_num]['format'];
				$type = $form_ini[$column_num]['form_type'];
				$value = format_change($format,$result_row[$fotter_text_array[$i]],$type);
				$subject[$count] .= $value;
			}
			else
			{
				if($fotter_text_array[$i] == '<br>')
				{
					$subject[$count] .="\r\n";
				}
				else
				{
					$subject[$count] .= $fotter_text_array[$i];
				}
			}
		}
		$user[$count] = $result_row[$user_column];
		$count++;
	}
	$mail_param[0] = $adress;
	$mail_param[1] = $title;
	$mail_param[2] = $subject;
	$mail_param[3] = $user;
	$mail_param[4] = $count_gap;
	return($mail_param);
}

/************************************************************************************************************
function pdf_select($code_value,$tablenum,$maintablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function pdf_select($code_value,$tablenum,$maintablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$column = $form_ini[$tablenum]['insert_form_num'];
	$columnname = $form_ini[$column]['column'];
	$link_num = $form_ini[$column]['link_num'];
	$code = $maintablenum."CODE";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$pdf_table = "";
	$pdf_path = '';
	$isonece = true ;
	$pdf_result = array();
	$judge = false;
	$count=0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = idSelectSQL($code_value,$tablenum,$code);
	$sql = substr($sql,0,-1);
	$sql .=" order by ".$columnname." desc ;";
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$pdf_table = "<table id = 'link'><tr><td class = 'center'>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$pdf_table .= "<a href = './pdf.php?path=".
						$result_row[$columnname]."&code=".
						$code_value."&tablenum=".
						$tablenum."' target='Modal' >".
						$link_num.($count+1)."</a>&nbsp;";
		$count++;
		if($isonece)
		{
			$pdf_path = $result_row[$columnname];
			$isonece = false;
		}
	}
	$pdf_table .= "</td></tr></table>";
	if($pdf_path =='')
	{
		$pdf_table = '<a class = "error">対象ファイルなし</a>';
	}
	
	$pdf_result[0] = $pdf_table;
	$pdf_result[1] = $pdf_path;
	return($pdf_result);
}


/************************************************************************************************************
function syaken_mail_select()

引数	なし

戻り値	なし
************************************************************************************************************/
function syaken_mail_select(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_mail.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM syakeninfo LEFT JOIN userinfo ON (syakeninfo.3CODE = userinfo.3CODE)";
	$sql .= " LEFT JOIN carinfo ON (syakeninfo.4CODE = carinfo.4CODE)";
	$after_month = $mail_ini['syaken']['after_month'];
	$adress = $mail_ini['syaken']['send_add'];
	$title = $mail_ini['syaken']['title'];
	$header1 = $mail_ini['syaken']['header1'];
	$header2 = $mail_ini['syaken']['header2'];
	$template = $mail_ini['syaken']['template'];
	$title_array = explode('~',$title);
	$header1_array = explode('~',$header1);
	$header2_array = explode('~',$header2);
	$template_array = explode('~',$template);
	$month = date_create('NOW');
	$month = date_format($month, "m");
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	
	//------------------------//
	//          変数          //
	//------------------------//
	$predate ="";
	$date ="";
	$judge = false;
	$title_text = "";
	$body_text = "";
	$head_text = "";
	$sentence_text = "";
	$total = 0;
	$syaken_array =array();
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$predate = $year.'-'.$month.'-01';
	$date = date_create($predate);
	$date = date_add($date, date_interval_create_from_date_string($after_month.' month'));
	$date = date_format($date,'Yn');
	$syaken_date = date_create($predate);
	$syaken_date = date_add($syaken_date, date_interval_create_from_date_string($after_month.' month'));
	$syaken_year = date_format($syaken_date,'Y');
	$syaken_month = date_format($syaken_date,'n');
	$syaken_date = date_format($syaken_date,'Y-m-d');
	$syaken_array['YEAR'] = $syaken_year;
	$syaken_array['MONTH'] = $syaken_month;
	$sql .=" WHERE DATE_FORMAT(EXPIRYDATE,'%Y%c') = '".$date."'";
	$sql .=" ORDER BY EXPIRYDATE ASC ;";
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		for($i = 0 ; $i < count($template_array) ; $i++ )
		{
			if(isset($result_row[$template_array[$i]]))
			{
				$body_text .= $result_row[$template_array[$i]];
			}
			else if($template_array[$i] == "<br>")
			{
				$body_text .= "\r\n";
			}
			else
			{
				$body_text .= $template_array[$i];
			}
		}
		$total++;
	}
	$syaken_array['TOTAL'] =$total;
	for($i = 0 ; $i < count($title_array) ; $i++)
	{
		if(isset($syaken_array[$title_array[$i]]))
		{
			$title_text .= $syaken_array[$title_array[$i]];
		}
		else if($title_array[$i] == "<br>")
		{
			$title_text .= "\r\n";
		}
		else
		{
			$title_text .= $title_array[$i];
		}
	}
	for($i = 0 ; $i < count($header1_array) ; $i++)
	{
		if(isset($syaken_array[$header1_array[$i]]))
		{
			$head_text .= $syaken_array[$header1_array[$i]];
		}
		else if($header1_array[$i] == "<br>")
		{
			$head_text .= "\r\n";
		}
		else
		{
			$head_text .= $header1_array[$i];
		}
	}
	for($i = 0 ; $i < count($header2_array) ; $i++)
	{
		if(isset($syaken_array[$header2_array[$i]]))
		{
			$head_text .= $syaken_array[$header2_array[$i]];
		}
		else if($header2_array[$i] == "<br>")
		{
			$head_text .= "\r\n";
		}
		else
		{
			$head_text .= $header2_array[$i];
		}
	}
	$sentence_text .= $head_text.$body_text;
	sendmail($adress,$title_text,$sentence_text);
}

/************************************************************************************************************
function make_check_array($post,$main_table)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_check_array($post,$main_table){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$check_array = array();
	$judge = false;
	$count = 0;
	$check_str = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = joinSelectSQL($post,$main_table);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$check_str = "check_".$result_row[$main_table.'CODE'];
		$check_array[$count] = $check_str;
		$count++;
	}
	return $check_array;
}

/************************************************************************************************************
function table_code_exist()

引数	なし

戻り値	なし
************************************************************************************************************/
function table_code_exist(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$listtablenum = $form_ini[$tablenum]['see_table_num'];
	$listtablenum_array = explode(',',$listtablenum);
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$isexit = false;
	$count = 0;
	
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($i = 0 ; $i < count($listtablenum_array) ; $i++)
	{
		$sql = codeCountSQL($tablenum,$listtablenum_array[$i]);
		$result = $con->query($sql) or ($judge = true);																	// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$count = $result_row['COUNT(*)'];
		}
		if($count != 0)
		{
			$isexit = true;
		}
		$count = 0;
	}
	return($isexit);
}
/************************************************************************************************************
function make_label($code,$tablenum)

引数	なし

戻り値	なし
************************************************************************************************************/
function make_label($code,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	require_once ("f_Form.php");																						// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$count = 0;
	$label_param = array();
	$useradress = array();
	$username = array();
	$userpostcd = array();
	$orgadress = array();
	$orgname = array();
	$orgpostcd = array();
	$count_code = 0;
	$count_rows = 0;
	$count_gap = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
	}
	$code_array = explode(',',$code);
	$count_code = count($code_array);
	$count_rows = $result->num_rows;
	$count_gap = ($count_code - $count_rows);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$useradress[$count] = $result_row['USERADD1'];
		$username[$count] = $result_row['USERNAME'];
		$userpostcd[$count] = $result_row['USERPOSTCD'];
		$count++;
	}
	$label_param[0] = $useradress;
	$label_param[1] = $username;
	$label_param[2] = $userpostcd;
	$label_param[3] = $orgadress;
	$label_param[4] = $orgname;
	$label_param[5] = $orgpostcd;
	$label_param[6] = $count_gap;
	
	return($label_param);
}
/************************************************************************************************************
function existID($id)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function existID($id){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$selectidsql = "SELECT * FROM ".$tablename." where ".$tablenum."CODE = ".$id." ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($selectidsql);																				// クエリ発行
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function countLoginUser()


引数	

戻り値	
************************************************************************************************************/
	
function countLoginUser(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM loginuserinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	
	$result = $con->query($sql);																				// クエリ発行
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$countnum = $result_row['COUNT(*)'];
	}
	if($countnum > 1)
	{
		$judge = true;
	}
	return($judge);
}


/************************************************************************************************************
function makeList_item($sql,$post)

引数1	$sql						検索SQL

戻り値	list_html					リストhtml
************************************************************************************************************/
function makeList_item($sql,$post){
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $SQL_ini[$filename]['listcolums'];
	$columns_array = explode(',',$columns);
	$columnname = $SQL_ini[$filename]['clumname'];
	$columnname_array = explode(',',$columnname);
	$format = $SQL_ini[$filename]['format'];
	$format_array = explode(',',$format);
	$type = $SQL_ini[$filename]['type'];
	$type_array = explode(',',$type);
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	$isNo = $form_ini[$filename]['isNo'];
	$isList = $form_ini[$filename]['isList'];
	$isEdit = $form_ini[$filename]['isEdit'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$listtable = $form_ini[$main_table]['see_table_num'];
	$listtable_array = explode(',',$listtable);
	$limit_num = $form_ini[$filename]['limit'];
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置

	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$value_GENBA = "未選択";
	$value_4CODE = -1;
	$total_charge = 0;
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		if(isset($post['4CODE']))
		{
			if($post['4CODE'] != "")
			{
				$value_4CODE = $post['4CODE'];
				$sql_GENBA = idSelectSQL($value_4CODE,4,'4CODE');
				$result = $con->query($sql_GENBA);
				while($result_row = $result->fetch_array(MYSQLI_ASSOC))
				{
					$value_GENBA = $result_row['GENBANAME'];
				}
			}
		}
		$list_html .= "<br>選択現場 : ".$value_GENBA."<br><br><input type = 'hidden' id = 'check_4CODE' value = '".
						$value_4CODE."'>";
	}
	$result = $con->query($sql[1]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
		$quotient = floor($totalcount / $limit_num);
		$remainder = $totalcount % $limit_num;
		if($remainder != 0)
		{
			$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
		}
		else
		{
			$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
		}
	}
	$_SESSION['kobetu']['total'] = $totalcount;
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2' && $filename != 'PJTOUROKU_2' && $filename != 'PJTOUROKU_1' && $filename != 'EDABANINFO_2')
	{
		$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
		$sql[0] .= $limit.";";																									// LIMIT追加
	}
	$result = $con->query($sql[0]) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if($filename != 'PJTOUROKU_1')
        {
                if ($totalcount == $limitstart )
                {
                        $list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
                }
                else
                {
                        $list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
                }
        }
	$list_html .= "<table class ='list'";
        if($filename == "PJTOUROKU_1")
        {
            //PJ登録画面は中央寄せ
            $list_html .= " style = 'margin: auto;'";
        }
        $list_html .= "><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>発行</a></th>";
	}
	if($isNo == 1 )
	{
		$list_html .="<th><a class ='head'>No.</a></th>";
	}
	for($i = 0 ; $i < count($columnname_array) ; $i++)
	{
		$list_html .="<th><a class ='head'>".$columnname_array[$i]."</a></th>";
	}
	if($isList == 1)
	{
		for($i = 0 ; $i < count($listtable_array) ; $i++)
		{
			$title_name = $form_ini[$listtable_array[$i]]['table_title'];
			$list_html .="<th><a class ='head'>".$title_name."</a></th>";
		}
	}
	
	
	
	if($filename == 'PJTOUROKU_2' || $filename == 'PJTOUROKU_1' || $filename == 'EDABANINFO_2')
	{
		$list_html .="<th><a class ='head'>社員別金額</a></th>";
	}
	else
	{
		if($isEdit == 1)
		{
			$list_html .="<th><a class ='head'>編集</a></th>";
		}
	}

	
	
	$list_html .="</tr></thead><tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		
		if($isCheckBox == 1)
		{
			$list_html .="<td ".$id. "class = 'center'><input type = 'checkbox' name ='check_".
							$result_row[$main_table.'CODE']."' id = 'check_".
							$result_row[$main_table.'CODE']."'";
			if(isset($post['check_'.$result_row[$main_table.'CODE']]))
			{
				$list_html .= " checked ";
			}
			$list_html .=' onclick="this.blur();this.focus();" onchange="check_out(this.id)" ></td>';
		}
		if($isNo == 1)
		{
			$list_html .="<td ".$id." class = 'center'><a class='body'>".
							($limitstart + $counter)."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $columns_array[$i];
			$format1 = $format_array[$i];
			$value = $result_row[$field_name];
			if ($field_name == "PROJECTNUM" )
			{
				$value = substr($result_row[$field_name],0,2)."-".substr($result_row[$field_name],2,4);
			}
			if ($field_name == "EDABAN" )
			{
				$value = substr($result_row[$field_name],0,4)."-".substr($result_row[$field_name],4,2);
			}
			$type1 = $type_array[$i];
			if($format1 != 0)
			{
				$value = format_change($format1,$value,$type1);
			}
			if($format1 == 3)
			{
				$class = "class = 'right' ";
			}
			else
			{
				$class = "";
			}
			$list_html .="<td ".$id." ".$class." ><a class ='body'>".
			$value."</a></td>";
		}
		if($isList == 1)
		{
			for($i = 0 ; $i < count($listtable_array) ; $i++)
			{
				$list_html .='<td '.$id.'><input type = "button" value ="'
								.$form_ini[$listtable_array[$i]]['table_title'].
								'" onClick ="click_list('.$result_row[$main_table.'CODE'].
								','.$listtable_array[$i].')"></td>';
			}
		}
		
		
		
		
		
		
		if($filename == 'PJTOUROKU_2' || $filename == 'EDABANINFO_2')
		{
			$check_js = 'onChange = " return inputcheck(\'kobetu_'.$totalcount.'_'.$counter.'\',7,7,0,2)"';
			$kobetu_value = null;
			$sql = "SELECT DETALECHARGE FROM  projectditealinfo WHERE 5CODE = ".$_SESSION['kobetu']['id']." AND 4CODE = ".$result_row['4CODE']." ;";
			
			$result1 = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row2 = $result1->fetch_array(MYSQLI_ASSOC))
			{
				if(isset($result_row2['DETALECHARGE']))
				{
					$kobetu_value = $result_row2['DETALECHARGE'];
				}
			}
			$list_html .= "<td ".$id."><input type='text' name='kobetu_".
							$result_row['4CODE']."' id = 'kobetu_".
							$totalcount."_".$counter."' value = '"
							.$kobetu_value."' ".$check_js."></td>";
			$total_charge += $kobetu_value;
		
		}
                else if($filename == 'PJTOUROKU_1')
		{
			$check_js = 'onChange = " return inputcheck(\'kobetu_'.$totalcount.'_'.$counter.'\',7,7,0,2)"';
                        if(isset($_SESSION['insert']['kobetu_'.$result_row['4CODE']]) && $_SESSION['insert']['kobetu_'.$result_row['4CODE']] != null)
                        {
                            $kobetu_value = $_SESSION['insert']['kobetu_'.$result_row['4CODE']];
                        }
                        else
                        {
                            $kobetu_value = null;
                        }
			$list_html .= "<td ".$id."><input type='text' name='kobetu_".
							$result_row['4CODE']."' id = 'kobetu_".
							$totalcount."_".$counter."' value = '"
							.$kobetu_value."' ".$check_js."></td>";
			$total_charge += $kobetu_value;
		
		}
		else
		{
			if($isEdit == 1)
			{
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '編集'></td>";
			}
		}
		
		
		
		
		
		
		
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	if($filename != 'PJTOUROKU_2' && $filename != 'PJTOUROKU_1' && $filename != 'EDABANINFO_2')
	{
		$list_html .="<div style='display:inline-flex'>";
		$list_html .= "<div class = 'left'>";
		$list_html .= "<input type='submit' name ='backall' value ='一番最初に戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
		$list_html .= "<div class = 'left'>";
		$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div><div class = 'left'>";
		$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
		$list_html .="<div class = 'left'>";
		$list_html .= "<input type='submit' name ='nextall' value ='一番最後に進む' class = 'button' style ='height : 30px;' ";
		if(($limitstart + $listcount) == $totalcount)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
	}
	$_SESSION['kobetu']['totalCharge'] = $total_charge;
	return ($list_html);
}



/************************************************************************************************************
function insertnyuusyukka($post)


引数	$id						検索対象ID

戻り値	$result_array			検索結果
************************************************************************************************************/
	
function insertnyuusyukka($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$value_1CODE = "";
	$value_2CODE = "";
	$value_4CODE = "";
	$key_array = array();
	$nyuusyukka_num = 0;
	$type = 0;
	$colname = "";
	if($filename == 'SYUKKAINFO_2')
	{
		$type = 1;
		$colname = $form_ini['504']['column'];
	}
	if($filename == 'HENKYAKUINFO_2')
	{
		$type = 2;
		$colname = $form_ini['604']['column'];
	}
	$date = date_create('NOW');
	$date = date_format($date,'Y-m-d');
	$sagyou_date = $post['form_start_0'];
	$sagyou_date .= "-".$post['form_start_1'];
	$sagyou_date .= "-".$post['form_start_2'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sql_2CODE = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql_2CODE);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$value_2CODE = $result_row['2CODE'];
	}
	foreach($post as $key  =>  $value)
	{
		
		if(strstr($key,'syukka_') != false)
		{
			$key_array = explode('_',$key);
			$value_1CODE = $key_array[1];
			
			if($value != "")
			{
				$insert_nyuusyukka = "INSERT INTO ".$tablename." (1CODE,4CODE,".$colname.") VALUES(";
				$insert_nyuusyukka .= $value_1CODE.",".$value_4CODE.",'".$value."');";
				$result = $con->query($insert_nyuusyukka) or ($judge = true);																	// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$insert_rireki = "INSERT INTO rirekiinfo (1CODE,2CODE,4CODE,IONUM,IOTYPE,CREATEDATE,SAGYOUDATE) VALUES(";
				$insert_rireki .= $value_1CODE.",".$value_2CODE.",".$value_4CODE.",'".
									$value."','".$type."','".$date."','".$sagyou_date."');";
				$result = $con->query($insert_rireki) or ($judge = true);																	// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
			}
		}
	}
}





/************************************************************************************************************
function makeList_radio($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function makeList_radio($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	if($filename == "nenzi_5")
	{
		$columns = "102,202,203";
	}
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit_num = $form_ini[$filename]['limit'];
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置
	$resultcolumns = $form_ini[$filename]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if($filename != 'pjagain_5' && $filename != 'nenzi_5')
		{
			$totalcount = $result_row['COUNT(*)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}
		}
		else if($filename == 'pjagain_5')
		{
			$totalcount = $result_row['COUNT(DISTINCT endpjinfo.PROJECTNUM,endpjinfo.EDABAN,endpjinfo.PJNAME,5CODE)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}

		}
		else
		{
			$totalcount = $result_row['COUNT(DISTINCT(5CODE),PROJECTNUM,EDABAN,PJNAME,CHARGE)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}
		}
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																								// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table border='1' class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
	$list_html .="</tr></thead><tbody>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		if($filename == 'pjagain_5')
		{
			$column_value = $result_row['5CODE'].'#$';
			$form_name = '5CODE,';
			$form_type .= '9,';
/*			$column_value .= $result_row['6CODE'].'#$';
			$form_name .= '6CODE,';
			$form_type .= '9,';
*/		}
		else
		{
			$column_value = $result_row[$tablenum.'CODE'].'#$';
			$form_name = $tablenum.'CODE,';
			$form_type .= '9,';
		}
		for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
		{
			$field_name = $form_ini[$resultcolumns_array[$i]]['column'];
			$format = $form_ini[$resultcolumns_array[$i]]['format'];
			$value = $result_row[$field_name];
			if ($field_name == "PROJECTNUM" )
			{
				$value = substr($result_row[$field_name],0,2)."-".substr($result_row[$field_name],2,4);
			}
			if ($field_name == "EDABAN" )
			{
				$value = substr($result_row[$field_name],0,4)."-".substr($result_row[$field_name],4,2);
			}
			$type = $form_ini[$resultcolumns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 4)
			{
				$class = "class = 'right'";
			}
			else
			{
				$class = "";
			}
			if($i == 3)
			{
				$class = "class = 'right'";
			}
			$row .="<td ".$id." ".$class." ><a class ='body'>"
						.$value."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			$form_value = formvalue_return($columns_array[$i],$value,$type);
			$form_name .= $form_value[0];
			$column_value .= $form_value[1];
			$form_type .=  $form_value[2];
		}
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
		$list_html .= '<input type ="radio" name = "radio" onClick="select_value(\''
						.$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';
		$list_html .= "</td>";
		$list_html .= $row;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='一番最初に戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'left'>";
	$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='一番最後に進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div style='clear:both;'></div>";
	return ($list_html);
}

/************************************************************************************************************
function makeList_check($sql,$post,$tablenum)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/

function makeList_check($sql,$post,$tablenum){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	if($filename == "nenzi_5")
	{
		$columns = "102,202,203";
	}
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit_num = $form_ini[$filename]['limit'];
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit開始位置
	$resultcolumns = $form_ini[$filename]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	$title_name = "";
	$counter = 1;
	$id = "";
	$class = "";
	$field_name = "";
	$totalcount = 0;
	$listcount = 0;
	$result = array();
	$judge = false;
	$column_value = "";
	$form_name = "";
	$row = "";
	$form_value = "";
	$form_type = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if($filename != 'pjagain_5' && $filename != 'nenzi_5')
		{
			$totalcount = $result_row['COUNT(*)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}
		}
		else if($filename == 'pjagain_5')
		{
			$totalcount = $result_row['COUNT(DISTINCT endpjinfo.PROJECTNUM,endpjinfo.EDABAN,endpjinfo.PJNAME,5CODE)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}

		}
		else
		{
			$totalcount = $result_row['COUNT(DISTINCT(5CODE),PROJECTNUM,EDABAN,PJNAME,CHARGE)'];
			$quotient = floor($totalcount / $limit_num);
			$remainder = $totalcount % $limit_num;
			if($remainder != 0)
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num);
			}
			else
			{
				$_SESSION['list']['max'] = ((floor($totalcount / $limit_num)) * $limit_num) - $limit_num;
			}
		}
	}
	$sql[0] = substr($sql[0],0,-1);																						// 最後の';'削除
	$sql[0] .= $limit.";";																								// LIMIT追加
	$result = $con->query($sql[0]) or ($judge = true);																	// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// 検索結果件数取得
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."件中 ".($limitstart)."件～".($limitstart + $listcount)."件 表示中";					// 件数表示作成
	}
	else
	{
		$list_html .= $totalcount."件中 ".($limitstart + 1)."件～".($limitstart + $listcount)."件 表示中";				// 件数表示作成
	}
	$list_html .= "<table border='1' class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>選択</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
    
    //終了日付項目追加
    $list_html .="<th><a class ='head'>終了日付</a></th>";
    
    //社員名、社員別金額、作業時間項目追加
    $syainsql = "SELECT * FROM syaininfo;";         //社員数を求めるSQL
    $syainresult = $con->query($syainsql);																	// クエリ発行
    $syain_rows = $syainresult -> num_rows;
    
    for($i = 0 ; $i < $syain_rows ; $i++)
    {
        $list_html .="<th><a class ='head'>社員名</a></th>";
        $list_html .="<th><a class ='head'>金額</a></th>";
        $list_html .="<th><a class ='head'>時間</a></th>";
    }
    
	$list_html .="</tr></thead><tbody id ='endpjlist'>";
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list_html .="<tr>";
		if(($counter%2) == 1)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$list_html .= "<td ".$id." class = 'center'>";
		if($filename == 'pjagain_5')
		{
			$column_value = $result_row['5CODE'].'#$';
			$form_name = '5CODE,';
			$form_type .= '9,';
/*			$column_value .= $result_row['6CODE'].'#$';
			$form_name .= '6CODE,';
			$form_type .= '9,';
*/		}
		else
		{
			$column_value = $result_row[$tablenum.'CODE'].'#$';
			$form_name = $tablenum.'CODE,';
			$form_type .= '9,';
		}
		for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
		{
			$field_name = $form_ini[$resultcolumns_array[$i]]['column'];
			$format = $form_ini[$resultcolumns_array[$i]]['format'];
			$value = $result_row[$field_name];
			if ($field_name == "PROJECTNUM" )
			{
				$value = substr($result_row[$field_name],0,2)."-".substr($result_row[$field_name],2,4);
			}
			if ($field_name == "EDABAN" )
			{
				$value = substr($result_row[$field_name],0,4)."-".substr($result_row[$field_name],4,2);
			}
			$type = $form_ini[$resultcolumns_array[$i]]['form_type'];
			if($format != 0)
			{
				$value = format_change($format,$value,$type);
			}
			if($format == 4)
			{
				$class = "class = 'right'";
			}
			else
			{
				$class = "";
			}
			if($i == 3)
			{
				$class = "class = 'right'";
			}
			$row .="<td ".$id." ".$class." ><a class ='body'>"
						.$value."</a></td>";
		}
		for($i = 0 ; $i < count($columns_array) ; $i++)
		{
			$field_name = $form_ini[$columns_array[$i]]['column'];
			$format = $form_ini[$columns_array[$i]]['format'];
			$value = $result_row[$field_name];
			$type = $form_ini[$columns_array[$i]]['form_type'];
			$form_value = formvalue_return($columns_array[$i],$value,$type);
			$form_name .= $form_value[0];
			$column_value .= $form_value[1];
			$form_type .=  $form_value[2];
		}
        //終了日付項目作成
        $row .= "<td ".$id." ".$class." ><a class ='body'>"
						.$result_row['5ENDDATE']."</a></td>";
        
        //一覧の社員名、社員別金額、作業時間項目作成
        $syainrow = "";
        $list_num = 0;
        $row_sql = "SELECT *FROM projectditealinfo where 5CODE = ".$result_row["5CODE"].";";
        $row_result = $con->query($row_sql) or ($judge = true);

        while($row_list = $row_result->fetch_array(MYSQLI_ASSOC)){
            //社員名
            $item_sql = "SELECT *FROM syaininfo where 4CODE = ".$row_list["4CODE"].";";
            $item_result = $con->query($item_sql) or ($jadge = true);
            $item = $item_result->fetch_array(MYSQLI_ASSOC);
            $syainrow .="<td ".$id.">".$item["STAFFNAME"]."<a class ='body'></a></td>";

            //社員別金額
            $syainrow .="<td ".$id.">".$row_list["DETALECHARGE"]."<a class ='body'></a></td>";
            	
            //社員ごとの定時時間と残業時間の合計取得
            $item_sql = "SELECT SUM(TEIZITIME),SUM(ZANGYOUTIME) FROM progressinfo WHERE 6CODE = ".$row_list["6CODE"].";";
            $item_result = $con->query($item_sql) or ($judge = true);																		// クエリ発行
            $item_row = $item_result->fetch_array(MYSQLI_ASSOC);
            
            $sagyoutime = $item_row["SUM(TEIZITIME)"] + $item_row["SUM(ZANGYOUTIME)"];
                
            $syainrow .="<td ".$id.">".$sagyoutime."<a class ='body'></a></td>";            
            $list_num++;
        }
        
        if($list_num < $syain_rows)
        {
            for(;$list_num < $syain_rows;$list_num++)
            {
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //社員名
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //社員別金額
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //作業時間
            }
        }
        
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
        
        if($result_row["5PJSTAT"] == "2")
        {
            $list_html .= '済';           
        }
        else
        {
            $list_html .= '<input type ="checkbox" name = "checkbox" value = "'.$result_row["5CODE"].'" onClick="select_checkbox(\''
                .$column_value.'\',\''.$form_name.'\',\''.$form_type.'\')">';           
        }
		$list_html .= "</td>";
		$list_html .= $row;
        $list_html .= $syainrow;
		$list_html .= "</tr>";
		$row ="";
		$column_value = "";
		$form_name = "";
		$form_type = "";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='一番最初に戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='back' value ='戻る' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'left'>";
	$list_html .= "<input type='submit' name ='next' value ='進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='一番最後に進む' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	$list_html .="<div style='clear:both;'></div>";
	return ($list_html);
}

/************************************************************************************************************
function genbaend($post)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function genbaend($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_SQL.php");																							// DB関数呼び出し準備
	require_once("f_mail.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$value_GENBASTATUS = "";
	$value_1CODE = "";
	$value_2CODE = "";
	$value_4CODE = "";
	$key_array = array();
	$nyuusyukka_num = 0;
	$type = 0;
	$colname = "";
	$saimail = "";
	$saicount = 0;
	$genbaname = "";
	$genbacode = "";
	$sizainame = "";
	$sizaiid = "";
	$title = "";
	$add = "";
	$message = "";
	$out_count = 0;
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$result_array =array();
	$sql_GENBASTATUS = "";
	$judge = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sai_judge = true;
	$sql_GENBASTATUS = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql_GENBASTATUS);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$value_GENBASTATUS = $result_row['GENBASTATUS'];
	}
	if($value_GENBASTATUS == 1)
	{
		$saiSql = idSelectSQL($value_4CODE,8,'4CODE');
		$result = $con->query($saiSql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$SAISTATUS = $result_row['SAISTATUS'];
			if($SAISTATUS == 0 )
			{
				$sai_judge = false;
				$out_count++;
			}
		}
		if($sai_judge == true)
		{
//			$saiSql = idSelectSQL($value_4CODE,8,'4CODE');
//			$result = $con->query($saiSql);
//			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//			{
//				$value_1CODE = $result_row['SAISTATUS'];
//				$saitype = $result_row['SAITYPE'];
//				$sai = $result_row['SAINUM'];
//				tyousei($value_1CODE,$saitype,$sai);
//			}
//			genba_change($value_4CODE,2);


			$henkyakuSql = henkyakuSQL($post,1);
			$result = $con->query($henkyakuSql);
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$SAIKB = $result_row['SAIKB'];
				$syukka = $result_row['yotei'];
				$henkyaku = $result_row['henkyaku'];
				$value_1CODE = $result_row['1CODE'];
				$value_2CODE = $result_row['2CODE'];
				$sai = $syukka - $henkyaku;
				if($sai < 0)
				{
					$sai = abs($sai);
					$saitype = 2;
					tyousei($value_1CODE,$saitype,$sai);
				}
				else if($sai > 0)
				{
					$sai = abs($sai);
					$saitype = 1;
					tyousei($value_1CODE,$saitype,$sai);
				}
			}
			genba_change($value_4CODE,2);
			$message = "<a class = 'item'>現場終了処理が完了いたしました。</a>";
		}
		else
		{
			$message = "<a class = 'error'>".$out_count."件の差異処理が完了いていません。<br>差異処理を完了させてもう一度現場終了処理をしてください。</a>";
		}
	}
	else if($value_GENBASTATUS == 0)
	{
		$henkyakuSql = henkyakuSQL($post,0);
		$result = $con->query($henkyakuSql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$SAIKB = $result_row['SAIKB'];
			$syukka = $result_row['yotei'];
			$henkyaku = $result_row['henkyaku'];
			$value_1CODE = $result_row['1CODE'];
			$value_2CODE = $result_row['2CODE'];
			$sai = $syukka - $henkyaku;
			if($sai < 0)
			{
				$sai = abs($sai);
				if($SAIKB == 1)
				{
					$saitype = 2;
					saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$saitype,$sai);
					$sai_judge = false;
					$genbaname =  $result_row['GENBANAME'];
					$genbaid =  $result_row['GENBAID'];
					$sizaiid = $result_row['SIZAIID'];
					$sizainame = $result_row['SIZAINAME'];
					$saicount++;
					$saimail .= $sizainame."(".$sizaiid.") 過剰 ".$sai."個 \r\n";
				}
			}
			else if($sai > 0)
			{
				$sai = abs($sai);
				if($SAIKB == 1)
				{
					$saitype = 1;
					saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$saitype,$sai);
					$sai_judge = false;
					$genbaname =  $result_row['GENBANAME'];
					$genbaid =  $result_row['GENBAID'];
					$sizaiid = $result_row['SIZAIID'];
					$sizainame = $result_row['SIZAINAME'];
					$saicount++;
					$saimail .= $sizainame."(".$sizaiid.") 不足 ".$sai."個 \r\n";
				}
			}
		}
		if($sai_judge == false)
		{
			genba_change($value_4CODE,1);
			if($saimail != "")
			{
				$saimail = $genbaname."(".$genbaid.") にて差異が".$saicount."件 見つかりました。\r\n".$saimail;
				$saimail = rtrim($saimail,'\r\n');
				$title = $mail_ini['sai']['title'];
				$add = $mail_ini['sai']['send_add'];
				sendmail($add,$title,$saimail);
			}
			$message = "<a class = 'error'>".$saicount."件の差異処理がありました。<br>差異処理を完了させてもう一度現場終了処理をしてください。</a>";
		}
		else
		{
			
			$henkyakuSql = henkyakuSQL($post,0);
			$result = $con->query($henkyakuSql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$SAIKB = $result_row['SAIKB'];
				$syukka = $result_row['yotei'];
				$henkyaku = $result_row['henkyaku'];
				$value_1CODE = $result_row['1CODE'];
				$value_2CODE = $result_row['2CODE'];
				$sai = $syukka - $henkyaku;
				if($sai < 0)
				{
					$sai = abs($sai);
					$saitype = 2;
					tyousei($value_1CODE,$saitype,$sai);
				}
				else if($sai > 0)
				{
					$sai = abs($sai);
					$saitype = 1;
					tyousei($value_1CODE,$saitype,$sai);
				}
			}
			
			genba_change($value_4CODE,2);
			$message = "<a class = 'item'>現場終了処理が完了いたしました。</a>";
		}
	}
	return($message);
}

/************************************************************************************************************
function tyousei($value_1CODE,$SAITYPE,$SAINUM)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function tyousei($value_1CODE,$SAITYPE,$SAINUM){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sainum = ' + 0' ;
	if($SAITYPE == 2)
	{
		$sainum = ' + '.$SAINUM;
	}
	else if($SAITYPE == 1)
	{
		$sainum = ' - '.$SAINUM;
	}
	$sql = "UPDATE zaikoinfo SET ZAIKONUM = (ZAIKONUM ".$sainum.") WHERE 1CODE = ".$value_1CODE." ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}


/************************************************************************************************************
function saiinsert($value_1CODE,$SAITYPE,$SAINUM)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$SAITYPE,$SAINUM){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sql = "INSERT INTO saiinfo (4CODE,2CODE,1CODE,SAITYPE,SAICREATEDATE,SAISTATUS,SAINUM ) VALUES (";
	$sql .= $value_4CODE.",".$value_2CODE.",".$value_1CODE.",'".$SAITYPE."','".$SAICREATEDATE."','0','".$SAINUM."' ) ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}

/************************************************************************************************************
function genba_change($value_4CODE,$GENBASTATUS)

引数1		$sql						検索SQL
引数2		$post						ページ移動時post
引数3		$tablenum					表示テーブル番号

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function genba_change($value_4CODE,$GENBASTATUS){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sainum = ' + 0' ;
	$sql = "UPDATE genbainfo SET GENBASTATUS =  ".$GENBASTATUS;
	if($GENBASTATUS == 2)
	{
		$sql .= " , ENDDATE = '".$date."' ";
	}
	$sql .= " WHERE 4CODE = ".$value_4CODE." ;";
	$result = $con->query($sql);
	if($GENBASTATUS == 2)
	{
		$sql = "DELETE FROM syukkainfo WHERE 4CODE = ".$value_4CODE." ;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		$sql = "DELETE FROM henkyakuinfo WHERE 4CODE = ".$value_4CODE." ;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	
}

/************************************************************************************************************
入出荷履歴削除処理(資材管理システム)
function deleterireki()

引数1		$sql						検索SQL

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function deleterireki(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_sub($date, date_interval_create_from_date_string('5 year'));
	$DATE = date_format($date, "Y-m-d");
//	$DATETIME = date_format($date, 'Y-m-d H:i:s');
	$DATETIME = $DATE." 00:00:00";
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$sql = "DELETE FROM pjinfo WHERE 5ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM projectditealinfo WHERE 6ENDDATE < '".$DATETIME."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM progressinfo WHERE 7ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
		$sql = "DELETE FROM endpjinfo WHERE 8ENDDATE < '".$DATETIME."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM monthdatainfo WHERE 9ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	deletedate_change();
}

/************************************************************************************************************
PJ情報取得処理(プロジェクト管理システム)
function getPJdata(id)

引数1		$id							検索対象プライマリキー

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function getPJdata($id){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "SELECT * FROM projectinfo LEFT JOIN  projectnuminfo USING(1CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE 5CODE = ".$id." ;";
	$judge = false;
	$count = 1;
	$result ;
	$result_row = array();
	$result_array = array();
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$result_array = $result_row;
	}
	$form = "<table><tr><td>プロジェクトコード</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PROJECTNUM']."'>";
	$form .= "</td></tr><tr><td>枝番</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['EDABAN']."'>";
	$form .= "</td></tr><tr><td>製番・案件名</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PJNAME']."'>";
	$form .= "</td></tr><tr><td>金額</td><td>";
	$form .= "<input type = 'text' id ='PJCharge' class = 'readOnly' size = 40 readonly value = '".$result_array['CHARGE']."'>";
	$form .= "</td></tr></table>";
	return ($form);
	
}

/************************************************************************************************************
PJ終了処理(プロジェクト管理システム)
function pjend($post)

引数1		$post						削除対象

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function pjend($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
        
        if(isset($_SESSION['seizyou5code']))
        {
            $pjid = $_SESSION['seizyou5code'];
            unset($_SESSION['seizyou5code']);
        }
        else
        {
            $pjid = explode(",",$post['5CODE']);
        }
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = 0;
	$zangyou = 0;
	$charge = 0;
	$period = 0;
	$upcode6 = "";
	$errorcnt = 0;
	$syaincnt = 0;
	$error = array();
	$syainArray = array();
	$checkflg = false;

	//------------------------//
	//      定時チェック      //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	for($o=0; $o < count($pjid); $o++)
        {
            //プロジェクトの開始日と終了日取得
            $sql = "SELECT MIN(SAGYOUDATE),MAX(SAGYOUDATE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                    ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                    ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$o]." order by SAGYOUDATE ;";

            $result = $con->query($sql) or ($judge = true);																		// クエリ発行
            $result_row = $result->fetch_array(MYSQLI_ASSOC);
            $start = $result_row['MIN(SAGYOUDATE)'];
            $end =  $result_row['MAX(SAGYOUDATE)'];

            //プロジェクトの作業社員取得
            $sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                    ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                    ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$o]." order by 4CODE ;";
            $result = $con->query($sql) or ($judge = true);																		// クエリ発行
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $syainArray[$syaincnt] = $result_row['4CODE'];
                $syaincnt++;
            }

            //社員ごとに定時チェック
            for($s = 0; $s < count($syainArray); $s++)
            {
                //社員が変わるごとにbeforeとteiziを初期化
                $before = "";
                $teizi = 0;

                $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                        ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                        ."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '".$start."' AND '".$end."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";

                $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $after = $result_row['SAGYOUDATE'];
                    if(!empty($before))
                    {
                        if($before == $after)
                        {

                            $teizi += $result_row['TEIZITIME'];
                            if($teizi > $teijitime)
                            {
                                $checkflg = true;
                                //定時エラー//
                                $errrecname = $result_row['STAFFNAME'];
                                $errrecdate = $result_row['SAGYOUDATE'];
                                $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                $error[$errorcnt]['KOUTEINAME'] = "";
                                $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                                $errorcnt++;
                            }
                        }
                        else
                        {
                            //日付が変わるごとにteiziを初期化
                            $teizi = 0;
                            $teizi += $result_row['TEIZITIME'];
                            if($teizi > $teijitime)
                            {
                                $checkflg = true;
                                //定時エラー//
                                $errrecname = $result_row['STAFFNAME'];
                                $errrecdate = $result_row['SAGYOUDATE'];
                                $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                $error[$errorcnt]['KOUTEINAME'] = "";
                                $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                                $errorcnt++;
                            }
                        }
                    }
                    else
                    {
                        $teizi += $result_row['TEIZITIME'];
                        if($teizi > $teijitime)
                        {
                            $checkflg = true;
                            //定時エラー//
                            $errrecname = $result_row['STAFFNAME'];
                            $errrecdate = $result_row['SAGYOUDATE'];
                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                            $error[$errorcnt]['KOUTEINAME'] = "";
                            $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                            $errorcnt++;
                        }
                    }
                    $before = $result_row['SAGYOUDATE'];
                }
            }

            //$_SESSION['error'];
            //------------------------//
            //      終了登録処理      //
            //------------------------//

            if(!$checkflg)
            {
                //該当プロジェクト($pjid)を選択
                $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                        ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                        ."LEFT JOIN kouteiinfo USING(3CODE) WHERE projectditealinfo.5CODE = ".$pjid[$o]." order by SAGYOUDATE ;";
                $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    //社員別プロジェクトコード(6CODE)ごとに多次元配列に格納
                    if(isset($time[$result_row['6CODE']]))
                    {
                        $time[$result_row['6CODE']][count($time[$result_row['6CODE']])] = $result_row;
                    }
                    else
                    {
                        $time[$result_row['6CODE']][0] = $result_row;
                    }
                }

                $keyarray = array_keys($time);
                foreach($keyarray as $key)
                {
                    //$key(=6CODE)が変わるごとに初期化
                    $teizi = 0;
                    $zangyou = 0;
                    unset($before);
                    //実績時間計算
                    for($i = 0 ; $i < count($time[$key]) ; $i++)
                    {
                        $teizi += $time[$key][$i]['TEIZITIME'];
                        $zangyou += $time[$key][$i]['ZANGYOUTIME'];
                    }
                    //終了PJ登録
                    $pjnum = $time[$key][0]['PROJECTNUM'];
                    $pjeda = $time[$key][0]['EDABAN'];
                    $pjname = $time[$key][0]['PJNAME'];
                    $charge = $time[$key][0]['DETALECHARGE'];
                    $total = $teizi + $zangyou;
                    $performance = round($charge/$total,3);
                    $sql_end = "INSERT INTO endpjinfo (6CODE,TEIJITIME,ZANGYOTIME,TOTALTIME,PERFORMANCE,8ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES "
                                ."(".$key.",".$teizi.",".$zangyou.",".$total.",".$performance.","."'".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".") ;";
                    $result = $con->query($sql_end) or ($judge = true);																		// クエリ発行
                    if($judge)
                    {
                        error_log($con->error,0);
                        $judge = false;
                    }
                    if(!empty($upcode6))
                    {
                        $upcode6 .= $key.",";
                    }
                    else
                    {
                        $upcode6 = $key.",";
                    }
                }
                //フラグを終了PJ(STAT=2)に更新
                $sql_update = "UPDATE projectinfo SET  5ENDDATE = '".$nowdate."' , 5PJSTAT = '2' WHERE 5CODE = ".$pjid[$o]." ;";
                $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }

                $upcode6 = substr($upcode6, 0, -1);
                $sql_update = "UPDATE projectditealinfo SET 6ENDDATE = '".$nowdate."' , 6PJSTAT = '2' WHERE 6CODE IN (".$upcode6.");";
                $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                $sql_update = "UPDATE progressinfo SET 7ENDDATE = '".$nowdate."' , 7PJSTAT = '2' WHERE 6CODE IN (".$upcode6.");";
                $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
            }
            if(!$checkflg)
            {
                $message = '完了';
            }
            else
            {
                $message = 'エラー';
            }
        }
	return($message);
}

/************************************************************************************************************
月次処理(プロジェクト管理システム)
function getuji($month,$period,$kubun)

引数1		$month						処理対象月
引数2		$period 					期
引数3		$kubun						0:通常処理	1:年次処理

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function getuji($month,$period){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$endjudge = false;
	$checkflg = false;
	$insertArray = array();
	$syaincnt = 0;
	$syainArray = array();
	$time = array();
	$cnt = 0;
	$syaincnt = 0;
	$errorcnt = 0;
	$year = getyear($month,$period);
	$lastday = getlastday($month,$year);
	$Month = str_pad($month, 2, "0", STR_PAD_LEFT);

	//------------------------//
	//        検索処理        //
	//------------------------//
	
	//月次済判定
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM endmonthinfo WHERE PERIOD = ".$period." AND MONTH = ".$month.";";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows > 0)
	{
		$endjudge = true;
	}
	if(!$endjudge)
	{
		//指定期間内に登録されている社員コード取得
		$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$syainArray[$syaincnt] = $result_row['4CODE'];
			$syaincnt++;
		}
		
		//定時チェック
		for($s = 0; $s < count($syainArray); $s++)
		{
			//初期化
			$before = "";
			$teizi = 0;
			$zangyou = 0;
			
			//社員コードと日付を条件に作業日順で選択
			$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
				$checkflg = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$after = $result_row['SAGYOUDATE'];
				if(isset($before))
				{
					if($before == $after)
					{
						
						$teizi += $result_row['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
						}
					}
					else
					{
						//日付が変わるごとにteiziを初期化
						$teizi = 0;
						$teizi += $result_row['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
						}
					}
				}
				else
				{
					$teizi += $result_row['TEIZITIME'];
					if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
						}
				}
				$before = $result_row['SAGYOUDATE'];
			}
		}
		
		//実績計算
		if(!$checkflg)
		{
			//指定期間中のレコードを作業日順に選択
			$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$Month."-1' AND '".$year."-".$Month."-".$lastday."' ORDER BY SAGYOUDATE;";
			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
				$checkflg = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				//社員別プロジェクトコード(6CODE)ごとに多配列登録
				if(isset($time[$result_row['6CODE']]))
				{
					$time[$result_row['6CODE']][count($time[$result_row['6CODE']])] = $result_row;
				}
				else
				{
					$time[$result_row['6CODE']][0] = $result_row;
				}
			}
			$keyarray = array_keys($time);
//			$checkflg = false;
//			$delcheckflg = false;
			foreach($keyarray as $key)
			{
				//初期化
				$teizi = 0;
				$zangyou = 0;
//				$teizicheck = 0;
				unset($before);
//				$checkkouteiarray = array();
				
				//登録データ格納
				$insertArray[$cnt]['4CODE'] = $time[$key][0]['4CODE'];
				$insertArray[$cnt]['5CODE'] = $time[$key][0]['5CODE'];
				$insertArray[$cnt]['PROJECTNUM'] = $time[$key][0]['PROJECTNUM'];
				$insertArray[$cnt]['EDABAN'] = $time[$key][0]['EDABAN'];
				$insertArray[$cnt]['PJNAME'] = $time[$key][0]['PJNAME'];
				//社員別プロジェクトコードごとに実績計算
				for($i = 0 ; $i < count($time[$key]) ; $i++)
				{
					
/*					$after = $time[$key][$i]['SAGYOUDATE'];
					if(isset($before))
					{
						if($before == $after)
						{
							$teizicheck += $time[$key][$i]['TEIZITIME'];
							if($teizicheck > $teijitime)
							{
								$checkflg = true;
								//定時エラー//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
							}
							if(array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray) !== FALSE)
							{
								$checkflg = true;
								//同一レコードエラー//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = $time[$key][$i]['KOUTEINAME'];
								$error[$errorcnt]['GENIN'] = "同一工程のレコードが存在します。";
								$checkstack = array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray);
								$checkkouteiarray[$checkstack] = '';
							}
						}
						else
						{
							$teizicheck = 0;
							$teizicheck += $time[$key][$i]['TEIZITIME'];
							if($teizicheck > $teijitime)
							{
								$checkflg = true;
								//定時エラー//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
							}
							$checkkouteiarray = array();
						}
					}
					else
					{
						$teizicheck += $time[$key][$i]['TEIZITIME'];
						if($teizicheck > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
						}
					}
*/					//一ヵ月分の実績データ計算
					$teizi += $time[$key][$i]['TEIZITIME'];
					$zangyou += $time[$key][$i]['ZANGYOUTIME'];
					$before = $time[$key][$i]['SAGYOUDATE'];
//					$checkkouteiarray[] = $time[$key][$i]['KOUTEINAME'];
				}
				//一ヵ月分の実績データ作成
				$insertArray[$cnt]['TEIZI'] = $teizi;
				$insertArray[$cnt]['ZANGYOU'] = $zangyou;
				$cnt++;
			}
		}
		//月間実績登録
		if(!$checkflg)
		{
			for($i = 0; $i < count($insertArray); $i++)
			{
				$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
							." (".$insertArray[$i]['4CODE'].",".$insertArray[$i]['5CODE'].",'".$period."','".$month."','定時時間','".$insertArray[$i]['TEIZI']."'"
							.",NOW(),"."'".$insertArray[$i]['PROJECTNUM']."'".","."'".$insertArray[$i]['EDABAN']."'".","."'".$insertArray[$i]['PJNAME']."'".");";
				$result = $con->query($sql_month) or ($judge = true);																		// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
							." (".$insertArray[$i]['4CODE'].",".$insertArray[$i]['5CODE'].",'".$period."','".$month."','残業時間','".$insertArray[$i]['ZANGYOU']."'"
							.",NOW(),"."'".$insertArray[$i]['PROJECTNUM']."'".","."'".$insertArray[$i]['EDABAN']."'".","."'".$insertArray[$i]['PJNAME']."'".");";
				$result = $con->query($sql_month) or ($judge = true);																		// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
			}
		}
	}
	else
	{
		$checkflg = true;
		$message = '既に処理済の期月です。';
	}
	if(!$checkflg)
	{
		//月次済期間登録
		$year = getyear($month,$period);
		$sql = "INSERT INTO endmonthinfo (PERIOD,YEAR,MONTH) VALUE ('".$period."','".$year."','".$month."');";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		deletedate_change();
		return('月次完了');
	}
	else
	{
		if(!empty($error))
		{
			$_SESSION['error'] = $error;
			$message = '月次処理にてエラーが発生しました。';
		}
		return($message);
	}
}

/************************************************************************************************************
年→期変換処理(プロジェクト管理システム)
function getperiod($month,$year)

引数1		$month						月
引数2		$year 						年

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function getperiod($month,$year){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$startyear = $item_ini['period']['startyear'];
	$startmonth = $item_ini['period']['startmonth'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$period = 0 ;
	
	
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$period = $year - $startyear + 1;
	if($startmonth > $month)
	{
		$period = $period - 1 ;
	}
	
	return $period;
	
}
/************************************************************************************************************
期→年変換処理(プロジェクト管理システム)
function getyear($month,$period)

引数1		$month						月
引数2		$period 					期

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function getyear($month,$period){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$startyear = $item_ini['period']['startyear'];
	$startmonth = $item_ini['period']['startmonth'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$year = 0 ;
	
	
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$year = $period + $startyear - 1;
	if($startmonth > $month)
	{
		$year = $year + 1 ;
	}
	
	return $year;
	
}

/************************************************************************************************************
月末日取得処理(プロジェクト管理システム)
function getlastday($month,$year)

引数1		$month						月

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function getlastday($month,$year){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$day = 0 ;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
	{
		$day = 31;
	}
	else if($month == 2)
	{
		$day = 28;
		if($month%4 == 0)
		{
			$day = 29;
		}
	}
	else
	{
		$day = 30;
	}
	
	return $day;
	
}
/************************************************************************************************************
期またぎ処理(プロジェクト管理システム)
function kimatagi($post)

引数1		$post						ポスト内容

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function kimatagi($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	
	
	
}

/************************************************************************************************************
入出荷履歴削除処理(資材管理システム)
function deletepjall()

引数1		$sql						検索SQL

戻り値		$list_html					モーダルに表示リストhtml
************************************************************************************************************/
function deletepjall($delkey){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "";
	$ssql = "";
	$sresult = "";
/*	$sql = "DELETE FROM projectinfo WHERE 5CODE = '".$delkey."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
*/
	$ssql = "SELECT DISTINCT(7CODE) FROM progressinfo WHERE 6CODE IN (SELECT 6CODE FROM projectditealinfo WHERE 5CODE = '".$delkey."' ) ;";
	error_log($ssql,0);
	$sresult = $con->query($ssql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $sresult->fetch_array(MYSQLI_ASSOC))
	{

		$sql = "DELETE FROM progressinfo WHERE 7CODE = '".$result_row['7CODE']."' ;";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	
	$sql = "DELETE FROM projectditealinfo WHERE 5CODE = '".$delkey."' ;";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}
/************************************************************************************************************
終了PJキャンセル処理(プロジェクト管理システム)
function pjagain($post)

引数1		$post						対象

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function pjagain($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$pjid = $post['5CODE'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	$period = 0;
	$code5 = 0;
	$code6 = 0;
	$code8 = 0;
//	$sql6 = "";
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();	
//	$sql = "SELECT * FROM endpjinfo;";
	$sql = "SELECT * FROM endpjinfo LEFT JOIN projectditealinfo USING (6CODE) LEFT JOIN syaininfo USING (4CODE)"
			." RIGHT JOIN projectinfo USING (5CODE) LEFT JOIN progressinfo USING (6CODE) LEFT JOIN projectnuminfo USING (1CODE) LEFT JOIN edabaninfo USING (2CODE)"
			." WHERE projectinfo.5CODE = ".$pjid.";";			// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if($code8 != $result_row['8CODE'])
		{
			$code8 = $result_row['8CODE'];
			//endpjinfoから削除
			$sql_delete =  "DELETE FROM endpjinfo WHERE 8CODE = ".$code8." ;";
			$result_delete = $con->query($sql_delete) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}
		if($code5 != $result_row['5CODE'])
		{
			$code5 = $result_row['5CODE'];
			//フラグを1（未処理）に変更
			$sql5 = "UPDATE projectinfo SET  5ENDDATE = NULL , 5PJSTAT = '1' WHERE 5CODE = ".$code5.";";
			$result5 = $con->query($sql5) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}

		if($code6 != $result_row['6CODE'])
		{
			$code6 = $result_row['6CODE'];
			//フラグを1（未処理）に変更
			$sql6 = "UPDATE projectditealinfo SET  6ENDDATE = NULL , 6PJSTAT = '1' WHERE 6CODE = ".$code6.";";
			$result6 = $con->query($sql6) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			$sql7 = "UPDATE progressinfo SET  7ENDDATE = NULL , 7PJSTAT = '1' WHERE 6CODE = ".$code6.";";
			$result7 = $con->query($sql7) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}
	}
	return("終了PJキャンセル処理が完了しました。");
}
/************************************************************************************************************
年次処理(プロジェクト管理システム)
function nenji($period)

引数1		$month						処理対象月
引数2		$period 					期
引数3		$kubun						0:通常処理	1:年次処理

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function nenji($period){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;

	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();
	$sql = "INSERT INTO endperiodinfo (PERIOD) VALUE ('".$period."');";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	deletedate_change();

}

/************************************************************************************************************
年次処理(プロジェクト管理システム)
function nenjiCheck($period)

引数1		$month						処理対象月
引数2		$period 					期
引数3		$kubun						0:通常処理	1:年次処理

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function nenjiCheck($period){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$teijitime = (float)$item_ini['settime']['teijitime'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$monthjudge = false;
	$error_month = "";
	$error_pj = array();
	$endmonth = array();
	$Month = "6,7,8,9,10,11,12,1,2,3,4,5";
	$arrayMonth = explode(',',$Month);
	$checkflgmessage = '';
	$count = 0;

	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();	
	
	//年次チェック
	$sql = "SELECT * FROM endperiodinfo WHERE PERIOD = '".$period."';";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows == 0)
	{
		//月次チェック
		$sql = "SELECT * FROM endmonthinfo WHERE PERIOD = '".$period."';";
		$result = $con->query($sql);
		$rows = $result->num_rows;
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$endmonth[$count] = $result_row['MONTH'];
			$count++;
		}
		for($i = 0; $i < 12; $i++)
		{
			for($j = 0; $j < count($endmonth); $j++)
			{
				if($arrayMonth[$i] == $endmonth[$j])
				{
					$monthjudge = true;
				}
			}
			if(!$monthjudge)
			{
				//月次を行っていない月を集計
				$error_month .= $arrayMonth[$i].',';
			}
			$monthjudge = false;
		}
		$_SESSION['errormonth'] = rtrim($error_month,',');
		$count = 0;
		//PJチェック
		$start_year = getyear('6',$period);
		$end_year = $start_year + 1;
		$sql = "SELECT DISTINCT(EDABAN),PROJECTNUM,PJNAME FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
			."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) LEFT JOIN kouteiinfo USING(3CODE) WHERE projectinfo.5PJSTAT = 1 AND "
			."progressinfo.SAGYOUDATE BETWEEN '".$start_year."-06-01' AND '".$end_year."-05-31' order by PROJECTNUM,EDABAN ;";
		$result = $con->query($sql);
		$rows = $result->num_rows;
		if($rows > 0)
		{
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$error_pj[$count]['PROJECTNUM'] = $result_row['PROJECTNUM'];
				$error_pj[$count]['EDABAN'] = $result_row['EDABAN'];
				$error_pj[$count]['PJNAME'] = $result_row['PJNAME'];
				$count++;
			}
		}
		return ($error_pj);
	}
	else
	{
		$_SESSION['nenzi']['error'] = $period."期は既に年次処理済です。";
	}
}

/************************************************************************************************************
PJ終了処理(プロジェクト管理システム)
function pjCheck($post)

引数1		$post						削除対象

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function pjCheck($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	if($filename == 'getuzi_5')
	{
//		$nowdate = date_create("NOW");
		$period = $post['period'];
		$month = $post['month'];
		$year = getyear($month,$period);
		$lastday = getlastday($month,$year);
		$Month = str_pad($month, 2, "0", STR_PAD_LEFT);
	}
	else if($filename == 'pjend_5')
	{
		$pjid = explode(",",$post["5CODE"]);					//プロジェクト終了
	}
	else
	{
		$pjid = explode(",",$post);							//プロジェクト削除
	}
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = 0;
	$error = array();
	$errorcnt = 0;
	$syaincnt = 0;
	$syainArray = array();
	$checkflg = false;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();
		//該当プロジェクト($pjid)を選択
	if($filename == 'getuzi_5')
	{
		//操作月以降は月次禁止
//		$now = $nowyr'-'$nowmn;
//		$pos = $year."-".$month;
//		if()
		if(!$checkflg)
		{
			//指定期間内に登録されている社員コード取得
			$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$month."-01' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$syainArray[$syaincnt] = $result_row['4CODE'];
				$syaincnt++;
			}
			//社員ごとに定時チェック
			for($s = 0; $s < count($syainArray); $s++)
			{
				//社員が変わるごとにbeforeとteiziを初期化
				$before = "";
				$teizi = 0;
				
				//社員コードと日付を条件に作業日順で選択
				$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
						."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
						."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
						.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
				$result = $con->query($sql) or ($judge = true);																		// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
					$checkflg = true;
				}
				while($result_row = $result->fetch_array(MYSQLI_ASSOC))
				{
					$after = $result_row['SAGYOUDATE'];
					if(!empty($before))
					{
						if($before == $after)
						{
							
							$teizi += $result_row['TEIZITIME'];
							if($teizi > $teijitime)
							{
								$checkflg = true;
								//定時エラー//
								$errrecname = $result_row['STAFFNAME'];
								$errrecdate = $result_row['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
								$errorcnt++;
							}
						}
						else
						{
							//日付が変わるごとにteiziを初期化
							$teizi = 0;
							$teizi += $result_row['TEIZITIME'];
							if($teizi > $teijitime)
							{
								$checkflg = true;
								//定時エラー//
								$errrecname = $result_row['STAFFNAME'];
								$errrecdate = $result_row['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
								$errorcnt++;
							}
						}
					}
					else
					{
						$teizi += $result_row['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
							$errorcnt++;
						}
					}
					$before = $result_row['SAGYOUDATE'];
				}
			}
		}
	}
	else
	{
		for($i = 0; $i < count($pjid); $i++)
                {
                    //進捗情報の有無
                    $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                            ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                            ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                            ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by SAGYOUDATE ;";
                    $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                    if($judge)
                    {
                        error_log($con->error,0);
                        $judge = false;
                    }
                    //進捗情報の有無を確認
                    $rows = $result->num_rows;
                    if($rows > 0)
                    {
                        //プロジェクトの開始日と終了日取得
                        $sql = "SELECT MIN(SAGYOUDATE),MAX(SAGYOUDATE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                                ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                                ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by SAGYOUDATE ;";
                        $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                        if($judge)
                        {
                            error_log($con->error,0);
                            $judge = false;
                        }
                        $result_row = $result->fetch_array(MYSQLI_ASSOC);
                        $start = $result_row['MIN(SAGYOUDATE)'];
                        $end =  $result_row['MAX(SAGYOUDATE)'];

                        //プロジェクトの作業社員取得
                        $sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                                ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                                ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by 4CODE ;";

                        $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                        if($judge)
                        {
                            error_log($con->error,0);
                            $judge = false;
                        }
                        while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $syainArray[$syaincnt] = $result_row['4CODE'];
                            $syaincnt++;
                        }

                        //社員ごとに定時チェック
                        for($s = 0; $s < count($syainArray); $s++)
                        {
                            //社員が変わるごとにbeforeとteiziを初期化
                            $before = "";
                            $teizi = 0;

                            $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                                    ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '".$start."' AND '".$end."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";

                            $result = $con->query($sql) or ($judge = true);																		// クエリ発行
                            if($judge)
                            {
                                error_log($con->error,0);
                                $judge = false;
                            }
                            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                            {
                                $after = $result_row['SAGYOUDATE'];
                                if(!empty($before))
                                {
                                    if($before == $after)
                                    {

                                        $teizi += $result_row['TEIZITIME'];
                                        if($teizi > $teijitime)
                                        {
                                            $checkflg = true;
                                            //定時エラー//
                                            $errrecname = $result_row['STAFFNAME'];
                                            $errrecdate = $result_row['SAGYOUDATE'];
                                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                            $error[$errorcnt]['KOUTEINAME'] = "";
                                            $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                                            $errorcnt++;
                                        }
                                    }
                                    else
                                    {
                                        //日付が変わるごとにteiziを初期化
                                        $teizi = 0;
                                        $teizi += $result_row['TEIZITIME'];
                                        if($teizi > $teijitime)
                                        {
                                            $checkflg = true;
                                            //定時エラー//
                                            $errrecname = $result_row['STAFFNAME'];
                                            $errrecdate = $result_row['SAGYOUDATE'];
                                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                            $error[$errorcnt]['KOUTEINAME'] = "";
                                            $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                                            $errorcnt++;
                                        }
                                    }
                                }
                                else
                                {
                                    $teizi += $result_row['TEIZITIME'];
                                    if($teizi > $teijitime)
                                    {
                                        $checkflg = true;
                                        //定時エラー//
                                        $errrecname = $result_row['STAFFNAME'];
                                        $errrecdate = $result_row['SAGYOUDATE'];
                                        $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                        $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                        $error[$errorcnt]['KOUTEINAME'] = "";
                                        $error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
                                        $errorcnt++;
                                    }
                                }
                                $before = $result_row['SAGYOUDATE'];
                            }
                        }
                        $_SESSION['seizyou5code'][] = $pjid[$i];
                    }
                    else
                    {
                        $pjcode = explode(",",$_SESSION['list']['pjcode']);
                        $edabancode = explode(",",$_SESSION['list']['edabancode']);
                        $pjname = explode(",",$_SESSION['list']['pjname']);

                        $_SESSION['pjcode'][] = $pjcode[$i];
                        $_SESSION['edabancode'][] = $edabancode[$i];
                        $_SESSION['pjname'][] = $pjname[$i];
                        $_SESSION['message'][] = "<a class = 'error'>進捗情報が登録されていません。</a>";
                    }
                }
	}

/*
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE projectditealinfo.5CODE = ".$pjid." order by SAGYOUDATE ;";
			$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			//社員別プロジェクトコード(6CODE)ごとに多次元配列に格納
			if(isset($time[$result_row['6CODE']]))
			{
				$time[$result_row['6CODE']][count($time[$result_row['6CODE']])] = $result_row;
			}
			else
			{
				$time[$result_row['6CODE']][0] = $result_row;
			}
		}
		$keyarray = array_keys($time);
		$checkflg = false;
		foreach($keyarray as $key)
		{
			//6CODE(社員別プロジェクトコード)ごとに初期化
			$teizi = 0;
			unset($before);
			$checkkouteiarray = array();
			//社員別プロジェクトコードごとにエラーチェック
			for($i = 0 ; $i < count($time[$key]) ; $i++)
			{
				$after = $time[$key][$i]['SAGYOUDATE'];
				if(isset($before))
				{
					if($before == $after)
					{
						$teizi += $time[$key][$i]['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
							$errorcnt++;
						}
						if(array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray) !== FALSE)
						{
							$checkflg = true;
							//同一レコードエラー//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$checkstack = array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray);
							$checkkouteiarray[$checkstack] = '';
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = $time[$key][$i]['KOUTEINAME'];
							$error[$errorcnt]['GENIN'] = "同一工程のレコードが存在します。";
							$errorcnt++;
						}
					}
					else
					{
						$teizi = 0;
						$teizi += $time[$key][$i]['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//定時エラー//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
							$errorcnt++;
						}
						$checkkouteiarray = array();
					}
				}
				else
				{
					$teizi += $time[$key][$i]['TEIZITIME'];
					if($teizi > $teijitime)
					{
						$checkflg = true;
						//定時エラー//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$error[$errorcnt]['STAFFNAME'] = $errrecname;
						$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
						$error[$errorcnt]['KOUTEINAME'] = "";
						$error[$errorcnt]['GENIN'] = "規定の定時時間を越えています。";
						$errorcnt++;
					}
					
				}
				$before = $time[$key][$i]['SAGYOUDATE'];
			}
		}

		if($filename != 'getuzi_5' && !$checkflg)
		{

		}
		if($filename == 'getuzi_5' && !$checkflg)
		{
		}
	}
*/
	return($error);
}
/************************************************************************************************************
PJ終了処理(プロジェクト管理システム)
function makeList_error($post)

引数1		$post						削除対象

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function makeList_error($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	if($filename == 'pjend_5' || $filename == 'getuzi_5')
	{
		$list_html .= "<table class ='list'><thead><tr>";
		$list_html .="<th><a class ='head'>No</a></th>";
		$list_html .="<th><a class ='head'>日付</a></th>";
		$list_html .="<th><a class ='head'>作業者</a></th>";
		$list_html .="<th><a class ='head'>工程</a></th>";
		$list_html .="<th><a class ='head'>原因</a></th>";
		$list_html .="</tr><thead><tbody>";

		for($i = 0; $i < count($post); $i++)
		{
			$list_html .="<tr>";
			if(($i%2) == 0)
			{
				$id = "";
			}
			else
			{
				$id = "id = 'stripe'";
			}
			
			$list_html .="<td ".$id." class = 'center'><a class='body'>".($i + 1)."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['SAGYOUDATE']."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['STAFFNAME']."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['KOUTEINAME']."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['GENIN']."</a></td></tr>";
		}
		$list_html .="</tbody></table>";
	}
	else
	{
		$list_html .= "<table class ='list'><thead><tr>";
		$list_html .="<th><a class ='head'>No</a></th>";
		$list_html .="<th><a class ='head'>プロジェクトコード</a></th>";
		$list_html .="<th><a class ='head'>枝番コード</a></th>";
		$list_html .="<th><a class ='head'>製番・案件名</a></th>";
		$list_html .="</tr><thead><tbody>";

		for($i = 0; $i < count($post); $i++)
		{
			$list_html .="<tr>";
			if(($i%2) == 0)
			{
				$id = "";
			}
			else
			{
				$id = "id = 'stripe'";
			}
			
			$list_html .="<td ".$id." class = 'center'><a class='body'>".($i + 1)."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['PROJECTNUM']."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['EDABAN']."</a></td>";
			$list_html .="<td ".$id." ><a class ='body'>".$post[$i]['PJNAME']."</a></td>";
		}
		$list_html .="</tbody></table>";
	}
	return($list_html);
}
/************************************************************************************************************
PJ終了処理(プロジェクト管理システム)
function make_pjdel($post)

引数1		$post						削除対象

戻り値		$form						モーダルに表示リストhtml
************************************************************************************************************/
function make_pjdel($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	
	//------------------------//
	//          変数          //
	//------------------------//
	$list_html = "";
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT PROJECTNUM,EDABAN,PJNAME FROM projectinfo LEFT JOIN  projectnuminfo USING(1CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE 5CODE = '".$post."';";
	$result = $con->query($sql);																		// クエリ発行
	$result_row = $result->fetch_array(MYSQLI_ASSOC);
	$pjnum = $result_row['PROJECTNUM'];
	$edaban = $result_row['EDABAN'];
	$pjname = $result_row['PJNAME'];

	$list_html .= "<table><tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>プロジェクトコード</a></td><td class = 'two'><a class = 'comp' >".$pjnum."</a></td></tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>枝番コード</a></td><td class = 'two'><a class = 'comp' >".$edaban."</a></td></tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>製番・案件名</a></td><td class = 'two'><a class = 'comp' >".$pjname."</a></td></tr>";
	$list_html .= "</table>";
	return($list_html);
}
/************************************************************************************************************
function pjdelete($post)

引数1		$post								入力内容
引数2		$data								登録ファイル内容

戻り値	なし
************************************************************************************************************/
function pjdelete($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$id = $post['id'];
	//プロジェクト削除
	$sql = "DELETE FROM projectinfo WHERE 5CODE = ".$id.";";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
	if(isset($post['shintyoku']))
	{
		$sql = "SELECT * FROM projectditealinfo WHERE 5CODE = ".$id.";";
		$result = $con->query($sql) or ($judge = true);
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$code .= $result_row['6CODE'].',';
		}
		$code = rtrim($code,',');
		$sql = "DELETE FROM progressinfo WHERE 6CODE IN (".$code.");";
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	//社員別プロジェクト削除
	$sql = "DELETE FROM projectditealinfo WHERE 5CODE = ".$id.";";
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
}
/************************************************************************************************************
function endMonth()

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function endMonth(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	//require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$endmonth = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM endmonthinfo;";
	$result = $con->query($sql);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$endmonth .= $result_row['PERIOD'].",".$result_row['YEAR'].",".$result_row['MONTH'].",";
	}
	return ($endmonth);
}
/************************************************************************************************************
function makeEndMonth()

引数1		$post							登録フォーム入力値
引数2		$tablenum						テーブル番号
引数3		$type							1:insert 2:edit 3:delete

戻り値		$errorinfo						既登録確認結果
************************************************************************************************************/
function makeEndMonth(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$endmonth_ini = parse_ini_file('./ini/endmonth.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB関数呼び出し準備
	require_once ("f_SQL.php");																							// SQL関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$date = date_create('NOW');
	$nowyr = date_format($date, "Y");
	$nowmn = date_format($date, "n");
	$nowpd = getperiod($nowmn,$nowyr);
	$before = $endmonth_ini['endmonth']['before_period'];
	$start = $nowpd - $before + 1 ;

	//------------------------//
	//          変数          //
	//------------------------//
	$sql = "";
	$judge = false;
	$endmonth = array();
	$listhtml = "";
	
	//------------------------//
	//          処理          //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$sql = "SELECT * FROM endmonthinfo;";
	$result = $con->query($sql);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if(isset($endmonth[$result_row['PERIOD']]))
		{
			$endmonth[$result_row['PERIOD']][count($endmonth[$result_row['PERIOD']])] = $result_row['MONTH'];
		}
		else
		{
			$endmonth[$result_row['PERIOD']][0] = $result_row['MONTH'];
		}
	}
	$listhtml .= "<table><tr><td>";
	$listhtml .= "<table><td width='140''>　</td><td width='20' bgcolor='#f4a460'>　</td><td>･･･月次済</td></tr></table>";
	$listhtml .= "</td></tr><tr><td><table class ='list'><thead><tr>";
	$listhtml .= "<th><a class ='head'>期</a></th>";
	$listhtml .= "<th colspan='12'><a class ='head'>月</a></th></tr></thead>";
	$listhtml .= "<tbody>";	
	
	for($i = 0; $i < $before; $i++)
	{
		//期を作成
		$listhtml .= "<tr><td class='center' bgcolor='#1E90FF'><a class ='body'>".($start+$i)."</a></td>";
		
		//12ヶ月表作成
		for($j = 0; $j < 12; $j++)
		{
			if($j < 7)
			{
				$color = "";
				if(!empty($endmonth[($start+$i)]))
				{
					for($g = 0; $g < count($endmonth[($start+$i)]); $g++)
					{
						$month = $endmonth[($start+$i)][$g];
						if($month == ($j + 6))
						{
							$color = "#f4a460";
							break;
						}
					}
				}
				$listhtml .= "<td class='center' width='25' bgcolor='".$color."'><a class ='body'>".($j + 6)."</a></td>";
			}
			else
			{
				if(!empty($endmonth[($start+$i)]))
				{
					$color = "";
					for($g = 0; $g < count($endmonth[($start+$i)]); $g++)
					{
						$month = $endmonth[($start+$i)][$g];
						if($month == ($j - 6))
						{
							$color = "#f4a460";
							break;
						}
					}
				}
				$listhtml .= "<td class='center' width='25' bgcolor='".$color."'><a class ='body'>".($j - 6)."</a></td>";
			}
		}
		$listhtml .= "</tr>";
	}
	$listhtml .= "</tbody></table></td></tr></table>";

	return ($listhtml);
}
/************************************************************************************************************

//重複チェック用
function edaget()


引数	

戻り値	
************************************************************************************************************/
	
function pjget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM projectnuminfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$lisstr .= $result_row['PUROJECTNUM'].",".$result_row['PROJECTNAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function edaget()


引数	

戻り値	
************************************************************************************************************/
	
function edaget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM edabaninfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$lisstr .= $result_row['EDABAN'].",".$result_row['PJNAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function kouget()


引数	

戻り値	
************************************************************************************************************/
	
function kouget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM kouteiinfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$lisstr .= $result_row['KOUTEIID'].",".$result_row['KOUTEINAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
/************************************************************************************************************

//重複チェック用
function edaget()


引数	

戻り値	
************************************************************************************************************/
	
function syaget(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	
	//------------------------//
	//          定数          //
	//------------------------//
	$sql = "SELECT * FROM syaininfo ;";
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        検索処理        //
	//------------------------//
	$con = dbconect();																									// db接続関数実行
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$lisstr .= $result_row['STAFFID'].",".$result_row['STAFFNAME'].",";
		$cntrow = $cntrow + 1;
	}
	$listrow = $lisstr."";
	return($listrow);
}
?>
