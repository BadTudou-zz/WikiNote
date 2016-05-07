<?php  
	header("Content-type: text/html; charset=utf-8");
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	user_web.php
	By		:	BadTudu
	Date	:	2016年05月03日17:52:05
	Note	:	客户端请求：用户部分
*/
	require_once('LIB.php');
	require_once('user.php');
	if (isset($_POST['action']))
	{
		$wikiuser = new WIKINOTE_USER('localhost', '3306', 'root', 'mysql');
		$wikiuser->m_database->connect();
		if ($wikiuser->m_database->getConnectState())
		{
			$action =  $_POST['action'];
			switch ($action) 
			{
				//登录
				case 'signin':
					$user = $_POST['user'];
					$pwd = $_POST['pwd'];
					if ($wikiuser->login($user, $pwd) == false)
					{
						SendRespond(1, '用户名或密码错误');
					}
					else
					{
						SendRespond(0, '登录成功');
					}
					break;

				//注册
				case 'register':
					$user = $_POST['user'];
					$pwd = $_POST['pwd'];
					if ($wikiuser->resister($user, $pwd))
					{
						SendRespond(1, '用户已存在');
					}
					else
					{
						SendRespond(0, '注册成功');
					}
					break;
				
				case 'getnotes':
					$start = $_POST['start'];
					$count = $_POST['count'];
					echo json_encode($wikiuser->getnotes($start, $count));
				
				default:
					# code...
					break;
			}
		}
		
	}

	/*function SignIn(string $user, string $pwd)
	{
		
	}*/
?>