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
	$list_str .="<div style='clear:both;'></div>";

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
	$limit_num = $form_ini[$filename]['limit'];
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
	$list_html .="<div style='display:inline-flex'>";
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='��ԍŏ��ɖ߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
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
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='��ԍŌ�ɐi��' class = 'button' style ='height : 30px;' ";
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
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',',$columns);
	$main_table = $tablenum;
	$limit_num = $form_ini[$filename]['limit'];
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
	$list_html .= "<div class = 'left'>";
	$list_html .= "<input type='submit' name ='backall' value ='��ԍŏ��ɖ߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
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
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='��ԍŌ�ɐi��' class = 'button' style ='height : 30px;' ";
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
function endCheck($year,$month)

����1		$post							�o�^�t�H�[�����͒l
����2		$tablenum						�e�[�u���ԍ�
����3		$type							1:insert 2:edit 3:delete

�߂�l		$errorinfo						���o�^�m�F����
************************************************************************************************************/
function endCheck($year,$month){
	
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
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$year."' AND MONTH = '".$month."';";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows > 0)
	{
		$errorinfo[1] = "<div class = 'center'><a class = 'error'>���Ɍ����������������Ă�����Ԃ̂��߁A�o�^�ł��܂���B</a></div><br>";
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
	$endjudge = false;
	$codeValue = "";
	$code = "";
	$counter = 1;
	$main_CODE =0;
	$over = array();
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	if($filename == 'PROGRESSINFO_2')
	{
		
	}
	if(!$endjudge)
	{
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
                                        $con->query($SQL) or ($judge = true);																	// �N�G�����s
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
			$result = $con->query($sql) or ($judge = true);																// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
			}
		}
		if($filename == 'EDABANINFO_1')
		{
			//�}�Ԓ��o
			$sql = "SELECT MAX(2CODE) FROM edabaninfo;";
			$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge =false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$code2 = $result_row['MAX(2CODE)'] ;
			}
			//PJ�i���o���o
			$sql2 = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$post['form_102_0']."';";
			$result2 = $con->query($sql2) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge =false;
			}
			while($result_row = $result2->fetch_array(MYSQLI_ASSOC))
			{
				$code1 = $result_row['1CODE'] ;
			}
			
			//PJ�o�^
			$sql3 = "INSERT INTO projectinfo (1CODE,2CODE,CHARGE) VALUES (".$code1.",".$code2.",".$post['form_504_0'].");";
			$result3 = $con->query($sql3) or ($judge = true);																		// �N�G�����s
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
				//���z���J���}��؂�ɂ��Ȃ��悤�ɕύX
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

����		$post							���͓��e

�߂�l		$path							csv�t�@�C���p�X
************************************************************************************************************/
function make_getujicsv($period,$month){
	
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
	
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	for($i = 0; $i <= $lastday; $i++)
	{
		if($i == 0)
		{
			$hedder1 = "�Ј���,�敪,���v,";
			$hedder2 = "\r\n".$period."���@".$month."��\r\n�Ј���,���ԁE�Č���,�敪,���v,";
		}
		else
		{
			$hedder1 .= $i."��,";
			$hedder2 .= $i."��,";
		}
	}
	
	//���ԓ��ɐi���f�[�^�̂���Ј��R�[�h���擾
	$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
			."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
			.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syainArray[$syaincnt] = $result_row['4CODE'];
		$syaincnt++;
	}
	//�Ј��ԍ��ʍ�Ǝ��Ԍv�Z
	for($s = 0; $s < count($syainArray); $s++)
	{
		//������
		$name = "";
		$before = "";
		$teizi = 0;
		$zangyou = 0;
		$pjcnt = 0;
		$pjArray = array();
		
		//�Ј��R�[�h�Ɠ��t�������ɍ�Ɠ����őI��
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$name  = $result_row['STAFFNAME'];
			//�v���W�F�N�g���ƂɊi�[
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
					//���t���ς�邲�Ƃ�teizi��zangyou��������
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
		//�Ō�̃f�[�^���i�[
		$date = explode('-',$before);
		$day = $date[2];
		if(substr($day,0,1) == "0")
		{
			$day = ltrim($day,"0");
		}
		$getuji[$syainArray[$s]]['name'] = $name;
		$getuji[$syainArray[$s]][$day]['teizi'] = $teizi;
		$getuji[$syainArray[$s]][$day]['zangyou'] = $zangyou;

		//�Ј��v���W�F�N�g�ʍ�Ǝ��Ԍv�Z
		$keyarray = array_keys($pjArray);
		foreach($keyarray as $key)
		{
			//������
			$pjbefore = "";
			$pjteizi = 0;
			$pjzangyou = 0;
			
			//�v���W�F�N�g���ς�邲�Ƃɖ��O�ƃv���W�F�N�g�����i�[
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
						//���t���ς�邲�Ƃ�teizi��zangyou��������
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
				//�Ō�̃f�[�^���i�[
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
	//�Ј��R�[�h����csv�f�[�^�쐬
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
				$hteizi = mb_convert_encoding($getuji[$key]['name'], "sjis-win", "cp932").",[�莞],";
				$hzangyo = mb_convert_encoding($getuji[$key]['name'], "sjis-win", "cp932").",[�c��],";
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
	//�Ј��ʃv���W�F�N�g���Ƃ�csv�f�[�^�쐬
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
				$hteizi = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[�莞],";
				$hzangyo = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[�c��],";
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

����		$post							���͓��e

�߂�l		$path							csv�t�@�C���p�X
************************************************************************************************************/
function make_nenjicsv($period){
	
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
	
	
	//------------------------//
	//          �ϐ�          //
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
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	for($i = 0; $i <= 12; $i++)
	{
		if($i == 0)
		{
			$hedder1 = "�Ј���,�敪,���v,";
			$hedder2 = "\r\n".$period."��\r\n�Ј���,���ԁE�Č���,�敪,���v,";
		}
		else
		{
			if($i <= 7)
			{
				$hedder1 .= ($i+5)."��,";
				$hedder2 .= ($i+5)."��,";
			}
			else
			{
				$hedder1 .= ($i-7)."��,";
				$hedder2 .= ($i-7)."��,";
			}
		}
	}
	
	//���ԓ��ɐi���f�[�^�̂���Ј��R�[�h���擾
	$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
			."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
			.$start."-06-01' AND '".$end."-05-31' ORDER BY syaininfo.4CODE;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$syainArray[$syaincnt] = $result_row['4CODE'];
		$syaincnt++;
	}
	//�Ј��ԍ��ʍ�Ǝ��Ԍv�Z
	for($s = 0; $s < count($syainArray); $s++)
	{
		//������
		$name = "";
		$before = "";
		$teizi = 0;
		$zangyou = 0;
		$pjcnt = 0;
		$pjArray = array();
		
		//�Ј��R�[�h�Ɠ��t�������ɍ�Ɠ����őI��
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$start."-06-01' AND '".$end."-05-31' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$name = $result_row['STAFFNAME'];
			//�v���W�F�N�g���ƂɊi�[
			if(isset($pjArray[$result_row['6CODE']]))
			{
				$pjArray[$result_row['6CODE']][count($pjArray[$result_row['6CODE']])] = $result_row;
			}
			else
			{
				$pjArray[$result_row['6CODE']][0] = $result_row;
			}
			
			//��ƌ��̂ݎ擾
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
					//�����ς�邲�Ƃ�teizi��zangyou��������
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
		//�Ō�̌���$nenji�Ɋi�[
		$nenji[$syainArray[$s]]['name'] = $name;
		$nenji[$syainArray[$s]][$before]['teizi'] = $teizi;
		$nenji[$syainArray[$s]][$before]['zangyou'] = $zangyou;
		
		//�Ј��v���W�F�N�g�ʍ�Ǝ��Ԍv�Z
		$keyarray = array_keys($pjArray);
		foreach($keyarray as $key)
		{
			//������
			$pjbefore = "";
			$pjteizi = 0;
			$pjzangyou = 0;
			
			//�v���W�F�N�g���ς�邲�Ƃɖ��O�ƃv���W�F�N�g�����i�[
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
						//�����ς�邲�Ƃ�teizi��zangyou��������
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
				//�Ō�̌���$pj�Ɋi�[
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
	//�Ј��R�[�h����csv�f�[�^�쐬
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
				$hteizi = mb_convert_encoding($nenji[$key]['name'], "sjis-win", "cp932").",[�莞],";
				$hzangyo = mb_convert_encoding($nenji[$key]['name'], "sjis-win", "cp932").",[�c��],";
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
	//�Ј��ʃv���W�F�N�g���Ƃ�csv�f�[�^�쐬
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
				$hteizi = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[�莞],";
				$hzangyo = mb_convert_encoding($pj[$key]['name'], "sjis-win", "cp932").",".mb_convert_encoding($pj[$key]['pjname'], "sjis-win", "cp932").",[�c��],";
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
	$limit_num = $form_ini[$filename]['limit'];
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
	if($filename != 'PJTOUROKU_1')
        {
                if ($totalcount == $limitstart )
                {
                        $list_html .= $totalcount."���� ".($limitstart)."���`".($limitstart + $listcount)."�� �\����";					// �����\���쐬
                }
                else
                {
                        $list_html .= $totalcount."���� ".($limitstart + 1)."���`".($limitstart + $listcount)."�� �\����";				// �����\���쐬
                }
        }
	$list_html .= "<table class ='list'";
        if($filename == "PJTOUROKU_1")
        {
            //PJ�o�^��ʂ͒�����
            $list_html .= " style = 'margin: auto;'";
        }
        $list_html .= "><thead><tr>";
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
	
	
	
	if($filename == 'PJTOUROKU_2' || $filename == 'PJTOUROKU_1' || $filename == 'EDABANINFO_2')
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
		
		
		
		
		
		
		if($filename == 'PJTOUROKU_2' || $filename == 'EDABANINFO_2')
		{
			$check_js = 'onChange = " return inputcheck(\'kobetu_'.$totalcount.'_'.$counter.'\',7,7,0,2)"';
			$kobetu_value = null;
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
								$result_row[$main_table.'CODE']."' value = '�ҏW'></td>";
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
		$list_html .= "<input type='submit' name ='backall' value ='��ԍŏ��ɖ߂�' class = 'button' style ='height : 30px;' ";
		if($limitstart == 0)
		{
			$list_html .= " disabled='disabled'";
		}
		$list_html .= "></div>";
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
		$list_html .="<div class = 'left'>";
		$list_html .= "<input type='submit' name ='nextall' value ='��ԍŌ�ɐi��' class = 'button' style ='height : 30px;' ";
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
	$limit_num = $form_ini[$filename]['limit'];
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
	$list_html .= "<table border='1' class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>�I��</a></th>";
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
	$list_html .= "<input type='submit' name ='backall' value ='��ԍŏ��ɖ߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
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
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='��ԍŌ�ɐi��' class = 'button' style ='height : 30px;' ";
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

����1		$sql						����SQL
����2		$post						�y�[�W�ړ���post
����3		$tablenum					�\���e�[�u���ԍ�

�߂�l		$list_html					���[�_���ɕ\�����X�ghtml
************************************************************************************************************/

function makeList_check($sql,$post,$tablenum){
	
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
	$limit_num = $form_ini[$filename]['limit'];
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
	$list_html .= "<table border='1' class ='list'><thead><tr>";
	$list_html .="<th><a class ='head'>�I��</a></th>";
	for($i = 0 ; $i < count($resultcolumns_array) ; $i++)
	{
		$title_name = $form_ini[$resultcolumns_array[$i]]['link_num'];
		$list_html .="<th><a class ='head'>".$title_name."</a></th>";
	}
    
    //�I�����t���ڒǉ�
    $list_html .="<th><a class ='head'>�I�����t</a></th>";
    
    //�Ј����A�Ј��ʋ��z�A��Ǝ��ԍ��ڒǉ�
    $syainsql = "SELECT * FROM syaininfo;";         //�Ј��������߂�SQL
    $syainresult = $con->query($syainsql);																	// �N�G�����s
    $syain_rows = $syainresult -> num_rows;
    
    for($i = 0 ; $i < $syain_rows ; $i++)
    {
        $list_html .="<th><a class ='head'>�Ј���</a></th>";
        $list_html .="<th><a class ='head'>���z</a></th>";
        $list_html .="<th><a class ='head'>����</a></th>";
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
        //�I�����t���ڍ쐬
        $row .= "<td ".$id." ".$class." ><a class ='body'>"
						.$result_row['5ENDDATE']."</a></td>";
        
        //�ꗗ�̎Ј����A�Ј��ʋ��z�A��Ǝ��ԍ��ڍ쐬
        $syainrow = "";
        $list_num = 0;
        $row_sql = "SELECT *FROM projectditealinfo where 5CODE = ".$result_row["5CODE"].";";
        $row_result = $con->query($row_sql) or ($judge = true);

        while($row_list = $row_result->fetch_array(MYSQLI_ASSOC)){
            //�Ј���
            $item_sql = "SELECT *FROM syaininfo where 4CODE = ".$row_list["4CODE"].";";
            $item_result = $con->query($item_sql) or ($jadge = true);
            $item = $item_result->fetch_array(MYSQLI_ASSOC);
            $syainrow .="<td ".$id.">".$item["STAFFNAME"]."<a class ='body'></a></td>";

            //�Ј��ʋ��z
            $syainrow .="<td ".$id.">".$row_list["DETALECHARGE"]."<a class ='body'></a></td>";
            	
            //�Ј����Ƃ̒莞���ԂƎc�Ǝ��Ԃ̍��v�擾
            $item_sql = "SELECT SUM(TEIZITIME),SUM(ZANGYOUTIME) FROM progressinfo WHERE 6CODE = ".$row_list["6CODE"].";";
            $item_result = $con->query($item_sql) or ($judge = true);																		// �N�G�����s
            $item_row = $item_result->fetch_array(MYSQLI_ASSOC);
            
            $sagyoutime = $item_row["SUM(TEIZITIME)"] + $item_row["SUM(ZANGYOUTIME)"];
                
            $syainrow .="<td ".$id.">".$sagyoutime."<a class ='body'></a></td>";            
            $list_num++;
        }
        
        if($list_num < $syain_rows)
        {
            for(;$list_num < $syain_rows;$list_num++)
            {
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //�Ј���
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //�Ј��ʋ��z
                $syainrow .="<td ".$id."><a class ='body'></a></td>";       //��Ǝ���
            }
        }
        
		$form_name = substr($form_name,0,-1);
		$column_value = substr($column_value,0,-2);
		$form_type = substr($form_type,0,-1);
        
        if($result_row["5PJSTAT"] == "2")
        {
            $list_html .= '��';           
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
	$list_html .= "<input type='submit' name ='backall' value ='��ԍŏ��ɖ߂�' class = 'button' style ='height : 30px;' ";
	if($limitstart == 0)
	{
		$list_html .= " disabled='disabled'";
	}
	$list_html .= "></div>";
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
	$list_html .="<div class = 'left'>";
	$list_html .= "<input type='submit' name ='nextall' value ='��ԍŌ�ɐi��' class = 'button' style ='height : 30px;' ";
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
	$form = "<table><tr><td>�v���W�F�N�g�R�[�h</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PROJECTNUM']."'>";
	$form .= "</td></tr><tr><td>�}��</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['EDABAN']."'>";
	$form .= "</td></tr><tr><td>���ԁE�Č���</td><td>";
	$form .= "<input type = 'text' class = 'readOnly' size = 40 readonly value = '".$result_array['PJNAME']."'>";
	$form .= "</td></tr><tr><td>���z</td><td>";
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
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          �萔          //
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
	//          �ϐ�          //
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
	//      �莞�`�F�b�N      //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	for($o=0; $o < count($pjid); $o++)
        {
            //�v���W�F�N�g�̊J�n���ƏI�����擾
            $sql = "SELECT MIN(SAGYOUDATE),MAX(SAGYOUDATE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                    ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                    ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$o]." order by SAGYOUDATE ;";

            $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
            $result_row = $result->fetch_array(MYSQLI_ASSOC);
            $start = $result_row['MIN(SAGYOUDATE)'];
            $end =  $result_row['MAX(SAGYOUDATE)'];

            //�v���W�F�N�g�̍�ƎЈ��擾
            $sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                    ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                    ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$o]." order by 4CODE ;";
            $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $syainArray[$syaincnt] = $result_row['4CODE'];
                $syaincnt++;
            }

            //�Ј����Ƃɒ莞�`�F�b�N
            for($s = 0; $s < count($syainArray); $s++)
            {
                //�Ј����ς�邲�Ƃ�before��teizi��������
                $before = "";
                $teizi = 0;

                $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                        ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                        ."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '".$start."' AND '".$end."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";

                $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
                                //�莞�G���[//
                                $errrecname = $result_row['STAFFNAME'];
                                $errrecdate = $result_row['SAGYOUDATE'];
                                $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                $error[$errorcnt]['KOUTEINAME'] = "";
                                $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
                                $errorcnt++;
                            }
                        }
                        else
                        {
                            //���t���ς�邲�Ƃ�teizi��������
                            $teizi = 0;
                            $teizi += $result_row['TEIZITIME'];
                            if($teizi > $teijitime)
                            {
                                $checkflg = true;
                                //�莞�G���[//
                                $errrecname = $result_row['STAFFNAME'];
                                $errrecdate = $result_row['SAGYOUDATE'];
                                $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                $error[$errorcnt]['KOUTEINAME'] = "";
                                $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
                            //�莞�G���[//
                            $errrecname = $result_row['STAFFNAME'];
                            $errrecdate = $result_row['SAGYOUDATE'];
                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                            $error[$errorcnt]['KOUTEINAME'] = "";
                            $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
                            $errorcnt++;
                        }
                    }
                    $before = $result_row['SAGYOUDATE'];
                }
            }

            //$_SESSION['error'];
            //------------------------//
            //      �I���o�^����      //
            //------------------------//

            if(!$checkflg)
            {
                //�Y���v���W�F�N�g($pjid)��I��
                $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                        ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                        ."LEFT JOIN kouteiinfo USING(3CODE) WHERE projectditealinfo.5CODE = ".$pjid[$o]." order by SAGYOUDATE ;";
                $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    //�Ј��ʃv���W�F�N�g�R�[�h(6CODE)���Ƃɑ������z��Ɋi�[
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
                    //$key(=6CODE)���ς�邲�Ƃɏ�����
                    $teizi = 0;
                    $zangyou = 0;
                    unset($before);
                    //���ю��Ԍv�Z
                    for($i = 0 ; $i < count($time[$key]) ; $i++)
                    {
                        $teizi += $time[$key][$i]['TEIZITIME'];
                        $zangyou += $time[$key][$i]['ZANGYOUTIME'];
                    }
                    //�I��PJ�o�^
                    $pjnum = $time[$key][0]['PROJECTNUM'];
                    $pjeda = $time[$key][0]['EDABAN'];
                    $pjname = $time[$key][0]['PJNAME'];
                    $charge = $time[$key][0]['DETALECHARGE'];
                    $total = $teizi + $zangyou;
                    $performance = round($charge/$total,3);
                    $sql_end = "INSERT INTO endpjinfo (6CODE,TEIJITIME,ZANGYOTIME,TOTALTIME,PERFORMANCE,8ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES "
                                ."(".$key.",".$teizi.",".$zangyou.",".$total.",".$performance.","."'".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".") ;";
                    $result = $con->query($sql_end) or ($judge = true);																		// �N�G�����s
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
                //�t���O���I��PJ(STAT=2)�ɍX�V
                $sql_update = "UPDATE projectinfo SET  5ENDDATE = '".$nowdate."' , 5PJSTAT = '2' WHERE 5CODE = ".$pjid[$o]." ;";
                $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }

                $upcode6 = substr($upcode6, 0, -1);
                $sql_update = "UPDATE projectditealinfo SET 6ENDDATE = '".$nowdate."' , 6PJSTAT = '2' WHERE 6CODE IN (".$upcode6.");";
                $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                $sql_update = "UPDATE progressinfo SET 7ENDDATE = '".$nowdate."' , 7PJSTAT = '2' WHERE 6CODE IN (".$upcode6.");";
                $result = $con->query($sql_update) or ($judge = true);																		// �N�G�����s
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
            }
            if(!$checkflg)
            {
                $message = '����';
            }
            else
            {
                $message = '�G���[';
            }
        }
	return($message);
}

/************************************************************************************************************
��������(�v���W�F�N�g�Ǘ��V�X�e��)
function getuji($month,$period,$kubun)

����1		$month						�����Ώی�
����2		$period 					��
����3		$kubun						0:�ʏ폈��	1:�N������

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function getuji($month,$period){
	
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
	$filename = $_SESSION['filename'];
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	
	//------------------------//
	//          �ϐ�          //
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
	//        ��������        //
	//------------------------//
	
	//�����ϔ���
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "SELECT * FROM endmonthinfo WHERE PERIOD = ".$period." AND MONTH = ".$month.";";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows > 0)
	{
		$endjudge = true;
	}
	if(!$endjudge)
	{
		//�w����ԓ��ɓo�^����Ă���Ј��R�[�h�擾
		$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
				.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$syainArray[$syaincnt] = $result_row['4CODE'];
			$syaincnt++;
		}
		
		//�莞�`�F�b�N
		for($s = 0; $s < count($syainArray); $s++)
		{
			//������
			$before = "";
			$teizi = 0;
			$zangyou = 0;
			
			//�Ј��R�[�h�Ɠ��t�������ɍ�Ɠ����őI��
			$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
			$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
							//�莞�G���[//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
						}
					}
					else
					{
						//���t���ς�邲�Ƃ�teizi��������
						$teizi = 0;
						$teizi += $result_row['TEIZITIME'];
						if($teizi > $teijitime)
						{
							$checkflg = true;
							//�莞�G���[//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
						}
					}
				}
				else
				{
					$teizi += $result_row['TEIZITIME'];
					if($teizi > $teijitime)
						{
							$checkflg = true;
							//�莞�G���[//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
						}
				}
				$before = $result_row['SAGYOUDATE'];
			}
		}
		
		//���ьv�Z
		if(!$checkflg)
		{
			//�w����Ԓ��̃��R�[�h����Ɠ����ɑI��
			$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$Month."-1' AND '".$year."-".$Month."-".$lastday."' ORDER BY SAGYOUDATE;";
			$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
				$checkflg = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				//�Ј��ʃv���W�F�N�g�R�[�h(6CODE)���Ƃɑ��z��o�^
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
				//������
				$teizi = 0;
				$zangyou = 0;
//				$teizicheck = 0;
				unset($before);
//				$checkkouteiarray = array();
				
				//�o�^�f�[�^�i�[
				$insertArray[$cnt]['4CODE'] = $time[$key][0]['4CODE'];
				$insertArray[$cnt]['5CODE'] = $time[$key][0]['5CODE'];
				$insertArray[$cnt]['PROJECTNUM'] = $time[$key][0]['PROJECTNUM'];
				$insertArray[$cnt]['EDABAN'] = $time[$key][0]['EDABAN'];
				$insertArray[$cnt]['PJNAME'] = $time[$key][0]['PJNAME'];
				//�Ј��ʃv���W�F�N�g�R�[�h���ƂɎ��ьv�Z
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
								//�莞�G���[//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
							}
							if(array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray) !== FALSE)
							{
								$checkflg = true;
								//���ꃌ�R�[�h�G���[//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = $time[$key][$i]['KOUTEINAME'];
								$error[$errorcnt]['GENIN'] = "����H���̃��R�[�h�����݂��܂��B";
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
								//�莞�G���[//
								$errrecname = $time[$key][$i]['STAFFNAME'];
								$errrecdate = $time[$key][$i]['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
							//�莞�G���[//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
						}
					}
*/					//�ꃕ�����̎��уf�[�^�v�Z
					$teizi += $time[$key][$i]['TEIZITIME'];
					$zangyou += $time[$key][$i]['ZANGYOUTIME'];
					$before = $time[$key][$i]['SAGYOUDATE'];
//					$checkkouteiarray[] = $time[$key][$i]['KOUTEINAME'];
				}
				//�ꃕ�����̎��уf�[�^�쐬
				$insertArray[$cnt]['TEIZI'] = $teizi;
				$insertArray[$cnt]['ZANGYOU'] = $zangyou;
				$cnt++;
			}
		}
		//���Ԏ��ѓo�^
		if(!$checkflg)
		{
			for($i = 0; $i < count($insertArray); $i++)
			{
				$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
							." (".$insertArray[$i]['4CODE'].",".$insertArray[$i]['5CODE'].",'".$period."','".$month."','�莞����','".$insertArray[$i]['TEIZI']."'"
							.",NOW(),"."'".$insertArray[$i]['PROJECTNUM']."'".","."'".$insertArray[$i]['EDABAN']."'".","."'".$insertArray[$i]['PJNAME']."'".");";
				$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
							." (".$insertArray[$i]['4CODE'].",".$insertArray[$i]['5CODE'].",'".$period."','".$month."','�c�Ǝ���','".$insertArray[$i]['ZANGYOU']."'"
							.",NOW(),"."'".$insertArray[$i]['PROJECTNUM']."'".","."'".$insertArray[$i]['EDABAN']."'".","."'".$insertArray[$i]['PJNAME']."'".");";
				$result = $con->query($sql_month) or ($judge = true);																		// �N�G�����s
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
		$message = '���ɏ����ς̊����ł��B';
	}
	if(!$checkflg)
	{
		//�����ϊ��ԓo�^
		$year = getyear($month,$period);
		$sql = "INSERT INTO endmonthinfo (PERIOD,YEAR,MONTH) VALUE ('".$period."','".$year."','".$month."');";
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		deletedate_change();
		return('��������');
	}
	else
	{
		if(!empty($error))
		{
			$_SESSION['error'] = $error;
			$message = '���������ɂăG���[���������܂����B';
		}
		return($message);
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
	$period = $year - $startyear + 1;
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
function deletepjall()

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
/*	$sql = "DELETE FROM projectinfo WHERE 5CODE = '".$delkey."' ;";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
*/
	$ssql = "SELECT DISTINCT(7CODE) FROM progressinfo WHERE 6CODE IN (SELECT 6CODE FROM projectditealinfo WHERE 5CODE = '".$delkey."' ) ;";
	error_log($ssql,0);
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
/************************************************************************************************************
�I��PJ�L�����Z������(�v���W�F�N�g�Ǘ��V�X�e��)
function pjagain($post)

����1		$post						�Ώ�

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function pjagain($post){
	
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
	$filename = $_SESSION['filename'];
	$pjid = $post['5CODE'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	//------------------------//
	//          �ϐ�          //
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
	//        ��������        //
	//------------------------//
	$con = dbconect();	
//	$sql = "SELECT * FROM endpjinfo;";
	$sql = "SELECT * FROM endpjinfo LEFT JOIN projectditealinfo USING (6CODE) LEFT JOIN syaininfo USING (4CODE)"
			." RIGHT JOIN projectinfo USING (5CODE) LEFT JOIN progressinfo USING (6CODE) LEFT JOIN projectnuminfo USING (1CODE) LEFT JOIN edabaninfo USING (2CODE)"
			." WHERE projectinfo.5CODE = ".$pjid.";";			// db�ڑ��֐����s
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
			//endpjinfo����폜
			$sql_delete =  "DELETE FROM endpjinfo WHERE 8CODE = ".$code8." ;";
			$result_delete = $con->query($sql_delete) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}
		if($code5 != $result_row['5CODE'])
		{
			$code5 = $result_row['5CODE'];
			//�t���O��1�i�������j�ɕύX
			$sql5 = "UPDATE projectinfo SET  5ENDDATE = NULL , 5PJSTAT = '1' WHERE 5CODE = ".$code5.";";
			$result5 = $con->query($sql5) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}

		if($code6 != $result_row['6CODE'])
		{
			$code6 = $result_row['6CODE'];
			//�t���O��1�i�������j�ɕύX
			$sql6 = "UPDATE projectditealinfo SET  6ENDDATE = NULL , 6PJSTAT = '1' WHERE 6CODE = ".$code6.";";
			$result6 = $con->query($sql6) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			$sql7 = "UPDATE progressinfo SET  7ENDDATE = NULL , 7PJSTAT = '1' WHERE 6CODE = ".$code6.";";
			$result7 = $con->query($sql7) or ($judge = true);																		// �N�G�����s
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}
	}
	return("�I��PJ�L�����Z���������������܂����B");
}
/************************************************************************************************************
�N������(�v���W�F�N�g�Ǘ��V�X�e��)
function nenji($period)

����1		$month						�����Ώی�
����2		$period 					��
����3		$kubun						0:�ʏ폈��	1:�N������

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function nenji($period){
	
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
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge = false;

	//------------------------//
	//        ��������        //
	//------------------------//
	
	$con = dbconect();
	$sql = "INSERT INTO endperiodinfo (PERIOD) VALUE ('".$period."');";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	deletedate_change();

}

/************************************************************************************************************
�N������(�v���W�F�N�g�Ǘ��V�X�e��)
function nenjiCheck($period)

����1		$month						�����Ώی�
����2		$period 					��
����3		$kubun						0:�ʏ폈��	1:�N������

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function nenjiCheck($period){
	
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
	$filename = $_SESSION['filename'];
	$teijitime = (float)$item_ini['settime']['teijitime'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	
	//------------------------//
	//          �ϐ�          //
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
	//        ��������        //
	//------------------------//
	
	$con = dbconect();	
	
	//�N���`�F�b�N
	$sql = "SELECT * FROM endperiodinfo WHERE PERIOD = '".$period."';";
	$result = $con->query($sql);
	$rows = $result->num_rows;
	if($rows == 0)
	{
		//�����`�F�b�N
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
				//�������s���Ă��Ȃ������W�v
				$error_month .= $arrayMonth[$i].',';
			}
			$monthjudge = false;
		}
		$_SESSION['errormonth'] = rtrim($error_month,',');
		$count = 0;
		//PJ�`�F�b�N
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
		$_SESSION['nenzi']['error'] = $period."���͊��ɔN�������ςł��B";
	}
}

/************************************************************************************************************
PJ�I������(�v���W�F�N�g�Ǘ��V�X�e��)
function pjCheck($post)

����1		$post						�폜�Ώ�

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function pjCheck($post){
	
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
		$pjid = explode(",",$post["5CODE"]);					//�v���W�F�N�g�I��
	}
	else
	{
		$pjid = explode(",",$post);							//�v���W�F�N�g�폜
	}
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	//------------------------//
	//          �ϐ�          //
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
	//        ��������        //
	//------------------------//
	
	$con = dbconect();
		//�Y���v���W�F�N�g($pjid)��I��
	if($filename == 'getuzi_5')
	{
		//���쌎�ȍ~�͌����֎~
//		$now = $nowyr'-'$nowmn;
//		$pos = $year."-".$month;
//		if()
		if(!$checkflg)
		{
			//�w����ԓ��ɓo�^����Ă���Ј��R�[�h�擾
			$sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
					."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
					."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
					.$year."-".$month."-01' AND '".$year."-".$month."-".$lastday."' ORDER BY syaininfo.4CODE;";
			$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
			//�Ј����Ƃɒ莞�`�F�b�N
			for($s = 0; $s < count($syainArray); $s++)
			{
				//�Ј����ς�邲�Ƃ�before��teizi��������
				$before = "";
				$teizi = 0;
				
				//�Ј��R�[�h�Ɠ��t�������ɍ�Ɠ����őI��
				$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
						."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
						."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '"
						.$year."-".$month."-1' AND '".$year."-".$month."-".$lastday."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";
				$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
								//�莞�G���[//
								$errrecname = $result_row['STAFFNAME'];
								$errrecdate = $result_row['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
								$errorcnt++;
							}
						}
						else
						{
							//���t���ς�邲�Ƃ�teizi��������
							$teizi = 0;
							$teizi += $result_row['TEIZITIME'];
							if($teizi > $teijitime)
							{
								$checkflg = true;
								//�莞�G���[//
								$errrecname = $result_row['STAFFNAME'];
								$errrecdate = $result_row['SAGYOUDATE'];
								$error[$errorcnt]['STAFFNAME'] = $errrecname;
								$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
								$error[$errorcnt]['KOUTEINAME'] = "";
								$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
							//�莞�G���[//
							$errrecname = $result_row['STAFFNAME'];
							$errrecdate = $result_row['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
                    //�i�����̗L��
                    $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                            ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                            ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                            ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by SAGYOUDATE ;";
                    $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
                    if($judge)
                    {
                        error_log($con->error,0);
                        $judge = false;
                    }
                    //�i�����̗L�����m�F
                    $rows = $result->num_rows;
                    if($rows > 0)
                    {
                        //�v���W�F�N�g�̊J�n���ƏI�����擾
                        $sql = "SELECT MIN(SAGYOUDATE),MAX(SAGYOUDATE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                                ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                                ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by SAGYOUDATE ;";
                        $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
                        if($judge)
                        {
                            error_log($con->error,0);
                            $judge = false;
                        }
                        $result_row = $result->fetch_array(MYSQLI_ASSOC);
                        $start = $result_row['MIN(SAGYOUDATE)'];
                        $end =  $result_row['MAX(SAGYOUDATE)'];

                        //�v���W�F�N�g�̍�ƎЈ��擾
                        $sql = "SELECT DISTINCT(4CODE) FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) "
                                ."LEFT JOIN projectinfo USING(5CODE) LEFT JOIN projectnuminfo USING(1CODE) "
                                ."LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                ."LEFT JOIN kouteiinfo USING(3CODE) WHERE 5CODE = ".$pjid[$i]." order by 4CODE ;";

                        $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

                        //�Ј����Ƃɒ莞�`�F�b�N
                        for($s = 0; $s < count($syainArray); $s++)
                        {
                            //�Ј����ς�邲�Ƃ�before��teizi��������
                            $before = "";
                            $teizi = 0;

                            $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
                                    ."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
                                    ."LEFT JOIN kouteiinfo USING(3CODE) WHERE progressinfo.SAGYOUDATE BETWEEN '".$start."' AND '".$end."' AND syaininfo.4CODE = ".$syainArray[$s]." ORDER BY SAGYOUDATE;";

                            $result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
                                            //�莞�G���[//
                                            $errrecname = $result_row['STAFFNAME'];
                                            $errrecdate = $result_row['SAGYOUDATE'];
                                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                            $error[$errorcnt]['KOUTEINAME'] = "";
                                            $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
                                            $errorcnt++;
                                        }
                                    }
                                    else
                                    {
                                        //���t���ς�邲�Ƃ�teizi��������
                                        $teizi = 0;
                                        $teizi += $result_row['TEIZITIME'];
                                        if($teizi > $teijitime)
                                        {
                                            $checkflg = true;
                                            //�莞�G���[//
                                            $errrecname = $result_row['STAFFNAME'];
                                            $errrecdate = $result_row['SAGYOUDATE'];
                                            $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                            $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                            $error[$errorcnt]['KOUTEINAME'] = "";
                                            $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
                                        //�莞�G���[//
                                        $errrecname = $result_row['STAFFNAME'];
                                        $errrecdate = $result_row['SAGYOUDATE'];
                                        $error[$errorcnt]['STAFFNAME'] = $errrecname;
                                        $error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
                                        $error[$errorcnt]['KOUTEINAME'] = "";
                                        $error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
                        $_SESSION['message'][] = "<a class = 'error'>�i����񂪓o�^����Ă��܂���B</a>";
                    }
                }
	}

/*
		$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
				."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) "
				."LEFT JOIN kouteiinfo USING(3CODE) WHERE projectditealinfo.5CODE = ".$pjid." order by SAGYOUDATE ;";
			$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		
		while($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			//�Ј��ʃv���W�F�N�g�R�[�h(6CODE)���Ƃɑ������z��Ɋi�[
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
			//6CODE(�Ј��ʃv���W�F�N�g�R�[�h)���Ƃɏ�����
			$teizi = 0;
			unset($before);
			$checkkouteiarray = array();
			//�Ј��ʃv���W�F�N�g�R�[�h���ƂɃG���[�`�F�b�N
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
							//�莞�G���[//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
							$errorcnt++;
						}
						if(array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray) !== FALSE)
						{
							$checkflg = true;
							//���ꃌ�R�[�h�G���[//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$checkstack = array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray);
							$checkkouteiarray[$checkstack] = '';
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = $time[$key][$i]['KOUTEINAME'];
							$error[$errorcnt]['GENIN'] = "����H���̃��R�[�h�����݂��܂��B";
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
							//�莞�G���[//
							$errrecname = $time[$key][$i]['STAFFNAME'];
							$errrecdate = $time[$key][$i]['SAGYOUDATE'];
							$error[$errorcnt]['STAFFNAME'] = $errrecname;
							$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
							$error[$errorcnt]['KOUTEINAME'] = "";
							$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
						//�莞�G���[//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$error[$errorcnt]['STAFFNAME'] = $errrecname;
						$error[$errorcnt]['SAGYOUDATE'] = $errrecdate;
						$error[$errorcnt]['KOUTEINAME'] = "";
						$error[$errorcnt]['GENIN'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
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
PJ�I������(�v���W�F�N�g�Ǘ��V�X�e��)
function makeList_error($post)

����1		$post						�폜�Ώ�

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function makeList_error($post){
	
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
	$filename = $_SESSION['filename'];
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$list_html = "";
	
	//------------------------//
	//        ��������        //
	//------------------------//
	if($filename == 'pjend_5' || $filename == 'getuzi_5')
	{
		$list_html .= "<table class ='list'><thead><tr>";
		$list_html .="<th><a class ='head'>No</a></th>";
		$list_html .="<th><a class ='head'>���t</a></th>";
		$list_html .="<th><a class ='head'>��Ǝ�</a></th>";
		$list_html .="<th><a class ='head'>�H��</a></th>";
		$list_html .="<th><a class ='head'>����</a></th>";
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
		$list_html .="<th><a class ='head'>�v���W�F�N�g�R�[�h</a></th>";
		$list_html .="<th><a class ='head'>�}�ԃR�[�h</a></th>";
		$list_html .="<th><a class ='head'>���ԁE�Č���</a></th>";
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
PJ�I������(�v���W�F�N�g�Ǘ��V�X�e��)
function make_pjdel($post)

����1		$post						�폜�Ώ�

�߂�l		$form						���[�_���ɕ\�����X�ghtml
************************************************************************************************************/
function make_pjdel($post){
	
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
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$list_html = "";
	
	//------------------------//
	//        ��������        //
	//------------------------//
	
	$con = dbconect();																									// db�ڑ��֐����s
	$sql = "SELECT PROJECTNUM,EDABAN,PJNAME FROM projectinfo LEFT JOIN  projectnuminfo USING(1CODE) LEFT JOIN edabaninfo USING(2CODE) WHERE 5CODE = '".$post."';";
	$result = $con->query($sql);																		// �N�G�����s
	$result_row = $result->fetch_array(MYSQLI_ASSOC);
	$pjnum = $result_row['PROJECTNUM'];
	$edaban = $result_row['EDABAN'];
	$pjname = $result_row['PJNAME'];

	$list_html .= "<table><tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>�v���W�F�N�g�R�[�h</a></td><td class = 'two'><a class = 'comp' >".$pjnum."</a></td></tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>�}�ԃR�[�h</a></td><td class = 'two'><a class = 'comp' >".$edaban."</a></td></tr>";
	$list_html .= "<td class = 'space'></td><td class = 'one'><a class = 'itemname'>���ԁE�Č���</a></td><td class = 'two'><a class = 'comp' >".$pjname."</a></td></tr>";
	$list_html .= "</table>";
	return($list_html);
}
/************************************************************************************************************
function pjdelete($post)

����1		$post								���͓��e
����2		$data								�o�^�t�@�C�����e

�߂�l	�Ȃ�
************************************************************************************************************/
function pjdelete($post){
	
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
	$sql = "";
	$judge = false;
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$id = $post['id'];
	//�v���W�F�N�g�폜
	$sql = "DELETE FROM projectinfo WHERE 5CODE = ".$id.";";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
		$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
	}
	//�Ј��ʃv���W�F�N�g�폜
	$sql = "DELETE FROM projectditealinfo WHERE 5CODE = ".$id.";";
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	
}
/************************************************************************************************************
function endMonth()

����1		$post							�o�^�t�H�[�����͒l
����2		$tablenum						�e�[�u���ԍ�
����3		$type							1:insert 2:edit 3:delete

�߂�l		$errorinfo						���o�^�m�F����
************************************************************************************************************/
function endMonth(){
	
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

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sql = "";
	$judge = false;
	$endmonth = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
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

����1		$post							�o�^�t�H�[�����͒l
����2		$tablenum						�e�[�u���ԍ�
����3		$type							1:insert 2:edit 3:delete

�߂�l		$errorinfo						���o�^�m�F����
************************************************************************************************************/
function makeEndMonth(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$endmonth_ini = parse_ini_file('./ini/endmonth.ini', true);
	require_once ("f_Form.php");
	require_once ("f_DB.php");																							// DB�֐��Ăяo������
	require_once ("f_SQL.php");																							// SQL�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$date = date_create('NOW');
	$nowyr = date_format($date, "Y");
	$nowmn = date_format($date, "n");
	$nowpd = getperiod($nowmn,$nowyr);
	$before = $endmonth_ini['endmonth']['before_period'];
	$start = $nowpd - $before + 1 ;

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$sql = "";
	$judge = false;
	$endmonth = array();
	$listhtml = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
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
	$listhtml .= "<table><td width='140''>�@</td><td width='20' bgcolor='#f4a460'>�@</td><td>���������</td></tr></table>";
	$listhtml .= "</td></tr><tr><td><table class ='list'><thead><tr>";
	$listhtml .= "<th><a class ='head'>��</a></th>";
	$listhtml .= "<th colspan='12'><a class ='head'>��</a></th></tr></thead>";
	$listhtml .= "<tbody>";	
	
	for($i = 0; $i < $before; $i++)
	{
		//�����쐬
		$listhtml .= "<tr><td class='center' bgcolor='#1E90FF'><a class ='body'>".($start+$i)."</a></td>";
		
		//12�����\�쐬
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

//�d���`�F�b�N�p
function edaget()


����	

�߂�l	
************************************************************************************************************/
	
function pjget(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$sql = "SELECT * FROM projectnuminfo ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

//�d���`�F�b�N�p
function edaget()


����	

�߂�l	
************************************************************************************************************/
	
function edaget(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$sql = "SELECT * FROM edabaninfo ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

//�d���`�F�b�N�p
function kouget()


����	

�߂�l	
************************************************************************************************************/
	
function kouget(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$sql = "SELECT * FROM kouteiinfo ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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

//�d���`�F�b�N�p
function edaget()


����	

�߂�l	
************************************************************************************************************/
	
function syaget(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$sql = "SELECT * FROM syaininfo ;";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$judge =false;
	$countnum = 0;
	//------------------------//
	//        ��������        //
	//------------------------//
	$con = dbconect();																									// db�ڑ��֐����s
	$cntrow = 0;
	$lisstr = "";
	
	//$listrow = new array();
	
	$result = $con->query($sql) or ($judge = true);																		// �N�G�����s
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
