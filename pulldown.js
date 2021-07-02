function change(id,isdisabled,formname){
	var obj = document.forms[formname].elements(id);
	var select = obj.selectedIndex;
	var judge = false;
	if(isdisabled != '')
	{
		judge = true;
	}
	for(var i = 0 ; i < obj.options.length ; i++ )
	{
		if(i != select)
		{
			 obj.options[i].disabled = judge;
		}
	}
}
