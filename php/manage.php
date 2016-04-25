<?php
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	MANAGE.php
	By		:	BadTudu
	Date	:	2016年4月25日13:06:05
	Note	:	后台管理
*/
	require_once('SQL.php');
	class MANAGE
	{
		public $m_database;
		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			$this->m_database = new MYSQL($host, $port, $user, $pwd);
		}

		/**
		 * [初始化数据库]
		 * @param  string $dbname [数据库名称]
		 */
		public function initDatabase(string $dbname)
		{
			if ($this->m_database->selectDatabase($dbname))
			{
				echo '切换成功';
			}
			//创建用户表
			$sqlcmd = 'CREATE TABLE user 
			(
				uID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY, 
				nickname VARCHAR(128) NOT NULL,
				pwd CHAR(128) NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);
	
			//创建笔记表
			$sqlcmd = 'CREATE TABLE note
			(
				nID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				notetypeID INTEGER UNSIGNED NOT 	NULL,
				title VARCHAR(128) NOT NULL,
				creatorID INTEGER UNSIGNED NOT 	NULL,
				createTime TIMESTAMP NOT NULL 	DEFAULT CURRENT_TIMESTAMP,
				modifyTime  TIMESTAMP NOT NULL 	DEFAULT CURRENT_TIMESTAMP ON 	UPDATE CURRENT_TIMESTAMP,
				filepath VARCHAR(128) NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);
		}

		/**
		 * [添加用户]
		 * @param string $password [密码]
		 * @param string $nickname [昵称]
		 */
		public function addUser(string $password, string $nickname)
		{
			$sqlcmd = 'INSERT INTO user (nickname, pwd) values (\''. $nickname .'\', \''. $password .'\')';
			return $this->m_database->executeQuery($sqlcmd);
		}
	}

	//TEST
	$my = new MANAGE('localhost', '3306', 'root', '123qwe');
	$my->m_database->connect();
	if ($my->m_database->getConnectState())
	{
		echo ' 连接成功';
		if ($my->m_database->selectDatabase('WikiNote'))
		{
			echo ' 选择数据库成功';
			if ($my->m_database->createDatabase('WikiNote'));
			{
				echo '创建成功';
				$my->initDatabase('WikiNote');
				$my->addUser('user1', 'pwd1');
				$my->addUser('user2', 'pwd2');
	
			}
			if ($my->m_database->dropDatabase('dd'))
			{
				echo '删除成功';
			}		
		}
	}
	else
	{
		echo '连接失败';
	}
		
?>