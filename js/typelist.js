/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   userlist.js
    By      :   BadTudu
    Date    :   2016年5月06日11:55:05
    Note    :   WikiNote主界面
*/
function GetTypes()
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'gettypes'}
	})
	.done(function(json) 
	{
		$('#userlist-uID').empty();
		$('#userlist-uNickName').empty();
		$.each(json, function(idx, obj)
		{
			$('#userlist-uID').append('<li>'+obj['tID']+'</li>');
			$('#userlist-uNickName').append('<li>'+obj['title']+'</li>');
		});
	})
	.fail(function() 
	{
		console.log("error");
	})
}

function ShowSubDiv(pos)
{
	$('#div_add').hide();
	$('#div_search').hide();
	$('#div_del').hide();
	switch(pos)
	{
		case 1:
			$('#div_add').show();
			break;
		case 2:
			$('#div_search').show();
			break;
		case 3:
			$('#div_del').show();
			break;
	}
}
function GetUsersByNickname(nickname)
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'getusersbynickname', nickname:nickname}
	})
	.done(function(json) 
	{
		$('#userlist-uID').empty();
		$('#userlist-uNickName').empty();
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

function AddUser(user, pwd) 
{
	console.log(user+' '+pwd);
	$.ajax(
	{
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'register', user:user, pwd:pwd}
	})
	.done(function(json) 
	{
		$('#userForm-inputform-tip').text(json.msgText);
		if (json.stateCode == 0)
		{
			window.location.reload();
		}
	})
	.fail(function(json) 
	{
		alert('服务器发生错误'+json);
	})
	// body...
}

function DelUser(userID) 
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'deleteuser', id:userID}
	})
	.done(function(json) 
	{
		if (json.stateCode == 0)
		{
			console.log('删除成功');
			GetUsers(0, 100);
		}
		else
		{
			alert('删除失败');
		}
	})
	.fail(function(json) 
	{
		alert('服务器发生错误'+json);
	})
	// body...
}

$(document).ready(function()
{
	GetTypes();
	ShowSubDiv(0);
	$('#allUser').click(function(event) 
	{
		ShowSubDiv(0);
		GetUsers(0, 100);
	});
	$('#addUser').click(function(event) {
		/* Act on the event */
		if ($("#div_add").is(":hidden") )
		{
			ShowSubDiv(1);
		}
		else
		{
			ShowSubDiv(0);
		}
	});
	$('#searchUser').click(function(event) {
		/* Act on the event */
		if ($("#div_search").is(":hidden") )
		{
			ShowSubDiv(2);
		}
		else
		{
			ShowSubDiv(0);
		}
	});
	$('#delUser').click(function(event) {
		/* Act on the event */
		if ($("#div_del").is(":hidden") )
		{
			ShowSubDiv(3);
		}
		else
		{
			ShowSubDiv(0);
		}
	});
	$('#addnew').click(function(event) 
	{
		var user = $('#input_user').val();
		var pwd = $('#input_pwd').val();
		console.log('addnew');
		if (user == '' || pwd == '')
		{
			return false;
		}
		AddUser(user, pwd);
	});

	$('#delete').click(function(event) 
	{
		var id = $('#input_id').val();
		console.log('uid'+id);
		DelUser(id);
	});

	$('#search').click(function(event) 
	{
		var nickname = $('#input_nickname').val();
		console.log('nickname');
		GetUsersByNickname(nickname);
	});
});
