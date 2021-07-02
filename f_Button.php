<?php


/***************************************************************************
function makebutton($fileName,$buttonPosition)


引数1	$fileName			表示ファイル名
引数2	$buttonPosition		表示位置

戻り値	$con	mysql接続済みobject
***************************************************************************/

function makebutton($fileName,$buttonPosition){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$button_ini_array = parse_ini_file("./ini/button.ini",true);												// ボタン基本情報格納.iniファイル
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$column_num = $button_ini_array[$fileName]['buttom_column_num'];
	$button_num = $button_ini_array[$fileName]['set_button_'.$buttonPosition];
	$button_num_array = explode(",",$button_num);
	$total_button = count($button_num_array);
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$total_count = 0;
	$button_html = "";
	
	//------------------------//
	//     ボタン作成処理     //
	//------------------------//
/*
	if($column_num == 0 )
	{
		$column_num = $total_button;
	}
	if($buttonPosition == 'top')
	{
		while ($total_count != $total_button)
		{
			$button_html .= "<table class='top' WIDTH=100%><tr>";
			for($i = 0 ; $i < $column_num ; $i++)
			{
				if($button_num_array[$total_count] == '2')
				{
					$button_html .="<td class = 'right' style =' WIDTH:".
									$button_ini_array[$button_num_array[$total_count]]['size_x']."px'>";
					$button_html .="<input type = 'submit' class = 'button'";
					$button_html .=" name = '".$button_ini_array[$button_num_array[$total_count]]['button_name']."' ";
					$button_html .=" value = '".$button_ini_array[$button_num_array[$total_count]]['value']."' ";
					$button_html .=" style='WIDTH:".$button_ini_array[$button_num_array[$total_count]]['size_x']."px  ;";
					$button_html .=" HEIGHT:".$button_ini_array[$button_num_array[$total_count]]['size_y']."px' >";
					$button_html .="</td>";
				}
				else
				{
					$button_html .="<td class = 'left' style =' WIDTH:".
									$button_ini_array[$button_num_array[$total_count]]['size_x']."px'>";
					$button_html .="<input type = 'submit' class = 'button'";
					$button_html .=" name = '".$button_ini_array[$button_num_array[$total_count]]['button_name']."' ";
					$button_html .=" value = '".$button_ini_array[$button_num_array[$total_count]]['value']."' ";
					$button_html .=" style='WIDTH:".$button_ini_array[$button_num_array[$total_count]]['size_x']."px  ;";
					$button_html .=" HEIGHT:".$button_ini_array[$button_num_array[$total_count]]['size_y']."px' >";
					$button_html .="</td>";
				}
				$total_count++;
				if($total_count == $total_button)
				{
					$button_html .="</tr></table>";
					break  2;
				}
			}
		}
	}
	else
	{
		while ($total_count != $total_button)
		{
			for($i = 0 ; $i < $column_num ; $i++)
			{
				$button_html .="<div class = 'left' style =' HEIGHT:".
								$button_ini_array[$button_num_array[$total_count]]['size_y']."px'>";
				$button_html .="<input type = 'submit' class = 'button'";
				$button_html .=" name = '".$button_ini_array[$button_num_array[$total_count]]['button_name']."' ";
				$button_html .=" value = '".$button_ini_array[$button_num_array[$total_count]]['value']."' ";
				$button_html .=" style='WIDTH:".$button_ini_array[$button_num_array[$total_count]]['size_x']."px  ;";
				$button_html .=" HEIGHT:".$button_ini_array[$button_num_array[$total_count]]['size_y']."px' >";
				$button_html .="</div>";
				$total_count++;
				if($total_count == $total_button)
				{
					break  2;
				}
			}
			$button_html .="<div style='clear:both;'></div>";
		}
	}
	return ($button_html);
*/
	if($column_num == 0 )
	{
		$column_num = $total_button;
	}
	while ($total_count != $total_button)
	{
		for($i = 0 ; $i < $column_num ; $i++)
		{
			$button_html .="<div class = 'left' style =' HEIGHT:".
							$button_ini_array[$button_num_array[$total_count]]['size_y']."px'>";
			$button_html .="<input type = 'submit' class = 'button'";
			$button_html .=" name = '".$button_ini_array[$button_num_array[$total_count]]['button_name']."' ";
			$button_html .=" value = '".$button_ini_array[$button_num_array[$total_count]]['value']."' ";
			$button_html .=" style='WIDTH:".$button_ini_array[$button_num_array[$total_count]]['size_x']."px  ;";
			$button_html .=" HEIGHT:".$button_ini_array[$button_num_array[$total_count]]['size_y']."px' >";
			$button_html .="</div>";
			$total_count++;
			if($total_count == $total_button)
			{
				break  2;
			}
		}
		$button_html .="<div style='clear:both;'></div>";
	}
	return ($button_html);
}

