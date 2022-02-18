var row_copy = { CODE6:'', PJNUM:'', EDABAN:'', PJNAME:'', STAFFID:'', STAFFNAME:'', CODE3:'', KOUTEIID:'', KOUTEINAME:'', TEIZI:'', ZANGYOU:'0' };//コピー用
var row_init = { CODE6:'', PJNUM:'', EDABAN:'', PJNAME:'', STAFFID:'', STAFFNAME:'', CODE3:'', KOUTEIID:'', KOUTEINAME:'', TEIZI:'', ZANGYOU:'0' };//クリア用
var color_copy = { CODE6:'', PJNUM:'', EDABAN:'', PJNAME:'', STAFFID:'', STAFFNAME:'', CODE3:'', KOUTEIID:'', KOUTEINAME:'', TEIZI:'', ZANGYOU:'' };//カラーコピー用
var color_init = { CODE6:'#fff', PJNUM:'#fff', EDABAN:'#fff', PJNAME:'#fff', STAFFID:'#fff', STAFFNAME:'#fff', CODE3:'#fff', KOUTEIID:'#fff', KOUTEINAME:'#fff', TEIZI:'#fff', ZANGYOU:'#fff' };//カラークリア用
//行のデータを連想配列に入れて返す
function getRowData(pos)
{
	var row = {};
	row.CODE6 = $('#6CODE_'+pos).val();	
	row.PJNUM = $('#form_102_0_'+pos).val();	
	row.EDABAN = $('#form_202_0_'+pos).val();	
	row.PJNAME = $('#form_203_0_'+pos).val();	
	row.STAFFID = $('#form_402_0_'+pos).val();	
	row.STAFFNAME = $('#form_403_0_'+pos).val();	
	row.CODE3 = $('#3CODE_'+pos).val();	
	row.KOUTEIID = $('#form_302_0_'+pos).val();	
	row.KOUTEINAME = $('#form_303_0_'+pos).val();	
	row.TEIZI = $('#form_705_0_'+pos).val();	
	row.ZANGYOU = $('#form_706_0_'+pos).val();	
	
	return row;
}
//指定行に連想配列のデータをセットする
function setRowData(pos,row)
{
	$('#6CODE_'+pos).val(row.CODE6 );
	$('#form_102_0_'+pos).val(row.PJNUM );
	$('#form_202_0_'+pos).val( row.EDABAN );
	$('#form_203_0_'+pos).val( row.PJNAME );
	$('#form_402_0_'+pos).val( row.STAFFID );
	$('#form_403_0_'+pos).val( row.STAFFNAME );
	$('#3CODE_'+pos).val(row.CODE3 );
	$('#form_302_0_'+pos).val( row.KOUTEIID );	
	$('#form_303_0_'+pos).val( row.KOUTEINAME );
	$('#form_705_0_'+pos).val( row.TEIZI );	
	$('#form_706_0_'+pos).val( row.ZANGYOU );
}
//指定行を白色にする（insert時）
function changeColor(pos)
{
    document.getElementById('form_102_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_202_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_203_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_402_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_403_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_302_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_303_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_705_0_'+pos).style.backgroundColor = '';	
    document.getElementById('form_706_0_'+pos).style.backgroundColor = '';
}
//指定行をグローバル変数にコピーする
function copyRow( pos )
{
	row_copy = getRowData(pos);
}
//指定行にグローバル変数のデータをコピーする
function pasteRow( pos )
{
	setRowData(pos,row_copy);
        changeColor(pos);
        totalTime();
}
//指定箇所の行を削除してつめる
function removeRow( pos )
{
	setRowData(pos,row_init);	//10行目は空白にする
        changeColor(pos);	//10行目は白色にする
        totalTime();
}
//工数複数登録時のチェック
function PROGRESScheck(post)
{
    var judge = true;
    var dates = 0;
    var m = String.fromCharCode(event.keyCode);
    if((typeof isCancel != 'undefined' && isCancel == false) || 
            (typeof ischeckpass != 'undefined' && ischeckpass == true))
    {
        for(var i = 0; i < 10; i++)
        {
            //入力のない行はチェックしない
            if(document.getElementById('form_102_0_'+i).value == "" &&
                    document.getElementById('form_302_0_'+i).value == "" &&
                    document.getElementById('form_705_0_'+i).value == "" &&
                    document.getElementById('form_706_0_'+i).value == "0")
            {
                continue;
            }
            else
            {
                if(document.getElementById('form_102_0_'+i).value == "")
                {
                    document.getElementById('form_102_0_'+i).style.backgroundColor = '#ff0000';
                    document.getElementById('form_202_0_'+i).style.backgroundColor = '#ff0000';
                    document.getElementById('form_203_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else
                {
                    document.getElementById('form_102_0_'+i).style.backgroundColor = '';
                    document.getElementById('form_202_0_'+i).style.backgroundColor = '';
                    document.getElementById('form_203_0_'+i).style.backgroundColor = '';
                }
                
                if(document.getElementById('form_302_0_'+i).value == "")
                {
                        document.getElementById('form_302_0_'+i).style.backgroundColor = '#ff0000';
                        document.getElementById('form_303_0_'+i).style.backgroundColor = '#ff0000';
                        judge = false;
                }
                else
                {
                        document.getElementById('form_302_0_'+i).style.backgroundColor = '';
                        document.getElementById('form_303_0_'+i).style.backgroundColor = '';
                }
                
                var teizitime = document.getElementById('form_705_0_'+i).value;
                var teizi = document.getElementById('form_705_0_'+i).value * 100;
                var zangyoutime = document.getElementById('form_706_0_'+i).value;
                var zangyou = document.getElementById('form_706_0_'+i).value * 100;
                               
                if(teizitime.match(/[^0-9\.]+/))
                {
                    document.getElementById('form_705_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else if (strlen(teizitime) > 4)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
                            document.getElementById('form_705_0_'+i).style.backgroundColor = '#ff0000';
                            judge = false;
			}
		}
                else if(document.getElementById('form_705_0_'+i).value == '')
                {
                    document.getElementById('form_705_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else if(teizi % 25 != 0)
                {
                    document.getElementById('form_705_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else
                {
                    document.getElementById('form_705_0_'+i).style.backgroundColor = '';
                }
                
                if(zangyoutime.match(/[^0-9\.]+/))
                {
                    document.getElementById('form_706_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else if (strlen(zangyoutime) > 5)
		{
			if("\b\r".indexOf(m, 0) < 0)
			{
                            document.getElementById('form_706_0_'+i).style.backgroundColor = '#ff0000';
                            judge = false;
			}
		}
                else if(document.getElementById('form_706_0_'+i).value == '')
                {
                    document.getElementById('form_706_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else if(zangyou % 25 != 0)
                {
                    document.getElementById('form_706_0_'+i).style.backgroundColor = '#ff0000';
                    judge = false;
                }
                else
                {
                    document.getElementById('form_706_0_'+i).style.backgroundColor = '';
                }          
                dates++;
            }
        }
        
        if(document.getElementById('teizitotal').value > 7.75)
        {
            judge = false;
        }

        var sagyoutotal = document.getElementById('teizitotal').value * 100; 
        sagyoutotal += document.getElementById('zangyoutotal').value * 100;
        if(sagyoutotal > 2400)
        {
            judge = false;
        }
        
        if(dates == 0 && post == "insert")
        {
            judge = false;
        }
        else
        {
            if(!judge)
            {
                alert('入力内容に誤りがあります。');
            }
            else if(document.getElementById('teizitotal').value < 7.75)
            {
                var result = window.confirm('定時時間が7.75未満ですが、このまま登録しますか？');

                if(!result)
                {
                    judge = false;
                }
            }
        }
    }
    return judge;
}
//定時時間と残業時間の合計を計算
function totalTime()
{
    var teizitotal = 0;
    var zangyoutotal = 0;
    for(var i = 0; i < 10; i++)
    {
        var teiziid = 'form_705_0_'+i;
        var zangyouid = 'form_706_0_'+i;
        if(document.getElementById(teiziid).value == '')
        {
                teizitotal += 0;
                document.getElementById(teiziid).style.backgroundColor = '';
        }
        else 
        {
            var teizi = document.getElementById(teiziid).value * 100;
            if(document.getElementById(teiziid).value.match(/[^0-9\.]+/) ||
                    teizi % 25 != 0)
            {
                    document.getElementById(teiziid).style.backgroundColor = '#ff0000';
            }
            else
            {
                    document.getElementById(teiziid).style.backgroundColor = '';
                    teizitotal += parseInt(teizi);
            }
        }
        if(document.getElementById(zangyouid).value == '')
        {
                zangyoutotal += 0;
                document.getElementById(zangyouid).style.backgroundColor = '';
        }
        else
        {
            var zangyou = document.getElementById(zangyouid).value * 100;
            if(document.getElementById(zangyouid).value.match(/[^0-9\.]+/) ||
                    zangyou % 25 != 0)
            {
                    document.getElementById(zangyouid).style.backgroundColor = '#ff0000';
            }
            else
            {
                    document.getElementById(zangyouid).style.backgroundColor = '';
                    zangyoutotal += parseInt(zangyou);
            }
        }
    }
    document.getElementById('teizitotal').value = teizitotal /100;
    document.getElementById('zangyoutotal').value = zangyoutotal /100;
}