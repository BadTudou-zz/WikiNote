/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	manager_signin.js
	By		:	BadTudu
	Date	:	2016年5月03日13:40:05
	Note	:	WikiNote管理员登录
*/
/**
 * [登录]
 * @param [string] user   [用户名]
 * @param [string] pwd    [密码]
 */
function SignIn(user, pwd)
{
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'signin', manage_user:user, manage_pwd:pwd}
	})
	.done(function(json) 
	{
		$('#userForm-inputform-tip').text(json.msgText);
		if (json.stateCode == 0)
		{
			location.href = '../html/manage.html';
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
		SignIn(user, pwd);
		
		return false;
	});	
});