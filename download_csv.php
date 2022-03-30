<?php
/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////
session_start();
require_once("f_Construct.php");
require_once("f_DB.php");
$form_ini = parse_ini_file('./ini/form.ini', true);
startJump($_POST);
$filename = $_SESSION['filename'];
if($filename == 'nenzi_5')
{
	$period = mb_convert_encoding($_POST['period'],'sjis-win','SJIS');
	$path = make_nenjicsv($period);
}
else if($filename == 'getuzi_5')
{
	$period = mb_convert_encoding($_POST['period'],'sjis-win','SJIS');
	$month = mb_convert_encoding($_POST['month'],'sjis-win','SJIS');
	$path = make_getujicsv($period,$month);
}
else if($filename == 'SYUEKIHYO_2')
{
        $path = make_syuekihyocsv();
}
else
{
	$path = make_csv($_SESSION['list']);
}
$date = date_create("NOW");
$date = date_format($date, "Ymd");
if($filename == 'nenzi_5')
{
	$file_name = "List_".$period. mb_convert_encoding("期年次_",'sjis-win','UTF-8').$date.".csv";
}
else if($filename == 'getuzi_5')
{
	$file_name = "List_".$period. mb_convert_encoding("�?",'sjis-win','UTF-8').$month.mb_convert_encoding("月月次_",'sjis-win','UTF-8').$date.".csv";
}
else
{
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$tablename = $form_ini[$tablenum]['table_title'];
	$tablename = mb_convert_encoding($tablename,'sjis-win','SJIS');
	$file_name = "List_".$tablename."_".$date.".csv";
}
if($filename == 'SYUEKIHYO_2')
{   
        $_SESSION['path'] = $path;
        $_SESSION['file_name'] = $file_name;
	$_SESSION['pre_post'] = $_SESSION['post'];
	$_SESSION['post'] = null;
        header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/list.php");
        exit();
}
header('Content-Type: application/octet-stream'); 
header('Content-Disposition: attachment; filename="'.$file_name.'"'); 
header('Content-Length: '.filesize($path));
readfile($path);
unlink($path);
?>