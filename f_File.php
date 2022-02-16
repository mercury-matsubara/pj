<?php

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////










/****************************************************************************************
function csv_write($CSV)


����1	$CSV				CSV
����2	$csv_path			CSV�t�@�C���p�X

�߂�l	�Ȃ�
****************************************************************************************/
function csv_write($CSV) {
	
    //------------------------//
    //          �萔          //
    //------------------------//
    $csv_path = "./List/List_".session_id().".csv";



    //--------------------------//
    //  CSV�t�@�C���̒ǋL����  //
    //--------------------------//

//	$CSV = mb_convert_encoding($CSV,'sjis-win','utf-8');																		// �擾string�����R�[�h�ϊ�

    $fp = fopen($csv_path, 'ab');																								// CSV�t�@�C����ǋL�������݂ŊJ��
    // �t�@�C�����J������ //
    if ($fp)
    {
            // �t�@�C���̃��b�N���ł����� //
            if (flock($fp, LOCK_EX))																								// ���b�N
            {
                    // ���O�̏������݂����s������ //
                    if (fwrite($fp , $CSV."\r\n") === FALSE)																			// CSV�ǋL��������
                    {
                            // �������ݎ��s���̏���
                    }

                    flock($fp, LOCK_UN);																								// ���b�N�̉���
            }
            else
            {
                    // ���b�N���s���̏���
            }
    }
    fclose($fp);																												// �t�@�C�������
    return($csv_path);
}	

/****************************************************************************************
function check_mail()


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/
function check_mail(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$mial_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$check_path = $mial_ini['syaken']['file_path'];																				// ���M�m�F�t�@�C��
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$month = date_create('NOW');
	$month = date_format($month, "m");
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSV�t�@�C���̒ǋL����  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
	// �t�@�C�����J������ //
	if ($fp)
	{
		// �t�@�C���̃��b�N���ł����� //
		if (flock($fp, LOCK_EX))																								// ���b�N
		{
			$buffer = fgets($fp);
			if($buffer != $year.$month)
			{
				ftruncate( $fp,0);
				// ���O�̏������݂����s������ //
				if (fwrite($fp ,$year.$month) === FALSE)																		// check_mail�ǋL��������
				{
					// �������ݎ��s���̏���
				}
				syaken_mail_select();
			}
			flock($fp, LOCK_UN);																								// ���b�N�̉���
		}
		else
		{
			// ���b�N���s���̏���
		}
	}
	fclose($fp);																												// �t�@�C�������
}	

/****************************************************************************************
function limit_mail($message)


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/
function limit_mail($message){


    //------------------------//
    //        �����ݒ�        //
    //------------------------//
    $mial_ini = parse_ini_file('./ini/mail.ini', true);
    require_once("f_Form.php");																									// Form�֐��Ăяo������

    //------------------------//
    //          �萔          //
    //------------------------//
    $check_path = $mial_ini['limit']['file_path'];																				// ���M�m�F�t�@�C��
    $date = date_create("NOW");
    $date = date_format($date, "Y-m-d");


    //------------------------//
    //          �ϐ�          //
    //------------------------//
    $buffer = "";

    //--------------------------//
    //  CSV�t�@�C���̒ǋL����  //
    //--------------------------//

    if(!file_exists($check_path))
    {
            $fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
            fclose($fp);				
    }

    $fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
    // �t�@�C�����J������ //
    if ($fp)
    {
            // �t�@�C���̃��b�N���ł����� //
            if (flock($fp, LOCK_EX))																								// ���b�N
            {
                    $buffer = fgets($fp);
                    if($buffer == "")
                    {
                            ftruncate( $fp,0);
                            // ���O�̏������݂����s������ //
                            if (fwrite($fp ,$date) === FALSE)																		// check_mail�ǋL��������
                            {
                                    // �������ݎ��s���̏���
                            }
                            else
                            {
                                    make_limit_mail($message);
                            }
                    }
                    flock($fp, LOCK_UN);																								// ���b�N�̉���
            }
            else
            {
                    // ���b�N���s���̏���
            }
    }
    fclose($fp);																												// �t�@�C�������
}
/****************************************************************************************
function getuzi_rireki()


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/

function getuzi_rireki(){


    //------------------------//
    //        �����ݒ�        //
    //------------------------//
    $file_ini = parse_ini_file('./ini/file.ini', true);

    //------------------------//
    //          �萔          //
    //------------------------//
    $filename = $_SESSION['filename'];
    $check_path = $file_ini[$filename]['file_path'];
    $date = date_create('NOW');
    $date = date_format($date, "Y-m-d");


    //------------------------//
    //          �ϐ�          //
    //------------------------//
    $buffer = "";

    //--------------------------//
    //  CSV�t�@�C���̒ǋL����  //
    //--------------------------//

    if(!file_exists($check_path))
    {
            $fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
            fclose($fp);				
    }

    $fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
    // �t�@�C�����J������ //
    if ($fp)
    {
            // �t�@�C���̃��b�N���ł����� //
            if (flock($fp, LOCK_EX))																								// ���b�N
            {
                    $buffer = fgets($fp);
                    flock($fp, LOCK_UN);																								// ���b�N�̉���
            }
            else
            {
                    // ���b�N���s���̏���
            }
    }
    fclose($fp);																												// �t�@�C�������
    return($buffer);
}

/****************************************************************************************
function nenzi_rireki()


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/

function nenzi_rireki(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSV�t�@�C���̒ǋL����  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
	// �t�@�C�����J������ //
	if ($fp)
	{
		// �t�@�C���̃��b�N���ł����� //
		if (flock($fp, LOCK_EX))																								// ���b�N
		{
			$buffer = fgets($fp);
			flock($fp, LOCK_UN);																								// ���b�N�̉���
		}
		else
		{
			// ���b�N���s���̏���
		}
	}
	fclose($fp);																												// �t�@�C�������
	return($buffer);
}
/****************************************************************************************
function deletedate_change()


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/
function deletedate_change(){
	
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// ���M�m�F�t�@�C��
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	if($filename == 'getuzi_5')
	{
		$period = $_SESSION['getuji']['period'];
		$month = $_SESSION['getuji']['month'];
		$date = $period."�� ".$month."�� ( ���s���F ".$date." )";
	}
	if($filename == 'nenzi_5')
	{
		$period = $_SESSION['nenzi']['period'];
		$date = $period."�� ( ���s�� �F".$date." )";
	}

	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSV�t�@�C���̒ǋL����  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
	// �t�@�C�����J������ //
	if ($fp)
	{
		// �t�@�C���̃��b�N���ł����� //
		if (flock($fp, LOCK_EX))																								// ���b�N
		{
			ftruncate( $fp,0);
			// ���O�̏������݂����s������ //
			if (fwrite($fp ,$date) === FALSE)																		// check_mail�ǋL��������
			{
				// �������ݎ��s���̏���
			}
			flock($fp, LOCK_UN);																								// ���b�N�̉���
		}
		else
		{
			// ���b�N���s���̏���
		}
	}
	fclose($fp);																												// �t�@�C�������
}	

/****************************************************************************************
function Delete_rireki()


����	�Ȃ�

�߂�l	�Ȃ�
****************************************************************************************/

function Delete_rireki(){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// ���M�m�F�t�@�C��
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSV�t�@�C���̒ǋL����  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// ���M�m�F�t�@�C����ǋL�������݂ŊJ��
	// �t�@�C�����J������ //
	if ($fp)
	{
		// �t�@�C���̃��b�N���ł����� //
		if (flock($fp, LOCK_EX))																								// ���b�N
		{
			$buffer = fgets($fp);
			flock($fp, LOCK_UN);																								// ���b�N�̉���
		}
		else
		{
			// ���b�N���s���̏���
		}
	}
	fclose($fp);																												// �t�@�C�������
	return($buffer);
}
/***************************************************************************
function FileReadInsert()


����			�Ώۃt�@�C���p�X

�߂�l			�Ȃ�
***************************************************************************/
function FileReadInsert(){
//----2018/01/22 ����32 asanoma �\�[�g�폜�Ή� start ----->>
//    //----2018/01/18 ����32 asanoma PJ�i�����捞�Ή� start ----->>
//    //
//    //    require_once("f_DB.php");
//    //    $form_ini = parse_ini_file('./ini/form.ini', true);
//    //    $filename = $_SESSION['filename'];
//    //    $tablenum = $form_ini[$filename]['use_maintable_num'];
//    //    $columns = $form_ini[$filename]['result_num'];
//    //    $columns = str_replace("202","202,203",$columns);
//    //    $columns = str_replace("303","302,303",$columns);
//    //    $columns_array = explode(',',$columns);
//    //    $restable = "";
//    //    $isAlrady = 0;
//    //    $con = dbconect();
//    //    $res = 0;
//    //    $FilePath = "temp/tempfileinsert.txt";
//    //    $insert_SQL = "";
//    //    $strsub = explode(',',"");
//    //    $id = "";
//    //    $count = 0;
//    //    $countlow = 0;
//    //    $readHeader = array();
//    //    $readBody = array();
//    //    $getujiflg = true;
//    //    $errorflg = true;													//�G���[�t���O
//    //    $judge = false;
//    //    $error1 = false;
//    //    $error2 = false;
//    //    $teizicheck = 0;
//    //    $date = array();
//    //    $errormessage = "";
//    //
//    //    $file = fopen($FilePath, "r");
//    //    if($file){
//    //            while ($line = fgets($file)) 
//    //            { 
//    //                    $isAlrady = true;											//�o�^�ς݃t���O
//    //                    $checkflg = true;											//���̓`�F�b�N�t���O
//    //                    $pjudge = true;												//PJ�R�[�h�o�^�ς݃t���O
//    //                    $ejudge = true;												//�}�ԓo�^�ς݃t���O
//    //                    $kjudge = true;												//�H���ԍ��o�^�ς݃t���O
//    //                    $pcheck = true;												//PJ�R�[�h���̓`�F�b�N�t���O
//    //                    $echeck = true;												//�}�ԓ��̓`�F�b�N�t���O
//    //                    $kcheck = true;												//�H���ԍ����̓`�F�b�N�t���O
//    //                    $code1 = "";
//    //                    $code2 = "";
//    //                    $code3 = "";
//    //                    $strsub = explode(",", $line); //�J���}��؂�̃f�[�^���擾
//    //                    if($countlow == 0)
//    //                    {
//    //                            $date =  mb_convert_encoding($strsub[0], "SJIS", "SJIS");
//    //                            $date = explode('/',$date);
//    //                            $readHeader[0] = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);			//���t
//    //                            $readHeader[1] =  mb_convert_encoding(str_replace(array("\r\n", "\r", "\n"),'', $strsub[1]), "SJIS", "SJIS");				//���[�U�[�ԍ�
//    //                            //�����σ`�F�b�N
//    //                            $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
//    //                            $result = $con->query($sql);
//    //                            $rows = $result->num_rows;
//    //                            if($rows > 0)
//    //                            {
//    //                                    $getujiflg = false;
//    //                                    $errorflg = false;
//    //                                    $errormessage = "<div class = 'center'><a class = 'error'>���Ɍ����������������Ă�����Ԃ̂��߁A�o�^�ł��܂���B</a></div><br>";
//    //                            }
//    //                            else
//    //                            {
//    //                                    //4CODE�擾
//    //                                    $sql = "SELECT * FROM syaininfo WHERE STAFFID = '".$readHeader[1]."';";
//    //                                    $result = $con->query($sql) or ($judge = true);
//    //                                    if($judge)
//    //                                    {
//    //                                            error_log($con->error,0);
//    //                                            $errorflg = false;
//    //                                    }
//    //                                    $result_row = $result->fetch_array(MYSQLI_ASSOC);
//    //                                    $code4 = $result_row['4CODE'];
//    //                            }
//    //                    }
//    //                    else
//    //                    {
//    //                            $pj = mb_convert_encoding($strsub[0], "SJIS", "SJIS");			//PJ�R�[�h
//    //                            $edaban = mb_convert_encoding($strsub[1], "SJIS", "SJIS");		//�}�ԃR�[�h
//    //                            $koutei = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//�H��
//    //                            $teizi = (float)mb_convert_encoding($strsub[3], "SJIS", "SJIS");		//�莞����
//    //                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[4]);
//    //                            $zangyo = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//�c�Ǝ���
//    //                            $teizicheck += $teizi;
//    //                            //���͒l�����p�p�����`�F�b�N
//    //                            if (preg_match("/^[a-zA-Z0-9]+$/", $pj))
//    //                            {
//    //                                    //DB�Ƀf�[�^�����݂��邩�`�F�b�N
//    //                                    $sql = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$pj."';";
//    //                                    $result = $con->query($sql) or ($judge = true);
//    //                                    if($judge)
//    //                                    {
//    //                                            error_log($con->error,0);
//    //                                            $errorflg = false;
//    //                                    }
//    //                                    $rows = $result->num_rows;
//    //                                    if($rows < 1)
//    //                                    {
//    //                                            $errorflg = false;
//    //                                            $isAlrady = false;
//    //                                            $pjudge = false;
//    //                                    }
//    //                                    else
//    //                                    {
//    //                                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//    //                                            $code1 = $result_row['1CODE'];
//    //                                    }
//    //                            }
//    //                            else
//    //                            {
//    //                                    $errorflg = false;
//    //                                    $checkflg = false;
//    //                                    $pcheck = false;
//    //                            }
//    //
//    //                            //���͒l�����p�p�����`�F�b�N
//    //                            if (preg_match("/^[a-zA-Z0-9]+$/", $edaban))
//    //                            {
//    //                                    $sql = "SELECT * FROM edabaninfo WHERE EDABAN = '".$edaban."';";
//    //                                    $result = $con->query($sql) or ($judge = true);
//    //                                    if($judge)
//    //                                    {
//    //                                            error_log($con->error,0);
//    //                                            $errorflg = false;
//    //                                    }
//    //                                    $rows = $result->num_rows;
//    //                                    if($rows < 1)
//    //                                    {
//    //                                            $errorflg = false;
//    //                                            $isAlrady = false;
//    //                                            $ejudge = false;
//    //                                    }
//    //                                    else
//    //                                    {
//    //                                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//    //                                            $code2 = $result_row['2CODE'];
//    //                                            $readBody[($countlow-1)]['pjname'] = $result_row['PJNAME'];
//    //                                    }
//    //                            }
//    //                            else
//    //                            {
//    //                                    $errorflg = false;
//    //                                    $checkflg = false;
//    //                                    $echeck = false;
//    //                            }
//    //
//    //                            //���͒l�����p�p�����`�F�b�N
//    //                            if (preg_match("/^[a-zA-Z0-9]+$/", $koutei))
//    //                            {
//    //                                    $sql = "SELECT * FROM kouteiinfo WHERE KOUTEIID = '".$koutei."';";
//    //                                    $result = $con->query($sql) or ($judge = true);
//    //                                    if($judge)
//    //                                    {
//    //                                            error_log($con->error,0);
//    //                                            $errorflg = false;
//    //                                    }
//    //                                    $rows = $result->num_rows;
//    //                                    if($rows < 1)
//    //                                    {
//    //                                            $errorflg = false;
//    //                                            $isAlrady = false;
//    //                                            $kjudge = false;
//    //                                    }
//    //                                    else
//    //                                    {
//    //                                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//    //                                            $readBody[($countlow-1)]['3CODE'] = $result_row['3CODE'];
//    //                                            $readBody[($countlow-1)]['kouteiname'] = $result_row['KOUTEINAME'];
//    //                                    }
//    //                            }
//    //                            else
//    //                            {
//    //                                    $errorflg = false;
//    //                                    $checkflg = false;
//    //                                    $kcheck = false;
//    //                            }
//    //
//    //                            //��荞�݌��ʗp�z��ɓ���
//    //                            if($getujiflg)
//    //                            {
//    //                                    //����
//    //                                    if($isAlrady && $checkflg)
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'OK';
//    //                                            $readBody[($countlow-1)]['message'] = '�������';
//    //                                            $sql = "SELECT * FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2.";";
//    //                                            $result = $con->query($sql) or ($judge = true);
//    //                                            if($judge)
//    //                                            {
//    //                                                    error_log($con->error,0);
//    //                                                    $errorflg = false;
//    //                                            }
//    //                                            $rownums = $result->num_rows;
//    //                                            if($rownums == 1)
//    //                                            {
//    //                                                    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//    //                                                    {
//    //                                                            $code5 = $result_row['5CODE'];
//    //                                                    }
//    //                                            }
//    //                                            else
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                                    $readBody[($countlow-1)]['message'] = "�Y������v���W�F�N�g���o�^����Ă��܂���B";
//    //                                                    $errorflg = false;
//    //                                            }
//    //
//    //                                            if($readBody[($countlow-1)]['judge'] == 'OK')
//    //                                            {
//    //                                                    $sql = "SELECT * FROM projectditealinfo WHERE 4CODE = ".$code4." AND 5CODE = ".$code5.";";
//    //                                                    $result = $con->query($sql) or ($judge = true);
//    //                                                    if($judge)
//    //                                                    {
//    //                                                            error_log($con->error,0);
//    //                                                            $errorflg = false;
//    //                                                    }
//    //                                                    $rownums = $result->num_rows;
//    //                                                    if($rownums == 1)
//    //                                                    {
//    //                                                            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['6CODE'] = $result_row['6CODE'];
//    //                                                                    $count++;
//    //                                                            }
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['6CODE'] = "";
//    //                                                            $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                                            $readBody[($countlow-1)]['message'] = "�Y������Ј��ʃv���W�F�N�g���o�^����Ă��܂���B";
//    //                                                            $errorflg = false;
//    //                                                    }
//    //                                            }
//    //                                    }
//    //                                    //�o�^�ς݊����̓G���[
//    //                                    else if($isAlrady && !$checkflg)
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                            if(!$pcheck)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] .= '�v���W�F�N�g�R�[�h';
//    //                                            }
//    //                                            if(!$echeck)
//    //                                            {
//    //                                                    if(!empty($readBody[($countlow-1)]['message']))
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',�}��';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '�}��';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kcheck)
//    //                                            {
//    //                                                    if(!empty($readBody[($countlow-1)]['message']))
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',�H���ԍ�';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '�H���ԍ�';
//    //                                                    }
//    //                                            }
//    //                                            $readBody[($countlow-1)]['message'] .= '�����p�p���݂̂œ��͂���Ă��܂���B';
//    //                                    }
//    //                                    //���o�^
//    //                                    else
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                            if(!$pjudge)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] = '�v���W�F�N�g�R�[�h';
//    //                                            }
//    //                                            if(!$ejudge)
//    //                                            {
//    //                                                    if($readBody[($countlow-1)]['message'] == '')
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] = '�}��';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',�}��';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kjudge)
//    //                                            {
//    //                                                    if($readBody[($countlow-1)]['message'] == '')
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] = '�H���ԍ�';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',�H���ԍ�';
//    //                                                    }
//    //                                            }
//    //                                            $readBody[($countlow-1)]['message'] .= '�����݂��܂���B';
//    //                                            //���̓G���[
//    //                                            if(!$checkflg)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] .= '<br>';
//    //                                                    if(!$pcheck)
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '�v���W�F�N�g�R�[�h';
//    //                                                    }
//    //                                                    if(!$echeck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',�}��';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '�}��';
//    //                                                            }
//    //                                                    }
//    //                                                    if(!$kcheck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',�H���ԍ�';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '�H���ԍ�';
//    //                                                            }
//    //                                                    }
//    //                                                    $readBody[($countlow-1)]['message'] .= '�����p�p���݂̂œ��͂���Ă��܂���B';
//    //                                            }
//    //                                    }
//    //                            }
//    //                            else
//    //                            {
//    //                                    if($isAlrady && $checkflg)
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                            $readBody[($countlow-1)]['message'] = '�����ς̂��ߓo�^�s�ł��B';
//    //                                            $sql = "SELECT * FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2.";";
//    //                                            $result = $con->query($sql) or ($judge = true);
//    //                                            if($judge)
//    //                                            {
//    //                                                    error_log($con->error,0);
//    //                                                    $judge = false;
//    //                                            }
//    //                                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//    //                                            $code5 = $result_row['5CODE'];
//    //                                            $sql = "SELECT * FROM projectditealinfo WHERE 4CODE = ".$code4." AND 5CODE = ".$code5.";";
//    //                                            $result = $con->query($sql) or ($judge = true);
//    //                                            if($judge)
//    //                                            {
//    //                                                    error_log($con->error,0);
//    //                                                    $judge = false;
//    //                                            }
//    //                                            $rownums = $result->num_rows;
//    //                                            if($rownums == 1)
//    //                                            {
//    //                                                    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//    //                                                    {
//    //                                                            $code6 = $result_row['6CODE'];
//    //                                                            $count++;
//    //                                                    }
//    //                                                    $readBody[($countlow-1)]['6CODE'] = $code6;
//    //                                            }
//    //                                            else
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['6CODE'] = "";
//    //                                            }
//    //                                    }
//    //                                    else
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'NG';
//    //                                            $readBody[($countlow-1)]['message'] = '�����ς̂��ߓo�^�s�ł��B<br>';
//    //                                            if(!$pjudge)
//    //                                            {
//    //                                                    $message = '�v���W�F�N�g�R�[�h';
//    //                                            }
//    //                                            if(!$ejudge)
//    //                                            {
//    //                                                    if($message == '')
//    //                                                    {
//    //                                                            $message = '�}��';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $message .= ',�}��';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kjudge)
//    //                                            {
//    //                                                    if($message == '')
//    //                                                    {
//    //                                                            $message = '�H���ԍ�';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $message .= ',�H���ԍ�';
//    //                                                    }
//    //                                            }
//    //                                            $message.= '�����݂��܂���B';
//    //                                            $readBody[($countlow-1)]['message'] .= $message;
//    //                                            if(!$checkflg)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] .= '<br>';
//    //                                                    if(!$pcheck)
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '�v���W�F�N�g�R�[�h';
//    //                                                    }
//    //                                                    if(!$echeck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',�}��';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '�}��';
//    //                                                            }
//    //                                                    }
//    //                                                    if(!$kcheck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',�H���ԍ�';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '�H���ԍ�';
//    //                                                            }
//    //                                                    }
//    //                                                    $readBody[($countlow-1)]['message'] .= '�����p�p���݂̂œ��͂���Ă��܂���B';
//    //                                            }
//    //                                    }
//    //                            }
//    //                    }
//    //                    $countlow = $countlow  + 1;
//    //            }
//    //            if(!$checkflg)
//    //            {
//    //                    $errormessage .= "<div class = 'center'><a class = 'error'>���͒l�Ɍ�肪���邽�߁A�o�^�ł��܂���B</a></div><br>";
//    //            }
//    //            //�莞�`�F�b�N
//    //            if($teizicheck < 7.75)
//    //            {
//    //                    $errorflg = false;
//    //                    $error1 = true;
//    //                    $errormessage .= "<a class = 'error'>�K��̒莞���ԂɒB���Ă��܂���B</a><br><br>";
//    //            }
//    //            else if($teizicheck > 7.75)
//    //            {
//    //                    $errorflg = false;
//    //                    $error2 = true;
//    //                    $errormessage .= "<a class = 'error'>�K��̒莞���Ԃ��z���Ă��܂��B</a><br><br>";
//    //            }
//    //    }
//    //    fclose($file);
//    //
//    //    //��荞�݌��ʃe�[�u���쐬
//    //    $restable = "<div><center>���t�F".$readHeader[0]."�@���[�U�[�ԍ��F".$readHeader[1]."<br><br>"; 
//    //    if(!empty($errormessage))
//    //    {
//    //            $restable .= $errormessage;
//    //    }
//    //    if($errorflg)
//    //    {
//    //            $_SESSION['fileinsert']['judge'] = true;
//    //            $restable .= "<table class='list'><tr>"; 
//    //            for($i = 0 ; $i < count($columns_array) ; $i++)
//    //            {
//    //                    $title_name = $form_ini[$columns_array[$i]]['link_num'];
//    //                    $restable .="<th><a class ='head'>".$title_name."</a></th>";
//    //            }
//    //            $restable .= "</tr>";
//    //            for($i = 0 ; $i < count($readBody); $i++)
//    //            {
//    //                    $count = 0;
//    //                    $rownums = 0;
//    //                    $restable .= "<tr><td ".$id. " class = 'center'>".$readBody[$i]['pj']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['edaban']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['pjname']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['koutei']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['kouteiname']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".number_format($readBody[$i]['teizi'],2,".","")."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".number_format($readBody[$i]['zangyo'],2,".","")."</td></tr>";
//    //                    //�����i���f�[�^�̌���
//    //                    $sql = "SELECT * FROM progressinfo WHERE 3CODE = '".$readBody[$i]['3CODE']."' AND 6CODE = '".$readBody[$i]['6CODE']."' AND SAGYOUDATE = '".$readHeader[0]."';";
//    //                    $result = $con->query($sql) or ($judge = true);
//    //                    if($judge)
//    //                    {
//    //                            error_log($con->error,0);
//    //                            $judge = false;
//    //                    }
//    //                    $rownums = $result->num_rows;
//    //                    if($rownums == 1)
//    //                    {
//    //                            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//    //                            {
//    //                                    //�����i���f�[�^�̍폜
//    //                                    $code7 = $result_row['7CODE'];
//    //                                    $count++;
//    //                            }
//    //                            $sql = "DELETE FROM progressinfo WHERE 7CODE = ".$code7.";";
//    //                            $result = $con->query($sql) or ($judge = true);
//    //                            if($judge)
//    //                            {
//    //                                    error_log($con->error,0);
//    //                                    $judge = false;
//    //                            }
//    //                    }
//    //                    //progressinfo�ɓo�^
//    //                    $sql = "INSERT INTO progressinfo ( 3CODE, 6CODE, SAGYOUDATE, TEIZITIME, ZANGYOUTIME, 7ENDDATE, 7PJSTAT) "
//    //                                    ."VALUE (".$readBody[$i]['3CODE'].",".$readBody[$i]['6CODE'].",'".$readHeader[0]."',".$readBody[$i]['teizi'].",".$readBody[$i]['zangyo'].",NULL,1);";
//    //                    $result = $con->query($sql) or ($judge = true);
//    //                    if($judge)
//    //                    {
//    //                            error_log($con->error,0);
//    //                            $judge = false;
//    //                    }
//    //            }
//    //    }
//    //    else
//    //    {
//    //            $_SESSION['fileinsert']['judge'] = false;
//    //            $restable .= "<table class='list'><tr>"; 
//    //            for($i = 0 ; $i < count($columns_array) ; $i++)
//    //            {
//    //                    $title_name = $form_ini[$columns_array[$i]]['link_num'];
//    //                    $restable .="<th><a class ='head'>".$title_name."</a></th>";
//    //            }
//    //            $restable .="<th><a class ='head'>��荞�݌���</a></th>";
//    //            $restable .="<th><a class ='head'>���b�Z�[�W</a></th></tr>";
//    //            for($i = 0 ; $i < count($readBody); $i++)
//    //            {
//    //                    $restable .= "<tr><td ".$id. " class = 'center'>".$readBody[$i]['pj']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['edaban']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['pjname']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['koutei']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['kouteiname']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".number_format($readBody[$i]['teizi'],2,".","")."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".number_format($readBody[$i]['zangyo'],2,".","")."</td>";
//    //                    if($error1)
//    //                    {
//    //                            $readBody[$i]['judge'] = "NG";
//    //                            $readBody[$i]['message'] = "�莞���ԕs��";
//    //                    }
//    //                    if($error2)
//    //                    {
//    //                            $readBody[$i]['judge'] = "NG";
//    //                            $readBody[$i]['message'] = "�莞����";
//    //                    }
//    //                    if(!$getujiflg)
//    //                    {
//    //                            $readBody[$i]['judge'] = "NG";
//    //                    }
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['judge']."</td>";
//    //                    $restable .= "<td ".$id. " class = 'center'>".$readBody[$i]['message']."</td></tr>";
//    //            }
//    //    }
//

    //------------------------//
    //        �����ݒ�        //
    //------------------------//
    require_once("f_DB.php");
    $form_ini = parse_ini_file('./ini/form.ini', true);
    $con = dbconect();													//DB�ڑ�

    //------------------------//
    //          �萔          //
    //------------------------//
    $filename = $_SESSION['filename'];									//�t�@�C����
    $tablenum = $form_ini[$filename]['use_maintable_num'];				//�e�[�u���ԍ�
    //----2018/01/22 ����32 asanoma �\�[�g�폜�Ή� start ----->>
    //$columns = "704,102,202,203,302,303,705,706";							//�o�^�J����
    $columns = "402,704,102,202,203,302,303,705,706";							//�o�^�J����
    //----2018/01/22 ����32 asanoma �\�[�g�폜�Ή� end -----<<
    $columns_array = explode(',',$columns);
    $FilePath = "temp/tempfileinsert.txt";								//�t�@�C���p�X
	
//
//    //------------------------//
//    //          �ϐ�          //
//    //------------------------//
//    $cnt = 0;
//    $countrow = 0;
//    $readBody = array();												//�ǂݍ��ݔz��
//    $sql = "";
//    $judge = false;														//SQL���f�t���O
//    $staffnum = "";														//�Ј��ԍ�
//    $staffname = "";													//�Ј���
//    $pjname = "";														//���ԁE�Č���
//    $kouteiname = "";													//�H��
//    $date = array();													//���t�z��
//    $getujiflg = true;													//�����G���[�t���O
//    $errorflg = true;													//�G���[�t���O
//    $errormessage = "";
//    $inputerror = "";													//���̓G���[
//    $selecterror = "";													//�o�^�����G���[
//    $teizicheck = 0;													//�莞�`�F�b�N�p�ϐ�
//    $restable = "";
//    $code7 = "";
//    //------------------------//
//    //        �捞����        //
//    //------------------------//
//
//    //�捞�f�[�^��ǂݍ���
//    $file = fopen($FilePath, "r");
//    if($file){
//            while ($line = fgets($file)) 
//            {
//                    $strsub = explode(",", $line); //�J���}��؂�̃f�[�^���擾
//                    if($countrow == 0)
//                    {
//                            $staffnum =  mb_convert_encoding(str_replace(array("\r\n", "\r", "\n"),'', $strsub[0]), "SJIS", "SJIS");				//���[�U�[�ԍ�
//                            //4CODE�擾
//                            $sql = "SELECT * FROM syaininfo WHERE STAFFID = '".$staffnum."';";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                            }
//                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//                            $code4 = $result_row['4CODE'];
//                            $staffname = $result_row['STAFFNAME'];
//                            $restable = "�Ј��ԍ��F".$staffnum."�@�Ј����F".$staffname."<br><br>"; 
//                    }
//                    else
//                    {
//                            $date = mb_convert_encoding($strsub[0], "SJIS", "SJIS");
//                            $date = explode('/',$date);
//                            $day = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);			//���t
//                            if($date[0] != "")
//                            {
//                                    //��Ɠ����Ƃɑ������z��Ɋi�[
//                                    if(!empty($readBody[$day]))
//                                    {
//                                            $cnt = count($readBody[$day]);
//                                            $readBody[$day][$cnt]['date'] = $day;
//                                            $readBody[$day][$cnt]['pj'] = mb_convert_encoding($strsub[1], "SJIS", "SJIS");			//PJ�R�[�h
//                                            $readBody[$day][$cnt]['edaban'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//�}�ԃR�[�h
//                                            $koutei = mb_convert_encoding($strsub[3], "SJIS", "SJIS");
//                                            $readBody[$day][$cnt]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//�H���ԍ�
//                                            $teizi = (float)mb_convert_encoding($strsub[4], "SJIS", "SJIS");
//                                            $readBody[$day][$cnt]['teizi'] = $teizi;												//�莞����
//                                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[5]);
//                                            $readBody[$day][$cnt]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");	//�c�Ǝ���
//                                            $readBody[$day][$cnt]['judge'] = "OK";
//                                            $readBody[$day][$cnt]['message'] = "";
//                                    }
//                                    else
//                                    {
//                                            $readBody[$day][0]['date'] = $day;
//                                            $readBody[$day][0]['pj'] = mb_convert_encoding($strsub[1], "SJIS", "SJIS");			//PJ�R�[�h
//                                            $readBody[$day][0]['edaban'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//�}�ԃR�[�h
//                                            $koutei = mb_convert_encoding($strsub[3], "SJIS", "SJIS");
//                                            $readBody[$day][0]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//�H���ԍ�
//                                            $teizi = (float)mb_convert_encoding($strsub[4], "SJIS", "SJIS");
//                                            $readBody[$day][0]['teizi'] = $teizi;													//�莞����
//                                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[5]);
//                                            $readBody[$day][0]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//�c�Ǝ���
//                                            $readBody[$day][0]['judge'] = "OK";
//                                            $readBody[$day][0]['message'] = "";
//                                    }
//                            }
//                    }
//                    $countrow++;
//            }
//    }
//    fclose($file);
//
//    //------------------------//
//    //       �`�F�b�N����     //
//    //------------------------//
//    $keyarray = array_keys($readBody);
//    foreach($keyarray as $key)
//    {
//            //�����σ`�F�b�N
//            $date = explode('-',$key);
//            $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
//            $result = $con->query($sql);
//            $rows = $result->num_rows;
//            if($rows > 0)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = "NG";
//                            $readBody[$key][$i]['message'] = "���������I���ς݊��Ԃ̂��߁A�o�^�ł��܂���B";
//                    }
//                    $errorflg = false;
//                    $errormessage = "<div class = 'center'><a class = 'error'>���Ɍ����������������Ă�����Ԃ̂��߁A�o�^�ł��܂���B</a></div><br>";
//            }
//            for($i = 0; $i < count($readBody[$key]); $i++)
//            {
//                    //������
//                    $selecterror = "";
//                    $inputerror = "";
//
//                    //���͒l�`�F�b�N
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['pj']))
//                    {
//                            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
//                            $sql = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$readBody[$key][$i]['pj']."';";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                            }
//                            $rows = $result->num_rows;
//                            if($rows < 1)
//                            {
//                                    $errorflg = false;
//                                    $readBody[$key][$i]['judge'] = "NG";
//                                    $selecterror = "�v���W�F�N�g�R�[�h";
//                            }
//                            else
//                            {
//                                    $result_row = $result->fetch_array(MYSQLI_ASSOC);
//                                    $code1 = $result_row['1CODE'];
//                            }
//                    }
//                    else
//                    {
//                            $readBody[$key][$i]['judge'] = "NG";
//                            $inputerror = "�v���W�F�N�g�R�[�h";
//                            $errorflg = false;		
//                    }
//                    //���͒l�`�F�b�N
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['edaban']))
//                    {
//                            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
//                            $sql = "SELECT * FROM edabaninfo WHERE EDABAN = '".$readBody[$key][$i]['edaban']."';";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                            }
//                            $rows = $result->num_rows;
//                            if($rows < 1)
//                            {
//                                    $errorflg = false;
//                                    $readBody[$key][$i]['judge'] = "NG";
//                                    if(!empty($selecterror))
//                                    {
//                                            $selecterror .= "�A�}��";
//                                    }
//                                    else
//                                    {
//                                            $selecterror = "�}��";
//                                    }
//                            }
//                            else
//                            {
//                                    $result_row = $result->fetch_array(MYSQLI_ASSOC);
//                                    $code2 = $result_row['2CODE'];
//                                    $readBody[$key][$i]['pjname'] = $result_row['PJNAME'];
//                            }
//                    }
//                    else
//                    {
//                            $readBody[$key][$i]['judge'] = "NG";
//                            if(!empty($inputerror))
//                            {
//                                    $inputerror .= "�A�}��";
//                            }
//                            else
//                            {
//                                    $inputerror = "�}��";
//                            }
//                            $errorflg = false;		
//                    }
//                    //���͒l�`�F�b�N
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['koutei']))
//                    {
//                            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
//                            $sql = "SELECT * FROM kouteiinfo WHERE KOUTEIID = '".$readBody[$key][$i]['koutei']."';";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                            }
//                            $rows = $result->num_rows;
//                            if($rows < 1)
//                            {
//                                    $errorflg = false;
//                                    $readBody[$key][$i]['judge'] = "NG";
//                                    if(!empty($selecterror))
//                                    {
//                                            $selecterror .= "�A�H���ԍ�";
//                                    }
//                                    else
//                                    {
//                                            $selecterror = "�H���ԍ�";
//                                    }
//                            }
//                            else
//                            {
//                                    $result_row = $result->fetch_array(MYSQLI_ASSOC);
//                                    $readBody[$key][$i]['3CODE'] = $result_row['3CODE'];
//                                    $readBody[$key][$i]['kouteiname'] = $result_row['KOUTEINAME'];
//                            }
//                    }
//                    else
//                    {
//                            $readBody[$key][$i]['judge'] = "NG";
//                            if(!empty($inputerror))
//                            {
//                                    $inputerror .= "�A�H���ԍ�";
//                            }
//                            else
//                            {
//                                    $inputerror = "�H���ԍ�";
//                            }
//                            $errorflg = false;		
//                    }
//                    if(!empty($inputerror))
//                    {
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>".$inputerror."�����p�p���݂̂œ��͂���Ă��܂���B�B";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = $inputerror."�����p�p���݂̂œ��͂���Ă��܂���B";
//                            }
//                    }
//                    if(!empty($selecterror))
//                    {
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>".$selecterror."���o�^����Ă��܂���B";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = $selecterror."���o�^����Ă��܂���B";
//                            }
//                    }
//                    if(empty($inputerror) && empty($selecterror))
//                    {
//                            $sql = "SELECT * FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2.";";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                                    $errorflg = false;
//                            }
//                            $rownums = $result->num_rows;
//                            if($rownums == 1)
//                            {
//                                    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//                                    {
//                                            $code5 = $result_row['5CODE'];
//                                    }
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['judge'] = 'NG';
//                                    if(!empty($readBody[$key][$i]['message']))
//                                    {
//                                            $readBody[$key][$i]['message'] .= "<br>�Y������v���W�F�N�g���o�^����Ă��܂���B";
//                                    }
//                                    else
//                                    {
//                                            $readBody[$key][$i]['message'] = "�Y������v���W�F�N�g���o�^����Ă��܂���B";
//                                    }
//                                    $errorflg = false;
//                            }
//
//                            if($readBody[$key][$i]['judge'] == 'OK')
//                            {
//                                    $sql = "SELECT * FROM projectditealinfo WHERE 4CODE = ".$code4." AND 5CODE = ".$code5.";";
//                                    $result = $con->query($sql) or ($judge = true);
//                                    if($judge)
//                                    {
//                                            error_log($con->error,0);
//                                            $errorflg = false;
//                                    }
//                                    $rownums = $result->num_rows;
//                                    if($rownums == 1)
//                                    {
//                                            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//                                            {
//                                                    $readBody[$key][$i]['6CODE'] = $result_row['6CODE'];
//                                            }
//                                    }
//                                    else
//                                    {
//                                            $readBody[$key][$i]['6CODE'] = "";
//                                            $readBody[$key][$i]['judge'] = 'NG';
//                                            if(!empty($readBody[$key][$i]['message']))
//                                            {
//                                                    $readBody[$key][$i]['message'] .= "<br>�Y������Ј��ʃv���W�F�N�g���o�^����Ă��܂���";
//                                            }
//                                            else
//                                            {
//                                                    $readBody[$key][$i]['message'] = "�Y������Ј��ʃv���W�F�N�g���o�^����Ă��܂���";
//                                            }
//                                            $errorflg = false;
//                                    }
//                            }
//                    }
//            }
//    }
//
//    //�莞�`�F�b�N
//    $keyarray = array_keys($readBody);
//    foreach($keyarray as $key)
//    {
//            //������
//            $teizicheck = 0;
//            for($i = 0; $i < count($readBody[$key]); $i++)
//            {
//                    $teizicheck += $readBody[$key][$i]['teizi'];
//            }
//            //�莞���Ԃ��K�薢���̓��t
//            if($teizicheck < 7.75)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = 'NG';
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>�K��̒莞���ԂɒB���Ă��܂���B";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = "�K��̒莞���ԂɒB���Ă��܂���B";
//                            }
//                    }
//                    $errorflg = false;
//            }
//            //�莞���Ԃ��K�蒴�߂̓��t
//            if($teizicheck > 7.75)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = 'NG';
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>�K��̒莞���Ԃ��z���Ă��܂��B";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
//                            }
//                            $errorflg = false;
//                    }
//            }
//    }
//
//    //------------------------//
//    //   �\�����X�g�쐬����   //
//    //------------------------//
//    //��Ɠ����Ƀ\�[�g
//    ksort($readBody);
//    if(!empty($errormessage))
//    {
//            $restable .= $errormessage;
//    }
//    if($errorflg)
//    {
//            $_SESSION['fileinsert']['judge'] = true;
//            $restable .= "<table class='list'><tr>"; 
//            for($i = 0 ; $i < count($columns_array) ; $i++)
//            {
//                    $title_name = $form_ini[$columns_array[$i]]['link_num'];
//                    $restable .="<th><a class ='head'>".$title_name."</a></th>";
//            }
//            $restable .= "</tr>";
//            //���t���ƂɃe�[�u���쐬
//            $keyarray = array_keys($readBody);
//            foreach($keyarray as $key)
//            {
//                    //�����i���f�[�^�̌���
//                    $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN syaininfo USING(4CODE) "
//                                    ."WHERE SAGYOUDATE = '".$key."' AND STAFFID = '".$staffnum."';";
//                    $result = $con->query($sql) or ($judge = true);
//                    if($judge)
//                    {
//                            error_log($con->error,0);
//                            $judge = false;
//                    }
//                    $rownums = $result->num_rows;
//                    if($rownums > 0)
//                    {
//                            $code7 = "";
//                            while($result_row = $result->fetch_array(MYSQLI_ASSOC))
//                            {
//                                    //�����i���f�[�^�̍폜
//                                    $code7 .= $result_row['7CODE'].",";
//                            }
//                            $code7 = rtrim($code7,',');
//                            $sql = "DELETE FROM progressinfo WHERE 7CODE IN (".$code7.");";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                                    $judge = false;
//                            }
//                    }
//                    for($i = 0 ; $i < count($readBody[$key]); $i++)
//                    {
//                            $count = 0;
//                            $rownums = 0;
//                            $restable .= "<tr><td class = 'center'>".$readBody[$key][$i]['date']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['pj']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['edaban']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['pjname']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['koutei']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['kouteiname']."</td>";
//                            $restable .= "<td class = 'center'>".number_format($readBody[$key][$i]['teizi'],2,".","")."</td>";
//                            $restable .= "<td class = 'center'>".number_format($readBody[$key][$i]['zangyo'],2,".","")."</td></tr>";
//
//                            //progressinfo�ɓo�^
//                            $sql = "INSERT INTO progressinfo ( 3CODE, 6CODE, SAGYOUDATE, TEIZITIME, ZANGYOUTIME, 7ENDDATE, 7PJSTAT) "
//                                            ."VALUE (".$readBody[$key][$i]['3CODE'].",".$readBody[$key][$i]['6CODE'].",'".$readBody[$key][$i]['date']."',".$readBody[$key][$i]['teizi'].",".$readBody[$key][$i]['zangyo'].",NULL,1);";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                                    $judge = false;
//                            }
//                    }
//            }
//    }
//    else
//    {
//            $_SESSION['fileinsert']['judge'] = false;
//            $restable .= "<table class='list'><tr>"; 
//            for($i = 0 ; $i < count($columns_array) ; $i++)
//            {
//                    $title_name = $form_ini[$columns_array[$i]]['link_num'];
//                    $restable .="<th><a class ='head'>".$title_name."</a></th>";
//            }
//            $restable .="<th><a class ='head'>��荞�݌���</a></th>";
//            $restable .="<th><a class ='head'>���b�Z�[�W</a></th></tr>";
//            $keyarray = array_keys($readBody);
//            foreach($keyarray as $key)
//            {
//                    for($i = 0 ; $i < count($readBody[$key]); $i++)
//                    {
//                            $restable .= "<tr><td class = 'center'>".$readBody[$key][$i]['date']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['pj']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['edaban']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['pjname']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['koutei']."</td>";
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['kouteiname']."</td>";
//                            $restable .= "<td class = 'center'>".number_format($readBody[$key][$i]['teizi'],2,".","")."</td>";
//                            $restable .= "<td class = 'center'>".number_format($readBody[$key][$i]['zangyo'],2,".","")."</td>";
//                            $restable .= "<td class = 'center'>NG</td>";
//                            if(empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] = "���̓��̓f�[�^�ɂăG���[���������Ă��܂��B";
//                            }
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['message']."</td></tr>";
//                    }
//            }
//    }
//    $restable .= "</table>";
//----2018/01/18 ����32 asanoma PJ�i�����捞�Ή� end -----<<

    //------------------------//
    //          �ϐ�          //
    //------------------------//
    $countrow = 0;
    $readBody = array();												//�ǂݍ��ݔz��
    $sql = "";
    $judge = false;														//SQL���f�t���O
    $staffnum = "";														//�Ј��ԍ�
    $date = array();													//���t�z��
    $errorflg = true;													//�G���[�t���O
    $errormessage = "";
    $inputerror = "";													//���̓G���[
    $selecterror = "";													//�o�^�����G���[
    $teizicheck = array();													//�莞�`�F�b�N�p�ϐ�
    $restable = "";
    $code7 = "";

    //------------------------//
    //        �捞����        //
    //------------------------//

    //�捞�f�[�^��ǂݍ���
    $file = fopen($FilePath, "r");
    if($file)
    {
        while ($line = fgets($file)) 
        {
            $strsub = explode(",", $line); //�J���}��؂�̃f�[�^���擾
            $staffnum = mb_convert_encoding( $strsub[0], "SJIS", "SJIS");
            $readBody[$countrow]['staffnum'] = $staffnum;
            $date = mb_convert_encoding($strsub[1], "SJIS", "SJIS");
            $date = explode('/',$date);
            $day = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);
            $readBody[$countrow]['date'] = $day;
            $readBody[$countrow]['pj'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");			//PJ�R�[�h
            $readBody[$countrow]['edaban'] = mb_convert_encoding($strsub[3], "SJIS", "SJIS");		//�}�ԃR�[�h
            $koutei = mb_convert_encoding($strsub[4], "SJIS", "SJIS");
            $readBody[$countrow]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//�H���ԍ�
            $teizi = (float)mb_convert_encoding($strsub[5], "SJIS", "SJIS");
            $readBody[$countrow]['teizi'] = $teizi;													//�莞����
            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[6]);
            $readBody[$countrow]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//�c�Ǝ���
            $readBody[$countrow]['judge'] = "OK";
            $readBody[$countrow]['message'] = "";
            $teizicheck[$staffnum][$day] = "";
            $teizicheck[$staffnum][$day] += $teizi;                                                                            //teizicheck�z��֓��t���Ƃɒ莞���Ԃ����Z
            $countrow++;
        }
    }
    fclose($file);


    //------------------------//
    //       �`�F�b�N����     //
    //------------------------//
    for($i = 0; $i < count($readBody); $i++)
    {
        //�����σ`�F�b�N
        $date = explode('-',$readBody[$i]['date']);
        $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
        $result = $con->query($sql);
        $rows = $result->num_rows;
        if($rows > 0)
        {
            $readBody[$i]['judge'] = "NG";
            $readBody[$i]['message'] = "���������I���ς݊��Ԃ̂��߁A�o�^�ł��܂���B";
            $errorflg = false;
            $errormessage = "<div class = 'center'><a class = 'error'>���Ɍ����������������Ă�����Ԃ̂��߁A�o�^�ł��܂���B</a></div><br>";
        }
        //������
        $selecterror = "";
        $inputerror = "";
        //���͒l�`�F�b�N(�Ј��ԍ�)
        if (preg_match("/^[0-9]+$/", $readBody[$i]['staffnum']))
        {
            //DB�Ƀf�[�^���o�^�ς݂��`�F�b�N
            $sql = "SELECT * FROM syaininfo WHERE STAFFID = '".$readBody[$i]['staffnum']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //���o�^�o�^�G���[
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                $selecterror = "�Ј��ԍ�";
            }
            else
            {
                $result_row = $result->fetch_array(MYSQLI_ASSOC);
                $code4 = $result_row['4CODE'];
            }
        }
        else
        {
            //���̓G���[
            $errorflg = false;
            $readBody[$i]['judge'] = "NG";
            $inputerror = "�Ј��ԍ�";
        }
        //���͒l�`�F�b�N(�v���W�F�N�g�R�[�h)
        if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$i]['pj']))
        {
            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
            $sql = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$readBody[$i]['pj']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //���o�^�o�^�G���[
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "�A�v���W�F�N�g�R�[�h";
                }
                else
                {
                    $selecterror = "�v���W�F�N�g�R�[�h";
                }
            }
            else
            {
                $result_row = $result->fetch_array(MYSQLI_ASSOC);
                $code1 = $result_row['1CODE'];
            }
        }
        else
        {
            $readBody[$i]['judge'] = "NG";
            if(!empty($inputerror))
            {
                $inputerror .= "�A�v���W�F�N�g�R�[�h";
            }
            else
            {
                $inputerror = "�v���W�F�N�g�R�[�h";
            }
            $errorflg = false;		
        }
        //���͒l�`�F�b�N
        if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$i]['edaban']))
        {
            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
            $sql = "SELECT * FROM edabaninfo WHERE EDABAN = '".$readBody[$i]['edaban']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //���o�^�o�^�G���[
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "�A�}��";
                }
                else
                {
                    $selecterror = "�}��";
                }
            }
            else
            {
                $result_row = $result->fetch_array(MYSQLI_ASSOC);
                $code2 = $result_row['2CODE'];
                $readBody[$i]['pjname'] = $result_row['PJNAME'];
            }
        }
        else
        {
            $readBody[$i]['judge'] = "NG";
            if(!empty($inputerror))
            {
                $inputerror .= "�A�}��";
            }
            else
            {
                $inputerror = "�}��";
            }
            $errorflg = false;		
        }
        //���͒l�`�F�b�N(�H���ԍ�)
        if (preg_match("/^[0-9]+$/", $readBody[$i]['koutei']))
        {
            //DB�Ƀf�[�^�����݂��邩�`�F�b�N
            $sql = "SELECT * FROM kouteiinfo WHERE KOUTEIID = '".$readBody[$i]['koutei']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //���o�^�o�^�G���[
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "�A�H���ԍ�";
                }
                else
                {
                    $selecterror = "�H���ԍ�";
                }
            }
            else
            {
                $result_row = $result->fetch_array(MYSQLI_ASSOC);
                $readBody[$i]['3CODE'] = $result_row['3CODE'];
                $readBody[$i]['kouteiname'] = $result_row['KOUTEINAME'];
            }
        }
        else
        {
            $readBody[$key][$i]['judge'] = "NG";
            if(!empty($inputerror))
            {
                $inputerror .= "�A�H���ԍ�";
            }
            else
            {
                $inputerror = "�H���ԍ�";
            }
            $errorflg = false;		
        }
        if(!empty($inputerror))
        {
            if(!empty($readBody[$i]['message']))
            {
                $readBody[$i]['message'] .= "<br>".$inputerror."�����p�p���݂̂œ��͂���Ă��܂���B";
            }
            else
            {
                $readBody[$i]['message'] = $inputerror."�����p�p���݂̂œ��͂���Ă��܂���B";
            }
        }
        if(!empty($selecterror))
        {
            if(!empty($readBody[$i]['message']))
            {
                $readBody[$i]['message'] .= "<br>".$selecterror."���o�^����Ă��܂���B";
            }
            else
            {
                $readBody[$i]['message'] = $selecterror."���o�^����Ă��܂���B";
            }
        }
        if(empty($inputerror) && empty($selecterror))
        {
            //DB�Ƀf�[�^�����݂��邩�`�F�b�N(�v���W�F�N�g:5CODE)
            //$sql = "SELECT * FROM projectinfo WHERE 1CODE = ".$code1." AND 2CODE = ".$code2.";";
            $sql = "SELECT * FROM projectinfo ";
            $sql .= " INNER JOIN projectnuminfo USING ( 1CODE ) ";
            $sql .= " INNER JOIN edabaninfo USING ( 2CODE ) ";
            $sql .= "WHERE PROJECTNUM = '".$readBody[$i]['pj']."' AND EDABAN = '".$readBody[$i]['edaban']."';";
            
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
                $errorflg = false;
            }
            $rownums = $result->num_rows;
            if($rownums == 1)
            {
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $code2 = $result_row['2CODE'];
                    $readBody[$i]['pjname'] = $result_row['PJNAME'];
                    $code5 = $result_row['5CODE'];
                    $endflg = $result_row['5PJSTAT'];
                }
                //DB�Ƀf�[�^�����݂��邩�`�F�b�N(�ʃv���W�F�N�g:6CODE)
                $sql2 = "SELECT * FROM projectditealinfo WHERE 4CODE = ".$code4." AND 5CODE = ".$code5.";";
                $result2 = $con->query($sql2) or ($judge = true);
                if($judge)
                {
                    error_log($con->error,0);
                    $errorflg = false;
                }
                $rownums = $result2->num_rows;
                if($rownums == 1)
                {
                    while($result2_row = $result2->fetch_array(MYSQLI_ASSOC))
                    {
                        $readBody[$i]['6CODE'] = $result2_row['6CODE'];
                    }
                }
                else
                {
                    $readBody[$i]['6CODE'] = "";
                    $readBody[$i]['judge'] = 'NG';
                    if(!empty($readBody[$i]['message']))
                    {
                        $readBody[$i]['message'] .= "<br>�Y������Ј��ʃv���W�F�N�g���o�^����Ă��܂���";
                    }
                    else
                    {
                        $readBody[$i]['message'] = "�Y������Ј��ʃv���W�F�N�g���o�^����Ă��܂���";
                    }
                    $errorflg = false;	
                }
                //----2018/01/23 ����32 asanoma �I���`�F�b�N�ǉ� start ----->>
                if($endflg == 2)
                {
                    $readBody[$i]['judge'] = 'NG';
                    if(!empty($readBody[$i]['message']))
                    {
                        $readBody[$i]['message'] .= "<br>���ɏI�������v���W�F�N�g�̂��ߓo�^�ł��܂���B";
                    }
                    else
                    {
                        $readBody[$i]['message'] = "���ɏI�������v���W�F�N�g�̂��ߓo�^�ł��܂���B";
                    }
                    $errorflg = false;	
                }
                //----2018/01/23 ����32 asanoma �I���`�F�b�N�ǉ� start ----->>
            }
            else
            {
                $readBody[$i]['judge'] = 'NG';
                if(!empty($readBody[$i]['message']))
                {
                    $readBody[$i]['message'] .= "<br>�Y������v���W�F�N�g���o�^����Ă��܂���B";
                }
                else
                {
                    $readBody[$i]['message'] = "�Y������v���W�F�N�g���o�^����Ă��܂���B";
                }
                $errorflg = false;
            }
        }
    }

    //�莞�`�F�b�N
    $keyarray = array_keys($teizicheck);
    foreach($keyarray as $key)
    {
        $keyarray2 = array_keys($teizicheck[$key]);
        foreach($keyarray2 as $key2)
        {
            //�莞���Ԃ��K�薢���̓��t
            if($teizicheck[$key][$key2] < 7.75)
            {
                for($i = 0; $i < count($readBody); $i++)
                {
                    //----2018/01/23 ����32 asanoma ��������C�� start ----->>
                    //if($readBody[$i]['staffnum'] == $key)
                    if(($readBody[$i]['staffnum'] == $key) && ($readBody[$i]['date'] == $key2))
                    //----2018/01/23 ����32 asanoma ��������C�� end -----<<
                    {
                        $readBody[$i]['judge'] = 'NG';
                        if(!empty($readBody[$i]['message']))
                        {
                                $readBody[$i]['message'] .= "<br>�K��̒莞���ԂɒB���Ă��܂���B";
                        }
                        else
                        {
                                $readBody[$i]['message'] = "�K��̒莞���ԂɒB���Ă��܂���B";
                        }
                    }
                }
                $errorflg = false;
            }
            //�莞���Ԃ��K�蒴�߂̓��t
            if($teizicheck[$key][$key2] > 7.75)
            {
                for($i = 0; $i < count($readBody); $i++)
                {
                    //----2018/01/23 ����32 asanoma ��������C�� start ----->>
                    //if($readBody[$i]['staffnum'] == $key)
                    if(($readBody[$i]['staffnum'] == $key) && ($readBody[$i]['date'] == $key2))
                    //----2018/01/23 ����32 asanoma ��������C�� end -----<<
                    {
                        $readBody[$i]['judge'] = 'NG';
                        if(!empty($readBody[$i]['message']))
                        {
                            $readBody[$i]['message'] .= "<br>�K��̒莞���Ԃ��z���Ă��܂��B";
                        }
                        else
                        {
                            $readBody[$i]['message'] = "�K��̒莞���Ԃ��z���Ă��܂��B";
                        }
                    }
                }
                $errorflg = false;
            }
        }
    }

    //------------------------//
    //   �\�����X�g�쐬����   //
    //------------------------//
    if(!empty($errormessage))
    {
            $restable .= $errormessage;
    }
    if($errorflg)
    {
        $_SESSION['fileinsert']['judge'] = true;
        //----2018/01/23 ����32 asanoma ���X�g�\���`���C�� start----->> 
//        $restable .= "<table class='list'><tr>"; 
//        for($i = 0 ; $i < count($columns_array) ; $i++)
//        {
//            $title_name = $form_ini[$columns_array[$i]]['link_num'];
//            $restable .="<th><a class ='head'>".$title_name."</a></th>";
//        }
//        $restable .= "</tr>";
        //�����i���f�[�^�̌���
        $keyarray = array_keys($teizicheck);
        foreach($keyarray as $key)
        {
            $keyarray2 = array_keys($teizicheck[$key]);
            foreach($keyarray2 as $key2)
            {
                $sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN syaininfo USING(4CODE) "
                            ."WHERE SAGYOUDATE = '".$key2."' AND STAFFID = '".$key."';";
                $result = $con->query($sql) or ($judge = true);
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                $rownums = $result->num_rows;
                if($rownums > 0)
                {
                    $code7 = "";
                    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        //�����i���f�[�^�̍폜
                        $code7 .= $result_row['7CODE'].",";
                    }
                    $code7 = rtrim($code7,',');
                    $sql = "DELETE FROM progressinfo WHERE 7CODE IN (".$code7.");";
                    $result = $con->query($sql) or ($judge = true);
                    if($judge)
                    {
                        error_log($con->error,0);
                        $judge = false;
                        //----2018/01/23 ����32 asanoma DB�G���[�Ή��ǉ� start----->>
                        $readBody[$i]['judge'] = "NG";
                        $readBody[$i]['message'] = "DB�폜�����ɂăG���[���������܂����B";
                        $errorflg = false;
                        //----2018/01/23 ����32 asanoma DB�G���[�Ή��ǉ� end-----<<
                    }
                }
            }
        }
        
       
        for($i = 0; $i < count($readBody); $i++)
        {
            //progressinfo�ɓo�^
            $sql = "INSERT INTO progressinfo ( 3CODE, 6CODE, SAGYOUDATE, TEIZITIME, ZANGYOUTIME, 7ENDDATE, 7PJSTAT) "
                            ."VALUE (".$readBody[$i]['3CODE'].",".$readBody[$i]['6CODE'].",'".$readBody[$i]['date']."',".$readBody[$i]['teizi'].",".$readBody[$i]['zangyo'].",NULL,1);";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
                $judge = false;
                //----2018/01/23 ����32 asanoma DB�G���[�Ή��ǉ� start----->>
                $readBody[$i]['judge'] = "NG";
                $readBody[$i]['message'] = "DB�o�^�����ɂăG���[���������܂����B";
                $errorflg = false;
                //----2018/01/23 ����32 asanoma DB�G���[�Ή��ǉ� end-----<<
            }
        }       
        if($errorflg)
        {
            //�e�[�u���쐬
            $restable .= "<table class='list'><tr>"; 
            for($i = 0 ; $i < count($columns_array) ; $i++)
            {
                $title_name = $form_ini[$columns_array[$i]]['link_num'];
                $restable .="<th><a class ='head'>".$title_name."</a></th>";
            }
            $restable .="<th><a class ='head'>��荞�݌���</a></th>";
            $restable .="<th><a class ='head'>���b�Z�[�W</a></th></tr>";
            for($i = 0; $i < count($readBody); $i++)
            {
                $restable .= "<tr><td class = 'center'>".$readBody[$i]['staffnum']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['date']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['pj']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['edaban']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['pjname']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['koutei']."</td>";
                $restable .= "<td class = 'center'>".$readBody[$i]['kouteiname']."</td>";
                $restable .= "<td class = 'center'>".number_format($readBody[$i]['teizi'],2,".","")."</td>";
                $restable .= "<td class = 'center'>".number_format($readBody[$i]['zangyo'],2,".","")."</td>";
                $restable .= "<td class = 'center'>OK</td>";
                $restable .= "<td class = 'center'>�������</td></tr>";
            }

//            //progressinfo�ɓo�^
//            $sql = "INSERT INTO progressinfo ( 3CODE, 6CODE, SAGYOUDATE, TEIZITIME, ZANGYOUTIME, 7ENDDATE, 7PJSTAT) "
//                            ."VALUE (".$readBody[$i]['3CODE'].",".$readBody[$i]['6CODE'].",'".$readBody[$i]['date']."',".$readBody[$i]['teizi'].",".$readBody[$i]['zangyo'].",NULL,1);";
//            $result = $con->query($sql) or ($judge = true);
//            if($judge)
//            {
//                error_log($con->error,0);
//                $judge = false;
//            }
        }
    }
//    else
            //----2018/01/23 ����32 asanoma ���X�g�\���`���C�� end-----<<
    if(!$errorflg)
    {
        $_SESSION['fileinsert']['judge'] = false;
        $restable .= "<table class='list'><tr>"; 
        for($i = 0 ; $i < count($columns_array) ; $i++)
        {
            $title_name = $form_ini[$columns_array[$i]]['link_num'];
            $restable .="<th><a class ='head'>".$title_name."</a></th>";
        }
        $restable .="<th><a class ='head'>��荞�݌���</a></th>";
        $restable .="<th><a class ='head'>���b�Z�[�W</a></th></tr>";
        //�e�[�u���쐬
        for($i = 0; $i < count($readBody); $i++)
        {
            $restable .= "<tr><td class = 'center'>".$readBody[$i]['staffnum']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['date']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['pj']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['edaban']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['pjname']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['koutei']."</td>";
            $restable .= "<td class = 'center'>".$readBody[$i]['kouteiname']."</td>";
            $restable .= "<td class = 'center'>".number_format($readBody[$i]['teizi'],2,".","")."</td>";
            $restable .= "<td class = 'center'>".number_format($readBody[$i]['zangyo'],2,".","")."</td>";
            $restable .= "<td class = 'center'>NG</td>";
            if(empty($readBody[$i]['message']))
            {
                $readBody[$i]['message'] = "���̓��̓f�[�^�ɂăG���[���������Ă��܂��B";
            }
            $restable .= "<td class = 'center'>".$readBody[$i]['message']."</td></tr>";
        }
    }
    //----2018/01/22 ����32 asanoma �\�[�g�폜�Ή� end -----<<
    $restable .= "</table>";

    return $restable;
    //return $tablenum ."�@�@�@�@�@".$filename;
}
?>