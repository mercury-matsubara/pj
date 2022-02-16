<?php
        session_start();
        require_once("f_Construct.php");
	require_once("f_DB.php");
        
	$_SESSION['pre_post'] = $_SESSION['post'];
        
        $keyarray = array_keys($_POST);
	$url = 'retry';
	foreach($keyarray as $key)
        {
            if($key == 'copydate')
            {
                // 期間の開始日
                $begin = new DateTimeImmutable($_POST['pasteStart']);
                // 期間の終了日
                $end = new DateTimeImmutable($_POST['pasteEnd']);
                $interval = new DateInterval('P1D');

                $daterange = new DatePeriod( $begin, $interval ,$end );
                foreach( $daterange as $date ){
                    $dates[] = $date->format('Y-m-d').PHP_EOL;;
                }
                $dates[] = $_POST['pasteEnd'];

                $a = 0;

                //------------------------//
                //          処理          //
                //------------------------//

                // db接続関数実行
                $con = dbconect();

//                $selecrSQL = "SELECT 3CODE,6CODE,TEIZITIME,ZANGYOUTIME,7ENDDATE,7PJSTAT FROM progressinfo "
//                        . "LEFT JOIN syaininfo ON 4CODE WHERE SAGYOUDATE='".$_POST['copydate']."' AND 4CODE='".$_SESSION['user']['4CODE']."';";

                $selecrSQL = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo using (6CODE) "
                        . "where SAGYOUDATE = '".$_POST['copydate']."' AND 4CODE = '".$_SESSION['user']['4CODE']."';";
                
                // SQL実行
                $result = $con->query($selecrSQL);																	// クエリ発行
                if(!$result)
                {
                        error_log($con->error,0);
                        exit();
                }        

                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $copyDate[$a]['3CODE'] = $result_row['3CODE'];
                    $copyDate[$a]['6CODE'] = $result_row['6CODE'];
                    $copyDate[$a]['TEIZITIME'] = $result_row['TEIZITIME'];
                    $copyDate[$a]['ZANGYOUTIME'] = $result_row['ZANGYOUTIME'];
                    $copyDate[$a]['7PJSTAT'] = $result_row['7PJSTAT'];
                    $a++;
                }

                //削除する工数を検索
                $delete_selectsql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo using (6CODE) "
                        . "where SAGYOUDATE BETWEEN '".$_POST['pasteStart']."' AND '".$_POST['pasteEnd']."' AND 4CODE = '".$_SESSION['user']['4CODE']."';";
                
                $result = $con->query($delete_selectsql);																	// クエリ発行
                if(!$result)
                {
                        error_log($con->error,0);
                        exit();
                }
                
                $a = 0;
                while($result_row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $deletecode[$a] = $result_row['7CODE'];
                    $a++;
                }
                
                //工数削除
                for($i = 0; $i < count($deletecode); $i++)
                {
                    $deletesql = "DELETE FROM progressinfo WHERE 7CODE = '".$deletecode[$i]."';";
                    $result = $con->query($deletesql);																	// クエリ発行
                    if(!$result)
                    {
                            error_log($con->error,0);
                            exit();
                    }
                }
                
                //工数追加
                $insertSQL = "INSERT INTO progressinfo (3CODE,6CODE,TEIZITIME,ZANGYOUTIME,7PJSTAT,SAGYOUDATE) VALUES ";
                for($i = 0; $i  < count($dates); $i ++)
                {
                    for($j = 0; $j < count($copyDate); $j++)
                    {
                        $insertSQL .= '("'.$copyDate[$j]['3CODE'].'",';
                        $insertSQL .= '"'.$copyDate[$j]['6CODE'].'",';
                        $insertSQL .= '"'.$copyDate[$j]['TEIZITIME'].'",';
                        $insertSQL .= '"'.$copyDate[$j]['ZANGYOUTIME'].'",';
                        $insertSQL .= '"'.$copyDate[$j]['7PJSTAT'].'",';
                        $insertSQL .= '"'.$dates[$i].'"),';
                    }
                }
                $insertSQL = substr($insertSQL,0,-1);	
                $insertSQL .= ";";

                // SQL実行
                $result2 = $con->query($insertSQL);																	// クエリ発行
                if(!$result2)
                {
                        error_log($con->error,0);
                        exit();
                }   
            }
            else if($key == 'prev')
            {
                $_SESSION['TOP_4'] = $_SESSION['prev'];
                unset($_POST['prev']);
                $url = "TOP";
            }
            else if($key == 'next')
            {
                $_SESSION['TOP_4'] = $_SESSION['next'];
                unset($_POST['next']);
                $url = "TOP";
            }
        }
        
        if(isset($_GET['mainmenu']))
        {
            unset($_GET['mainmenu']);
            unset($_SESSION['TOP_4']);
            $url = "TOP";
        }
        
        header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
					.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/".$url.".php");