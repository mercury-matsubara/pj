<?php
/***************************************************************************
function sendmail($adress,$title,$sentence)


引数1	$customerPrintCD		BHTから送信された印刷番号
引数2	$customorStatus			BHTから送信された招待者ステータス

戻り値			なし
***************************************************************************/

function sendmail($adress,$title,$sentence){
	
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$mail_ini = parse_ini_file("./ini/mail.ini",true);																							// メール基本情報格納.iniファイル
	
	//------------------------//
	//          定数          //
	//------------------------//
	$systemorg = $mail_ini["smtp"]["systemorg"];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$error = "";






//-----------------------------------------------------------//
//                                                           //
//                     メール発行処理                        //
//                                                           //
//-----------------------------------------------------------//
	//--------------------------//
	//   phpmailer呼び出し処理  //
	//--------------------------//
	require_once("./class.phpmailer.php");																									// ライブラリ読み込み
	mb_internal_encoding("SJIS");																											// 内部エンコーディング(UTF-8)
	mb_language("Japanese");																												// 言語(日本語)
	$mail = new PHPMailer();																												// PHPMailerのインスタンス生成
	
	//--------------------------//
	//      SMTPサーバー設定    //
	//--------------------------//
	$mail->IsSMTP();																														//「SMTPサーバーを使うよ」設定
	$mail->SMTPAuth = TRUE;																													//「SMTP認証を使うよ」設定
	$mail->Host = $mail_ini["smtp"]["servername"].':'.$mail_ini["smtp"]["port"];															// SMTPサーバーアドレス:ポート番号
	$mail->Username =  $mail_ini["smtp"]["userid"];																							// SMTP認証用のユーザーID
	$mail->Password =  $mail_ini["smtp"]["userpass"];																						// SMTP認証用のパスワード

	//------------------------//
	//      メール発行設定    //
	//------------------------//
	$mail->Encoding = "7bit";																												// エンコーディング
	$mail->From = $mail_ini["smtp"]["orgaddress"];																							// 差出人(From)をセット
	$mail->FromName = mb_encode_mimeheader($systemorg,"UTF-8");																				// 差出人(From名)をセット
	$mail->Subject = mb_encode_mimeheader($title,"UTF-8");																					// 件名(title)をセット
	$mail->Body  = mb_convert_encoding($sentence,"UTF-8");																					// 本文(sentence)をセット

	
	
	//-------------------------//
	//    メール発行処理       //
	//-------------------------//
	$adress = trim($adress);
	$adress = trim($adress,'　');
	if($adress != '')
	{
		$mail->ClearAddresses();																											// 宛先削除
		$mail->AddAddress($adress);																											// 宛先をセット
		if (!$mail->Send())
		{
			$error = $mail->ErrorInfo;
		}
		else
		{
			// 成功時処理
		}
	}
	else
	{
		$error = 'メールアドレスが登録されていません。';
	}
	return($error);

}

?>
