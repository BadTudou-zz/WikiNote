<?php
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	USER.php
	By		:	BadTudu
	Date	:	2016年4月26日20:23:05
	Note	:	用户操作
*/
	require_once('SQL.php');
	require_once('manage.php');
	class WIKINOTE_USER
	{
		public $m_database;
		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			$this->m_database = new MYSQL($host, $port, $user, $pwd);
		}

		/**
		 * [用户登录]
		 * @param  string $nickname [昵称]
		 * @param  string $pwd      [密码]
		 * @return [string]         [成功,用户ID;失败,空字符串]
		 */
		public function login(string $nickname, string $pwd)
		{
			$this->m_database->connect();
			if ($this->m_database->selectDatabase('WikiNote'))
			{
				$sqlcmd = 'SELECT uID FROM user WHERE nickname = \''. $nickname .'\' AND pwd = \''. $pwd .'\'';
				$queryResult = $this->m_database->executeQuery($sqlcmd);
				$fields = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
				if (is_array($fields))
				{
					return $fields['uID'];
				}
			}
			return '';
		}

		public function register(string $nickname, string $pwd)
		{
			$this->m_database = new MANAGE('localhost', '3306', 'root', 'mysql');
			$this->m_database->addUser($nickname, $pwd);
			return true;
		}

		public function getnotes(int $start, int $count)
		{
			$this->m_database = new MANAGE('localhost', '3306', 'root', 'mysql');
			return $this->m_database->getNotes($start, $count);
		}

		public function addnote(int $creatorID, int $typeID,  string $title, string $content)
		{
			$this->m_database = new MANAGE('localhost', '3306', 'root', 'mysql');
			return $this->m_database->addNote($creatorID, 1, $title, $content);
		}

		public function getnote(string $title)
		{
			$this->m_database = new MANAGE('localhost', '3306', 'root', 'mysql');
			return $this->m_database->getNote($title);
		}
			
	}

	//TEST
	/*$my = new WIKINOTE_USER('localhost', '3306', 'root', 'mysql');
	$my->m_database->connect();
	if ($my->m_database->getConnectState())
	{
		echo ' 连接成功';
		if ($my->login('user1', 'pwd1') == false)
		{
			echo '空';
		}
	}*/
?>