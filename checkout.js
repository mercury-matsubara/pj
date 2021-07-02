function checkout(id)
{
	if(document.getElementsByName('checkout')[0])
	{
		var out_obj = document.getElementById('checkout');
		var check_obj = document.getElementById(id);
		var replacetxt ='';
		if(check_obj.checked == false)
		{
			if(out_obj.value == '')
			{
				out_obj.value = id;
//				alert(out_obj.value);
			}
			else
			{
				out_obj.value += ','+id;
//				alert(out_obj.value);
			}
		}
		else
		{
			if(out_obj.value.indexOf(id) != -1)
			{
				if(out_obj.value.indexOf(id) == 0)
				{
					replacetxt = id;
					out_obj.value = out_obj.value.replace(replacetxt,'');
//					alert(out_obj.value);
				}
				else
				{
					replacetxt = ','+id;
					out_obj.value = out_obj.value.replace(replacetxt,'');
//					alert(out_obj.value);
				}
			}
		}
	}
}
