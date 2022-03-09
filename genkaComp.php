<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	require_once ("f_DB.php");
	start();
        
        $judge = false;
        $list = $_SESSION['list'];
        $con = dbconect();	
        
        insert_sousarireki($_SESSION["filename"],"2","");
        //原価マスタに登録されている社員情報を取得
        $genkaSQL = "SELECT 4CODE,STAFFID FROM genkainfo "
                . "LEFT JOIN syaininfo USING(4CODE) WHERE LUSERNAME is not null;";
        $result = $con->query($genkaSQL) or ($judge = true);																		// クエリ発行
	if($judge)
	{
            error_log($con->error,0);
            $judge = false;
	}
        while($result_row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $staffid = $result_row['STAFFID'];
            $genkaList[$staffid] = $result_row['4CODE'];
        }
        
        $keys = array_keys($list);
        
        //原価マスタに登録がある場合はUPDATE、登録がない場合はINSERT
        for($i = 0; $i < count($keys); $i++)
        {
            if(isset($genkaList[$keys[$i]]))
            {
                $SQL = "UPDATE genkainfo SET GENKA = '".$list[$keys[$i]]."' WHERE 4CODE = '".$genkaList[$keys[$i]]."';";
            }
            else if($keys[$i] != 'setGenka')
            {
                $syainSQL = "SELECT 4CODE FROM syaininfo WHERE STAFFID = '".$keys[$i]."';";
                $result2 = $con->query($syainSQL) or ($judge = true);																		// クエリ発行
                if($judge)
                {
                    error_log($con->error,0);
                    $judge = false;
                }
                $result2_row = $result2->fetch_array(MYSQLI_ASSOC);
                $SQL = "INSERT INTO genkainfo ( 4CODE , GENKA ) VALUES ( '".$result2_row['4CODE']."' , '".$list[$keys[$i]]."' );";
            }
            else
            {
                continue;
            }
            
            $result3 = $con->query($SQL) or ($judge = true);																		// クエリ発行
            if($judge)
            {
                    error_log($con->error,0);
            }
        }
        
        header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
        
        
        