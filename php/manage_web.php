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
	require_once('WIKINOTE_MANAGER.php');
	if (isset($_POST['action']))
	{		
		$wikimanager = new WIKINOTE_MANAGER('localhost', '3306', 'root', 'mysql');
		$wikimanager->m_resource->connect();
		if ($wikimanager->m_resource->getConnectState())
		{
			$action =  $_POST['action'];
			switch ($action) 
			{
				case 'signin':
					$user = $_POST['manage_user'];
					$pwd = $_POST['manage_pwd'];
					if ($wikimanager->loginManager($user, $pwd) == false)
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

				case 'getnotelist':
					$start = $_POST['start'];
					$count = $_POST['count'];
					echo json_encode($wikimanager->getNotes($start, $count));
					break;

				case 'getusersbynickname':
					$nickname = $_POST['nickname'];
					echo json_encode($wikimanager->getUsersByNickname($nickname));
					break;
				case 'deleteuser':
					$userid = $_POST['id'];
					SendRespond(0, $wikimanager->delUser($userid));
					break;
				case 'gettypes':
					if ($_POST['type'] == '')
					{
						echo json_encode($wikimanager->getSubTypes(array()));
					}
					else
					{
						echo json_encode($wikimanager->getSubTypes(explode('/',$_POST['type'])));
					}
					break;
				case 'addtype':
					if($_POST['type'] == '')
					{
						$state = $wikimanager->m_resource->addType(array(), $_POST['add']);
					}
					else
					{
						error_log('add'.$_POST['add']);
						$state = $wikimanager->addType(explode('/',$_POST['type']), $_POST['add']);	
					}
					if ($state)
					{
						SendRespond(0, '添加成功');
					}
					else
					{
						SendRespond(1, '添加失败');
					}
				default:
					# code...
				break;
			}
		}
	}
?>