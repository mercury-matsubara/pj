<?php


/***************************************************************************
function makebutton($fileName,$buttonPosition)


����1	$fileName			�\���t�@�C����
����2	$buttonPosition		�\���ʒu

�߂�l	$con	mysql�ڑ��ς�object
***************************************************************************/

function makebutton($fileName,$buttonPosition){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$button_ini_array = parse_ini_file("./ini/button.ini",true);												// �{�^����{���i�[.ini�t�@�C��
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$column_num = $button_ini_array[$fileName]['buttom_column_num'];
	$button_num = $button_ini_array[$fileName]['set_button_'.$buttonPosition];
	$button_num_array = explode(",",$button_num);
	$total_button = count($button_num_array);
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$total_count = 0;
	$button_html = "";
	
	//------------------------//
	//     �{�^���쐬����     //
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

