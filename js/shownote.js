/**
 * [获取url传递的指定名称的参数值]
 * @param {[string]} name [参数名]
 */
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

function GetNote(title)
{
	$.ajax({
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getnote', title:title}
	})
	.done(function(json) 
	{
		if (json.stateCode == 0)
		{
			$('#note_content').val(json.msgText);
		}
	})
	.fail(function() {
		console.log("error");
	})
}

$(document).ready(function()
{
	var title = GetQueryString('note');
	if (title != '')
	{
		$('#note_title').val(decodeURI(title));
		GetNote(title);
	}
});