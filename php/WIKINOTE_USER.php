<?php
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	USER.php
	By		:	BadTudu
	Date	:	2016年4月26日20:23:05
	Note	:	用户操作
*/
	require_once('WIKINOTE_VISITOR.php');

	class WIKINOTE_USER extends WIKINOTE_VISITOR
	{
		
		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			parent::__construct($host, $port, $user, $pwd);
		}

		public function addNoteContent(int $creatorID, int $typeID,  string $title, string $content)
		{
			$sqlcmd = "INSERT INTO note (notetypeID, title, creatorID, content) values ($typeID, '$title', $creatorID, '$content')";
			return mysqli_error($this->m_database->executeQuery($sqlcmd)) == ''?true:false;
		}

		public function changePWD(string $nickname, string $pwdOld, string $pwdNew)
		{
			$sqlcmd = 'UPDATE user SET pwd = \''. $pwdNew .'\' WHERE nickname = \''. $nickname .'\' AND pwd = \''. $pwdOld .'\'';
			return mysqli_error($this->m_database->executeQuery($sqlcmd)) == ''?true:false;
		}
			
	}

	//TEST
	/*$my = new WIKINOTE_USER('localhost', '3306', 'root', 'mysql');
	$my->m_resource->connect();
	if ($my->m_resource->getConnectState())
	{
		echo ' 连接成功';
		if ($my->login('user1', 'pwd1') == false)
		{
			echo '空';
		}
	}*/
?>