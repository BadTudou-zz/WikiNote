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
			$this->m_database->connect();
			$this->m_database->selectDatabase('WikiNote');
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

			//创建管理员表
			$sqlcmd = 'CREATE TABLE manager 
			(
				mID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY, 
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
				//创建分类
				$sqlcmd = 'CREATE TABLE t'. $i .'
				(
					tPID INTEGER UNSIGNED NOT NULL,
					tID INTEGER UNSIGNED AUTO_INCREMENT  NOT NULL PRIMARY KEY,
					title VARCHAR(128) NOT NULL UNIQUE
				)';
				$this->m_database->executeQuery($sqlcmd);

				if ($i > 1)
				{
					$sqlcmd = "ALTER TABLE t$i ADD CONSTRAINT fk_deleteORupdate_tPID_$i FOREIGN KEY(tPID) REFERENCES t" .($i-1). '(tID) ON UPDATE CASCADE ON DELETE CASCADE';
					$this->m_database->executeQuery($sqlcmd);	
				}
				
			}

			//创建日志表
			$sqlcmd = 'CREATE TABLE log
			(
				logID INTEGER UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
				uID INTEGER UNSIGNED NOT NULL ,
				nID INTEGER UNSIGNED NOT NULL ,
				replacetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				filepath VARCHAR(128) NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);

			//创建消息表
			$sqlcmd = 'CREATE TABLE msg
			(
				msgID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				mtext VARCHAR(128) NOT NULL
			)';
			$this->m_database->executeQuery($sqlcmd);	
		}

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
		 * [获取指定范围用户列表]
		 * @param  int    $start [开始的序号]
		 * @param  int    $count [个数]
		 * @return [array]        [用户信息]
		 */
		public function getUsers(int $start, int $count)
		{
			$fields = array();
			$sqlcmd = 'SELECT uID, nickname FROM user ';
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}
			return $fields;
		}
		/**
		 * [获取类型的ID，最上层]
		 * @param  array  $aType [层次类型]
		 * @return int        [存在：类型ID, 不存在：false]
		 */
		public function getTypeID(array $aType)
		{
			$tables = '';
			$sqlcmdWhere ='';
			$depth = count($aType);
			for ($i=1; $i < $depth; $i++) 
			{ 
				$tables .= ", t$i";
				$sqlcmdWhere .= " AND t".($i).".title = '". $aType[$i-1] ."'";
			}

			$sqlcmd = 'SELECT t' .$depth. ".tID FROM t$depth$tables WHERE t$depth".".title = '" .$aType[$depth-1] ."'";
			$sqlcmd .= $sqlcmdWhere;
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			if($queryResult)
			{
				$field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
				return $field['tID'];
			}

			return false;
		}
		/**
		 * [添加类别]
		 * @param array  $aType [层次类别]
		 * @param string $title [添加的类别]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function addType(array $aType, string $title)
		{
			$depth = count($aType);
			if ($depth == 0)
			{
				$sqlcmd = "INSERT INTO t1 (tPID, title) values (0, '$title')";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = 'INSERT INTO t'.($depth+1). '(tPID, title) values ('. $field. ", '$title')";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
			
		}

		/**
		 * [获取指定类型的所有子类别]
		 * @param  array  $aType [层次类型]
		 * @return array  $fields[成功：类别数组, 失败：false]
		 */
		public function getSubTypes(array $aType)
		{
			$fields = array();
			$depth = count($aType);
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = 'SELECT t' .($depth+1). '.title FROM t' .($depth+1). "  WHERE t".($depth+1). ".tPID = " .$field;
				$queryResult = $this->m_database->executeQuery($sqlcmd);
				while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
				{
					array_push($fields, $field);
				}
				return $fields;
			}
			return false;
		}

		/**
		 * [级联删除指定ID的类别]
		 * @param  array  $aType [层次类型]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function delSubTypes(array $aType)
		{
			$depth = count($aType);
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = "DELETE FROM t$depth WHERE t$depth.tID = $field";
				$queryResult = $this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
		}

		/**
		 * [重命名类型名称]
		 * @param  array  $aType [层次类型]
		 * @param  string $title [新名词]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function renameTypeTitle(array $aType, string $title)
		{
			$depth = count($aType);
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = "UPDATE  t$depth SET title = '$title' WHERE tID = $field";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
		}

		/**
		* [管理员登录]
		* @param  string $nickname [昵称]
		* @param  string $pwd      [密码]
		* @return [string]         [成功,用户ID;失败,空字符串]
		*/
		public function login(string $nickname, string $pwd)
		{
			$this->m_database->connect();
			if ($this->m_database->selectDatabase('WikiNote'))
			{
				$sqlcmd = 'SELECT mID FROM manager WHERE nickname = \''. $nickname .'\' AND pwd = \''. $pwd .'\'';
				$queryResult = $this->m_database->executeQuery($sqlcmd);
				$fields = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
				if (is_array($fields))
				{
					return $fields['mID'];
				}
			}
			return '';
		}
	}

	//TEST
	/*$my = new MANAGE('localhost', '3306', 'root', 'mysql');
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
			echo '2ok'.$my->addType(array(),'工业技术');
			echo '2ok'.$my->addType(array(),'数理化');
			echo '2ok'.$my->addType(array(),'人文');
			echo '4ok'.$my->addType(array('工业技术'),'计算机');
			echo '4ok'.$my->addType(array('工业技术'),'机械');
			echo '4ok'.$my->addType(array('工业技术'),'航天');
			echo '4ok'.$my->addType(array('工业技术', '计算机'),'编程技术');
			echo $my->renameTypeTitle(array('工业技术','计算机','编程技术'), '新编程技术');
			print_r($my->getSubTypes(array('工业技术')));
			print_r($my->getSubTypes(array('工业技术', '计算机')));
			print_r($my->getSubTypes(array('测试2')));
			echo $my->delSubTypes(array('测试'));
			echo $my->delSubTypes(array('测试2'));
	
		}
		if ($my->m_database->dropDatabase('dd'))
		{
			echo '删除成功';
		}		
	}
	else
	{
		echo '连接失败';
	}*/
		
?>