/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   index.js
    By      :   BadTudu
    Date    :   2016年5月05日20:13:05
    Note    :   WikiNote主界面
*/
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

function IsLogin()
{
	if (GetQueryString('login') == 'true')
	{
		$('#head-menu-rightbutton-login').hide();
		$('#head-menu-rightbutton-user').show();
		console.log('has login');
	}
	else
	{
		$('#head-menu-rightbutton-login').show();
		$('#head-menu-rightbutton-user').hide();
		console.log('has not login');	
	}
}

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
	IsLogin();
	GetNotes(0, 10);
});