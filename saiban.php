<?php



/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////






	include('./f_DB.php');
	$html = "";
	$num = 0 ;
	$judge = false;
	$sql = "SELECT ifnull(min(genbainfo.GENBAID), '0') + 1 as SAIBAN FROM genbainfo WHERE ifnull(genbainfo.GENBAID, '0') + 1 not in ( SELECT ifnull(genbainfo.GENBAID, '0') + 0 FROM genbainfo)";
	$sql2 = "SELECT * FROM genbainfo WHERE genbainfo.GENBAID = '00000';";
	$con=dbconect();
	$result = $con->query($sql2) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	$num = $result->num_rows;																							// 検索結果件数取得
	if($num != 0)
	{
		$result = $con->query($sql) or ($judge = true);																		// クエリ発行
		if($judge)
		{
			error_log($con->error,0);
			$judge = false;
		}
		while ($result_row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$html = str_pad($result_row['SAIBAN'], 5, "0", STR_PAD_LEFT);
		}
	}
	else
	{
		$html = '00000';
	}
	header('Content-type: application/json');
	echo json_encode($html);
?>