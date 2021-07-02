
//---------------------------------------------------//
//                                                   //
//          メール送信ajax呼び出し関数               //
//        引数　なし                                 //
//        戻り値　なし                               //
//                                                   //
//---------------------------------------------------//
function click_send()
{
	$('#dialog2').dialog('open');
}
function send_mail()
{
	var send_num = 50;
	var num = 0;
	var total = code.split(",").length;
	var end_count = 0;
	while(1)
	{
		var mail_param =  new Object();
		var add = [];
		var sentence = [];
		var title = [];
		if((total- num) >= send_num )
		{
			end_count = send_num;
		}
		else
		{
			end_count = total- num;
		}
		for(var i = 0 ; i < end_count ; i++)
		{
			if(document.getElementById('adress'+num) != null)
			{
				add[i] = (document.getElementById('adress'+num).value);
				sentence[i] = (document.getElementById('sentence'+num).value);
				title[i] = (document.getElementById('title'+num).value);
			}
			else
			{
//				失敗時処理
			}
			num++;
		}
		mail_param.add = add;
		mail_param.sentence = sentence;
		mail_param.title = title;
		var mail = JSON.stringify(mail_param);
		$.ajax({
			url: "Mailsender.php",
			dataType: 'json',
			type:"post",
			data: {
				mail : mail,
			},
			success : function(){
				count_ajax--;
			},
			error : function(){
				count_ajax--;
//				alert(count_ajax);
				iserror = true;
			}
		});
		count_ajax++;
		if(end_count != send_num)
		{
			break;
		}
	}
	setTimeout("check_count();",1000);
}

function sleep(time, callback){
	setTimeout(callback, time);
}

function check_count()
{
	if(count_ajax != 0)
	{
		setTimeout("check_count();",1000);
	}
	else
	{
		if(iserror)
		{
			$.ajax({
				url: "set_error.php",
				dataType: 'json',
				type:"post",
				success : function(){
					$('#dialog2').dialog('close');
					window.open('./Mail_send.php','Modal');
				}
			});
		}
		else
		{
			$('#dialog2').dialog('close');
			window.open('./Mail_send.php','Modal');
		}
	}
}
