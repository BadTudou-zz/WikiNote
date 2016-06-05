<?php
	require_once('SQL.php');
	class WIKINOTE_VISITOR
	{
		public $m_database;

		public function __construct($host, $port, $user, $pwd)
		{
			$this->m_database = new MYSQL($host, $port, $user, $pwd);
			$this->m_database->connect();
			$this->m_database->selectDatabase('WikiNote');
		}

		public function getNoteContent(string $title)
		{
			$sqlcmd = "SELECT title, content FROM note WHERE title = '$title'";
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			$field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
			return $field;
		}

		public function getNoteList(int $start, int $count)
		{
			$this->m_database->selectDatabase('WikiNote');
			$fields = array();
			$sqlcmd = "SELECT nID, title FROM note ORDER BY nID LIMIT $start , $count";
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}
			return $fields;
		}

		public function addUser(string $nickname, string $pwd)
		{
			$this->m_database->selectDatabase('WikiNote');
			$sqlcmd = "INSERT INTO user (nickname, pwd) values ('{$nickname}' , '{$pwd}')";
			$this->m_database->executeQuery($sqlcmd);
			return mysqli_error($this->m_database->m_resource)==''?true:false;
		}

		/**
		 * @return [string]         [成功,用户ID;失败,空字符串]
		 */
		public function loginUser(string $nickname, string $pwd)
		{
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
	}
?>