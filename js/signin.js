/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	signin.js
	By		:	BadTudu
	Date	:	2016年5月03日13:40:05
	Note	:	WikiNote用户登录
*/

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
 
/**
 * [用户活动是否为登录]
 */
function IsUserLoginAction()
{
	if ($('#button_changeAction').text() == '转到注册')
	{
		return true;
	}
	return false;
}

/**
 * [根据活动改变视图]
 * @param {[bool]} state [true:注册, false:登录]
 */
function ChangeViewByAction(state)
{
	if (state)
	{
		$('title').text('注册WikiNote');
		$('#logo-text').text('注册WikiNote');
		$('#button_signin').val('立即注册');
		$('#check_rememberLoginInfo').hide();
		$('#text_rememberLoginInfo').hide();
		$('#a_forgetPassword').hide();
		$('#openPlatform-title').text('使用其他帐号注册');
		$('#button_changeAction').text('转到登录');
	}
	else
	{
		$('title').text('登录WikiNote');
		$('#logo-text').text('登录WikiNote');
		$('#button_signin').val('立即登录');
		$('#check_rememberLoginInfo').show();
		$('#text_rememberLoginInfo').show();
		$('#a_forgetPassword').show();
		$('#openPlatform-title').text('使用其他帐号登录');
		$('#button_changeAction').text('转到注册');
	}

}
/**
 * [登录/注册]
 * @param [string] action [活动：登录 或 注册]
 * @param [string] user   [用户名]
 * @param [string] pwd    [密码]
 */
function SignIn(action, user, pwd)
{
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
	if (GetQueryString('action') == 'register')
	{
		ChangeViewByAction(true);
	}
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
		ChangeViewByAction(IsUserLoginAction());
		return false;
		
	});
});