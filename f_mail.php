<?php
/***************************************************************************
function sendmail($adress,$title,$sentence)


����1	$customerPrintCD		BHT���瑗�M���ꂽ����ԍ�
����2	$customorStatus			BHT���瑗�M���ꂽ���Ҏ҃X�e�[�^�X

�߂�l			�Ȃ�
***************************************************************************/

function sendmail($adress,$title,$sentence){
	
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$mail_ini = parse_ini_file("./ini/mail.ini",true);																							// ���[����{���i�[.ini�t�@�C��
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$systemorg = $mail_ini["smtp"]["systemorg"];
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$error = "";






//-----------------------------------------------------------//
//                                                           //
//                     ���[�����s����                        //
//                                                           //
//-----------------------------------------------------------//
	//--------------------------//
	//   phpmailer�Ăяo������  //
	//--------------------------//
	require_once("./class.phpmailer.php");																									// ���C�u�����ǂݍ���
	mb_internal_encoding("SJIS");																											// �����G���R�[�f�B���O(UTF-8)
	mb_language("Japanese");																												// ����(���{��)
	$mail = new PHPMailer();																												// PHPMailer�̃C���X�^���X����
	
	//--------------------------//
	//      SMTP�T�[�o�[�ݒ�    //
	//--------------------------//
	$mail->IsSMTP();																														//�uSMTP�T�[�o�[���g����v�ݒ�
	$mail->SMTPAuth = TRUE;																													//�uSMTP�F�؂��g����v�ݒ�
	$mail->Host = $mail_ini["smtp"]["servername"].':'.$mail_ini["smtp"]["port"];															// SMTP�T�[�o�[�A�h���X:�|�[�g�ԍ�
	$mail->Username =  $mail_ini["smtp"]["userid"];																							// SMTP�F�ؗp�̃��[�U�[ID
	$mail->Password =  $mail_ini["smtp"]["userpass"];																						// SMTP�F�ؗp�̃p�X���[�h

	//------------------------//
	//      ���[�����s�ݒ�    //
	//------------------------//
	$mail->Encoding = "7bit";																												// �G���R�[�f�B���O
	$mail->From = $mail_ini["smtp"]["orgaddress"];																							// ���o�l(From)���Z�b�g
	$mail->FromName = mb_encode_mimeheader($systemorg,"UTF-8");																				// ���o�l(From��)���Z�b�g
	$mail->Subject = mb_encode_mimeheader($title,"UTF-8");																					// ����(title)���Z�b�g
	$mail->Body  = mb_convert_encoding($sentence,"UTF-8");																					// �{��(sentence)���Z�b�g

	
	
	//-------------------------//
	//    ���[�����s����       //
	//-------------------------//
	$adress = trim($adress);
	$adress = trim($adress,'�@');
	if($adress != '')
	{
		$mail->ClearAddresses();																											// ����폜
		$mail->AddAddress($adress);																											// ������Z�b�g
		if (!$mail->Send())
		{
			$error = $mail->ErrorInfo;
		}
		else
		{
			// ����������
		}
	}
	else
	{
		$error = '���[���A�h���X���o�^����Ă��܂���B';
	}
	return($error);

}

?>
