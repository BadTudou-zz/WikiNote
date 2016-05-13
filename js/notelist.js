/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   notelist.js
    By      :   BadTudu
    Date    :   2016年5月06日11:55:05
    Note    :   WikiNote笔记列表
*/

/**
 * [重复代码，另一个副本在notelist.js中]
 */
function GetNoteList(start, count)
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getnotelist', start:start, count:count}
	})
	.done(function(json) 
	{
		$.each(json, function(idx, obj)
		{
			$('#userlist-uID').append('<li>'+obj['nID']+'</li>');
			$('#userlist-uNickName').append('<li>'+obj['title']+'</li>');
		});
	})
	.fail(function() 
	{
		console.log("error");
	})
}

$(document).ready(function()
{
	GetNoteList(0, 100);
});