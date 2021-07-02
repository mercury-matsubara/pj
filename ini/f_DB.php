<?php


/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////




/***************************************************************************
function dbconect()


����			�Ȃ�

�߂�l	$con	mysql�ڑ��ς�objectT
***************************************************************************/

function dbconect(){


//-----------------------------------------------------------//
//                                                           //
//                     DB�A�N�Z�X����                        //
//                                                           //
//-----------------------------------------------------------//

	
	//-----------------------------//
	//   ini�t�@�C���ǂݎ�菀��   //
	//-----------------------------//
	$db_ini_array = parse_ini_file("./ini/DB.ini",true);																// DB��{���i�[.ini�t�@�C��
	
	//-------------------------------//
	//   ini�t�@�C�������擾����   //
	//-------------------------------//
	$host = $db_ini_array["database"]["host"];																			// DB�T�[�o�[�z�X�g
	$user = $db_ini_array["database"]["user"];																			// DB�T�[�o�[���[�U�[
	$password = $db_ini_array["database"]["userpass"];																	// DB�T�[�o�[�p�X���[�h
	$database = $db_ini_array["database"]["database"];																	// DB��
	
	
	//------------------------//
	//     DB�A�N�Z�X����     //
	//------------------------//
	$con = new mysqli($host,$user,$password, $database, "3306") or die('1'.$con->error);			// DB�ڑ�
	
	$con->set_charset("cp932") or die('2'.$con->error);												// cp932���g�p����
	return ($con);
}


/************************************************************************************************************
function login($userName,$usserPass)


����1	$userName				���[�U�[��
����2	$userPass				���[�U�[�p�X���[�h

�߂�l	$result					���O�C������
************************************************************************************************************/
	
function login($userName,$userPass){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$Loginsql = "select * from loginuserinfo where LUSERNAME = '".$userName."' AND LUSERPASS = '".$userPass."' ;";		// ���O�C��SQL��
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$log_result = false;																								// ���O�C�����f
	$rownums = 0;																										// �������ʌ���
	
	//------------------------//
	//    ���O�C����������    //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($Loginsql);																					// �N�G�����s
	$rownums = $result->num_rows;																						// �������ʌ����擾
	
	//------------------------//
	//    ���O�C�����f����    //
	//------------------------//
	if ($rownums == 1)
	{
		$log_result = true;																								// ���O�C������true
	}
	return ($log_result);
	
}


/************************************************************************************************************
function limit_date()


����	�Ȃ�					���[�U�[��

�߂�l	$result					�L����������
************************************************************************************************************/
	
function limit_date(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																						// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$Loginsql = "select * from systeminfo;";																		// �L������SQL��
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$limit_result = 0;																								// �L���������f
	$rownums = 0;																									// �������ʌ���
	$startdate = "";
	$enddate = "";
	$befor_month = "";
	$message = "";
	$result_limit = array();
	
	//------------------------//
	//    ���O�C����������    //
	//------------------------//
	$con = dbconect();																								// db�ڑ��֐����s
	$result = $con->query($Loginsql) or die($con-> error);														// �N�G�����s
	$rownums = $result->num_rows;																					// �������ʌ����擾
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$startdate = $result_row['STARTDATE'];
	}
	
	//------------------------//
	//    ���O�C�����f����    //
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


����1	$userID						���[�U�[��
����2	$userPass					���[�U�[�p�X

�߂�l	$columnName					���ɓo�^����Ă���J������
************************************************************************************************************/
	
function UserCheck($userID,$userPass){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$checksql1 = "select * from loginuserinfo where LUSERNAME ='".$userID."' OR LUSERPASS ='".$userPass."' ;";			// ���o�^�m�FSQL��1
	$checksql2 = "select * from loginuserinfo where LUSERNAME ='".$userID."' ;";										// ���o�^�m�FSQL��2
	$checksql3 = "select * from loginuserinfo where LUSERPASS ='".$userPass."' ;";										// ���o�^�m�FSQL��3
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$columnName = ""		;																							// ���ɓo�^����Ă���J�������錾
	$rownums = 0;																										// �������ʌ���
	
	//------------------------//
	//      �`�F�b�N����      //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($checksql1);																					// �N�G�����s
	$rownums = $result->num_rows;																						// �������ʌ����擾
	if($rownums == 0)
	{
		return($columnName);
	}
	else
	{
		$result = $con->query($checksql2);																				// �N�G�����s
		$rownums = $result->num_rows;																					// �������ʌ����擾
		if($rownums != 0)
		{
			$columnName .= 'LUSERNAME';
		}
		return($columnName);
	}
	
	
	
}


/************************************************************************************************************
function insertUser()


����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
	
function insertUser(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$userID = $_SESSION['insertUser']['uid'];
	$userPass = $_SESSION['insertUser']['pass'];
	$insertsql = "insert into loginuserinfo (LUSERNAME,LUSERPASS) value ('".$userID."','".$userPass."') ;";				// ���o�^�m�FSQL��

	//------------------------//
	//        �o�^����        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$con->query($insertsql);																							// �N�G�����s
}


/************************************************************************************************************
function selectUser()


����	�Ȃ�

�߂�l	list			listhtml
************************************************************************************************************/
	
function selectUser(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	if(!isset($_SESSION['listUser']))
	{
		$_SESSION['listUser']['limit'] = ' limit 0,10';
		$_SESSION['listUser']['limitstart'] =0;
		$_SESSION['listUser']['where'] ='';
		$_SESSION['listUser']['orderby'] ='';
	}
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$limit = $_SESSION['listUser']['limit'];																			// limit
	$limitstart = $_SESSION['listUser']['limitstart'];																	// limit�J�n�ʒu
	$where = $_SESSION['listUser']['where'];																			// ����
	$orderby = $_SESSION['listUser']['orderby'];																		// order by ����
	$totalSelectsql = "SELECT * from loginuserinfo ".$where." ;";														// �Ǘ��ґS���擾SQL
	$selectsql = "SELECT * from loginuserinfo ".$where.$orderby.$limit." ;";											// �Ǘ��҃��X�g���擾SQL��
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$totalcount = 0;
	$listcount = 0;
	$list_str = "";
	$counter = 1;
	$id ="";
	
	//------------------------//
	//        �o�^����        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($totalSelectsql);																				// �N�G�����s
	$totalcount = $result->num_rows;																					// �������ʌ����擾
	$result = $con->query($selectsql);																					// �N�G�����s
	$listcount = $result->num_rows;																						// �������ʌ����擾
	if ($totalcount == $limitstart )
	{
		$list_str .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
	}
	else
	{
		$list_str .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
	}
	$list_str .= "<table class = 'list' ><thead><tr>";
	$list_str .= "<th>No.</th>";
	$list_str .= "<th>�Ǘ���ID</th>";
	$list_str .= "<th>�ҏW</th>";
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
					.$result_row['LUSERID']."_edit' value = '�ҏW'></td></tr>";
		$counter++;
	}
	$list_str .= "</tbody>";
	$list_str .= "</table>";
	$list_str .= "<div class = 'left'>";
	$list_str .= "<input type='submit' name ='back' value ='�߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div><div class = 'left'>";
	$list_str .= "<input type='submit' name ='next' value ='�i��' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_str .= " disabled='disabled'";
	}
	$list_str .= "></div>";
	return($list_str);
}

/************************************************************************************************************
function selectID($id)


����	$id						�����Ώ�ID

�߂�l	$result_array			��������
************************************************************************************************************/
	
function selectID($id){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$selectidsql = "SELECT * FROM loginuserinfo where LUSERID = ".$id." ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($selectidsql);																				// �N�G�����s
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function updateUser()


����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
	
function updateUser(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$userID = $_SESSION['editUser']['uid'];
	$userPass = $_SESSION['editUser']['newpass'];
	$id = $_SESSION['listUser']['id'];
	$updatesql = "UPDATE loginuserinfo SET LUSERNAME ='"
				.$userID."', LUSERPASS = '".$userPass."' where LUSERID = ".$id." ;";									// �X�VSQL��

	//------------------------//
	//        �X�V����        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$con->query($updatesql);																							// �N�G�����s
}
/************************************************************************************************************
function deleteUser()


����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
	
function deleteUser(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$id = $_SESSION['result_array']['LUSERID'];
	$deletesql = "DELETE FROM loginuserinfo where LUSERID = ".$id." ;";													// �X�VSQL��

	//------------------------//
	//        �X�V����        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$con->query($deletesql);																							// �N�G�����s
}



/************************************************************************************************************
function makeList($sql,$post)

����1	$sql						����SQL
����2	$post						�y�[�W�ړ����̃|�X�g

�߂�l	list_html					���X�ghtml
************************************************************************************************************/
function makeList($sql,$post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
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
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit�J�n�ʒu

	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql[1]) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// �Ō��';'�폜
	$sql[0] .= $limit.";";																									// LIMIT�ǉ�
	$result = $con->query($sql[0]) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// �������ʌ����擾
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
	}
	else
	{
		$list_html .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>���s</a></th>";
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
		$list_html .="<th><a class ='head'>�ҏW</a></th></tr><thead>";
	}
	if($filename == 'nenzi_5'){
		$list_html .="<th><a class ='head'>����</a></th></tr><thead>";
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
//							$result_row[$main_table.'CODE']."' value = '�ҏW' ".$disabled."></td>";
			if($filename == "PJTOUROKU_2")
			{
				$list_html .= "<td ".$id."  valign='top'><input type='submit' name='item_".
								$result_row[$main_table.'CODE']."' value = '�ҏW' ".$disabled."></td>";
			}
			else
			{
				$list_html .= "<td ".$id."  valign='top'><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '�ҏW' ".$disabled."></td>";
			}
		}
		
		if($filename == 'nenzi_5'){
			$list_html .= "<td ".$id."  valign='top'><input type='submit' name='edit_".
				$result_row[$main_table.'CODE']."' value = '���܂���' ".$disabled."></td>";
		}
		
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='back' value ='�߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div><div class = 'left'>";
	$list_html .= "<input type='submit' name ='next' value ='�i��' class = 'button' style ='height : 30px;' ";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
	return ($list_html);
}



/************************************************************************************************************
function makeList_Modal($sql,$post,$tablenum)

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function makeList_Modal($sql,$post,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit = $_SESSION['Modal']['limit'];																				// limit
	$limitstart = $_SESSION['Modal']['limitstart'];																		// limit�J�n�ʒu
	$resultcolumns = $form_ini[$tablenum]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	//------------------------//
	//          �U��          //
	//------------------------//
	
	$filename = $_SESSION['filename'];
	
	
	
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$columns = '402,403,202,203';
		$columns_array = explode(',',$columns);
	}
	
	
	
	
	
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql[1]) or ($judge = true);																	// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// �Ō��';'�폜
	$sql[0] .= $limit.";";																								// LIMIT�ǉ�
	$result = $con->query($sql[0]) or ($judge = true);																	// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// �������ʌ����擾
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
	}
	else
	{
		$list_html .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>�I��</a></th>";
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
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' class = 'button' name ='back' value ='�߂�'";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td><td>";
	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='�i��'";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}

/************************************************************************************************************
function existCheck($post,$tablenum,$type)

����1		$post							�o�^�t�H�[�����͒l
����2		$tablenum						�e�[�u���ԍ�
����3		$type							1:insert 2:edit 3:delete

�߂�l		$errorinfo						���o�^�m�F����
************************************************************************************************************/
function existCheck($post,$tablenum,$type){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	//require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// SQL�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$uniquecolumn = $form_ini[$filename]['uniquecheck'];
	$uniquecolumn_array = explode(',',$uniquecolumn);
	$master_tablenum = $form_ini[$tablenum]['seen_table_num'];
	$master_tablenum_array = explode(',',$master_tablenum);
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	switch($type)
	{
	case 1 :
		$syorimei = "�o�^";
		break;
	case 2 :
		$syorimei = "�ҏW";
		break;
	case 3 :
		$syorimei = "�폜";
		break;
	default :
		break;
	}
	$con = dbconect();																									// db�ڑ��֐����s
	if($type == 1)
	{

		if ($filename ==  "PJTOUROKU_1") {
			$cntrow = 0;
			$code1 = "";
			$code2 = "";
			$code1 = $post['1CODE'];
			$code2 = $post['2CODE'];

			$sql = "SELECT COUNT(*) FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2." ;";

			$result = $con->query($sql) or ($judge = true);																	// �N�G�����s
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
										$table_title."���łɓo�^����Ă���PJ��񂽂�".
										$syorimei."�ł��܂���B</a></div><br>";
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
		$result = $con->query($sql) or ($judge = true);																	// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."��񂪍폜����Ă��邽��".
									$syorimei."�ł��܂���B</a></div><br>";
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
			$result = $con->query($sql) or ($judge = true);																// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			if($result->num_rows != 0 )
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
		$result = $con->query($sql) or ($judge = true);																	// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		if($result->num_rows == 0 )
		{
			$errorinfo[$counter] = "<div class = 'center'><a class = 'error'>".
									$table_title."��񂪍폜����Ă��邽��".
									$syorimei."�ł��܂���B</a></div><br>";
			$counter++;
		}
	}
	return ($errorinfo);
}

/************************************************************************************************************
function insert($post)

����		$post						���͓��e

�߂�l		�Ȃ�
************************************************************************************************************/
function insert($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sql = "";
	$judge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = InsertSQL($post,$tablenum,"");
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
				$result = $con->query($sql) or ($judge = true);																// �N�G�����s
				if($judge)
				{
					error_log($con->error,0);
				}
			}
		}
	}
	if($filename == 'SIZAIINFO_1')
	{
		$main_CODE = $con->insert_id;
		$sql = "INSERT INTO zaikoinfo (1CODE,ZAIKONUM) VALUES (".$main_CODE.",0)";
		$result = $con->query($sql) or ($judge = true);																// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
		}
	}
	
}

/************************************************************************************************************
function make_post($main_codeValue)

����		$main_codeValue						���C���e�[�u���̃v���C�}���[�ԍ�

�߂�l		�Ȃ�
************************************************************************************************************/
function make_post($main_codeValue){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$code = $tablenum.'CODE';
	$_SESSION['edit'][$code] = $main_codeValue;
	$sql = idSelectSQL($main_codeValue,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
			$result = $con->query($sql) or ($judge = true);																// �N�G�����s
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
			$result = $con->query($sql) or ($judge = true);																// �N�G�����s
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

����		$post								���͓��e

�߂�l		�Ȃ�
************************************************************************************************************/
function update($post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = UpdateSQL($post,$tablenum,"");
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
					$result = $con->query($sql) or ($judge = true);																// �N�G�����s
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
				$result = $con->query($sql) or ($judge = true);																// �N�G�����s
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

����		$post							���͓��e

�߂�l		$path							csv�t�@�C���p�X
************************************************************************************************************/
function make_csv($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	require_once ("f_File.php");																						// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	
	
	
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
		$sql[0] = str_replace("*"," PROJECTNUM,EDABAN,PJNAME,STAFFID,KOUTEINAME ",$sql[0]);
	}
	error_log($sql[0],0);
	
	$result = $con->query($sql[0]) or ($judge = true);																	// �N�G�����s
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
					$header = '6��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '7month' )
				{
					$header = '7��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '8month' )
				{
					$header = '8��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '9month' )
				{
					$header = '9��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '10month' )
				{
					$header = '10��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '11month' )
				{
					$header = '11��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '12month' )
				{
					$header = '12��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '1month' )
				{
					$header = '1��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '2month' )
				{
					$header = '2��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '3month' )
				{
					$header = '3��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '4month' )
				{
					$header = '4��';
					$header_csv .= $header;
					$where = key_value($key,$post);
				}
				if($key == '5month' )
				{
					$header = '5��';
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
						$header = "�o�א�";
					}
					if($key == 'HENKYAKUSUM')
					{
						$header = "�ԋp��";
					}
					if($key == 'ZAIKO')
					{
						$header = "�y��݌ɐ�";
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
function delete($post,$data)

����1		$post								���͓��e
����2		$data								�o�^�t�@�C�����e

�߂�l	�Ȃ�
************************************************************************************************************/
function delete($post,$data){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$list_tablenum = $form_ini[$tablenum]['see_table_num'];
	$list_tablenum_array = explode(',',$list_tablenum);
	$main_table_type = $form_ini[$tablenum]['table_type'];
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$code = $tablenum.'CODE';
	$delete_CODE = $post[$code];
	$sql = DeleteSQL($delete_CODE,$tablenum,$code);
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
								// ��A���C�̏ꍇ
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
							$result = $con->query($sql) or ($judge = true);												// �N�G�����s
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

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function make_zaikokei(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
		if(strstr($result_row['MAKEDATE'],'���a') == true)
		{
			$pre_year = mb_ereg_replace('[^0-9]', '', $result_row['MAKEDATE']);
			if($pre_year < $year)
			{
				$year = $pre_year;
				$old_make_date = $result_row['MAKEDATE'];
				$year_type = 2;
			}
		}
		else if(strstr($result_row['MAKEDATE'],'����') == true && $year_type != 2)
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

����1		$post										�I��N��
����2		$tablenum									���C���e�[�u���ԍ�

�߂�l		$syakentable								�N���I�������N�e�[�u��
************************************************************************************************************/
function make_kensaku($post,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$befor_year = ($year - 2);
	$after_year = ($year + 3);
	$filename = $_SESSION['filename'];
	$formnum = $form_ini[$filename]['sech_form_num'];
	$columnname = $form_ini[$formnum]['column'];
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$sql = kensakuSelectSQL($post,$tablenum);
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
	$syakentable = "<table id = 'syaken'><tr><th>�L������������</th></tr>";
	for($yearcount = $befor_year ; $yearcount < ($after_year+1) ; $yearcount++)
	{
		$syakentable .= "<tr><td class='year".$counter."'><a class ='kensakuyear'>";
		$counter++;
		$wareki1 = wareki_year($yearcount);
		$wareki2 = wareki_year_befor($yearcount);
		if($wareki1 != $wareki2)
		{
			$wareki = $wareki1."�N - ".$wareki2."�N�x [".$yearcount."]";
		}
		else
		{
			$wareki = $wareki1."�N�x [".$yearcount."]";
		}
		$syakentable .= $wareki."</a></td>";
		for($monthcount = 1 ;$monthcount < (12 + 1); $monthcount++)
		{
			if(isset($syakenbi[$yearcount][$monthcount]))
			{
				$syakentable .= "<td><a href='./kensakuJump.php?year="
								.$yearcount."&month=".$monthcount."'> ";
				$syakentable .= $monthcount."��[".$syakenbi[$yearcount][$monthcount]."] </a></td>";
			}
			else
			{
				$syakentable .= "<td><a class='itemname'> ";
				$syakentable .= $monthcount."��[0] </a></td>";
			}
		}
		$syakentable .="</tr>";
	}
	$syakentable .="</table>";
	return($syakentable);
}

/************************************************************************************************************
function make_mail($code,$tablenum)

����1		$code								
����2		$tablenum							

�߂�l		$mail_param							
************************************************************************************************************/
function make_mail($code,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	require_once ("f_Form.php");																						// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function pdf_select($code_value,$tablenum,$maintablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$column = $form_ini[$tablenum]['insert_form_num'];
	$columnname = $form_ini[$column]['column'];
	$link_num = $form_ini[$column]['link_num'];
	$code = $maintablenum."CODE";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$pdf_table = "";
	$pdf_path = '';
	$isonece = true ;
	$pdf_result = array();
	$judge = false;
	$count=0;
	
	
	//------------------------//
	//          ����          //
	//------------------------//
	$sql = idSelectSQL($code_value,$tablenum,$code);
	$sql = substr($sql,0,-1);
	$sql .=" order by ".$columnname." desc ;";
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
		$pdf_table = '<a class = "error">�Ώۃt�@�C���Ȃ�</a>';
	}
	
	$pdf_result[0] = $pdf_table;
	$pdf_result[1] = $pdf_path;
	return($pdf_result);
}


/************************************************************************************************************
function syaken_mail_select()

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function syaken_mail_select(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	require_once ("f_mail.php");																						// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
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
	//          ����          //
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
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function make_check_array($post,$main_table){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$check_array = array();
	$judge = false;
	$count = 0;
	$check_str = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$sql = joinSelectSQL($post,$main_table);
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql[0]) or ($judge = true);																	// �N�G�����s
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

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function table_code_exist(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$listtablenum = $form_ini[$tablenum]['see_table_num'];
	$listtablenum_array = explode(',',$listtablenum);
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;
	$isexit = false;
	$count = 0;
	
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	for($i = 0 ; $i < count($listtablenum_array) ; $i++)
	{
		$sql = codeCountSQL($tablenum,$listtablenum_array[$i]);
		$result = $con->query($sql) or ($judge = true);																	// �N�G�����s
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

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function make_label($code,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	require_once ("f_Form.php");																						// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$param_ini = parse_ini_file('./ini/param.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$sql = codeSelectSQL($code,$tablenum);
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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


����	$id						�����Ώ�ID

�߂�l	$result_array			��������
************************************************************************************************************/
	
function existID($id){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_name'];
	$selectidsql = "SELECT * FROM ".$tablename." where ".$tablenum."CODE = ".$id." ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$result_array =array();
	
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($selectidsql);																				// �N�G�����s
	if($result->num_rows == 1)
	{
		$result_array = $result->fetch_array(MYSQLI_ASSOC);
	}
	return($result_array);
}

/************************************************************************************************************
function countLoginUser()


����	

�߂�l	
************************************************************************************************************/
	
function countLoginUser(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$sql = "SELECT COUNT(*) FROM loginuserinfo ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	
	$result = $con->query($sql);																				// �N�G�����s
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

����1	$sql						����SQL

�߂�l	list_html					���X�ghtml
************************************************************************************************************/
function makeList_item($sql,$post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$SQL_ini = parse_ini_file('./ini/SQL.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
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
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit�J�n�ʒu

	//------------------------//
	//          �ϐ�          //
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
	$value_GENBA = "���I��";
	$value_4CODE = -1;
	$total_charge = 0;
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
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
		$list_html .= "<br>�I������ : ".$value_GENBA."<br><br><input type = 'hidden' id = 'check_4CODE' value = '".
						$value_4CODE."'>";
	}
	$result = $con->query($sql[1]) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$_SESSION['kobetu']['total'] = $totalcount;
	if($filename != 'HENKYAKUINFO_2' && $filename != 'SYUKKAINFO_2' && $filename != 'PJTOUROKU_2')
	{
		$sql[0] = substr($sql[0],0,-1);																						// �Ō��';'�폜
		$sql[0] .= $limit.";";																									// LIMIT�ǉ�
	}
	$result = $con->query($sql[0]) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// �������ʌ����擾
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
	}
	else
	{
		$list_html .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
	}
	$list_html .= "<table class ='list'><thead><tr>";
	if($isCheckBox == 1 )
	{
		$list_html .="<th><a class ='head'>���s</a></th>";
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
	
	
	
	if($filename == 'PJTOUROKU_2')
	{
		$list_html .="<th><a class ='head'>�Ј��ʋ��z</a></th>";
	}
	else
	{
		if($isEdit == 1)
		{
			$list_html .="<th><a class ='head'>�ҏW</a></th>";
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
		
		
		
		
		
		
		if($filename == 'PJTOUROKU_2')
		{
			$check_js = 'onChange = " return inputcheck(\'kobetu_'.$totalcount.'_'.$counter.'\',10,7,0)"';
			$kobetu_value = '';
			$sql = "SELECT DETALECHARGE FROM  projectditealinfo WHERE 5CODE = ".$_SESSION['kobetu']['id']." AND 4CODE = ".$result_row['4CODE']." ;";
			
			$result1 = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
		else
		{
			if($isEdit == 1)
			{
				$list_html .= "<td ".$id."><input type='submit' name='edit_".
								$result_row[$main_table.'CODE']."' value = '�ҏW'></td>";
			}
		}
		
		
		
		
		
		
		
		$list_html .= "</tr>";
		$counter++;
	}
	$list_html .="</tbody></table>";
	if($filename != 'PJTOUROKU_2')
	{
		$list_html .= "<div class = 'left'>";
		$list_html .= "<input type='submit' name ='back' value ='�߂�' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div><div class = 'left'>";
		$list_html .= "<input type='submit' name ='next' value ='�i��' class = 'button' style ='height : 30px;' ";
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


����	$id						�����Ώ�ID

�߂�l	$result_array			��������
************************************************************************************************************/
	
function insertnyuusyukka($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_SQL.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
	//------------------------//
	$result_array =array();
	$sql_2CODE = "";
	$judge = false;
	
	//------------------------//
	//        ��������        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sql_2CODE = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db�ڑ��֐����s
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
				$result = $con->query($insert_nyuusyukka) or ($judge = true);																	// �N�G�����s
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$insert_rireki = "INSERT INTO rirekiinfo (1CODE,2CODE,4CODE,IONUM,IOTYPE,CREATEDATE,SAGYOUDATE) VALUES(";
				$insert_rireki .= $value_1CODE.",".$value_2CODE.",".$value_4CODE.",'".
									$value."','".$type."','".$date."','".$sagyou_date."');";
				$result = $con->query($insert_rireki) or ($judge = true);																	// �N�G�����s
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

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function makeList_radio($sql,$post,$tablenum){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	if($filename == "nenzi_5")
	{
		$columns = "102,202,203";
	}
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit = $_SESSION['list']['limit'];																				// limit
	$limitstart = $_SESSION['list']['limitstart'];																		// limit�J�n�ʒu
	$resultcolumns = $form_ini[$filename]['result_num'];
	$resultcolumns_array = explode(',',$resultcolumns);


	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	
	
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql[1]) or ($judge = true);																	// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$totalcount = $result_row['COUNT(*)'];
	}
	$sql[0] = substr($sql[0],0,-1);																						// �Ō��';'�폜
	$sql[0] .= $limit.";";																								// LIMIT�ǉ�
	$result = $con->query($sql[0]) or ($judge = true);																	// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$listcount = $result->num_rows;																						// �������ʌ����擾
	if ($totalcount == $limitstart )
	{
		$list_html .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
	}
	else
	{
		$list_html .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
	}
	$list_html .= "<table class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>�I��</a></th>";
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
	$list_html .= "<table><tr><td>";
	$list_html .= "<input type='submit' class = 'button' name ='back' value ='�߂�'";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td><td>";
	$list_html .= "<input type='submit' class = 'button'  name ='next' value ='�i��'";
	if(($limitstart + $listcount) == $totalcount)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></td>";
	return ($list_html);
}



/************************************************************************************************************
function genbaend($post)

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function genbaend($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_SQL.php");																							// DB�֐��Ăяo������
	require_once("f_mail.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$mail_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
	//------------------------//
	$result_array =array();
	$sql_GENBASTATUS = "";
	$judge = false;
	
	//------------------------//
	//        ��������        //
	//------------------------//
	
	$value_4CODE = $post['4CODE'];
	
	$sai_judge = true;
	$sql_GENBASTATUS = idSelectSQL($value_4CODE,4,'4CODE');
	
	
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql_GENBASTATUS);
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$value_GENBASTATUS = $result_row['GENBASTATUS'];
	}
	if($value_GENBASTATUS == 1)
	{
		$saiSql = idSelectSQL($value_4CODE,8,'4CODE');
		$result = $con->query($saiSql) or ($judge = true);																		// �N�G�����s
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
			$message = "<a class = 'item'>����I�������������������܂����B</a>";
		}
		else
		{
			$message = "<a class = 'error'>".$out_count."���̍��ُ������������Ă��܂���B<br>���ُ��������������Ă�����x����I�����������Ă��������B</a>";
		}
	}
	else if($value_GENBASTATUS == 0)
	{
		$henkyakuSql = henkyakuSQL($post,0);
		$result = $con->query($henkyakuSql) or ($judge = true);																		// �N�G�����s
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
					$saimail .= $sizainame."(".$sizaiid.") �ߏ� ".$sai."�� \r\n";
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
					$saimail .= $sizainame."(".$sizaiid.") �s�� ".$sai."�� \r\n";
				}
			}
		}
		if($sai_judge == false)
		{
			genba_change($value_4CODE,1);
			if($saimail != "")
			{
				$saimail = $genbaname."(".$genbaid.") �ɂč��ق�".$saicount."�� ������܂����B\r\n".$saimail;
				$saimail = rtrim($saimail,'\r\n');
				$title = $mail_ini['sai']['title'];
				$add = $mail_ini['sai']['send_add'];
				sendmail($add,$title,$saimail);
			}
			$message = "<a class = 'error'>".$saicount."���̍��ُ���������܂����B<br>���ُ��������������Ă�����x����I�����������Ă��������B</a>";
		}
		else
		{
			
			$henkyakuSql = henkyakuSQL($post,0);
			$result = $con->query($henkyakuSql) or ($judge = true);																		// �N�G�����s
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
			$message = "<a class = 'item'>����I�������������������܂����B</a>";
		}
	}
	return($message);
}

/************************************************************************************************************
function tyousei($value_1CODE,$SAITYPE,$SAINUM)

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function tyousei($value_1CODE,$SAITYPE,$SAINUM){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
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
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}


/************************************************************************************************************
function saiinsert($value_1CODE,$SAITYPE,$SAINUM)

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function saiinsert($value_4CODE,$value_2CODE,$value_1CODE,$SAITYPE,$SAINUM){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$date = date_create("NOW");
	$SAICREATEDATE = date_format($date, 'Y-m-d H:i:s');
	$judge = false;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "";
	$sql = "INSERT INTO saiinfo (4CODE,2CODE,1CODE,SAITYPE,SAICREATEDATE,SAISTATUS,SAINUM ) VALUES (";
	$sql .= $value_4CODE.",".$value_2CODE.",".$value_1CODE.",'".$SAITYPE."','".$SAICREATEDATE."','0','".$SAINUM."' ) ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}

/************************************************************************************************************
function genba_change($value_4CODE,$GENBASTATUS)

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function genba_change($value_4CODE,$GENBASTATUS){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_format($date, "Y-m-d");
	$judge = false;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
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
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		$sql = "DELETE FROM henkyakuinfo WHERE 4CODE = ".$value_4CODE." ;";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	
}

/************************************************************************************************************
���o�ח����폜����(���ފǗ��V�X�e��)
function deleterireki()

����1		$sql						����SQL

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function deleterireki(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$date = date_create("NOW");
	$date = date_sub($date, date_interval_create_from_date_string('5 year'));
	$DATE = date_format($date, "Y-m-d");
//	$DATETIME = date_format($date, 'Y-m-d H:i:s');
	$DATETIME = $DATE." 00:00:00";
	$judge = false;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "";
	$sql = "DELETE FROM pjinfo WHERE 5ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM projectditealinfo WHERE 6ENDDATE < '".$DATETIME."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM progressinfo WHERE 7ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
		$sql = "DELETE FROM endpjinfo WHERE 8ENDDATE < '".$DATETIME."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "DELETE FROM monthdatainfo WHERE 9ENDDATE < '".$DATE."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	deletedate_change();
}

/************************************************************************************************************
PJ���擾����(�v���W�F�N�g�Ǘ��V�X�e��)
function getPJdata(id)

����1		$id							�����Ώۃv���C�}���L�[

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getPJdata($id){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sql = "SELECT * FROM projectinfo LEFT JOIN  projectnuminfo USING(1CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE 5CODE = ".$id." ;";
	$judge = false;
	$count = 1;
	$result ;
	$result_row = array();
	$result_array = array();
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$result_array = $result_row;
	}
	$form = "<table><tr><td>�v���W�F�N�g�R�[�h :</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PROJECTNUM']."'>";
	$form .= "</td></tr><tr><td>�}�� :</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['EDABAN']."'>";
	$form .= "</td></tr><tr><td>�o�i�� :</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PJNAME']."'>";
	$form .= "</td></tr><tr><td>���z :</td><td>";
	$form .= "<input type = 'text' id ='PJCharge' class = 'readOnly' size = 40 readonly value = '".$result_array['CHARGE']."'>";
	$form .= "</td></tr></table>";
	return ($form);
	
}

/************************************************************************************************************
PJ�I������(�v���W�F�N�g�Ǘ��V�X�e��)
function pjend($post)

����1		$post						�폜�Ώ�

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function pjend($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$pjid = $post['5CODE'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	$period = 0;
	
	//------------------------//
	//        ��������        //
	//------------------------//
	$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE projectditealinfo.5CODE = ".$pjid." order by SAGYOUDATE ;";
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
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
	$checkflgmessage = '';
	$delcheckflg = false;
	foreach($keyarray as $key)
	{
		
		$teizi[$key] = 0;
		$zangyou[$key] = 0;
		$teizicheck[$key] = 0;
		unset($before);
		$checkteiziarray = array();
		for($i = 0 ; $i < count($time[$key]) ; $i++)
		{
			$after = $time[$key][$i]['SAGYOUDATE'];
			if(isset($before))
			{
				if($before == $after)
				{
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
					if(in_array($time[$key][$i]['TEIZITIME'], $checkteiziarray))
					{
						$checkflg = true;
						//���ꃌ�R�[�h�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^�ɓ��ꃌ�R�[�h�����݂��܂��B<br />';
					}
					
				}
				else
				{
					$teizicheck[$key] = 0;
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
					$checkteiziarray = array();
				}
			}
			else
			{
				$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
				if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
			}
			$teizi[$key]  += $time[$key][$i]['TEIZITIME'];
			$zangyou[$key]  += $time[$key][$i]['ZANGYOUTIME'];
			$charge = $time[$key][$i]['DETALECHARGE'];
			$before = $time[$key][$i]['SAGYOUDATE'];
			$checkteiziarray[] = $time[$key][$i]['TEIZITIME'];
			$pjnum = $time[$key][$i]['PROJECTNUM'];
			$pjeda = $time[$key][$i]['EDABAN'];
			$pjname = $time[$key][$i]['PJNAME'];
		}
		if(!$checkflg)
		{	
			$total = $teizi[$key] + $zangyou[$key];
		    $performance = round($charge/$total,3);
		    $sql_end = "INSERT INTO endpjinfo (6CODE,TEIJITIME,ZANGYOTIME,TOTALTIME,PERFORMANCE,PROJECTNUM,EDABAN,PJNAME) VALUES "
		    			."(".$key.",".$teizi[$key].",".$zangyou[$key].",".$total.",".$performance.","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".") ;";
		    $result = $con->query($sql_end) or ($judge = true);																		// �N�G�����s
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		    $month_array = array();
		    for($i = 0 ; $i < count($time[$key]); $i++ )
		    {
		    	$date = date_create($time[$key][$i]['SAGYOUDATE']);
		    	$month = date_format($date, 'n');
		    	$day = date_format($date, 'j');
		    	$year = date_format($date, 'Y');
		    	$period = getperiod($month,$year);
		    	if(isset($month_array[$month]))
		    	{
		    		$month_array[$month]['teizi'] += $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] += $time[$key][$i]['ZANGYOUTIME'];
		    	}
		    	else
		    	{
		    		$month_array[$month]['teizi'] = $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] = $time[$key][$i]['ZANGYOUTIME'];
		    		$month_array[$month]['4CODE'] = $time[$key][$i]['4CODE'];
		    	}
		    }
		    if(!$delcheckflg)
			{
				$sql_delete = "DELETE FROM monthdatainfo WHERE 5CODE = ".$pjid." ;";
				$result = $con->query($sql_delete) or ($judge = true);																		// �N�G�����s
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$delcheckflg = true;
			}
		    for($i = 1 ;$i <= 12 ;$i++)
		    {
		    	if(isset($month_array[$i]))
		    	{
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$pjid.",'".$period."','".$i."','�莞����','"
		    					.$month_array[$month]['teizi']."','".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$checkSQLtest .= $sql_month;
		    		$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$pjid.",'".$period."','".$i."','�c�Ǝ���','"
		    					.$month_array[$month]['zangyou']."','".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$checkSQLtest .= $sql_month;
		    		$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    	}
		    }
		    $sql_update = "UPDATE progressinfo SET 7ENDDATE = '".$nowdate."' , 7PJSTAT = '2' WHERE 6CODE = ".$key." ;";
		    $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		    $sql_update = "UPDATE projectditealinfo SET 6ENDDATE = '".$nowdate."' , 6PJSTAT = '2' WHERE 6CODE = ".$key." ;";
		    $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		}
	}
	if(!$checkflg)
	{
		$sql_update = "UPDATE projectinfo SET  5ENDDATE = '".$nowdate."' , 5PJSTAT = '2' WHERE 5CODE = ".$pjid." ;";
	    $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
	    if($judge)
	    {
	    	error_log($con->error,0);
	    	$judge = false;
	    }
	    return("�I���������������܂����B");
	}
	else
	{
		return($checkflgmessage);
	}
}

/************************************************************************************************************
��������(�v���W�F�N�g�Ǘ��V�X�e��)
function getuji($month,$period)

����1		$month						�����Ώی�
����2		$period 					��

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getuji($month,$period){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	$year = getyear($month,$period);
	$lastday = getlastday($month,$year);
	//------------------------//
	//        ��������        //
	//------------------------//
	$sql = "DELETE FROM monthdatainfo WHERE PERIOD = '".$period."' AND MONTH = '".$month."' AND 9ENDDATE is NULL;";
	
	$con = dbconect();																									// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE projectditealinfo.6PJSTAT = '1'"
			." AND progressinfo.SAGYOUDATE BETWEEN '"
			.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' ORDER BY SAGYOUDATE;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
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
	$checkflgmessage = '';
	foreach($keyarray as $key)
	{
		$teizi[$key] = 0;
		$zangyou[$key] = 0;
		$teizicheck[$key] = 0;
		unset($before);
		$checkteiziarray = array();
		for($i = 0 ; $i < count($time[$key]) ; $i++)
		{
			$after = $time[$key][$i]['SAGYOUDATE'];
			if(isset($before))
			{
				if($before == $after)
				{
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
					if(in_array($time[$key][$i]['TEIZITIME'], $checkteiziarray))
					{
						$checkflg = true;
						//���ꃌ�R�[�h�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^�ɓ��ꃌ�R�[�h�����݂��܂��B<br />';
					}
					
				}
				else
				{
					$teizicheck[$key] = 0;
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
					$checkteiziarray = array();
				}
			}
			else
			{
				$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
				if($teizicheck[$key] > 7.75)
					{
						$checkflg = true;
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'��'.$errrecdate.'�̐i���f�[�^���K��̒莞���Ԃ��z���Ă��܂��B<br />';
					}
			}
			$teizi[$key]  += $time[$key][$i]['TEIZITIME'];
			$zangyou[$key]  += $time[$key][$i]['ZANGYOUTIME'];
			$charge = $time[$key][$i]['DETALECHARGE'];
			$before = $time[$key][$i]['SAGYOUDATE'];
			$checkteiziarray[] = $time[$key][$i]['TEIZITIME'];
			$pjnum = $time[$key][$i]['PROJECTNUM'];
			$pjeda = $time[$key][$i]['EDABAN'];
			$pjname = $time[$key][$i]['PJNAME'];
		}
		if(!$checkflg)
		{
			$month_array = array();
		    for($i = 0 ; $i < count($time[$key]); $i++ )
		    {
		    	if(isset($month_array[$month]))
		    	{
		    		$month_array[$month]['teizi'] += $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] += $time[$key][$i]['ZANGYOUTIME'];
		    	}
		    	else
		    	{
		    		$month_array[$month]['teizi'] = $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] = $time[$key][$i]['ZANGYOUTIME'];
		    		$month_array[$month]['4CODE'] = $time[$key][$i]['4CODE'];
		    		$month_array[$month]['5CODE'] = $time[$key][$i]['5CODE'];
		    	}
		    }
		    for($i = 1 ;$i <= 12 ;$i++)
		    {
		    	if(isset($month_array[$i]))
		    	{
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$month_array[$i]['5CODE'].",'".$period."','".$month."','�莞����','"
		    					.$month_array[$month]['teizi']."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$month_array[$i]['5CODE'].",'".$period."','".$i."','�c�Ǝ���','"
		    					.$month_array[$month]['zangyou']."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    	}
		    }
		}
	}
	if(!$checkflg)
	{
		deletedate_change();
		return('aaa');
	}
	else
	{
		return($checkflgmessage);
	}
}

/************************************************************************************************************
�N�����ϊ�����(�v���W�F�N�g�Ǘ��V�X�e��)
function getperiod($month,$year)

����1		$month						��
����2		$year 						�N

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getperiod($month,$year){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$startyear = $item_ini['period']['startyear'];
	$startmonth = $item_ini['period']['startmonth'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$period = 0 ;
	
	
	
	//------------------------//
	//        ��������        //
	//------------------------//
	$period = $year - $startyear  + 1;
	if($startmonth > $month)
	{
		$period = $period - 1 ;
	}
	
	return $period;
	
}
/************************************************************************************************************
�����N�ϊ�����(�v���W�F�N�g�Ǘ��V�X�e��)
function getyear($month,$period)

����1		$month						��
����2		$period 					��

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getyear($month,$period){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$startyear = $item_ini['period']['startyear'];
	$startmonth = $item_ini['period']['startmonth'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$year = 0 ;
	
	
	
	//------------------------//
	//        ��������        //
	//------------------------//
	$year = $period + $startyear - 1;
	if($startmonth > $month)
	{
		$year = $year + 1 ;
	}
	
	return $year;
	
}

/************************************************************************************************************
�������擾����(�v���W�F�N�g�Ǘ��V�X�e��)
function getlastday($month,$year)

����1		$month						��

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getlastday($month,$year){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$day = 0 ;
	
	//------------------------//
	//        ��������        //
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
���܂�������(�v���W�F�N�g�Ǘ��V�X�e��)
function kimatagi($post)

����1		$post						�|�X�g���e

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function kimatagi($post){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	
	
	//------------------------//
	//        ��������        //
	//------------------------//
	
	
	
	
}

/************************************************************************************************************
���o�ח����폜����(���ފǗ��V�X�e��)
function deleterireki()

����1		$sql						����SQL

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function deletepjall($delkey){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	require_once("f_File.php");																							// DB�֐��Ăяo������
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "";
	$ssql = "";
	$sresult = "";
	$sql = "DELETE FROM projectinfo WHERE 5CODE = '".$delkey."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}

	$ssql = "select distinct(7CODE) from progressinfo where 6code in (select 6CODE from projectditealinfo where 5CODE = '".$delkey."' ) ;";
	$sresult = $con->query($ssql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $sresult->fetch_array(MYSQLI_ASSOC))
	{

		$sql = "DELETE FROM progressinfo WHERE 7CODE = '".$result_row['7CODE']."' ;";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	
	$sql = "DELETE FROM projectditealinfo WHERE 5CODE = '".$delkey."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
}
?>
