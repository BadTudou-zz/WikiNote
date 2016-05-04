/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	signin.js
	By		:	BadTudu
	Date	:	2016年5月03日13:40:05
	Note	:	WikiNote用户登录
*/
function IsUserLoginAction()
{
	if ($('#button_changeAction').text() == '转到注册')
	{
		return true;
	}
	return false;
}

function SignIn(action, user, pwd)
{
	console.log('user:'+user+' pwd:'+pwd);
	$.ajax(
	{
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: action, user:user, pwd:pwd}
	})
	.done(function(json) 
	{
		$('#userForm-inputform-tip').text(json.msgText);
		if (json.stateCode == 0)
		{
			location.href = '../html/index.html';
		}
	})
	.fail(function(json) 
	{
		alert('服务器发生错误'+json);
	})
}

$(document).ready(function()
{
	$('#button_signin').click(function(event) 
	{
		var user = $('#input_user').val();
		var pwd = $('#input_pwd').val();
		if (IsUserLoginAction())
		{
			SignIn('signin', user, pwd);
		}
		else
		{
			SignIn('register', user, pwd);
		}
		return false;
	});

	$('#button_changeAction').click(function(event) 
	{
		if (IsUserLoginAction())
		{
			$('#logo-text').text('注册WikiNote');
			$('#button_signin').val('立即注册');
			$('#check_rememberLoginInfo').hide();
			$('#text_rememberLoginInfo').hide();
			$('#a_forgetPassword').hide();
			$('#openPlatform-title').text('使用其他帐号注册');
			$(this).text('转到登录');
		}
		else
		{
			$('#logo-text').text('登录WikiNote');
			$('#button_signin').val('立即登录');
			$('#check_rememberLoginInfo').show();
			$('#text_rememberLoginInfo').show();
			$('#a_forgetPassword').show();
			$('#openPlatform-title').text('使用其他帐号登录');
			$(this).text('转到注册');

		}

		return false;
		
	});
});