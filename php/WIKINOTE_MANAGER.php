<?php
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	MANAGE.php
	By		:	BadTudu
	Date	:	2016年4月25日13:06:05
	Note	:	后台管理
*/
	require_once('WIKINOTE_WIKINOTE_DATABASE.php');
	require_once('WIKINOTE_USER.php');
	require_once('WIKINOTE_MANAGER_USER.php');
	require_once('WIKINOTE_MANAGER_NOTETYPE.php');
	class WIKINOTE_MANAGER extends WIKINOTE_USER
	{
		protected $m_database;

		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			parent::__construct($host, $port, $user, $pwd);
			$this->m_database = new WIKINOTE_MANAGER_DATABASE($host, $port, $user, $pwd);
		}

		/**
		* @return [string]         [成功,用户ID;失败,空字符串]
		*/
		public function loginManager(string $nickname, string $pwd)
		{
			$this->m_resource->connect();
			if ($this->m_resource->selectDatabase('WikiNote'))
			{
				$sqlcmd = 'SELECT mID FROM manager WHERE nickname = \''. $nickname .'\' AND pwd = \''. $pwd .'\'';
				$queryResult = $this->m_resource->executeQuery($sqlcmd);
				if ($queryResult != FALSE)
				{
					$fields = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
					return $fields['mID'];
				}
				else
				{
					error_log('kong');
				}
				
			}
			else
			{
				error_log('切换数据库失败');
			}
			return '';
		}	

		public function initDatabase(string $dbname)
		{
			$this->m_database->initDatabase($dbname);
			$sqlcmd = "insert into manager (nickname, pwd) values ('admin', 'admin')";
			$this->m_resource->executeQuery($sqlcmd);
		}
	}	
?>