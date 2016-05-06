/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   index.js
    By      :   BadTudu
    Date    :   2016年5月05日20:13:05
    Note    :   WikiNote主界面
*/

function GetNotes(start, count)
{
	$.ajax(
	{
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getnotes', start:start, count:count}
	})
	.done(function(json) 
	{
		$.each(json, function(idx, obj)
		{
			$('#notelist').append('<li>'+obj['title']+'</li>');
			console.log(idx+' '+obj['title']);
		});
	})
	.fail(function() 
	{
		console.log("error");
	})
}

$(document).ready(function()
{
	GetNotes(0, 10);
});