<?php
	require_once('SQL.php');
	class WIKINOTE_MANAGER_DATABASE
	{
		public $m_resource;


		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			$this->m_resource = new MYSQL($host, $port, $user, $pwd);
			$this->m_resource->connect();
			$this->m_resource->selectDatabase('WikiNote');
		}

		//创建用户表
		protected function createUserTable()
		{
			$sqlcmd = 'CREATE TABLE user 
			(
				uID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY, 
				nickname VARCHAR(128) NOT NULL,
				pwd CHAR(128) NOT NULL
			)';
			$this->m_resource->executeQuery($sqlcmd);
		}

		//创建管理员表
		public function createManagerTable()
		{
			$sqlcmd = 'CREATE TABLE manager 
			(
				mID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY, 
				nickname VARCHAR(128) NOT NULL,
				pwd CHAR(128) NOT NULL
			)';
			$this->m_resource->executeQuery($sqlcmd);
		}

		//创建笔记分类表
		protected function createNoteTypeTable()
		{
			$sqlcmd = 'CREATE TABLE type
			(
				tID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				t1ID INTEGER UNSIGNED,
				t2ID INTEGER UNSIGNED,
				t3ID INTEGER UNSIGNED,
				t4ID INTEGER UNSIGNED
			)';
			$this->m_resource->executeQuery($sqlcmd);
			for ($i=1; $i <= 4; $i++) 
			{ 
				//创建分类
				$sqlcmd = 'CREATE TABLE t'. $i .'
				(
					tPID INTEGER UNSIGNED NOT NULL,
					tID INTEGER UNSIGNED AUTO_INCREMENT  NOT NULL PRIMARY KEY,
					title VARCHAR(128) NOT NULL UNIQUE
				)';
				$this->m_resource->executeQuery($sqlcmd);

				$sqlcmd = "ALTER TABLE type ADD CONSTRAINT fk_type_t".$i."ID FOREIGN KEY(t".$i."ID) REFERENCES t".$i."(tID) ON UPDATE CASCADE ON DELETE CASCADE";
				$this->m_resource->executeQuery($sqlcmd);	

				if ($i > 1)
				{
					$sqlcmd = "ALTER TABLE t$i ADD CONSTRAINT fk_deleteORupdate_tPID_$i FOREIGN KEY(tPID) REFERENCES t" .($i-1). '(tID) ON UPDATE CASCADE ON DELETE CASCADE';
					$this->m_resource->executeQuery($sqlcmd);	
				}	
			}
		}
		
		//创建笔记表
		protected function createNoteTable()
		{
			
			$sqlcmd = 'CREATE TABLE note
			(
				nID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				notetypeID INTEGER UNSIGNED NOT NULL,
				title VARCHAR(128) NOT NULL,
				creatorID INTEGER UNSIGNED NOT 	NULL,
				createTime TIMESTAMP NOT NULL 	DEFAULT CURRENT_TIMESTAMP,
				modifyTime  TIMESTAMP NOT NULL 	DEFAULT CURRENT_TIMESTAMP ON 	UPDATE CURRENT_TIMESTAMP,
				content MEDIUMTEXT 
				/*filepath VARCHAR(128) NOT NULL*/
			)';
			$this->m_resource->executeQuery($sqlcmd);

			//创建笔记表外键
			$sqlcmd = "ALTER TABLE note ADD CONSTRAINT fk_note_creatorID FOREIGN KEY(creatorID) REFERENCES user(uID) ON UPDATE CASCADE";
			$this->m_resource->executeQuery($sqlcmd);	

			$sqlcmd = "ALTER TABLE note ADD CONSTRAINT fk_note_notetypeID FOREIGN KEY(notetypeID) REFERENCES type(tID) ON UPDATE CASCADE ON DELETE CASCADE";
			$this->m_resource->executeQuery($sqlcmd);
		}

		//创建日志表
		protected function createLogTable()
		{
			$sqlcmd = 'CREATE TABLE log
			(
				logID INTEGER UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
				uID INTEGER UNSIGNED NOT NULL ,
				nID INTEGER UNSIGNED NOT NULL ,
				replacetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				filepath VARCHAR(128) NOT NULL
			)';
			$this->m_resource->executeQuery($sqlcmd);
		}

		//创建消息表
		protected function createMessageTable()
		{
			$sqlcmd = 'CREATE TABLE msg
			(
				msgID INTEGER UNSIGNED 	AUTO_INCREMENT NOT NULL 	PRIMARY KEY,
				mtext VARCHAR(128) NOT NULL
			)';
			$this->m_resource->executeQuery($sqlcmd);
		}

		/**
		 * [初始化数据库]
		 * @param  string $dbname [数据库名称]
		 */
		public function initDatabase(string $dbname)
		{
			if ($this->m_resource->selectDatabase($dbname))
			{
				$this->createUserTable();
				$this->createManagerTable();
				$this->createNoteTypeTable();
				$this->createNoteTable();
				$this->createLogTable();
				$this->createMessageTable();
			}		
		}
	}
?>