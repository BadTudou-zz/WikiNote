/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   index.js
    By      :   BadTudu
    Date    :   2016年5月05日20:13:05
    Note    :   WikiNote主界面
*/

/**
 * [获取查询的字符串]
 * @param {[string]} name [参数名]
 */
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

/**
 * [是否是登录]
 */
function IsLogin()
{
	if (GetQueryString('login') == 'true')
	{
		$('#head-menu-rightbutton-login').hide();
		$('#head-menu-rightbutton-user').show();
	}
	else
	{
		$('#head-menu-rightbutton-login').show();
		$('#head-menu-rightbutton-user').hide();
	}
}

/**
 * [获取笔记列表]
 * @param {[int]} start [起始编号]
 * @param {[int]} count [笔记个数]
 */
function GetNoteList(start, count)
{
	$.ajax(
	{
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getnotelist', start:start, count:count}
	})
	.done(function(json) 
	{
		$.each(json, function(idx, obj)
		{
			var en = encodeURI(obj['title']);
			var link = encodeURI('../html/shownote.html?note='+en);
			$('#notelist').append('<li><a href="'+link+'">'+obj['title']+'</a></li>');
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
	GetNoteList(0, 10);
});