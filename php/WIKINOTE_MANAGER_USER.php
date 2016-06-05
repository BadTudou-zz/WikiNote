<?php
	require_once('WIKINOTE_USER.php');
	class WIKINOTE_MANAGER_USER extends WIKINOTE_USER
	{

		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			$this->m_resource = new MYSQL($host, $port, $user, $pwd);
			$this->m_resource->connect();
			$this->m_resource->selectDatabase('WikiNote');
		}

		/**
		 * [删除用户]
		 * @return [bool]           [description]
		 */
		public function deleteUser(string $nickname)
		{
			$this->m_resource->selectDatabase('WikiNote');
			$sqlcmd = "DELETE FROM user WHERE  nickname = '$nickname'" ;
			return $this->m_resource->executeQuery($sqlcmd);
		}

		/**
		 * [获取指定范围用户列表]
		 * @return [array]        [用户信息]
		 */
		public function getUsersList(int $start, int $count)
		{
			$this->m_resource->selectDatabase('WikiNote');
			$fields = array();
			$sqlcmd = "SELECT uID, nickname FROM user ORDER BY uID LIMIT $start , $count";
			$queryResult = $this->m_resource->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}
			return $fields;
		}

		/**
		 * [获取指定昵称的用户]
		 * @param  string $nickname [昵称]
		 * @return [array]          [用户ID与昵称]
		 */
		public function getUsersByNickname(string $nickname)
		{
			$this->m_database->selectDatabase('WikiNote');
			$fields = array();
			$sqlcmd = "SELECT uID, nickname FROM user WHERE nickname = '$nickname' ORDER BY uID";
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}
			return $fields;	
		}

		/**
		 * [删除指定ID的用户]
		 * @param  int    $id [用户ID]
		 * @return [bool]     [状态：成功,true;失败,flase]
		 */
		public function deleteUserByID(int $id)
		{
			$this->m_database->selectDatabase('WikiNote');
			$sqlcmd = "DELETE FROM user WHERE uID = $id";
			return $this->m_database->executeQuery($sqlcmd);
		}

	}
?>