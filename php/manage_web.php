<?php  
	header("Content-type: text/html; charset=utf-8");
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	manage_web.php
	By		:	BadTudu
	Date	:	2016年05月05日20:44:05
	Note	:	客户端请求：管理员部分
*/
	require_once('LIB.php');
	require_once('manage.php');
	if (isset($_POST['action']))
	{		
		$wikimanager = new MANAGE('localhost', '3306', 'root', 'mysql');
		$wikimanager->m_database->connect();
		if ($wikimanager->m_database->getConnectState())
		{
			$action =  $_POST['action'];
			switch ($action) 
			{
				case 'signin':
					$user = $_POST['manage_user'];
					$pwd = $_POST['manage_pwd'];
					if ($wikimanager->login($user, $pwd) == false)
					{
						SendRespond(1, '用户名或密码错误');
					}
					else
					{
						SendRespond(0, '登录成功');
					}
					break;
				case 'getusers':
					$start = $_POST['start'];
					$count = $_POST['count'];
					echo json_encode($wikimanager->getUsers($start, $count));
					break;
				default:
					# code...
				break;
			}
		}
	}
?>