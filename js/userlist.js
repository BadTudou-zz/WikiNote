/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   userlist.js
    By      :   BadTudu
    Date    :   2016年5月06日11:55:05
    Note    :   WikiNote主界面
*/
function GetUsers(start, count)
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getusers', start:start, count:count}
	})
	.done(function(json) 
	{
		$.each(json, function(idx, obj)
		{
			$('#userlist-uID').append('<li>'+obj['uID']+'</li>');
			$('#userlist-uNickName').append('<li>'+obj['nickname']+'</li>');
		});
	})
	.fail(function() 
	{
		console.log("error");
	})
}

$(document).ready(function()
{
	GetUsers(0, 10);
});