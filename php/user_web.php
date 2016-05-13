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
					$uID = $wikiuser->login($user, $pwd);
					if ($uID == false)
					{
						SendRespond(1, '用户名或密码错误');
					}
					else
					{
						session_start();
						$_SESSION['uID']  = $uID;
						$timeOut = time()+3600*24;
						setcookie('uID', $uID);
						SendRespond(0, '登录成功');
						session_write_close();
					}
					break;

				//注册
				case 'register':
					$user = $_POST['user'];
					$pwd = $_POST['pwd'];
					if ($wikiuser->register($user, $pwd))
					{
						SendRespond(0, '注册成功');
					}
					else
					{
						SendRespond(1, '用户已存在');
					}
					break;
				
				case 'getnotelist':
					$start = $_POST['start'];
					$count = $_POST['count'];
					echo json_encode($wikiuser->getnotes($start, $count));
					break;
				
				case 'addnote':
					session_start();
					$uID = $_SESSION['uID'];
					$title = $_POST['title'];
					$content = $_POST['content'];
					$wikiuser->addnote($uID, 1, $title, $content);
					session_write_close();
					SendRespond(0, '创建成功');
					break;

				case 'getnote':
					$title = $_POST['title'];
					$note = $wikiuser->getnote($title);
					SendRespond(0, $note['content']);
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