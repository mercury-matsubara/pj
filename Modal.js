function click_mail()
{
	var w = screen.availWidth;
	var h = screen.availHeight;
	w = (w * 0.8);
	h = (h * 0.8);
	var code ="";
	var input = document.getElementsByTagName("input");
	var inputname ;
	for(var i = 0 ; i < input.length ; i++)
	{
		if(input[i].type == 'checkbox')
		{
			if(input[i].checked == true)
			{
				inputname = input[i].name.split("_"); 
				code += ',' + inputname[1];
			}
		}
	}
	if(check_csv != '')
	{
		code = check_csv + code ;
	}
	if(code != '')
	{
		if(check_csv == '')
		{
			code=code.slice(1);
		}
		$.ajax({
			url: "set_session.php",
			dataType: 'json',
			type:"post",
			data: {
				data: code,
			},
			complete:function() {
				url = 'Mail.php';
				n = showModalDialog(
					url,
					this,
//					"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
					"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
				);
			}
		});
	}
	else
	{
		alert("発行対象を選択してください。");
	}
}

function click_label()
{
	var code ="";
	var input = document.getElementsByTagName("input");
	var inputname ;
	var w = screen.availWidth;
	var h = screen.availHeight;
	w = (w * 0.8);
	h = (h * 0.8);
	for(var i = 0 ; i < input.length ; i++)
	{
		if(input[i].type == 'checkbox')
		{
			if(input[i].checked == true)
			{
				inputname = input[i].name.split("_"); 
				code += ',' + inputname[1];
			}
		}
	}
	if(check_csv != '')
	{
		code = check_csv + code ;
	}
	if(code != '')
	{
		if(check_csv == '')
		{
			code=code.slice(1);
		}
		$.ajax({
			url: "set_session.php",
			dataType: 'json',
			type:"post",
			data: {
				data: code,
			},
			complete:function() {
				url = 'Label.php';
				n = showModalDialog(
					url,
					this,
//					"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
					"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
				);
			}
		});
	}
	else
	{
		alert("発行対象を選択してください。");
	}
}

function click_list(code,tablenum)
{
	var w = screen.availWidth;
	var h = screen.availHeight;
	w = (w * 0.8);
	h = (h * 0.8);
	url = 'pdf.php?code='+code+'&tablenum='+tablenum;
	n = showModalDialog(
		url,
		this,
//		"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
		"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
	);
}
function check_out(id)
{
	var out_obj = document.getElementById('checkout');
	var check_obj = document.getElementById(id);
	var befor = "";
	if(check_obj.checked == false)
	{
		if(out_obj.value == '')
		{
			out_obj.value = id;
		}
		else
		{
			out_obj.value += ','+id;
		}
	}
	else
	{
		if(out_obj.value.indexOf(id) != -1)
		{
			if(out_obj.value.indexOf(id) == 0)
			{
				befor = out_obj.value;
				replacetxt = id+',';
				out_obj.value = out_obj.value.replace(replacetxt,'');
				if(befor == out_obj.value)
				{
					replacetxt = id;
					out_obj.value = out_obj.value.replace(replacetxt,'');
					if(out_obj.value != "" )
					{
						out_obj.value = befor;
					}
				}
			}
			else
			{
				befor = out_obj.value;
				replacetxt = ','+id+',';
				out_obj.value = out_obj.value.replace(replacetxt,'');
				if(befor != out_obj.value )
				{
					replacetxt = ','+id;
					out_obj.value = befor;
					out_obj.value = out_obj.value.replace(replacetxt,'');
				}
				else
				{
					replacetxt = ','+id;
					out_obj.value = out_obj.value.replace(replacetxt,'');
					if((out_obj.value + ',' + id) != befor )
					{
						out_obj.value = befor ;
					}
				}
			}
		}
	}
}

function make_check_csv()
{
	var input = document.getElementsByTagName("input");
	var obj=RegExp(/check_/g);
	var befor = "";
	
	for(var i = 0 ; i < input.length ; i++)
	{
		if(input[i].type == 'checkbox')
		{
			if(input[i].checked == true)
			{
				befor = check_csv;
				check_csv = check_csv.replace(input[i].name + ',',"");
				if(befor == check_csv)
				{
					check_csv = check_csv.replace(',' + input[i].name,"");
				}
				if(befor == check_csv)
				{
					check_csv = check_csv.replace(input[i].name,"");
					if(check_csv != "")
					{
						check_csv = befor;
					}
				}
			}
		}
	}
	
	check_csv = check_csv.replace(obj,"");
}

