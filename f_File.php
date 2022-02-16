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


引数1	$CSV				CSV
引数2	$csv_path			CSVファイルパス

戻り値	なし
****************************************************************************************/
function csv_write($CSV) {
	
    //------------------------//
    //          定数          //
    //------------------------//
    $csv_path = "./List/List_".session_id().".csv";



    //--------------------------//
    //  CSVファイルの追記処理  //
    //--------------------------//

//	$CSV = mb_convert_encoding($CSV,'sjis-win','utf-8');																		// 取得string文字コード変換

    $fp = fopen($csv_path, 'ab');																								// CSVファイルを追記書き込みで開く
    // ファイルが開けたか //
    if ($fp)
    {
            // ファイルのロックができたか //
            if (flock($fp, LOCK_EX))																								// ロック
            {
                    // ログの書き込みを失敗したか //
                    if (fwrite($fp , $CSV."\r\n") === FALSE)																			// CSV追記書き込み
                    {
                            // 書き込み失敗時の処理
                    }

                    flock($fp, LOCK_UN);																								// ロックの解除
            }
            else
            {
                    // ロック失敗時の処理
            }
    }
    fclose($fp);																												// ファイルを閉じる
    return($csv_path);
}	

/****************************************************************************************
function check_mail()


引数	なし

戻り値	なし
****************************************************************************************/
function check_mail(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$mial_ini = parse_ini_file('./ini/mail.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$check_path = $mial_ini['syaken']['file_path'];																				// 送信確認ファイル
	$year = date_create('NOW');
	$year = date_format($year, "Y");
	$month = date_create('NOW');
	$month = date_format($month, "m");
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			$buffer = fgets($fp);
			if($buffer != $year.$month)
			{
				ftruncate( $fp,0);
				// ログの書き込みを失敗したか //
				if (fwrite($fp ,$year.$month) === FALSE)																		// check_mail追記書き込み
				{
					// 書き込み失敗時の処理
				}
				syaken_mail_select();
			}
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
}	

/****************************************************************************************
function limit_mail($message)


引数	なし

戻り値	なし
****************************************************************************************/
function limit_mail($message){


    //------------------------//
    //        初期設定        //
    //------------------------//
    $mial_ini = parse_ini_file('./ini/mail.ini', true);
    require_once("f_Form.php");																									// Form関数呼び出し準備

    //------------------------//
    //          定数          //
    //------------------------//
    $check_path = $mial_ini['limit']['file_path'];																				// 送信確認ファイル
    $date = date_create("NOW");
    $date = date_format($date, "Y-m-d");


    //------------------------//
    //          変数          //
    //------------------------//
    $buffer = "";

    //--------------------------//
    //  CSVファイルの追記処理  //
    //--------------------------//

    if(!file_exists($check_path))
    {
            $fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
            fclose($fp);				
    }

    $fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
    // ファイルが開けたか //
    if ($fp)
    {
            // ファイルのロックができたか //
            if (flock($fp, LOCK_EX))																								// ロック
            {
                    $buffer = fgets($fp);
                    if($buffer == "")
                    {
                            ftruncate( $fp,0);
                            // ログの書き込みを失敗したか //
                            if (fwrite($fp ,$date) === FALSE)																		// check_mail追記書き込み
                            {
                                    // 書き込み失敗時の処理
                            }
                            else
                            {
                                    make_limit_mail($message);
                            }
                    }
                    flock($fp, LOCK_UN);																								// ロックの解除
            }
            else
            {
                    // ロック失敗時の処理
            }
    }
    fclose($fp);																												// ファイルを閉じる
}
/****************************************************************************************
function getuzi_rireki()


引数	なし

戻り値	なし
****************************************************************************************/

function getuzi_rireki(){


    //------------------------//
    //        初期設定        //
    //------------------------//
    $file_ini = parse_ini_file('./ini/file.ini', true);

    //------------------------//
    //          定数          //
    //------------------------//
    $filename = $_SESSION['filename'];
    $check_path = $file_ini[$filename]['file_path'];
    $date = date_create('NOW');
    $date = date_format($date, "Y-m-d");


    //------------------------//
    //          変数          //
    //------------------------//
    $buffer = "";

    //--------------------------//
    //  CSVファイルの追記処理  //
    //--------------------------//

    if(!file_exists($check_path))
    {
            $fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
            fclose($fp);				
    }

    $fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
    // ファイルが開けたか //
    if ($fp)
    {
            // ファイルのロックができたか //
            if (flock($fp, LOCK_EX))																								// ロック
            {
                    $buffer = fgets($fp);
                    flock($fp, LOCK_UN);																								// ロックの解除
            }
            else
            {
                    // ロック失敗時の処理
            }
    }
    fclose($fp);																												// ファイルを閉じる
    return($buffer);
}

/****************************************************************************************
function nenzi_rireki()


引数	なし

戻り値	なし
****************************************************************************************/

function nenzi_rireki(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			$buffer = fgets($fp);
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
	return($buffer);
}
/****************************************************************************************
function deletedate_change()


引数	なし

戻り値	なし
****************************************************************************************/
function deletedate_change(){
	
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// 送信確認ファイル
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	if($filename == 'getuzi_5')
	{
		$period = $_SESSION['getuji']['period'];
		$month = $_SESSION['getuji']['month'];
		$date = $period."期 ".$month."月 ( 実行日： ".$date." )";
	}
	if($filename == 'nenzi_5')
	{
		$period = $_SESSION['nenzi']['period'];
		$date = $period."期 ( 実行日 ：".$date." )";
	}

	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			ftruncate( $fp,0);
			// ログの書き込みを失敗したか //
			if (fwrite($fp ,$date) === FALSE)																		// check_mail追記書き込み
			{
				// 書き込み失敗時の処理
			}
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
}	

/****************************************************************************************
function Delete_rireki()


引数	なし

戻り値	なし
****************************************************************************************/

function Delete_rireki(){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$file_ini = parse_ini_file('./ini/file.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$check_path = $file_ini[$filename]['file_path'];																				// 送信確認ファイル
	$date = date_create('NOW');
	$date = date_format($date, "Y-m-d");
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$buffer = "";
	
	//--------------------------//
	//  CSVファイルの追記処理  //
	//--------------------------//
	
	if(!file_exists($check_path))
	{
		$fp = fopen($check_path, 'ab');																							// 送信確認ファイルを追記書き込みで開く
		fclose($fp);				
	}
	
	$fp = fopen($check_path, 'a+b');																							// 送信確認ファイルを追記書き込みで開く
	// ファイルが開けたか //
	if ($fp)
	{
		// ファイルのロックができたか //
		if (flock($fp, LOCK_EX))																								// ロック
		{
			$buffer = fgets($fp);
			flock($fp, LOCK_UN);																								// ロックの解除
		}
		else
		{
			// ロック失敗時の処理
		}
	}
	fclose($fp);																												// ファイルを閉じる
	return($buffer);
}
/***************************************************************************
function FileReadInsert()


引数			対象ファイルパス

戻り値			なし
***************************************************************************/
function FileReadInsert(){
//----2018/01/22 項番32 asanoma ソート削除対応 start ----->>
//    //----2018/01/18 項番32 asanoma PJ進捗情報取込対応 start ----->>
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
//    //    $errorflg = true;													//エラーフラグ
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
//    //                    $isAlrady = true;											//登録済みフラグ
//    //                    $checkflg = true;											//入力チェックフラグ
//    //                    $pjudge = true;												//PJコード登録済みフラグ
//    //                    $ejudge = true;												//枝番登録済みフラグ
//    //                    $kjudge = true;												//工程番号登録済みフラグ
//    //                    $pcheck = true;												//PJコード入力チェックフラグ
//    //                    $echeck = true;												//枝番入力チェックフラグ
//    //                    $kcheck = true;												//工程番号入力チェックフラグ
//    //                    $code1 = "";
//    //                    $code2 = "";
//    //                    $code3 = "";
//    //                    $strsub = explode(",", $line); //カンマ区切りのデータを取得
//    //                    if($countlow == 0)
//    //                    {
//    //                            $date =  mb_convert_encoding($strsub[0], "SJIS", "SJIS");
//    //                            $date = explode('/',$date);
//    //                            $readHeader[0] = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);			//日付
//    //                            $readHeader[1] =  mb_convert_encoding(str_replace(array("\r\n", "\r", "\n"),'', $strsub[1]), "SJIS", "SJIS");				//ユーザー番号
//    //                            //月次済チェック
//    //                            $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
//    //                            $result = $con->query($sql);
//    //                            $rows = $result->num_rows;
//    //                            if($rows > 0)
//    //                            {
//    //                                    $getujiflg = false;
//    //                                    $errorflg = false;
//    //                                    $errormessage = "<div class = 'center'><a class = 'error'>既に月次処理が完了している期間のため、登録できません。</a></div><br>";
//    //                            }
//    //                            else
//    //                            {
//    //                                    //4CODE取得
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
//    //                            $pj = mb_convert_encoding($strsub[0], "SJIS", "SJIS");			//PJコード
//    //                            $edaban = mb_convert_encoding($strsub[1], "SJIS", "SJIS");		//枝番コード
//    //                            $koutei = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//工程
//    //                            $teizi = (float)mb_convert_encoding($strsub[3], "SJIS", "SJIS");		//定時時間
//    //                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[4]);
//    //                            $zangyo = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//残業時間
//    //                            $teizicheck += $teizi;
//    //                            //入力値が半角英数かチェック
//    //                            if (preg_match("/^[a-zA-Z0-9]+$/", $pj))
//    //                            {
//    //                                    //DBにデータが存在するかチェック
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
//    //                            //入力値が半角英数かチェック
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
//    //                            //入力値が半角英数かチェック
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
//    //                            //取り込み結果用配列に入力
//    //                            if($getujiflg)
//    //                            {
//    //                                    //正常
//    //                                    if($isAlrady && $checkflg)
//    //                                    {
//    //                                            $readBody[($countlow-1)]['pj'] = $pj;
//    //                                            $readBody[($countlow-1)]['edaban'] = $edaban;
//    //                                            $readBody[($countlow-1)]['koutei'] = $koutei;
//    //                                            $readBody[($countlow-1)]['teizi'] = $teizi;
//    //                                            $readBody[($countlow-1)]['zangyo'] = $zangyo;
//    //                                            $readBody[($countlow-1)]['judge'] = 'OK';
//    //                                            $readBody[($countlow-1)]['message'] = '正常入力';
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
//    //                                                    $readBody[($countlow-1)]['message'] = "該当するプロジェクトが登録されていません。";
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
//    //                                                            $readBody[($countlow-1)]['message'] = "該当する社員別プロジェクトが登録されていません。";
//    //                                                            $errorflg = false;
//    //                                                    }
//    //                                            }
//    //                                    }
//    //                                    //登録済み且つ入力エラー
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
//    //                                                    $readBody[($countlow-1)]['message'] .= 'プロジェクトコード';
//    //                                            }
//    //                                            if(!$echeck)
//    //                                            {
//    //                                                    if(!empty($readBody[($countlow-1)]['message']))
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',枝番';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '枝番';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kcheck)
//    //                                            {
//    //                                                    if(!empty($readBody[($countlow-1)]['message']))
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',工程番号';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= '工程番号';
//    //                                                    }
//    //                                            }
//    //                                            $readBody[($countlow-1)]['message'] .= 'が半角英数のみで入力されていません。';
//    //                                    }
//    //                                    //未登録
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
//    //                                                    $readBody[($countlow-1)]['message'] = 'プロジェクトコード';
//    //                                            }
//    //                                            if(!$ejudge)
//    //                                            {
//    //                                                    if($readBody[($countlow-1)]['message'] == '')
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] = '枝番';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',枝番';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kjudge)
//    //                                            {
//    //                                                    if($readBody[($countlow-1)]['message'] == '')
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] = '工程番号';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= ',工程番号';
//    //                                                    }
//    //                                            }
//    //                                            $readBody[($countlow-1)]['message'] .= 'が存在しません。';
//    //                                            //入力エラー
//    //                                            if(!$checkflg)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] .= '<br>';
//    //                                                    if(!$pcheck)
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= 'プロジェクトコード';
//    //                                                    }
//    //                                                    if(!$echeck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',枝番';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '枝番';
//    //                                                            }
//    //                                                    }
//    //                                                    if(!$kcheck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',工程番号';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '工程番号';
//    //                                                            }
//    //                                                    }
//    //                                                    $readBody[($countlow-1)]['message'] .= 'が半角英数のみで入力されていません。';
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
//    //                                            $readBody[($countlow-1)]['message'] = '月次済のため登録不可です。';
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
//    //                                            $readBody[($countlow-1)]['message'] = '月次済のため登録不可です。<br>';
//    //                                            if(!$pjudge)
//    //                                            {
//    //                                                    $message = 'プロジェクトコード';
//    //                                            }
//    //                                            if(!$ejudge)
//    //                                            {
//    //                                                    if($message == '')
//    //                                                    {
//    //                                                            $message = '枝番';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $message .= ',枝番';
//    //                                                    }
//    //                                            }
//    //                                            if(!$kjudge)
//    //                                            {
//    //                                                    if($message == '')
//    //                                                    {
//    //                                                            $message = '工程番号';
//    //                                                    }
//    //                                                    else
//    //                                                    {
//    //                                                            $message .= ',工程番号';
//    //                                                    }
//    //                                            }
//    //                                            $message.= 'が存在しません。';
//    //                                            $readBody[($countlow-1)]['message'] .= $message;
//    //                                            if(!$checkflg)
//    //                                            {
//    //                                                    $readBody[($countlow-1)]['message'] .= '<br>';
//    //                                                    if(!$pcheck)
//    //                                                    {
//    //                                                            $readBody[($countlow-1)]['message'] .= 'プロジェクトコード';
//    //                                                    }
//    //                                                    if(!$echeck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',枝番';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '枝番';
//    //                                                            }
//    //                                                    }
//    //                                                    if(!$kcheck)
//    //                                                    {
//    //                                                            if(!empty($readBody[($countlow-1)]['message']))
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= ',工程番号';
//    //                                                            }
//    //                                                            else
//    //                                                            {
//    //                                                                    $readBody[($countlow-1)]['message'] .= '工程番号';
//    //                                                            }
//    //                                                    }
//    //                                                    $readBody[($countlow-1)]['message'] .= 'が半角英数のみで入力されていません。';
//    //                                            }
//    //                                    }
//    //                            }
//    //                    }
//    //                    $countlow = $countlow  + 1;
//    //            }
//    //            if(!$checkflg)
//    //            {
//    //                    $errormessage .= "<div class = 'center'><a class = 'error'>入力値に誤りがあるため、登録できません。</a></div><br>";
//    //            }
//    //            //定時チェック
//    //            if($teizicheck < 7.75)
//    //            {
//    //                    $errorflg = false;
//    //                    $error1 = true;
//    //                    $errormessage .= "<a class = 'error'>規定の定時時間に達していません。</a><br><br>";
//    //            }
//    //            else if($teizicheck > 7.75)
//    //            {
//    //                    $errorflg = false;
//    //                    $error2 = true;
//    //                    $errormessage .= "<a class = 'error'>規定の定時時間を越えています。</a><br><br>";
//    //            }
//    //    }
//    //    fclose($file);
//    //
//    //    //取り込み結果テーブル作成
//    //    $restable = "<div><center>日付：".$readHeader[0]."　ユーザー番号：".$readHeader[1]."<br><br>"; 
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
//    //                    //既存進捗データの検索
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
//    //                                    //既存進捗データの削除
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
//    //                    //progressinfoに登録
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
//    //            $restable .="<th><a class ='head'>取り込み結果</a></th>";
//    //            $restable .="<th><a class ='head'>メッセージ</a></th></tr>";
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
//    //                            $readBody[$i]['message'] = "定時時間不足";
//    //                    }
//    //                    if($error2)
//    //                    {
//    //                            $readBody[$i]['judge'] = "NG";
//    //                            $readBody[$i]['message'] = "定時超過";
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
    //        初期設定        //
    //------------------------//
    require_once("f_DB.php");
    $form_ini = parse_ini_file('./ini/form.ini', true);
    $con = dbconect();													//DB接続

    //------------------------//
    //          定数          //
    //------------------------//
    $filename = $_SESSION['filename'];									//ファイル名
    $tablenum = $form_ini[$filename]['use_maintable_num'];				//テーブル番号
    //----2018/01/22 項番32 asanoma ソート削除対応 start ----->>
    //$columns = "704,102,202,203,302,303,705,706";							//登録カラム
    $columns = "402,704,102,202,203,302,303,705,706";							//登録カラム
    //----2018/01/22 項番32 asanoma ソート削除対応 end -----<<
    $columns_array = explode(',',$columns);
    $FilePath = "temp/tempfileinsert.txt";								//ファイルパス
	
//
//    //------------------------//
//    //          変数          //
//    //------------------------//
//    $cnt = 0;
//    $countrow = 0;
//    $readBody = array();												//読み込み配列
//    $sql = "";
//    $judge = false;														//SQL判断フラグ
//    $staffnum = "";														//社員番号
//    $staffname = "";													//社員名
//    $pjname = "";														//製番・案件名
//    $kouteiname = "";													//工程
//    $date = array();													//日付配列
//    $getujiflg = true;													//月次エラーフラグ
//    $errorflg = true;													//エラーフラグ
//    $errormessage = "";
//    $inputerror = "";													//入力エラー
//    $selecterror = "";													//登録検索エラー
//    $teizicheck = 0;													//定時チェック用変数
//    $restable = "";
//    $code7 = "";
//    //------------------------//
//    //        取込処理        //
//    //------------------------//
//
//    //取込データを読み込み
//    $file = fopen($FilePath, "r");
//    if($file){
//            while ($line = fgets($file)) 
//            {
//                    $strsub = explode(",", $line); //カンマ区切りのデータを取得
//                    if($countrow == 0)
//                    {
//                            $staffnum =  mb_convert_encoding(str_replace(array("\r\n", "\r", "\n"),'', $strsub[0]), "SJIS", "SJIS");				//ユーザー番号
//                            //4CODE取得
//                            $sql = "SELECT * FROM syaininfo WHERE STAFFID = '".$staffnum."';";
//                            $result = $con->query($sql) or ($judge = true);
//                            if($judge)
//                            {
//                                    error_log($con->error,0);
//                            }
//                            $result_row = $result->fetch_array(MYSQLI_ASSOC);
//                            $code4 = $result_row['4CODE'];
//                            $staffname = $result_row['STAFFNAME'];
//                            $restable = "社員番号：".$staffnum."　社員名：".$staffname."<br><br>"; 
//                    }
//                    else
//                    {
//                            $date = mb_convert_encoding($strsub[0], "SJIS", "SJIS");
//                            $date = explode('/',$date);
//                            $day = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);			//日付
//                            if($date[0] != "")
//                            {
//                                    //作業日ごとに多次元配列に格納
//                                    if(!empty($readBody[$day]))
//                                    {
//                                            $cnt = count($readBody[$day]);
//                                            $readBody[$day][$cnt]['date'] = $day;
//                                            $readBody[$day][$cnt]['pj'] = mb_convert_encoding($strsub[1], "SJIS", "SJIS");			//PJコード
//                                            $readBody[$day][$cnt]['edaban'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//枝番コード
//                                            $koutei = mb_convert_encoding($strsub[3], "SJIS", "SJIS");
//                                            $readBody[$day][$cnt]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//工程番号
//                                            $teizi = (float)mb_convert_encoding($strsub[4], "SJIS", "SJIS");
//                                            $readBody[$day][$cnt]['teizi'] = $teizi;												//定時時間
//                                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[5]);
//                                            $readBody[$day][$cnt]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");	//残業時間
//                                            $readBody[$day][$cnt]['judge'] = "OK";
//                                            $readBody[$day][$cnt]['message'] = "";
//                                    }
//                                    else
//                                    {
//                                            $readBody[$day][0]['date'] = $day;
//                                            $readBody[$day][0]['pj'] = mb_convert_encoding($strsub[1], "SJIS", "SJIS");			//PJコード
//                                            $readBody[$day][0]['edaban'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");		//枝番コード
//                                            $koutei = mb_convert_encoding($strsub[3], "SJIS", "SJIS");
//                                            $readBody[$day][0]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//工程番号
//                                            $teizi = (float)mb_convert_encoding($strsub[4], "SJIS", "SJIS");
//                                            $readBody[$day][0]['teizi'] = $teizi;													//定時時間
//                                            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[5]);
//                                            $readBody[$day][0]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//残業時間
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
//    //       チェック処理     //
//    //------------------------//
//    $keyarray = array_keys($readBody);
//    foreach($keyarray as $key)
//    {
//            //月次済チェック
//            $date = explode('-',$key);
//            $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
//            $result = $con->query($sql);
//            $rows = $result->num_rows;
//            if($rows > 0)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = "NG";
//                            $readBody[$key][$i]['message'] = "月次処理終了済み期間のため、登録できません。";
//                    }
//                    $errorflg = false;
//                    $errormessage = "<div class = 'center'><a class = 'error'>既に月次処理が完了している期間のため、登録できません。</a></div><br>";
//            }
//            for($i = 0; $i < count($readBody[$key]); $i++)
//            {
//                    //初期化
//                    $selecterror = "";
//                    $inputerror = "";
//
//                    //入力値チェック
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['pj']))
//                    {
//                            //DBにデータが存在するかチェック
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
//                                    $selecterror = "プロジェクトコード";
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
//                            $inputerror = "プロジェクトコード";
//                            $errorflg = false;		
//                    }
//                    //入力値チェック
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['edaban']))
//                    {
//                            //DBにデータが存在するかチェック
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
//                                            $selecterror .= "、枝番";
//                                    }
//                                    else
//                                    {
//                                            $selecterror = "枝番";
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
//                                    $inputerror .= "、枝番";
//                            }
//                            else
//                            {
//                                    $inputerror = "枝番";
//                            }
//                            $errorflg = false;		
//                    }
//                    //入力値チェック
//                    if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$key][$i]['koutei']))
//                    {
//                            //DBにデータが存在するかチェック
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
//                                            $selecterror .= "、工程番号";
//                                    }
//                                    else
//                                    {
//                                            $selecterror = "工程番号";
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
//                                    $inputerror .= "、工程番号";
//                            }
//                            else
//                            {
//                                    $inputerror = "工程番号";
//                            }
//                            $errorflg = false;		
//                    }
//                    if(!empty($inputerror))
//                    {
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>".$inputerror."が半角英数のみで入力されていません。。";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = $inputerror."が半角英数のみで入力されていません。";
//                            }
//                    }
//                    if(!empty($selecterror))
//                    {
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>".$selecterror."が登録されていません。";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = $selecterror."が登録されていません。";
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
//                                            $readBody[$key][$i]['message'] .= "<br>該当するプロジェクトが登録されていません。";
//                                    }
//                                    else
//                                    {
//                                            $readBody[$key][$i]['message'] = "該当するプロジェクトが登録されていません。";
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
//                                                    $readBody[$key][$i]['message'] .= "<br>該当する社員別プロジェクトが登録されていません";
//                                            }
//                                            else
//                                            {
//                                                    $readBody[$key][$i]['message'] = "該当する社員別プロジェクトが登録されていません";
//                                            }
//                                            $errorflg = false;
//                                    }
//                            }
//                    }
//            }
//    }
//
//    //定時チェック
//    $keyarray = array_keys($readBody);
//    foreach($keyarray as $key)
//    {
//            //初期化
//            $teizicheck = 0;
//            for($i = 0; $i < count($readBody[$key]); $i++)
//            {
//                    $teizicheck += $readBody[$key][$i]['teizi'];
//            }
//            //定時時間が規定未満の日付
//            if($teizicheck < 7.75)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = 'NG';
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>規定の定時時間に達していません。";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = "規定の定時時間に達していません。";
//                            }
//                    }
//                    $errorflg = false;
//            }
//            //定時時間が規定超過の日付
//            if($teizicheck > 7.75)
//            {
//                    for($i = 0; $i < count($readBody[$key]); $i++)
//                    {
//                            $readBody[$key][$i]['judge'] = 'NG';
//                            if(!empty($readBody[$key][$i]['message']))
//                            {
//                                    $readBody[$key][$i]['message'] .= "<br>規定の定時時間を越えています。";
//                            }
//                            else
//                            {
//                                    $readBody[$key][$i]['message'] = "規定の定時時間を越えています。";
//                            }
//                            $errorflg = false;
//                    }
//            }
//    }
//
//    //------------------------//
//    //   表示リスト作成処理   //
//    //------------------------//
//    //作業日順にソート
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
//            //日付ごとにテーブル作成
//            $keyarray = array_keys($readBody);
//            foreach($keyarray as $key)
//            {
//                    //既存進捗データの検索
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
//                                    //既存進捗データの削除
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
//                            //progressinfoに登録
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
//            $restable .="<th><a class ='head'>取り込み結果</a></th>";
//            $restable .="<th><a class ='head'>メッセージ</a></th></tr>";
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
//                                    $readBody[$key][$i]['message'] = "他の入力データにてエラーが発生しています。";
//                            }
//                            $restable .= "<td class = 'center'>".$readBody[$key][$i]['message']."</td></tr>";
//                    }
//            }
//    }
//    $restable .= "</table>";
//----2018/01/18 項番32 asanoma PJ進捗情報取込対応 end -----<<

    //------------------------//
    //          変数          //
    //------------------------//
    $countrow = 0;
    $readBody = array();												//読み込み配列
    $sql = "";
    $judge = false;														//SQL判断フラグ
    $staffnum = "";														//社員番号
    $date = array();													//日付配列
    $errorflg = true;													//エラーフラグ
    $errormessage = "";
    $inputerror = "";													//入力エラー
    $selecterror = "";													//登録検索エラー
    $teizicheck = array();													//定時チェック用変数
    $restable = "";
    $code7 = "";

    //------------------------//
    //        取込処理        //
    //------------------------//

    //取込データを読み込み
    $file = fopen($FilePath, "r");
    if($file)
    {
        while ($line = fgets($file)) 
        {
            $strsub = explode(",", $line); //カンマ区切りのデータを取得
            $staffnum = mb_convert_encoding( $strsub[0], "SJIS", "SJIS");
            $readBody[$countrow]['staffnum'] = $staffnum;
            $date = mb_convert_encoding($strsub[1], "SJIS", "SJIS");
            $date = explode('/',$date);
            $day = $date[0]."-".str_pad($date[1], 2, "0", STR_PAD_LEFT)."-".str_pad($date[2], 2, "0", STR_PAD_LEFT);
            $readBody[$countrow]['date'] = $day;
            $readBody[$countrow]['pj'] = mb_convert_encoding($strsub[2], "SJIS", "SJIS");			//PJコード
            $readBody[$countrow]['edaban'] = mb_convert_encoding($strsub[3], "SJIS", "SJIS");		//枝番コード
            $koutei = mb_convert_encoding($strsub[4], "SJIS", "SJIS");
            $readBody[$countrow]['koutei'] = str_pad($koutei, 3, "0", STR_PAD_LEFT);				//工程番号
            $teizi = (float)mb_convert_encoding($strsub[5], "SJIS", "SJIS");
            $readBody[$countrow]['teizi'] = $teizi;													//定時時間
            $text = str_replace(array("\r\n", "\r", "\n"), '', $strsub[6]);
            $readBody[$countrow]['zangyo'] = (float)mb_convert_encoding($text, "SJIS", "SJIS");			//残業時間
            $readBody[$countrow]['judge'] = "OK";
            $readBody[$countrow]['message'] = "";
            $teizicheck[$staffnum][$day] = "";
            $teizicheck[$staffnum][$day] += $teizi;                                                                            //teizicheck配列へ日付ごとに定時時間を加算
            $countrow++;
        }
    }
    fclose($file);


    //------------------------//
    //       チェック処理     //
    //------------------------//
    for($i = 0; $i < count($readBody); $i++)
    {
        //月次済チェック
        $date = explode('-',$readBody[$i]['date']);
        $sql = "SELECT * FROM endmonthinfo WHERE YEAR = '".$date[0]."' AND MONTH = '".$date[1]."';";
        $result = $con->query($sql);
        $rows = $result->num_rows;
        if($rows > 0)
        {
            $readBody[$i]['judge'] = "NG";
            $readBody[$i]['message'] = "月次処理終了済み期間のため、登録できません。";
            $errorflg = false;
            $errormessage = "<div class = 'center'><a class = 'error'>既に月次処理が完了している期間のため、登録できません。</a></div><br>";
        }
        //初期化
        $selecterror = "";
        $inputerror = "";
        //入力値チェック(社員番号)
        if (preg_match("/^[0-9]+$/", $readBody[$i]['staffnum']))
        {
            //DBにデータが登録済みかチェック
            $sql = "SELECT * FROM syaininfo WHERE STAFFID = '".$readBody[$i]['staffnum']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //未登録登録エラー
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                $selecterror = "社員番号";
            }
            else
            {
                $result_row = $result->fetch_array(MYSQLI_ASSOC);
                $code4 = $result_row['4CODE'];
            }
        }
        else
        {
            //入力エラー
            $errorflg = false;
            $readBody[$i]['judge'] = "NG";
            $inputerror = "社員番号";
        }
        //入力値チェック(プロジェクトコード)
        if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$i]['pj']))
        {
            //DBにデータが存在するかチェック
            $sql = "SELECT * FROM projectnuminfo WHERE PROJECTNUM = '".$readBody[$i]['pj']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //未登録登録エラー
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "、プロジェクトコード";
                }
                else
                {
                    $selecterror = "プロジェクトコード";
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
                $inputerror .= "、プロジェクトコード";
            }
            else
            {
                $inputerror = "プロジェクトコード";
            }
            $errorflg = false;		
        }
        //入力値チェック
        if (preg_match("/^[a-zA-Z0-9]+$/", $readBody[$i]['edaban']))
        {
            //DBにデータが存在するかチェック
            $sql = "SELECT * FROM edabaninfo WHERE EDABAN = '".$readBody[$i]['edaban']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //未登録登録エラー
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "、枝番";
                }
                else
                {
                    $selecterror = "枝番";
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
                $inputerror .= "、枝番";
            }
            else
            {
                $inputerror = "枝番";
            }
            $errorflg = false;		
        }
        //入力値チェック(工程番号)
        if (preg_match("/^[0-9]+$/", $readBody[$i]['koutei']))
        {
            //DBにデータが存在するかチェック
            $sql = "SELECT * FROM kouteiinfo WHERE KOUTEIID = '".$readBody[$i]['koutei']."';";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
            }
            $rows = $result->num_rows;
            if($rows < 1)
            {
                //未登録登録エラー
                $errorflg = false;
                $readBody[$i]['judge'] = "NG";
                if(!empty($selecterror))
                {
                    $selecterror .= "、工程番号";
                }
                else
                {
                    $selecterror = "工程番号";
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
                $inputerror .= "、工程番号";
            }
            else
            {
                $inputerror = "工程番号";
            }
            $errorflg = false;		
        }
        if(!empty($inputerror))
        {
            if(!empty($readBody[$i]['message']))
            {
                $readBody[$i]['message'] .= "<br>".$inputerror."が半角英数のみで入力されていません。";
            }
            else
            {
                $readBody[$i]['message'] = $inputerror."が半角英数のみで入力されていません。";
            }
        }
        if(!empty($selecterror))
        {
            if(!empty($readBody[$i]['message']))
            {
                $readBody[$i]['message'] .= "<br>".$selecterror."が登録されていません。";
            }
            else
            {
                $readBody[$i]['message'] = $selecterror."が登録されていません。";
            }
        }
        if(empty($inputerror) && empty($selecterror))
        {
            //DBにデータが存在するかチェック(プロジェクト:5CODE)
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
                //DBにデータが存在するかチェック(個別プロジェクト:6CODE)
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
                        $readBody[$i]['message'] .= "<br>該当する社員別プロジェクトが登録されていません";
                    }
                    else
                    {
                        $readBody[$i]['message'] = "該当する社員別プロジェクトが登録されていません";
                    }
                    $errorflg = false;	
                }
                //----2018/01/23 項番32 asanoma 終了チェック追加 start ----->>
                if($endflg == 2)
                {
                    $readBody[$i]['judge'] = 'NG';
                    if(!empty($readBody[$i]['message']))
                    {
                        $readBody[$i]['message'] .= "<br>既に終了したプロジェクトのため登録できません。";
                    }
                    else
                    {
                        $readBody[$i]['message'] = "既に終了したプロジェクトのため登録できません。";
                    }
                    $errorflg = false;	
                }
                //----2018/01/23 項番32 asanoma 終了チェック追加 start ----->>
            }
            else
            {
                $readBody[$i]['judge'] = 'NG';
                if(!empty($readBody[$i]['message']))
                {
                    $readBody[$i]['message'] .= "<br>該当するプロジェクトが登録されていません。";
                }
                else
                {
                    $readBody[$i]['message'] = "該当するプロジェクトが登録されていません。";
                }
                $errorflg = false;
            }
        }
    }

    //定時チェック
    $keyarray = array_keys($teizicheck);
    foreach($keyarray as $key)
    {
        $keyarray2 = array_keys($teizicheck[$key]);
        foreach($keyarray2 as $key2)
        {
            //定時時間が規定未満の日付
            if($teizicheck[$key][$key2] < 7.75)
            {
                for($i = 0; $i < count($readBody); $i++)
                {
                    //----2018/01/23 項番32 asanoma 分岐条件修正 start ----->>
                    //if($readBody[$i]['staffnum'] == $key)
                    if(($readBody[$i]['staffnum'] == $key) && ($readBody[$i]['date'] == $key2))
                    //----2018/01/23 項番32 asanoma 分岐条件修正 end -----<<
                    {
                        $readBody[$i]['judge'] = 'NG';
                        if(!empty($readBody[$i]['message']))
                        {
                                $readBody[$i]['message'] .= "<br>規定の定時時間に達していません。";
                        }
                        else
                        {
                                $readBody[$i]['message'] = "規定の定時時間に達していません。";
                        }
                    }
                }
                $errorflg = false;
            }
            //定時時間が規定超過の日付
            if($teizicheck[$key][$key2] > 7.75)
            {
                for($i = 0; $i < count($readBody); $i++)
                {
                    //----2018/01/23 項番32 asanoma 分岐条件修正 start ----->>
                    //if($readBody[$i]['staffnum'] == $key)
                    if(($readBody[$i]['staffnum'] == $key) && ($readBody[$i]['date'] == $key2))
                    //----2018/01/23 項番32 asanoma 分岐条件修正 end -----<<
                    {
                        $readBody[$i]['judge'] = 'NG';
                        if(!empty($readBody[$i]['message']))
                        {
                            $readBody[$i]['message'] .= "<br>規定の定時時間を越えています。";
                        }
                        else
                        {
                            $readBody[$i]['message'] = "規定の定時時間を越えています。";
                        }
                    }
                }
                $errorflg = false;
            }
        }
    }

    //------------------------//
    //   表示リスト作成処理   //
    //------------------------//
    if(!empty($errormessage))
    {
            $restable .= $errormessage;
    }
    if($errorflg)
    {
        $_SESSION['fileinsert']['judge'] = true;
        //----2018/01/23 項番32 asanoma リスト表示形式修正 start----->> 
//        $restable .= "<table class='list'><tr>"; 
//        for($i = 0 ; $i < count($columns_array) ; $i++)
//        {
//            $title_name = $form_ini[$columns_array[$i]]['link_num'];
//            $restable .="<th><a class ='head'>".$title_name."</a></th>";
//        }
//        $restable .= "</tr>";
        //既存進捗データの検索
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
                        //既存進捗データの削除
                        $code7 .= $result_row['7CODE'].",";
                    }
                    $code7 = rtrim($code7,',');
                    $sql = "DELETE FROM progressinfo WHERE 7CODE IN (".$code7.");";
                    $result = $con->query($sql) or ($judge = true);
                    if($judge)
                    {
                        error_log($con->error,0);
                        $judge = false;
                        //----2018/01/23 項番32 asanoma DBエラー対応追加 start----->>
                        $readBody[$i]['judge'] = "NG";
                        $readBody[$i]['message'] = "DB削除処理にてエラーが発生しました。";
                        $errorflg = false;
                        //----2018/01/23 項番32 asanoma DBエラー対応追加 end-----<<
                    }
                }
            }
        }
        
       
        for($i = 0; $i < count($readBody); $i++)
        {
            //progressinfoに登録
            $sql = "INSERT INTO progressinfo ( 3CODE, 6CODE, SAGYOUDATE, TEIZITIME, ZANGYOUTIME, 7ENDDATE, 7PJSTAT) "
                            ."VALUE (".$readBody[$i]['3CODE'].",".$readBody[$i]['6CODE'].",'".$readBody[$i]['date']."',".$readBody[$i]['teizi'].",".$readBody[$i]['zangyo'].",NULL,1);";
            $result = $con->query($sql) or ($judge = true);
            if($judge)
            {
                error_log($con->error,0);
                $judge = false;
                //----2018/01/23 項番32 asanoma DBエラー対応追加 start----->>
                $readBody[$i]['judge'] = "NG";
                $readBody[$i]['message'] = "DB登録処理にてエラーが発生しました。";
                $errorflg = false;
                //----2018/01/23 項番32 asanoma DBエラー対応追加 end-----<<
            }
        }       
        if($errorflg)
        {
            //テーブル作成
            $restable .= "<table class='list'><tr>"; 
            for($i = 0 ; $i < count($columns_array) ; $i++)
            {
                $title_name = $form_ini[$columns_array[$i]]['link_num'];
                $restable .="<th><a class ='head'>".$title_name."</a></th>";
            }
            $restable .="<th><a class ='head'>取り込み結果</a></th>";
            $restable .="<th><a class ='head'>メッセージ</a></th></tr>";
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
                $restable .= "<td class = 'center'>正常入力</td></tr>";
            }

//            //progressinfoに登録
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
            //----2018/01/23 項番32 asanoma リスト表示形式修正 end-----<<
    if(!$errorflg)
    {
        $_SESSION['fileinsert']['judge'] = false;
        $restable .= "<table class='list'><tr>"; 
        for($i = 0 ; $i < count($columns_array) ; $i++)
        {
            $title_name = $form_ini[$columns_array[$i]]['link_num'];
            $restable .="<th><a class ='head'>".$title_name."</a></th>";
        }
        $restable .="<th><a class ='head'>取り込み結果</a></th>";
        $restable .="<th><a class ='head'>メッセージ</a></th></tr>";
        //テーブル作成
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
                $readBody[$i]['message'] = "他の入力データにてエラーが発生しています。";
            }
            $restable .= "<td class = 'center'>".$readBody[$i]['message']."</td></tr>";
        }
    }
    //----2018/01/22 項番32 asanoma ソート削除対応 end -----<<
    $restable .= "</table>";

    return $restable;
    //return $tablenum ."　　　　　".$filename;
}
?>