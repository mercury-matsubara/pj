<?php


/***************************************************************************
function loglotation()


����			�Ȃ�

�߂�l			�Ȃ�
***************************************************************************/

function loglotaton(){


//-----------------------------------------------------------//
//                                                           //
//           ���O�t�@�C�����[�e�[�V��������                  //
//                                                           //
//-----------------------------------------------------------//

	$strTargetFileName = "./log/error.log";
	
	//-----------------------------//
	//   �t�@�C���ǂݎ�菀��      //
	//-----------------------------//
	$fp = fopen($strTargetFileName, "r+");
	$date = date_create('NOW');
	$date = date_format($date,'YmdHis');
	
	
	// �t�@�C���T�C�Y���m�F����B
	// �t�@�C���T�C�Y��5M�𒴂��Ă���悤�ł���΃��[�e�[�V�������s���B
	if ( filesize($strTargetFileName) >= 5120000 ) {
	
		// �������ݐ�n���h��
		$fpw = fopen("./log/".$date."_error.log", "w+");
	
		//-----------------------------//
		//   �t�@�C����r�����b�N      //
		//-----------------------------//
		if(flock($fp, LOCK_EX)){
			// �t�@�C�����b�N����

			// �t�@�C�����e�����ׂĎ擾���A�{�����t�t�@�C���ɏ�������
			while (!feof($fp)) {
	            $buffer = fgets($fp);
	            fwrite($fpw, $buffer);
	        }
			
			// �ǂݏo�����t�@�C���̒��g�����ׂď����B
			ftruncate($fp, 0);
			// ���b�N����
			flock($fp, LOCK_UN);
			// �t�@�C���N���[�Y
			fclose($fpw);
			// �t�@�C���N���[�Y
			fclose($fp);
			// ���O�t�H���_�ȉ��̃t�@�C�������擾��4�t�@�C���ȏ゠��Ήߋ��t�@�C�����폜����B
			$ListArray = glob("./log/*_error.log");
			
			// �ߋ����ɕ��בւ���
			arsort($ListArray);
			$i = 0;
			foreach ( $ListArray as $filedate ) {
				if ($i >= 3) {
					unlink($filedate);
				}
				
				$i++;
			}
			
		} else {
			// �t�@�C�����b�N���s
			// ���񃍃O�C�����ɍď����ł���Ζ��Ȃ�����
			// ���������Ɍ�����B
			fclose($fp); //�O�̂��߃N���[�Y
		}
	}
	else {
		// �����Ă��Ȃ���Ώ����𔲂���
		fclose($fp); //�O�̂��߃N���[�Y
	}
}

?>