<?php



/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////











/************************************************************************************************************
function makeformInsert_set($post,$out_column,$isReadOnly,$formName)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l
����	$out_column		���̓`�F�b�N(php��)�ŕs�J�����ԍ�
����	$isReadOnly		���[�h�I�����[��ݒ肷�邩
����	$formName		�t�H�[���^�O��name

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function makeformInsert_set($post,$out_column,$isReadOnly,$formName){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini�Ăяo��
	require_once 'f_Form.php';																			// f_From�֐��Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$out_column = explode(',',$out_column);																// ���̓`�F�b�N(php��)�ŕs�J�����ԍ��z��
	$filename = $_SESSION['filename'];																	// �y�[�WID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// �o�^�J�����ꗗ(csv)
	$columns_array = explode(',',$columns);																// �o�^�J�����ꗗ(�z��)
	$isMasterInsert = 0;      // $form_ini[$filename]['isMasterInsert'];								// �}�X�^�[�e�[�u���̓o�^�������邩 0:�s�� 1:����
	$maintable = $form_ini[$filename]['use_maintable_num'];

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$istable = false;																					// �o�^�J�������e�[�u����
	$table_columns ="";																					// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(csv)
	$table_columns_array = array();																		// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(�z��)
	$loop_count = 0;																					// $table_columns_array�̔z��
	$ismaster = false;																					// �e�[�u�����}�X�^�[�e�[�u����
	$islist = false;																					// �e�[�u�������X�g�e�[�u����
	$Colum = "";																						// �쐬�Ώۃt�H�[���̃J�����ԍ�
	$form_format_type = "";																				// �쐬�Ώۃt�H�[���̃^�C�v form.ini 'form_type'
	$form_before_year = "";																				// �쐬�Ώۃt�H�[�� ���t�v���_�E���̊J�n�N form.ini 'before_year'
	$form_after_year = "";																				// �쐬�Ώۃt�H�[�� ���t�v���_�E���̏I���N form.ini 'after_year'
	$form_num = "";																						// �쐬�Ώۃt�H�[�� �t�H�[���� form.ini 'form_num'
	$form_type = "";																					// �쐬�Ώۃt�H�[�� �t�H�[���^�C�v form.ini 'form_type'
	$form_item_name = "";																				// �쐬�Ώۃt�H�[�� �A�C�e���� form.ini 'form_item_name'
	$form_size = "";																					// �쐬�Ώۃt�H�[�� �T�C�Y form.ini 'form*_size'
	$form_value = "";																					// �쐬�Ώۃt�H�[�� value form.ini 'form*_value'
	$form_format = "";																					// �쐬�Ώۃt�H�[�� ���͉\���� form.ini 'form*_format'
	$form_length = "";																					// �쐬�Ώۃt�H�[�� ���͉\���� form.ini 'form*_length'
	$form_isJust = "";																					// �쐬�Ώۃt�H�[�� ���͉\���� form.ini 'form*_length'
	$form_delimiter = "";																				// �쐬�Ώۃt�H�[�� ��؂蕶�� form.ini 'form*_length'
	$form_id = "";																						// �쐬�Ώۃt�H�[�� id
	$form_name = "";																					// �쐬�Ώۃt�H�[�� name
	$form_class = "";																					// �쐬�Ώۃt�H�[�� class
	$insert_str = "";																					// ���̓t�H�[��html �߂�l
	$isonce = false;																					// ���̓t�H�[���쐬��1�e�[�u������1��ڂ�
	$input_type = "";																					// input�^�O �^�C�v textbpx or file
	$check_column_str = "";																				// ���̓`�F�b�N�Ώۃt�H�[��name(csv)
	$isnotnull = 0;																						// ���͕K�{���ڔ��f
	$notnull_column_str = "";																			// ���͕K�{�t�H�[���e�[�u���ԍ�(csv)
	$notnull_type_str = "";																				// ���͕K�{�t�H�[���e�[�u���ԍ�(csv)
	$check_js = "";																						// ���̓`�F�b�Njavascripr �Ăяo��html��
	$isout = false;																						// �쐬�Ώۃt�H�[�������̓`�F�b�N(php��)�s�J������
	$keyarray = array();																				// ����$post �́@Key�z��
	$list_id = array();																					// ���X�g�e�[�u���̌J��Ԃ�ID�z��
	$idcount = 0;																						// ���X�g�e�[�u���̌J��Ԃ�ID�z��̔z��ԍ�
	$list_loop = 0;																						// ���X�g�e�[�u���̌J��Ԃ���
	$max_over = -1;																						// ���X�g�e�[�u���̌J��Ԃ��ő吔
	$table_title = "";																					// �e�[�u���^�C�g��
	$ReadOnly = "";																						// ReadOnly����
	$hidden_value = "";																					// hidden �t�H�[����value�l
	$error ="";
	$ReadOnlyBak ="";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// ���̓t�H�[��html��
	for($i = 0 ; $i < count($columns_array) ; $i++)														// �o�^�J�����������[�v
	{
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// �o�^�J�������e�[�u���ԍ���
		{
			$istable = true;																			// 
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			if($form_ini[$columns_array[$i]]['table_type'] == 1)
			{
				$ismaster = true;
			}
			else if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$islist = true;
				$islistform = true;
				$isonce = true;
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
		}
//		$list_loop = count($list_id) + 1;
		$list_loop = 1;
		$idcount = 0;
		if($ismaster && $columns_array[$i] != $maintable )
		{
			$insert_str .= "<tr><td class = 'space'></td><td class ='one'></td>
								<td class ='two'>";
			$insert_str .= '<input type="button" value="'.$table_title.'�I��" 
				onclick="popup_modal(\''.$columns_array[$i].'\')">';
			if($isMasterInsert == 1)
			{
				$insert_str .= '<input type="button" value="'.$table_title.'�o�^" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
			}
			if(isset($post[$columns_array[$i].'CODE']))
			{
				$hidden_value = $post[$columns_array[$i].'CODE'];
			}
			else
			{
				$hidden_value ="";
			}
			$insert_str .= "<input type ='hidden' name ='".$columns_array[$i]
							."CODE'  value ='".$hidden_value."' >";
			$insert_str .= "</td>";
			
			for($out_counter = 0; $out_counter < count($out_column) ; $out_counter++)
			{
				if($out_column == "")
				{
					break;
				}
				if(isset($form_ini[$out_column[$out_counter]]['column']))
				{
					if($form_ini[$out_column[$out_counter]]['column'] == $columns_array[$i].'CODE')
					{
						$tablename_out = $form_ini[$columns_array[$i]]['table_title'];
						$insert_str .= "<td><a class = 'error'>".$tablename_out."���͊��ɓo�^����Ă��܂��B</a></td>";
					}
				}
			}
			
			$insert_str .= "</tr>";
			if($isReadOnly == true)
			{
				$ReadOnly = "readOnly";
			}
			$ReadOnlyBak = $ReadOnly;
		}
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				if($islist)
				{
					$insert_str .="<tr id = '".$columns_array[$i]."'>";
					$insert_str .="<td class = 'space'></td><td class ='one'>";
				}
				else
				{
					$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
				}
				$form_item_name = $form_ini[$Colum]['item_name'];
				$insert_str .= "<a class = 'itemname'>";
				$insert_str .= $form_item_name;
				$insert_str .= "</a></td>";
				
				
				for($outcounter1 = 0 ; $outcounter1 < count($out_column) ; $outcounter1++)
				{
					if(strstr($out_column[$outcounter1], $Colum))
					{
						$out = explode(',',$out_column[$outcounter1]);
						for($outcounter2 = 0 ; $outcounter2 < count($out) ; $outcounter2++)
						{
							$error .= $form_ini[$out[$outcounter2]]['item_name'].",";
						}
						$error = substr($error,0,-1);
						$isout = true;
					}
				}
				
				$form_format_type = $form_ini[$Colum]['form_type'];
				if($form_ini[$Colum]['isnotnull'] == 1)
				{
					$notnull_column_str .= $Colum.",";
					$notnull_type_str .= $form_format_type.",";
					$isnotnull = 1;
					if($islist)
					{
						$isnotnull = 0;
					}
				}
				else
				{
					$isnotnull = 0;
				}
				$insert_str .= "<td class = 'two'>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
						$form_isJust = $form_ini[$Colum]['isJust'];
						$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
						if($list_count == 0)
						{
							$form_id = "form_".$Colum."_".($k);
							$form_name = "form_".$Colum."_".($k);
						}
						else
						{
							$form_id = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
							$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
						}
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = $form_ini[$Colum]['form'.($k + 1).'_value'];
						}
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".$isnotnull."~".$form_isJust.",";
						
						
						if($filename == 'PROGRESSINFO_1' && $form_name == 'form_706_0')
						{
							if(empty($form_value))
							{
								$form_value = 0;
							}
						}
						
						
						
						
						
						if($filename == 'GENBAINFO_1' && $form_name == 'form_402_0')
						{
							$ReadOnly = "readOnly";
						}
						
						
						
						if($filename == 'EDABANINFO_1' && ($form_name == 'form_102_0' || $form_name == 'form_103_0'))
						{
							$ReadOnly = "readOnly";
						}
						
						
						
						
						
						
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','
										.$form_format.','.$isnotnull.','.$form_isJust.')"';
						}
						$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
										.$form_name.'" id = "'.$form_id.'" 
										class = "'.$ReadOnly.'" value = "'.$form_value.
										'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' >';
						
						
						
						
						
						
						
						
						if($filename == 'GENBAINFO_1' && $form_name == 'form_402_0')
						{
							$ReadOnly = $ReadOnlyBak;
							$insert_str .= '    <input type ="button" value ="����R�[�h�擾" onclick="saiban();">';
						}
						
						
						
						
						
						
						
						
						
						
					}
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a>";
						$isout = false;
						$error = "";
						$insert_str .="</td>";
					}
					else
					{
						$insert_str .="</td>";
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					$insert_str.= pulldown_set($form_format_type,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
					
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a>";
						$insert_str .="</td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
					
				}
				else
				{
					$form_before_year = $form_ini[$Colum]['before_year'];
					$form_after_year = $form_ini[$Colum]['after_year'];
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					$insert_str.= pulldownDate_set($form_format_type,$form_before_year,
													$form_after_year,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a></td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
				}
				$insert_str .= "</tr>";
			}
			$islist = false;
			$ReadOnly = "";
		}
		$list_id = array();
		$istable = false;
		$ismaster = false;
	}
	if($filename != 'nenzi_5')
	{
		$insert_str .= "</table>";
	}
	$check_column_str = rtrim($check_column_str,',');
	$notnull_column_str = rtrim($notnull_column_str,',');
	$notnull_type_str = rtrim($notnull_type_str,',');
	$_SESSION['check_column'] = $check_column_str;
	$_SESSION['notnullcolumns'] = $notnull_column_str;
	$_SESSION['notnulltype'] = $notnull_type_str;
	$_SESSION['max_over'] = $max_over;
	return ($insert_str);
}


/************************************************************************************************************
function pulldownDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull)

����	�Ȃ�

�߂�l	�Ȃ�
************************************************************************************************************/
function pulldownDate_set($type,$beforeyear,$afteryear,$name,$over,$post,$ReadOnly,$formName,$isnotnull){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	require_once 'f_Form.php';																			// f_From�֐��Ăяo��
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini�Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$month = date_create('NOW');
	$month = date_format($month, "n");
	$day = date_create('NOW');
	$day = date_format($day, "j");
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$y_name ="";
	$m_name ="";
	$d_name ="";
	$y_value = "";
	$m_value = "";
	$d_value = "";
	$select = "";
	$str="";
	$isSelect = false;
	$readonly = "";
	$date = array();
	$wareki = "";
	$wareki_befor = "";
	$nenngou_count = 0;
	$changeyear ="";
	$month_value = "";
	$select_year = "";
	$select_month = "";
	$start_month = 0;
	$end_month = 12;
	$start_day = 0;
	$end_day = 31;
	$m_text = "";
	$d_text = "";

	

	//------------------------//
	//          ����          //
	//------------------------//
	if($over != "")
	{
		$y_name = $name.'_0_'.$over;
		$m_name = $name.'_1_'.$over;
		$d_name = $name.'_2_'.$over;
	}
	else
	{
		$y_name = $name.'_0';
		$m_name = $name.'_1';
		$d_name = $name.'_2';
	}
	if(isset($post[$y_name]))
	{
		$y_value = $post[$y_name];
	}
	else{
		$today = explode('/',date("Y/m/d"));
		$y_value = $today[0];
	}
	if(isset($post[$m_name]))
	{
		$m_value = $post[$m_name];
	}
	else{
		$today = explode('/',date("Y/m/d"));
		$m_value = $today[1];
	}
	if(isset($post[$d_name]))
	{
		$d_value = $post[$d_name];
	}
	else{
		$today = explode('/',date("Y/m/d"));
		$d_value = $today[2];
	}
	$month_value = rtrim($m_value,'��');
	$dayarray = array(29,31,28,31,30,31,30,31,31,30,31,30,31);
	for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
	{
		$date = explode('-',$start_array[$nenngou_count]);
		if($beforeyear >= $date[0])
		{
			if($nenngou_count != 0)
			{
				$nenngou_count--;
			}
			break;
		}
		
	}
	if ($type == 5 || $type == 6 || $type == 3 )
	{
		if($type == 3 )
		{
			$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\') "; 
					onChange="generateMonth(this.id,'.$type.',\''.$start.'\',\''.$nenngou.'\');
					notnullcheck(this.id,'.$isnotnull.');">';
		}
		else
		{
			$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');"
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
		}
		for ($countYear1=$beforeyear; $countYear1 <= $year; $countYear1++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear1);
			$wareki_befor = wareki_year_befor($countYear1);
			if(($countYear1).'-1-1' == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear1;
			}
			else if((($wareki.'�N') == $y_value 
						|| ($wareki_befor.'�N') == $y_value )&& $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear1;
			}
			else
			{
				$select = "";
			}
			if($type == 6)
			{
				$str.='<option value="'.($countYear1).'-1-1" '.$select.'>'
							.($countYear1).'</option>';
			}
			else
			{
				if(($countYear1) == $changeyear && $type == 3)
				{
					if($y_value == $wareki_befor.'�N' && $month_value <= $date[1])
					{
						$str.='<option value="'.$wareki_befor.'�N" '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'�N" >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value="'.$wareki_befor.'�N" >'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'�N" '.$select.' >'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else if(($countYear1) == $changeyear)
				{
					if($y_value == $wareki_befor.'�N')
					{
						$str.='<option value="'.$wareki_befor.'�N" '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'�N" >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value="'.$wareki_befor.'�N" >'.$wareki_befor.'</option>';
						$str.='<option value="'.$wareki.'�N" '.$select.' >'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else
				{
					$str.='<option value="'.$wareki.'�N" '.$select.' >'.$wareki.'</option>';
				}
			}
		}
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($year >= $date[0])
			{
				if($nenngou_count != 0)
				{
					$nenngou_count--;
				}
				break;
			}
		
		}
		for ($countYear2=1;$countYear2<=$afteryear;$countYear2++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear2+$year);
			$wareki_befor = wareki_year_befor($countYear2+$year);
			if(($countYear2+$year).'-1-1' == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear2+$year;
			}
			else if(($wareki.'�N') == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
			}
			else
			{
				$select = "";
			}
			if($type==6)
			{
				$str.='<option value="'.($countYear2+$year).'-1-1" '.$select.' >'
							.($countYear2+$year).'</option>';
			}
			else
			{
				$str.='<option value="'.$wareki.'�N" '.$select.'>'.$wareki.'</option>';
			}
		}
	}
	else
	{
		$str='<select id="'.$y_name.'" class ="'.$ReadOnly.'" name="'.$y_name.'" 
				onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
				onChange="generateMonth(this.id,'.$type.',\''.$start.'\',\''.$nenngou.'\') ; 
				notnullcheck(this.id,'.$isnotnull.');">';
		for ($countYear1=$beforeyear; $countYear1 <= $year; $countYear1++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear1);
			$wareki_befor = wareki_year_befor($countYear1);
			if(($countYear1) == $y_value)
			{
				$select = " selected";
				$isSelect = true;
				$readonly = "";
				$select_year = $countYear1;
			}
			else
			{
				$select = "";
			}
			if($type==2 || $type==4)
			{
				$str.='<option value="'.($countYear1).'" '.$select.'>'
							.($countYear1).'</option>';
			}
			else
			{
				if(($countYear1) == $changeyear)
				{
					if($m_value < $date[1] || ($m_value == $date[1] && $d_value < $date[2]))
					{
						$str.='<option value='.($countYear1).' '.$select.'>'.$wareki_befor.'</option>';
						$str.='<option value='.($countYear1).' >'.$wareki.'</option>';
					}
					else
					{
						$str.='<option value='.($countYear1).' >'.$wareki_befor.'</option>';
						$str.='<option value='.($countYear1).' '.$select.'>'.$wareki.'</option>';
					}
					if($nenngou_count != 0)
					{
						$nenngou_count--;
					}
				}
				else
				{
					$str.='<option value='.($countYear1).' '.$select.'>'.$wareki.'</option>';
				}
			}
		}
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($year >= $date[0])
			{
				if($nenngou_count != 0)
				{
					$nenngou_count--;
				}
				break;
			}
		
		}
		for ($countYear2=1;$countYear2<=$afteryear;$countYear2++)
		{
			$date = explode('-',$start_array[$nenngou_count]);
			$changeyear =$date[0];
			$wareki = wareki_year($countYear2+$year);
			$wareki_befor = wareki_year_befor($countYear2+$year);
			if(($countYear2+$year) == $y_value && $y_value != '')
			{
				$select = " selected";
				$isSelect = true;
				$select_year = $countYear2+$year;
			}
			else
			{
				$select = "";
			}
			if($type==2 || $type==4)
			{
				$str.='<option value='.($countYear2+$year).' '.$select.' >'
							.($countYear2+$year).'</option>';
			}
			else
			{
				$str.='<option value='.($countYear2+$year).' '.$select.'>'.$wareki.'</option>';
			}
		}
	}
	if($isSelect == false)
	{
		$str.='<option value="" selected></option>';
	}
	else
	{
		$str.='<option value="" ></option>';
		$isSelect = false;
	}
	$str.='</select>';
	$str.='�N';
	if($select_year != "")
	{
		$wareki = wareki_year($select_year);
		$wareki_befor = wareki_year_befor($select_year);
		for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
		{
			$date = explode('-',$start_array[$nenngou_count]);
			if($select_year == $date[0])
			{
				if($wareki."�N" == $y_value)
				{
					$start_month = ($date[1]-1);
				}
				else
				{
					$end_month = $date[1];
				}
				if($type == 1)
				{
					if($m_value < $date[1] ||($m_value == $date[1] && $d_value < $date[2]))
					{
						$end_month = $date[1];
					}
					else
					{
						$start_month = ($date[1]-1);
					}
				}
				else if($type == 2)
				{
					$start_month = 0;
					$end_month = 12;
				}
			}
		}
	}
	if($type!=5 && $type!=6)
	{
		if($type==1 || $type==2)
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange="generateDay(this.id,\''.$start.'\',\''.$nenngou.'\'); 
					 notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = $start_month ; $countMonth < $end_month ; $countMonth++)
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1) == $m_value && $m_value != '')
				{
					$str.='<option value='.($countMonth+1).
								' selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth+1;
				}
				else
				{
					$str.='<option value='.($countMonth+1).
								'>'.$m_text.'</option>';
				}
			}
		}
		else if($type == 3)
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'"
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = $start_month ; $countMonth < $end_month ; $countMonth++)
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1)."��" == $m_value && $m_value != '')
				{
					$str.='<option value="'.($countMonth+1).'��"
							 selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth + 1;
				}
				else
				{
					$str.='<option value="'.($countMonth+1).'��"
							 >'.$m_text.'</option>';
				}
			}
		}
		else
		{
			$str.='<select id="'.$m_name.'" class ="'.$ReadOnly.'" name="'.$m_name.'" 
					onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.');">';
			for ($countMonth = 0 ; $countMonth < 12 ; $countMonth++ )
			{
				$m_text = str_pad($countMonth+1, 2, "0", STR_PAD_LEFT);
				if(($countMonth+1).'-1' == $m_value && $m_value != '')
				{
					$str.='<option value="'.($countMonth+1).'-1"
								 selected>'.$m_text.'</option>';
					$isSelect = true;
					$select_month = $countMonth+1;
				}
				else
				{
					$str.='<option value="'.($countMonth+1).'-1"
								>'.$m_text.'</option>';
				}
			}
		}
		if($isSelect == false)
		{
			$str.='<option value="" selected></option>';
		}
		else
		{
			$str.='<option value="" ></option>';
			$isSelect = false;
		}
		$str.='</select>';
		$str.='��';
	}
	if($type==1 || $type==2)
	{
		$str.='<select id="'.$d_name.'" class ="'.$ReadOnly.'" name="'.$d_name.'" 
				onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
				onChange = "notnullcheck(this.id,'.$isnotnull.');">';
		if($select_month != '')
		{
			$end_day = $dayarray[$select_month];
		}
		else
		{
			$end_day = 31;
		}
		if($select_year%4==0)
		{
			if($select_month==2)
			{
				$end_day = $dayarray[0];
			}
		}
		if($select_year != "" && $select_month != "")
		{
			for($nenngou_count = 0; $nenngou_count < count($start_array) ; $nenngou_count++ )
			{
				$date = explode('-',$start_array[$nenngou_count]);
				if($select_year == $date[0] && $select_month == $date[1])
				{
					if($type == 1)
					{
						if($d_value < $date[2])
						{
							$end_day = ($date[2]-1);
						}
						else
						{
							$start_day = ($date[2]-1);
						}
					}
					else if($type == 2)
					{
						$start_day = 0;
						$end_day = 31;
					}
				}
			}
		}
		for ($countDay = $start_day ; $countDay < $end_day ; $countDay++ )
		{
			$d_text = str_pad($countDay+1, 2, "0", STR_PAD_LEFT);
			if(($countDay+1) == $d_value && $d_value != '')
			{
				$str.='<option value='.($countDay+1).' selected>'.$d_text.'</option>';
				$isSelect = true;
			}
			else
			{
				$str.='<option value='.($countDay+1).' >'.$d_text.'</option>';
			}
		}
		if($isSelect == false)
		{
			$str.='<option value="" selected></option>';
		}
		else
		{
			$str.='<option value="" ></option>';
			$isSelect = false;
		}
		$str.='</select>';
		$str.='��';
	}
	return $str;
}



/************************************************************************************************************
function makeformSerch_set($post,$formName)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function makeformSerch_set($post,$formName){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$columns = $form_ini[$filename]['sech_form_num'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$columns_array = explode(',',$columns);
	$orderby = $form_ini[$filename]['orderby'];
	$between = $form_ini[$filename]['betweenColumn'];
	$orderby_array = explode(',',$orderby);

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$loop_count = 0;
	$Colum = "";
	$form_format_type = "";
	$form_before_year = "";
	$form_after_year = "";
	$form_num = "";
	$form_type = "";
	$form_item_name = "";
	$form_size = "";
	$form_value = "";
	$form_format = "";
	$form_length = "";
	$form_isJust = 2;
	$form_delimiter = "";
	$form_id = "";
	$form_name = "";
	$form_class = "";
	$serch_str = "";
	$input_type = "";
	$check_js = "";
	$check_column_str = "";
	$seen_table = $form_ini[$tablenum]['seen_table_num'];
	$seen_table_array = explode(',',$seen_table);
	$readOnly = '';
	$hidden_value ="";
	
	
	//------------------------//
	//          ����          //
	//------------------------//
	$serch_str .= "<table name ='formInsert' id ='serch'>";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		if($columns_array[0] == "")
		{
			break;
		}
		$Colum = $columns_array[$i];
		if(isset($form_ini[$Colum]['table_name']))
		{
			$insertColumn = $form_ini[$Colum]['insert_form_num'];
			
			
			
			
			if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
			{
				$insertColumn = '402,403,202,203';
			}
			
			
			
			
			
			$insertColumn_array = explode(',',$insertColumn);
			if($tablenum != $Colum)
			{
				$table_title = $form_ini[$Colum]['table_title'];
				$serch_str .= "<tr><td></td><td>";
				$serch_str .= '<input type="button" value="'.$table_title.'�I��" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
				$serch_str .= '</td></tr>';
				if(isset($post[$Colum.'CODE']))
				{
					$hidden_value = $post[$Colum.'CODE'];
				}
				else
				{
					$hidden_value ="";
				}
				$serch_str .= "<input type ='hidden' name ='".$columns_array[$i]
								."CODE'  value ='".$hidden_value."' >";
				$readOnly = 'class = "readOnly" readOnly';
			}
			for($j = 0 ; $j < count($insertColumn_array) ; $j++)
			{
				$Colum1 = $insertColumn_array[$j];
				$serch_str .="<tr><td>";
				$form_item_name = $form_ini[$Colum1]['item_name'];
				$serch_str .= "<a class = 'itemname'>";
				$serch_str .= $form_item_name;
				$serch_str .= "</a></td>";
				$form_format_type = $form_ini[$Colum1]['form_type'];
				$serch_str .= "<td>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum1]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum1]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum1]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum1]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum1]['form'.($k +1).'_length'];
						$form_delimiter = $form_ini[$Colum1]['form'.($k +1).'_delimiter'];
						$form_id = "form_".$Colum1."_".($k);
						$form_name = "form_".$Colum1."_".($k);
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = "";
						}
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','.$form_format.',false,'.$form_isJust.')"';
							$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
						}
						$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
										.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
										'"'.$readOnly.' size = "'.$form_size.'" '.$check_js.' >';
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
				}
				else if($form_format_type ==6 )
				{
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= period_pulldown_set($form_name,$over,$post,"",$formName,0);
				}
				else
				{
					$form_before_year = $form_ini[$Colum1]['before_year'];
					$form_after_year = $form_ini[$Colum1]['after_year'];
					$form_name = "form_".$Colum1;
					$over = "";
					$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
										$form_after_year,$form_name,$over,$post,"",$formName,0);
				}
				$serch_str .= "</td></tr>";
			}
			$readOnly = '';
		}
		else
		{
			$serch_str .="<tr><td>";
			$form_item_name = $form_ini[$Colum]['item_name'];
			$serch_str .= "<a class = 'itemname'>";
			$serch_str .= $form_item_name;
			$serch_str .= "</a></td>";
			$form_format_type = $form_ini[$Colum]['form_type'];
			$serch_str .= "<td>";
			if($form_format_type == 9)
			{
				$form_num = $form_ini[$Colum]['form_num'];
				for($k = 0 ; $k < $form_num ; $k++)
				{
					$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
					$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
					$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
					$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
					$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
					$form_id = "form_".$Colum."_".($k);
					$form_name = "form_".$Colum."_".($k);
					if(isset($post[$form_name]))
					{
						$form_value = $post[$form_name];
					}
					else
					{
						$form_value = "";
					}
					if($form_type == 2)
					{
						$input_type = 'file';
						$check_js = "";
					}
					else
					{
						$input_type = 'text';
						$check_js = 'onChange = " return inputcheck(\''
									.$form_name.'\','.$form_length.','.$form_format.',false,'.$form_isJust.')"';
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".false."~".$form_isJust.",";
					}
					$serch_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$check_js.' >';
				}
			}
			else if($form_format_type > 9)
			{
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldown_set($form_format_type,$form_name,$over,$post,"",$formName,0);
			}
			else if($form_format_type ==6 )
			{
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= period_pulldown_set($form_name,$over,$post,"",$formName,0);
			}
			else
			{
				$form_before_year = $form_ini[$Colum]['before_year'];
				$form_after_year = $form_ini[$Colum]['after_year'];
				$form_name = "form_".$Colum;
				$over = "";
				$serch_str.= pulldownDate_set($form_format_type,$form_before_year,
									$form_after_year,$form_name,$over,$post,"",$formName,0);
			}
			$serch_str .= "</td></tr>";
		}
	}
	if($orderby != '')
	{
		$serch_str .= "<tr><td><a class = 'itemname'>�\�[�g����</a></td>";
		$serch_str .= "<td><select name='sort'>";
		$serch_str .=  "<option value='0'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 0)
			{
				$serch_str .= " selected";
			}
		}
		else
		{
			$serch_str .=  " selected";
		}
		$serch_str .=  ">---�\�[�g������I�����Ă��������B---</option>";
		$serch_str .= "<option value='1'";
		if((isset ($post['sort'])))
		{
			if($post['sort'] == 1)
			{
				$serch_str .= " selected";
			}
		}
		$serch_str .=  ">�\�[�g�Ȃ�</option>";
		for($i = 0; $i < count($orderby_array) ; $i++)
		{
			$serch_str .= "<option value='".$orderby_array[$i]."'";
			if((isset ($post['sort'])))
			{
				if($post['sort'] == $orderby_array[$i])
				{
					$serch_str .= " selected";
				}
			}
			$serch_str .=  ">".$form_ini[$orderby_array[$i]]['item_name']."</option>";
		}
		$serch_str .= "</select><input name='radiobutton' type='radio' value='ASC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'ASC')
			{
				$serch_str .= " checked";
			}
		}
		else
		{
			$serch_str .= "checked";
		}
		$serch_str .= ">����";
		$serch_str .= "<input name='radiobutton' type='radio' value='DESC'";
		if((isset ($post['radiobutton'])))
		{
			if($post['radiobutton'] == 'DESC')
			{
				$serch_str .= " checked";
			}
		}
		$serch_str .= ">�~��";
		$serch_str .= "</td></tr>";
	}
	if($between != "")
	{
		$form_type = $form_ini[$filename]['form_type'];
		$before_year = $form_ini[$filename]['before_year'];
		$after_year = $form_ini[$filename]['after_year'];
		$over = "";
		$serch_str.= "<tr><td>�J�n���t</td><td>";
		$serch_str.= pulldownDate_set($form_type,$before_year,
					$after_year,"form_start",$over,$post,"",$formName,0);
		$serch_str.="</td></tr>";
		$serch_str.= "<tr><td>�I�����t</td><td>";
		$serch_str.= pulldownDate_set($form_type,$before_year,
					$after_year,"form_end",$over,$post,"",$formName,0);
		$serch_str.="</td></tr>";
	}
	
	
	$serch_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	return ($serch_str);
	
}



/************************************************************************************************************
function pulldown_set($type,$name,$over,$post,$ReadOnly,$formName,$isnotnull)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function pulldown_set($type,$name,$over,$post,$ReadOnly,$formName,$isnotnull){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$pulldown_ini = parse_ini_file('./ini/pulldown.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$pulldown = "";
	$num = 0;
	$text = "";
	$value ="";
	$formname ="";
	$select = "";
	$isSelect = false;
	$isdisable = "";
	$disable = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	if($ReadOnly == '')
	{
		$isdisable = "";
	}
	else
	{
		$isdisable = 'disabled';
	}
	if(isset($pulldown_ini[$type]['num']))
	{
		$num = $pulldown_ini[$type]['num'];
	}
	if($over !="")
	{
		$formname = $name."_0_".$over;
	}
	else
	{
		$formname = $name."_0";
	}
	
	$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.',\''.$formName.'\');">';
	for($i = 0 ;$i < $num ; $i++)
	{
		if(isset($pulldown_ini[$type]['text'.($i + 1)]))
		{
			$text = $pulldown_ini[$type]['text'.($i + 1)];
		}
		else
		{
			$text = '';
		}
		if(isset($pulldown_ini[$type]['value'.($i + 1)]))
		{
			$value = $pulldown_ini[$type]['value'.($i + 1)];
		}
		else
		{
			$value = '';
		}
		if(isset($post[$formname]))
		{
			if($value == $post[$formname])
			{
				$select = ' selected ';
				$isSelect=true;
				$disable = "";
			}
		}
		$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
		$select = "";
	}
	if($isSelect)
	{
		$pulldown.='<option value ="" >---�I�����ĉ�����---</option>';
	}
	else
	{
		$pulldown.='<option value ="" selected >---�I�����ĉ�����---</option>';
	}
	return $pulldown;
}


/************************************************************************************************************
function format_change($format,$value,$type)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function format_change($format,$value,$type){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$prevalue = array();
	$result = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	switch ($format)
	{
	case 1:
		if(preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $value))
		{
			$prevalue = explode('-',$value);
			if(checkdate($prevalue[1], $prevalue[2], $prevalue[0]))
			{
				$prevalue[0] = wareki_date($value)."�N ";
				$prevalue[1] = $prevalue[1]."�� ";
				$prevalue[2] = $prevalue[2]."��";
				$result .= $prevalue[0];
				if($type != 5 && $type != 6)
				{
					$result .= $prevalue[1];
				}
				if($type == 1 || $type == 2)
				{
					$result .= $prevalue[2];
				}
			}
		}
		return $result;
		break;
	case 2:
		if(preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $value))
		{
			$prevalue = explode('-',$value);
			if(checkdate($prevalue[1], $prevalue[2], $prevalue[0]))
			{
				$prevalue[0] = $prevalue[0]."�N ";
				$prevalue[1] = $prevalue[1]."�� ";
				$prevalue[2] = $prevalue[2]."��";
				$result .= $prevalue[0];
				if($type != 5 && $type != 6)
				{
					$result .= $prevalue[1];
				}
				if($type == 1 || $type == 2)
				{
					$result .= $prevalue[2];
				}
			}
		}
		return $result;
		break;
	case 3:
		if (is_numeric($value))
		{
			$result = number_format($value);
		}
		return $result;
		break;
	case 4:
		if (is_numeric($value))
		{
			if($value == 0)
			{
				$result = '�o�ג�';
			}
			if($value == 1)
			{
				$result = '�ď����҂�';
			}
			if($value == 2)
			{
				$result = '����';
			}
		}
		return $result;
		break;
	case 5:
		if (is_numeric($value))
		{
			if($value == 1)
			{
				$result = '�o��';
			}
			if($value == 2)
			{
				$result = '�ԋp';
			}
		}
		return $result;
		break;
	case 6:
		if (is_numeric($value))
		{
			if($value == 1)
			{
				$result = '�s��';
			}
			if($value == 2)
			{
				$result = '�ߏ�';
			}
		}
		return $result;
		break;
	case 7:
		if (is_numeric($value))
		{
			if($value == 1)
			{
				$result = '���ٖ�����';
			}
			if($value == 2)
			{
				$result = '�ď����ς�';
			}
		}
		return $result;
		break;
	case 8:
		if (is_numeric($value))
		{
			if($value == 0)
			{
				$result = '�Ȃ�';
			}
			if($value == 1)
			{
				$result = '����';
			}
		}
		return $result;
		break;
	case 9:
		$date = date_create($value);
		$result = date_format($date, 'Y/m/d H:i:s');
		return $result;
		break;
	default :
		$result = $value;
	}
	return $result;

}

/************************************************************************************************************
function make_selectlist()

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function make_selectlist(){
    $form_str = "";
    $form_str .= "<table border='1' id = 'select_pj' class ='list' name ='formInsert'><thead><tr><th><a class ='head'>No</a></th><th><a class ='head'>�v���W�F�N�g�R�[�h</a></th><th><a class ='head'>�}�ԃR�[�h</a><th><a class ='head'>���ԁE�Č���</a></th></th><tr/></thead></table>";
    
    return ($form_str);
}
/************************************************************************************************************
function makeformModal_set($post,$isReadOnly,$form_Name,$columns)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function makeformModal_set($post,$isReadOnly,$form_Name,$columns){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns_array = explode(',',$columns);
	//2017-12-27
	$filename = $_SESSION['filename'];

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$Colum = "";
	$form_format_type = "";
	$form_before_year = "";
	$form_after_year = "";
	$form_num = "";
	$form_type = "";
	$form_item_name = "";
	$form_size = "";
	$form_value = "";
	$form_format = "";
	$form_length = "";
	$form_isJust = 2;
	$form_delimiter = "";
	$form_id = "";
	$form_name = "";
	$form_class = "";
	$form_str = "";
	$input_type = "";
	$check_js = "";
	$check_column_str = "";
	$change = "";
	if(isset($_SESSION['nenzi']['kimatagi']) && $columns == '102,202,203')
	{
		$change = 'true';
	}
	

	
	//------------------------//
	//          ����          //
	//------------------------//
	$form_str .= "<table name ='formInsert' id ='serch'>";
	for($i = 0 ; $i < count($columns_array) ; $i++)
	{
		$Colum = $columns_array[$i];
		$form_str .="<tr><td>";
		$form_item_name = $form_ini[$Colum]['item_name'];
		$form_str .= "<a class = 'itemname'>";
		$form_str .= $form_item_name;
		$form_str .= "</a></td>";
		$form_format_type = $form_ini[$Colum]['form_type'];
		$form_str .= "<td>";
		if($form_format_type == 9)
		{
			$form_num = $form_ini[$Colum]['form_num'];
			for($k = 0 ; $k < $form_num ; $k++)
			{
				$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
				$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
				$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
				$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
				$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
				$form_id = "form_".$Colum."_".($k);
				$form_name = "form_".$Colum."_".($k);
				if(isset($post[$form_name]))
				{
					$form_value = $post[$form_name];
				}
				else
				{
					$form_value = "";
				}
				if($form_type == 2)
				{
					$input_type = 'file';
					$check_js = "";
				}
				else
				{
					$input_type = 'text';
					$check_js = 'onChange = " return inputcheck(\''
								.$form_name.'\','.$form_length.','.$form_format.',false,'.$form_isJust.')"';
					$check_column_str .= $form_name."~".$form_length."~".$form_format."~false~".$form_isJust.",";
					
				}
				//2017-12-27
				if(isset($_SESSION['nenzi']['kimatagi']) && $form_Name == 'drop' && $change == 'true')
				{
					if($form_name == 'form_102_0')
					{
						$form_name = "pjcode";
					}
					if($form_name == 'form_202_0')
					{
						$form_name = "edaban";
					}
					if($form_name == 'form_203_0')
					{
						$form_name = "pjname";
					}
					$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_name.'" 
									 class ="'.$isReadOnly.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$isReadOnly.' >';
				}
				else
				{
					$form_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									 class ="'.$isReadOnly.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$isReadOnly.' '.$check_js.' >';
				}
				//2017-12-27�����܂�
			}
		}
		else if($form_format_type > 9)
		{
			$form_name = "form_".$Colum;
			$over = '';
			$form_str.= pulldown_set($form_format_type,$form_name,
									$over,$post,$isReadOnly,$form_Name,false);
		}
		else
		{
			$form_before_year = $form_ini[$Colum]['before_year'];
			$form_after_year = $form_ini[$Colum]['after_year'];
			$form_name = "form_".$Colum;
			$over = '';
			$form_str.= pulldownDate_set($form_format_type,$form_before_year,
							$form_after_year,$form_name,$over,$post,$isReadOnly,$form_Name,false);
		}
		$form_str .= "</td></tr>";
	}
	$form_str .= "</table>";
	$check_column_str =  substr($check_column_str,0,-1);
	$_SESSION['check_column'] = $check_column_str;
	return ($form_str);
}




/************************************************************************************************************
function formvalue_return($colum_num,$value,$type)

����	$colum_num
����	$value

�߂�l	$result
************************************************************************************************************/
function formvalue_return($colum_num,$value,$type) {
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$fild_name = $form_ini[$colum_num]['column'];
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$column_value = '';
	$form_name  = '';
	$form_type  = '';
	$form_para = array();
	
	
	//------------------------//
	//          ����          //
	//------------------------//
	if($type == '1' || $type == '2')
	{
		$value = explode('-',$value);
		for($i = 0; $i < 3 ; $i++ )
		{
			$form_name .= 'form_'.$colum_num.'_'.$i.',';
			//$column_value .= ereg_replace("^0+","", $value[$i]).'#$';
			$form_type .=$type.',';
		}
	}
	else if($type == 3)
	{
		$value = explode('�N',$value);
		$form_name .= 'form_'.$colum_num.'_0,';
		$column_value .= $value[0].'�N#$';
		$form_name .= 'form_'.$colum_num.'_1,';
		$column_value .= $value[1].'#$';
		$form_type .=$type.','.$type.',';
	}
	else if($type == 4)
	{
		$value = explode('-',$value);
		$form_name .= 'form_'.$colum_num.'_0,';
		$column_value .= $value[0].'#$';
		$form_name .= 'form_'.$colum_num.'_1,';
		$column_value .= $value[1].'-'.$value[2].'#$';
		$form_type .=$type.','.$type.',';
	}
	else
	{
		if(strstr($fild_name,'CODE') != false)
		{
			$form_name .= $fild_name.',';
		}
		else
		{
			$form_name .= 'form_'.$colum_num.'_0,';
		}
		$column_value .= $value.'#$';
		$form_type .=$type.',';
	}
	$form_para[0] = $form_name;
	$form_para[1] = $column_value;
	$form_para[2] = $form_type;
	
	return($form_para);
}

/************************************************************************************************************
function getover($post,$tablenum)

����	$colum_num
����	$value

�߂�l	$result
************************************************************************************************************/
function getover($post,$tablenum) {
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);
	require_once 'f_Form.php';
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columns = $form_ini[$tablenum]['insert_form_num'];
	$columns_array = explode(',', $columns);
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$over =array();
	$keyarray = array();
	$counter = 0;
	$keyparam = array();
	
	//------------------------//
	//          ����          //
	//------------------------//
	
	$keyarray = array_keys($post);
	foreach($keyarray as $key)
	{
		if(strstr($key,$columns_array[0]) != false )
		{
			$keyparam = explode('_',$key);
			if(count($keyparam) == 3)
			{
				$over[$counter] = "";
			}
			else if(count($keyparam) == 4)
			{
				$over[$counter] = $keyparam[3];
			}
			else
			{
				$over[$counter] = "";
			}
			$counter++;
		}
	}
	return($over);
}
/************************************************************************************************************
function InsertComp($post)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function InsertComp($post){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini�Ăяo��
	require_once 'f_Form.php';																			// f_From�֐��Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];																	// �y�[�WID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// �o�^�J�����ꗗ(csv)
	$columns_array = explode(',',$columns);																// �o�^�J�����ꗗ(�z��)

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$table_columns ="";																					// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(csv)
	$table_columns_array = array();																		// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(�z��)
	$loop_count = 0;																					// $table_columns_array�̔z��
	$Colum = "";																						// �쐬�Ώۃt�H�[���̃J�����ԍ�
	$form_type = "";																					// �쐬�Ώۃt�H�[���̃^�C�v form.ini 'form_type'
	$format_type = "";																					// �쐬�Ώۃt�H�[�� �t�H�[���^�C�v form.ini 'form_type'
	$keyarray = array();																				// ����$post �́@Key�z��
	$list_id = array();																					// ���X�g�e�[�u���̌J��Ԃ�ID�z��
	$idcount = 0;																						// ���X�g�e�[�u���̌J��Ԃ�ID�z��̔z��ԍ�
	$list_loop = 0;																						// ���X�g�e�[�u���̌J��Ԃ���
	$max_over = -1;																						// ���X�g�e�[�u���̌J��Ԃ��ő吔
	$table_title = "";																					// �e�[�u���^�C�g��
	$delimiter = "";
	$value = "";
	$istable = false;
	$insert_str = "";
	$pre_insert_str = "";
	$isType2 = false;
	$counter = 0;
	
	//------------------------//
	//          ����          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// ���̓t�H�[��html��
	for($i = 0 ; $i < count($columns_array) ; $i++)														// �o�^�J�����������[�v
	{
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// �o�^�J�������e�[�u���ԍ���
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			$istable = true;
			if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
			$istable = false;
		}
		$list_loop = count($list_id) + 1;
		$idcount = 0;
		$counter = 0;
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				$pre_insert_str ="<tr><td class = 'space'></td><td class = 'one'>";
				$form_item_name = $form_ini[$Colum]['item_name'];
				$pre_insert_str .= "<a class = 'itemname'>";
				$pre_insert_str .= $form_item_name;
				$pre_insert_str .= "</a></td>";
				$form_type = $form_ini[$Colum]['form_type'];
				$format_type = $form_ini[$Colum]['format'];
				$pre_insert_str .= "<td class = 'two'><a class = 'comp' >";
				for($k = 0 ; $k < 5 ; $k++)
				{
					if(isset($form_ini[$Colum]['form'.($k + 1).'_type']))
					{
						$input_type = $form_ini[$Colum]['form'.($k + 1).'_type'];
					}
					else
					{
						$input_type = 1;
					}
					if($input_type != 1 && $input_type != 2)
					{
						$input_type = 1;
					}
					if($list_count == 0)
					{
						$form_name = "form_".$Colum."_".($k);
					}
					else
					{
						$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
					}
					if($form_type == 1 || $form_type == 2  || $form_type == 4 )
					{
						$delimiter = "-";
					}
					else
					{
						$delimiter = "";
					}
					if(isset($post[$form_name]) && $input_type == 1)
					{
						$value .= $post[$form_name].$delimiter;
					}
					else if(isset($post[$form_name]) && $input_type != 1)
					{
						$counter++;
						$value .=  $form_ini[$Colum]['link_num'].($counter);
						$isType2 = true;
					}
					else if($input_type == 2)
					{
						$isType2 = true;
					}
				}
				$value = rtrim($value,$delimiter);
				$value = format_change($format_type,$value,$form_type);
				if($isType2 == false || $value != "")
				{
					$insert_str .= $pre_insert_str.$value."</a></td></tr>";
				}
				$pre_insert_str = "";
				$value = "";
				$isType2 = false;
			}
		}
		$list_id = array();
	}
	$insert_str .= "</table>";
	return ($insert_str);
}


/************************************************************************************************************
function makeformEdit_set($post,$out_column,$isReadOnly,$formName,$data)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l
����	$out_column		���̓`�F�b�N(php��)�ŕs�J�����ԍ�
����	$isReadOnly		���[�h�I�����[��ݒ肷�邩
����	$formName		�t�H�[���^�O��name

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function makeformEdit_set($post,$out_column,$isReadOnly,$formName,$data){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini�Ăяo��
	require_once 'f_Form.php';																			// f_From�֐��Ăяo��
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$out_column = explode(',',$out_column);																// ���̓`�F�b�N(php��)�ŕs�J�����ԍ��z��
	$filename = $_SESSION['filename'];																	// �y�[�WID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// �o�^�J�����ꗗ(csv)
	$columns_array = explode(',',$columns);																// �o�^�J�����ꗗ(�z��)
	$notEditcolumns = $form_ini[$filename]['notEditColum'];												// �o�^�J�����ꗗ(csv)
	$notEditcolumns_array = explode(',',$notEditcolumns);												// �o�^�J�����ꗗ(�z��)
	$isMasterInsert = 0;      // $form_ini[$filename]['isMasterInsert'];								// �}�X�^�[�e�[�u���̓o�^�������邩 0:�s�� 1:����
	$maintable = $form_ini[$filename]['use_maintable_num'];

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$istable = false;																					// �o�^�J�������e�[�u����
	$table_columns ="";																					// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(csv)
	$table_columns_array = array();																		// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(�z��)
	$loop_count = 0;																					// $table_columns_array�̔z��
	$ismaster = false;																					// �e�[�u�����}�X�^�[�e�[�u����
	$islist = false;																					// �e�[�u�������X�g�e�[�u����
	$Colum = "";																						// �쐬�Ώۃt�H�[���̃J�����ԍ�
	$form_format_type = "";																				// �쐬�Ώۃt�H�[���̃^�C�v form.ini 'form_type'
	$form_before_year = "";																				// �쐬�Ώۃt�H�[�� ���t�v���_�E���̊J�n�N form.ini 'before_year'
	$form_after_year = "";																				// �쐬�Ώۃt�H�[�� ���t�v���_�E���̏I���N form.ini 'after_year'
	$form_num = "";																						// �쐬�Ώۃt�H�[�� �t�H�[���� form.ini 'form_num'
	$form_type = "";																					// �쐬�Ώۃt�H�[�� �t�H�[���^�C�v form.ini 'form_type'
	$form_item_name = "";																				// �쐬�Ώۃt�H�[�� �A�C�e���� form.ini 'form_item_name'
	$form_size = "";																					// �쐬�Ώۃt�H�[�� �T�C�Y form.ini 'form*_size'
	$form_value = "";																					// �쐬�Ώۃt�H�[�� value form.ini 'form*_value'
	$form_format = "";																					// �쐬�Ώۃt�H�[�� ���͉\���� form.ini 'form*_format'
	$form_length = "";																					// �쐬�Ώۃt�H�[�� ���͉\���� form.ini 'form*_length'
	$form_isJust = "";
	$form_delimiter = "";																				// �쐬�Ώۃt�H�[�� ��؂蕶�� form.ini 'form*_length'
	$form_id = "";																						// �쐬�Ώۃt�H�[�� id
	$form_name = "";																					// �쐬�Ώۃt�H�[�� name
	$form_class = "";																					// �쐬�Ώۃt�H�[�� class
	$insert_str = "";																					// ���̓t�H�[��html �߂�l
	$isonce = false;																					// ���̓t�H�[���쐬��1�e�[�u������1��ڂ�
	$input_type = "";																					// input�^�O �^�C�v textbpx or file
	$check_column_str = "";																				// ���̓`�F�b�N�Ώۃt�H�[��name(csv)
	$isnotnull = 0;																						// ���͕K�{���ڔ��f
	$notnull_column_str = "";																			// ���͕K�{�t�H�[���e�[�u���ԍ�(csv)
	$notnull_type_str = "";																				// ���͕K�{�t�H�[���e�[�u���ԍ�(csv)
	$check_js = "";																						// ���̓`�F�b�Njavascripr �Ăяo��html��
	$isout = false;																						// �쐬�Ώۃt�H�[�������̓`�F�b�N(php��)�s�J������
	$keyarray = array();																				// ����$post �́@Key�z��
	$list_id = array();																					// ���X�g�e�[�u���̌J��Ԃ�ID�z��
	$idcount = 0;																						// ���X�g�e�[�u���̌J��Ԃ�ID�z��̔z��ԍ�
	$list_loop = 0;																						// ���X�g�e�[�u���̌J��Ԃ���
	$max_over = -1;																						// ���X�g�e�[�u���̌J��Ԃ��ő吔
	$table_title = "";																					// �e�[�u���^�C�g��
	$ReadOnly = "";																						// ReadOnly����
	$hidden_value = "";																					// hidden �t�H�[����value�l
	$error ="";
	$readonly_back = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='edit'>";											// ���̓t�H�[��html��
	for($i = 0 ; $i < count($columns_array) ; $i++)														// �o�^�J�����������[�v
	{
		if(isset($data[$columns_array[$i]]))
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			for($j = 0 ; $j < count($data[$columns_array[$i]]) ; $j++ )
			{
				for( $k = 0 ; $k < count($table_columns_array) ; $k++)
				{
					$Colum = $table_columns_array[$k];
					$link_num = $form_ini[$Colum]['link_num'];
					foreach($data[$columns_array[$i]][$j] as $key => $value)
					{
						if(strstr( $key, $Colum) != false)
						{
							$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
							$form_item_name = $form_ini[$Colum]['item_name'];
							$insert_str .= "<a class = 'itemname'>";
							$insert_str .= $form_item_name;
							$insert_str .= "</a></td>";
							$insert_str .= "<td class = 'two'><A HREF='./showpdf.php?path=".
											$value
											."&title=".$link_num.($j+1)."' TARGET='_blank' >".
											$link_num.($j+1)."</A>";
							$insert_str .= "<input type='checkbox' id = 'check_".
											$i."_".$j."' name='delete".
											$columns_array[$i]."[]' value='".
											$value.":".
											$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
											."'";
							if(isset($post['delete'.$columns_array[$i]]))
							{
								if(in_array($value.':'
									.$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
									,$post['delete'.$columns_array[$i]]) == true)
								{
									 $insert_str .= 'checked';
								}
							}
							$insert_str .= '>';
							$insert_str .= "<label for = 'check_".
											$i."_".$j."'> �폜 </label>";
						}
					}
				}
			}
		}
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// �o�^�J�������e�[�u���ԍ���
		{
			$istable = true;																			// 
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			if($form_ini[$columns_array[$i]]['table_type'] == 1)
			{
				$ismaster = true;
			}
			else if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$islist = true;
				$islistform = true;
				$isonce = true;
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
		}
//		$list_loop = count($list_id) + 1;
		$list_loop = 1;
		$idcount = 0;
		if($ismaster && $columns_array[$i] != $maintable )
		{
			$insert_str .= "<tr><td class = 'space'></td><td class ='one'></td>
								<td class ='two'>";
			$insert_str .= '<input type="button" value="'.$table_title.'�I��" 
				onclick="popup_modal(\''.$columns_array[$i].'\')">';
			if($isMasterInsert == 1)
			{
				$insert_str .= '<input type="button" value="'.$table_title.'�o�^" 
					onclick="popup_modal(\''.$columns_array[$i].'\')">';
			}
			if(isset($post[$columns_array[$i].'CODE']))
			{
				$hidden_value = $post[$columns_array[$i].'CODE'];
			}
			else
			{
				$hidden_value ="";
			}
			$insert_str .= "<input type ='hidden' name ='".$columns_array[$i]
							."CODE'  value ='".$hidden_value."' >";
			$insert_str .= "</td>";
			
			for($out_counter = 0; $out_counter < count($out_column) ; $out_counter++)
			{
				if($out_column == "")
				{
					break;
				}
				if(isset($form_ini[$out_column[$out_counter]]['column']))
				{
					if($form_ini[$out_column[$out_counter]]['column'] == $columns_array[$i].'CODE')
					{
						$tablename_out = $form_ini[$columns_array[$i]]['table_title'];
						$insert_str .= "<td><a class = 'error'>".$tablename_out."���͊��ɓo�^����Ă��܂��B</a></td>";
					}
				}
			}
			
			$insert_str .= "</tr>";
			if($isReadOnly == true)
			{
				$ReadOnly = "readOnly";
				$readonly_back = $ReadOnly;
			}
		}
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				if($islist)
				{
					$insert_str .="<tr id = '".$columns_array[$i]."'>";
					$insert_str .="<td class = 'space'></td><td class ='one'>";
				}
				else
				{
					$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
				}
				$form_item_name = $form_ini[$Colum]['item_name'];
				$insert_str .= "<a class = 'itemname'>";
				$insert_str .= $form_item_name;
				$insert_str .= "</a></td>";
				
				if(in_array($Colum,$notEditcolumns_array))
				{
					$ReadOnly = "readOnly";
				}
				else
				{
					$ReadOnly = $readonly_back;
				}
				if(($filename == 'KOUTEIINFO_2' && $Colum == '302') || ($filename == 'SYAINNINFO_2' && $Colum == '402'))
				{
					$ReadOnly = "readOnly";
				}
				
				for($outcounter1 = 0 ; $outcounter1 < count($out_column) ; $outcounter1++)
				{
					if(strstr($out_column[$outcounter1], $Colum))
					{
						$out = explode(',',$out_column[$outcounter1]);
						for($outcounter2 = 0 ; $outcounter2 < count($out) ; $outcounter2++)
						{
							$error .= $form_ini[$out[$outcounter2]]['item_name'].",";
						}
						$error = substr($error,0,-1);
						$isout = true;
					}
				}
				
				$form_format_type = $form_ini[$Colum]['form_type'];
				if($form_ini[$Colum]['isnotnull'] == 1)
				{
					$notnull_column_str .= $Colum.",";
					$notnull_type_str .= $form_format_type.",";
					$isnotnull = 1;
					if($islist)
					{
						$isnotnull = 0;
					}
				}
				else
				{
					$isnotnull = 0;
				}
				$insert_str .= "<td class = 'two'>";
				if($form_format_type == 9)
				{
					$form_num = $form_ini[$Colum]['form_num'];
					for($k = 0 ; $k < $form_num ; $k++)
					{
						$form_type = $form_ini[$Colum]['form'.($k +1).'_type'];
						$form_size = $form_ini[$Colum]['form'.($k +1).'_size'];
						$form_format = $form_ini[$Colum]['form'.($k +1).'_format'];
						$form_length = $form_ini[$Colum]['form'.($k +1).'_length'];
						$form_isJust = $form_ini[$Colum]['isJust'];
						$form_delimiter = $form_ini[$Colum]['form'.($k +1).'_delimiter'];
						if($list_count == 0)
						{
							$form_id = "form_".$Colum."_".($k);
							$form_name = "form_".$Colum."_".($k);
						}
						else
						{
							$form_id = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
							$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
						}
						if(isset($post[$form_name]))
						{
							$form_value = $post[$form_name];
						}
						else
						{
							$form_value = $form_ini[$Colum]['form'.($k + 1).'_value'];
						}
						$check_column_str .= $form_name."~".$form_length."~".$form_format."~".$isnotnull."~".$form_isJust.",";
						if($form_type == 2)
						{
							$input_type = 'file';
							$check_js = "";
						}
						else
						{
							$input_type = 'text';
							$check_js = 'onChange = " return inputcheck(\''
										.$form_name.'\','.$form_length.','
										.$form_format.','.$isnotnull.','.$form_isJust.')"';
						}
						$insert_str .= $form_delimiter.'<input type ="'.$input_type.'" name = "'
									.$form_name.'" id = "'.$form_id.'" 
									class = "'.$ReadOnly.'" value = "'.$form_value.
									'" size = "'.$form_size.'" '.$ReadOnly.' '.$check_js.' >';
					}
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a>";
						$isout = false;
						$error = "";
						$insert_str .="</td>";
					}
					else
					{
						$insert_str .="</td>";
					}
				}
				else if($form_format_type > 9)
				{
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					$insert_str.= pulldown_set($form_format_type,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
					
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a>";
						$insert_str .="</td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
					
				}
				else
				{
					$form_before_year = $form_ini[$Colum]['before_year'];
					$form_after_year = $form_ini[$Colum]['after_year'];
					$form_name = "form_".$Colum;
					$over = "";
					if($list_count == 0)
					{
						$over = "";
					}
					else
					{
						$over = $list_id[$list_count - 1];
					}
					$insert_str.= pulldownDate_set($form_format_type,$form_before_year,
													$form_after_year,$form_name,$over,
													$post,$ReadOnly,$formName,$isnotnull);
					if($isonce)
					{
						$insert_str .="</td><td>";
						$insert_str .='<input type="button" value="'.$table_title.'�g�ǉ�" 
										onClick="AddTableRows(\''.$columns_array[$i].'\')">';
						$isonce = false;
						if($isout)
						{
							$insert_str .="</td><td><a class='error'>"
											.$error."�͊��ɓo�^����Ă��܂��B</a>";
							$isout = false;
							$error = "";
						}
						$insert_str .="</td>";
					}
					else if($isout)
					{
						$insert_str .="</td><td></td><td>";
						$insert_str .="</td><td><a class='error'>"
										.$error."�͊��ɓo�^����Ă��܂��B</a></td>";
						$isout = false;
						$error = "";
					}
					else
					{
						$insert_str .= "</td>";
					}
				}
				$insert_str .= "</tr>";
			}
			$islist = false;
			$ReadOnly = "";
			$readonly_back = "";
		}
		$list_id = array();
		$istable = false;
		$ismaster = false;
	}
	
	$insert_str .= "</table>";
	
	$masters = $form_ini[$maintable]['seen_table_num'];
	$masters_array = explode(',',$masters);
	for ($i = 0 ; $i < count($masters_array) ; $i++)
	{
		if(!in_array($masters_array[$i],$columns_array) && $masters_array[$i] != "")
		{
			$insert_str .= "<input type='hidden' name = '".$masters_array[$i]."CODE' value ='".
						$post[$masters_array[$i].'CODE']."' >";
		}
	}
	
	
	
	
	
	
	
	$check_column_str = rtrim($check_column_str,',');
	$notnull_column_str = rtrim($notnull_column_str,',');
	$notnull_type_str = rtrim($notnull_type_str,',');
	$_SESSION['check_column'] = $check_column_str;
	$_SESSION['notnullcolumns'] = $notnull_column_str;
	$_SESSION['notnulltype'] = $notnull_type_str;
	$_SESSION['max_over'] = $max_over;
	return ($insert_str);
}


/************************************************************************************************************
function EditComp($post,$data)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function EditComp($post,$data){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);													// form.ini�Ăяo��
	require_once 'f_Form.php';																			// f_From�֐��Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];																	// �y�[�WID
	$columns = $form_ini[$filename]['insert_form_tablenum'];											// �o�^�J�����ꗗ(csv)
	$columns_array = explode(',',$columns);																// �o�^�J�����ꗗ(�z��)

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$table_columns ="";																					// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(csv)
	$table_columns_array = array();																		// �o�^�J�������e�[�u�������̃e�[�u���̓o�^�J�����ԍ�(�z��)
	$loop_count = 0;																					// $table_columns_array�̔z��
	$Colum = "";																						// �쐬�Ώۃt�H�[���̃J�����ԍ�
	$form_type = "";																					// �쐬�Ώۃt�H�[���̃^�C�v form.ini 'form_type'
	$format_type = "";																					// �쐬�Ώۃt�H�[�� �t�H�[���^�C�v form.ini 'form_type'
	$keyarray = array();																				// ����$post �́@Key�z��
	$list_id = array();																					// ���X�g�e�[�u���̌J��Ԃ�ID�z��
	$idcount = 0;																						// ���X�g�e�[�u���̌J��Ԃ�ID�z��̔z��ԍ�
	$list_loop = 0;																						// ���X�g�e�[�u���̌J��Ԃ���
	$max_over = -1;																						// ���X�g�e�[�u���̌J��Ԃ��ő吔
	$table_title = "";																					// �e�[�u���^�C�g��
	$delimiter = "";
	$value = "";
	$istable = false;
	$insert_str = "";
	$listcount = 0;
	$isOut = true;
	$isType2 = false;
	$counter = 0 ;
	
	//------------------------//
	//          ����          //
	//------------------------//
	$insert_str .= "<table name ='formInsert' id ='insert'>";											// ���̓t�H�[��html��
	for($i = 0 ; $i < count($columns_array) ; $i++)														// �o�^�J�����������[�v
	{
		$listcount = 0;
		if(isset($data[$columns_array[$i]]) == true)
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			for($j = 0 ; $j < count($data[$columns_array[$i]]) ; $j++ )
			{
				for( $k = 0 ; $k < count($table_columns_array) ; $k++)
				{
					$Colum = $table_columns_array[$k];
					$link_num = $form_ini[$Colum]['link_num'];
					foreach($data[$columns_array[$i]][$j] as $key => $value)
					{
						if(strstr( $key, $Colum) == true)
						{
							if(isset($post['delete'.$columns_array[$i]]) == true)
							{
								if(in_array($value.':'
									.$data[$columns_array[$i]][$j][$columns_array[$i].'CODE']
									,$post['delete'.$columns_array[$i]]) != true)
								{
									$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
									$form_item_name = $form_ini[$Colum]['item_name'];
									$insert_str .= "<a class = 'itemname'>";
									$insert_str .= $form_item_name;
									$insert_str .= "</a></td>";
									$insert_str .= "<td class = 'two'><a class = 'comp' >"
													.$link_num.($listcount+1)."</a>";
									$listcount++;
								}
							}
							else
							{
								$insert_str .="<tr><td class = 'space'></td><td class ='one'>";
								$form_item_name = $form_ini[$Colum]['item_name'];
								$insert_str .= "<a class = 'itemname'>";
								$insert_str .= $form_item_name;
								$insert_str .= "</a></td>";
								$insert_str .= "<td class = 'two'><a class = 'comp' >"
												.$link_num.($listcount+1)."</a>";
								$listcount++;
							}
						}
					}
				}
			}
		}
		if(isset($form_ini[$columns_array[$i]]['insert_form_num']))										// �o�^�J�������e�[�u���ԍ���
		{
			$table_columns = $form_ini[$columns_array[$i]]['insert_form_num'];
			$table_columns_array = explode(',',$table_columns);
			$loop_count = count($table_columns_array);
			$table_title = $form_ini[$columns_array[$i]]['table_title'];
			$istable = true;
			if($form_ini[$columns_array[$i]]['table_type'] == 2)
			{
				$keyarray = array_keys($post);
				foreach($keyarray as $key)
				{
					if (strstr($key, ($table_columns_array[0]."_0")) !=false )
					{
						$key_id = explode('_',$key);
						if(count($key_id) == 4)
						{
							$list_id[$idcount] = $key_id[3];
							if($max_over < $key_id[3])
							{
								$max_over = $key_id[3];
							}
							$idcount++;
						}
					} 
				}
			}
		}
		else
		{
			$loop_count = 1;
			$table_title = $form_ini[$columns_array[$i]]['link_num'];
			$istable = false;
		}
		$list_loop = count($list_id) + 1;
		$idcount = 0;
		$value = "";
		$counter = 0 ;
		for ($list_count = 0 ; $list_count < $list_loop ; $list_count++)
		{
			for($j = 0 ; $j < $loop_count ; $j++)
			{
				if($istable)
				{
					$Colum = $table_columns_array[$j];
				}
				else
				{
					$Colum = $columns_array[$i];
				}
				$form_type = $form_ini[$Colum]['form_type'];
				$format_type = $form_ini[$Colum]['format'];
				for($k = 0 ; $k < 5 ; $k++)
				{
					if(isset($form_ini[$Colum]['form'.($k + 1).'_type']))
					{
						$input_type = $form_ini[$Colum]['form'.($k + 1).'_type'];
					}
					else
					{
						$input_type = 1;
					}
					if($input_type != 1 && $input_type != 2 )
					{
						$input_type = 1;
					}
					if($list_count == 0)
					{
						$form_name = "form_".$Colum."_".($k);
					}
					else
					{
						$form_name = "form_".$Colum."_".($k)."_".$list_id[$list_count - 1];
					}
					if($form_type == 1 || $form_type == 2  || $form_type == 4 )
					{
						$delimiter = "-";
					}
					else
					{
						$delimiter = "";
					}
					if(isset($post[$form_name]) && $input_type == 1)
					{
						$value .= $post[$form_name].$delimiter;
						$isOut = true;
					}
					else if(isset($post[$form_name]) && $input_type != 1)
					{
						$counter++;
						$value .=  $form_ini[$Colum]['link_num'].($counter + $listcount);
						$isOut = true;
						$isType2 = true;
					}
					else if($input_type == 2)
					{
						$isType2 = true;
					}
//					else if(isset($post[$form_name]) == false)
//					{
//						$isOut = false;
//					}
					if($value != '')
					{
						$isOut = true;
					}
				}
				$value = rtrim($value,$delimiter);
				$value = format_change($format_type,$value,$form_type);
				if($isOut == true && ($isType2 == false || $value != ""))
				{
					$insert_str .="<tr><td class = 'space'></td><td class = 'one'>";
					$form_item_name = $form_ini[$Colum]['item_name'];
					$insert_str .= "<a class = 'itemname'>";
					$insert_str .= $form_item_name;
					$insert_str .= "</a></td>";
					$insert_str .= "<td class = 'two'><a class = 'comp' >";
					$insert_str .= $value."</a></td></tr>";
				}
				$value = "";
				$isOut = true;
				$isType2 = false;
			}
		}
		$list_id = array();
	}
	$insert_str .= "</table>";
	return ($insert_str);
}


/************************************************************************************************************
function wareki_year($year)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function wareki_year($year){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini�Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date = array();
	$wareki = "";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$date = explode('-',$start_array[$i]);
		if($year >= $date[0])
		{
			$wareki = $nenngou_array[$i]." ".($year-$date[0]+1);
			break;
		}
	}
	return($wareki);
	
}
/************************************************************************************************************
function wareki_year_befor($year)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function wareki_year_befor($year){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini�Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date = array();
	$wareki = "";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$date = explode('-',$start_array[$i]);
		if($year > $date[0])
		{
			$wareki = $nenngou_array[$i]." ".($year-$date[0]+1);
			break;
		}
	}
	return($wareki);
	
}

/************************************************************************************************************
function wareki_date($date)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function wareki_date($date)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);													// form.ini�Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$start =  $item_ini['wareki']['start'];
	$start_array = explode(',',$start);
	$nenngou = $item_ini['wareki']['nenngou'];
	$nenngou_array = explode(',',$nenngou);
	$date_array = explode('-',$date);
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$start_date = array();
	$wareki ="";
	
	
	for($i = 0; $i < count($start_array) ; $i++)
	{
		$start_date = explode('-',$start_array[$i]);
		if(strtotime($date) >= strtotime($start_array[$i]))
		{
			$wareki = $nenngou_array[$i]." ".($date_array[0]-$start_date[0]+1);
			break;
		}
	}
	return($wareki);
}
/************************************************************************************************************
function make_mail_radio($user,$adress)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function make_mail_radio($user,$adress)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$mail_table = "";
	$id = "";
	$error = "";
	$disabled = "";
	$count = 0;
	$count1 = 0;
	$count_str = "";
	$result_mail = array();
	
	//------------------------//
	//          ����          //
	//------------------------//
	$mail_table = "<table class ='mail' id = 'mail'>";
	$mail_table .= "<thead><tr><th><a class ='head'>���ʊm�F</a></th>";
	$mail_table .= "<th><a class ='head'>���q�l��</a></th>";
	$mail_table .= "<th>�X�e�[�^�X</th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$count++;
		$mail_table  .= "<tr>";
		if(($i%2) == 0)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'�@');
		if($adress[$i] == '')
		{
			$error = '���[���A�h���X���o�^�̂��ߑ��M�s��';
			$disabled = "disabled";
		}
		else
		{
			$error = "";
			$disabled = "";
			$count1++;
		}
		$mail_table .= "<td  class='center' ".$id.">";
		$mail_table .= '<input type = "radio" name="radio" value="'.$i.'" id = "radio_'.$i.'" '.$disabled.'
						 onClick ="check_mail(this.id); ">';
		$mail_table .= "</td><td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td>";
		$mail_table .= "<td ".$id."><a class = 'error'>".$error."</a></td></tr>";
	}
	$mail_table .= "</table>";
	$count_str = "���[�����s�I������ ".$count."��(���s�\���� ".$count1."��)";
	$result_mail[0] = $mail_table;
	$result_mail[1] = $count_str;
	
	return($result_mail);
}
/************************************************************************************************************
function make_mail_result($user,$error,$adress)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function make_mail_result($user,$error,$adress)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$mail_table = "";
	$id = "";
	$result = '';
	$counter = 0;
	$counter_str = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$mail_table = "<table class ='mail'>";
	$mail_table .= "<thead><tr><th><a class ='head'>����</a></th>";
	$mail_table .= "<th><a class ='head'>���q�l��</a></th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'�@');
		if($adress[$i] != "")
		{
			$mail_table  .= "<tr>";
			if(($counter%2) == 0)
			{
				$id = "";
			}
			else
			{
				$id = "id = 'stripe'";
			}
			if($error[$i] == '')
			{
				$result = '����';
			}
			else
			{
				$result = '���s';
			}
			$mail_table .= "<td  class='center' ".$id.">";
			$mail_table .= "<a class = 'body'>".$result."</a>";
			$mail_table .= "</td><td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td></tr>";
			$counter++;
		}
	}
	$mail_table .= "</table>";
	$counter_str = "���[�����s���� ".$counter."��";
	$counter_str .= $mail_table;
	
	return($counter_str);
}
/************************************************************************************************************
function make_scv($post,$str)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function make_scv($post,$str)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$csv_str = '';
	
	//------------------------//
	//          ����          //
	//------------------------//
	foreach($post as $key => $value)
	{
		if(strstr($key,$str) !=false )
		{
			$csv_str .= $key.',';
		}
	}
	$csv_str = substr($csv_str,0,-1);
	
	return($csv_str);
}


/************************************************************************************************************
function make_limit_mail($message)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function make_limit_mail($message)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$mail_ini = parse_ini_file('./ini/mail.ini', true);																			// mail.ini�Ăяo��
	require_once("f_mail.php");																									// mail�֐��Ăяo������
	//------------------------//
	//          �萔          //
	//------------------------//
	$title = $mail_ini['limit']['title'];
	$adress = $mail_ini['limit']['send_add'];
	$pre_sentence = $mail_ini['limit']['header1'];
	$pre_sentence_array = explode('~',$pre_sentence);
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$mail_result = array();
	$sentence = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	for($i = 0 ; $i < count($pre_sentence_array) ; $i++)
	{
		if($pre_sentence_array[$i] == '<br>')
		{
			$sentence .= "\r\n";
		}
		else if($pre_sentence_array[$i] == 'limit')
		{
			$sentence .= $message;
		}
		else
		{
			$sentence .= $pre_sentence_array[$i];
		}
	}
	sendmail($adress,$title,$sentence);
}


/************************************************************************************************************
function key_value($key,$post)

			�o�^�p���̓t�H�[���쐬�֐�

����	$post			�t�H�[��value�l

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function key_value($key,$post)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$form_ini = parse_ini_file('./ini/form.ini', true);																			// form.ini�Ăяo��
	$param_ini = parse_ini_file('./ini/param.ini', true);																		// param.ini�Ăяo��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$columnum = $param_ini[$key]['column_num'];
	$type = $form_ini[$columnum]['form_type'];
	$serch_str = "form_".$columnum."_";
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$count_num = 0;
	$result = "";
	$tani = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	if($type == 1 || $type == 2)
	{
		$count_num = 3;
	}
	else if($type == 3 || $type == 4)
	{
		$count_num = 2;
	}
	else
	{
		$count_num = 1;
	}
	for($i = 0; $i < $count_num ; $i++)
	{
		if($count_num == 1)
		{
			if(isset($post[$serch_str.$i]))
			{
				$result .= $post[$serch_str.$i];
			}
		}
		else
		{
			switch($i)
			{
			case 0:
				$tani ="�N";
				break;
			case 1:
				$tani ="��";
				break;
			case 2:
				$tani ="��";
				break;
			default:
				$tani ="";
			}
			$result .= $tani."�F ";
			if(isset($post[$serch_str.$i]))
			{
				$result .= $post[$serch_str.$i];
				if($post[$serch_str.$i] != "")
				{
					$result .= $tani;
				}
			}
			$result .= " ";
		}
	}
	return $result;
}

/************************************************************************************************************
function make_label_list($user,$userpostcd,$adress)

			���x���p���X�g�쐬�֐�

����	$user		���[�U�[��
		$userpostcd ���[�U�[�X�֔ԍ�
		$adress		���[�U�[�A�h���X

�߂�l	���̓t�H�[��html
************************************************************************************************************/
function make_label_list($user,$userpostcd,$adress)
{
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$label_table = "";
	$id = "";
	$error = "";
	$disabled = "";
	$count = 0;
	$count_str = "";
	$result_label = array();
	
	//------------------------//
	//          ����          //
	//------------------------//
	$label_table = "<table class ='label'>";
	$label_table .= "<thead><tr><th><a class ='head'>���q�l��</a></th>";
	$label_table .= "<th><a class ='head'>�X�֔ԍ�</a></th>";
	$label_table .= "<th>�Z��</th></tr></thead><tbody>";
	for($i = 0 ; $i < count($user) ; $i++)
	{
		$count++;
		$label_table  .= "<tr>";
		if(($i%2) == 0)
		{
			$id = "";
		}
		else
		{
			$id = "id = 'stripe'";
		}
		$adress[$i] = trim($adress[$i]);
		$adress[$i] = trim($adress[$i],'�@');
		$post1 = substr($userpostcd[$i], 0, 3 );
		$post2 = substr($userpostcd[$i], 3, 4 );
		$userpostcd[$i] = $post1."-".$post2;

		$label_table .= "<td class='name' ".$id."><a class = 'body'>".$user[$i]."</a></td>";
		$label_table .= "</td><td class='postcd' ".$id."><a class = 'body'>".$userpostcd[$i]."</a></td>";
		$label_table .= "<td class='adress' ".$id.">".$adress[$i]."</td></tr>";
	}
	$label_table .= "</table>";
	$count_str = "���x�����s�I������ ".$count."��";
	$result_label[0] = $label_table;
	$result_label[1] = $count_str;
	$result_label[2] = $count;
	
	return($result_label);
}

/************************************************************************************************************
function period_pulldown_set($name,$over,$post,$ReadOnly,$formName,$isnotnull)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function period_pulldown_set($name,$over,$post,$ReadOnly,$formName,$isnotnull){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$month = date_create('NOW');
	$month = date_format($month, "n");
	$startyear = $item_ini['period']['startyear'];
	$startmonth = $item_ini['period']['startmonth'];
	$period = 0;
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$pulldown = "";
	$num = 0;
	$text = "";
	$value ="";
	$formname ="";
	$select = "";
	$isSelect = false;
	$isdisable = "";
	$disable = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	$period = $year - $startyear;
	if($startmonth <= $month)
	{
		$period = $period + 1;
	}
	if($filename == 'nenzi_5')
	{
		$period = $period - 1;
	}
	if($ReadOnly == '')
	{
		$isdisable = "";
	}
	else
	{
		$isdisable = 'disabled';
	}
	if($over !="")
	{
		$formname = $name."_0_".$over;
	}
	else
	{
		$formname = $name."_0";
	}
	
	$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.',\''.$formName.'\');">';
	for($i = 1 ;$i <= $period ; $i++)
	{
		$text = $i."��";
		$value = $i;
		if(isset($post[$formname]))
		{
			if($value == $post[$formname])
			{
				$select = ' selected ';
				$isSelect=true;
				$disable = "";
			}
		}
		$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
		$select = "";
	}
	if($isSelect)
	{
		$pulldown.='<option value ="" > </option>';
	}
	else
	{
		$pulldown.='<option value ="" selected > </option>';
	}
	return $pulldown;
}

/************************************************************************************************************
function month_pulldown_set($name,$over,$post,$ReadOnly,$formName,$isnotnull)

����	$post

�߂�l	�Ȃ�
************************************************************************************************************/
function month_pulldown_set($name,$over,$post,$ReadOnly,$formName,$isnotnull){
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$pulldown = "";
	$num = 0;
	$text = "";
	$value ="";
	$formname ="";
	$select = "";
	$isSelect = false;
	$isdisable = "";
	$disable = "";
	
	//------------------------//
	//          ����          //
	//------------------------//
	if($ReadOnly == '')
	{
		$isdisable = "";
	}
	else
	{
		$isdisable = 'disabled';
	}
	if($over !="")
	{
		$formname = $name."_0_".$over;
	}
	else
	{
		$formname = $name."_0";
	}
	
	$pulldown.='<select id="'.$formname.'"  class ="'.$ReadOnly.'" name="'.$formname.'"
					 onMouseOver ="change(this.id,\''.$ReadOnly.'\',\''.$formName.'\');" 
					onChange = "notnullcheck(this.id,'.$isnotnull.',\''.$formName.'\');">';
	for($i = 1 ;$i <= 12 ; $i++)
	{
		$text = $i."��";
		$value = $i;
		if(isset($post[$formname]))
		{
			if($value == $post[$formname])
			{
				$select = ' selected ';
				$isSelect=true;
				$disable = "";
			}
		}
		$pulldown.='<option value ="'.$value.'" '.$select.' >'.$text.'</option>';
		$select = "";
	}
	if($isSelect)
	{
		$pulldown.='<option value ="" > </option>';
	}
	else
	{
		$pulldown.='<option value ="" selected > </option>';
	}
	return $pulldown;
}


?>
