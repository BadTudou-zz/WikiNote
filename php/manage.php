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

			//参加笔记分类表
			$sqlcmd = 'CREATE TABLE type
			(
				tID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				t1ID INTEGER UNSIGNED NOT NULL,
				t2ID INTEGER UNSIGNED NOT NULL,
				t3ID INTEGER UNSIGNED NOT NULL,
				t4ID INTEGER UNSIGNED NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);
			for ($i=1; $i <= 4; $i++) 
			{ 
				$sqlcmd = 'CREATE TABLE t'. $i .'
				(
					tPID INTEGER UNSIGNED NOT NULL,
					tID INTEGER UNSIGNED AUTO_INCREMENT  NOT NULL PRIMARY KEY,
					title VARCHAR(128) NOT NULL UNIQUE
				)';
				$this->m_database->executeQuery($sqlcmd);
			}
			
		}
		//创建日志表
			$sqlcmd = 'CREATE TABLE log
			(
				logID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				uID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL ,
				nID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL ,
				replacetime TIMESTAMP NOT NULL 	DEFAULT CURRENT_TIMESTAMP,
				filepath VARCHAR(128) NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);

			//创建消息表
			$sqlcmd = 'CREATE TABLE msg
			(
				msgID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				mtext VARCHAR(128) NOT NULL,

			)';
			$this->m_database->executeQuery($sqlcmd);

		/**
		 * [添加用户]
		 * @param string $nickname [昵称]
		 * @param string $password [密码]
		 */
		public function addUser(string $nickname, string $pwd)
		{
			$sqlcmd = 'INSERT INTO user (nickname, pwd) values (\''. $nickname .'\', \''. $pwd .'\')';
			return $this->m_database->executeQuery($sqlcmd);
		}

		/**
		 * [删除用户]
		 * @param string $nickname [昵称]
		 * @param string $password [密码]
		 * @return [bool]           [description]
		 */
		public function delUser(string $nickname, string $pwd)
		{
			$sqlcmd = 'DELETE FROM user WHERE  nickname = \''. $nickname .'\' AND pwd = \''. $pwd .'\'';
			return $this->m_database->executeQuery($sqlcmd);
		}

		/**
		 * [更改用户密码]
		 * @param  string $nickname [昵称]
		 * @param  string $pwdOld   [原密码]
		 * @param  string $pwdNew   [新密码]
		 * @return [type]           [description]
		 */
		public function changeUserPWD(string $nickname, string $pwdOld, string $pwdNew)
		{
			$sqlcmd = 'UPDATE user SET pwd = \''. $pwdNew .'\' WHERE nickname = \''. $nickname .'\' AND pwd = \''. $pwdOld .'\'';
			return $this->m_database->executeQuery($sqlcmd);
		}

		/**
		 * [添加类别]
		 * @param array $aType [类别，key代表ID,value代表类别名称]
		 */
		public function addType(array $aType)
		{
			$i = 1;
			foreach ($aType as $key => $value) 
			{
				$sqlcmd = "INSERT INTO t$i (tPID, title) values ($key, '$value') ";
				$this->m_database->executeQuery($sqlcmd);
				$i++;
			}
		}

		/**
		 * [获取指定ID的所有子类别]
		 * @param  array  $aID [层次的ID]
		 * @return [array]     [类型数组]
		 */
		public function getSubtypes(array $aID)
		{
			$tables = '';
			$sqlcmdWhere ='';
			$depth = count($aID);
			$tbIDsour = $depth-1;
			$tbIDdest = $depth+1;
			$fields = array();

			for ($i=2; $i <= $depth; $i++) 
			{ 
				$tb = $depth - $i + 2;
				$value = $aID[$depth-$i];
				$tables .= ", t$i";
				$sqlcmdWhere .= " AND t$tb.tID = $value";
			}
			$sqlcmd = "SELECT t$tbIDdest.title FROM t$tbIDdest$tables WHERE t$tbIDdest.tPID = $aID[$tbIDsour]";
			$sqlcmd .= $sqlcmdWhere;

			$queryResult = $this->m_database->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}

			return $fields;
		}
	}

	//TEST
	$my = new MANAGE('localhost', '3306', 'root', '123qwe');
	$my->m_database->connect();
	if ($my->m_database->getConnectState())
	{
	echo ' 连接成功';
		
		if ($my->m_database->createDatabase('WikiNote'));
		{
			echo '创建成功';
			$my->initDatabase('WikiNote');
			$my->addUser('user1', 'pwd1');
			$my->addUser('user2', 'pwd2');
			$my->changeUserPWD('pwd2', 'user2', 'newpwd');
			$a = array(0 => '测试9',1 => '测试2' );
			$my->addType($a);
			print_r($my->getSubtypes(array('1')));
	
		}
		if ($my->m_database->dropDatabase('dd'))
		{
			echo '删除成功';
		}		
	}
	else
	{
		echo '连接失败';
	}
		
?>